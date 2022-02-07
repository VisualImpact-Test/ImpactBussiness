<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Articulo extends MY_Model
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

	public function obtenerTipoArticulo($params = [])
	{
		$sql = "
			SELECT
				idTipoArticulo AS id
				, nombre AS value
			FROM compras.tipoArticulo
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerMarcaArticulo($params = [])
	{
		$sql = "
			SELECT
				idMarcaArticulo AS id
				, nombre AS value
			FROM compras.marcaArticulo
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCategoriaArticulo($params = [])
	{
		$sql = "
			SELECT
				idCategoriaArticulo AS id
				, nombre AS value
			FROM compras.categoriaArticulo
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionArticulos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoArticulo']) ? ' AND a.idTipoArticulo = ' . $params['tipoArticulo'] : '';
		$filtros .= !empty($params['marcaArticulo']) ? ' AND a.idMarcaArticulo = ' . $params['marcaArticulo'] : '';
		$filtros .= !empty($params['categoriaArticulo']) ? ' AND a.idCategoriaArticulo = ' . $params['categoriaArticulo'] : '';
		$filtros .= !empty($params['articulo']) ? " AND a.nombre LIKE '%" . $params['articulo'] . "%'" : "";
		$filtros .= !empty($params['idArticulo']) ? ' AND a.idArticulo = ' . $params['idArticulo'] : '';

		$sql = "
			SELECT
				a.idArticulo
				, ta.idTipoArticulo
				, ta.nombre AS tipoArticulo
				, ma.idMarcaArticulo
				, ma.nombre AS marcaArticulo
				, ca.idCategoriaArticulo
				, ca.nombre AS categoriaArticulo
				, a.nombre AS articulo
				, a_l.idArticulo AS idArticuloLogistica
				, a_l.nombre AS equivalenteLogistica
				, a.estado
			FROM compras.articulo a
			JOIN compras.tipoArticulo ta ON a.idTipoArticulo = ta.idTipoArticulo
			LEFT JOIN compras.marcaArticulo ma ON a.idMarcaArticulo = ma.idMarcaArticulo
			LEFT JOIN compras.categoriaArticulo ca ON a.idCategoriaArticulo = ca.idCategoriaArticulo
			LEFT JOIN visualImpact.logistica.articulo a_l ON a.idArticuloLogistica = a_l.idArticulo
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

	public function obtenerArticulosLogistica()
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

	public function validarExistenciaArticulo($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idArticulo']) ? ' AND a.idArticulo != ' . $params['idArticulo'] : '';

		$sql = "
			SELECT
				idArticulo
			FROM compras.articulo a
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

	public function insertarArticulo($params = [])
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

	public function actualizarArticulo($params = [])
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
