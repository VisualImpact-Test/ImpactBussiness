<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model{

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
	public function validar_filas_unicas_HT($post){
		$listas = array();
		foreach($post as $index => $value){
			$listas[$index] = $value['idLista'];
		}

		if(count($listas) != count(array_unique($listas))){
			return false;
		}else{
			return true;
		}

	}

	public function getWhereJoinMultiple($tabla, $where, $select = '*', $join = [], $orden = ''){
		// Las abreviaturas de las tablas deben estar definidas en las variables.
		// Ejemplo $tabla = $join['tabla'] = 'ImpactTrade_bd.trade.tablaDeEjemplo tb';
		$this->db->select($select,false);
		$this->db->from($tabla);
		foreach ($join as $key => $value) {
			$this->db->join($value['tabla'],$value['on'],$value['tipo']);
		}
		foreach ($where as $key => $value) {
			if ($key==0){ $this->db->where($value); }
			else {
				$this->db->or_group_start()->where($value)->group_end();
			}
		}
		if($orden != ''){
			$this->db->order_by($orden);
		}
		return $this->db->get();
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
            'extension' => explode('/',$config['type'])[1],
			'extensionVisible' => !empty($config['extensionVisible']) ? $config['extensionVisible'] : '',
        ];

		$this->load->library('s3');

		$s3Client = $this->s3;

		$s3Client::setAuth('BS9EM7XW1288NCZXLL6G', 'cIe5Mfe7ovcjsm3waEcmqGDun6Xu6d0ftAepy3AS');		

		$s3Client->setEndpoint('s3.us-central-1.wasabisys.com');

		$s3Client->setRegion('us-central-1');
		// $file_url = '';
		$file_url = FCPATH . $nombreUnico."_WASABI.{$file['extension']}";
        $base64 = str_replace("data:{$file['type']};base64,", '', $file['base64']);
        $base64 = str_replace(' ', '+', $base64);
        $content = base64_decode($base64);

		file_put_contents($file_url, $content);

		$extensionForName = '';

		if(!empty($file['extensionVisible'])) $extensionForName = $file['extensionVisible'];
		else $extensionForName = $file['extension'];

		$response = S3::putObject(S3::inputFile($file_url, false), 'impact.business/'.$carpeta, $nombreUnico."_WASABI.{$extensionForName}", S3::ACL_PUBLIC_READ);
		unlink($file_url);
		return $nombreUnico."_WASABI.{$extensionForName}";
	}
}