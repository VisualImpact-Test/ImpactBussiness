<!-- <div class="ui attached  message">
  <div class="header">
    Registrar Cotización
  </div>
</div> -->
<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

	.floating-container {
		height: 150px !important;
	}

	.btn-info-custom {
		cursor: pointer;
		display: inline-block;
		line-height: 1;
	}
</style>
<div class="ui form attached fluid segment p-4 <?= !empty($disabled) ? 'read-only' : '' ?>">
	<form class="ui form" role="form" id="formRegistroCotizacion" method="post">
		<input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
		<input type="hidden" name="costoDistribucion" id="costoDistribucion" value="<?= !empty($costoDistribucion) ? $costoDistribucion['costo'] : 0 ?>">
		<h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
		<div class="fields">
			<div class="six wide field">
				<div class="ui sub header">Título</div>
				<input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Deadline compras</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
			</div>
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
			<div class="two wide field">
				<div class="ui sub header">
					Validez <div class="ui btn-info-validez btn-info-custom text-primary"><i class="info circle icon"></i></div>
				</div>
				<input class="onlyNumbers" id="diasValidez" name="diasValidez" patron="requerido" placeholder="Días de validez" value="<?= !empty($cotizacion['diasValidez']) ? $cotizacion['diasValidez'] : '' ?>">
			</div>
		</div>
		<div class="fields">
			<div class="five wide field">
				<div class="ui sub header">Solicitante</div>
				<select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions read-only" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idSolicitante']) ? $cotizacion['idSolicitante'] : '']); ?>
				</select>
			</div>
			<div class="five wide field">
				<div class="ui sub header">Cuenta</div>
				<select class="ui search dropdown parentDependiente read-only" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
				</select>
			</div>
			<div class="six wide field">
				<div class="ui sub header">Centro de costo</div>
				<select class="ui search dropdown simpleDropdown childDependiente clearable read-only" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuentaCentroCosto']) ? $cotizacion['idCuentaCentroCosto'] : '']); ?>
				</select>
			</div>
		</div>
		<div class="fields">
			<div class="five wide field">
				<div class="ui sub header">Prioridad</div>
				<select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idPrioridad']) ? $cotizacion['idPrioridad'] : '']); ?>
				</select>
			</div>
			<div class="eleven wide field">
				<div class="ui sub header">
					Motivo <div class="ui btn-info-motivo btn-info-custom text-primary"><i class="info circle icon"></i></div>
				</div>
				<input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
			</div>

		</div>
		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Comentario</div>
				<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?></textarea>
			</div>
			<div class="eight wide field anexos">
				<div class="ui sub header">Anexos</div>
				<div class="ui small images content-lsck-capturas">
					<div class="content-lsck-galeria">
						<div class="ui small image text-center btn-add-file">
							<div class="ui dimmer">
								<div class="content">
									<div class="ui small primary button" onclick="$(this).parents('.anexos').find('.file-lsck-capturas-anexos').click();">
										Agregar
									</div>
								</div>
							</div>
							<img class="ui image" src="<?= IMG_WIREFRAME ?>">
						</div>
						<input type="file" name="capturas" class="file-lsck-capturas-anexos form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*" multiple="">
						<? foreach ($anexos as $anexo) { ?>
							<div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?>">
								<div class="ui dimmer dimmer-file-detalle">
									<div class="content">
										<p class="ui tiny inverted header"><?= $anexo['nombre_inicial'] ?></p>
									</div>
								</div>
								<a class="ui red right corner label img-lsck-anexos-delete"><i class="trash icon"></i></a>
								<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
								<input type="hidden" name="anexo-type" value="image/<?= $anexo['extension'] ?>">
								<input type="hidden" name="anexo-name" value="<?= $anexo['nombre_inicial'] ?>">
								<img height="100" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
							</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Ver precio PDF</div>
				<div class="ui basic floating dropdown button simpleDropdown w-100">
					<input type="hidden" name="flagMostrarPrecio" value="<?= !empty($cotizacion['flagMostrarPrecio']) ? $cotizacion['flagMostrarPrecio'] : 0 ?>" patron="requerido">
					<div class="text">Ver Precio PDF</div>
					<i class="dropdown icon"></i>
					<div class="menu">
						<div class="item" data-value="1">Ver precio</div>
						<div class="item" data-value="0">Ocultar Precio</div>
					</div>
				</div>
			</div>
		</div>
		<h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
		</h4>
		<div class="default-item">
			<? foreach ($cotizacionDetalle as $row) : ?>
				<input type="hidden" name="idCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>" id="">
				<div class="ui segment body-item nuevo" data-id="<?= $row['idCotizacionDetalle'] ?>">
					<div class="ui right floated header">

						<div class="ui icon menu">
							<a class="item btn-bloquear-detalle" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
								<i class="lock icon"></i>
							</a>
							<a class="item btn-eliminar-detalle btneliminarfila">
								<i class="trash icon"></i>
							</a>
						</div>

					</div>
					<div class="ui left floated header">
						<span class="ui medium text "><?= $row['item'] ?></span></span>
					</div>
					<div class="ui clearing divider"></div>
					<div class="ui grid">
						<div class="sixteen wide tablet twelve wide computer column">
							<div class="fields">

								<div class="six wide field">
									<div class="ui sub header">Item</div>
									<div class="ui-widget">
										<!-- <div class="ui icon input w-100">
                                            <input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>" readonly>
                                            <i class="semaforoForm flag link icon"></i>
                                        </div> -->

										<div class="ui right action left icon input w-100">
											<i class="semaforoForm flag link icon"></i>
											<input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>" readonly>
											<input type='hidden' name='nameItemOriginal' patron="requerido" placeholder="Buscar item" value="<?= $row['itemNombre'] ?>">
											<div class="ui basic floating flagCuentaSelect dropdown button simpleDropdown read-only">
												<input type="hidden" class="flagCuentaForm" name="flagCuenta" value="<?= !empty($row['flagCuenta']) ? $row['flagCuenta'] : 0 ?>" patron="requerido">
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
								<div class="five wide field">
									<div class="ui sub header">Tipo Item</div>
									<select class="ui dropdown simpleDropdown idTipoItem read-only" id="tipoItemForm" name="tipoItemForm" patron="requerido">
										<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Características para el cliente</div>
									<div class="ui right labeled input w-100">
										<input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' patron="requerido" value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Caracteristicas del item">
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="eight wide field">
									<div class="ui sub header">Características para compras</div>
									<input name="caracteristicasCompras" placeholder="Características" value="<?= !empty($row['caracteristicasCompras']) ? $row['caracteristicasCompras'] : '' ?>">
								</div>
								<div class="eight wide field">
									<div class="ui sub header">Características para proveedor</div>
									<input name="caracteristicasProveedor" placeholder="Características" value="<?= !empty($row['caracteristicasProveedor']) ? $row['caracteristicasProveedor'] : '' ?>">
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
												<div class="three wide field">
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
													<div class="ui sub header">Costo</div>
													<input class="onlyNumbers  costoSubItemTextil" name="costoTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0.00" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Subtotal</div>
													<input class="onlyNumbers  subtotalItemTextil" name="subtotalTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0.00" value="<?= !empty($dataSubItem['subtotal']) ? $dataSubItem['subtotal'] : '' ?>">
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
								<div class="content-body-sub-item">
									<?
									if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']])) :
										foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']] as $dataSubItem) : ?>
											<div class="fields body-sub-item body-sub-item-servicio">
												<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">

												<div class="eleven wide field">
													<div class="ui sub header">Sub item </div>
													<input class="nombreSubItem" name="nombreSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Nombre" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>">
												</div>
												<div class="five wide field">
													<div class="ui sub header">Cantidad</div>
													<input class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>
							</div>
							<!-- TRANSPORTE -->
							<?php if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TRANSPORTE['id']])) :  ?>
								<div class="div-features pb-5 div-feature-<?= COD_TRANSPORTE['id'] ?> <?= $row['idItemTipo'] == COD_TRANSPORTE['id'] ? '' : 'd-none' ?>">
									<div class="content-body-sub-item">
										<?php foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TRANSPORTE['id']] as $dataSubItem) : ?>
											<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
											<div class="fields body-sub-item body-sub-item-servicio" data-id="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="ten wide field">
													<div class="ui sub header">Descripción</div>
													<input class="nombreSubItem" name="nombreSubItemForm[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Nombre" value="<?= verificarEmpty($dataSubItem['nombre']) ?>">
												</div>
												<div class="five wide field">
													<div class="ui sub header">Costo</div>
													<input class="costoTransporte" name="costoSubItemForm[<?= $row['idCotizacionDetalle'] ?>]" placeholder="costo" value="<?= verificarEmpty($dataSubItem['costoSubItem']) ?>">
												</div>
												<div class="one wide field">
													<div class="ui sub header">Eliminar</div>
													<button type="button" class="ui basic button btn-eliminar-sub-item">
														<i class="trash icon"></i>
													</button>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
									<button type="button" class="ui basic button btn-add-sub-item">
										<i class="plus icon"></i>
										Agregar
									</button>
								</div>
							<?php endif; ?>
							<!-- Distribucion -->
							<div class="<?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>">
								<?
								if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']])) :
									foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']] as $dataSubItem) : ?>
										<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">

										<div class="fields ">
											<div class="six wide field">
												<div class="ui sub header">Tipo Servicio</div>
												<select class="ui search dropdown simpleDropdown tipoServicioForm tipoServicioSubItem" name="tipoServicioSubItem[<?= $row['idCotizacionDetalle'] ?>]">
													<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'selected' => $dataSubItem['idTipoServicio'], 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
												</select>
											</div>
											<div class="two wide field">
												<div class="ui sub header">Generar OC</div>
												<div class="ui test toggle checkbox checkValidarOC mt-2">
													<input class="checkForm generarOCSubItem" name="generarOCSubItem[<?= $row['idCotizacionDetalle'] ?>]" type="checkbox" onchange="Cotizacion.actualizarTotal();" <?= $dataSubItem['requiereOrdenCompra'] == '1' ? 'checked' : '' ?>>
												</div>
											</div>
											<div class="four wide field">
												<div class="ui sub header">Unidad de medida</div>
												<input class="unidadMedidaTipoServicio" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['unidadMedida']) ? $dataSubItem['unidadMedida'] : '' ?>" readonly>
												<input type="hidden" class="unidadMedidaSubItem" name="unidadMedidaSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Unidad Medida" value="<?= !empty($dataSubItem['idUnidadMedida']) ? $dataSubItem['idUnidadMedida'] : '' ?>" readonly>
											</div>
											<div class="four wide field">
												<div class="ui sub header">Costo S/</div>
												<input class="costoTipoServicio costoSubItem" name="costoSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Costo" value="<?= !empty($dataSubItem['costo']) ? $dataSubItem['costo'] : '' ?>" readonly>
											</div>
										</div>
										<div class="fields">
											<div class="eight wide field ">
												<div class="ui sub header">Item Logística</div>
												<select class="ui clearable search dropdown simpleDropdown itemLogisticaForm " name="itemLogisticaForm[<?= $row['idCotizacionDetalle'] ?>]">
													<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $itemLogistica, 'id' => 'value', 'value' => 'label', 'selected' => $dataSubItem['idItem'], 'class' => 'text-titlecase', 'data-option' => ['pesoLogistica']]); ?>
												</select>
											</div>
											<div class="four wide field">
												<div class="ui sub header">Peso</div>
												<input class="onlyNumbers cantidadSubItemDistribucion cantidadSubItem" name="cantidadSubItemDistribucion[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
											</div>
											<div class="four wide field">
												<div class="ui sub header">Cantidad PDV</div>
												<input class="onlyNumbers cantidadPdvSubItemDistribucion" name="cantidadPdvSubItemDistribucion[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" data-min="1" value="<?= !empty($dataSubItem['cantidadPdv']) ? $dataSubItem['cantidadPdv'] : '' ?>" onkeyup="$(this).closest('.nuevo').find('.cantidadForm').keyup()">
											</div>
										</div>
										<div class="<?= ($dataSubItem['requiereOrdenCompra'] == '0') ? 'd-none ' : ''; ?> fields divAddParaOC">
											<div class="eight wide field">
												<div class="ui sub header">Proveedor</div>
												<select class="ui clearable dropdown simpleDropdown proveedorDistribucionSubItem" name="proveedorDistribucionSubItem[<?= $row['idCotizacionDetalle'] ?>]">
													<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedorDistribucion, 'id' => 'idProveedor', 'value' => 'razonSocial', 'selected' => $dataSubItem['idProveedorDistribucion'], 'class' => 'text-titlecase' /*, 'data-option' => ['columnaAdicionalSegunLoRequerido']*/]); ?>
												</select>
											</div>
											<div class="four wide field">
												<div class="ui sub header">Peso Real</div>
												<input class="cantidadRealSubItem" name="cantidadRealSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad REAL" value="<?= $dataSubItem['cantidadReal'] ?>">
											</div>
											<!-- <div class="four wide field">
                                                <div class="ui sub header">Observación Adicional</div>
                                                <input class="observacionSubItemForm" name="observacion-NoGuarda[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Observación">
                                            </div> -->
										</div>
										<div class="tbDistribucionTachado <?= !empty($detalleTachado[$dataSubItem['idItem']]) ? '' : 'd-none' ?>">
											<h4 class="ui dividing header">TACHADO</h4>
											<input value='0' class='chkTachadoDistribucion d-none' type="radio" name="chkTachado[<?= $row['idCotizacionDetalle'] ?>]" <?= !empty($dataSubItem['idDistribucionTachado']) ? '' : 'checked' ?>>
											<table class="ui single line table">
												<thead>
													<tr>
														<th></th>
														<th class="thCustomNameItem"></th>
														<th>Tiempo tachado (días)</th>
														<th>Personas para tachado</th>
														<th>Costo por día</th>
														<th>Total de costo</th>
													</tr>
												</thead>
												<tbody>
													<? if (!empty($detalleTachado[$dataSubItem['idItem']])) : ?>
														<? foreach ($detalleTachado[$dataSubItem['idItem']] as $tachado) :
															$subTotalTachado = (($tachado['dias'] * $tachado['personas']) * $tachado['costoDia']);
														?>
															<tr data-id="<?= $tachado['idDistribucionTachado'] ?>" data-subtotal="<?= $subTotalTachado ?>">
																<td>
																	<div class="ui radio checkbox dvTachadoDistribucion">
																		<input value="<?= $tachado['idDistribucionTachado'] ?>" class='chkTachadoDistribucion ' type="radio" name="chkTachado[<?= $row['idCotizacionDetalle'] ?>]" <?= $tachado['idDistribucionTachado'] == $dataSubItem['idDistribucionTachado'] ? 'checked' : '' ?>>
																		<label></label>
																	</div>
																</td>
																<td> <?= verificarEmpty($tachado['limiteInferior'], 3) . ' - ' . verificarEmpty($tachado['limiteSuperior'], 3) ?></td>
																<td> <?= verificarEmpty($tachado['dias'], 3) ?></td>
																<td> <?= verificarEmpty($tachado['personas'], 3) ?></td>
																<td> <?= !empty($tachado['costoDia']) ? moneda($tachado['costoDia']) : 0 ?></td>
																<td> <?= moneda($subTotalTachado) ?></td>
															</tr>
														<? endforeach; ?>
													<? endif; ?>
												</tbody>
											</table>
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
							<div class="content-lsck-capturas divImagenesDeLaCotizacion">
								<div class="sixteen wide field">
									<?php if (!empty($cotizacionDetalleArchivosDelProveedor[$row['idCotizacionDetalle']])) :  ?>
										<div class="ui small images">
											<?php foreach ($cotizacionDetalleArchivosDelProveedor[$row['idCotizacionDetalle']] as $kAE => $vAE) : ?>
												<div class="ui fluid image dimmable" data-id="<?= $kAE ?>">
													<?php $src = RUTA_WIREFRAME . "file.png"; ?>

													<?php $src = $vAE['idTipoArchivo'] == TIPO_PDF ? RUTA_WIREFRAME . "pdf.png" : $src; ?>
													<?php $src = $vAE['idTipoArchivo'] == TIPO_EXCEL ? RUTA_WIREFRAME . "xlsx.png" : $src; ?>
													<?php $src = $vAE['idTipoArchivo'] == TIPO_IMAGEN ? (RUTA_WASABI . 'cotizacionProveedor/' . $vAE['nombre_archivo']) : $src; ?>

													<a target="_blank" href="<?= RUTA_WASABI . 'cotizacionProveedor/' . $vAE['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
													<img height="100" src="<?= $src; ?>" class="img-responsive img-thumbnail">
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="content-lsck-capturas">
								<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*,.pdf" multiple="">
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
															<input type="hidden" name="file-item[<?= $row['idCotizacionDetalle'] ?>]" value="">
															<input type="hidden" name="file-type[<?= $row['idCotizacionDetalle'] ?>]" value="image/<?= $archivo['extension'] ?>">
															<input type="hidden" name="file-name[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
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
													if ($archivo['idTipoArchivo'] == TIPO_PDF || $archivo['idTipoArchivo'] == TIPO_OTROS) { ?>
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
									<div class="ui sub header">Cantidad de Elementos</div>
									<input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" patron="requerido,numerico" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Costo</div>
									<!-- <div class="ui right labeled input">
                                        <label for="amount" class="ui label">S/</label>
                                        <input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo']) : '' ?>" readonly>
                                        <input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
                                    </div> -->
									<div class="ui right action right labeled input">
										<label for="amount" class="ui label">S/</label>
										<input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo']) : '' ?>" readonly>
										<input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="eight wide field">
									<div class="ui sub header">GAP %</div>
									<div class="ui right labeled input">
										<input data-max='100' data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($row['gap']) ? $row['gap'] : '' ?>">
										<div class="ui basic label">
											%
										</div>
									</div>
								</div>
								<div class="eight wide field">
									<div class="ui sub header">Precio</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label">S/</label>
										<input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" readonly>
										<input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>" readonly>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Subtotal</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label teal">S/</label>
										<input class=" subtotalFormLabel" type="text" placeholder="0.00" patron="requerido" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal']) : '' ?>" readonly>
										<input class=" subtotalForm" type="hidden" patron="requerido" name="subtotalForm" placeholder="0.00" value="<?= !empty($row['subtotal']) ? ($row['subtotal']) : '' ?>" readonly>

										<input type="hidden" class="costoRedondeadoForm" name="costoRedondeadoForm" placeholder="0" value="0">
										<input type="hidden" class="costoNoRedondeadoForm" name="costoNoRedondeadoForm" placeholder="0" value="0">
										<div class="ui basic floating dropdown button simpleDropdown ">
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
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<div class="ui black three column center aligned stackable divided grid segment">
			<div class="column">
				<div class="ui test toggle checkbox ">
					<input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();" <?= $cotizacion['igv'] ? 'checked' : '' ?>>
					<label>Incluir IGV</label>
				</div>
			</div>
			<div class="column">
				<!-- <div class="ui sub header">Total</div> -->
				<div class="ui right labeled input">
					<label for="feeForm" class="ui label">Fee: </label>
					<input data-max='100' data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();">
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
			<span class="float-element tooltip-left btn-send" data-message="Enviar" onclick="Cotizacion.frmSendToCliente();">
				<i class="send icon"></i>
			</span>
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