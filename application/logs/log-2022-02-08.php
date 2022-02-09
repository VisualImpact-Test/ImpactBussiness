<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-08 09:21:50 --> Unable to connect to the database
ERROR - 2022-02-08 11:37:55 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'flag_actualizarListas' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = 'hpineda', @clave VARCHAR(250) = 'Harry123';
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.flag_actualizarListas
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno 
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento 
				, u.demo
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.urlFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, CASE WHEN (pav.idPermisoActualizar IS NOT NULL) THEN 1 ELSE 0 END actualizarVisitas
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
		
ERROR - 2022-02-08 11:38:00 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'flag_actualizarListas' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = 'hpineda', @clave VARCHAR(250) = 'Harry123';
			SELECT DISTINCT
				u.idUsuario
				, e.idEmpleado
				, u.flag_actualizarListas
				, u.apePaterno + ' ' + ISNULL(u.apeMaterno, '') + ' ' + u.nombres apeNom
				, u.apePaterno + ', ' + u.nombres apeNom_corto
				, u.apePaterno
				, u.apeMaterno 
				, u.nombres
				, td.breve tipoDocumento
				, u.numDocumento 
				, u.demo
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.urlFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, CASE WHEN (pav.idPermisoActualizar IS NOT NULL) THEN 1 ELSE 0 END actualizarVisitas
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
		
ERROR - 2022-02-08 11:38:22 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'demo' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = 'hpineda', @clave VARCHAR(250) = 'Harry123';
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
				, u.demo
				, u.externo
				, ut.idTipoUsuario
				, ut.nombre tipoUsuario
				, e.urlFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, CASE WHEN (pav.idPermisoActualizar IS NOT NULL) THEN 1 ELSE 0 END actualizarVisitas
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
		
ERROR - 2022-02-08 11:38:43 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'urlFoto' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = 'hpineda', @clave VARCHAR(250) = 'Harry123';
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
				, e.urlFoto foto
				, uh.idUsuarioHist
				, u.ultimo_cambio_pwd
				, DATEDIFF(day, u.ultimo_cambio_pwd, getdate()) AS dias_pasados
				, flag_anuncio_visto
				, CASE WHEN (pav.idPermisoActualizar IS NOT NULL) THEN 1 ELSE 0 END actualizarVisitas
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
		
ERROR - 2022-02-08 11:39:23 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "pav.idPermisoActualizar" no se pudo enlazar. - Invalid query: 
			DECLARE @fecha DATE = GETDATE(), @usuario VARCHAR(250) = 'hpineda', @clave VARCHAR(250) = 'Harry123';
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
				, CASE WHEN (pav.idPermisoActualizar IS NOT NULL) THEN 1 ELSE 0 END actualizarVisitas
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
		
ERROR - 2022-02-08 11:39:39 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'demo' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT
				u.idUsuario
				, u.idTipoDocumento
				, u.numDocumento
				, u.usuario
				, u.clave
				, u.nombres
				, u.apePaterno
				, u.apeMaterno
				, u.demo
				, u.estado
				, u.externo
				, u.idEmpleado
				, u.fechaCreacion
				, u.fechaModificacion
				, u.flag_gestorDeArchivos
				, u.intentos
				, u.ultimo_cambio_pwd
				, u.flag_activo
				, u.claveEncriptada
				, e.email
				, e.email_corp
			FROM sistema.usuario u
			JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
			LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent
			WHERE u.estado = 1 AND u.usuario = 'hpineda'
		
ERROR - 2022-02-08 11:39:57 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'apeMaternodemo' no es válido. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT
				u.idUsuario
				, u.idTipoDocumento
				, u.numDocumento
				, u.usuario
				, u.clave
				, u.nombres
				, u.apePaterno
				, u.apeMaternodemo
				, u.estado
				, u.externo
				, u.idEmpleado
				, u.fechaCreacion
				, u.fechaModificacion
				, u.intentos
				, u.ultimo_cambio_pwd
				, u.flag_activo
				, u.claveEncriptada
				, e.email
				, e.email_corp
			FROM sistema.usuario u
			JOIN sistema.usuarioHistorico uh ON uh.idUsuario = u.idUsuario
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha) AND uh.estado = 1
			LEFT JOIN rrhh.dbo.Empleado e ON u.numDocumento = e.numTipoDocuIdent
			WHERE u.estado = 1 AND u.usuario = 'hpineda'
		
