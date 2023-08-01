<form class="form" role="form" id="formRegistroValidacionArte" method="post">
	<input type="hidden" name="proveedor" value="<?= $proveedor ?>">
	<input type="hidden" name="cotizacion" value="<?= $cotizacion ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Artes</legend>
				<table class="ui celled table" id="listaItemsPresupuesto" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 5%;background-color: #2586da;color: white;" class="text-center header">#</th>
							<th style="width: 50%;background-color: #2586da;color: white;" class="text-center header">Nombre Archivo</th>
							<th style="width: 15%;background-color: #2586da;color: white;" class="text-center header">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($artes as $k => $row) : ?>
							<tr class="default">
								<td><?= $k + 1 ?></td>
								<td><?= verificarEmpty($row['nombre_inicial'], 3) ?></td>
								<?php if ($row['flagAdjunto'] == '1') {
									$direccion = RUTA_WASABI . 'validacionArte/' . verificarEmpty($row['nombre_archivo'], 3);
								} else {
									$direccion = verificarEmpty($row['nombre_archivo'], 3);
								} ?>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">Descargar</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</fieldset>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>