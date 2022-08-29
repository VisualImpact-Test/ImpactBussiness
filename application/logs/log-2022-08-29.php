<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-08-29 10:57:28 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'UNION'. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
				sistema.usuarioMenu amo
				JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
				JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
				LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1	
			WHERE
				amo.idUsuario = 5
				AND amo.estado = 1
			ORDER BY gm.orden,mo.nombre
			UNION
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
			sistema.usuarioTipoMenu amo
			JOIN sistema.usuarioHistorico uh ON amo.idTipoUsuario = uh.idTipoUsuario 
				AND uh.idUsuario = 5
				AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecha,@fecha) = 1
				AND uh.estado = 1
			JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
			JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
			LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1
			ORDER BY gm.orden,mo.nombre	
			
ERROR - 2022-08-29 10:57:32 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'UNION'. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
				sistema.usuarioMenu amo
				JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
				JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
				LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1	
			WHERE
				amo.idUsuario = 5
				AND amo.estado = 1
			ORDER BY gm.orden,mo.nombre
			UNION
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
			sistema.usuarioTipoMenu amo
			JOIN sistema.usuarioHistorico uh ON amo.idTipoUsuario = uh.idTipoUsuario 
				AND uh.idUsuario = 5
				AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecha,@fecha) = 1
				AND uh.estado = 1
			JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
			JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
			LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1
			ORDER BY gm.orden,mo.nombre	
			
ERROR - 2022-08-29 10:57:50 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Debe haber elementos ORDER BY en la lista de selección si la instrucción contiene el operador UNION, INTERSECT o EXCEPT. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
				sistema.usuarioMenu amo
				JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
				JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
				LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1	
			WHERE
				amo.idUsuario = 5
				AND amo.estado = 1
			--ORDER BY gm.orden,mo.nombre
			UNION
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
			sistema.usuarioTipoMenu amo
			JOIN sistema.usuarioHistorico uh ON amo.idTipoUsuario = uh.idTipoUsuario 
				AND uh.idUsuario = 5
				AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecha,@fecha) = 1
				AND uh.estado = 1
			JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
			JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
			LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1
			ORDER BY gm.orden,mo.nombre	
			
ERROR - 2022-08-29 10:58:04 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'UNION'. - Invalid query: 
			DECLARE @fecha DATE = GETDATE();
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
				sistema.usuarioMenu amo
				JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
				JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
				LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1	
			WHERE
				amo.idUsuario = 5
				AND amo.estado = 1
			ORDER BY gm.orden,mo.nombre
			UNION
			SELECT 
				mo.*
				, gm.idGrupoMenu
				, gm.nombre grupoMenu
				, gm.cssIcono grupoIcono
				, gm.page grupoPage
				, sgm.idSubGrupoMenu
				, sgm.nombre subGrupoMenu
				, sgm.cssIcono subGrupoIcono
				, sgm.page subGrupoPage
			FROM 
			sistema.usuarioTipoMenu amo
			JOIN sistema.usuarioHistorico uh ON amo.idTipoUsuario = uh.idTipoUsuario 
				AND uh.idUsuario = 5
				AND General.dbo.fn_fechaVigente(uh.fecIni,uh.fecFin,@fecha,@fecha) = 1
				AND uh.estado = 1
			JOIN sistema.menu mo ON amo.idMenuOpcion = mo.idMenuOpcion AND mo.estado = 1
			JOIN sistema.grupoMenu gm ON gm.idGrupoMenu = mo.idGrupoMenu AND gm.estado = 1
			LEFT JOIN sistema.subGrupoMenu sgm ON sgm.idSubGrupoMenu = mo.idsubGrupoMenu AND sgm.estado = 1
			ORDER BY gm.orden,mo.nombre	
			
