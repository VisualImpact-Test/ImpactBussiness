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
			->select("comp.numeroDocumento,comp.fechaEmision,oc.seriado as ordenCompra, ocd.idOrdenCompra, cast(oc.fechaReg as DATE) as fechaRegOC, 
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
						emp.nombre as cuenta, cc.canal + ' / ' + cc.subcanal as centroCosto,
						0 AS flagOcLibre,
						REPLACE(
								   STUFF((SELECT CHAR(13) + CHAR(10) + b.nombre + ' - ' + ifb_inner.cuenta
										  FROM compras.informacionBancariaProveedor as ifb_inner
										  INNER JOIN dbo.banco as b ON ifb_inner.idBanco = b.idBanco
										  WHERE ifb_inner.idProveedor = ibp.idProveedor
										  FOR XML PATH(''), TYPE).value('.', 'VARCHAR(MAX)'), 1, 2, ''), 
								   '&#x0D;&#x0A;', '') as cuentas_bancos,
						REPLACE(
									STUFF((SELECT CHAR(13) + CHAR(10) + 'CCI' + 
										CASE 
										WHEN ifb_inner.cuenta = '-' THEN ''
											ELSE ' - ' + ifb_inner.cci
										END
									FROM compras.informacionBancariaProveedor as ifb_inner
									INNER JOIN dbo.banco as b ON ifb_inner.idBanco = b.idBanco
									WHERE ifb_inner.idProveedor = ibp.idProveedor
										FOR XML PATH(''), TYPE).value('.', 'VARCHAR(MAX)'), 1, 2, ''), 
										'&#x0D;&#x0A;', '') as ccis_bancos,
								   (SELECT COUNT(*) FROM sustento.comprobante WHERE idOrdenCompra = oc.idOrdenCompra AND flagOcLibre = '0' AND flagRevisado = 1 AND estado = 1 AND flagAprobadoFinanza = 1) + 1 as aprobados,
								   (SELECT COUNT(*) FROM sustento.comprobante WHERE idOrdenCompra = oc.idOrdenCompra AND flagOcLibre = '0' AND flagRevisado = 1 AND estado = 1 ) + 1 as totalDocumentos", false)
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
			->join('sustento.comprobante comp', 'comp.idOrdenCompra = oc.idOrdenCompra AND comp.idFormatoDocumento = 2 AND comp.estado = 1', 'LEFT')
			->join('compras.informacionBancariaProveedor ibp', 'ibp.idProveedor = PR.idProveedor', 'LEFT')
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
			->select("comp.numeroDocumento,comp.fechaEmision,oc.seriado as ordenCompra,
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
						emp.nombre as cuenta, cc.canal + ' / ' + cc.subcanal as centroCosto,
						1 AS flagOcLibre,
						REPLACE(
								   STUFF((SELECT CHAR(13) + CHAR(10) + b.nombre + ' - ' + ifb_inner.cuenta
										  FROM compras.informacionBancariaProveedor as ifb_inner
										  INNER JOIN dbo.banco as b ON ifb_inner.idBanco = b.idBanco
										  WHERE ifb_inner.idProveedor = ibp.idProveedor
										  FOR XML PATH(''), TYPE).value('.', 'VARCHAR(MAX)'), 1, 2, ''), 
								   '&#x0D;&#x0A;', '') as cuentas_bancos ,
						REPLACE(
									STUFF((SELECT CHAR(13) + CHAR(10) + 'CCI' + 
										CASE 
										WHEN ifb_inner.cuenta = '-' THEN ''
											ELSE ' - ' + ifb_inner.cci
										END
									FROM compras.informacionBancariaProveedor as ifb_inner
									INNER JOIN dbo.banco as b ON ifb_inner.idBanco = b.idBanco
									WHERE ifb_inner.idProveedor = ibp.idProveedor
										FOR XML PATH(''), TYPE).value('.', 'VARCHAR(MAX)'), 1, 2, ''), 
										'&#x0D;&#x0A;', '') as ccis_bancos,
								   (SELECT COUNT(*) FROM sustento.comprobante WHERE idOrdenCompra = oc.idOrdenCompra AND flagOcLibre = '1' AND flagRevisado = 1 AND estado = 1 AND flagAprobadoFinanza = 1) + 1 as aprobados,
								   (SELECT COUNT(*) FROM sustento.comprobante WHERE idOrdenCompra = oc.idOrdenCompra AND flagOcLibre = '1' AND flagRevisado = 1 AND estado = 1 ) + 1 as totalDocumentos", false)
			->from('orden.ordenCompraDetalle ocd')
			->join('orden.ordenCompra oc', 'ocd.idOrdenCompra = oc.idOrdenCompra')
			->join('compras.proveedor pr', 'pr.idProveedor = oc.idProveedor')
			->join('rrhh.dbo.Empresa emp', 'emp.idEmpresa = oc.idCuenta')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = oc.idCentroCosto')
			->join('compras.moneda mon', 'mon.idMoneda = oc.idMoneda')
			->join('sustento.comprobante comp', 'comp.idOrdenCompra = oc.idOrdenCompra AND comp.idFormatoDocumento = 2 AND comp.estado = 1', 'LEFT')
			->join('compras.informacionBancariaProveedor ibp', 'ibp.idProveedor = PR.idProveedor', 'LEFT')
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

	public function obtenerOCEmail($params = [])
	{
		$this->db
			->distinct()
			->select("mp.nombre AS metodoPago, mp.cantDias, c.fechaAprobadoFinanza,
			DATENAME(month, c.fechaAprobadoFinanza) AS mesAprobacionFinanza,
			p.nroDocumento AS ruc, p.razonSocial, oc.descripcionFinanzas AS descripcionCompras,
			cc.canal + ' - ' + cc.subcanal centroCosto, oc.seriado numeroOC, oc.pocliente,
			tc.nombre AS tipoComprobante, c.numeroDocumento AS serieFactura", false)
			->from('compras.ordenCompra oc')
			->join('compras.metodoPago mp', 'mp.idMetodoPago = oc.idMetodoPago', 'INNER')
			->join('sustento.comprobante c', 'c.idOrdenCompra = oc.idOrdenCompra AND c.estado = 1', 'INNER')
			->join('compras.proveedor p', 'p.idProveedor = oc.idProveedor', 'INNER')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = oc.idCentroCosto', 'LEFT')
			->join('compras.comprobante tc', 'tc.idComprobante = c.idTipoComprobante', 'LEFT');

		$this->db->where("numeroDocumento IS NOT NULL AND numeroDocumento != ''");
		if (!empty($params['idOrdenCompra'])) $this->db->where('oc.idOrdenCompra', $params['idOrdenCompra']);

		$this->db->order_by('tipoComprobante', 'DESC');

		return $this->db->get();
	}

	public function obtenerOCLibreEmail($params = [])
	{
		$this->db
			->distinct()
			->select("CASE WHEN mp.idMetodoPago = 1 THEN 'AL CONTADO' ELSE 'CRÉDITO' END AS metodoPago, mp.cantDias, 
			CONVERT(VARCHAR(10), DATEADD(day, mp.cantDias, c.fechaAprobadoFinanza), 103) AS fechaAprobadoFinanza,
    		DATENAME(month, DATEADD(day, mp.cantDias, c.fechaAprobadoFinanza)) AS mesAprobacionFinanza,
			p.nroDocumento AS ruc, p.razonSocial, oc.descripcionCompras,
			cc.canal + ' - ' + cc.subcanal centroCosto, oc.seriado numeroOC, oc.pocliente,
			tc.nombre AS tipoComprobante, c.numeroDocumento AS serieFactura", false)
			->from('orden.ordenCompra oc')
			->join('compras.metodoPago mp', 'mp.idMetodoPago = oc.idMetodoPago', 'INNER')
			->join('sustento.comprobante c', 'c.idOrdenCompra = oc.idOrdenCompra AND c.estado = 1', 'INNER')
			->join('compras.proveedor p', 'p.idProveedor = oc.idProveedor', 'INNER')
			->join('rrhh.dbo.empresa_Canal cc', 'cc.idEmpresaCanal = oc.idCentroCosto', 'LEFT')
			->join('compras.comprobante tc', 'tc.idComprobante = c.idTipoComprobante', 'LEFT');

		$this->db->where("numeroDocumento IS NOT NULL AND numeroDocumento != ''");
		if (!empty($params['idOrdenCompra'])) $this->db->where('oc.idOrdenCompra', $params['idOrdenCompra']);

		$this->db->order_by('tipoComprobante', 'DESC');

		return $this->db->get();
	}
}
