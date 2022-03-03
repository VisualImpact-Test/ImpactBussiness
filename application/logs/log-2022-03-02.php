<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-02 09:01:48 --> Unable to connect to the database
ERROR - 2022-03-02 15:05:56 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de columna 'fecha' no es válido. - Invalid query: 
			SELECT
				p.idPresupuesto
				, p.nombre AS presupuesto
				, CONVERT(VARCHAR, p.fecha, 103) AS fecha
				, tp.idTipoPresupuesto
				, tp.nombre AS tipoPresupuesto
				, p.nroPresupuesto
				, c.idCuenta
				, c.nombre AS cuenta
				, cc.idCuentaCentroCosto
				, cc.nombre AS cuentaCentroCosto
				, p.estado
			FROM compras.presupuesto p
			JOIN compras.tipoPresupuesto tp ON p.idTipoPresupuesto = tp.idTipoPresupuesto
			LEFT JOIN visualImpact.logistica.cuenta c ON p.idCuenta = c.idCuenta
			LEFT JOIN visualImpact.logistica.cuentaCentroCosto cc ON p.idCentroCosto = cc.idCuentaCentroCosto
			WHERE 1 = 1
			
		