ERROR - 2022-08-29 10:58:55 --> Severity: Notice --> Undefined property: CI_Loader::$namespace C:\wamp64\visualimpact_test\ImpactBussiness\application\views\core\10_body_js.php 41
ERROR - 2022-08-29 10:58:55 --> Severity: Notice --> Undefined property: CI_Loader::$namespace C:\wamp64\visualimpact_test\ImpactBussiness\application\views\core\10_body_js.php 43
ERROR - 2022-08-29 11:10:41 --> Severity: error --> Exception: Call to undefined method M_Autorizacion::obtenerRubro() C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 35
ERROR - 2022-08-29 11:34:20 --> Severity: error --> Exception: Call to undefined method M_Autorizacion::obtenerRubro() C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 35
ERROR - 2022-08-29 11:34:35 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:34:35 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:34:45 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:34:45 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:35:54 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:35:54 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:36:00 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:36:00 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:36:05 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:36:05 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:38:19 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:38:19 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:38:33 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:38:33 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 11:38:39 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 11:38:39 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 17:29:46 --> Unable to connect to the database
ERROR - 2022-08-29 12:33:10 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 82
ERROR - 2022-08-29 12:41:43 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 82
ERROR - 2022-08-29 12:41:57 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 82
ERROR - 2022-08-29 12:42:33 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 82
ERROR - 2022-08-29 13:01:30 --> Severity: Notice --> Undefined variable: gapEmpresas C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 26
ERROR - 2022-08-29 13:01:30 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:02:45 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "gd.idGap" no se pudo enlazar. - Invalid query: 
		DECLARE @fecha DATE = GETDATE();
		SELECT
		g.idEmpresa,
		gp.gap
		FROM
		compras.gap g
		JOIN compras.gapDetalle gp ON g.idGap = gd.idGap
			AND g.estado = 1
			AND General.dbo.fn_fechaVigente(gp.fechaInicio,gp.fechaFin,@fecha,@fecha) = 1
			AND gp.estado = 1
		
ERROR - 2022-08-29 13:03:00 --> Severity: Notice --> Array to string conversion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 26
ERROR - 2022-08-29 13:03:00 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:03:11 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "gp.gap" no se pudo enlazar. - Invalid query: 
		DECLARE @fecha DATE = GETDATE();
		SELECT
		g.idEmpresa,
		gp.gap
		FROM
		compras.gap g
		JOIN compras.gapDetalle gd ON g.idGap = gd.idGap
			AND g.estado = 1
			AND General.dbo.fn_fechaVigente(gd.fechaInicio,gd.fechaFin,@fecha,@fecha) = 1
			AND gd.estado = 1
		
ERROR - 2022-08-29 13:03:18 --> Severity: Notice --> Array to string conversion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 26
ERROR - 2022-08-29 13:03:18 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:04:03 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:04:19 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:08:24 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:08:52 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:10:42 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:10:55 --> Severity: Notice --> Undefined variable: cotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Cotizacion\viewFormularioRegistro.php 83
ERROR - 2022-08-29 13:22:44 --> 
		SELECT
			cdp.idCotizacionDetalleProveedor,
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			ei.idItemEstado,
			ei.nombre AS estadoItem,
			cdpd.costo,
			cd.cantidad,
			cdp.idProveedor,
			cdp.idCotizacion,
			cd.idCotizacionDetalle,
			p.razonSocial proveedor,
			um.nombre unidadMedida,
			cdpd.costo/cd.cantidad as costoUnitario,
			cdpd.comentario,
			cdpd.diasValidez,
			CONVERT(VARCHAR, cdpd.fechaValidez, 103) AS fechaValidez,
			cdpd.fechaEntrega,
			cde.nombre AS cotizacionDetalleEstado,
			CONVERT( VARCHAR, cd.fechaCreacion, 103)  AS fechaCreacion
		FROM
		compras.cotizacionDetalleProveedor cdp
		JOIN compras.proveedor p ON p.idProveedor = cdp.idProveedor
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdpd.idCotizacionDetalle
		JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
		LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cd.idUnidadMedida
		JOIN compras.item i ON i.idItem = cdpd.idItem
			AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
		JOIN compras.itemEstado ei ON cd.idItemEstado = ei.idItemEstado
		WHERE 1 = 1AND cdp.idCotizacion = 130
		
