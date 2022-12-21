<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{

	var $insertId;
	var $tablas;

	public function actualizarMasivo($table, $input, $where)
	{
		$update = $this->db->update_batch($table, $input, $where);
		return $update;
	}

	public function deleteMasivo($table, $columna, $ids)
	{
		$this->db->where_in($columna, $ids);
		$delete = $this->db->delete($table);
		return $delete;
	}

	public function verificarRepetido($tabla, $where)
	{
		$this->db->where($where);
		$incidencias = count($this->db->get($tabla)->result());

		if ($incidencias > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function validar_filas_unicas_HT($post)
	{
		$listas = array();
		foreach ($post as $index => $value) {
			$listas[$index] = $value['idLista'];
		}

		if (count($listas) != count(array_unique($listas))) {
			return false;
		} else {
			return true;
		}
	}

	public function getWhereJoinMultiple($tabla, $where, $select = '*', $join = [], $orden = '')
	{
		// Las abreviaturas de las tablas deben estar definidas en las variables.
		// Ejemplo $tabla = $join['tabla'] = 'ImpactTrade_bd.trade.tablaDeEjemplo tb';
		$this->db->select($select, false);
		$this->db->from($tabla);
		foreach ($join as $key => $value) {
			$this->db->join($value['tabla'], $value['on'], $value['tipo']);
		}
		foreach ($where as $key => $value) {
			if ($key == 0) {
				$this->db->where($value);
			} else {
				$this->db->or_group_start()->where($value)->group_end();
			}
		}
		if ($orden != '') {
			$this->db->order_by($orden);
		}
		return $this->db->get();
	}

	public function calcularDiasHabiles($data)
	{
		//Fecha debe estar en formato YYYY-MM-DD, de ser necesario agregar un conversor en el codigo.
		$fecha = !empty($data['fecha']) ? date("Ymd", strtotime($data['fecha'])) : date('Ymd');
		$dias = ($data['dias']>0) ? $data['dias'] : 0;

		$sql = "
			DECLARE @fecha INT = " . $fecha . ";
			DECLARE @row INT = " . $dias . ";

			WITH lst_fechasHabiles AS (
				SELECT TOP " . ($dias ? $dias : 1) . " -- No lo pude aplicar con el DECLARE asi que lo puse asi...
					*, ROW_NUMBER() OVER(ORDER BY idTiempo) as row
				FROM General.dbo.tiempo 
				where idTiempo >".($dias ? '' : '=')." @fecha

				-- EXCLUIR SAB Y DOM
				AND idDia not in (6,7)

				-- EXCLUIR FERIADOS
				AND idFeriado is null

				ORDER BY idTiempo
			)

			select * from lst_fechasHabiles where row = ".($dias ? '@row' : '1')."
		";

		$query = $this->db->query($sql);

		$row = $query->row_array();

		$rpta['fecha'] = $row['fecha'];

		// Si se necesitan más retornos agregar datos al array de $rpta.
		return $rpta;
	}

	public function contarDiasHabiles($data)
	{
		// Fecha debe estar en formato YYYY-MM-DD, de ser necesario agregar un conversor en el codigo.
		$fechaIni = !empty($data['fechaIni']) ? date("Ymd", strtotime($data['fechaIni'])) : date('Ymd');
		$fechaFin = !empty($data['fechaFin']) ? date("Ymd", strtotime($data['fechaFin'])) : date('Ymd');

		// Por si los días son negativos
		$rpta['valor'] = 1;
		if (intval($fechaIni) > intval($fechaFin)) {
			$fechaIniResp = $fechaIni;
			$fechaIni = $fechaFin;
			$fechaFin = $fechaIniResp;
			$rpta['valor'] = -1;
		}

		$sql = "
			DECLARE @fechaIni INT = " . $fechaIni . ";
			DECLARE @fechaFin INT = " . $fechaFin . ";

			WITH lst_fechasHabiles AS (
				SELECT *
				FROM General.dbo.tiempo 
				where idTiempo > @fechaIni and idTiempo <= @fechaFin

				-- EXCLUIR SAB Y DOM
				AND idDia not in (6,7)

				-- EXCLUIR FERIADOS
				AND idFeriado is null
			)

			select COUNT(*) as conteo from lst_fechasHabiles order by 1
		";

		$query = $this->db->query($sql);

		$row = $query->row_array();

		$rpta['conteo'] = intval($row['conteo']) * intval($rpta['valor']);

		// Si se necesitan más retornos agregar datos al array de $rpta.
		return $rpta;
	}

	public function insertarMasivo($table, $input)
	{
		return $this->db->insert_batch($table, $input);
	}

	public function saveFileWasabi($config = [])
	{
		if (empty($config['base64'])) return "";

		$carpeta = $config['carpeta'];
		$nombreUnico = $config['nombreUnico'];

		$file =
			[
				'base64' => $config['base64'],
				'name' => $config['name'],
				'type' => $config['type'],
				'extension' => explode('/', $config['type'])[1],
				'extensionVisible' => !empty($config['extensionVisible']) ? $config['extensionVisible'] : '',
			];

		$this->load->library('s3');

		$s3Client = $this->s3;

		$s3Client::setAuth('BS9EM7XW1288NCZXLL6G', 'cIe5Mfe7ovcjsm3waEcmqGDun6Xu6d0ftAepy3AS');

		$s3Client->setEndpoint('s3.us-central-1.wasabisys.com');

		$s3Client->setRegion('us-central-1');
		// $file_url = '';
		$file_url = FCPATH . $nombreUnico . "_WASABI.{$file['extension']}";
		$base64 = str_replace("data:{$file['type']};base64,", '', $file['base64']);
		$base64 = str_replace(' ', '+', $base64);
		$content = base64_decode($base64);

		file_put_contents($file_url, $content);

		$extensionForName = '';

		if (!empty($file['extensionVisible'])) $extensionForName = $file['extensionVisible'];
		else $extensionForName = $file['extension'];

		$response = S3::putObject(S3::inputFile($file_url, false), 'impact.business/' . $carpeta, $nombreUnico . "_WASABI.{$extensionForName}", S3::ACL_PUBLIC_READ);
		unlink($file_url);
		return $nombreUnico . "_WASABI.{$extensionForName}";
	}
}
