<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Marca extends MY_Model
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

	public function obtenerInformacionMarcas($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['marca']) ? " AND a.nombre LIKE '%" . $params['marca'] . "%'" : "";
		$filtros .= !empty($params['idMarcaArticulo']) ? ' AND a.idItemMarca = ' . $params['idMarcaArticulo'] : '';

		$sql = "
			SELECT
				a.idItemMarca
				, a.nombre AS marca
				, a.estado
			FROM compras.itemMarca a
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

	public function validarExistenciaMarca($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItemMarca']) ? ' AND a.idItemMarca != ' . $params['idItemMarca'] : '';
		$itemMarca = trim($params['nombre']);

		$sql = "
			SELECT
				idItemMarca
			FROM compras.itemMarca a
			WHERE
			a.nombre = '{$itemMarca}'
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

	public function insertarMarca($params = [])
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

	public function actualizarMarca($params = [])
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
