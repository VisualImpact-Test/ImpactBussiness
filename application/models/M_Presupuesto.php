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

	public function obtenerTipoPresupuesto($params = [])
	{
		$sql = "
			SELECT
				idTipoPresupuesto AS id
				, nombre AS value
			FROM compras.tipoPresupuesto
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCuenta($params = [])
	{
		$sql = "
			SELECT
				idCuenta AS id
				, nombre AS value
			FROM visualImpact.logistica.cuenta
			WHERE estado = 1
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenerCuentaCentroCosto($params = [])
	{
		$sql = "
			SELECT
				idCuentaCentroCosto AS id
				, idCuenta AS idDependiente
				, nombre AS value
			FROM visualImpact.logistica.cuentaCentroCosto
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
		$filtros .= !empty($params['tipoPresupuesto']) ? ' AND p.idTipoPresupuesto = ' . $params['tipoPresupuesto'] : '';
		$filtros .= !empty($params['cuenta']) ? ' AND p.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND p.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['presupuesto']) ? " AND p.nombre LIKE '%" . $params['presupuesto'] . "%'" : "";

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
				, cc.nombre AS cuentaCentroCosto
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

	public function obtenerInformacionPresupuestoDetalle($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idPresupuesto']) ? ' AND p.idPresupuesto = ' . $params['idPresupuesto'] : '';

		$sql = "
			SELECT
				p.idPresupuesto
				, p.nombre AS presupuesto
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, tp.nombre AS tipoPresupuesto
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.nombre AS estadoItem
			FROM compras.presupuesto p
			JOIN compras.presupuestoDetalle pd ON p.idPresupuesto = pd.idPresupuesto
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.tipoPresupuesto tp ON p.idTipoPresupuesto = tp.idTipoPresupuesto
			JOIN compras.estadoItem ei ON pd.idEstadoItem = ei.idEstadoItem
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

	public function obtenerArticuloServicio()
	{
		$sql = "
			SELECT
				a.idArticulo AS value
				, a.nombre AS label
				, ta.costo
				, 1 AS tipo
			FROM compras.articulo a
			LEFT JOIN compras.tarifarioArticulo ta ON a.idArticulo = ta.idArticulo
			WHERE (ta.flag_actual = 1 OR ta.flag_actual IS NULL)
			UNION
			SELECT
				s.idServicio AS value
				, s.nombre AS label
				, ts.costo
				, 2 AS tipo
			FROM compras.servicio s
			LEFT JOIN compras.tarifarioServicio ts ON s.idServicio = s.idServicio
			WHERE (ts.flag_actual = 1 OR ts.flag_actual IS NULL)
		";

		$result = $this->db->query($sql)->result_array();

		// $this->CI->aSessTrack[] = ['idAccion' => 5, 'tabla' => 'logistica.articulo', 'id' => null];
		return $result;
	}

	public function validarExistenciaPresupuesto($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idPresupuesto']) ? ' AND p.idPresupuesto != ' . $params['idPresupuesto'] : '';

		$sql = "
			SELECT
				idPresupuesto
			FROM compras.presupuesto p
			WHERE
			(p.nombre LIKE '%{$params['nombre']}%')
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

	public function insertarPresupuesto($params = [])
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

	public function insertarPresupuestoDetalle($params = [])
	{
		$query = $this->db->insert_batch($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}

	public function actualizarPresupuesto($params = [])
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
