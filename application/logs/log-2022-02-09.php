<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-09 10:17:27 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]El nombre de objeto 'trade.usuario' no es válido. - Invalid query: 
            SELECT td.breve, 
            u.numDocumento, 
            u.nombres, 
            u.apePaterno, 
            u.apeMaterno, 
            emp.email_corp,
            emp.telefono,
            emp.celular,
            emp.archFoto
        FROM trade.usuario u
            INNER JOIN trade.usuario_tipoDocumento td ON td.idTipoDocumento = u.idTipoDocumento
            INNER JOIN rrhh.dbo.empleado emp ON emp.numTipoDocuIdent = u.numDocumento
        WHERE u.idUsuario = 1;
		
