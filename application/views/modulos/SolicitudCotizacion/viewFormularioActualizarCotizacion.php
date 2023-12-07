<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

	.floating-container {
		height: 200px !important;
	}

	.stickyProveedores.fixed {
		margin-top: 8px !important;
		margin-left: 0px !important;
	}

	.stickyDetalleCotizacion.fixed {
		margin-top: 55px !important;
	}

	.ui.vertical.menu {
		width: 20rem;
	}
</style>
<div class="ui form attached fluid segment p-4 <?= !empty($disabled) ? 'disabled disabled-visible' : '' ?>">
	<form class="ui form" role="form" id="formRegistroCotizacion" method="post" autocomplete="off">
		<input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
		<h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
		<div class="fields disabled disabled-visible ">
			<div class="six wide field">
				<div class="ui sub header">Título</div>
				<input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
			</div>
			<div class="five wide field">
				<div class="ui sub header">Usuario Reg.</div>
				<input value="<?= verificarEmpty($cotizacion['usuario']) ?>" readonly>
			</div>
			<div class="five wide field">
				<div class="ui sub header">Deadline compras</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
			</div>
		</div>
		<div class="fields disabled disabled-visible">
			<div class="four wide field">
				<div class="ui sub header">Fecha requerida</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Fecha Requerida" value="<?= !empty($cotizacion['fechaRequerida']) ? $cotizacion['fechaRequerida'] : '' ?>">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="<?= !empty($cotizacion['fechaRequerida']) ? $cotizacion['fechaRequerida'] : '' ?>">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Solicitante</div>
				<select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idSolicitante']) ? $cotizacion['idSolicitante'] : '']); ?>
				</select>
			</div>
			<div class="four wide field">
				<div class="ui sub header">Cuenta</div>
				<select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
				</select>
			</div>
			<div class="four wide field">
				<div class="ui sub header">Centro de costo</div>
				<select class="ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuentaCentroCosto']) ? $cotizacion['idCuentaCentroCosto'] : '']); ?>
				</select>
			</div>
		</div>
		<div class="fields disabled disabled-visible">
			<div class="five wide field">
				<div class="ui sub header">Prioridad</div>
				<select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idPrioridad']) ? $cotizacion['idPrioridad'] : '']); ?>
				</select>
			</div>
			<div class="eleven wide field">
				<div class="ui sub header">Motivo</div>
				<input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
			</div>

		</div>
		<div class="fields disabled disabled-visible">
			<div class="sixteen wide field">
				<div class="ui sub header">Comentario</div>
				<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?></textarea>
				<!-- <input id="comentarioForm" name="comentarioForm" placeholder="Comentario" value="<?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?>"> -->
			</div>
		</div>
		<h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
		</h4>
		<div class="default-item">
			<div class="fields ui sticky">
				<div class="thirteen wide field">
					<div class="ui sub header">Proveedor</div>
					<select class="ui fluid search <?= $col_dropdown ?> dropdown simpleDropdown proveedorSolicitudForm" multiple="" name="proveedorSolicitudForm">
						<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedores, 'class' => 'text-titlecase', 'id' => 'idProveedor', 'value' => 'razonSocial']); ?>
					</select>
				</div>
				<div class="three wide field">
					<div class="ui sub header">&nbsp;</div>
					<button type='button' class="ui labeled icon green button w-100 btnSolicitarCotizacion">
						<i class="hand holding usd icon"></i>
						Solicitar cotización
					</button>

				</div>
			</div>
		</div>
		<div class="default-item">
			<? foreach ($cotizacionDetalle as $kd => $row) : ?>
				<div class="ui segment body-item nuevo" data-id="<?= $row['idCotizacionDetalle'] ?>">
					<input class="idCotizacionDetalleForm-<?= $row['idCotizacionDetalle'] ?>" type="hidden" name="idCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>" id="">
					<div class="ui right floated header sticky stickyDetalleCotizacion">
						<div class="ui icon menu">
							<?php if (!empty($cotizacionProveedorRegistrados[$row['idCotizacionDetalle']])) : ?>
								<a class="item btnCotizacionesProveedores" data-id="<?= $row['idCotizacionDetalle'] ?>">
									<i class="users icon"></i>
								</a>
							<?php endif; ?>
							<a class="item chk-item" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); ">
								<i class="square outline icon"></i>
								<input type="checkbox" name="checkItem[<?= $row['idCotizacionDetalle'] ?>]" class="checkItem d-none">
							</a>
							<a class="item btnPopupCotizacionesProveedor" data-proveedores='<?= !empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas']) ?>' data-id="<?= $row['idCotizacionDetalle'] ?>">
								<i class="hand holding usd icon"></i>
								<? if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']])) { ?>
									<?php if ($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'] == '') :  ?>
										<?php $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'] = 1 ?>
									<?php endif; ?>
									<div class="floating ui teal label"><?= $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'] ?></div>
								<? } ?>
							</a>
							<a class="item btnPopupPropuestaItem" data-proveedores='<?= !empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas']) ?>' data-id="<?= $row['idCotizacionDetalle'] ?>">
								<i class="sync alternate icon"></i>
							</a>
						</div>
						<? if (!empty($cotizacionProveedorVista[$row['idCotizacionDetalle']])) { ?>
							<div class="ui flowing custom popup custom-popup-<?= $row['idCotizacionDetalle'] ?> top left transition hidden">
								<?
								$wide = 'one';
								if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'])) {
									$prov = $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'];

									if ($prov >= 2) {
										$wide = 'two';
									}
								}
								?>
								<div class="ui <?= $wide ?> column divided center aligned grid">
									<? foreach ($cotizacionProveedorVista[$row['idCotizacionDetalle']] as $view) { ?>
										<div class="column">
											<h4 class="ui header"><?= $view['razonSocial'] ?></h4>
											<p><b><?= $view['cantidad'] ?></b> cantidad, <?= moneda($view['subTotal']) ?></p>
											<p><b>Costo Unitario: </b> <?= moneda($view['costoUnitario']) ?></p>

											<div class="ui button btnElegirProveedor primary">Elegir
												<input type="hidden" class="txtCodProveedorCotizacion" value="<?= $view['idCotizacionDetalleProveedorDetalle'] ?>">
												<input type="hidden" class="txtCostoProveedor" value="<?= $view['costoUnitario'] ?>">
												<input type="hidden" class="txtDiasEntregaItemProveedor" value="<?= $view['diasEntrega'] ?>">
												<input type="hidden" class="txtProveedorElegido" value="<?= $view['idProveedor'] ?>">
												<input type="hidden" class="txtSubProveedorCotizacion" value='<?= (!empty($cotizacionProveedorSub[$view['idCotizacionDetalleProveedorDetalle']]) && $row['idItemTipo'] != COD_SERVICIO['id']) ? json_encode($cotizacionProveedorSub[$view['idCotizacionDetalleProveedorDetalle']]) : "" ?>'>
												<?php if (!empty($cotizacionProveedorSub[$view['idCotizacionDetalleProveedorDetalle']]) && $row['idItemTipo'] == COD_SERVICIO['id']) :  ?>
													<label class="d-none txtDetalleTipoServicio"><?= json_encode($cotizacionProveedorSub[$view['idCotizacionDetalleProveedorDetalle']]) ?></label> <!-- Lo pongo en un label para evitar que una comilla dentro del contenido pueda causar errores. -->
													<input type="hidden" class="txtIdCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>">
												<?php endif; ?>
												<div class="d-none col-md-12 elegirImagenes">
													<?php if (!empty($cotizacionProveedorArchivos[$view['idCotizacionDetalleProveedorDetalle']])) : ?>
														<div class="ui small images">
															<?php foreach ($cotizacionProveedorArchivos[$view['idCotizacionDetalleProveedorDetalle']] as $keyCPA => $imgCPA) : ?>
																<div class="ui fluid image dimmable" data-id="<?= $keyCPA ?>">
																	<!-- TODO : Hacer una funcion en helper $src = src(tipoArchivo, rutaWasabi, nombreArchivo)  -->
																	<?php $src = RUTA_WIREFRAME . "file.png" ?>
																	<?php $src = ($imgCPA['idTipoArchivo'] == TIPO_PDF) ? (RUTA_WIREFRAME . "pdf.png") : $src; ?>
																	<?php $src = ($imgCPA['idTipoArchivo'] == TIPO_EXCEL) ? (RUTA_WIREFRAME . "xlsx.png") : $src; ?>
																	<?php $src = ($imgCPA['idTipoArchivo'] == TIPO_IMAGEN) ? (RUTA_WASABI . 'cotizacionProveedor/' . $imgCPA['nombre_archivo']) : $src; ?>
																	<a target="_blank" href="<?= RUTA_WASABI . 'cotizacionProveedor/' . $imgCPA['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
																	<img height="100" src="<?= $src ?>" class="img-responsive img-thumbnail">
																</div>
															<?php endforeach; ?>
														</div>
													<?php endif; ?>
												</div>
											</div>
											<div class="ui button btnConsultarItemProveedor" data-cot="<?= $row['idCotizacionDetalle'] ?>" data-pro="<?= $view['idProveedor'] ?>">
												Consultar
												<!-- <input type="hidden" class="txtCostoProveedor" value="<?= $view['costoUnitario'] ?>">
                                                <input type="hidden" class="txtProveedorElegido" value="<?= $view['idProveedor'] ?>"> -->
											</div>
										</div>
									<? } ?>
								</div>
							</div>
						<? } ?>
						<? if (true) { ?>
							<div class="ui flowing custom popup popup-propuesta-<?= $row['idCotizacionDetalle'] ?> top left transition hidden">
								<div class="ui vertical menu">
									<?php if (!empty($cotizacionPropuesta[$row['idCotizacionDetalle']])) :  ?>
										<? foreach ($cotizacionPropuesta[$row['idCotizacionDetalle']] as $cp) { ?>
											<a class="item">
												<h4 class="ui header"><?= !empty($cp['proveedor']) ? $cp['proveedor'] : '' ?></h4>
												<p><?= !empty($cp['nombre']) ? $cp['nombre'] : '' ?></p>
												<p><?= !empty($cp['motivo']) ? $cp['motivo'] : '' ?></p>

											</a>
										<? } ?>
									<?php endif; ?>
								</div>
							</div>
						<? } ?>

					</div>
					<div class="ui left floated header sticky stickyDetalleCotizacion">
						<span class="ui medium text ">
							<?= $row['item'] ?>
						</span>
						<br>
						<small class="text-primary"> Proveedores: <?= !empty($cotizacionProveedorRegistrados[$row['idCotizacionDetalle']]) ? implode(', ', $cotizacionProveedorRegistrados[$row['idCotizacionDetalle']]) : 'No se han enviado solicitudes' ?></small>
					</div>
					<div class="ui clearing divider"></div>
					<div class="ui grid">
						<div class="sixteen wide tablet twelve wide computer column">
							<div class="fields">

								<div class="six wide field">
									<div class="ui sub header">Item</div>
									<div class="ui-widget">
										<div class="ui right action left icon input w-100">
											<i class="semaforoForm flag link icon"></i>
											<input class="nameItemForm" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>">
											<input type='hidden' name='nameItemOriginal' patron="" value="<?= $row['itemNombre'] ?>">
											<div class="ui basic floating flagCuentaSelect dropdown button simpleDropdown">
												<input type="hidden" class="flagCuentaForm" name="flagCuenta" value="<?= !empty($row['flagExterno']) ? $row['flagExterno'] : 0 ?>" patron="requerido">
												<div class="text">Cuenta</div>
												<i class="dropdown icon"></i>
												<div class="menu">
													<div class="item" data-value="1">De la cuenta</div>
													<div class="item" data-value="0">Externo</div>
												</div>
											</div>
										</div>

										<input class="codItems" type='hidden' name='idItemForm' value="<?= $row['idItem'] ?>">

										<input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
										<input class="idProveedor" type='hidden' name='idProveedorForm' value="<?= !empty($row['idProveedor']) ? $row['idProveedor'] : ""; ?>">
										<input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
									</div>
								</div>
								<div class="five wide field disabled disabled-visible">
									<div class="ui sub header">Tipo Item</div>
									<select class="ui dropdown simpleDropdown idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido">
										<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="five wide field">
									<div class="ui sub header">TÍTULO DE COTIZACIÓN - COMPRAS</div>
									<div class="ui labeled input w-100">
										<input class="tituloCoti" type='text' name='tituloCoti' placeholder="Título de Cotización - Compras" maxlength="100" value="<?= verificarEmpty($row['tituloParaOc']); ?>">
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="six wide field">
									<div class="ui sub header">Características</div>
									<div class="ui input w-100">
										<input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Caracteristicas del item">
										<input class="diasEntregaItemForm" type='hidden' name='diasEntregaItem' value="<?= !empty($row['diasEntrega']) ? $row['diasEntrega'] : '' ?>">
									</div>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Características para Compras</div>
									<input name="caracteristicasCompras" placeholder="Características" value="<?= !empty($row['caracteristicasCompras']) ? $row['caracteristicasCompras'] : '' ?>" autocomplete="off">
								</div>
								<div class="five wide field">
									<div class="ui sub header">Características para el proveedor</div>
									<input name="caracteristicasProveedor" placeholder="Características" value="<?= !empty($row['caracteristicasProveedor']) ? $row['caracteristicasProveedor'] : '' ?>" autocomplete="off">
								</div>
							</div>
							<!-- Textiles -->
							<div class="ui form attached fluid segment my-3 <?= $row['idItemTipo'] == COD_TEXTILES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TEXTILES['id'] ?>">
								<h4 class="ui dividing header">SUB ITEMS</h4>
								<div class="content-body-sub-item">
									<?
									if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TEXTILES['id']])) :
										foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TEXTILES['id']] as $dataSubItem) : ?>
											<div class="fields body-sub-item ">
												<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="one wide field">
													<div class="ui sub header">Talla</div>
													<input readonly class="tallaSubItem camposTextil" name="tallaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Talla" value="<?= !empty($dataSubItem['talla']) ? $dataSubItem['talla'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Tela</div>
													<input readonly class="telaSubItem camposTextil" name="telaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Tela" value="<?= !empty($dataSubItem['tela']) ? $dataSubItem['tela'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Color</div>
													<input readonly class="colorSubItem " name="colorSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Color" value="<?= !empty($dataSubItem['color']) ? $dataSubItem['color'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Cantidad</div>
													<input readonly class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Genero</div>
													<select class="ui search dropdown simpleDropdown" name="generoSubItem[<?= $row['idCotizacionDetalle'] ?>]">
														<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => LIST_GENERO, 'class' => 'text-titlecase', 'selected' => $dataSubItem['genero']]); ?>
													</select>
												</div>
												<div class="two wide field">
													<div class="ui sub header">Costo</div>
													<input patron="requerido" readonly class="onlyNumbers costoSubItem costoSubItemTextil" name="costoTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Costo" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Subtotal</div>
													<input patron="requerido" readonly class="onlyNumbers subtotalSubItem subtotalSubItemTextil" name="subtotalTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Subtotal" value="<?= !empty($dataSubItem['subtotal']) ? $dataSubItem['subtotal'] : '' ?>">
												</div>
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>

							</div>

							<!-- Monto S/ -->
							<div class="fields <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
								<?
								if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']])) :
									foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']] as $dataSubItem) : ?>
										<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">

										<div class="sixteen wide field">
											<div class="ui sub header">Monto S/</div>
											<input class="montoSubItem" name="montoSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Monto" value="<?= !empty($dataSubItem['monto']) ? $dataSubItem['monto'] : '' ?>">
										</div>
								<?
									endforeach;
								endif;
								?>
							</div>

							<!-- Servicios -->
							<div class="ui form attached fluid segment my-3 <?= $row['idItemTipo'] == COD_SERVICIO['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
								<h4 class="ui dividing header">SUB ITEMS</h4>
								<div class="content-body-sub-item divItemServicio">
									<?php if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']])) : ?>

										<?php $var1 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['sucursal']; ?>
										<?php $var2 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['razonSocial']; ?>
										<?php $var3 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['tipoElemento']; ?>
										<?php $var4 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['marca']; ?>
										<?php $costoTotal = 0; ?>

										<?php foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']] as $dataSubItem) : ?>
											<?php if (!($var1 == $dataSubItem['sucursal'] && $var2 == $dataSubItem['razonSocial'] && $var3 == $dataSubItem['tipoElemento'] && $var4 == $dataSubItem['marca'])) :  ?>
												<?php $var1 = $dataSubItem['sucursal']; ?>
												<?php $var2 = $dataSubItem['razonSocial']; ?>
												<?php $var3 = $dataSubItem['tipoElemento']; ?>
												<?php $var4 = $dataSubItem['marca']; ?>
												<div class="fields">
													<div class="field fourteen wide ui transparent input">
														<input readonly class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
													</div>
													<div class="field two wide ui transparent input">
														<input readonly class="" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
													</div>
												</div>
												<hr class="solid">
												<?php $costoTotal = 0; ?>
											<?php endif; ?>
											<?php $costoTotal += (floatval($dataSubItem['cantidad']) * floatval($dataSubItem['costo'])) ?>
											<div class="fields body-sub-item body-sub-item-servicio">
												<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="five wide field">
													<div class="ui sub header">Sucursal</div>
													<input class="marcaSubItem" name="sucursalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Sucursal" value="<?= !empty($dataSubItem['sucursal']) ? $dataSubItem['sucursal'] : '' ?>">
												</div>
												<div class="five wide field">
													<div class="ui sub header">Razón Social</div>
													<input class="marcaSubItem" name="razonSocialSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Razón Social" value="<?= !empty($dataSubItem['razonSocial']) ? $dataSubItem['razonSocial'] : '' ?>">
												</div>
												<div class="five wide field">
													<div class="ui sub header">Tipo Elemento</div>
													<input class="marcaSubItem" name="tipoElementoSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Tipo Elemento" value="<?= !empty($dataSubItem['tipoElemento']) ? $dataSubItem['tipoElemento'] : '' ?>">
												</div>
												<div class="five wide field">
													<div class="ui sub header">Marca</div>
													<input class="marcaSubItem" name="marcaSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Marca" value="<?= !empty($dataSubItem['marca']) ? $dataSubItem['marca'] : '' ?>">
												</div>
												<div class="six wide field">
													<div class="ui sub header">Descripción</div>
													<input class="nombreSubItem" name="nombreSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Descripción" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Cantidad</div>
													<input readonly class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Costo</div>
													<input readonly class="onlyNumbers costoSubItem" name="costoSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= verificarEmpty($dataSubItem['costo']) ?>" readonly>
												</div>
												<div class="four wide field">
													<div class="ui sub header">Subtotal</div>
													<input readonly class="onlyNumbers subTotalSubItem" name="subtotalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= number_format(verificarEmpty($dataSubItem['subtotal'], 2), 2) ?>" readonly>
												</div>
											</div>
										<?php endforeach; ?>
										<div class="fields">
											<div class="field fourteen wide ui transparent input">
												<input readonly class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
											</div>
											<div class="field two wide ui transparent input">
												<input readonly class="" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
											</div>
										</div>
										<hr class="solid">
									<?php endif; ?>
								</div>
							</div>

							<!-- Distribucion -->
							<div class="fields <?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>">
								<?
								if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']])) :
									foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']] as $dataSubItem) : ?>

										<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
										<div class="seven wide field">
											<div class="ui sub header">Tipo Servicio</div>
											<select class="ui search dropdown simpleDropdown tipoServicioForm tipoServicioSubItem" name="tipoServicioSubItem[<?= $row['idCotizacionDetalle'] ?>]">
												<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
											</select>
										</div>
										<div class="three wide field">
											<div class="ui sub header">Unidad de medida</div>
											<input class="unidadMedidaTipoServicio" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['unidadMedida']) ? $dataSubItem['unidadMedida'] : '' ?>" readonly>
											<input type="hidden" class="unidadMedidaSubItem" name="unidadMedidaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['idUnidadMedida']) ? $dataSubItem['idUnidadMedida'] : '' ?>" readonly>
										</div>
										<div class="three wide field">
											<div class="ui sub header">Costo S/</div>
											<input class="costoTipoServicio costoSubItem" name="costoSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Costo" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>" readonly>
										</div>
										<div class="three wide field">
											<div class="ui sub header">Pesos / Cantidad</div>
											<input class="onlyNumbers cantidadSubItemDistribucion cantidadSubItem" name="cantidadSubItemDistribucion[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
										</div>
								<?
									endforeach;
								endif;
								?>
							</div>

							<div class="fields">
								<div class="four wide field">
									<div class="ui sub header">Archivos</div>
									<div class="ui small image btn-add-file text-center">
										<div class="ui dimmer">
											<div class="content">
												<div class="ui small primary button" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();">
													Agregar
												</div>
											</div>
										</div>
										<img class="ui image" src="<?= IMG_WIREFRAME ?>">
									</div>
									<div class="extraImages">
										<?php if (!empty($cotizacionDetalleArchivosDelProveedor[$row['idCotizacionDetalle']])) :  ?>
											<div class="ui small images">
												<?php foreach ($cotizacionDetalleArchivosDelProveedor[$row['idCotizacionDetalle']] as $kAE => $vAE) : ?>
													<div class="ui fluid image dimmable" data-id="<?= $kAE ?>">
														<a target="_blank" href="<?= RUTA_WASABI . 'cotizacionProveedor/' . $vAE['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
														<img height="100" src="<?= $vAE['extension'] == 'pdf' ? (RUTA_WIREFRAME . "pdf.png") : (RUTA_WASABI . 'cotizacionProveedor/' . $vAE['nombre_archivo']) ?>" class="img-responsive img-thumbnail">
													</div>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="twelve wide field">
									<div class="ui sub header">Links</div>
									<div class="ui left corner labeled input">
										<div class="ui left corner label">
											<i class="linkify icon"></i>
										</div>
										<textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"><?= !empty($row['enlaces']) ? $row['enlaces'] : '' ?></textarea>
									</div>
								</div>
							</div>
							<div class="content-lsck-capturas">
								<input data-row="<?= $kd ?>" type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*,.pdf" multiple="">
								<div class="fields ">
									<div class="sixteen wide field">
										<div class="ui small images content-lsck-galeria">
											<? if (!empty($cotizacionDetalleArchivos[$row['idCotizacionDetalle']])) { ?>
												<? foreach ($cotizacionDetalleArchivos[$row['idCotizacionDetalle']] as $archivo) {
													if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) { ?>
														<div class="ui fluid image content-lsck-capturas" data-id="<?= $archivo['idCotizacionDetalleArchivo'] ?>">
															<div class="ui dimmer dimmer-file-detalle">
																<div class="content">
																	<p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
																</div>
															</div>
															<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
															<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
															<input type="hidden" value="">
															<input type="hidden" value="image/<?= $archivo['extension'] ?>">
															<input type="hidden" value="<?= $archivo['nombre_inicial'] ?>">
															<img height="100" src="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
														</div>
												<? }
												} ?>
											<? } ?>
										</div>
									</div>
								</div>
								<div class="fields ">
									<div class="sixteen wide field">
										<div class="ui small images content-lsck-files">
											<? if (!empty($cotizacionDetalleArchivos[$row['idCotizacionDetalle']])) { ?>
												<? foreach ($cotizacionDetalleArchivos[$row['idCotizacionDetalle']] as $archivo) {
													if ($archivo['idTipoArchivo'] == TIPO_PDF || $archivo['idTipoArchivo'] == TIPO_OTROS || $archivo['idTipoArchivo'] == TIPO_EXCEL) { ?>
														<div class="ui fluid image content-lsck-capturas" data-id="<?= $archivo['idCotizacionDetalleArchivo'] ?>">
															<div class="ui dimmer dimmer-file-detalle">
																<div class="content">
																	<p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
																</div>
															</div>
															<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
															<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
															<input type="hidden" name="file-item[<?= $row['idCotizacionDetalle'] ?>]" value="">
															<input type="hidden" name="file-type[<?= $row['idCotizacionDetalle'] ?>]" value="application/<?= $archivo['extension'] ?>">
															<input type="hidden" name="file-name[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
															<img height="100" src="<?= RUTA_WIREFRAME . ($archivo['idTipoArchivo'] == TIPO_PDF ? 'pdf.png' : 'file.png') ?>" class="img-lsck-capturas img-responsive img-thumbnail">
														</div>
												<? }
												} ?>
											<? } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="sixteen wide tablet four wide computer column">
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Cantidad</div>
									<input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' readonly>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Costo</div>
									<div class="ui right action right labeled input">
										<label for="amount" class="ui label">S/</label>
										<input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo'], false, 4) : '' ?>" readonly>
										<input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>

										<input type="hidden" class="costoRedondeadoForm" name="costoRedondeadoForm" placeholder="0" value="0">
										<input type="hidden" class="costoNoRedondeadoForm" name="costoNoRedondeadoForm" placeholder="0" value="0">

										<div class="ui basic floating dropdown button simpleDropdown d-none">
											<input type="hidden" class="flagRedondearForm" name="flagRedondearForm" value="<?= !empty($row['flagRedondear']) ? $row['flagRedondear'] : 0 ?>" patron="requerido">
											<div class="text">Redondear</div>
											<i class="dropdown icon"></i>
											<div class="menu">
												<div class="item" data-value="1">Redondear arriba</div>
												<div class="item" data-value="0">No redondear</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="eight wide field">
									<div class="ui sub header">GAP %</div>
									<div class="ui right labeled input">
										<input data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($row['gap']) ? $row['gap'] : '' ?>" readonly>
										<div class="ui basic label">
											%
										</div>
									</div>
								</div>
								<div class="eight wide field">
									<div class="ui sub header">Precio</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label">S/</label>
										<input class="precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo'], false, 4) : '' ?>" readonly>
										<input class="precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Subtotal</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label teal">S/</label>
										<input class=" subtotalFormLabel" type="text" placeholder="0.00" patron="requerido" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal'], false, 4) : '' ?>" readonly>
										<input class=" subtotalForm" type="hidden" patron="requerido" name="subtotalForm" placeholder="0.00" value="<?= !empty($row['subtotal']) ? ($row['subtotal']) : '' ?>" readonly>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<div class="ui black three column center aligned stackable divided grid segment">
			<div class="column">
				<div class="ui test toggle checkbox read-only">
					<input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();" <?= $cotizacion['igv'] ? 'checked' : '' ?>>
					<label>Incluir IGV</label>
				</div>
			</div>
			<div class="column">
				<!-- <div class="ui sub header">Total</div> -->
				<div class="ui right labeled input">
					<label for="feeForm" class="ui label">Fee: </label>
					<input data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();" readonly>
					<div class="ui basic label">
						%
					</div>
				</div>
			</div>
			<div class="column">
				<div class="ui right labeled input">
					<label for="totalForm" class="ui label green">Total: </label>
					<input class=" totalFormLabel" type="text" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? moneda($cotizacion['total']) : '0.00' ?>" readonly="">
					<input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? ($cotizacion['total']) : '0.00' ?>" readonly="">
					<input class=" totalFormFeeIgv" type="hidden" name="totalFormFeeIgv" placeholder="0.00" readonly="">
					<input class=" totalFormFee" type="hidden" name="totalFormFee" placeholder="0.00" readonly="">
				</div>
			</div>
		</div>
	</form>
