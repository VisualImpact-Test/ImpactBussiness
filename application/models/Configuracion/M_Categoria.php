<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Categoria extends MY_Model
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

	public function obtenerTipoCategoria($params = [])
	{
		$sql = "
			SELECT
				idTipoCategoria AS id
				, nombre AS value
			FROM compras.itemCategoria
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionCategorias($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['categoria']) ? " AND a.nombre LIKE '%{$params['categoria']}%' " :'';
		//$filtros .= !empty($params['categoria']) ? ' AND a.nombre LIKE % ' . $params['categoria'] . '' : '';
		$filtros .= !empty($params['idCategoriaArticulo']) ? ' AND a.idItemCategoria = ' . $params['idCategoriaArticulo'] : '';

		$sql = "
			SELECT
				a.idItemCategoria
				, a.nombre AS categoria
				, a.estado
			FROM compras.itemCategoria a
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

	public function validarExistenciaCategoria($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItemCategoria']) ? ' AND a.idItemCategoria != ' . $params['idItemCategoria'] : '';
		$itemCategoria = trim($params['nombre']);

		$sql = "
			SELECT
				idItemCategoria
			FROM compras.itemCategoria a
			WHERE
			a.nombre = '{$itemCategoria}'
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

	public function insertarCategoria($params = [])
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

	public function actualizarCategoria($params = [])
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
