<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-01-26 11:05:43 --> Severity: error --> Exception: Unable to locate the model you have specified: M_control C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:05:48 --> Severity: error --> Exception: Unable to locate the model you have specified: M_control C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:20:08 --> Severity: Notice --> Undefined property: Login::$m_Login C:\wamp64\www\impactBussiness\application\controllers\Login.php 130
ERROR - 2022-01-26 11:20:08 --> Severity: error --> Exception: Call to a member function get_cuenta() on null C:\wamp64\www\impactBussiness\application\controllers\Login.php 130
ERROR - 2022-01-26 11:22:34 --> Severity: error --> Exception: Unable to locate the model you have specified: M_muro C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:22:53 --> Severity: error --> Exception: Unable to locate the model you have specified: M_muro C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:23:36 --> Severity: error --> Exception: Unable to locate the model you have specified: M_control C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:24:16 --> Severity: error --> Exception: Unable to locate the model you have specified: M_control C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:24:46 --> Severity: error --> Exception: Unable to locate the model you have specified: M_control C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-01-26 11:35:09 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'ORDER'. - Invalid query: 
			DECLARE @fecha date=getdate();
			SELECT TOP 1  
				idUsuario,idProyecto,fechaIni,fechaFin,estado,porcentaje,
				idPeticion,
				CONVERT(varchar,hora,8) hora,
				CONVERT(varchar,fechaActualizacion,103) fechaActualizacion,
				CASE WHEN (porcentaje >= 100) THEN 1 ELSE 0 END actualizado
			FROM 
				ImpactTrade_small.trade.peticionActualizarVisitas
			WHERE 
			idProyecto=
			ORDER BY fechaActualizacion DESC,idPeticion DESC;
ERROR - 2022-01-26 11:36:38 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'ORDER'. - Invalid query: 
			DECLARE @fecha date=getdate();
			SELECT TOP 1  
				idUsuario,idProyecto,fechaIni,fechaFin,estado,porcentaje,
				idPeticion,
				CONVERT(varchar,hora,8) hora,
				CONVERT(varchar,fechaActualizacion,103) fechaActualizacion,
				CASE WHEN (porcentaje >= 100) THEN 1 ELSE 0 END actualizado
			FROM 
				ImpactTrade_small.trade.peticionActualizarVisitas
			WHERE 
			idProyecto=
			ORDER BY fechaActualizacion DESC,idPeticion DESC;
