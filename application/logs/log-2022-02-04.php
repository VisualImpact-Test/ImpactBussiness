<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-04 09:51:44 --> Unable to connect to the database
ERROR - 2022-02-04 10:46:02 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-04';
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
         AND py.idProyecto = 3  AND cu.idCuenta = 3 
        
ERROR - 2022-02-04 10:46:05 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-04';
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
         AND py.idProyecto = 3  AND cu.idCuenta = 3 
        
ERROR - 2022-02-04 10:46:12 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
        DECLARE @fecha DATE = '2022-02-04';
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
         AND py.idProyecto = 3  AND cu.idCuenta = 3 
        
ERROR - 2022-02-04 10:46:22 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipoArticulo' no es válido. - Invalid query: 
			SELECT
				idTipoArticulo AS id
				, nombre AS value
			FROM compras.tipoArticulo
			WHERE estado = 1
		
ERROR - 2022-02-04 11:22:57 --> Severity: error --> Exception: Call to undefined method M_Articulo::obtenerArticulosLogistica() C:\wamp64\www\impactBussiness\application\controllers\Tarifario\Articulo.php 111
ERROR - 2022-02-04 11:23:39 --> Severity: Notice --> Undefined index: equivalenteLogistica C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioActualizacion.php 11
ERROR - 2022-02-04 11:23:39 --> Severity: Notice --> Undefined index: idArticuloLogistica C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioActualizacion.php 12
ERROR - 2022-02-04 11:23:39 --> Severity: Notice --> Undefined variable: tipoArticulo C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioActualizacion.php 17
ERROR - 2022-02-04 11:23:39 --> Severity: Notice --> Undefined variable: marcaArticulo C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioActualizacion.php 23
ERROR - 2022-02-04 11:23:39 --> Severity: Notice --> Undefined variable: categoriaArticulo C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioActualizacion.php 29
ERROR - 2022-02-04 11:53:28 --> Severity: Notice --> Undefined index: insert C:\wamp64\www\impactBussiness\application\controllers\Tarifario\Articulo.php 209
ERROR - 2022-02-04 11:53:28 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'AND'. - Invalid query: 
			SELECT
				idTarifarioArticulo
			FROM compras.tarifarioArticulo ta
			WHERE
			ta.idArticulo =  AND flag_actual = 1
			
		
ERROR - 2022-02-04 14:04:34 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipoArticulo' no es válido. - Invalid query: 
			SELECT
				idTipoArticulo AS id
				, nombre AS value
			FROM compras.tipoArticulo
			WHERE estado = 1
		
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'idTarifarioArticulo' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 17
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecIni' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 19
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecFin' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 20
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'proveedor' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 22
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'idTarifarioArticulo' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 17
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecIni' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 19
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecFin' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 20
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'proveedor' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 22
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'idTarifarioArticulo' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 17
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecIni' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 19
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'fecFin' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 20
ERROR - 2022-02-04 14:04:45 --> Severity: Warning --> Illegal string offset 'proveedor' C:\wamp64\www\impactBussiness\application\views\modulos\Tarifario\Articulo\formularioHistorial.php 22
ERROR - 2022-02-04 14:07:53 --> Severity: Notice --> Undefined index: idTarifarioArticulo C:\wamp64\www\impactBussiness\application\controllers\Tarifario\Articulo.php 374
ERROR - 2022-02-04 14:08:00 --> Severity: Notice --> Undefined index: idTarifarioArticulo C:\wamp64\www\impactBussiness\application\controllers\Tarifario\Articulo.php 374
