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

		$sql = "
				select 
				idProveedorServicioGenerado
				,ps.idTipoDocumento
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
				from finanzas.proveedorServicioPagoGenerado as pspg
				left join finanzas.estadoPago as ep on ep.idEstadoPago = pspg.idEstadoPago
				left join rrhh.dbo.empresa_Canal as ec on ec.idEmpresaCanal = pspg.idCentroCosto
				join finanzas.proveedorServicioPago as psp on pspg.idProveedorServicioPago = psp.idProveedorServicioPago
				join finanzas.proveedorServicio as ps on ps.idProveedorServicio = psp.idProveedorServicio
				left join compras.moneda as mn on mn.idMoneda = psp.idMoneda
				left join rrhh.dbo.empresa as emp on emp.idEmpresa = pspg.idCuenta
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


	
}
