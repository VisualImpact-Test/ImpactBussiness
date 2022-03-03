<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Personal extends MY_Model
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

	public function obtenerItemTipo($params = [])
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

	public function obtenerInformacionPersonal($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['cuenta']) ? ' AND p.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND p.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['cotizacion']) ? " AND p.nombre LIKE '%" . $params['cotizacion'] . "%'" : "";

		$sql = "
			SELECT
				p.idPersonal
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoPersonal
				, p.codPersonal
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idPersonalEstado = ce.idPersonalEstado
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

	public function obtenerInformacionPersonalDetalle($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idPersonal']) ? ' AND p.idPersonal = ' . $params['idPersonal'] : '';

		$sql = "
			SELECT
				p.idPersonal
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, p.codPersonal
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, ce.nombre AS cotizacionEstado
			
				, it.idItemTipo
				, it.nombre AS itemTipo
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idItemEstado
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
				, cde.nombre AS cotizacionDetalleEstado
				, CONVERT( VARCHAR, pd.fechaCreacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaCreacion, 108) AS fechaCreacion
				, CONVERT( VARCHAR, pd.fechaModificacion, 103) + ' ' + CONVERT( VARCHAR, pd.fechaModificacion, 108) AS fechaModificacion
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idPersonal = pd.idPersonal
			JOIN compras.itemTipo it ON pd.idItemTipo = it.idItemTipo
			JOIN compras.cotizacionEstado ce ON p.idPersonalEstado = ce.idPersonalEstado
			JOIN compras.cotizacionDetalleEstado cde ON pd.idPersonalDetalleEstado = cde.idPersonalDetalleEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
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

	public function validarExistenciaPersonal($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idPersonal']) ? ' AND p.idPersonal != ' . $params['idPersonal'] : '';

		$sql = "
			SELECT
				idPersonal
			FROM compras.cotizacion p
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

	public function insertarPersonal($params = [])
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

	public function insertarPersonalDetalle($params = [])
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

	public function actualizarPersonal($params = [])
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
