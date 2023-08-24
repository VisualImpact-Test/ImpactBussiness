<form class="form" role="form" id="formularioListadoDeArtes" method="post">
	<input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $idCotizacionDetalleProveedor ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Artes</legend>
				<table class="ui celled table" id="listaItemsPresupuesto" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 5%; background-color: #2586da;color: white;" class="text-center header">#</th>
							<th style="width: 50%; background-color: #2586da;color: white;" class="text-center header">Nombre Archivo</th>
							<th style="width: 50%; background-color: #2586da;color: white;" class="text-center header">Estado</th>
							<th style="width: 15%; background-color: #2586da;color: white;" class="text-center header">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($sustentosCargados as $k => $row) : ?>
							<?php $direccion = RUTA_WASABI . 'sustentoServicio/' . verificarEmpty($row['nombre_archivo'], 3); ?>
							<tr class="default">
								<td><?= $k + 1 ?></td>
								<td><?= verificarEmpty($row['nombre_inicial'], 3) ?></td>
								<td>
									<?php if ($row['flagRevisado'] == '1') :  ?>
										<?php if ($row['flagAprobado'] == '1') :  ?>
											<label class="ui green basic label large">Aprobado</label>
										<?php else : ?>
											<label class="ui red basic label large">Rechazado</label>
										<?php endif; ?>
									<?php else : ?>
										<label class="ui black basic label large">Pendiente Revisión</label>
									<?php endif; ?>
								</td>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
									<?php if ($row['flagRevisado'] == '1' && $row['flagAprobado'] != '1') :  ?>
										<a class="ui button formEditSustentoServ" data-id="<?= $row['idCotizacionDetalleProveedorSustentoCompra'] ?>">
											<i class="icon edit"></i>
										</a>
									<?php endif; ?>
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