ERROR - 2022-08-29 13:24:02 --> 
		SELECT
			cdp.idCotizacionDetalleProveedor,
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			ei.idItemEstado,
			ei.nombre AS estadoItem,
			cdpd.costo,
			cd.cantidad,
			cdp.idProveedor,
			cdp.idCotizacion,
			cd.idCotizacionDetalle,
			p.razonSocial proveedor,
			um.nombre unidadMedida,
			cdpd.costo/cd.cantidad as costoUnitario,
			cdpd.comentario,
			cdpd.diasValidez,
			CONVERT(VARCHAR, cdpd.fechaValidez, 103) AS fechaValidez,
			cdpd.fechaEntrega,
			cde.nombre AS cotizacionDetalleEstado,
			CONVERT( VARCHAR, cd.fechaCreacion, 103)  AS fechaCreacion
		FROM
		compras.cotizacionDetalleProveedor cdp
		JOIN compras.proveedor p ON p.idProveedor = cdp.idProveedor
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdpd.idCotizacionDetalle
		JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
		LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cd.idUnidadMedida
		JOIN compras.item i ON i.idItem = cdpd.idItem
			AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
		JOIN compras.itemEstado ei ON cd.idItemEstado = ei.idItemEstado
		WHERE 1 = 1AND cdp.idProveedor = 78AND cdp.idCotizacion = 130
		
ERROR - 2022-08-29 13:24:02 --> [{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":185,"idItem":458,"item":"CAMISA\/BLUSA AZUL EN OXFORD SAN JACINTO CON BOLSILLO, MANGA LARGA CON 1 BORDADO (VISUAL IMPACT)","tipoItem":"Textiles","idItemEstado":1,"estadoItem":"En sistema","costo":null,"cantidad":34,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":297,"proveedor":"rengar","unidadMedida":null,"costoUnitario":null,"comentario":null,"diasValidez":null,"fechaValidez":null,"fechaEntrega":null,"cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"},{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":186,"idItem":78,"item":"MANDIL AZUL\/ ROJO DE TASLAN GAMUZADO CON ESTAMPADO","tipoItem":"Articulo","idItemEstado":2,"estadoItem":"Nuevo","costo":null,"cantidad":15,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":298,"proveedor":"rengar","unidadMedida":null,"costoUnitario":null,"comentario":null,"diasValidez":null,"fechaValidez":null,"fechaEntrega":null,"cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"}]
ERROR - 2022-08-29 13:25:21 --> 
		SELECT
			cdp.idCotizacionDetalleProveedor,
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			ei.idItemEstado,
			ei.nombre AS estadoItem,
			cdpd.costo,
			cd.cantidad,
			cdp.idProveedor,
			cdp.idCotizacion,
			cd.idCotizacionDetalle,
			p.razonSocial proveedor,
			um.nombre unidadMedida,
			cdpd.costo/cd.cantidad as costoUnitario,
			cdpd.comentario,
			cdpd.diasValidez,
			CONVERT(VARCHAR, cdpd.fechaValidez, 103) AS fechaValidez,
			cdpd.fechaEntrega,
			cde.nombre AS cotizacionDetalleEstado,
			CONVERT( VARCHAR, cd.fechaCreacion, 103)  AS fechaCreacion
		FROM
		compras.cotizacionDetalleProveedor cdp
		JOIN compras.proveedor p ON p.idProveedor = cdp.idProveedor
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdpd.idCotizacionDetalle
		JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
		LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cd.idUnidadMedida
		JOIN compras.item i ON i.idItem = cdpd.idItem
			AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
		JOIN compras.itemEstado ei ON cd.idItemEstado = ei.idItemEstado
		WHERE 1 = 1AND cdp.idProveedor = 78AND cdp.idCotizacion = 130
		
