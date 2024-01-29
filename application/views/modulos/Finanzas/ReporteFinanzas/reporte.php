<style> 
.claseEstado{
    font-size: 12px!important; 
    width: 70px;
    text-align: center;
}
.tdtext{
    display: flex;
    justify-content: center;
}
</style>
<div class="card-datatable">
	<table id="tb-reporteFinanzas" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
                <th>CUENTA</th>
				<th>CENTRO DE COSTO</th>
				<th>RAZON SOCIAL</th>
				<th>RUC</th>
                <th>DESCRIPCIÓN SERVICIO</th>
                
				<th>MONEDA</th>
                <th>MONTO FACTURADO</th>
                <th>NOTA CREDITO</th>
                <th>MONTO PAGADO</th>
               <th>TOTAL FALTANTE</th>

				<th>ESTADO PAGO</th>
			</tr>
		</thead>
        <tbody>
        <? $n = 1; ?>
			<?php foreach ($pagosGenerados as $k => $row) : ?>
              
                <tr>
				<td class="td-center"><?= $n; ?></td>
                <td><?= $row['cuenta']; ?></td>
                <td><?= $row['canal']; ?></td>
				<td class=""><?= $row['datosProveedor']; ?></td>
				<td><?= $row['numDocumento']; ?></td>
                <td><?= $row['descripcionServicio']; ?></td>
                
                <td class="text-center"><?= $row['moneda']; ?></td>

         
				<td class="text-right"><?= (empty($row['montofacturas'])) ? '-' :  numeroVista($row['montofacturas']); ?></td>
                <td class="text-center"><?= (empty($row['montonotacredito'])) ? '-' :  numeroVista($row['montonotacredito']); ?></td>

                <td class="text-right"><?= (empty($row['montoefectuados'])) ? '-' :  numeroVista($row['montoefectuados']); ?></td>
				<td class="text-center"></td>


                <?php $estado = ($row['idEstadoPago']== 1 ) ? 'red' : 'green' ; ?>
				<td><span class="ui <?= $estado ?> large label claseEstado"><?= $row['nombreEstado']; ?></span></td>
	

			</tr>
            <?php $n++;?> 
            <?php endforeach ?>
        </tbody>
	</table>
</div>


<script>
    function getAllTextFromTable(tableID) {
        var table = document.getElementById(tableID);
        var rows = table.getElementsByTagName('tr'); // Esto obtiene todas las filas, incluso las no visibles.
        var data = [];

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var rowData = [];
            for (var j = 0; j < row.cells.length; j++) {
                if (j !== 1) {
                    rowData.push(row.cells[j].textContent.trim());
                }
            }
            data.push(rowData);
        }

        return data;x|
    }

    var allData = getAllTextFromTable('tb-reporteFinanzas');

    // Esta función crea y descarga el archivo Excel
    function exportToExcel(filename = 'tableData.xlsx') {
        let workbook = new ExcelJS.Workbook();
        let worksheet = workbook.addWorksheet('My Sheet');

        // Estilos para el encabezado
        const headerFill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: 'FFF9FAFB'
            } // Un color verde claro, cambia según tus preferencias
        };

        const headerFont = {
            name: 'Calibri',
            color: {
                argb: 'FF000000'
            }, // Texto negro
            bold: true,
            size: 12
        };

        const border = {
            top: {
                style: 'thin'
            },
            left: {
                style: 'thin'
            },
            bottom: {
                style: 'thin'
            },
            right: {
                style: 'thin'
            }
        };

        // Aplica los estilos al encabezado
        const headerRow = worksheet.getRow(1);
        headerRow.values = allData[0]; // Asume que la primera fila de allData contiene los encabezados
        headerRow.eachCell((cell) => {
            cell.fill = headerFill;
            cell.font = headerFont;
            cell.border = border;
            cell.alignment = {
                vertical: 'middle',
                horizontal: 'center'
            };
        });

        // Estilos para las celdas de datos
        const dataFill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: 'FFFFFFFF'
            } // Blanco
        };

        const dataFont = {
            name: 'Calibri',
            color: {
                argb: 'FF000000'
            }, // Texto negro
            size: 11
        };

        // Aplica los estilos a las celdas de datos
        allData.slice(1).forEach((rowData, index) => { // Empieza desde la segunda fila de datos
            const row = worksheet.addRow(rowData);
            row.eachCell((cell) => {
                cell.fill = dataFill;
                cell.font = dataFont;
                cell.border = border;
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'left',
                    wrapText: true
                };
            });
        });

        // Ajustar el ancho de las columnas basado en el contenido
        worksheet.columns.forEach(column => {
            let maxColumnLength = 0;
            column.eachCell({
                includeEmpty: true
            }, cell => {
                let cellLength = cell.value ? cell.value.toString().length : 0;
                maxColumnLength = Math.max(maxColumnLength, cellLength);
            });
            column.width = maxColumnLength < 10 ? 10 : maxColumnLength + 2;
        });

        // Guardar y descargar el archivo Excel
        workbook.xlsx.writeBuffer().then(buffer => {
            let blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(error => console.error(error));
    }
</script>