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
			->select("oc.seriado as ordenCompra, ocd.idOrdenCompra, cast(oc.fechaReg as DATE) as fechaRegOC, 
						oc.idProveedor, 
						0 as flagOcLibre,
						pr.razonSocial, pr.nroDocumento as rucProveedor, 
						/* cd.subtotal, */
						cd.cantidad * ISNULL(cd.costo, 0) as subtotal, 
						mon.nombreMoneda,
						c.idCotizacion, op.requerimiento as oper, op.idOper,
						oc.descripcionFinanzas as cotizacion, c.idCuenta, c.idCentroCosto, c.codOrdenCompra as poCliente, c.motivoAprobacion as desTracking,
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

	public function obtenerRegistrosParaFinanzasLibre($params = [])
	{
		$this->db
			->distinct()
			->select("oc.seriado as ordenCompra,
						oc.idOrdenCompra as idOrdenCompra, 
						1 as flagOcLibre,
						cast(oc.fechaReg as DATE) as fechaRegOC, 
						oc.idProveedor, pr.razonSocial, 
						pr.nroDocumento as rucProveedor,
						oc.total as subtotal,
						mon.nombreMoneda, 
						null as idCotizacion, 
						oc.requerimiento as oper, 
						null as idOper, 
						oc.descripcionCompras as cotizacion,
						oc.idCuenta, 
						oc.idCentroCosto, 
						oc.poCliente as poCliente, oc.concepto as desTracking,
						isnull(STUFF((
							SELECT ', ' + CONVERT(VARCHAR(500), numeroGr)
							FROM orden.ordenCompraGr WHERE estado = 1 AND idOrdenCompra = oc.idOrdenCompra
							FOR XML PATH('')
						), 1, 2, ''),'PENDIENTE') as numeroGR,
						oc.IGVPorcentaje as igv, 
						emp.nombre as cuenta, cc.canal + ' / ' + cc.subcanal as centroCosto", false)
			->from('orden.ordenCompraDetalle ocd')
			->join('orden.ordenCompra oc', 'ocd.idOrdenCompra = oc.idOrdenCompra')
			->join('compras.proveedor pr', 'pr.idProveedor = oc.idProveedor')
			->join('rrhh.dbo.Empresa emp', 'emp.idEmpresa = oc.idCuenta')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = oc.idCentroCosto')
			->join('compras.moneda mon', 'mon.idMoneda = oc.idMoneda')
			->where('ocd.estado', 1);

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
