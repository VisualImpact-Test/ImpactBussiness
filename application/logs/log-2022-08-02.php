<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-08-02 20:40:14 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Cotizacion.php 1194
ERROR - 2022-08-02 20:40:30 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de correlación 'od' está especificado varias veces en una cláusula FROM. - Invalid query: 
			SELECT
			o.idOrdenCompra,
			SUM(cp.subTotal) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
			p.razonSocial,
			p.nroDocumento rucProveedor,
			p.nombreContacto,
			p.direccion,
			p.correoContacto,
			p.numeroContacto,
			CONVERT(VARCHAR, p.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, c.fechaRequerida, 103) AS fechaRequerida,
			c.idCotizacion,
			oper.requerimiento,
			cp.*
			FROM
			compras.ordenCompra o
			JOIN compras.ordenCompraDetalle od ON od.idOrdenCompra = o.idOrdenCompra	
				AND od.estado = 1
			JOIN compras.cotizacionDetalle cp ON od.idCotizacionDetalle = cp.idCotizacionDetalle
			JOIN compras.cotizacion c ON c.idCotizacion = cp.idCotizacion
			JOIN compras.operDetalle od ON od.idCotizacion = c.idCotizacion
			JOIN compras.oper oper ON oper.idOper = od.idOper
			JOIN compras.proveedor p ON p.idProveedor = o.idProveedor 
				
			WHERE o.estado = 1 AND o.idOrdenCompra = 4
		
ERROR - 2022-08-02 20:41:30 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idOper' no es válido. - Invalid query: 
			SELECT
			o.idOrdenCompra,
			SUM(cp.subTotal) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
			p.razonSocial,
			p.nroDocumento rucProveedor,
			p.nombreContacto,
			p.direccion,
			p.correoContacto,
			p.numeroContacto,
			CONVERT(VARCHAR, p.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, c.fechaRequerida, 103) AS fechaRequerida,
			c.idCotizacion,
			oper.requerimiento,
			cp.*
			FROM
			compras.ordenCompra o
			JOIN compras.ordenCompraDetalle od ON od.idOrdenCompra = o.idOrdenCompra	
				AND od.estado = 1
			JOIN compras.cotizacionDetalle cp ON od.idCotizacionDetalle = cp.idCotizacionDetalle
			JOIN compras.cotizacion c ON c.idCotizacion = cp.idCotizacion
			JOIN compras.operDetalle operd ON operd.idCotizacion = c.idCotizacion
			JOIN compras.oper oper ON oper.idOper = od.idOper
			JOIN compras.proveedor p ON p.idProveedor = o.idProveedor 
				
			WHERE o.estado = 1 AND o.idOrdenCompra = 4
		
ERROR - 2022-08-02 20:41:49 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'fechaEntrega' no es válido. - Invalid query: 
			SELECT
			o.idOrdenCompra,
			SUM(cp.subTotal) OVER (PARTITION BY o.idOrdenCompra) subTotalOrdenCompra,
			p.razonSocial,
			p.nroDocumento rucProveedor,
			p.nombreContacto,
			p.direccion,
			p.correoContacto,
			p.numeroContacto,
			CONVERT(VARCHAR, p.fechaEntrega, 103) AS fechaEntrega,
			CONVERT(VARCHAR, c.fechaRequerida, 103) AS fechaRequerida,
			c.idCotizacion,
			oper.requerimiento,
			cp.*
			FROM
			compras.ordenCompra o
			JOIN compras.ordenCompraDetalle od ON od.idOrdenCompra = o.idOrdenCompra	
				AND od.estado = 1
			JOIN compras.cotizacionDetalle cp ON od.idCotizacionDetalle = cp.idCotizacionDetalle
			JOIN compras.cotizacion c ON c.idCotizacion = cp.idCotizacion
			JOIN compras.operDetalle operd ON operd.idCotizacion = c.idCotizacion
			JOIN compras.oper oper ON oper.idOper = operd.idOper
			JOIN compras.proveedor p ON p.idProveedor = o.idProveedor 
				
			WHERE o.estado = 1 AND o.idOrdenCompra = 4
		
