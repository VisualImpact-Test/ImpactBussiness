<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ProveedorServicio extends MY_Model
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

	public function obtenerDatosReporte($data = [])
	{
		/* TODO: BORRAR CUANDO NO HAYA OBSERVACIONES DE INFORMACION - COMENTADO EL 2023-11-28
		$this->db
			->select('DISTINCT
							CONVERT(VARCHAR, min(cdpd.fechaEntrega), 103) AS fechaEntrega,
							cp.idCotizacion,
							cp.idCotizacionDetalleProveedor,
							CONVERT(VARCHAR, c.fechaEmision, 103) AS fechaEmision,
							c.nombre,
							c.motivo,
							c.total,
							cc.nombre AS cuentaCentroCosto,
							cu.nombre AS cuenta,
							cp.idProveedor,
							pr.razonSocial as proveedor,
							c.motivoAprobacion,
							c.codOrdenCompra')
			->from('compras.cotizacionDetalleProveedor cp')
			->join('compras.cotizacion c', 'c.idCotizacion = cp.idCotizacion', 'INNER')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'c.idCentroCosto = cc.idCuentaCentroCosto', 'INNER')
			->join('visualImpact.logistica.cuenta cu', 'c.idCuenta = cu.idCuenta', 'INNER')
			->join('compras.cotizacionDetalleProveedorDetalle cdpd', 'cp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor')
			->join('compras.proveedor pr', 'pr.idProveedor = cp.idProveedor')
			->where('cp.estado', '1')
			->group_by('cp.idCotizacion, cp.idCotizacionDetalleProveedor, CONVERT(VARCHAR, c.fechaEmision, 103), 
			c.nombre, c.motivo, c.total, cc.nombre, cu.nombre, cp.idProveedor, pr.razonSocial,
			c.motivoAprobacion,
			c.codOrdenCompra')
			->order_by('cp.idCotizacionDetalleProveedor desc');
		*/
		$this->db
			->distinct()
			->select('
			CONVERT(VARCHAR, min(cdpd.fechaEntrega), 103) AS fechaEntrega,
			cd.idCotizacion,
			cp.idCotizacionDetalleProveedor,
			CONVERT(VARCHAR, c.fechaEmision, 103) AS fechaEmision,
			c.nombre, c.motivo, c.total,
			cc.nombre AS cuentaCentroCosto,
			c.motivoAprobacion,
			pr.razonSocial as proveedor,
			cu.nombre AS cuenta, cd.idProveedor,
			c.codOrdenCompra')
			->from('compras.cotizacionDetalle cd')
			->join('compras.cotizacion c', 'c.idCotizacion = cd.idCotizacion', 'INNER')
			->join('compras.proveedor pr', 'pr.idProveedor = cd.idProveedor', 'INNER')
			->join('compras.cotizacionDetalleProveedor cp', 'c.idCotizacion = cp.idCotizacion', 'LEFT')
			->join('visualImpact.logistica.cuentaCentroCosto cc', 'c.idCentroCosto = cc.idCuentaCentroCosto', 'INNER')
			->join('visualImpact.logistica.cuenta cu', 'c.idCuenta = cu.idCuenta', 'INNER')
			->join('compras.cotizacionDetalleProveedorDetalle cdpd', 'cp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor AND cd.idCotizacionDetalle = cdpd.idCotizacionDetalle', 'LEFT')
			->where('cd.estado', '1')
			->group_by('cd.idCotizacion, cp.idCotizacionDetalleProveedor, CONVERT(VARCHAR, c.fechaEmision, 103), 
			c.nombre, c.motivo, c.total, cc.nombre, cu.nombre, cd.idProveedor,
			pr.razonSocial, 
			c.motivoAprobacion,
			c.codOrdenCompra')
			->order_by('cp.idCotizacionDetalleProveedor desc');

		if ($this->idUsuario != 1) $this->db->where('c.demo', 0);
		// isset($params['idProveedor']) ? $this->db->where('cd.idProveedor', $params['idProveedor']) : '';

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE) =', $data['fechaEmision']);
		if (isset($data['idCuenta'])) $this->db->where('c.idCuenta', $data['idCuenta']);
		if (isset($data['idCentroCosto'])) $this->db->where('c.idCentroCosto', $data['idCentroCosto']);
		if (isset($data['codPo_'])) $this->db->like('c.codOrdenCompra', $data['codPo_']);


		return $this->db->get();
	}
}
