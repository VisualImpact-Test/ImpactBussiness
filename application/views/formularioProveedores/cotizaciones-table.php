<style>
	.form-control:disabled,
	.form-control[readonly] {
		background-color: white;
		opacity: 1;
	}
</style>

<div class="card-datatable">
	<!-- ///////////////////////////////////// -->
	<form id="frmCotizacionesProveedor">
		<input type="hidden" name="idProveedor" value="<?= $idProveedor ?>">
		<input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $idCotizacion ?>">
		<p class="font-weight-bold">Datos de la Cotizacion</p>
		<?php if (!empty($datos[0]['fechaValidez'])) : ?>
			<?php $fechaRegistro = getFechaDias($datos[0]['fechaValidez'], (-1 * intval($datos[0]['diasValidez']))); ?>
			<small>Fecha de Registro: <?= $fechaRegistro ?></small>
		<?php endif; ?>
		<hr class="featurette-divider">
		<div class="container mx-0 col-12">
			<?php $validator = $datos[0]['fechaValidez']; ?>
			<?php foreach ($datos as $k => $row) : ?>
				<div class="cotiDet">
					<?php $i = 0; ?>
					<input type="hidden" name="idCotizacionDetalleProveedorDetalle" value="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
					<input type="hidden" class="form-control" name="idItem" readonly value="<?= $row['idItem'] ?>">

					<h4><?= verificarEmpty($row['item'], 3) . empty($row['unidadMedida'] ? '' : (' ( ' . $row['unidadMedida'] . ' )')) ?></h4>
					<div class="row">
						<div class="col-md-10 row justify-content-start">
							<div class="col-md-2">
								<div class="form-label-group">
									<input type="text" class="form-control" autofocus value="<?= verificarEmpty($row['tipoItem'], 3) ?>" readonly>
									<label>TIPO ITEM</label>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-label-group">
									<input type="text" class="form-control" autofocus value="<?= $row['caracteristicasProveedor'] ?>" readonly>
									<label>COMENTARIO DE COMPRAS</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="btn-group" role="group">
									<button class="form-control imgShow btnContraoferta" type="button" name="button" data-id="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" title="Agregar Contraoferta"><i class="handshake outline icon"></i></button>
									<button class="form-control imgShow" type="button" name="button" onclick="$('.imgCotizacion').removeClass('d-none');  $('.imgShow').addClass('d-none')" title="Mostrar Archivos"><i class="folder open outline icon"></i></button>
									<?php if (!empty($cotizacionIMG[$row['idCotizacionDetalle']])) :  ?>
										<div class="floating ui teal label"><?= count($cotizacionIMG[$row['idCotizacionDetalle']]); ?></div>
									<?php endif; ?>
									<button class="form-control imgCotizacion d-none" type="button" name="button" onclick="$('.imgCotizacion').addClass('d-none'); $('.imgShow').removeClass('d-none');" title="Ocultar Archivos"><i class="folder closed outline icon"></i></button>
								</div>
							</div>
							<div class="col-md-12 imgCotizacion d-none">
								<?php if (empty($cotizacionIMG[$row['idCotizacionDetalle']])) : ?>
									<div class="alert alert-info" role="alert">
										<b>No se encontro documentos adjuntos.</b>
									</div>
								<?php else : ?>
									<div class="ui small images">
										<?php foreach ($cotizacionIMG[$row['idCotizacionDetalle']] as $key => $img) : ?>
											<div class="ui fluid image dimmable" data-id="<?= $key ?>">
												<div class="ui dimmer dimmer-file-detalle">
													<div class="content">
														<p class="ui tiny inverted header">322.png</p>
													</div>
												</div>
												<a target="_blank" href="<?= RUTA_WASABI . 'cotizacion/' . $img['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
												<img height="100" src="<?= $img['idTipoArchivo'] == TIPO_OTROS ? (RUTA_WIREFRAME . "file.png") : ($img['idTipoArchivo'] == TIPO_EXCEL ? RUTA_WIREFRAME . "xlsx.png" : ($img['extension'] == 'pdf' ? (RUTA_WIREFRAME . "pdf.png") : (RUTA_WASABI . 'cotizacion/' . $img['nombre_archivo']))) ?>" class="img-responsive img-thumbnail">
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
							<?php if (!empty($row['enlaces'])) :  ?>
								<div class="col-sm-12 row">
									<div class="col-sm-12">
										<div class="form-group">
											<div class="form-group">
												<h4 class="mb-1">LINKS</h4>
												<textarea placeholder="Ingrese los enlaces aquí " rows="6" class="form-control"><?= !empty($row['enlaces']) ? $row['enlaces'] : '' ?></textarea>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<div class="col-sm-12 row">
								<div class="col-sm-2">
									<div class="form-group">
										<div class="form-group">
											<h4 class="mb-1">DIAS DE VALIDEZ</h4>
											<input class="form-control" placeholder="días" name="diasValidez" patron="requerido,numerico" id="dv_input<?= ($k + 1) ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?= !isset($row['diasValidez']) ? '10' : $row['diasValidez']; ?>" <?= (!empty($row['diasValidez'])) ? 'readonly' : ''; ?> <?php if (empty($row['diasValidez'])) :  ?> onkeyup="FormularioProveedores.calcularFecha(<?= ($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');" <?php endif; ?>>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<h4 class="mb-1">FECHA VALIDEZ</h4>
										<input type="date" class="form-control" name="fechaValidez" value="<?= !isset($row['diasValidez']) ? ($this->model->calcularDiasHabiles(['dias' => '10'])['fecha']) : date_change_format_bd($row['fechaValidez'])  ?>" id="fechaValidez<?= ($k + 1) ?>" <?= (!empty($row['fechaValidez'])) ? 'readonly' : ''; ?> <?php if (empty($row['fechaValidez'])) :  ?> onkeyup="FormularioProveedores.calcularDiasValidez(<?= ($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')" onchange="FormularioProveedores.calcularDiasValidez(<?= ($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')" <?php endif; ?>>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="form-group">
											<h4 class="mb-1">DIAS DE ENTREGA</h4>
											<input class="form-control" placeholder="días" name="diasEntrega" patron="requerido,numerico" id="de_input<?= ($k + 1) ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' <?= (!empty($row['diasEntrega'])) ? 'readonly' : ''; ?> value="<?= !isset($row['diasEntrega']) ? '10' : $row['diasEntrega']; ?>" <?php if (empty($row['diasEntrega'])) :  ?> onkeyup="FormularioProveedores.calcularFechaEntrega(<?= ($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');" <?php endif; ?>>
										</div>
									</div>
								</div>
								<div class="col-sm-2 d-none">
									<div class="form-group">
										<h4 class="mb-1">FECHA ENTREGA</h4>
										<input type="date" class="form-control" name="fechaEntrega" value="<?= empty($row['fechaEntrega']) ? ($this->model->calcularDiasHabiles(['dias' => '10'])['fecha']) : $row['fechaEntrega'] ?>" id="fechaEntrega<?= ($k + 1) ?>" <?= (!empty($row['fechaEntrega'])) ? 'readonly' : ''; ?> onkeyup="FormularioProveedores.calcularDiasEntrega(<?= ($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')" onchange="FormularioProveedores.calcularDiasEntrega(<?= ($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<h4 class="mb-1">RESPUESTA</h4>
										<input class="form-control" name="comentario" value="<?= verificarEmpty($row['comentario']); ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<h4 class="mb-1">Cantidad</h4>
								<input class="form-control" name="cantidad" id="cantidad_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" value="<?= $row['cantidad'] ?>" readonly>
							</div>
							<div class="form-group">
								<h4 class="mb-1">Costo Unitario (S/)</h4>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text">S/ </span>
									</div>
									<input class="form-control onlyNumbers d-none" placeholder="costo" id="costo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" name="costoUnitario" value="<?= verificarEmpty($row['costoUnitario'], 4) ?>" onchange="FormularioProveedores.calcularTotal(<?= ($k + 1) ?>,<?= $row['cantidad'] ?>,value, this);" patron="requerido">
									<input class="form-control onlyNumbers" placeholder="costo" id="costoredondo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" value="<?= number_format(verificarEmpty($row['costoUnitario'], 2), 4, '.', '') ?>" onchange="FormularioProveedores.calcularTotal(<?= ($k + 1) ?>,<?= $row['cantidad'] ?>,value, this); $('#costo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>').val(value);">
								</div>
								<small id="msgCosto_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" class="form-text text-muted d-none">
									Costo promedio calculado del detalle
									Valor redondeado
								</small>
							</div>
							<div class="form-group">
								<h4 class="mb-1">Total</h4>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text">S/ </span>
									</div>
									<input class="form-control" name="costo" value="<?= number_format(verificarEmpty($row['cantidad'], 2) * verificarEmpty($row['costoUnitario'], 2), 4, '.', '') ?>" id="valorTotal<?= ($k + 1) ?>" readonly>
								</div>
							</div>
						</div>
					</div>

					<div class="row justify-content-start">
						<div class="col-md-10 row divDetalle">
							<?php if ($row['idItemTipo'] == COD_SERVICIO['id']) :  ?>
								<!-- <div class="col-md-12"> -->
								<!-- <a class="btn btn-lg btn-outline-success" onclick="FormularioProveedores.agregarDetalleServicio(this, <?= $row['idCotizacionDetalleProveedorDetalle'] ?>) "><i class="fa fa-plus"></i> Detalle </a> -->
								<button data-form="FormularioProveedor/getFormCargaMasivaCotizacionProveedorDetalleSub" data-save="FormularioProveedor/guardarCargaMasivaCotizacionProveedorDetalleSub" data-tdata="<?= $row['idCotizacionDetalleProveedorDetalle']; ?>" data-id="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" type="button" class="btn btn-lg btn-outline-success btn-CustomCargaMasiva" id="" title="Carga Masiva Tarifario">
									<i class="fa fa-file"></i> Detalle Multiple
								</button>
								<!-- </div> -->
							<?php endif; ?>
							<div class="col-md-12 pl-0 py-2 row dataDetalle <?= ($row['idItemTipo'] == COD_SERVICIO['id']) ? 'd-none' : ''; ?>">
								<?php foreach ($subdatos[$row['idCotizacionDetalleProveedorDetalle']] as $key => $value) : ?>
									<div class="col-md-12 row filaDetalle">
										<?php $servicio = ($row['idItemTipo'] == COD_SERVICIO['id']); ?>
										<?php $textil = ($row['tipoItem'] == 'Textiles'); ?>
										<div class="row col-md-12">
											<div class="col-sm-2 <?= $servicio ? '' : 'd-none' ?>">
												<div class="form-group">
													<div class="form-group">
														<h4 class="mb-1">SUCURSAL</h4>
														<input class="form-control" placeholder="Sucursal" name="sucursal[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['sucursal'] ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-4 <?= $servicio ? '' : 'd-none' ?>">
												<div class="form-group">
													<div class="form-group">
														<h4 class="mb-1">RAZON SOCIAL</h4>
														<input class="form-control" placeholder="Razón Social" name="razonSocial[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['razonSocial']; ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-3 <?= $servicio ? '' : 'd-none' ?>">
												<div class="form-group">
													<div class="form-group">
														<h4 class="mb-1">TIPO DE ELEMENTO</h4>
														<input class="form-control" placeholder="Tipo de elemento" name="tipoElemento[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['tipoElemento']; ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-3 <?= $servicio ? '' : 'd-none' ?>">
												<div class="form-group">
													<div class="form-group">
														<h4 class="mb-1">MARCA</h4>
														<input class="form-control" placeholder="Marca" name="marca[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['marca']; ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="row col-md-12">
											<div class="col-sm-8 pr-0 <?= $servicio ? '' : 'd-none' ?>">
												<div class="form-group">
													<h4 class="mb-1">Descripción</h4>
													<input class="form-control" type="hidden" name="idCDPD[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
													<input class="form-control" type="hidden" name="idCDPDS[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['idCotizacionDetalleProveedorDetalleSub'] ?>">
													<input class="form-control" name="descripcion[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $value['descripcion'] ?>">
												</div>
											</div>
											<div class="col-sm-2 <?= $textil ? '' : 'd-none' ?>">
												<div class="form-group">
													<h4 class="mb-1">Talla</h4>
													<input class="form-control" value="<?= $value['talla'] ?>" readonly>
												</div>
											</div>
											<div class="col-sm-2 <?= $textil ? '' : 'd-none' ?>">
												<div class="form-group">
													<h4 class="mb-1">Tela</h4>
													<input class="form-control" value="<?= $value['tela'] ?>" readonly>
												</div>
											</div>
											<div class="col-sm-2 <?= $textil ? '' : 'd-none' ?>">
												<div class="form-group">
													<h4 class="mb-1">Color</h4>
													<input class="form-control" value="<?= $value['color'] ?>" readonly>
												</div>
											</div>
											<div class="col-sm-2 <?= $textil ? '' : 'd-none' ?>">
												<div class="form-group">
													<h4 class="mb-1">Genero</h4>
													<input class="form-control" value="<?= !empty($value['genero']) ? RESULT_GENERO[$value['genero']] : '' ?>" readonly>
												</div>
											</div>
											<div class="col-sm-1">
												<div class="form-group">
													<h4 class="mb-1">Cant</h4>
													<input class="form-control cantidad" name="cantidad[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" onkeyup="FormularioProveedores.calcularSubItemTotal(this)" value="<?= $servicio ? $value['cantidad'] : $value['cantidadItem']; ?>" <?= $textil ? 'readonly' : ''; ?>>
												</div>
											</div>
											<div class="col-sm-1">
												<div class="form-group">
													<h4 class="mb-1">P.U.</h4>
													<input class="form-control costo" name="costo[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" onkeyup="FormularioProveedores.calcularSubItemTotal(this)" value="<?= $value['costo'] ?>">
												</div>
											</div>
											<div class="col-sm-2">
												<div class="form-group">
													<h4 class="mb-1">STot</h4>
													<input class="form-control subtotal" name="subtotal[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" readonly value="<?= $value['subTotal'] ?>" data-tiposervicio="<?= $row['tipoItem'] ?>" onchange="FormularioProveedores.calcularSubTotal(<?= $row['idCotizacionDetalleProveedorDetalle'] ?>, this)">
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php if ($row['idItemTipo'] == COD_SERVICIO['id']) :  ?>
								<div class="col-md-12 row py-4">
									<table class="ui table">
										<thead>
											<tr>
												<th>SUCURSAL</th>
												<th>RAZON SOCIAL</th>
												<th>TIPO ELEMENTO</th>
												<th>MARCA</th>
												<th>DESCRIPCION</th>
												<th>CANTIDAD</th>
												<th>PREC UNITARIO</th>
												<th>TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($subdatos[$row['idCotizacionDetalleProveedorDetalle']])) :  ?>
												<?php $var1 = $subdatos[$row['idCotizacionDetalleProveedorDetalle']][0]['sucursal']; ?>
												<?php $var2 = $subdatos[$row['idCotizacionDetalleProveedorDetalle']][0]['razonSocial']; ?>
												<?php $var3 = $subdatos[$row['idCotizacionDetalleProveedorDetalle']][0]['tipoElemento']; ?>
												<?php $var4 = $subdatos[$row['idCotizacionDetalleProveedorDetalle']][0]['marca']; ?>
												<?php $costoTotal = 0; ?>
												<?php foreach ($subdatos[$row['idCotizacionDetalleProveedorDetalle']] as $key => $value) : ?>
													<?php if (!($var1 == $value['sucursal'] && $var2 == $value['razonSocial'] && $var3 == $value['tipoElemento'] && $var4 == $value['marca'])) :  ?>
														<?php $var1 = $value['sucursal']; ?>
														<?php $var2 = $value['razonSocial']; ?>
														<?php $var3 = $value['tipoElemento']; ?>
														<?php $var4 = $value['marca']; ?>
														<tr style="background: #f9fafb;">
															<td colspan="7" class="text-right" style="font-weight: bold;">SUBTOTAL</td>
															<td class="text-right"><?= moneda($costoTotal); ?></td>
														</tr>
														<?php $costoTotal = 0; ?>
													<?php endif; ?>
													<?php $costoTotal += (floatval($value['cantidad']) * floatval($value['costo'])) ?>
													<tr>
														<td><?= $value['sucursal']; ?></td>
														<td><?= $value['razonSocial']; ?></td>
														<td><?= $value['tipoElemento']; ?></td>
														<td><?= $value['marca']; ?></td>
														<td><?= $value['descripcion']; ?></td>
														<td><?= $value['cantidad']; ?></td>
														<td class="text-right"><?= moneda($value['costo']); ?></td>
														<td class="text-right"><?= moneda(floatval($value['cantidad']) * floatval($value['costo'])); ?></td>
													</tr>
												<?php endforeach; ?>
												<tr style="background: #f9fafb;">
													<td colspan="7" class="text-right" style="font-weight: bold;">SUBTOTAL</td>
													<td class="text-right"><?= moneda($costoTotal); ?></td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
						<div class="col-md-2"></div>
					</div>

					<div class="col-md-12">
						<div class="form-group nuevo">
							<a href="javascript:;" class="btn btn-lg btn-outline-secondary col-md-2" title="Agregar Captura" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();">
								Agregar Archivos <i class="fa fa-lg fa-camera-retro"></i>
							</a>
							<div class="content-lsck-capturas pt-2">
								<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" accept="image/*,.pdf,.xlsx" multiple="">
								<div class="fields ">
									<div class="container sixteen wide field">
										<div class="row content-lsck-galeria content-lsck-capturas">
											<?php if (!empty($archivos)) : ?>
												<?php foreach ($archivos as $k => $archivo) : ?>
													<?php if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']) : ?>
														<div class="col-md-2 text-center">
															<div class="ui dimmer dimmer-file-detalle">
																<div class="content">
																	<p class="ui tiny inverted header"> <?= $archivo['nombre_inicial'] ?> </p>
																</div>
															</div>
															<a class="ui red right corner label img-lsck-capturas-delete">
																<i class="trash icon"></i>
															</a>
															<a target="_blank" href="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label">
																<i class="eye icon"></i>
															</a>
															<input type="hidden" name="file-item[0]" value="">
															<input type="hidden" name="file-type[0]" value="image/<?= $archivo['extension'] ?>">
															<input type="hidden" name="file-name[0]" value="<?= $archivo['nombre_inicial'] ?>">
															<?php if ($archivo['extension'] == 'pdf') {
																$ruta = RUTA_WIREFRAME . "pdf.png";
															} else if ($archivo['extension'] == 'xlsx') {
																$ruta = RUTA_WIREFRAME . "xlsx.png";
															} else {
																$ruta = RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}";
															} ?>
															<img src="<?= $ruta ?>" class="rounded img-lsck-capturas img-responsive img-thumbnail">
														</div>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="fields ">
									<div class="sixteen wide field">
										<div class="ui images content-lsck-files">
											<!-- <?php if (!empty($archivos)) :  ?>
												<?php foreach ($archivos as $archivo) : ?>
													<?php if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']) :  ?>
														<?php if ($archivo['idTipoArchivo'] == TIPO_PDF) :  ?>
															<div class="content-lsck-capturas">
																<div class="ui dimmer dimmer-file-detalle">
																	<div class="content">
																		<p class="ui tiny inverted header"> <?= $archivo['nombre_inicial'] ?> </p>
																	</div>
																</div>
																<a class="ui red right corner label img-lsck-capturas-delete">
																	<i class="trash icon"></i>
																</a>
																<a target="_blank" href="
        														<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label">
																	<i class="eye icon"></i>
																</a>
																<input type="hidden" name="file-item[
        														<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="">
																<input type="hidden" name="file-type[
        															<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="application/
        															<?= $archivo['extension'] ?>">
																<input type="hidden" name="file-name[
        																<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="
        																<?= $archivo['nombre_inicial'] ?>">
																<img height="100" src="
        																	<?= RUTA_WIREFRAME . "pdf.png" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
															</div>
														<?php endif; ?>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr class="featurette-divider">
				</div>

			<?php endforeach; ?>
		</div>

		<!-- Validar Btn de Guardado -->
		<?php $mostrarBtnGuardar = true; ?>
		<?php foreach ($datos as $kp => $vp) : ?>
			<?php if ($vp['flag_activo'] == '0') :  ?>
				<?php $mostrarBtnGuardar = true; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="container">
			<?php if ($mostrarBtnGuardar) :  ?>
				<div class="ui right floated small primary labeled icon button btnGuardarCotizacion">
					<i class="save icon"></i> <span class="">Guardar</span>
				</div>
			<?php endif; ?>
			<div class="ui small button btnRefreshCotizaciones btn-Consultar">
				<i class="sync icon"></i>
				Refresh
			</div>
			<div class="ui small red button btnVolverProveedor">
				<i class="fas fa-solid fa-caret-left icon"></i>
				<span class="">Volver</span>
			</div>
		</div>
		<hr class="featurette-divider">
		<div class="ui bottom attached warning message">
			<i class="icon warning"></i>Los costos indicados NO incluyen el IGV.
		</div>

	</form>
</div>