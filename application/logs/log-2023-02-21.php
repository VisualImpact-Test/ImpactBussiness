<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-02-21 11:26:42 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuario' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = '00000000', @clave VARCHAR(250) = 'Demo1234';
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.archFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, u.demo
			FROM
				sistema.usuario u
				JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
					AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1

				LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.usuario = @usuario
				AND u.claveEncriptada = HASHBYTES('SHA1', @clave)
				AND u.estado = 1
				AND u.flag_activo = 1
				
			;
		
ERROR - 2023-02-21 11:26:59 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuario' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = '00000000', @clave VARCHAR(250) = 'demo1234';
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.archFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, u.demo
			FROM
				sistema.usuario u
				JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
					AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1

				LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.usuario = @usuario
				AND u.claveEncriptada = HASHBYTES('SHA1', @clave)
				AND u.estado = 1
				AND u.flag_activo = 1
				
			;
		
ERROR - 2023-02-21 11:27:26 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuario' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = '00000000', @clave VARCHAR(250) = 'Demo1234';
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.archFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, u.demo
			FROM
				sistema.usuario u
				JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
					AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1

				LEFT JOIN sistema.usuarioTipo ut ON ut.idTipoUsuario = uh.idTipoUsuario AND ut.estado = 1
				LEFT JOIN sistema.usuarioTipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
				LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent AND e.flag = 'ACTIVO'
			WHERE
				u.usuario = @usuario
				AND u.claveEncriptada = HASHBYTES('SHA1', @clave)
				AND u.estado = 1
				AND u.flag_activo = 1
				
			;
		
ERROR - 2023-02-21 11:54:17 --> Severity: error --> Exception: Unable to locate the model you have specified: M_catman C:\Apache24\htdocs\php71.loc\ImpactBussiness\system\core\Loader.php 348
ERROR - 2023-02-21 11:56:24 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 11:56:40 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:03:30 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:03:36 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getDatos() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 60
ERROR - 2023-02-21 12:03:59 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:04:04 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getDatos() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 60
ERROR - 2023-02-21 12:04:29 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:04:32 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getDatos() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 60
ERROR - 2023-02-21 12:05:36 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:05:40 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getDatos() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 60
ERROR - 2023-02-21 12:08:56 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:09:00 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de columna 'Id' no es válido. - Invalid query: SELECT *
FROM "ImpactBussiness"."compras"."comprobante"
ORDER BY "Id" DESC
ERROR - 2023-02-21 12:10:27 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:11:06 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:11:52 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:17:04 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:22:50 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getWhere() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 12:26:04 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getWhere() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 12:27:21 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:27:32 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getWhere() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 12:28:23 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de columna 'id' no es válido. - Invalid query: SELECT *
FROM "ImpactBussiness"."compras"."comprobante"
WHERE "id" = 1
ERROR - 2023-02-21 12:29:00 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]El nombre de columna 'id' no es válido. - Invalid query: UPDATE "ImpactBussiness"."compras"."comprobante" SET "nombre" = 'Primer Elemento Prueba 2', "estado" = '1'
WHERE "id" = 1
ERROR - 2023-02-21 12:39:27 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:42:30 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:43:08 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:44:31 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:44:54 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:45:56 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:46:09 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 12:46:16 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 13:24:11 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:06:31 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:09:51 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:11:14 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:12:18 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:13:01 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:13:17 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:14:24 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:14:25 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:15:22 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:17:06 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:24:59 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:25:43 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:26:16 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:26:52 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:27:27 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:27:58 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:28:31 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:29:24 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:30:08 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:30:20 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:32:02 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:35:25 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:37:56 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:39:38 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:39:59 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:40:18 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:40:31 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:41:18 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:41:53 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:42:11 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:42:43 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:43:53 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:46:03 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:46:49 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:47:00 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:51:04 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:51:37 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:53:36 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:53:48 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:54:06 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:54:43 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:54:50 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:54:59 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:55:17 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:55:48 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:57:12 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:58:14 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 14:58:49 --> Severity: Notice --> Undefined variable: message C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\core\07_content_title.php 9
ERROR - 2023-02-21 15:06:47 --> Severity: Notice --> Undefined property: stdClass::$estado C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 135
ERROR - 2023-02-21 15:06:57 --> Severity: Notice --> Undefined property: stdClass::$estado C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Configuracion\Comprobante.php 135
ERROR - 2023-02-21 15:12:10 --> Severity: error --> Exception: Call to undefined method M_Comprobante::getWhere() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 15:13:16 --> Severity: error --> Exception: Call to undefined method M_Comprobante::get_where() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 15:13:20 --> Severity: error --> Exception: Call to undefined method M_Comprobante::get_where() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 15:13:40 --> Severity: error --> Exception: Call to undefined method M_Comprobante::get_where() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
ERROR - 2023-02-21 15:13:44 --> Severity: error --> Exception: Call to undefined method M_Comprobante::get_where() C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\Configuracion\M_Comprobante.php 16
