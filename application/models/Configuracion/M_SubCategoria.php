<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_SubCategoria extends MY_Model
{
	var $resultado = [
		'query' => '',
		'estado' => false,
		'id' => null,
		'msg' => ''
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function obtenerInformacionSubCategoria($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['SubCategoria']) ? " AND a.nombre LIKE '%{$params['SubCategoria']}%' " :'';
		//$filtros .= !empty($params['categoria']) ? ' AND a.nombre LIKE % ' . $params['categoria'] . '' : '';
		$filtros .= !empty($params['idItemSubCategoria']) ? ' AND a.idItemSubCategoria = ' . $params['idItemSubCategoria'] : '';

		$sql = "
			SELECT
				a.idItemSubCategoria
				, a.nombre AS SubCategoria
				, a.estado
			FROM compras.itemSubCategoria a
			WHERE 1 = 1
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function validarExistenciaSubCategoria($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItemSubCategoria']) ? ' AND a.idItemSubCategoria != ' . $params['idItemSubCategoria'] : '';
		$itemSubCategoria = trim($params['nombre']);

		$sql = "
			SELECT
				idItemSubCategoria
			FROM compras.itemSubCategoria a
			WHERE
			a.nombre = '{$itemSubCategoria}'
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function insertarSubCategoria($params = [])
	{
		$query = $this->db->insert($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarSubCategoria($params = [])
	{
		$query = $this->db->update($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
