<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-28 00:17:35 --> Severity: Notice --> Undefined index: idEstadoIteam C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 00:17:35 --> Severity: Notice --> Undefined index: idEstadoIteam C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 00:17:35 --> Severity: Notice --> Undefined index: idEstadoIteam C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 00:17:48 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 00:17:48 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 00:17:48 --> Severity: Notice --> Undefined index: idEstadoItem C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 50
ERROR - 2022-02-28 09:41:28 --> Unable to connect to the database
ERROR - 2022-02-28 09:41:46 --> Unable to connect to the database
ERROR - 2022-02-28 10:13:04 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'nombre' no es válido. - Invalid query: 
			SELECT
				p.idPresupuesto
				, p.nombre AS presupuesto
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, tp.nombre AS tipoPresupuesto
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.nombre AS proveedor
			FROM compras.presupuesto p
			JOIN compras.presupuestoDetalle pd ON p.idPresupuesto = pd.idPresupuesto
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.tipoPresupuesto tp ON p.idTipoPresupuesto = tp.idTipoPresupuesto
			JOIN compras.estadoItem ei ON pd.idEstadoItem = ei.idEstadoItem
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idPresupuesto = 8
		
ERROR - 2022-02-28 10:13:08 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'nombre' no es válido. - Invalid query: 
			SELECT
				p.idPresupuesto
				, p.nombre AS presupuesto
				, c.nombre AS cuenta
				, cc.nombre AS cuentaCentroCosto
				, tp.nombre AS tipoPresupuesto
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
			
				, pd.nombre AS item
				, pd.cantidad
				, pd.costo
				, ei.idEstadoItem
				, ei.nombre AS estadoItem
				, pr.nombre AS proveedor
			FROM compras.presupuesto p
			JOIN compras.presupuestoDetalle pd ON p.idPresupuesto = pd.idPresupuesto
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			JOIN compras.tipoPresupuesto tp ON p.idTipoPresupuesto = tp.idTipoPresupuesto
			JOIN compras.estadoItem ei ON pd.idEstadoItem = ei.idEstadoItem
			LEFT JOIN compras.proveedor pr ON pd.idProveedor = pr.idProveedor
			WHERE 1 = 1
			 AND p.idPresupuesto = 8
		
ERROR - 2022-02-28 11:31:09 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Instrucción INSERT en conflicto con la restricción FOREIGN KEY "FK_presupuestoDetalle_proveedor". El conflicto ha aparecido en la base de datos "ImpactBussiness", tabla "compras.proveedor", column 'idProveedor'. - Invalid query: INSERT INTO "compras"."presupuestoDetalle" ("cantidad", "costo", "idArticulo", "idEstadoItem", "idPresupuesto", "idProveedor", "idServicio", "nombre") VALUES ('2','2.1','4','1','9','1',NULL,'GUANTES DE HILO (PARES)'), ('5','1.5','8','1','9','6',NULL,'GUANTES QUIRURGICOS HEALTH '), ('5','0',NULL,'2','9','',NULL,'GUANTES DE GOMA')
ERROR - 2022-02-28 11:33:19 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 6
ERROR - 2022-02-28 11:33:19 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 10
ERROR - 2022-02-28 11:33:19 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 18
ERROR - 2022-02-28 11:33:19 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 22
ERROR - 2022-02-28 11:33:19 --> Severity: Notice --> Undefined variable: detalle C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 43
ERROR - 2022-02-28 11:33:19 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\formularioVisualizacion.php 43
ERROR - 2022-02-28 11:54:39 --> Severity: error --> Exception: Call to undefined method M_Articulo::obtenerProveedor() C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 350
ERROR - 2022-02-28 11:55:21 --> Severity: error --> Exception: Call to undefined method M_Articulo::obtenerProveedor() C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 350
ERROR - 2022-02-28 12:34:24 --> Severity: Notice --> Undefined index: idProveedor C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 416
ERROR - 2022-02-28 15:25:56 --> Severity: Notice --> Undefined index: proveedorCotizacion C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 545
ERROR - 2022-02-28 15:25:56 --> Severity: error --> Exception: Cannot use object of type CI_DB_sqlsrv_result as array C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 562
ERROR - 2022-02-28 15:26:41 --> Severity: error --> Exception: Cannot use object of type CI_DB_sqlsrv_result as array C:\wamp64\www\impactBussiness\application\controllers\Presupuesto.php 562
ERROR - 2022-02-28 15:29:34 --> Severity: Notice --> Undefined property: Presupuesto::$insertarArticulo C:\wamp64\www\impactBussiness\system\core\Model.php 73
ERROR - 2022-02-28 15:29:35 --> Severity: Notice --> Undefined property: Presupuesto::$insertarArticulo C:\wamp64\www\impactBussiness\system\core\Model.php 73