ERROR - 2022-08-29 13:25:21 --> [{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":185,"idItem":458,"item":"CAMISA\/BLUSA AZUL EN OXFORD SAN JACINTO CON BOLSILLO, MANGA LARGA CON 1 BORDADO (VISUAL IMPACT)","tipoItem":"Textiles","idItemEstado":1,"estadoItem":"En sistema","costo":510,"cantidad":34,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":297,"proveedor":"rengar","unidadMedida":null,"costoUnitario":15,"comentario":"","diasValidez":10,"fechaValidez":"08\/09\/2022","fechaEntrega":"2022-09-03","cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"},{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":186,"idItem":78,"item":"MANDIL AZUL\/ ROJO DE TASLAN GAMUZADO CON ESTAMPADO","tipoItem":"Articulo","idItemEstado":2,"estadoItem":"Nuevo","costo":750,"cantidad":15,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":298,"proveedor":"rengar","unidadMedida":null,"costoUnitario":50,"comentario":"","diasValidez":10,"fechaValidez":"08\/09\/2022","fechaEntrega":"2022-09-03","cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"}]
ERROR - 2022-08-29 13:25:26 --> 
		SELECT
			cdp.idCotizacionDetalleProveedor,
			cdpd.idCotizacionDetalleProveedorDetalle,
			cdpd.idItem,
			i.nombre item,
			it.nombre tipoItem,
			ei.idItemEstado,
			ei.nombre AS estadoItem,
			cdpd.costo,
			cd.cantidad,
			cdp.idProveedor,
			cdp.idCotizacion,
			cd.idCotizacionDetalle,
			p.razonSocial proveedor,
			um.nombre unidadMedida,
			cdpd.costo/cd.cantidad as costoUnitario,
			cdpd.comentario,
			cdpd.diasValidez,
			CONVERT(VARCHAR, cdpd.fechaValidez, 103) AS fechaValidez,
			cdpd.fechaEntrega,
			cde.nombre AS cotizacionDetalleEstado,
			CONVERT( VARCHAR, cd.fechaCreacion, 103)  AS fechaCreacion
		FROM
		compras.cotizacionDetalleProveedor cdp
		JOIN compras.proveedor p ON p.idProveedor = cdp.idProveedor
		JOIN compras.cotizacionDetalleProveedorDetalle cdpd ON cdp.idCotizacionDetalleProveedor = cdpd.idCotizacionDetalleProveedor
		JOIN compras.cotizacionDetalle cd ON cd.idCotizacionDetalle = cdpd.idCotizacionDetalle
		JOIN compras.cotizacionDetalleEstado cde ON cd.idCotizacionDetalleEstado = cde.idCotizacionDetalleEstado
		LEFT JOIN compras.unidadMedida um ON um.idUnidadMedida = cd.idUnidadMedida
		JOIN compras.item i ON i.idItem = cdpd.idItem
			AND i.estado = 1
		JOIN compras.itemTipo it ON it.idItemTipo = i.idItemTipo
		JOIN compras.itemEstado ei ON cd.idItemEstado = ei.idItemEstado
		WHERE 1 = 1AND cdp.idProveedor = 78AND cdp.idCotizacion = 130
		
