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

		$sql = "
            select psp.idProveedorServicioPago, ps.idProveedorServicio, ps.ruc, ps.razonSocial, ps.direccion,
                ps.nombreContacto, ps.numeroContacto, ps.correoContacto,
                pe.nombre AS estado, pe.idProveedorEstado idEstado, pe.icono AS estadoIcono, pe.nombre, pe.toggle AS estadoToggle,
                u.departamento, u.provincia, u.distrito, psp.monto, psp.diaPago, psp.frecuenciaPago,
                CONVERT(VARCHAR, psp.fechaInicio, 103) AS fechaInicio,
                CONVERT(VARCHAR, psp.fechaTermino, 103) AS fechaTermino, psp.descripcionServicio, md.simbolo
            from finanzas.proveedorServicio ps
            INNER JOIN compras.proveedorEstado pe ON pe.idProveedorEstado = ps.idProveedorEstado
            INNER JOIN General.dbo.ubigeo u ON u.cod_ubigeo = ps.cod_ubigeo
            INNER JOIN finanzas.proveedorServicioPago psp ON psp.idProveedorServicio = ps.idProveedorServicio
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

	public function validarExistenciaProveedorServicio($params = [])
	{




		if ($params['tipoDocumento'] === 'DNI') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.dni LIKE '%{$params['ruc']}%'
			)
			
		";
		} elseif ($params['tipoDocumento'] === 'RUC') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.ruc LIKE '%{$params['ruc']}%'
			)
			
		";
		} elseif ($params['tipoDocumento'] === 'CE') {

			$sql = "
			SELECT
				idProveedorServicio
			FROM finanzas.proveedorServicio p
			WHERE
			(
				LTRIM(RTRIM(p.razonSocial)) = LTRIM(RTRIM('{$params['razonSocial']}'))
				OR p.carnet_extranjeria LIKE '%{$params['ruc']}%'
			)
			
		";
		}

		$query = $this->db->query($sql);

		if ($query) {
			$this->resultado['query'] = $query;
			$this->resultado['estado'] = true;
			// $this->CI->aSessTrack[] = [ 'idAccion' => 5, 'tabla' => 'General.dbo.ubigeo', 'id' => null ];
		}

		return $this->resultado;
	}
}
