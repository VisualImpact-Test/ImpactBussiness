<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Home extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function query_cotizacion($input)
	{
		$sql = "
			SELECT 
				idCotizacion
				, codCotizacion
				, fechaEmision
				, fechaTermino
				, c.nombre titulo
				, cc.nombre centro_costo
				, c.total
				, DATEDIFF(dd, fechaEmision, ISNULL(fechaTermino, GETDATE())) dias
				, ce.idCotizacionEstado
				, c.comentario
				, (SELECT COUNT(1) FROM compras.cotizacionDetalle cd where cd.idCotizacion = c.idCotizacion) total_item
				, replace(ce.icono,'large','x-small') icono
			FROM 
				compras.cotizacion c
				JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
				JOIN visualImpact.logistica.cuentaCentroCosto cc ON cc.idCuenta = c.idCuenta AND cc.idCuentaCentroCosto = c.idCentroCosto
			WHERE
				idCotizacion = " . $input['idCotizacion'];
		return $this->db->query($sql);
	}

	public function query_cotizacion_detalle($input)
	{
		$sql = "
			SELECT 
				cd.idItem
				, cd.nombre
				, cd.cantidad
				, cd.costo
				, cd.subtotal
				, (SELECT p.razonSocial FROM compras.proveedor p WHERE p.idProveedor = cd.idProveedor) proveedor
				, it.idItemTipo
				, DATEADD(DAY,FLOOR(RAND()*(10-1)+1),cd.fechaCreacion) fecha
				, it.nombre tipo
			FROM 
				compras.cotizacionDetalle cd
				
				JOIN compras.itemTipo it ON it.idItemTipo = cd.idItemTipo
				
			WHERE
				idCotizacion = " . $input['idCotizacion'];
		return $this->db->query($sql);
	}

	public function query_estados_cotizacion()
	{
		$sql = "
			SELECT 
				idCotizacionEstado, nombre
			FROM
				compras.cotizacionEstado ce
			ORDER BY ce.orden
		";
		return $this->db->query($sql);
	}

	public function query_estados_cotizacion_proceso()
	{
		$sql = "
			SELECT 
				idCotizacionEstado, nombre
			FROM
				compras.cotizacionEstado ce
			-- WHERE 
				-- ce.idCotizacionEstado <> 7
				
			ORDER BY ce.orden
		";
		return $this->db->query($sql);
	}

	public function query_cotizaciones_proceso()
	{
		$sql = "
			SELECT 
				ce.idCotizacionEstado, COUNT(c.idCotizacion) cantidad
			FROM 
				compras.cotizacion c
				JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
			-- WHERE
				-- ce.idCotizacionEstado <> 7
			GROUP BY ce.idCotizacionEstado
		";
		return $this->db->query($sql);
	}

	public function query_cotizaciones()
	{
		$sql = "
			DECLARE @fecIni DATE =  DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0);
			DECLARE @fecFin DATE = GETDATE();
			SELECT 
				idCotizacion
				, codCotizacion
				, fechaEmision
				, fechaTermino
				, CASE WHEN (fechaEmision < @fecIni) THEN 1 ELSE 0 END pasado
				, c.nombre titulo
				, cc.nombre centro_costo
				, c.total
				, DATEDIFF(dd, fechaEmision, ISNULL(fechaTermino, GETDATE())) dias
				, ce.idCotizacionEstado idEstado
				, replace(ce.icono,'large','x-small') icono
				, u.nombres + ' ' + u.apePaterno + ' ' + u.apeMaterno as usuario
			FROM compras.cotizacion c
			LEFT JOIN sistema.usuario u ON u.idUsuario=c.idUsuarioReg
			JOIN compras.cotizacionEstado ce ON c.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON cc.idCuenta = c.idCuenta AND cc.idCuentaCentroCosto = c.idCentroCosto
			WHERE
				-- ce.idCotizacionEstado <> 7 AND 
				(fechaEmision BETWEEN @fecIni AND @fecFin
				OR fechaTermino IS NULL
				OR fechaTermino BETWEEN @fecIni AND @fecFin)
		";
		return $this->db->query($sql);
	}
}
