<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-02-23 09:23:10 --> Unable to connect to the database
ERROR - 2023-02-23 10:11:01 --> Severity: Notice --> Undefined index: item C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 92
ERROR - 2023-02-23 10:30:58 --> Severity: Notice --> Undefined index: id C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\M_Cotizacion.php 161
ERROR - 2023-02-23 10:30:58 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'ORDER'. - Invalid query: 
			DECLARE @hoy DATE = GETDATE();
			WITH lst_historico_estado AS (
				SELECT 
				idCotizacionEstadoHistorico,
				idCotizacionEstado,
				idCotizacionInternaEstado,
				idCotizacion,
				fechaReg,
				idUsuarioReg,
				estado,
				ROW_NUMBER() OVER (PARTITION BY idCotizacion,idCotizacionEstado  ORDER BY idCotizacionEstado) fila
				FROM
				compras.cotizacionEstadoHistorico
			)
			SELECT DISTINCT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, p.idCuenta
				, p.idCentroCosto idCuentaCentroCosto
				--, cc.nombre AS cuentaCentroCosto
				, c.razonSocial AS cuenta
				, cc.subcanal AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, ce.icono
				, p.estado
				, p.fechaRequerida
				, p.diasValidez
				, p.idSolicitante
				, p.fechaDeadline
				, p.flagIgv igv
				, p.fee
				, p.idCotizacionEstado
        		, p.idPrioridad
				, p.motivo
        		, p.comentario
				, p.total
				, p.codOrdenCompra
				, p.motivoAprobacion
				, p.montoOrdenCompra
				, od.idOper
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND cotizacionInterna = 1) nuevos
				, ISNULL((SELECT CASE WHEN DATEDIFF(DAY,fechaReg,@hoy) <= p.diasValidez THEN 1 ELSE 0 END FROM lst_historico_estado WHERE idCotizacion = p.idCotizacion AND p.idCotizacionEstado IN(4,5) AND idCotizacionEstado = 4 AND fila = 1),1) cotizacionValidaCliente
				, p.mostrarPrecio AS flagMostrarPrecio
				, u.nombres + ' ' + u.apePaterno + ' ' + u.apeMaterno as usuario
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN rrhh.dbo.Empresa c ON p.idCuenta = c.idEmpresa
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idEmpresaCanal = p.idCentroCosto
			LEFT JOIN compras.operDetalle od ON od.idCotizacion = p.idCotizacion
				AND od.estado = 1
			LEFT JOIN sistema.usuario u ON u.idUsuario=p.idUsuarioReg
			WHERE 1 = 1
			 AND p.idCotizacion !=
			ORDER BY p.idCotizacion DESC
		
