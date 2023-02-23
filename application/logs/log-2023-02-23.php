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
		
