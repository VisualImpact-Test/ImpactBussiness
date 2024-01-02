<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>


<div class="card-datatable">
	<table id="tb-oc" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th>OPCIONES</th>
				<th>NRO OC</th>
				<th>PO CLIENTE</th>
				<th>CONCEPTO</th>
				<th>REQUERIMIENTO</th>
				<th>PROVEEDOR</th>
				<th>LUGAR ENTREGA</th>
				<th>FECHA ENTREGA</th>
				<th>IGVPORCENTAJE</th>
				<th>MONEDA</th>
				<th>TOTAL CON IGV</th>
				<th>ESTADO</th>
			</tr>
		</thead>

		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($datos as $key => $row) : ?>
				<?php
				$mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
				$badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
				$toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
				?>
				<tr data-id="<?= $key ?>" data-idproveedor="<?= $row['idProveedor']; ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center style-icons">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar OC"><i class="fa fa-lg fa-edit"></i></a>
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-descargarOC" title="Imprimir OC"><i class="fa fa-lg fa-file-pdf"></i></a>
					</td>
					<td class="td-center"><?= verificarEmpty($row['seriado'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['poCliente'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['concepto'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['requerimiento'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['proveedor'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['entrega'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['fechaEntrega'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['IGVPorcentaje'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['moneda'], 3); ?></td>
					<td nowrap class="td-center"><?= monedaTipoNumero(['valor' => $row['totalIGV'], 'simbolo' => $row['simboloMoneda'], 'cambio' => $row['monedaCambio']]); ?></td>
					<td class="text-center style-icons">
						<span class="badge <?= $badge ?>" id="spanEstado-<?= $row['estado']; ?>"><?= $mensajeEstado; ?></span>
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

	<!-- <button onclick="exportToExcel('tb-oc', 'ExcelSheet.xlsx')">Exportar a Excel</button> -->

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
				rowData.push(row.cells[j].textContent.trim());
			}
			data.push(rowData);
		}

		return data;
	}

	var allData = getAllTextFromTable('tb-oc');

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