ERROR - 2023-02-23 10:35:44 --> Severity: Notice --> Undefined index: id C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\models\M_Cotizacion.php 161
ERROR - 2023-02-23 10:35:44 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server][SQL Server]Sintaxis incorrecta cerca de la palabra clave 'ORDER'. - Invalid query: 
			DECLARE @hoy DATE = GETDATE();
			WITH lst_historico_estado AS (
				SELECT 
				idCotizacionEstadoHistorico,
				idCotizacionEstado,
				idCotizacionInternaEstado,
				idCotizacion,
				fechaReg,
				idUsuarioReg,
				estado,
				ROW_NUMBER() OVER (PARTITION BY idCotizacion,idCotizacionEstado  ORDER BY idCotizacionEstado) fila
				FROM
				compras.cotizacionEstadoHistorico
			)
			SELECT DISTINCT
				p.idCotizacion
				, p.nombre AS cotizacion
				, CONVERT(VARCHAR, p.fechaEmision, 103) AS fechaEmision
				, 'COTIZACION' AS tipoCotizacion
				, p.codCotizacion
				, p.idCuenta
				, p.idCentroCosto idCuentaCentroCosto
				--, cc.nombre AS cuentaCentroCosto
				, c.razonSocial AS cuenta
				, cc.subcanal AS cuentaCentroCosto
				, ce.nombre AS cotizacionEstado
				, ce.icono
				, p.estado
				, p.fechaRequerida
				, p.diasValidez
				, p.idSolicitante
				, p.fechaDeadline
				, p.flagIgv igv
				, p.fee
				, p.idCotizacionEstado
        		, p.idPrioridad
				, p.motivo
        		, p.comentario
				, p.total
				, p.codOrdenCompra
				, p.motivoAprobacion
				, p.montoOrdenCompra
				, od.idOper
				, (SELECT COUNT(idCotizacionDetalle) FROM compras.cotizacionDetalle WHERE idCotizacion = p.idCotizacion AND cotizacionInterna = 1) nuevos
				, ISNULL((SELECT CASE WHEN DATEDIFF(DAY,fechaReg,@hoy) <= p.diasValidez THEN 1 ELSE 0 END FROM lst_historico_estado WHERE idCotizacion = p.idCotizacion AND p.idCotizacionEstado IN(4,5) AND idCotizacionEstado = 4 AND fila = 1),1) cotizacionValidaCliente
				, p.mostrarPrecio AS flagMostrarPrecio
				, u.nombres + ' ' + u.apePaterno + ' ' + u.apeMaterno as usuario
			FROM compras.cotizacion p
			LEFT JOIN compras.cotizacionEstado ce ON p.idCotizacionEstado = ce.idCotizacionEstado
			LEFT JOIN rrhh.dbo.Empresa c ON p.idCuenta = c.idEmpresa
			LEFT JOIN rrhh.dbo.empresa_Canal cc ON cc.idEmpresaCanal = p.idCentroCosto
			LEFT JOIN compras.operDetalle od ON od.idCotizacion = p.idCotizacion
				AND od.estado = 1
			LEFT JOIN sistema.usuario u ON u.idUsuario=p.idUsuarioReg
			WHERE 1 = 1
			 AND p.idCotizacion !=
			ORDER BY p.idCotizacion DESC
		
ERROR - 2023-02-23 17:28:13 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:28:14 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:28:14 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:28:45 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:29:36 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:29:48 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:34:15 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2928
ERROR - 2023-02-23 17:34:30 --> Severity: Warning --> json_decode() expects parameter 1 to be string, array given C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2928
ERROR - 2023-02-23 17:43:02 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:43:07 --> Query error: [Microsoft][ODBC Driver 11 for SQL Server]Syntax error, permission violation, or other nonspecific error - Invalid query: select top (1) 
                    auth.fechaReg as fechaRegistro,
                    auth.horaReg as horaRegistro,
                    cot.nombre as nombreCotizacion,
                    cot.codCotizacion as codigoCotizacion,
                    cot.fechaEmision as fechaCreacion,
                    us.nombres as nombreUsuario,
                    us.apePaterno as apellidoUsuario,
                    est.nombre as nombreEstado
                    from ImpactBussiness.compras.cotizacionEstadoHistorico auth
                    inner join ImpactBussiness.compras.cotizacion cot on auth.idCotizacion = cot.idCotizacion
                    inner join ImpactBussiness.compras.cotizacionEstado est on cot.idCotizacionEstado= est.idCotizacionEstado
                    inner join ImpactBussiness.sistema.usuario us on auth.idUsuarioReg = us.idUsuario 
                    where auth.idCotizacion = {$id} order by idCotizacionEstadoHistorico DESC
ERROR - 2023-02-23 17:43:44 --> Severity: error --> Exception: Cannot use object of type CI_DB_sqlsrv_result as array C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2933
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: nombreCotizacion C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2933
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: codigoCotizacion C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2934
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: fechaCreacion C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2935
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: nombreEstado C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2936
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: nombreUsuario C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2937
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: apellidoUsuario C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2938
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: fechaRegistro C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2939
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined index: horaRegistro C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2940
ERROR - 2023-02-23 17:44:13 --> Severity: Notice --> Undefined variable: result C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2953
ERROR - 2023-02-23 17:46:56 --> Severity: Notice --> Undefined variable: result C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\controllers\Cotizacion.php 2954
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 2
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 3
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 4
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 5
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:06:13 --> Severity: Notice --> Undefined variable: data C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 2
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 3
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 4
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 5
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:07:10 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 2
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 3
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 4
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 5
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 9
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:07:19 --> Severity: Notice --> Undefined offset: 0 C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 10
ERROR - 2023-02-23 18:08:12 --> Severity: Notice --> Trying to get property of non-object C:\Apache24\htdocs\php71.loc\ImpactBussiness\application\views\modulos\Cotizacion\viewAnulacionInfo.php 2
