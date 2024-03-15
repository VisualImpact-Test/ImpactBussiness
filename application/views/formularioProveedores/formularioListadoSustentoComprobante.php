<form class="form" role="form" id="formularioListadoDeArtes" method="post">
	<input type="hidden" name="ordencompra" value="<?= $idOrdenCompra ?>">
	<input type="hidden" name="proveedor" value="<?= $idProveedor ?>">
	<input type="hidden" name="flag" value="<?= $flagoclibre ?>">
	<input type="hidden" name="cotizacion" value="<?= $idCotizacion ?>">
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
							<!--< ?php if ($mostrarOpcionesExt) :  ?>
								<th style="width: 15%; background-color: #2586da;color: white;" class="text-center header">Validación</th>
							< ?php endif; ?>-->
						</tr>
					</thead>
					<tbody>
						<?php foreach ($ocGenerado as $k => $row) : ?>
							<tr class="default">
								<td><?= $k + 1 ?></td>
								<td>Orden Compra</td>
								<td class="tdEstado">
									<label class="ui green basic label large">Aprobado</label>
								</td>
								<td>
									<a class="ui button btn-descargarOc" target="_blank" data-id="<?= $row['idOrdenCompra']; ?>">
										<i class="icon eye"></i>
									</a>
								</td>
								<td class="text-center"></td>
							</tr>
						<?php endforeach; ?>
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
								<td><?= $k + 1 + count($ocGenerado) ?></td>
								<td>
									<?= $Carpeta; ?>

								</td>
								<td class="tdEstado">
									<?php if (!empty($row['observacionRechazoFinanza']) && $row['flagAprobadoFinanza'] == '0') : ?>
										<label class="ui red basic label large">Rechazado por Finanzas</label>
									<?php else : ?>
										<?php if ($row['flagRevisado'] == '1') :  ?>
											<?php if ($row['flagAprobado'] == '1') :  ?>
												<label class="ui green basic label large">Aprobado</label>
											<?php else : ?>
												<label class="ui red basic label large">Rechazado</label>
											<?php endif; ?>
										<?php else : ?>
											<label class="ui black basic label large">Pendiente Revisión</label>
										<?php endif; ?>
									<?php endif; ?>
								</td>
								<td>
									<a class="ui button" href="<?= $direccion ?>" target="_blank">
										<i class="icon eye"></i>
									</a>
									<?php if (($row['flagRevisado'] == '1' && $row['flagAprobado'] != '1') || (!empty($row['observacionRechazoFinanza']) && $row['flagAprobadoFinanza'] == '0')) :  ?>
										<a class="ui button formEditSustentoComprobante" data-id="<?= $row['idSustentoAdjunto'] ?>">
											<i class="icon edit"></i>
										</a>
									<?php endif; ?>
								</td>
								<!-- < ?php if ($mostrarOpcionesExt) :  ?>
									<td class="text-center">
										<a class="ui button green btn-estadoSustComprobante" data-id="< ?= $row['idSustentoAdjunto'] ?>" data-estado="1" data-idprov="< ?= $row['idProveedor']; ?>" data-flag="< ?= $row['flagoclibre']; ?>" data-idcot="< ?= $row['idCotizacion']; ?>" data-idformat="< ?= $row['idFormatoDocumento'];?>" data-ordencompra="< ?= $row['idOrdenCompra']; ?>">
											<i class="icon check"></i>
										</a>
										<a class="ui button red btn-estadoSustComprobante" data-id="< ?= $row['idSustentoAdjunto'] ?>" data-idprov="< ?= $row['idProveedor']; ?>" data-flag="< ?= $row['flagoclibre']; ?>" data-idcot="< ?= $row['idCotizacion']; ?>" data-idformat="< ?= $row['idFormatoDocumento']; ?>" data-estado="0" data-ordencompra="< ?= $row['idOrdenCompra']; ?>">
											<i class="icon times"></i>
										</a>
									</td>
								< ?php endif; ?>-->
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