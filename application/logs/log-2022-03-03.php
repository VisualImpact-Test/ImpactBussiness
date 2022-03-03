<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-03 09:04:39 --> Unable to connect to the database
ERROR - 2022-03-03 09:18:58 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idTipoItem' no es válido. - Invalid query: 
			SELECT
				a.idItem
				, ta.idTipoItem
				, ta.nombre AS tipoItem
				, ma.idItemMarca
				, ma.nombre AS itemMarca
				, ca.idItemCategoria
				, ca.nombre AS itemCategoria
				, a.nombre AS item
				, a_l.idItem AS idItemLogistica
				, a_l.nombre AS equivalenteLogistica
				, a.estado
			FROM compras.item a
			JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN visualImpact.logistica.articulo a_l ON a.idItemLogistica = a_l.idArticulo
			WHERE 1 = 1
			
		
ERROR - 2022-03-03 09:19:23 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idItem' no es válido. - Invalid query: 
			SELECT
				a.idItem
				, ta.idItemTipo
				, ta.nombre AS tipoItem
				, ma.idItemMarca
				, ma.nombre AS itemMarca
				, ca.idItemCategoria
				, ca.nombre AS itemCategoria
				, a.nombre AS item
				, a_l.idItem AS idItemLogistica
				, a_l.nombre AS equivalenteLogistica
				, a.estado
			FROM compras.item a
			JOIN compras.itemTipo ta ON a.idItemTipo = ta.idItemTipo
			LEFT JOIN compras.itemMarca ma ON a.idItemMarca = ma.idItemMarca
			LEFT JOIN compras.itemCategoria ca ON a.idItemCategoria = ca.idItemCategoria
			LEFT JOIN visualImpact.logistica.articulo a_l ON a.idItemLogistica = a_l.idArticulo
			WHERE 1 = 1
			
		
ERROR - 2022-03-03 09:22:56 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idTipoItem' no es válido. - Invalid query: INSERT INTO "compras"."item" ("nombre", "idTipoItem", "idMarcaItem", "idCategoriaItem", "idItemLogistica") VALUES ('GUANTES DE HILO (PARES)', '1', '2', '2', '')
ERROR - 2022-03-03 09:29:32 --> Severity: error --> Exception: Unable to locate the model you have specified: M_Articulo C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-03-03 09:31:52 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipoCotizacion' no es válido. - Invalid query: 
			SELECT
				idTipoCotizacion AS id
				, nombre AS value
			FROM compras.tipoCotizacion
			WHERE estado = 1
		
ERROR - 2022-03-03 09:59:34 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipoCotizacion' no es válido. - Invalid query: 
			SELECT
				idTipoCotizacion AS id
				, nombre AS value
			FROM compras.tipoCotizacion
			WHERE estado = 1
		
ERROR - 2022-03-03 10:03:11 --> Severity: error --> Exception: Call to undefined method M_Cotizacion::obtenerTipoCotizacion() C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 36
ERROR - 2022-03-03 10:03:46 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'nroCotizacion' no es válido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fecha
				, tp.idTipoCotizacion
				, 'COTIZACION' AS tipoCotizacion
				, p.nroCotizacion
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-03 10:04:08 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "tp.idTipoCotizacion" no se pudo enlazar. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fecha
				, tp.idTipoCotizacion
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-03 10:04:23 --> Severity: Notice --> Undefined index: nroCotizacion C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\reporte.php 36
ERROR - 2022-03-03 10:04:46 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tarifarioItem' no es válido. - Invalid query: 
			SELECT
				a.idItem AS value
				, a.nombre AS label
				, ta.costo
				, pr.idProveedor
				, pr.razonSocial AS proveedor
				, 1 AS tipo
			FROM compras.item a
			LEFT JOIN compras.tarifarioItem ta ON a.idItem = ta.idItem
			LEFT JOIN compras.proveedor pr ON ta.idProveedor = pr.idProveedor
			WHERE (ta.flag_actual = 1 OR ta.flag_actual IS NULL)
			UNION
			SELECT
				s.idServicio AS value
				, s.nombre AS label
				, ts.costo
				, pr.idProveedor
				, pr.razonSocial AS proveedor
				, 2 AS tipo
			FROM compras.servicio s
			LEFT JOIN compras.tarifarioServicio ts ON s.idServicio = s.idServicio
			LEFT JOIN compras.proveedor pr ON ts.idProveedor = pr.idProveedor
			WHERE (ts.flag_actual = 1 OR ts.flag_actual IS NULL)
		
ERROR - 2022-03-03 10:36:05 --> Severity: Notice --> Undefined index: costoForm C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 170
ERROR - 2022-03-03 10:36:05 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.estadoItem' no es válido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, 'COTIZACION' AS tipoCotizacion
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.estadoItem ei ON pd.idEstadoItem = ei.idEstadoItem
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 17
		
ERROR - 2022-03-03 10:36:59 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.estadoItem' no es válido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, 'COTIZACION' AS tipoCotizacion
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.estadoItem ei ON pd.idEstadoItem = ei.idEstadoItem
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 18
		
