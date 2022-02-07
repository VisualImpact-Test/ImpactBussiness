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

	public function obtenerProveedor($params = [])
	{
		$sql = "
			SELECT
				idProveedor AS id
				, razonSocial AS value
			FROM compras.proveedor
			WHERE idEstado = 2
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionTAHistorico($params = [])
	{
		$sql = "
			SELECT
				ta.idTarifarioArticulo
				, CONVERT(VARCHAR, tah.fecIni, 103) AS fecIni
				, ISNULL(CONVERT(VARCHAR, tah.fecFin, 103), 'En curso') AS fecFin
				, tah.costo
				, p.razonSocial AS proveedor
			FROM compras.tarifarioArticulo ta
			JOIN compras.tarifarioArticuloHistorico tah ON ta.idTarifarioArticulo = tah.idTarifarioArticulo
			JOIN compras.proveedor p ON ta.idProveedor = p.idProveedor
			WHERE ta.idTarifarioArticulo = {$params['idTarifarioArticulo']}
			ORDER BY tah.idTarifarioArticuloHistorico DESC
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerInformacionTarifarioArticulos($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoArticulo']) ? ' AND ta.idTipoArticulo = ' . $params['tipoArticulo'] : '';
		$filtros .= !empty($params['marcaArticulo']) ? ' AND ma.idMarcaArticulo = ' . $params['marcaArticulo'] : '';
		$filtros .= !empty($params['categoriaArticulo']) ? ' AND ca.idCategoriaArticulo = ' . $params['categoriaArticulo'] : '';
		$filtros .= !empty($params['articulo']) ? " AND a.nombre LIKE '%" . $params['articulo'] . "%'" : "";
		$filtros .= !empty($params['proveedor']) ? ' AND p.idProveedor = ' . $params['proveedor'] : '';
		$filtros .= !empty($params['chMostrar']) ? ' AND tfa.flag_actual = ' . $params['chMostrar'] : '';
		$filtros .= !empty($params['precioMinimo']) ? ' AND tfa.costo >= ' . $params['precioMinimo'] : '';
		$filtros .= !empty($params['precioMaximo']) ? ' AND tfa.costo <= ' . $params['precioMaximo'] : '';
		$filtros .= !empty($params['idTarifarioArticulo']) ? ' AND tfa.idTarifarioArticulo = ' . $params['idTarifarioArticulo'] : '';

		$sql = "
			SELECT
				tfa.idTarifarioArticulo
				, ma.idMarcaArticulo
				, ma.nombre AS marcaArticulo
				, ca.idCategoriaArticulo
				, ca.nombre AS categoriaArticulo
				, ta.idTipoArticulo
				, ta.nombre AS tipoArticulo
				, a.idArticulo
				, a.nombre AS articulo
				, p.idProveedor
				, p.razonSocial AS proveedor
				, tfa.costo
				, tfa.flag_actual
				, tfa.estado
			FROM compras.tarifarioArticulo tfa
			JOIN compras.proveedor p ON tfa.idProveedor = p.idProveedor
			JOIN compras.articulo a ON tfa.idArticulo = a.idArticulo
			LEFT JOIN compras.marcaArticulo ma ON a.idMarcaArticulo = ma.idMarcaArticulo
			LEFT JOIN compras.categoriaArticulo ca ON a.idCategoriaArticulo = ca.idCategoriaArticulo
			LEFT JOIN compras.tipoArticulo ta ON a.idTipoArticulo = ta.idTipoArticulo
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

	public function obtenerArticulos()
	{
		$sql = "
			SELECT
				a.idArticulo AS value
				, a.nombre AS label
			FROM compras.articulo a
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.articulo', 'id' => null];
		return $result;
	}

	public function validarExistenciaTarifarioArticulo($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idTarifarioArticulo']) ? ' AND ta.idTarifarioArticulo != ' . $params['idTarifarioArticulo'] : '';

		$sql = "
			SELECT
				idTarifarioArticulo
			FROM compras.tarifarioArticulo ta
			WHERE
			ta.idArticulo = {$params['idArticulo']} AND ta.idProveedor = {$params['idProveedor']}
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

	public function validarTarifarioArticuloActual($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idTarifarioArticulo']) ? ' AND ta.idTarifarioArticulo != ' . $params['idTarifarioArticulo'] : '';

		$sql = "
			SELECT
				idTarifarioArticulo
			FROM compras.tarifarioArticulo ta
			WHERE
			ta.idArticulo = {$params['idArticulo']} AND flag_actual = 1
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

	public function insertarTarifarioArticulo($params = [])
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

	public function actualizarTarifarioArticulo($params = [])
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
