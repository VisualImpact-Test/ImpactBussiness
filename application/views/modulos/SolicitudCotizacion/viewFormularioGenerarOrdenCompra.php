<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

	.floating-container {
		height: 150px !important;
	}
</style>
<div class="ui form attached fluid segment p-4 <?= !empty($disabled) ? 'disabled' : '' ?>">
	<form class="ui form" role="form" id="formRegistroOrdenCompra" method="post">
		<input type="hidden" name="idOper" value="<?= $idOper ?>">
		<?php foreach ($cotizaciones as $cotizacion) : ?>
			<input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
		<?php endforeach; ?>

		<?php foreach ($cotizacionDetalle as $row) : ?>
			<div class="default-item">
				<input type="hidden" name="idCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>" id="">
				<div class="ui segment body-item nuevo" data-id="<?= $row['idCotizacionDetalle'] ?>">
					<div class="ui right floated header">
						<div class="ui icon menu">
							<a class="item chk-item" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); ">
								<i class="square outline icon"></i>
								<input type="checkbox" name="checkItem[<?= $row['idCotizacionDetalle'] ?>]" class="checkItem d-none">
							</a>
							<a class="item btnPopupCotizacionesProveedor" data-proveedores='<?= !empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas']) ?>' data-id="<?= $row['idCotizacionDetalle'] ?>">
								<i class="hand holding usd icon"></i>
								<?php if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'])) :  ?>
									<div class="floating ui teal label"><?= $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'] ?></div>
								<?php endif; ?>
							</a>
						</div>
						<?php if (!empty($cotizacionProveedorVista[$row['idCotizacionDetalle']])) :  ?>
							<div class="ui flowing custom popup custom-popup-<?= $row['idCotizacionDetalle'] ?> top left transition hidden">
								<?
								$wide = 'one';
								if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'])) {
									$prov = $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'];

									if ($prov == 2) {
										$wide = 'two';
									}
									if ($prov == 3) {
										$wide = 'three';
									}
									if ($prov >= 4) {
										$wide = 'four';
									}
								}
								?>
								<div class="ui <?= $wide ?> column divided center aligned grid">
									<?php foreach ($cotizacionProveedorVista[$row['idCotizacionDetalle']] as $view) : ?>
										<div class="column">
											<h4 class="ui header"><?= $view['razonSocial'] ?></h4>
											<p><b><?= $view['cantidad'] ?></b> cantidad, <?= moneda($view['subTotal']) ?></p>
											<p><b>Costo Unitario: </b> <?= moneda($view['costoUnitario']) ?></p>

											<div class="ui button btnElegirProveedor">Elegir
												<input type="hidden" class="txtCostoProveedor" value="<?= $view['costoUnitario'] ?>">
												<input type="hidden" class="txtProveedorElegido" value="<?= $view['idProveedor'] ?>">
												<input type="hidden" class="txtProveedorElegidoName" value="<?= $view['razonSocial'] ?>">
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="ui left floated header">
						<span class="ui medium text "><?= $row['item'] ?></span></span>
					</div>
					<div class="ui clearing divider"></div>
					<div class="ui grid">
						<div class="sixteen wide column">
							<div class="fields">
								<div class="six wide field d-none">
									<div class="ui sub header">Item</div>
									<div class="ui-widget">
										<div class="ui icon input w-100">
											<input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>">
											<i class="semaforoForm flag link icon"></i>
										</div>

										<input class="codItems" type='hidden' name='idItemForm' value="<?= $row['idItem'] ?>">
										<input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
										<input class="idProveedor" type='hidden' name='idProveedorForm' value="<?= !empty($row['idProveedor']) ? $row['idProveedor'] : '' ?>">
										<input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
									</div>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Proveedor</div>
									<input class="proveedoresForm" type='text' value="<?= $row['razonSocial'] ?>" readonly>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Tipo Item</div>
									<select class="ui dropdown simpleDropdown read-only idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido">
										<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Características</div>
									<div class="ui right labeled input w-100">
										<input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' value="<?= !empty($row['caracteristicasCompras']) ? $row['caracteristicasCompras'] : '' ?>" placeholder="Caracteristicas del item">
									</div>
								</div>
								<div class="three wide field">
									<div class="ui sub header">Nro OC</div>
									<div class="ui right labeled input w-100">
										<input class="" type='text' name='ocDelCliente' value="<?= !empty($row['codOrdenCompra']) ? $row['codOrdenCompra'] : '' ?>" placeholder="Caracteristicas del item">
									</div>
								</div>
							</div>
							<!-- Textiles -->
							<div class="disabled disabled-visible ui form attached fluid segment my-3 <?= $row['idItemTipo'] == COD_TEXTILES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TEXTILES['id'] ?>">
								<h4 class="ui dividing header">SUB ITEMS</h4>
								<div class="content-body-sub-item">
									<?
									if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TEXTILES['id']])) :
										foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TEXTILES['id']] as $dataSubItem) : ?>
											<input type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
											<div class="fields body-sub-item ">
												<div class="one wide field">
													<div class="ui sub header">Talla</div>
													<input class="tallaSubItem camposTextil" name="tallaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Talla" value="<?= !empty($dataSubItem['talla']) ? $dataSubItem['talla'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Tela</div>
													<input class="telaSubItem camposTextil" name="telaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Tela" value="<?= !empty($dataSubItem['tela']) ? $dataSubItem['tela'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Color</div>
													<input class="colorSubItem " name="colorSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Color" value="<?= !empty($dataSubItem['color']) ? $dataSubItem['color'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Cantidad</div>
													<input class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Genero</div>
													<input class="" name="generoTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Genero" value="<?= !empty($dataSubItem['genero']) ? RESULT_GENERO[$dataSubItem['genero']] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Costo</div>
													<input class="onlyNumbers  costoSubItem costoSubItemTextil" name="costoTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Costo" value="<?= !empty($dataSubItem['costo']) ? moneda($dataSubItem['costo']) : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Subtotal</div>
													<input class="onlyNumbers  subtotalSubItem subtotalSubItemTextil" name="subtotalTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Subtotal" value="<?= !empty($dataSubItem['subtotal']) ? moneda($dataSubItem['subtotal']) : '' ?>">
												</div>
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>

							</div>

							<!-- Monto S/ -->
							<div class="disabled disabled-visible fields <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
								<?
								if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']])) :
									foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']] as $dataSubItem) : ?>
										<input type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
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
							<div class="disabled disabled-visible ui form attached fluid segment my-3 <?= $row['idItemTipo'] == COD_SERVICIO['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
								<h4 class="ui dividing header">SUB ITEMS</h4>
								<div class="content-body-sub-item">
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
													<div class="field fifteen wide ui transparent input">
														<input readonly="readonly" class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
													</div>
													<div class="field one wide ui transparent input">
														<input readonly="readonly" class="" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
													</div>
												</div>
												<hr class="solid">
												<?php $costoTotal = 0; ?>
											<?php endif; ?>
											<?php $costoTotal += (floatval($dataSubItem['cantidad']) * floatval($dataSubItem['costo'])) ?>
											<input type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
											<div class="fields body-sub-item body-sub-item-servicio">
												<div class="three wide field">
													<div class="ui sub header">Sucursal </div>
													<input class="sucursalSubItem" name="sucursalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['sucursal']) ? $dataSubItem['sucursal'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Razón Social </div>
													<input class="razonSocialSubItem" name="razonSocialSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['razonSocial']) ? $dataSubItem['razonSocial'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Tipo Elemento </div>
													<input class="tipoElementoSubItem" name="tipoElementoSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['tipoElemento']) ? $dataSubItem['tipoElemento'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Marca </div>
													<input class="marcaSubItem" name="marcaSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['marca']) ? $dataSubItem['marca'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Descripción </div>
													<input class="nombreSubItem" name="nombreSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>">
												</div>
												<div class="one wide field">
													<div class="ui sub header">Cantidad</div>
													<input class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
												<div class="one wide field">
													<div class="ui sub header">Costo</div>
													<input class="precioUnitarioSubItem" name="precioUnitarioSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>">
												</div>
												<div class="one wide field">
													<div class="ui sub header">Subtotal</div>
													<input class="onlyNumbers subTotalSubItem" name="subtotalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" value="<?= verificarEmpty($dataSubItem['subtotal']) ?>">
												</div>
											</div>
										<?php endforeach; ?>
										<div class="fields">
											<div class="field fifteen wide ui transparent input">
												<input readonly="readonly" class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
											</div>
											<div class="field one wide ui transparent input">
												<input readonly="readonly" class="" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<!-- Distribucion -->
							<div class="disabled disabled-visible fields <?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>">
								<?
								if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']])) :
									foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']] as $dataSubItem) : ?>
										<input type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
										<div class="four wide field">
											<div class="ui sub header">Tipo Servicio</div>
											<select class="ui search dropdown simpleDropdown tipoServicioForm tipoServicioSubItem" name="tipoServicioSubItem[<?= $row['idCotizacionDetalle'] ?>]">
												<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'selected' => $dataSubItem['idTipoServicio'], 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
											</select>
										</div>
										<div class="three wide field">
											<div class="ui sub header">Unidad de medida</div>
											<input class="unidadMedidaTipoServicio" name="unidadMedidaNameSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['unidadMedida']) ? $dataSubItem['unidadMedida'] : '' ?>" readonly>
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
										<div class="three wide field">
											<div class="ui sub header">Cantidad PDV</div>
											<input class="onlyNumbers cantidadPDVSubItemDistribucion cantidadPDVSubItem" name="cantidadPDVSubItemDistribucion[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidadPdv']) ? $dataSubItem['cantidadPdv'] : '' ?>">
										</div>
								<?
									endforeach;
								endif;
								?>
							</div>

						</div>
						<div class="sixteen wide column">
							<div class="fields">
								<div class="three wide field">
									<div class="ui sub header">Cantidad</div>
									<input class=" cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" readonly patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
								</div>
								<div class="three wide field">
									<div class="ui sub header">
										Costo
										<?php if (!empty($autorizaciones[$row['idCotizacionDetalle']])) :  ?>
											<input type="hidden" class="idAutorizacion" value="<?= $autorizaciones[$row['idCotizacionDetalle']]['idAutorizacion'] ?>">
											<?= $autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_PENDIENTE ? '<button type="button" class="btnAutorizarCosto btn-link p-0 border-0"><small class="text-info ">(Solicitud enviada)</small></button>' : '' ?>
											<?= $autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_ACEPTADO ? '<button type="button" class="btnAutorizarCosto btn-link p-0 border-0"><small class="text-sucess ">(Solicitud aceptada)</small></button>' : '' ?>
											<?= $autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_RECHAZADO ? '<button type="button" class="btnAutorizarCosto btn-link p-0 border-0"><small class="text-danger ">(Solicitud rechazada)</small></button>' : '' ?>
										<?php endif; ?>
									</div>
									<div class="ui right labeled input <?= !empty($autorizaciones[$row['idCotizacionDetalle']]) ? 'disabled disabled-visible' : '' ?>">
										<label for="amount" class="ui label">S/</label>
										<input class="costoFormLabelEditable costoFormLabel onlyNumbers" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? number_format($row['costo'], 2, '.', ',') : '' ?>">
										<input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
									</div>
								</div>
								<div class="three wide field">
									<div class="ui sub header">GAP %</div>
									<div class="ui right labeled input">
										<input data-max='100' data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($row['gap']) ? $row['gap'] : '' ?>" readonly>
										<div class="ui basic label">
											%
										</div>
									</div>
								</div>
								<div class="three wide field">
									<div class="ui sub header">Precio</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label">S/</label>
										<input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" readonly>
										<input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>" readonly>
									</div>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Subtotal</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label teal">S/</label>
										<input class="subtotalFormLabel" type="text" placeholder="0.00" patron="requerido" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal']) : '' ?>" readonly>
										<input class="subtotalForm" type="hidden" patron="requerido" name="subtotalForm" placeholder="0.00" value="<?= !empty($row['subtotal']) ? ($row['subtotal']) : '' ?>" readonly>
									</div>
									<?php if ($row['flagRedondear'] == '1') :  ?>
										<div class="ui pointing blue basic label">
											Valor redondeado
										</div>
									<?php endif; ?>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</form>