ERROR - 2022-08-29 13:25:26 --> [{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":185,"idItem":458,"item":"CAMISA\/BLUSA AZUL EN OXFORD SAN JACINTO CON BOLSILLO, MANGA LARGA CON 1 BORDADO (VISUAL IMPACT)","tipoItem":"Textiles","idItemEstado":1,"estadoItem":"En sistema","costo":510,"cantidad":34,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":297,"proveedor":"rengar","unidadMedida":null,"costoUnitario":15,"comentario":"","diasValidez":10,"fechaValidez":"08\/09\/2022","fechaEntrega":"2022-09-03","cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"},{"idCotizacionDetalleProveedor":159,"idCotizacionDetalleProveedorDetalle":186,"idItem":78,"item":"MANDIL AZUL\/ ROJO DE TASLAN GAMUZADO CON ESTAMPADO","tipoItem":"Articulo","idItemEstado":2,"estadoItem":"Nuevo","costo":750,"cantidad":15,"idProveedor":78,"idCotizacion":130,"idCotizacionDetalle":298,"proveedor":"rengar","unidadMedida":null,"costoUnitario":50,"comentario":"","diasValidez":10,"fechaValidez":"08\/09\/2022","fechaEntrega":"2022-09-03","cotizacionDetalleEstado":"Entregado","fechaCreacion":"29\/08\/2022"}]
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:35:14 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 13:38:10 --> Severity: Notice --> Undefined index: value C:\wamp64\visualimpact_test\ImpactBussiness\application\helpers\my_helper.php 1242
ERROR - 2022-08-29 15:03:59 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:03:59 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:04:07 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:04:07 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:04:29 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:04:29 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:04:57 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:04:57 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:05:12 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:05:12 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:05:25 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:05:25 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:05:42 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:05:42 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:06:00 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:06:00 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:06:09 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:06:09 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:06:44 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:06:44 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:06:46 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'CONVERT'. - Invalid query: 
			SELECT
                at.nombre tipoAutorizacion,
                ae.nombre estadoAutorizacion,
                a.nombre,
                c.codCotizacion,
                a.comentario,
                a.idUsuarioReg,
                ISNULL(u.nombres,'') + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'') usuario 
                CONVERT(VARCHAR, a.fechaCreacion, 103) AS fechaCreacion,
                CONVERT(VARCHAR, a.fechaModificacion, 103) AS fechaModificacion
			FROM compras.autorizacion a 
            JOIN compras.autorizacionEstado ae ON ae.idAutorizacionEstado = a.idAutorizacionEstado
            JOIN compras.autorizacionTipo at ON at.idTipoAutorizacion = a.idTipoAutorizacion
            JOIN sistema.usuario u ON a.idUsuarioReg = u.idUsuario 
            LEFT JOIN compras.cotizacion c ON a.idCotizacion  = c.idCotizacion
			WHERE a.estado = 1
		
