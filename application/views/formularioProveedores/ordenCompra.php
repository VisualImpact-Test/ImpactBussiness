<style>
	.floating-container {
		height: 220px !important;
	}
</style>
<?php $incluirImagen = false; ?>
<?php foreach ($imagen as $key => $value) : ?>
	<?php if (!empty($value)) : ?>
		<?php $incluirImagen = true; ?>
	<?php endif; ?>
<?php endforeach; ?>
<form id="frmOrdenCompraProveedorCabecera">
	<div>
		<div class="row child-divcenter">
			<img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
		</div>
		<div class="mb-3 card child-divcenter w-75">
			<div class="col-md-12 ">
				<div id="accordion">
					<div class="">
						<div class="card-header" id="headingOne">
							<input type="hidden" name="idOrdenCompra" id="idOrdenCompra" value="<?= $idOrdenCompra ?>">
							<h5 class="mb-0">
								<button type="button" class="btn " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<i class="fas fa-solid fa-caret-right"></i> N° DE ORDEN <?= verificarEmpty($cabecera['seriado'], 3); ?>
								</button>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
							<div class="row">
								<div class="col-md-5 child-divcenter">
									<div class="control-group child-divcenter row w-100">
										<label class="form-control form-control-sm col-md-5" style="border:0px;">N° DE RQ :</label>
										<label class="form-control form-control-sm col-md-7" style="border:0px;"><?= verificarEmpty($cabecera['requerimiento'], 3) ?></label>
									</div>
									<div class="control-group child-divcenter row w-100">
									</div>
								</div>
								<div class="col-md-5 child-divcenter">
									<div class="control-group child-divcenter row w-100">
										<label class="form-control form-control-sm col-md-5" style="border:0px;">FECHA :</label>
										<label class="form-control form-control-sm col-md-7" style="border:0px;"><?= verificarEmpty($cabecera['fechaReg'], 3) ?></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5 child-divcenter">
									<div class="control-group child-divcenter row w-100">
										<label class="form-control form-control-sm col-md-5" style="border:0px;">PROVEEDOR :</label>
										<label class="form-control form-control-sm col-md-7" style="border:0px;"><?= verificarEmpty($cabecera['razonSocial'], 3) ?></label>
									</div>
								</div>
								<div class="col-md-5 child-divcenter">
									<div class="control-group child-divcenter row w-100">
										<label class="form-control form-control-sm col-md-5" style="border:0px;">RUC :</label>
										<label class="form-control form-control-sm col-md-7" style="border:0px;"><?= verificarEmpty($cabecera['ruc'], 3) ?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mb-3 card child-divcenter w-75 p-1">
			<div class="sixteen wide field w-100">
				<div class="ui sub header">Fecha de entrega</div>
				<div class="ui calendar date-semantic ">
					<div class="ui input left icon w-100 disabled disabled-visible">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Fecha de entrega" value="<?= !empty($cabecera['fechaEntrega']) ? $cabecera['fechaEntrega'] : '' ?>" patron="requerido">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="fechaEntrega" placeholder="Fecha de entrega" value="<?= !empty($cabecera['fechaEntrega']) ? $cabecera['fechaEntrega'] : '' ?>" patron="requerido">
			</div>
			<?php if (!empty($cabecera['enlaces'])) :  ?>
				<div class="sixteen wide field pt-4">
					<div class="ui sub header">Links</div>
					<div class="ui left corner labeled input w-100">
						<div class="ui left corner label">
							<i class="linkify icon"></i>
						</div>
						<textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"><?= !empty($cabecera['enlaces']) ? $cabecera['enlaces'] : '' ?></textarea>
					</div>
				</div>
			<?php endif; ?>
			<table id="tb-cotizaciones" class="ui celled structured table">
				<thead class="full-width">
					<tr>
						<th class="text-center">Item</th>
						<th class="text-center">Cantidad</th>
						<?php if ($incluirImagen) : ?>
							<th class="text-center">Imagen</th>
						<?php endif; ?>
						<th class="text-center" colspan="4">Descripción</th>
						<th class="text-center">Precio Unit.</th>
						<th class="text-center">Precio Total</th>
					</tr>
				</thead>
				<tbody>
					<?php $indexT = 0; ?>
					<?php foreach ($detalle as $k => $row) : ?>
						<?php $total = $row['subTotalOrdenCompra']; ?>
						<?php $igv_total = ($row['subTotalOrdenCompra'] * (!empty($row['igv']) ? ($row['igv'] / 100) : 0)); ?>

						<?php $mostrarSubDetalle = false; ?>
						<?php $rowS = 1; ?>
						<?php if ($row['idItemTipo'] == COD_TEXTILES['id']) :  ?>
							<?php $mostrarSubDetalle = true; ?>
							<?php $rowS = count($subDetalleItem[$row['idItem']]) + 1; ?>
						<?php endif; ?>
						<?php if ($row['idItemTipo'] == COD_SERVICIO['id']) :  ?>
							<?php $v1 = $subDetalleItem[$row['idItem']][0]['sucursal'] ?>
							<?php $v2 = $subDetalleItem[$row['idItem']][0]['razonSocial'] ?>
							<?php $v3 = $subDetalleItem[$row['idItem']][0]['tipoElemento'] ?>
							<?php $v4 = $subDetalleItem[$row['idItem']][0]['marca'] ?>
							<?php $costoTotal = 0; ?>
							<?php foreach ($subDetalleItem[$row['idItem']] as $ks => $vs) : ?>
								<?php if (!($v1 == $vs['sucursal'] && $v2 == $vs['razonSocial'] && $v3 == $vs['tipoElemento'] && $v4 == $vs['marca'])) :  ?>
									<tr>
										<td class="text-center"><?= ++$indexT ?></td>
										<td class="text-center"><?= '1'; ?></td>
										<td class="text-left" colspan="4">
											<?= $v3 . '_' . $v4 . '_' . $v2 . '_' . $v1 ?>
										</td>
										<td class="text-right"><?= monedaNew(['valor' => $costoTotal, 'simbolo' => $cabecera['simboloMoneda']]); ?></td>
										<td class="text-right"><?= monedaNew(['valor' => $costoTotal, 'simbolo' => $cabecera['simboloMoneda']]); ?></td>
									</tr>
									<?php $v1 = $vs['sucursal']; ?>
									<?php $v2 = $vs['razonSocial']; ?>
									<?php $v3 = $vs['tipoElemento']; ?>
									<?php $v4 = $vs['marca']; ?>
									<?php $costoTotal = 0; ?>
								<?php endif; ?>
								<?php $costoTotal += (floatval($vs['cantidad']) * floatval($vs['costo'])); ?>
							<?php endforeach; ?>
							<tr>
								<td class="text-center"><?= ++$indexT ?></td>
								<td class="text-center"><?= '1'; ?></td>
								<td class="text-left" colspan="4">
									<?= $v3 . '_' . $v4 . '_' . $v2 . '_' . $v1 ?>
								</td>
								<td class="text-right"><?= monedaNew(['valor' => $costoTotal, 'simbolo' => $cabecera['simboloMoneda']]); ?></td>
								<td class="text-right"><?= monedaNew(['valor' => $costoTotal, 'simbolo' => $cabecera['simboloMoneda']]); ?></td>
							</tr>
						<?php else :  ?>
							<tr>
								<td class="text-center" rowspan="<?= $rowS ?>"><?= ++$indexT ?>
									<input type="hidden" name="idCotizacion" value="<?= $row['idCotizacion'] ?>">
								</td>
								<td class="text-center" rowspan="<?= $rowS ?>"><?= verificarEmpty($row['cantidad'], 2) ?></td>
								<?php if ($incluirImagen) : ?>
									<td rowspan="<?= $rowS ?>" class="text-center">
										<?php if (!empty($imagen[$row['idCotizacionDetalle']])) :  ?>
											<?php if (($cabecera['mostrar_imagenes'] == '1' || $cabecera['mostrar_imagenesCoti'] == '1') && count($imagen[$row['idCotizacionDetalle']])) : ?>
												<?php foreach ($imagen[$row['idCotizacionDetalle']] as $kkk => $imagenDeItem) : ?>
													<img class="imgCenter" src="<?= RUTA_WASABI . 'item/' . $imagenDeItem['nombre_archivo'] ?>" style="width: 80px; height: 80px;">
												<?php endforeach; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
								<?php endif; ?>
								<?php if ($mostrarSubDetalle) :  ?>
									<td class="text-center bold">CARACTERISTICA</td>
									<td class="text-center bold">TALLA</td>
									<td class="text-center bold">SEXO</td>
									<td class="text-center bold">CANTIDAD</td>
								<?php else : ?>
									<td class="text-left" colspan="4">
										<?= verificarEmpty($row['nombre'], 3) ?>
										<!-- <?= json_encode($row); ?> -->
									</td>
								<?php endif; ?>

								<td class="text-right" rowspan="<?= $rowS ?>">
									<?= !empty($row['costo']) ? monedaNew(['valor' => $row['costo'], 'simbolo' => $cabecera['simboloMoneda']]) : 0 ?>
								</td>
								<td class="text-right" rowspan="<?= $rowS ?>">
									<?= !empty($row['subtotal']) ? monedaNew(['valor' => $row['subtotal'], 'simbolo' => $cabecera['simboloMoneda']]) : 0 ?>
								</td>
							</tr>
							<?php if ($mostrarSubDetalle) :  ?>
								<?php foreach ($subDetalleItem[$row['idItem']] as $km => $vm) : ?>
									<tr>
										<?php if ($km == 0) :  ?>
											<td class="text-left" rowspan="<?= $rowS - 1 ?>">
												<?= verificarEmpty($row['nombre'] . ' ' . $row['caracteristicasCompras'], 3) ?>
											</td>
										<?php endif; ?>
										<td class="text-center"><?= $vm['talla']; ?></td>
										<td class="text-center"><?= RESULT_GENERO[$vm['genero']]; ?></td>
										<td class="text-center"><?= $vm['cantidad']; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>

						<!-- <?php if (!empty($imagen[$row['idCotizacionDetalle']])) :  ?>
							<tr>
								<td></td>
								<td></td>
								<td colspan="6" class="text-left">
									<?php foreach ($imagen[$row['idCotizacionDetalle']] as $key => $value) : ?>
										<img src="<?= RUTA_WASABI . $value['carpeta'] . $value['nombre_archivo'] ?>" style="padding-top: -120px; width: 200px; height: 120px;">
									<?php endforeach; ?>
								</td>
							</tr>
						<?php endif; ?> -->
					<?php endforeach; ?>
				</tbody>
				<tfoot class="full-width">
					<tr>
						<th colspan="<?= $incluirImagen ? 7 : 6; ?>" class="text-right">
							<p>Sub Total</p>
							<p>IGV</p>
							<p>TOTAL</p>
						</th>
						<th class="text-center">
							<p><?= !empty($cabecera['igv']) ? $cabecera['igv'] : '0' ?>%</p>
						</th>
						<th class="text-right">
							<p><?= monedaNew(['valor' => $total, 'simbolo' => $cabecera['simboloMoneda']]) ?></p>
							<p><?= monedaNew(['valor' => $igv_total, 'simbolo' => $cabecera['simboloMoneda']]) ?></p>
							<p><?= monedaNew(['valor' => $igv_total + $total, 'simbolo' => $cabecera['simboloMoneda']])  ?></p>
						</th>
					</tr>
					<tr>
						<th colspan="<?= $incluirImagen ? 9 : 8; ?>">
							Son: <?= moneyToText(['numero' => ($igv_total + $total), 'moneda' => $cabecera['monedaPlural']]) ?>
						</th>
					</tr>
					<tr style="height: 100px">
						<th colspan="2">
							<strong>Forma de Pago</strong>
						</th>
						<th colspan="2">
							<strong>
								<?= !empty($cabecera['metodoPago']) ? $cabecera['metodoPago'] : '' ?>
							</strong>
						</th>
						<th>
							<strong>
								Observaciones
							</strong>
						</th>
						<th colspan="<?= $incluirImagen ? 4 : 3; ?>">
							<strong>
								<?= !empty($cabecera['observacion']) ? $cabecera['observacion'] : '' ?>
							</strong>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</form>
<!-- FAB -->
<div class="floating-container">
	<div class="floating-button ">
		<i class="cog icon"></i>
	</div>
	<div class="element-container">
		<a href="javascript:;">
			<span class="float-element tooltip-left btn-send" style="background: red;" data-message="Enviar" onclick="FormularioProveedoresOC.descargarOC(<?= $idOrdenCompra ?>);">
				<i class="file pdf icon"></i>
			</span>
		</a>
		<a href="<?= $this->config->base_url() . 'FormularioProveedor/cotizacionesLista' ?>">
			<span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "frmOrdenCompraProveedorCabecera", fn: "FormularioProveedoresOC.confirmarOrdenCompra()", content: "¿Está seguro de confirmar la fecha de entrega para la orden de compra?" });'>
				<i class="tasks icon"></i>
			</span>
		</a>
	</div>
</div>