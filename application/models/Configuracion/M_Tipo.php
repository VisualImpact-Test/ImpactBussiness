<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Tipo extends MY_Model
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

	//ARTICULO

	public function obtenerInformacionTiposArticulo($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipo']) ? " AND a.nombre LIKE '%" . $params['tipo'] . "%'" : "";
		$filtros .= !empty($params['idTipo']) ? ' AND a.idTipoArticulo = ' . $params['idTipo'] : '';

		$sql = "
			SELECT
				a.idTipoArticulo AS idTipo
				, a.nombre AS tipo
				, a.estado
			FROM compras.tipoArticulo a
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

	public function validarExistenciaTipoArticulo($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idTipo']) ? ' AND a.idTipoArticulo != ' . $params['idTipo'] : '';

		$sql = "
			SELECT
				idTipoArticulo AS idTipo
			FROM compras.tipoArticulo a
			WHERE
			(a.nombre LIKE '%{$params['nombre']}%')
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

	public function insertarTipoArticulo($params = [])
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

	public function actualizarTipoArticulo($params = [])
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

	//SERVICIO

	public function obtenerInformacionTiposServicio($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipo']) ? " AND a.nombre LIKE '%" . $params['tipo'] . "%'" : "";
		$filtros .= !empty($params['idTipo']) ? ' AND a.idTipoServicio = ' . $params['idTipo'] : '';

		$sql = "
			SELECT
				a.idTipoServicio AS idTipo
				, a.nombre AS tipo
				, a.estado
			FROM compras.tipoServicio a
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

	public function validarExistenciaTipoServicio($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idTipo']) ? ' AND a.idTipoServicio != ' . $params['idTipo'] : '';

		$sql = "
			SELECT
				idTipoServicio AS idTipo
			FROM compras.tipoServicio a
			WHERE
			(a.nombre LIKE '%{$params['nombre']}%')
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

	public function insertarTipoServicio($params = [])
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

	public function actualizarTipoServicio($params = [])
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
