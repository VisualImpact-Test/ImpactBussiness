<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-25 09:27:21 --> Unable to connect to the database
ERROR - 2022-02-25 11:26:58 --> Severity: error --> Exception: Unable to locate the model you have specified: M_Categoria C:\wamp64\www\impactBussiness\system\core\Loader.php 348
ERROR - 2022-02-25 11:27:12 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\index.php 50
ERROR - 2022-02-25 11:28:50 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categoria' no es válido. - Invalid query: 
			SELECT
				a.idCategoria
				, a.nombre AS categoria
				, a.estado
			FROM compras.categoria a
			WHERE 1 = 1
			
		
ERROR - 2022-02-25 11:32:42 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categoria' no es válido. - Invalid query: 
			SELECT
				a.idCategoria
				, a.nombre AS categoria
				, a.estado
			FROM compras.categoria a
			WHERE 1 = 1
			
		
ERROR - 2022-02-25 11:33:01 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categorias' no es válido. - Invalid query: 
			SELECT
				a.idCategoria
				, a.nombre AS categoria
				, a.estado
			FROM compras.categorias a
			WHERE 1 = 1
			
		
ERROR - 2022-02-25 11:33:18 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idCategoria' no es válido. - Invalid query: 
			SELECT
				a.idCategoria
				, a.nombre AS categoria
				, a.estado
			FROM compras.categoriaArticulo a
			WHERE 1 = 1
			
		
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 19
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 23
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 23
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 29
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 19
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 23
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 23
ERROR - 2022-02-25 11:33:37 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Categoria\reporte.php 29
ERROR - 2022-02-25 11:42:22 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idCategoriaArtioulo' no es válido. - Invalid query: 
			SELECT
				idCategoriaArtioulo
			FROM compras.categoriaArticulo a
			WHERE
			(a.nombre LIKE '%MATERIALES%')
			
		
ERROR - 2022-02-25 11:42:47 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipoCategoria' no es válido. - Invalid query: 
			SELECT
				idTipoCategoria AS id
				, nombre AS value
			FROM compras.tipoCategoria
			WHERE estado = 1
		
ERROR - 2022-02-25 11:46:00 --> Severity: Notice --> Undefined index: idCategoria C:\wamp64\www\impactBussiness\application\controllers\Configuracion\Categoria.php 197
ERROR - 2022-02-25 11:46:00 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categoria' no es válido. - Invalid query: UPDATE "compras"."categoria" SET "estado" = 0
WHERE "idCategoria" IS NULL
ERROR - 2022-02-25 11:46:17 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.categoria' no es válido. - Invalid query: UPDATE "compras"."categoria" SET "estado" = 0
WHERE "idCategoriaArticulo" = 3
ERROR - 2022-02-25 12:06:38 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Marca\index.php 50
ERROR - 2022-02-25 12:06:51 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Marca\index.php 50
ERROR - 2022-02-25 12:07:34 --> Severity: Notice --> Undefined variable: tipoMarca C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Marca\index.php 50
ERROR - 2022-02-25 12:11:25 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.marca' no es válido. - Invalid query: INSERT INTO "compras"."marca" ("nombre") VALUES ('TRAMONTINA')
ERROR - 2022-02-25 12:12:10 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idMarcaArticuloArticulo' no es válido. - Invalid query: 
			SELECT
				a.idMarcaArticuloArticulo
				, a.nombre AS marca
				, a.estado
			FROM compras.marcaArticulo a
			WHERE 1 = 1
			
		
ERROR - 2022-02-25 12:13:49 --> Severity: Notice --> Undefined index: servicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Marca\formularioActualizacion.php 6
ERROR - 2022-02-25 12:14:56 --> Severity: Notice --> Undefined index: idMarca C:\wamp64\www\impactBussiness\application\controllers\Configuracion\Marca.php 147
ERROR - 2022-02-25 12:14:56 --> Severity: Notice --> Undefined index: tipo C:\wamp64\www\impactBussiness\application\controllers\Configuracion\Marca.php 150
ERROR - 2022-02-25 12:14:56 --> Severity: Notice --> Undefined index: idMarca C:\wamp64\www\impactBussiness\application\controllers\Configuracion\Marca.php 165
ERROR - 2022-02-25 12:14:56 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idMarca' no es válido. - Invalid query: UPDATE "compras"."marcaArticulo" SET "nombre" = 'VISUAL', "idTipoMarca" = NULL
WHERE "idMarca" IS NULL
ERROR - 2022-02-25 15:10:46 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioRegistro.php 11
ERROR - 2022-02-25 15:10:57 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioRegistro.php 11
ERROR - 2022-02-25 15:17:59 --> Severity: Notice --> Undefined variable: informacionServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioActualizacion.php 6
ERROR - 2022-02-25 15:17:59 --> Severity: Notice --> Undefined variable: informacionServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioActualizacion.php 7
ERROR - 2022-02-25 15:17:59 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioActualizacion.php 12
ERROR - 2022-02-25 15:17:59 --> Severity: Notice --> Undefined variable: informacionServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Articulo\formularioActualizacion.php 12
ERROR - 2022-02-25 15:21:57 --> Severity: Notice --> Undefined variable: tipoServicio C:\wamp64\www\impactBussiness\application\views\modulos\Configuracion\Tipo\Servicio\formularioRegistro.php 11
ERROR - 2022-02-25 15:22:53 --> Severity: Notice --> Undefined index: tipo C:\wamp64\www\impactBussiness\application\controllers\Configuracion\Tipo.php 290
ERROR - 2022-02-25 15:22:53 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipo' no es válido. - Invalid query: INSERT INTO "compras"."tipo" ("nombre", "idTipoTipo") VALUES ('Electricidad', NULL)
ERROR - 2022-02-25 15:23:21 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'compras.tipo' no es válido. - Invalid query: INSERT INTO "compras"."tipo" ("nombre") VALUES ('Electricidad')
ERROR - 2022-02-25 15:23:52 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'idTipo' no es válido. - Invalid query: 
			SELECT
				a.idTipoServicio AS idTipo
				, a.nombre AS tipo
				, a.estado
			FROM compras.tipoServicio a
			WHERE 1 = 1
			 AND a.idTipo = 4
		
ERROR - 2022-02-25 15:26:01 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]No se puede actualizar la columna de identidad 'idTipoServicio'. - Invalid query: UPDATE "compras"."tipoServicio" SET "idTipoServicio" = '4', "nombre" = 'Elecaatricidad'
WHERE "idTipoServicio" = '4'
ERROR - 2022-02-25 17:28:10 --> Unable to connect to the database
