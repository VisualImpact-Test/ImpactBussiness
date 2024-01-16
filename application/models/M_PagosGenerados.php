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
				,razonSocial
				,ruc
				,descripcionServicio
				,pspg.monto
				,fechaProgramada
				,numeroComprobante
				,idCentroCosto
				,ec.canal
				,porcentajeDetraccion
				,ep.idEstadoPago 
				,ep.nombreEstado
				,ps.nombreContacto
				,ps.correoContacto
				,ps.numeroContacto
				from finanzas.proveedorServicioPagoGenerado as pspg
				left join finanzas.estadoPago as ep on ep.idEstadoPago = pspg.idEstadoPago
				left join rrhh.dbo.empresa_Canal as ec on ec.idEmpresaCanal = pspg.idCentroCosto
				join finanzas.proveedorServicioPago as psp on pspg.idProveedorServicioPago = psp.idProveedorServicioPago
				join finanzas.proveedorServicio as ps on ps.idProveedorServicio = psp.idProveedorServicio 
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
