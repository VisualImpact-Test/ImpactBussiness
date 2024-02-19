<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_PagosGenerados extends MY_Model
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


    public function ObtenerDatosPagosGenerados($params = [])
	{
	
		$filtros = "";
		$filtros .= !empty($params['idPagoGenerado']) ? ' AND pspg.idProveedorServicioGenerado = ' . $params['idPagoGenerado'] : '';

		$filtro2 = "";
		$filtro2 .= !empty($params['idPagoGenerado']) ? ',(select SUM(monto) as monto_total from finanzas.proveedorServicioPagoComprobante where idProveedorServicioGenerado = '.$params['idPagoGenerado'].') as monto_total' : '';


		$sql = "
				select 
				idProveedorServicioGenerado
				,ps.idTipoDocumento
				,utd.breve
				,ps.numDocumento
				,ps.datosProveedor
				,descripcionServicio
				,psp.monto
				,fechaProgramada
				,numeroComprobante
				,idCentroCosto
				,ec.canal
				,porcentajeDetraccion
				,ep.idEstadoPago 
				,ep.nombreEstado
				,pspg.porcentajeDetraccion
				,pspg.montoDetraccion
				,pspg.fechaPagoComprobante
				,mn.nombre as moneda
				,pspg.idCuenta
				,emp.razonSocial as cuenta
				,pspg.idComprobante
				
				{$filtro2}
				, (SELECT idProveedorServicioGenerado FROM finanzas.proveedorServicioPagoComprobante as pspc  WHERE pspc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado group by idProveedorServicioGenerado  ) AS flagFacturas
				from finanzas.proveedorServicioPagoGenerado as pspg
				left join finanzas.estadoPago as ep on ep.idEstadoPago = pspg.idEstadoPago
				left join rrhh.dbo.empresa_Canal as ec on ec.idEmpresaCanal = pspg.idCentroCosto
				join finanzas.proveedorServicioPago as psp on pspg.idProveedorServicioPago = psp.idProveedorServicioPago
				join finanzas.proveedorServicio as ps on ps.idProveedorServicio = psp.idProveedorServicio
				left join compras.moneda as mn on mn.idMoneda = psp.idMoneda
				left join rrhh.dbo.empresa as emp on emp.idEmpresa = pspg.idCuenta
				left join sistema.usuarioTipoDocumento as utd on utd.idTipoDocumento = ps.idTipoDocumento
				where 1=1 
		
				{$filtros}
			";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}
	
	public function ObtenerDatosTipoDocumento($params = [])
	{
	
		$sql = "select idTipoDocumento as id , breve as value  from sistema.usuarioTipoDocumento
		where 1=1 and idTipoDocumento in (2,1,3,6)";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function ObtenerDatosMetodoPago($params = [])
	{
	
		$sql = "select idMetodoPago as id , nombre as value from finanzas.proveedorServicioMetodoPago";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}



	public function ObtenerDatosTipoComprobante($params = [])
	{
	
		$sql = "select idComprobante as id , nombre as value  from compras.comprobante";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	
	public function ObtenerDatosPagosGeneradosReporte($params = [])
	{
	
		$filtros = "";
		$filtros .= !empty($params['idPagoGenerado']) ? ' AND pspg.idProveedorServicioGenerado = ' . $params['idPagoGenerado'] : '';

		$filtro2 = "";
		$filtro2 .= !empty($params['idPagoGenerado']) ? ',(select SUM(monto) as monto_total from finanzas.proveedorServicioPagoComprobante where idProveedorServicioGenerado = '.$params['idPagoGenerado'].') as monto_total' : '';


		$sql = "
				select 
				idProveedorServicioGenerado
				,ps.idTipoDocumento
				,utd.breve
				,ps.numDocumento
				,ps.datosProveedor
				,descripcionServicio
				,pspg.monto
				,fechaProgramada
				,numeroComprobante
				,idCentroCosto
				,ec.canal
				,porcentajeDetraccion
				,ep.idEstadoPago 
				,ep.nombreEstado
				,pspg.porcentajeDetraccion
				,pspg.montoDetraccion
				,pspg.fechaPagoComprobante
				,mn.nombre as moneda
				,pspg.idCuenta
				,emp.razonSocial as cuenta
				,pspg.idComprobante
				, (SELECT SUM(pspc.monto) FROM finanzas.proveedorServicioPagoComprobante AS pspc WHERE pspc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado and idMoneda = 1) AS montofacturasSoles
				, (SELECT SUM(pspe.montoPagado) FROM finanzas.proveedorServicioPagoEfectuados AS pspe JOIN finanzas.proveedorServicioPagoComprobante AS psc ON pspe.idServicioPagoComprobante = psc.idServicioPagoComprobante WHERE psc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado  and idMoneda = 1) AS montoefectuadosSoles
				, (SELECT SUM(psnc.montoNota) FROM finanzas.proveedorServicioPagoNotaCredito AS psnc JOIN finanzas.proveedorServicioPagoComprobante AS psc ON psc.idServicioPagoComprobante = psnc.idServicioPagoComprobante WHERE psc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado  and idMoneda = 1) AS montonotacreditoSoles
				, (SELECT SUM(pspc.monto) FROM finanzas.proveedorServicioPagoComprobante AS pspc WHERE pspc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado and idMoneda = 2) AS montofacturasDolar
				, (SELECT SUM(pspe.montoPagado) FROM finanzas.proveedorServicioPagoEfectuados AS pspe JOIN finanzas.proveedorServicioPagoComprobante AS psc ON pspe.idServicioPagoComprobante = psc.idServicioPagoComprobante WHERE psc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado  and idMoneda = 2) AS montoefectuadosDolar
				, (SELECT SUM(psnc.montoNota) FROM finanzas.proveedorServicioPagoNotaCredito AS psnc JOIN finanzas.proveedorServicioPagoComprobante AS psc ON psc.idServicioPagoComprobante = psnc.idServicioPagoComprobante WHERE psc.idProveedorServicioGenerado = pspg.idProveedorServicioGenerado  and idMoneda = 2) AS montonotacreditoDolar
				{$filtro2}
				from finanzas.proveedorServicioPagoGenerado as pspg
				left join finanzas.estadoPago as ep on ep.idEstadoPago = pspg.idEstadoPago
				left join rrhh.dbo.empresa_Canal as ec on ec.idEmpresaCanal = pspg.idCentroCosto
				join finanzas.proveedorServicioPago as psp on pspg.idProveedorServicioPago = psp.idProveedorServicioPago
				join finanzas.proveedorServicio as ps on ps.idProveedorServicio = psp.idProveedorServicio
				left join compras.moneda as mn on mn.idMoneda = psp.idMoneda
				left join rrhh.dbo.empresa as emp on emp.idEmpresa = pspg.idCuenta
				left join sistema.usuarioTipoDocumento as utd on utd.idTipoDocumento = ps.idTipoDocumento
				where 1=1 
		
				{$filtros}
			";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}


	public function ObtenerDatosFacturas($params = [])
	{
	
		$sql = "select 
		psc.idServicioPagoComprobante,
		psc.idProveedorServicioGenerado,
		psc.fechaEmision , 
		psc.fechaRecepcion , 
		psc.fechaVencimiento,
		psc.tipoComprobante as tipoComprobanteFactura,
		psc.numeroComprobante as numComprobanteFactura,
		psc.monto as montoFactura,
		psc.nombre_archivo as nombre_archivo_factura,
		psc.idMoneda,
		psnc.idServicioPagoNota,
		psnc.idTipoNota,
		psnc.montoNota,
		psnc.fechaRecepcion as fechaRecepcionNota,
		psnc.fechaEmision as fechaEmisionNota,
		psnc.numNota,
		psnc.nombre_archivo as nombre_archivo_nota,
		pspe.idServicioPagoEfectuado,
		pspe.fechaPagoComprobante as fechaPagoComprobantePago  , 
		pspe.idTipoComprobante as idTipoPago,
		pspe.numeroComprobante as numComprobantePago , 
		pspe.montoPagado,
		pspe.idCuenta,
		pspe.idCentroCosto,
		pspe.flagDetraccion , 
		pspe.porcentajeDetraccion,
		pspe.montoDetraccion ,
		pspe.idEstadoPago ,
		pspe.nombre_archivo as nombre_archivo_pago
		from finanzas.proveedorServicioPagoComprobante as psc
		left join finanzas.proveedorServicioPagoNotaCredito as psnc on psc.idServicioPagoComprobante = psnc.idServicioPagoComprobante
		left join finanzas.proveedorServicioPagoEfectuados as pspe on pspe.idServicioPagoComprobante = psc.idServicioPagoComprobante
		where 1=1 and idProveedorServicioGenerado =  ". $params['idPagoGenerado'];
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function ObtenerDatosFacturasPagos($params = [])
	{
	
		$sql = "select * from finanzas.proveedorServicioPagoComprobante as psc
		left join finanzas.proveedorServicioPagoNotaCredito as psnc on psc.idServicioPagoComprobante = psnc.idServicioPagoComprobante
		left join finanzas.proveedorServicioPagoEfectuados as pspe on pspe.idServicioPagoComprobante = psc.idServicioPagoComprobante
		where 1=1 and idProveedorServicioGenerado =  ". $params['idPagoGenerado'];
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}


	public function ObtenerDatosTipoNota($params = [])
	{
	
		$sql = "select idTipoCredito as id , nombre as value from finanzas.tipoCredito";
		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function obtenertipoMoneda($params = [])
	{
		$sql = "
		select idMoneda as id , nombreMoneda AS value from compras.moneda where estado = 1 
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}


	public function obtenerReporteFinanzas($params = [])
	{
		$sql = "
		select 
		pspc.idProveedorServicioGenerado,
		ps.datosProveedor , 
		ps.numDocumento ,
		psp.descripcionServicio,
		pspc.idMoneda ,
		pspc.tipoComprobante as tipoComprobanteFactura,
		(select nombre from compras.comprobante as cp where pspc.tipoComprobante = cp.idComprobante  ) as estadofactura ,
		pspc.numeroComprobante ,
		pspc.fechaEmision,
		pspe.idTipoComprobante as tipoComprobantePago ,
		(select nombre from compras.comprobante as cp where pspe.idTipoComprobante = cp.idComprobante  ) as estadopago ,
		pspe.numeroComprobante as numComprobantePago,
		pspe.fechaPagoComprobante,
		pspc.monto,
		pspe.montoPagado,
		pspg.idEstadoPago,
		ep.nombreEstado
		from finanzas.proveedorServicioPagoComprobante as pspc
		left join finanzas.proveedorServicioPagoEfectuados as pspe on pspc.idServicioPagoComprobante = pspe.idServicioPagoComprobante
		left join finanzas.proveedorServicioPagoGenerado as pspg on pspc.idProveedorServicioGenerado =  pspg.idProveedorServicioGenerado
		left join finanzas.proveedorServicioPago as psp on psp.idProveedorServicioPago = pspg.idProveedorServicioPago
		left join finanzas.proveedorServicio as ps on ps.idProveedorServicio = psp.idProveedorServicio
		left join finanzas.estadoPago as ep on ep.idEstadoPago = pspg.idEstadoPago
		order by pspc.idProveedorServicioGenerado asc
				
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	
}
