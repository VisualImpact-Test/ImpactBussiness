<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Presupuesto extends MY_Model
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

	public function obtenerInformacionPresupuesto($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['tipoArticulo']) ? ' AND a.idTipoArticulo = ' . $params['tipoArticulo'] : '';
		$filtros .= !empty($params['marcaArticulo']) ? ' AND a.idMarcaArticulo = ' . $params['marcaArticulo'] : '';
		$filtros .= !empty($params['categoriaArticulo']) ? ' AND a.idCategoriaArticulo = ' . $params['categoriaArticulo'] : '';
		$filtros .= !empty($params['articulo']) ? " AND a.nombre LIKE '%" . $params['articulo'] . "%'" : "";
		$filtros .= !empty($params['idArticulo']) ? ' AND a.idArticulo = ' . $params['idArticulo'] : '';

		$sql = "
			SELECT
				p.idPresupuesto
				, p.nombre AS presupuesto
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
				, tp.idTipoPresupuesto
				, tp.nombre AS tipoPresupuesto
				, p.nroPresupuesto
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.idCuentaCentroCosto AS cuentaCentroCosto
				, p.estado
			FROM compras.presupuesto p
			JOIN compras.tipoPresupuesto tp ON p.idTipoPresupuesto = tp.idTipoPresupuesto
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
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
