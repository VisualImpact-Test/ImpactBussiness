<form class="form" role="form" id="formSustentosCargados" method="post">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Consultar Comprobante</legend>
				<table class="ui celled table" id="listaItemsPresupuesto" width="100%" class="tabla">
					<thead class="thead-light">
						<tr class="row_data">
							<th style="width: 5%; background-color: #2586da;color: white;" class="text-center header">#</th>
							<th style="width: 50%; background-color: #2586da;color: white;" class="text-center header">Nombre Archivo</th>
							<th style="width: 50%; background-color: #2586da;color: white;" class="text-center header">Estado</th>
							<th style="width: 15%; background-color: #2586da;color: white;" class="text-center header">Opciones</th>
							<th style="width: 15%; background-color: #2586da;color: white;" class="text-center header">Validación</th>
						</tr>
					</thead>
					<tbody>
						<tr class="default">
							<td>1</td>
							<td>Orden Compra</td>
							<td class="tdEstado">
								<label class="ui green basic label large">Aprobado</label>
							</td>
							<td>
								<?php if ($flagOcLibre == '1') : ?>
									<a href="<?= base_url() . 'OrdenCompra/visualizarPdfOCLibre/' . $idOrdenCompra; ?>?flag=<?= $flagOcLibre ?>&d=true" class="ui button" data-id="<?= $idOrdenCompra ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
								<?php else : ?>
									<a href="<?= base_url() . 'Cotizacion/descargarOCDirectoProvServ/' . $idOrdenCompra; ?>?flag=<?= $flagOcLibre ?>" class="ui button" data-id="<?= $idOrdenCompra ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
								<?php endif; ?>
							</td>
							<td class="text-center"></td>
						</tr>
						<?php foreach ($sustentosCargados as $k => $row) : ?>
							<?php
							$Carpeta = '-';
							switch ($row['idFormatoDocumento']) {
								case '1':
									$Carpeta = 'Guia';
									break;
								case '2':
									$Carpeta = 'Factura';
									break;
								case '3':
									$Carpeta = 'Xml';
									break;
								case '4':
									$Carpeta = 'Adicional';
									break;
								default:
									$Carpeta = '-';
									break;
							}
							?>
							<?php $direccion = RUTA_WASABI . 'sustento' . $Carpeta . '/' . verificarEmpty($row['nombre_archivo'], 3); ?>
							<tr class="default">
								<td><?= $k + 2 ?></td>
								<td>
									<?= $Carpeta; ?>
								</td>
								<td class="tdEstado">
									<?php if ($row['flagAprobado']) : ?>
										<?php if ($row['flagAprobadoFinanza'] == '1') : ?>
											<label class="ui green basic label large">Aprobado</label>
										<?php else : ?>
											<?php if ($row['observacionRechazoFinanza']) : ?>
												<label class="ui red basic label large">Rechazado por Finanzas</label>
												<!-- <label><?= $row['observacionRechazoFinanza'] ?></label> -->
											<?php else : ?>
												<label class="ui black basic label large">Pendiente</label>
											<?php endif; ?>
										<?php endif; ?>
									<?php else : ?>
										<label class="ui red basic label large">Rechazado por Compras</label>
									<?php endif; ?>
								</td>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
								</td>
								<td class="text-center">
									<a class="ui button btn-estadoSustComprobante green" data-id="<?= $row['idSustentoAdjunto'] ?>" data-idprov="<?= $row['idProveedor']; ?>" data-flag="<?= $row['flagoclibre']; ?>" data-idcot="<?= $row['idCotizacion']; ?>" data-idformat="<?= $row['idFormatoDocumento']; ?>" data-estado="1" data-idordencompra = "<?= $idOrdenCompra; ?>" data-monto = "<?= $monto; ?>">
										<i class="icon check"></i>
									</a>
									<a class="ui button btn-estadoSustComprobante red" data-id="<?= $row['idSustentoAdjunto'] ?>" data-idprov="<?= $row['idProveedor']; ?>" data-flag="<?= $row['flagoclibre']; ?>" data-idcot="<?= $row['idCotizacion']; ?>" data-idformat="<?= $row['idFormatoDocumento']; ?>" data-estado="0" data-idordencompra = "<?= $idOrdenCompra; ?>" data-monto = "<?= $monto; ?>">
										<i class="icon times"></i>
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