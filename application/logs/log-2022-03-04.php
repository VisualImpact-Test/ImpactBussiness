<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-04 09:00:58 --> Unable to connect to the database
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-04 09:00:58 --> Unable to connect to the database
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-04 09:00:58 --> Unable to connect to the database
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-04 09:00:58 --> Unable to connect to the database
ERROR - 2022-03-04 09:01:17 --> Unable to connect to the database
ERROR - 2022-03-04 09:23:32 --> Severity: error --> Exception: Unable to locate the model you have specified: M_CotizacionEfectiva C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-03-04 09:23:41 --> Severity: Notice --> Undefined property: CotizacionEfectiva::$model C:\wamp64\www\impactBussiness\application\controllers\CotizacionEfectiva.php 35
ERROR - 2022-03-04 09:23:41 --> Severity: error --> Exception: Call to a member function obtenerCuenta() on null C:\wamp64\www\impactBussiness\application\controllers\CotizacionEfectiva.php 35
ERROR - 2022-03-04 09:27:19 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idCotizacionEfectivaEstado' no es válido. - Invalid query: 
			SELECT
				p.idCotizacionEfectiva
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacionEfectiva
				, p.codCotizacionEfectiva
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEfectivaEstado = ce.idCotizacionEfectivaEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-04 09:27:27 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idCotizacionEfectivaEstado' no es válido. - Invalid query: 
			SELECT
				p.idCotizacionEfectiva
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacionEfectiva
				, p.codCotizacionEfectiva
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEfectivaEstado = ce.idCotizacionEfectivaEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-04 09:27:54 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idCotizacionEfectivaEstado' no es válido. - Invalid query: 
			SELECT
				p.idCotizacionEfectiva
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacionEfectiva
				, p.codCotizacionEfectiva
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, p.estado
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEfectivaEstado = ce.idCotizacionEfectivaEstado
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 6
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 10
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 18
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 22
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 30
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: cabecera C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 36
ERROR - 2022-03-04 09:33:39 --> Severity: Notice --> Undefined variable: detalle C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 60
ERROR - 2022-03-04 09:33:39 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\www\impactBussiness\application\views\modulos\CotizacionEfectiva\formularioVisualizacion.php 60
