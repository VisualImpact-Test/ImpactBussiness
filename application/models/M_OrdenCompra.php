<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_OrdenCompra extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function obtenerOrdenCompraLista($params = [])
	{
		$this->db
			->select('oc.*,
							ocd.idOrdenCompraDetalle,
							ocd.idItem,
							ocd.idTipo,
							ocd.costoUnitario AS costo_item,
							ocd.cantidad AS cantidad_item,
							ocd.gap AS gap_item,
							ocd.costoSubTotalGap AS csg_item,
							ocd.costoSubTotal AS cs_item,
							i.nombre as item,
							cu.nombre AS cuenta,
							cc.subcanal AS centroCosto,
							pro.razonSocial,
							pro.nroDocumento as rucProveedor,
							pro.nombreContacto,
							pro.direccion,
							pro.numeroContacto,
							pro.correoContacto,
							mon.simbolo as simboloMoneda,
							mon.nombreMoneda as monedaPlural,
							mp.nombre as metodoPago,
							uf.nombre_archivo as dirFirma,
							md.valor AS monedaCambio
							')
			->from('orden.ordenCompraDetalle ocd')
			->join('orden.ordenCompra oc', 'oc.idOrdenCompra = ocd.idOrdenCompra and ocd.estado=1', 'LEFT')
			->join('compras.item i', 'i.idItem = ocd.idItem', 'LEFT')
			->join('rrhh.dbo.empresa cu', 'cu.idEmpresa = oc.idCuenta', 'LEFT')
			->join('rrhh.dbo.empresa_canal cc', 'cc.idEmpresaCanal=oc.idCentroCosto', 'LEFT')
			->join('compras.proveedor pro', 'pro.idProveedor = oc.idProveedor', 'LEFT')
			->join('compras.moneda mon', 'mon.idMoneda = oc.idMoneda', 'LEFT')
			->join('compras.monedaDet md', 'md.idMoneda = mon.idMoneda AND General.dbo.fn_fechaVigente(md.fecIni,md.fecFin,oc.fechaReg,oc.fechaReg)=1')
			->join('compras.metodoPago mp', 'mp.idMetodoPago = oc.idMetodoPago', 'LEFT')
			->join('sistema.usuario u', 'u.idUsuario = oc.idUsuarioReg')
			->join('sistema.usuarioFirma uf', 'u.idUsuarioFirma=uf.idUsuarioFirma', 'left')
			->where('oc.estado', '1');

		if (isset($params['idOrdenCompra'])) {
			$this->db->where('ocd.idOrdenCompra', $params['idOrdenCompra']);
		}
		return $this->db->get();
	}
	public function obtenerInformacionOrdenCompraSubItem($params = [])
	{
		$this->db
			->select('ocds.*, um.nombre as unidadMedida')
			->from('orden.ordenCompraDetalleSub ocds')
			->join('compras.unidadMedida um', 'um.idUnidadMedida = ocds.idUnidadMedida', 'left');

		if (isset($params['idOrdenCompraDetalle'])) {
			$this->db->where('ocds.idOrdenCompraDetalle', $params['idOrdenCompraDetalle']);
		}
		return $this->db->get();
	}
}
