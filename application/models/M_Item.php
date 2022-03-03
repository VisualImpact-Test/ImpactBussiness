<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Item extends MY_Model
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

	public function obtenerTipoItem($params = [])
	{
		$sql = "
			SELECT
				idItemTipo AS id
				, nombre AS value
			FROM compras.itemTipo
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerMarcaItem($params = [])
	{
		$sql = "
			SELECT
				idItemMarca AS id
				, nombre AS value
			FROM compras.itemMarca
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCategoriaItem($params = [])
	{
		$sql = "
			SELECT
				idItemCategoria AS id
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

	public function obtenerInformacionItems($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoItem']) ? ' AND a.idItemTipo = ' . $params['tipoItem'] : '';
		$filtros .= !empty($params['itemMarca']) ? ' AND a.idItemMarca = ' . $params['itemMarca'] : '';
		$filtros .= !empty($params['itemCategoria']) ? ' AND a.idItemCategoria = ' . $params['itemCategoria'] : '';
		$filtros .= !empty($params['item']) ? " AND a.nombre LIKE '%" . $params['item'] . "%'" : "";
		$filtros .= !empty($params['idItem']) ? ' AND a.idItem = ' . $params['idItem'] : '';

		$sql = "
			SELECT
				a.idItem
				, ta.idItemTipo
				, ta.nombre AS tipoItem
				, ma.idItemMarca
				, ma.nombre AS itemMarca
				, ca.idItemCategoria
				, ca.nombre AS itemCategoria
				, a.nombre AS item
				, a_l.idArticulo AS idItemLogistica
				, a_l.nombre AS equivalenteLogistica
				, a.estado
			FROM compras.item a
			JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN visualImpact.logistica.articulo a_l ON a.idItemLogistica = a_l.idArticulo
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

	public function obtenerItemsLogistica()
	{
		$sql = "
			SELECT
				a.idArticulo AS value
				, ISNULL(a.codigo + ' - ','') + a.nombre AS label
				, um.idUnidadMedida AS idum
				, um.nombre AS um
				--, c.idCuenta
				--, c.nombre
			FROM visualimpact.logistica.articulo a
			LEFT JOIN visualimpact.logistica.articulo_det ad ON a.idArticulo = ad.idArticulo
			LEFT JOIN visualimpact.logistica.unidad_medida um ON ad.idUnidadMedida = um.idUnidadMedida
			--LEFT JOIN visualimpact.logistica.articulo_marca am on a.idMarca = am.idMarca
			--LEFT JOIN visualimpact.logistica.articulo_marca_cuenta amc ON am.idMarca = amc.idMarca
			--LEFT JOIN visualimpact.logistica.cuenta c ON amc.idCuenta = c.idCuenta
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.articulo', 'id' => null];
		return $result;
	}

	public function validarExistenciaItem($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idItem']) ? ' AND a.idItem != ' . $params['idItem'] : '';

		$sql = "
			SELECT
				idItem
			FROM compras.item a
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

	public function insertarItem($params = [])
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

	public function actualizarItem($params = [])
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
