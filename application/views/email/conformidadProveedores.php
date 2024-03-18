<style>
	.tabla {
		width: 100%;
		--border: 1px solid #000;
	}

	.tabla --th,
	td {
		/* width: 25%; */
		text-align: left;
		/* vertical-align: top; */
		border: 1px solid #000;
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
<meta charset="UTF-8">
<h3 style="margin: 0px;">Estimado</h3>
<br>
<h3 style="margin: 0px;">Se confirma la recepción de factura:</h3>
<br>
<br>

<div style="margin-top: 15px;">
	<fieldset style="margin-top:15px;margin-bottom:15px;">
		<legend>Datos</legend>
		<div>
			<div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
				<table class="tabla" id="listaItemsPresupuesto" style="background:#ffffff;color:#666666" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">MÉTODO PAGO</th>
							<th style="width: 5%;background-color: #2586da;color: white;" class="text-center header">N° DIAS</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">FECHA</th>
							<th style="width: 5%;background-color: #2586da;color: white;" class="text-center header">MES</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">RUC</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">RAZON SOCIAL</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">DESCRICIÓN COMPRAS</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">CENTRO COSTO</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">N° OC</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">PO/OC Cliente</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">TIPO COMPROBANTE</th>
							<th style="width: 10%;background-color: #2586da;color: white;" class="text-center header">SERIE FACTURA</th>
							<th style="width: 15%;background-color: #2586da;color: white;" class="text-center header"></th>
							<th style="width: 20%;background-color: #2586da;color: white;" class="text-center header">MONTO INC. IGV</th>
					</thead>
					<tbody>
						<tr class="default">
							<td style="background-color: #FFFFFF;color: black;font-weight: bold;" class="text-center header"><?= verificarEmpty($data['metodoPago'], 3); ?></td>
							<td style="background-color: #FFFFFF;color: black;font-weight: bold;" class="text-center header"><?= $data['cantDias']; ?></td>
							<td style="background-color: #EEF509;color: red;font-weight: bold;" class="text-center header"><?= verificarEmpty($data['fechaAprobadoFinanza'], 3); ?></td>
							<td style="background-color: #EEF509;color: red;font-weight: bold;" class="text-center header"><?= verificarEmpty($data['fechaAprobadoFinanza'], 3); ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= $data['ruc']; ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= $data['razonSocial']; ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= verificarEmpty($data['descripcionCompras'], 4); ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= verificarEmpty($data['centroCosto'], 3); ?></td>
							<td style="background-color: #EEF509;color: red;font-weight: bold;" class="text-center header"><?= verificarEmpty($data['numeroOC'], 3); ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= verificarEmpty($data['pocliente'], 3); ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= verificarEmpty($data['tipoComprobante'], 3); ?></td>
							<td style="background-color: #FFFFFF;color: black;" class="text-center header"><?= verificarEmpty($data['serieFactura'], 3); ?></td>
							<td style="background-color: #FFFFFF;" class="text-center header"></td>
							<td style="background-color: #FFFFFF;color: black;font-weight: bold;" class="text-center header"><?= verificarEmpty($data['monto'], 3); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="text-align: center;">

			</div>
		</div>
	</fieldset>
</div>