</div>

<!-- FAB -->
<div class="floating-container">
	<div class="floating-button ">
		<i class="cog icon"></i>
	</div>
	<div class="element-container">
		<a href="javascript:;">
			<!-- <span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroOrdenCompra", fn: "<?= $controller ?>.registrarOrdenCompra(<?= $siguienteEstado ?>)", content: "¿Esta seguro de generar ordenes de compra para cada proveedor seleccionado?" });'>
                <i class="send icon"></i>
            </span> -->
			<span class="float-element tooltip-left btn-preview-orden-compra" data-message="Generar OC">
				<i class="search dollar icon"></i>
			</span>
			<!-- <span class="float-element tooltip-left btn-add-detalle btn-add-row" onclick="" data-message="Agregar detalle">
                <i class="plus icon"></i>
            </span> -->
		</a>
	</div>
</div>

<!-- Popup Leyenda -->
<div class="ui leyenda popup top left transition hidden">
	<div class="ui list">
		<div class="item">
			<i class="flag icon teal"></i>
			<div class="content">
				7 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon yellow"></i>
			<div class="content">
				8 a 15 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon red"></i>
			<div class="content">
				+15 días
			</div>
		</div>
	</div>
	<div class="ui clearing divider"></div>
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

<div class="ui red fixed nag" id="nagPrecioValidacion">
	<div class="title">EL COSTO NO PUEDE SER IGUAL O MAYOR AL PRECIO</div>
	<i class="close icon"></i>
</div>

<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>
<input id="tachadoDistribucion" type="hidden" value='<?= json_encode($tachadoDistribucion) ?>'>