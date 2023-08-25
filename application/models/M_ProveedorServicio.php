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

		if (isset($data['idProveedor'])) $this->db->where('pr.idProveedor', $data['idProveedor']);
		if (isset($data['fechaEmision'])) $this->db->where('CAST(c.fechaEmision as DATE)', $data['fechaEmision']);

		return $this->db->get();
	}
}
