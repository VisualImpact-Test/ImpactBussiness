<form class="form" role="form" id="formularioListadoDeArtes" method="post">
	<input type="hidden" name="proveedor" value="<?= $proveedor ?>">
	<input type="hidden" name="cotizacion" value="<?= $cotizacion ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Fechas</legend>
				<table class="ui celled table" id="listaItemsPresupuesto" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 5%; background-color: #2586da;color: white;" class="text-center header">#</th>
							<th style="width: 35%; background-color: #2586da;color: white;" class="text-center header">Nombre Archivo</th>
							<?php if ($mostrarOpcionesExt) :  ?>
								<th style="width: 15%; background-color: #2586da;color: white;" class="text-center header">Validación</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($artes as $k => $row) : ?>
								<?php $direccion = RUTA_WASABI . 'fechaEjecucion/' . verificarEmpty($row['nombre_archivo'], 3); ?>		
							<tr class="default"  align ="center">
								<td><?= $k + 1 ?></td>
								<td><?= verificarEmpty($row['nombre_inicial'], 3) ?></td>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
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