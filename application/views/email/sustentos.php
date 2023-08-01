<style>
	.tabla {
		width: 100%;
		border: 1px solid #000;
	}

	.tabla th,
	td {
		/* width: 25%; */
		text-align: left;
		/* vertical-align: top; */
		/* border: 1px solid #000; */
		border-collapse: collapse;
		padding: 0.3em;
		caption-side: bottom;
	}

	caption {
		padding: 0.3em;
		color: #fff;
		background: #000;
	}

	.text-center {
		text-align: center;
	}

	.header {
		background-color: #2586da;
		color: white;
	}

	.row_data:hover {
		background-color: rgba(229, 247, 147, 0.46);
	}

	.boton {
		border: none;
		color: white;
		padding: 15px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
	}

	/* Green */
	.verde {
		background-color: #4CAF50;
	}

	/* Blue */
	.azul {
		background-color: #008CBA;
	}

	/* Red */
	.rojo {
		background-color: #f44336;
	}

	/* Gray */
	.gris {
		background-color: #e7e7e7;
		color: black;
	}

	/* Black */
	.neg {
		background-color: #555555;
	}
</style>
<h3 style="margin: 0px;">Estimados, se le informa que se han subido los documentos de sustento</h3>
<br>
<div style="margin-top: 15px;">
	<fieldset style="margin-top:15px;margin-bottom:15px;">
		<legend>Archivos</legend>
		<div>
			<div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
				<table class="tabla" id="listaItemsPresupuesto" style="background:#ffffff;color:#666666" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 5%;background-color: #2586da;color: white;" class="text-center header">#</th>
							<th style="width: 50%;background-color: #2586da;color: white;" class="text-center header">Formato Documento</th>
							<th style="width: 50%;background-color: #2586da;color: white;" class="text-center header">Nombre Archivo</th>
							<th style="width: 15%;background-color: #2586da;color: white;" class="text-center header">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $key => $row) : ?>
							<tr class="default">
								<td><?= $key + 1 ?></td>
								<td>
									<?php switch ($row['idFormatoDocumento']) {
										case '1':
											$tt = 'Guia';
											break;
										case '2':
											$tt =  'Factura';
											break;
										case '3':
											$tt =  'Xml';
											break;
										case '4':
											$tt =  'Adicional';
											break;
										default:
											$tt =  '-';
											break;
									}  ?>
									<?= $tt; ?>
								</td>
								<td><?= verificarEmpty($row['nombre_inicial'], 3) ?></td>
								<?php $direccion = RUTA_WASABI . 'sustento' . $tt . '/' . verificarEmpty($row['nombre_archivo'], 3); ?>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">Descargar</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div style="text-align: center;">

			</div>
		</div>
	</fieldset>
</div>
<!-- <h4 style="margin: 0px;">Se pide a los implicados rellenar los costos de cada item para completar la cotización.</h4> -->