ERROR - 2022-08-29 15:07:07 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:07:07 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: idProveedor C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 54
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: razonSocial C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 55
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: nroDocumento C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 56
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 57
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 58
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: departamento C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 59
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: provincia C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 60
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: distrito C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 61
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: direccion C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 62
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: nombreContacto C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 63
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: correoContacto C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 64
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: numeroContacto C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 65
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: informacionAdicional C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 66
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: idProveedorEstado C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 67
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: estado C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 68
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: estadoIcono C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 69
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: estadotoggle C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 70
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: rubros C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 43
ERROR - 2022-08-29 15:07:09 --> Severity: Notice --> Undefined index: metodosPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 44
ERROR - 2022-08-29 15:08:04 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:08:04 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:08:06 --> Severity: Notice --> Undefined variable: datos C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:08:06 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:08:08 --> Severity: Notice --> Undefined variable: datos C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:08:08 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:08:33 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:08:33 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:08:34 --> Severity: Notice --> Undefined variable: datos C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:08:34 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:10:42 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:10:42 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:10:43 --> Severity: Notice --> Undefined variable: datos C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:10:43 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:10:52 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:10:52 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:10:53 --> Severity: Notice --> Undefined variable: datos C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:10:53 --> Severity: Warning --> Invalid argument supplied for foreach() C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Proveedor\reporte.php 21
ERROR - 2022-08-29 15:11:02 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:11:02 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:11:03 --> Severity: Notice --> Undefined index: direccion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\reporte.php 33
ERROR - 2022-08-29 15:11:20 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:11:20 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:14:12 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:14:12 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:18:45 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:18:45 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:19:41 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:19:41 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:27:48 --> Severity: Notice --> Undefined variable: totalOper C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 4
ERROR - 2022-08-29 15:28:46 --> Severity: Notice --> Undefined variable: totalOper C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 4
ERROR - 2022-08-29 15:33:14 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:33:14 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:35:01 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:35:01 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:36:11 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:36:11 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:36:37 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:36:37 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:37:59 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:37:59 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:40:43 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:40:43 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:42:41 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:42:41 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:43:56 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:43:56 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:50:55 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:50:55 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 15:51:10 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 15:51:10 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: rucProveedor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 17
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: proveedor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 21
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: tipoAutorizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 28
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: codCotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 32
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: item C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 36
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: comentario C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 42
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: costo C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 48
ERROR - 2022-08-29 16:02:00 --> Severity: Notice --> Undefined index: nuevoValor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 52
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: rucProveedor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 17
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: proveedor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 21
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: tipoAutorizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 28
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: codCotizacion C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 32
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: item C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 36
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: comentario C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 42
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: costo C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 48
ERROR - 2022-08-29 16:02:43 --> Severity: Notice --> Undefined index: nuevoValor C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\frmActualizarAutorizacion.php 52
ERROR - 2022-08-29 16:07:35 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 16:07:35 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 16:07:43 --> Severity: Notice --> Undefined variable: html C:\wamp64\visualimpact_test\ImpactBussiness\application\controllers\Finanzas\Autorizacion.php 95
ERROR - 2022-08-29 16:10:20 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 16:10:20 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 16:24:19 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 16:24:19 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
ERROR - 2022-08-29 16:33:57 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Sintaxis incorrecta cerca de '.'. - Invalid query: 
			SELECT
				a.idAutorizacion,
                at.nombre tipoAutorizacion,
                ae.nombre estadoAutorizacion,
				a.idAutorizacionEstado
				a.idCotizacionDetalle,
                a.nombre,
                c.codCotizacion,
                a.comentario,
                a.idUsuarioReg,
                ISNULL(u.nombres,'') + ' ' + ISNULL(u.apePaterno,'') + ' ' + ISNULL(u.apeMaterno,'') usuario ,
                CONVERT(VARCHAR, a.fechaCreacion, 103) AS fechaCreacion,
                CONVERT(VARCHAR, a.fechaModificacion, 103) AS fechaModificacion,
				p.nroDocumento rucProveedor,
				p.razonSocial proveedor,
				cd.nombre item,
				cd.costo,
				a.nuevoValor,
				a.nuevoGap
			FROM compras.autorizacion a 
            JOIN compras.autorizacionEstado ae ON ae.idAutorizacionEstado = a.idAutorizacionEstado
            JOIN sistema.usuario u ON a.idUsuarioReg = u.idUsuario 
			JOIN compras.cotizacion c ON c.idCotizacion = a.idCotizacion 
			JOIN compras.cotizacionDetalle cd ON a.idCotizacionDetalle = cd.idCotizacionDetalle
			JOIN compras.proveedor p ON p.idProveedor = cd.idProveedor
			JOIN compras.autorizacionTipo at ON at.idTipoAutorizacion = a.idTipoAutorizacion
			WHERE a.estado = 1
			 AND a.idAutorizacion = 1
		
ERROR - 2022-08-29 16:38:28 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El identificador formado por varias partes "c.idAutorizacion" no se pudo enlazar. - Invalid query: 
			SELECT
				a.idAutorizacion,
				cda.idCotizacionDetalleArchivo,
				cda.idTipoArchivo,
				cda.nombre_inicial,
				cda.nombre_archivo,
				cda.extension
			FROM
			compras.autorizacion a
			LEFT JOIN compras.cotizacionDetalleArchivos cda ON a.idAutorizacion = cda.idAutorizacion
			WHERE
			1 = 1
			 AND c.idAutorizacion IN (1)
		
ERROR - 2022-08-29 16:42:17 --> Severity: Notice --> Undefined variable: rubro C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 47
ERROR - 2022-08-29 16:42:17 --> Severity: Notice --> Undefined variable: metodoPago C:\wamp64\visualimpact_test\ImpactBussiness\application\views\modulos\Finanzas\Autorizacion\index.php 53