</div>

<!-- FAB -->
<div class="floating-container">
	<div class="floating-button ">
		<i class="cog icon"></i>
	</div>
	<div class="element-container">
		<a href="javascript:;">
			<span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "<?= $controller ?>.registrarCotizacion(<?= $siguienteEstado ?>)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });'>
				<i class="send icon"></i>
			</span>
			<span class="float-element tooltip-left btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "noValidar", fn: "<?= $controller ?>.registrarCotizacion(1)", content: "¿Esta seguro de guardar esta cotizacion?" });'>
				<i class="save icon"></i>
			</span>
			<!-- <span class="float-element tooltip-left btn-add-detalle btn-add-row" onclick="" data-message="Agregar detalle">
                <i class="plus icon"></i>
            </span> -->
		</a>
	</div>
</div>

<!-- Popup Leyenda -->
<div class="ui leyenda popup top left transition hidden">
	<div class="ui sub header">Semáforo tarifario</div>
	<div class="ui list">
		<div class="item">
			<i class="flag icon teal"></i>
			<div class="content">
				+ 2 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon yellow"></i>
			<div class="content">
				1 a 2 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon red"></i>
			<div class="content">
				Tarifario expiró.
			</div>
		</div>
	</div>
	<div class="ui clearing divider"></div>
	<div class="ui sub header">Otros</div>
	<div class="ui list">
		<div class="item">
			<i class="square icon teal"></i>
			<div class="content">
				Subtotal
			</div>
		</div>
		<div class="item">
			<i class="square icon green"></i>
			<div class="content">
				Total
			</div>
		</div>
	</div>
</div>
<div class="ui modal">
	<div class="center aligned header">Header is centered</div>
	<div class="center aligned content">
		<p>Content is centered</p>
	</div>
	<div class="center aligned actions">
		<div class="ui negative button">Cancel</div>
		<div class="ui positive button">OK</div>
	</div>
</div>
<!-- Items -->
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>
<input id="tachadoDistribucion" type="hidden" value='<?= json_encode($tachadoDistribucion) ?>'>