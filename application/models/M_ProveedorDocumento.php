<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ProveedorDocumento extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function obtenerRegistrosParaFinanzas($params = [])
	{
		$this->db
			->select("ocd.idOrdenCompra, cast(oc.fechaReg as DATE) as fechaRegOC, oc.idProveedor, 
						pr.razonSocial, pr.nroDocumento as rucProveedor, 
						/* cd.subtotal, */
						cd.cantidad * ISNULL(cd.costo, 0) as subtotal, 
						mon.nombreMoneda,
						c.idCotizacion, op.requerimiento as oper, op.idOper,
						c.nombre as cotizacion, c.idCuenta, c.idCentroCosto, c.codOrdenCompra as poCliente, c.motivoAprobacion as desTracking,
						ISNULL(c.numeroGR, 'PENDIENTE') as numeroGR,
						ISNULL(oc.igv, 0) as igv,
						emp.nombre as cuenta, cc.canal + ' / ' + cc.subcanal as centroCosto", false)
			->from('compras.ordenCompraDetalle ocd')
			->join('compras.ordenCompra oc', 'ocd.idOrdenCompra = oc.idOrdenCompra')
			->join('compras.proveedor pr', 'pr.idProveedor = oc.idProveedor')
			->join('compras.cotizacionDetalle cd', 'cd.idCotizacionDetalle = ocd.idCotizacionDetalle')
			->join('compras.cotizacion c', 'c.idCotizacion = cd.idCotizacion')
			->join('compras.operDetalle od', 'od.idCotizacion = c.idCotizacion and od.estado = 1')
			->join('compras.oper op', 'op.idOper = od.idOper')
			->join('rrhh.dbo.Empresa emp', 'emp.idEmpresa = c.idCuenta')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = c.idCentroCosto')
			->join('compras.moneda mon', 'mon.idMoneda = oc.idMoneda')
			->where('ocd.estado', 1)->where('c.idUsuarioReg != 1')
			->order_by('ocd.idOrdenCompra desc');

		if (!empty($params['idProveedor'])) $this->db->where('oc.idProveedor', $params['idProveedor']);
		if (!empty($params['idCuenta'])) $this->db->where('c.idCuenta', $params['idCuenta']);
		if (!empty($params['fechaInicio'])) $this->db->where('CAST(oc.fechaReg as DATE) >=', $params['fechaInicio']);
		if (!empty($params['fechaFinal'])) $this->db->where('CAST(oc.fechaReg as DATE) <=', $params['fechaFinal']);
		return $this->db->get();
	}

	public function getProveedoresQueTienenOC()
	{
		$this->db
			->distinct()
			->select('p.*')
			->from('compras.ordenCompra oc')
			->join('compras.proveedor p', 'p.idProveedor = oc.idProveedor')
			->order_by('razonSocial');

		return $this->db->get();
	}
}