ERROR - 2022-02-08 11:41:01 --> Severity: Notice --> Undefined property: CI_Loader::$namespace C:\wamp64\www\impactBussiness\application\views\core\10_body_js.php 48
ERROR - 2022-02-08 11:41:01 --> Severity: Notice --> Undefined property: CI_Loader::$namespace C:\wamp64\www\impactBussiness\application\views\core\10_body_js.php 50
ERROR - 2022-02-08 11:41:10 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-08';
        SELECT
        COUNT(u.idUsuario) AS cantidadGtm
        FROM trade.usuario u
        JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
        AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
        LEFT JOIN trade.usuario_historicoCanal uhd ON uhd.idUsuarioHist = uh.idUsuarioHist
        LEFT JOIN trade.canal ca ON ca.idCanal = uhd.idCanal
        LEFT JOIN trade.grupoCanal gc ON gc.idGrupoCanal = ca.idGrupoCanal
        LEFT JOIN trade.proyecto py ON py.idProyecto = uh.idProyecto
        LEFT JOIN trade.cuenta cu ON cu.idCuenta = py.idCuenta
        WHERE uh.idTipoUsuario IN(1,18) AND u.demo = 0 AND u.estado = 1 AND uh.idAplicacion IN (1, 4, 8)
        
        
ERROR - 2022-02-08 11:48:23 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuario_historico' no es válido. - Invalid query: 
			DECLARE @fecha date=getdate();
			SELECT DISTINCT c.idCuenta AS id
			,c.nombre 
			,COUNT(p.idProyecto) OVER (PARTITION BY uh.idUsuario) proyectos
			FROM sistema.usuario_historico uh 
			JOIN sistema.proyecto p ON p.idProyecto = uh.idProyecto
			JOIN sistema.cuenta c ON c.idCuenta = p.idCuenta
			WHERE uh.estado = 1 
			AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
			AND uh.idUsuario = 1
ERROR - 2022-02-08 11:49:45 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-08';
        SELECT
        COUNT(u.idUsuario) AS cantidadGtm
        FROM trade.usuario u
        JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
        AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
        LEFT JOIN trade.usuario_historicoCanal uhd ON uhd.idUsuarioHist = uh.idUsuarioHist
        LEFT JOIN trade.canal ca ON ca.idCanal = uhd.idCanal
        LEFT JOIN trade.grupoCanal gc ON gc.idGrupoCanal = ca.idGrupoCanal
        LEFT JOIN trade.proyecto py ON py.idProyecto = uh.idProyecto
        LEFT JOIN trade.cuenta cu ON cu.idCuenta = py.idCuenta
        WHERE uh.idTipoUsuario IN(1,18) AND u.demo = 0 AND u.estado = 1 AND uh.idAplicacion IN (1, 4, 8)
        
        
ERROR - 2022-02-08 11:51:52 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuario_menuOpcion' no es válido. - Invalid query: 
			select
			umo.idUsuarioMenuOpcion, umo.idUsuario, umo.idMenuOpcion, mo.page
			from sistema.usuario_menuOpcion umo
			join sistema.menuOpcion mo on mo.idMenuOpcion = umo.idMenuOpcion
			JOIN sistema.intranet_menu im ON im.idMenuOpcion=mo.idMenuOpcion AND im.idIntranet=1
			where umo.estado='1' and umo.idUsuario=1
			
ERROR - 2022-02-08 11:53:02 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'sistema.usuarioMenuOpcion' no es válido. - Invalid query: 
			select
			umo.idUsuarioMenuOpcion, umo.idUsuario, umo.idMenuOpcion, mo.page
			from sistema.usuarioMenuOpcion umo
			join sistema.menu mo on mo.idMenuOpcion = umo.idMenuOpcion
			where umo.estado='1' and umo.idUsuario=1
			
ERROR - 2022-02-08 11:53:43 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-08';
        SELECT
        COUNT(u.idUsuario) AS cantidadGtm
        FROM trade.usuario u
        JOIN trade.usuario_historico uh ON u.idUsuario = uh.idUsuario
        AND @fecha BETWEEN uh.fecIni AND ISNULL(uh.fecFin, @fecha)
        LEFT JOIN trade.usuario_historicoCanal uhd ON uhd.idUsuarioHist = uh.idUsuarioHist
        LEFT JOIN trade.canal ca ON ca.idCanal = uhd.idCanal
        LEFT JOIN trade.grupoCanal gc ON gc.idGrupoCanal = ca.idGrupoCanal
        LEFT JOIN trade.proyecto py ON py.idProyecto = uh.idProyecto
        LEFT JOIN trade.cuenta cu ON cu.idCuenta = py.idCuenta
        WHERE uh.idTipoUsuario IN(1,18) AND u.demo = 0 AND u.estado = 1 AND uh.idAplicacion IN (1, 4, 8)
        
        
ERROR - 2022-02-08 11:57:16 --> Severity: Notice --> Undefined variable: cantidadGtm C:\wamp64\www\impactBussiness\application\views\home.php 111
ERROR - 2022-02-08 11:57:16 --> Severity: Notice --> Undefined variable: cantidadGtm C:\wamp64\www\impactBussiness\application\views\home.php 133
ERROR - 2022-02-08 17:34:28 --> Severity: Notice --> Undefined index: idArticulo C:\wamp64\www\impactBussiness\application\views\modulos\Presupuesto\reporte.php 39
