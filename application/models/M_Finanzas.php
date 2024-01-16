<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Finanzas extends MY_Model
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

	public function obtenerProveedorServicio($params = [])
	{
		$filtros = "";
		$filtros .= !empty($params['idProveedorServicioPago']) ? ' AND psp.idProveedorServicioPago = ' . $params['idProveedorServicioPago'] : '';
		$filtros .= !empty($params['idProveedorServicio']) ? ' AND ps.idProveedorServicio = ' . $params['idProveedorServicio'] : '';
		$filtros .= !empty($params['estado']) ? ' AND psp.estado = ' . $params['estado'] : '';

		$sql = "
            select psp.idProveedorServicioPago, ps.idProveedorServicio, ps.ruc, ps.razonSocial, ps.direccion,
                ps.nombreContacto, ps.numeroContacto, ps.correoContacto,
                pe.nombre AS estado, pe.idProveedorEstado idEstado, pe.icono AS estadoIcono, pe.nombre, pe.toggle AS estadoToggle,
                u.departamento, u.provincia, u.distrito, psp.monto, psp.diaPago, psp.frecuenciaPago, psp.flagFijo,
                CONVERT(VARCHAR, psp.fechaInicio, 103) AS fechaInicioReporte,
				CONVERT(VARCHAR, psp.fechaTermino, 103) AS fechaTerminoReporte, 
				psp.fechaInicio,
				psp.fechaTermino,
				psp.descripcionServicio, md.simbolo, md.idMoneda
            from finanzas.proveedorServicio ps
            INNER JOIN General.dbo.ubigeo u ON u.cod_ubigeo = ps.cod_ubigeo
            INNER JOIN finanzas.proveedorServicioPago psp ON psp.idProveedorServicio = ps.idProveedorServicio
            INNER JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = psp.estado
            INNER JOIN compras.moneda md ON md.idMoneda = psp.idMoneda
			
			WHERE
			1 = 1
			{$filtros}
		";

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
		}

		return $this->resultado;
	}

	public function insertarProveedorServicioPago($params = [])
	{
		$query = $this->db->insert($params['tabla'], $params['insert']);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			$this->resultado['id'] = $this->db->insert_id();
		}

		return $this->resultado;
	}
	
	public function actualizarProveedorServicioPago($params = [])
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
