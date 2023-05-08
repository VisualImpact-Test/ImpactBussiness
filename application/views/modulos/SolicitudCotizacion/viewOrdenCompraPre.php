<form class="ui form" role="form" id="formRegistroOperValidado" method="post">
	<div class="ui form attached fluid segment p-4">
		<h4 class="ui dividing header text-uppercase">DATOS OPER </h4>
		<input type="hidden" name="idOper" value="<?= $dataOper['idOper'] ?>">
		<div class="fields">
			<div class="ten wide field">
				<div class="ui sub header">Requerimiento</div>
				<input type="text" value="<?= $dataOper['requerimiento'] ?>" readonly patron="requerido">
			</div>
			<div class="six wide field">
				<div class="ui sub header">Concepto OC</div>
				<input type="text" value="<?= $dataOper['concepto'] ?>" readonly patron="requerido">
			</div>
		</div>
		<? foreach ($data['idCotizacion'] as $idCotizacion) { ?>
			<input type="hidden" name="idCotizacion" value="<?= $idCotizacion ?>">
		<? } ?>
	</div>
	<div id="accordion">
		<? foreach ($dataOrden as $k => $orden) { ?>
			<div class="ui form attached fluid segment p-4">
				<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#collapseOne<?= $k ?>" aria-expanded="true" aria-controls="collapseOne<?= $k ?>">
					<h4 class="ui dividing header text-uppercase">ORDEN COMPRA <?= $orden['proveedor'] ?></h4>
				</button>
				<input type="hidden" name="idProveedor" value="<?= $orden['idProveedorForm'] ?>">
				<div id="collapseOne<?= $k ?>" class="collapse show" aria-labelledby="headingOne<?= $k ?>" data-parent="#accordion">
					<div class="fields">
						<div class="ten wide field">
							<div class="ui sub header">Proveedor</div>
							<input type="text" value="<?= $orden['proveedor'] ?>" readonly patron="requerido">
						</div>
						<div class="six wide field">
							<div class="ui sub header">Ruc</div>
							<input type="text" value="<?= $orden['rucProveedor'] ?>" readonly patron="requerido">
						</div>
					</div>
					<div class="fields">
						<div class="eight wide field">
							<div class="ui sub header">Método de pago</div>
							<select name="metodoPago" class="ui fluid search clearable dropdown simpleDropdown semantic-dropdown" patron="requerido">
								<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $orden['proveedorMetodoPago'], 'class' => 'text-titlecase', 'id' => 'idMetodoPago', 'value' => 'metodoPago']); ?>
							</select>
						</div>
						<div class="six wide field">
							<div class="ui sub header">Moneda</div>
							<div class="ui fluid search selection dropdown simpleDropdown semantic-dropdown ">
								<!-- Actualmente solo funciona con SOLES -->
								<input type="hidden" name="idMoneda" value="1" patron="requerido"> <!-- Value="1" es el valor por defecto que tomara -->
								<!-- Quitar clase read-only para poder modificar -->
								<!-- Revisar https://fomantic-ui.com/modules/dropdown.html -->
								<i class="dropdown icon"></i>
								<div class="default text">Moneda</div>
								<div class="menu">
									<? foreach ($monedas as $moneda) { ?>
										<div class="item" data-value="<?= $moneda['idMoneda'] ?>">
											<i class="<?= $moneda['icono'] ?>"></i><?= $moneda['moneda'] ?>
										</div>
									<? } ?>
								</div>
							</div>
						</div>
						<div class="two wide field">
							<div class="ui sub header">IGV 18%</div>
							<div class="custom-control custom-switch custom-switch-lg">
								<input type="checkbox" class="custom-control-input" id="igvOrden" name="igvOrden">
								<label class="custom-control-label" for="igvOrden"></label>
							</div>
							<!-- <input name="igvOrden" class="onlyNumbers" data-max='100' data-min='0' type="text" placeholder="Escriba aquí" value="<?= (IGV * 100) ?>" patron="requerido"> -->
						</div>

					</div>

					<div class="fields">
						<div class="four wide field">
							<div class="ui sub header">Fecha de entrega</div>
							<div class="ui calendar date-semantic">
								<div class="ui input left icon">
									<i class="calendar icon"></i>
									<input type="text" placeholder="Fecha Requerimiento" value="<?= date('Y-m-d') ?>" patron="requerido">
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaEntrega" placeholder="Fecha de entrega" value="<?= date('Y-m-d') ?>" patron="requerido">
						</div>
						<div class="three wide field">
							<div class="ui sub header">OC Cliente</div>
							<input type="text" name="pocliente" placeholder="Escriba aquí" value="<?= $orden['ocDelCliente'] ?>">
						</div>
						<div class="three wide field">
							<div class="ui sub header">Lugar de entrega</div>
							<select class="ui dropdown simpleDropdown almacen" name="idAlmacen" onchange="SolicitudCotizacion.mostrarLugarEntrega(this)">
								<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $almacenes, 'id' => 'idAlmacen', 'value' => 'nombre', 'class' => 'text-titlecase', 'data-option' => ['direccion']]); ?>
							</select>
						</div>
						<div class="six wide field">
							<div class="ui sub header">.</div>
							<input class="lugarEntrega" type="text" name="lugarEntrega" placeholder="Escriba aquí" value="">
						</div>
					</div>
					<div class="fields">
						<div class="six wide field">
							<div class="ui sub header">Observación</div>
							<input type="text" name="observacion" placeholder="Escriba aquí" value="En caso de incumplimiento en fecha de entrega, se estará ejecutando penalidad del 1% por cada día de retraso.">
						</div>
						<div class="two wide field">
							<div class="ui sub header">Mostrar Observación</div>
							<div class="custom-control custom-switch custom-switch-lg">
								<input type="checkbox" class="custom-control-input" id="mostrar_observacion" name="mostrar_observacion">
								<label class="custom-control-label" for="mostrar_observacion"></label>
							</div>
							<!-- <input name="igvOrden" class="onlyNumbers" data-max='100' data-min='0' type="text" placeholder="Escriba aquí" value="<?= (IGV * 100) ?>" patron="requerido"> -->
						</div>
						<div class="two wide field">
							<div class="ui sub header">Mostrar Imagenes</div>
							<div class="custom-control custom-switch custom-switch-lg">
								<input type="checkbox" class="custom-control-input" id="mostrar_imagenes" name="mostrar_imagenes">
								<label class="custom-control-label" for="mostrar_imagenes"></label>
							</div>
							<!-- <input name="igvOrden" class="onlyNumbers" data-max='100' data-min='0' type="text" placeholder="Escriba aquí" value="<?= (IGV * 100) ?>" patron="requerido"> -->
						</div>
						<div class="two wide field">
							<div class="ui sub header">Mostrar Imagenes de Cotización</div>
							<div class="custom-control custom-switch custom-switch-lg">
								<input type="checkbox" class="custom-control-input" id="mostrar_imagenesCoti" name="mostrar_imagenesCoti">
								<label class="custom-control-label" for="mostrar_imagenesCoti"></label>
							</div>
						</div>
					</div>
					<div class="fields">
						<div class="sixteen wide field">
							<div class="ui sub header">Comentario</div>
							<input type="text" name="comentario" placeholder="Escriba aquí" value="">
						</div>
					</div>
					<h5 class="ui dividing header">
						DETALLE ITEMS
					</h5>
					<? foreach ($dataOrdenDet[$orden['idProveedorForm']] as $rowDetalle) {
					?>

						<div class="ui grid">
							<input type="hidden" name="idCotizacionDetalle[<?= $k ?>]" value="<?= $rowDetalle['idCotizacionDetalle'] ?>">
							<div class="sixteen wide column">
								<div class="fields">
									<div class="eight wide field">
										<div class="ui sub header">Item</div>
										<input class="items" type='text' name='nameItem[<?= $k ?>]' value="<?= $rowDetalle['nameItem'] ?>" patron="requerido" placeholder="" readonly>
									</div>
									<div class="eight wide field">
										<div class="ui sub header">Caracteristica para Compras</div>
										<input class="cc" type='text' name='caracteristicasItem[<?= $k ?>]' value="<?= $rowDetalle['caracteristicasItem'] ?>" placeholder="">
									</div>
								</div>
								<!-- Textiles -->
								<div class="disabled disabled-visible ui form attached fluid segment my-3 <?= $rowDetalle['tipoItemForm'] == COD_TEXTILES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TEXTILES['id'] ?>">
									<h4 class="ui dividing header">SUB ITEMS</h4>
									<div class="content-body-sub-item">
										<?
										if (!empty($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_TEXTILES['id']])) :
											foreach ($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_TEXTILES['id']] as $dataSubItem) : ?>
												<input type="hidden" name="idCotizacionDetalleSub[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="fields body-sub-item ">
													<div class="one wide field">
														<div class="ui sub header">Talla</div>
														<input class="tallaSubItem camposTextil" name="tallaSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Talla" value="<?= !empty($dataSubItem['talla']) ? $dataSubItem['talla'] : '' ?>">
													</div>
													<div class="four wide field">
														<div class="ui sub header">Tela</div>
														<input class="telaSubItem camposTextil" name="telaSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Tela" value="<?= !empty($dataSubItem['tela']) ? $dataSubItem['tela'] : '' ?>">
													</div>
													<div class="four wide field">
														<div class="ui sub header">Color</div>
														<input class="colorSubItem" name="colorSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Color" value="<?= !empty($dataSubItem['color']) ? $dataSubItem['color'] : '' ?>">
													</div>
													<div class="four wide field">
														<div class="ui sub header">Cantidad</div>
														<input class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
													</div>
													<div class="three wide field">
														<div class="ui sub header">Genero</div>
														<input class="" name="generoTextil[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Genero" value="<?= !empty($dataSubItem['genero']) ? $dataSubItem['genero'] : '' ?>">
													</div>
												</div>
										<?
											endforeach;
										endif;
										?>
									</div>

								</div>

								<!-- Monto S/ -->
								<div class="disabled disabled-visible fields <?= $rowDetalle['tipoItemForm'] == COD_TARJETAS_VALES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
									<?
									if (!empty($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_TARJETAS_VALES['id']])) :
										foreach ($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_TARJETAS_VALES['id']] as $dataSubItem) : ?>
											<input type="hidden" name="idCotizacionDetalleSub[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
											<div class="sixteen wide field">
												<div class="ui sub header">Monto S/</div>
												<input class="montoSubItem" name="montoSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Monto" value="<?= !empty($dataSubItem['monto']) ? $dataSubItem['monto'] : '' ?>">
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>

								<!-- Servicios -->
								<div class="disabled disabled-visible ui form attached fluid segment my-3 <?= $rowDetalle['tipoItemForm'] == COD_SERVICIO['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
									<h4 class="ui dividing header">SUB ITEMS</h4>
									<div class="content-body-sub-item">
										<?
										if (!empty($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_SERVICIO['id']])) :
											foreach ($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_SERVICIO['id']] as $dataSubItem) : ?>
												<input type="hidden" name="idCotizacionDetalleSub[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="fields body-sub-item body-sub-item-servicio">
													<div class="three wide field">
														<div class="ui sub header">Sucursal </div>
														<input class="sucursalSubItem" name="sucursalSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['sucursal']) ? $dataSubItem['sucursal'] : '' ?>">
													</div>
													<div class="three wide field">
														<div class="ui sub header">Razón Social </div>
														<input class="razonSocialSubItem" name="razonSocialSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['razonSocial']) ? $dataSubItem['razonSocial'] : '' ?>">
													</div>
													<div class="two wide field">
														<div class="ui sub header">Tipo Elemento </div>
														<input class="tipoElementoSubItem" name="tipoElementoSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['tipoElemento']) ? $dataSubItem['tipoElemento'] : '' ?>">
													</div>
													<div class="two wide field">
														<div class="ui sub header">Marca </div>
														<input class="marcaSubItem" name="marcaSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['marca']) ? $dataSubItem['marca'] : '' ?>">
													</div>
													<div class="three wide field">
														<div class="ui sub header">Descripción </div>
														<input class="nombreSubItem" name="nombreSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>">
													</div>
													<div class="one wide field">
														<div class="ui sub header">Cantidad</div>
														<input class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
													</div>
													<div class="two wide field">
														<div class="ui sub header">Precio Unitario </div>
														<input class="precioUnitarioSubItem" name="precioUnitarioSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>">
													</div>
													<!-- <div class="eleven wide field">
														<div class="ui sub header">Sub item </div>
														<input class="nombreSubItem" name="nombreSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Nombre" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>">
													</div>
													<div class="five wide field">
														<div class="ui sub header">Cantidad</div>
														<input class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
													</div> -->
												</div>
										<?
											endforeach;
										endif;
										?>
									</div>
									<!-- <button type="button" class="ui basic button btn-add-sub-item">
                                    <i class="plus icon"></i>
                                    Agregar
                                </button> -->
								</div>

								<!-- Distribucion -->
								<div class="disabled disabled-visible fields <?= $rowDetalle['tipoItemForm'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>">
									<?
									if (!empty($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_DISTRIBUCION['id']])) :
										foreach ($subDetalleOrden[$rowDetalle['idCotizacionDetalle']][COD_DISTRIBUCION['id']] as $dataSubItem) : ?>
											<input type="hidden" name="idCotizacionDetalleSub[<?= $rowDetalle['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
											<div class="four wide field">
												<div class="ui sub header">Tipo Servicio</div>
												<select class="ui search dropdown simpleDropdown tipoServicioForm tipoServicioSubItem" name="tipoServicioSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]">
													<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'selected' => $dataSubItem['idTipoServicio'], 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
												</select>
											</div>
											<div class="three wide field">
												<div class="ui sub header">Unidad de medida</div>
												<input class="unidadMedidaTipoServicio" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['unidadMedida']) ? $dataSubItem['unidadMedida'] : '' ?>" readonly>
												<input type="hidden" class="unidadMedidaSubItem" name="unidadMedidaSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['idUnidadMedida']) ? $dataSubItem['idUnidadMedida'] : '' ?>" readonly>
											</div>
											<div class="three wide field">
												<div class="ui sub header">Costo S/</div>
												<input class="costoTipoServicio costoSubItem" name="costoSubItem[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Costo" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>" readonly>
											</div>
											<div class="three wide field">
												<div class="ui sub header">Pesos / Cantidad</div>
												<input class="onlyNumbers cantidadSubItemDistribucion cantidadSubItem" name="cantidadSubItemDistribucion[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
											</div>
											<div class="three wide field">
												<div class="ui sub header">Cantidad PDV</div>
												<input class="onlyNumbers cantidadPDVSubItemDistribucion cantidadPDVSubItem" name="cantidadPDVSubItemDistribucion[<?= $rowDetalle['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidadPdv']) ? $dataSubItem['cantidadPdv'] : '' ?>">
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>

								<div class="fields">
									<div class="five wide field">
										<div class="ui sub header">Cantidad</div>
										<input class="form-control " type="number" name="cantidadForm[<?= $k ?>]" placeholder="0" value="<?= $rowDetalle['cantidadForm'] ?>" patron="requerido,numerico" min="1" step="1" readonly onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
									</div>
									<div class="five wide field">
										<div class="ui sub header">Costo</div>
										<div class="ui right labeled input">
											<label for="amount" class="ui label">S/</label>
											<input class="costoForm" type="text" name="costoForm[<?= $k ?>]" placeholder="0.00" value="<?= number_format($rowDetalle['costoForm'], 2, '.', '') ?>" readonly patron="requerido">
										</div>
									</div>
									<div class="six wide field">
										<div class="ui sub header">Subtotal</div>
										<div class="ui right labeled input">
											<label for="amount" class="ui label teal">S/</label>
											<!-- <input class=" subtotalFormLabel" type="text" placeholder="0.00" value="<?= moneda($rowDetalle['subtotalForm']) ?>" readonly>
                                            <input class=" subtotalForm" type="hidden" name="subtotalForm" value="<?= $rowDetalle['subtotalForm'] ?>" placeholder="0.00" readonly patron="requerido"> -->
											<input class=" subtotalFormLabel" type="text" placeholder="0.00" value="<?= moneda(floatval($rowDetalle['cantidadForm']) * floatval($rowDetalle['costoForm'])) ?>" readonly>
											<input class=" subtotalForm" type="hidden" name="subtotalForm" value="<?= floatval($rowDetalle['cantidadForm']) * floatval($rowDetalle['costoForm']) ?>" placeholder="0.00" readonly patron="requerido">
										</div>
									</div>
								</div>
							</div>
						</div>
					<? } ?>
				</div>

			</div>
		<? } ?>
	</div>
</form>
<script>
	$(document).ready(() => {
		$('.simpleDropdown').dropdown();
	})
</script>