ERROR - 2022-03-03 10:37:54 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'fecha' no es válido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, 'COTIZACION' AS tipoCotizacion
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 19
		
ERROR - 2022-03-03 10:38:48 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idEstadoItem' no es válido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, 'COTIZACION' AS tipoCotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 20
		
ERROR - 2022-03-03 10:39:11 --> Severity: Notice --> Undefined index: presupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 45
ERROR - 2022-03-03 10:39:11 --> Severity: Notice --> Undefined index: tipoPresupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 55
ERROR - 2022-03-03 10:57:22 --> Severity: Notice --> Undefined index: presupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 45
ERROR - 2022-03-03 10:57:22 --> Severity: Notice --> Undefined index: tipoPresupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 55
ERROR - 2022-03-03 10:58:59 --> Severity: Notice --> Undefined index: presupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 45
ERROR - 2022-03-03 10:58:59 --> Severity: Notice --> Undefined index: tipoPresupuesto C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\correo\informacionProveedor.php 55
ERROR - 2022-03-03 11:20:38 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:20:38 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:20:38 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:20:38 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:20:38 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:33:22 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:33:22 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:33:22 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:33:22 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:33:22 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:41:36 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:41:36 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:41:36 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:41:36 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:41:36 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 125
ERROR - 2022-03-03 11:42:01 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 53
ERROR - 2022-03-03 11:42:01 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 53
ERROR - 2022-03-03 11:42:01 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 53
ERROR - 2022-03-03 11:42:01 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 53
ERROR - 2022-03-03 11:42:01 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 53
ERROR - 2022-03-03 11:48:32 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "cd.idCotizacionDetalleEstado" no se pudo enlazar. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, p.codCotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fecha
			
				, it.idItemTipo
				, it.nombre AS itemTipo
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idItemEstado
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
				, cde.nombre AS cotizacionDetalleEstado
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			JOIN compras.itemTipo it ON pd.idItemTipo = it.idItemTipo
			JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 23
		
ERROR - 2022-03-03 11:49:04 --> Severity: Warning --> unlink(c:/wamp64/tmp\ci_impactTrade_session754pdq6r5e3qrct8t9qpmpn00cc0mob7): Resource temporarily unavailable C:\wamp64\www\impactBussiness\system\libraries\Session\drivers\Session_files_driver.php 388
ERROR - 2022-03-03 11:49:04 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:04 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:04 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:04 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:04 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:11 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:11 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:11 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:11 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 11:49:11 --> Severity: Notice --> Undefined index: cotizacionDetalleEstado C:\wamp64\www\impactBussiness\application\views\modulos\Cotizacion\formularioVisualizacion.php 54
ERROR - 2022-03-03 12:02:17 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]'FORMAT' no es un nombre de función integrada reconocido. - Invalid query: 
			SELECT
				p.idCotizacion
				, p.nombre AS cotizacion
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, p.codCotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, ce.nombre AS cotizacionEstado
			
				, it.idItemTipo
				, it.nombre AS itemTipo
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idItemEstado
				, ei.nombre AS estadoItem
				, pr.razonSocial AS proveedor
				, cde.nombre AS cotizacionDetalleEstado
				, FORMAT( pd.fechaCreacion, 'dd/MM/yyyy hh:mm (AM/PM)') AS fechaCreacion
				, pd.fechaModificacion
			FROM compras.cotizacion p
			JOIN compras.cotizacionDetalle pd ON p.idCotizacion = pd.idCotizacion
			JOIN compras.itemTipo it ON pd.idItemTipo = it.idItemTipo
			JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			JOIN compras.cotizacionDetalleEstado cde ON pd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.itemEstado ei ON pd.idItemEstado = ei.idItemEstado
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idCotizacion = 23
		
ERROR - 2022-03-03 12:26:42 --> Severity: error --> Exception: Unable to locate the model you have specified: M_Personal C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-03-03 12:30:06 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idPersonalEstado' no es válido. - Invalid query: 
			SELECT
				p.idPersonal
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoPersonal
				, p.codPersonal
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idPersonalEstado = ce.idPersonalEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 12:50:30 --> Severity: Notice --> Undefined index: tipoCotizacion C:\wamp64\www\impactBussiness\application\controllers\Cotizacion.php 272
ERROR - 2022-03-03 14:47:39 --> Severity: error --> Exception: Unable to locate the model you have specified: M_EquiposMoviles C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-03-03 14:47:48 --> Severity: Notice --> Undefined property: EquiposMoviles::$model C:\wamp64\www\impactBussiness\application\controllers\Confirmacion\EquiposMoviles.php 35
ERROR - 2022-03-03 14:47:48 --> Severity: error --> Exception: Call to a member function obtenerCuenta() on null C:\wamp64\www\impactBussiness\application\controllers\Confirmacion\EquiposMoviles.php 35
ERROR - 2022-03-03 15:53:40 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categoriaArticulo' no es válido. - Invalid query: 
			SELECT
				a.idCategoriaArticulo
				, a.nombre AS categoria
				, a.estado
			FROM compras.categoriaArticulo a
			WHERE 1 = 1
			
		
