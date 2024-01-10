<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>
<div class="card-datatable">
	<table id="tb-item" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center text-center">#</th>
				<th class="td-center text-center">OPCIONES</th>
				<th class="text-center">MARCA</th>
				<th class="text-center">CATEGORIA</th>
				<th class="text-center">SUBCATEGORIA</th>
				<th class="text-center">ITEM</th>
				<th class="text-center">CARACTERISTICAS</th>
				<?php foreach ($dataProveedor as $key => $row) : ?>

					
					<th nowrap class="text-center"><?= (strlen($row['nproveedor']) > 16) ? substr($row['nproveedor'], 0, 16) : $row['nproveedor']; ?></th>
				<?php endforeach; ?>
				<th class="td-center text-center">ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?
			foreach ($dataItem as $key => $row) {
				$mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
				$badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
				$toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
			?>
				<tr data-id="<?= $row['idItemTarifario'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td nowrap class="td-center style-icons">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-historialItemTarifario"><i class="fa fa-lg fa-history" title="Historial de Tarifario"></i></a>
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarItemTarifario"><i class="fa fa-lg fa-edit" title="Actualizar Tarifario de Item"></i></a>
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-fotosItemTarifario"><i class="fa fa-lg fa-image" title="Ver fotos de Tarifario de Item"></i></a>
						<a id="hrefEstado-<?= $row['idItemTarifario']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoItem" data-id="<?= $row['idItem']; ?>" data-estado="<?= $row['estado']; ?>">
							<i class="fal fa-lg <?= $toggle ?>"></i>
						</a>
					</td>
					<td class="td-left"><?= verificarEmpty($row['itemMarca'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['itemCategoria'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['itemSubCategoria'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['item']); ?></td>
					<td class="td-left"><?= verificarEmpty($row['caracteristicas'], 3); ?></td>
					<?php $idProveedor = 0; ?>
					<?php foreach ($dataProveedor as $key => $rProveedor) : ?>
						<?php $idProveedor = (!empty($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]) ? $rProveedor['idProveedor'] : $row['idProveedor']) ?>
						<?php $flag = !empty($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['flag_actual']) ? $dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['flag_actual'] : 0; ?>
						<?php if (
							$rProveedor['idProveedor'] == $idProveedor
							&& $dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['estaVigente']
						) : ?>
							<th nowrap class="text-center" style="color: green; <?= $flag ? 'background-color: #FFFC96;' : ''; ?>">
								<?= isset($dataItemProveedor[$row['idItem']][$idProveedor]['costo']) ? $dataItemProveedor[$row['idItem']][$idProveedor]['costo'] : '-'; ?>
								<br>
								<br>
								<?= isset($dataItemProveedor[$row['idItem']][$idProveedor]['fechaVigencia']) ?  date_change_format($dataItemProveedor[$row['idItem']][$idProveedor]['fechaVigencia']) : '-'; ?>
							</th>
						<?php else : ?>
							<th nowrap class="text-center" style="color: red; <?= $flag ? 'background-color: #FFFC96;' : ''; ?>">
								<?= isset($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['costo']) ? $dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['costo'] : '-'; ?>
								<br>
								<br>
								<?= isset($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['fechaVigencia']) ?  date_change_format($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['fechaVigencia']) : '-'; ?>
							</th>
						<?php endif; ?>
					<?php endforeach; ?>
					<td class="text-center style-icons">
						<span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idItemTarifario']; ?>"><?= $mensajeEstado; ?></span>
					</td>
				</tr>
			<? $ix++;
			} ?>
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

		return data;
	}

	var allData = getAllTextFromTable('tb-item');

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