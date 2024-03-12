<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_OrdenCompra extends MY_Model
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
	public function obtenerOrdenCompraLista($params = [])
	{
		$filtros = "";
		$filtros = "1=1";
		$filtros .= !empty($params['cuenta']) ? ' AND oc.idCuenta = ' . $params['cuenta'] : '';
		$filtros .= !empty($params['cuentaCentroCosto']) ? ' AND oc.idCentroCosto = ' . $params['cuentaCentroCosto'] : '';
		$filtros .= !empty($params['fechaDesde']) ? ' AND oc.fechaEntrega >= Convert(date,\'' . $params['fechaDesde'] . '\') ' : '';
		$filtros .= !empty($params['fechaHasta']) ? ' AND oc.fechaEntrega <= Convert(date,\'' . $params['fechaHasta'] . '\') ' : '';

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
							md.valor AS monedaCambio,
							oc.idAlmacen,
							oc.mostrar_observacion,
							oc.descripcionCompras')
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
			// ->where('oc.estado', '1')
			->where('ocd.estado', '1');

		if (isset($params['idOrdenCompra'])) {
			$this->db->where('ocd.idOrdenCompra', $params['idOrdenCompra']);
		}
		if (!empty($filtros)) {
			$this->db->where($filtros);
		}
		$this->db->order_by('oc.estado desc, oc.idOrdenCompra desc');

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
	public function obtenerInformacionOperSinCotSubItem($params = [])
	{
		$this->db
			->select('ocds.*, um.nombre as unidadMedida, ocds.genero as idGenero')
			->from('orden.operDetalleSub ocds')
			->join('compras.unidadMedida um', 'um.idUnidadMedida = ocds.idUnidadMedida', 'left');

		if (isset($params['idOperDetalle'])) {
			$this->db->where('ocds.idOperDetalle', $params['idOperDetalle']);
		}
		return $this->db->get();
	}
	public function obtenerInformacionOperSinCot($params = [])
	{
		$this->db
			->select("o.*,
							od.idOperDetalle,
							od.idItem,
							od.idTipo,
							od.costoUnitario AS costo_item,
							od.cantidad AS cantidad_item,
							od.gap AS gap_item,
							od.costoSubTotal AS csg_item,
							/* od.costoSubTotalGap AS csg_item, */
							i.nombre AS item,
							'Coordinadora de compras' AS usuarioReceptor,
							ue.nombres + ' ' + ISNULL(ue.apePaterno,'') + ' ' + ISNULL(ue.apeMaterno,'') AS usuarioRegistro,
							cu.nombre AS cuenta,
							cc.subcanal AS centroCosto,
							o.numeroOC as poCliente
							", false)
			->from('orden.oper o')
			->join('orden.operDetalle od', 'od.idOper = o.idOper and od.estado=1')
			->join('compras.item i', 'i.idItem = od.idItem', 'LEFT')
			->join('sistema.usuario ue', 'ue.idUsuario = o.idUsuarioReg', 'LEFT')
			->join('rrhh.dbo.empresa cu', 'cu.idEmpresa=o.idCuenta', 'LEFT')
			->join('rrhh.dbo.empresa_canal cc', 'cc.idEmpresaCanal=o.idCentroCosto', 'LEFT');
		// Where
		if (isset($params['idOper'])) {
			$this->db->where('o.idOper', $params['idOper']);
		}

		$this->db->order_by('o.idOper', 'DESC');
		return $this->db->get();
	}
	public function getItemTarifario()
	{
		$this->db
			->select('*')
			->from('compras.itemTarifario')
			->where('fechaVigencia >= CAST(GETDATE() AS DATE)')
			->where('estado', 1);

		return $this->db->get();
	}
	public function obtenerArchivo($id)
	{
		$sql = "
			SELECT
			idItemImagen
			,idItem
			,idTipoArchivo
			,nombre_archivo
			FROM compras.itemImagen
			WHERE estado = 1 AND idItem =" . $id;

		$query = $this->db->query($sql);
		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			return $this->resultado;
		} else {
			return null;
		}
	}
	public function getValidarItem($item)
	{
		$this->db
			->select('*')
			->from('compras.item')
			->where('nombre', $item);

		return $this->db->get();
	}
	
	public function actualizarAnulacionOC($params = [])
	{
		$query = $this->db->update($params['tabla'], $params['update'], $params['where']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}
}
