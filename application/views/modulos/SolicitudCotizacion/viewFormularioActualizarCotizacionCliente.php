<!-- <div class="ui attached message">
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
		<input type="hidden" id="idCotizacion" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
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
						<input id="deadLineCompra" type="text" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Fecha requerida</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input id="fechaRequerida" type="text" placeholder="Fecha Requerida" value="<?= !empty($cotizacion['fechaRequerida']) ? $cotizacion['fechaRequerida'] : '' ?>">
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
				<select id="solicitante" name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions read-only" patron="requerido">
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
			
			<div class="three wide field">
				<div class="ui sub header">Tipo Servicio</div>
				<select class="ui dropdown semantic-dropdown read-only" id="tipoServicioCotizacion" name="tipoServicioCotizacion" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicioCotizacion, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idTipoServicioCotizacion']) ? $cotizacion['idTipoServicioCotizacion'] : '']); ?>
				</select>
			</div>
			<div class="three wide field">
				<div class="ui sub header">Tipo Moneda</div>
				<select class="ui dropdown semantic-dropdown read-only" id="tipoMoneda" name="tipoMoneda" patron="requerido" onchange="Cotizacion.SimboloMoneda(this)">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoMoneda, 'class' => 'text-titlecase','selected' => !empty($cotizacion['idTipoMoneda']) ? $cotizacion['idTipoMoneda'] : '']); ?>
				</select>
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
					<input type="hidden" id="flagMostrarPrecio" name="flagMostrarPrecio" value="<?= !empty($cotizacion['flagMostrarPrecio']) ? $cotizacion['flagMostrarPrecio'] : 0 ?>" patron="requerido">
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
							<a class="item chk-itemTextoPdf" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); $(this).find('i').hasClass('check square') ? $(this).closest('.body-item').find('.itemTextoPdf').removeClass('d-none') : $(this).closest('.body-item').find('.itemTextoPdf').addClass('d-none');">
								<i class="icon square <?= $row['flagAlternativo'] ? 'check' : 'outline'; ?>"></i>
								<input type="checkbox" name="chkItemTextoPdf" class="d-none checkItemTextoPdf" <?= $row['flagAlternativo'] ? 'checked' : ''; ?>>
							</a>
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
										<div class="ui right action left icon input w-100">
											<i class="semaforoForm flag link icon"></i>
											<input class="items" type='text' id="nameItem" name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>" readonly>
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
									<div class="ui-widget">
										<input class="itemTextoPdf <?= !$row['flagAlternativo'] ? 'd-none' : ''; ?>" type='text' name='itemTextoPdf' placeholder="Descripción de Item para Cotización" value="<?= $row['nombreAlternativo']; ?>">
									</div>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Tipo Item</div>
									<select class="ui dropdown simpleDropdown idTipoItem read-only" id="tipoItemForm" name="tipoItemForm" patron="requerido">
										<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="four wide field divTipoTarjValesConcurso <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] || $row['idItemTipo'] == COD_CONCURSO['id'] ? '' : 'd-none' ?>">
									<div class="ui sub header">Tipo</div>
									<?php $tipo_vt = verificarEmpty($row['idTipo_TarjetasVales']); #Tambien es para concursos pero la columna ya tiene ese nombre :) ?>

									<select class="ui fluid clearable dropdown simpleDropdown" name="tipoTarjVales">
										<option class="text-titlecase" value <?= empty($tipo_vt) ? 'selected' : ''; ?>>Seleccione</option>
										<option class="text-titlecase" value="1" <?= $tipo_vt == '1' ? 'selected' : ''; ?>>COMPRA</option>
										<option class="text-titlecase" value="2" <?= $tipo_vt == '2' ? 'selected' : ''; ?>>RECARGA</option>
									</select>
								</div>
								
							</div>
							<div class="fields">
								<div class="five wide field">
									<div class="ui sub header">Características para el cliente</div>
									<div class="ui labeled input w-100">
										<input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' patron="requerido" value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Caracteristicas del item">
									</div>
								</div>
								<div class="six wide field <?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? 'd-none' : '' ?>">
									<div class="ui sub header">Características para compras</div>
									<input id="caracteristicasCompras" name="caracteristicasCompras" placeholder="Características" value="<?= !empty($row['caracteristicasCompras']) ? $row['caracteristicasCompras'] : '' ?>">
								</div>
								<div class="five wide field divTipoTarjValesConcurso <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] || $row['idItemTipo'] == COD_CONCURSO['id'] ? '' : 'd-none' ?>">
									<div class="ui sub header">Proveedor</div>
									<select class="ui fluid search clearable dropdown simpleDropdown provList" onchange="$(this).closest('.body-item').find('.idProveedor').val(this.value);">
										<?= htmlSelectOptionArray2(['query' => $listProveedor, 'id' => 'idProveedor', 'value' => 'razonSocial', 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione', 'selected' => verificarEmpty($row['idProveedor'])]); ?>
									</select>
								</div>
								<div class="five wide field <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] || $row['idItemTipo'] == COD_CONCURSO['id'] ? 'd-none' : '' ?>">
									<div class="ui sub header">Características para proveedor</div>
									<input id="caracteristicasProveedor" name="caracteristicasProveedor" placeholder="Características" value="<?= !empty($row['caracteristicasProveedor']) ? $row['caracteristicasProveedor'] : '' ?>">
								</div>
							</div>
							<div class="fields cantPDV <?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?>">
								<div class="three wide field">
									<div class="ui sub header">Cant. PDV</div>
									<input class="cantidadPDV" name="cantidadPDV" onkeyup="$(this).closest('.nuevo').find('.cantidadForm').keyup()" value="<?= verificarEmpty($row['cantPdv']); ?>">
								</div>
								<div class="three wide field">
									<div class="ui sub header">¿ Requiere OC ?</div>
									<select class="ui basic floating dropdown button simpleDropdown" name="flagGenerarOC">
										<option value="0" <?= $row['requiereOrdenCompra'] == '0' ? 'selected' : ''; ?>>NO generar</option>
										<option value="1" <?= $row['requiereOrdenCompra'] == '1' ? 'selected' : ''; ?>>Generar OC</option>
									</select>
								</div>
								<div class="three wide field">
									<div class="ui sub header">Tabla Detalle</div>
									<select class="ui basic floating dropdown button simpleDropdown" name="flagMostrarDetalle">
										<option value="0" <?= $row['flagMostrarDetalle'] == '0' ? 'selected' : ''; ?>>Ocultar</option>
										<option value="1" <?= $row['flagMostrarDetalle'] == '1' ? 'selected' : ''; ?>>Mostrar</option>
									</select>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Almacén</div>
									<select class="ui basic floating dropdown button simpleDropdown flagOtrosPuntos" name="flagOtrosPuntos">
										<?php $slctFlagOtrosPts = 0; ?>
										<?php
										if (isset($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']][0]['flagOtrosPuntos']))
											if ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_DISTRIBUCION['id']][0]['flagOtrosPuntos'] == '1')
												$slctFlagOtrosPts = 1;
										?>
										<option value="0" <?= $slctFlagOtrosPts === 0 ? 'selected' : ''; ?>>Almacén Visual</option>
										<option value="1" <?= $slctFlagOtrosPts === 1 ? 'selected' : ''; ?>>Otros Puntos</option>
									</select>
								</div>
							</div>
							<div class="fields cantPDV <?= $row['idItemTipo'] == COD_DISTRIBUCION['id'] ? '' : 'd-none' ?>">
								<div class="three wide field">
									<div class="ui sub header">Costo Packing</div>
									<select name="flagPackingSolicitado" onchange="$('.divCostoPacking').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
										<option value="0" <?= $row['flagPackingSolicitado'] == '0' ? 'selected' : ''; ?>>No requerido</option>
										<option value="1" <?= $row['flagPackingSolicitado'] == '1' ? 'selected' : ''; ?>>Requerido</option>
									</select>
								</div>
								<div class="two wide field divCostoPacking <?= $row['flagPackingSolicitado'] == '1' ? '' : 'd-none'; ?>">
									<div class="ui sub header">.</div>
									<input class="onlyNumbers costoPacking" name="costoPacking" value="<?= $row['costoPacking']; ?>" readonly>
								</div>
								<div class="three wide field">
									<div class="ui sub header">Costo Movilidad</div>
									<select name="flagMovilidadSolicitado" onchange="$('.divCostoMovilidad').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
										<option value="0" <?= $row['flagMovilidadSolicitado'] == '0' ? 'selected' : ''; ?>>No requerido</option>
										<option value="1" <?= $row['flagMovilidadSolicitado'] == '1' ? 'selected' : ''; ?>>Requerido</option>
									</select>
								</div>
								<div class="two wide field divCostoMovilidad <?= $row['flagMovilidadSolicitado'] == '1' ? '' : 'd-none'; ?>">
									<div class="ui sub header">.</div>
									<input class="onlyNumbers costoMovilidad" name="costoMovilidad" value="<?= $row['costoMovilidad']; ?>" readonly>
								</div>
								<div class="three wide field">
									<div class="ui sub header">Costo Personal</div>
									<select name="flagPersonalSolicitado" onchange="$('.divCostoPersonal').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
										<option value="0" <?= $row['flagPersonalSolicitado'] == '0' ? 'selected' : ''; ?>>No requerido</option>
										<option value="1" <?= $row['flagPersonalSolicitado'] == '1' ? 'selected' : ''; ?>>Requerido</option>
									</select>
								</div>
								<div class="two wide field divCostoPersonal <?= $row['flagPersonalSolicitado'] == '1' ? '' : 'd-none'; ?>">
									<div class="ui sub header">.</div>
									<input class="onlyNumbers costoPersonal" name="costoPersonal" value="<?= $row['costoPersonal']; ?>" readonly>
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
												<div class="three wide field">
													<div class="ui sub header">Genero</div>
													<input type="hidden" class="generoSubItem " name="generoSubItem[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Genero" value="<?= !empty($dataSubItem['genero']) ? $dataSubItem['genero'] : '' ?>" readonly>
													<input class="colorSubItem " placeholder="Genero" value="<?= !empty($dataSubItem['genero']) ? RESULT_GENERO[$dataSubItem['genero']] : '' ?>" readonly>
												</div>
												<div class="two wide field">
													<div class="ui sub header">Cantidad</div>
													<input class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>">
												</div>
												<div class="two wide field">
													<div class="ui sub header">Costo</div>
													<input class="onlyNumbers costoSubItemTextil" name="costoTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0.00" value="<?= !empty($dataSubItem['costoSubItem']) ? $dataSubItem['costoSubItem'] : '' ?>">
												</div>
												<div class="three wide field">
													<div class="ui sub header">Subtotal</div>
													<input class="onlyNumbers subtotalItemTextil" name="subtotalTextil[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0.00" value="<?= !empty($dataSubItem['subtotal']) ? $dataSubItem['subtotal'] : '' ?>">
												</div>
											</div>
									<?
										endforeach;
									endif;
									?>
								</div>
							</div>
							<!-- Monto -->
							<div class="ui grid ml-0 div-features <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
								<div class="row ml-0 pt-4 d-none"> <!-- No se muestra los botones de agregar y eliminar, pendiente corregir error -->
									<button type="button" class="ui button btn-add-sub-item-tarjVales teal ">
										<i class="plus icon"></i>
										Agregar
									</button>
									<button type="button" class="ui button btn-delete-sub-item-tarjVales red">
										<i class="trash icon"></i>
										Eliminar
									</button>
								</div>
								<? if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']])) : ?>
									<? foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TARJETAS_VALES['id']] as $dataSubItem) : ?>
										<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
										<div class="three column row divDetalleTarjVales">
											<div class="column">
												<div class="ui sub header">Descripción</div>
												<input class="descripcionSubItemTarjVal" name="descripcionSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Descripción" value="<?= $dataSubItem['nombre'] ?>">
											</div>
											<div class="column">
												<div class="ui sub header">Cantidad</div>
												<input class="cantidadSubItemTarjVal keyUpChange onlyNumbers" name="cantidadSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" onchange="Cotizacion.calcularMontoTarjetasVales(this);" value="<?= $dataSubItem['cantidad'] ?>">
											</div>
											<div class="column">
												<div class="ui sub header">Monto</div>
												<input class="montoSubItemTarjVal keyUpChange onlyNumbers" name="montoSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Monto" onchange="Cotizacion.calcularMontoTarjetasVales(this);" value="<?= $dataSubItem['costoSubItem'] ?>">
											</div>
										</div>
									<? endforeach; ?>
								<? endif; ?>
							</div>
							<!-- Monto S/ -->
							<!-- <div class="fields <?= $row['idItemTipo'] == COD_TARJETAS_VALES['id'] ? '' : 'd-none' ?> div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
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
							</div> -->
							<!-- Concurso -->
							<div class="ui grid ml-0 div-features <?= $row['idItemTipo'] == COD_CONCURSO['id'] ? '' : 'd-none' ?> div-feature-<?= COD_CONCURSO['id'] ?>">
								<div class="row ml-0 pt-4 d-none"> <!-- No se muestra los botones de agregar y eliminar, pendiente corregir error -->
									<button type="button" class="ui button btn-add-sub-item-concurso teal ">
										<i class="plus icon"></i>
										Agregar
									</button>
									<button type="button" class="ui button btn-delete-sub-item-concurso red">
										<i class="trash icon"></i>
										Eliminar
									</button>
								</div>
								<? if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_CONCURSO['id']])) : ?>
									<? foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_CONCURSO['id']] as $dataSubItem) : ?>
										<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
										<div class="four column row divDetalleConcurso">
											<div class="column">
												<div class="ui sub header">Descripción</div>
												<input class="descripcionSubItemConcurso" name="descripcionSubItemConcurso[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Descripción" value="<?= $dataSubItem['nombre'] ?>">
											</div>
											<div class="column">
												<div class="ui sub header">Cantidad</div>
												<input class="cantidadSubItemConcurso keyUpChange onlyNumbers" name="cantidadSubItemConcurso[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" value="<?= $dataSubItem['cantidad'] ?>" onchange="Cotizacion.calcularMontoConcurso(this);">
											</div>
											<div class="column">
												<div class="ui sub header">Monto</div>
												<input class="montoSubItemConcurso keyUpChange onlyNumbers" name="montoSubItemConcurso[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Monto" value="<?= $dataSubItem['costoSubItem'] ?>" onchange="Cotizacion.calcularMontoConcurso(this);">
											</div>
											<div class="column">
												<div class="ui sub header">Porcentaje</div>
												<input class="porcentajeSubItemConcurso keyUpChange onlyNumbers" name="porcentajeSubItemConcurso[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Porcentaje" value="<?= $dataSubItem['porcentajeParaCosto'] ?>" onchange="Cotizacion.calcularMontoConcurso(this);">
											</div>
											<!--
											<div class="column">
												<div class="ui sub header">Descripción</div>
												<input class="descripcionSubItemTarjVal" name="descripcionSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Descripción" value="<?= $dataSubItem['nombre'] ?>">
											</div>
											<div class="column">
												<div class="ui sub header">Cantidad</div>
												<input class="cantidadSubItemTarjVal keyUpChange onlyNumbers" name="cantidadSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Cantidad" onchange="Cotizacion.calcularMontoTarjetasVales(this);" value="<?= $dataSubItem['cantidad'] ?>">
											</div>
											<div class="column">
												<div class="ui sub header">Monto</div>
												<input class="montoSubItemTarjVal keyUpChange onlyNumbers" name="montoSubItemTarjVal[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Monto" onchange="Cotizacion.calcularMontoTarjetasVales(this);" value="<?= $dataSubItem['costoSubItem'] ?>">
											</div>
											-->
										</div>
									<? endforeach; ?>
								<? endif; ?>
							</div>
							<!-- Servicios -->
							<div class="ui form attached fluid segment my-3 <?= $row['idItemTipo'] == COD_SERVICIO['id'] ? '' : 'd-none' ?> div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
								<h4 class="ui dividing header">SUB ITEMS</h4>
								<div class="content-body-sub-item">
									<?php if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']])) : ?>
										<?php $var1 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['sucursal']; ?>
										<?php $var2 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['razonSocial']; ?>
										<?php $var3 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['tipoElemento']; ?>
										<?php $var4 = $cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']][0]['marca']; ?>
										<?php $costoTotal = 0; ?>
										<?php $costoTotalRedondeado = 0; ?>
										<?php foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_SERVICIO['id']] as $dataSubItem) : ?>
											<?php if (!($var1 == $dataSubItem['sucursal'] && $var2 == $dataSubItem['razonSocial'] && $var3 == $dataSubItem['tipoElemento'] && $var4 == $dataSubItem['marca'])) : ?>
												<?php $var1 = $dataSubItem['sucursal']; ?>
												<?php $var2 = $dataSubItem['razonSocial']; ?>
												<?php $var3 = $dataSubItem['tipoElemento']; ?>
												<?php $var4 = $dataSubItem['marca']; ?>
												<div class="fields">
													<div class="field fourteen wide ui transparent input">
														<input readonly="readonly" class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
													</div>
													<div class="field two wide ui transparent input">
														<input readonly="readonly" class="subtotalSubItemCT" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
													</div>
												</div>
												<hr class="solid">
												<?php $costoTotal = 0; ?>
												<?php $costoTotalRedondeado = 0; ?>
											<?php endif; ?>
											<?php $costoTotal += floatval($dataSubItem['subtotal']) ?>
											<?php $costoTotalRedondeado += ceil(floatval($dataSubItem['subtotal'])) ?>
											<div class="fields body-sub-item body-sub-item-servicio">
												<input class="idCotizacionDetalleSubForm" type="hidden" name="idCotizacionDetalleSub[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $dataSubItem['idCotizacionDetalleSub'] ?>">
												<div class="five wide field">
													<div class="ui sub header">Sucursal </div>
													<input class="sucursalSubItem" name="sucursalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Sucursal" value="<?= !empty($dataSubItem['sucursal']) ? $dataSubItem['sucursal'] : '' ?>" readonly>
												</div>
												<div class="five wide field">
													<div class="ui sub header">Razón Social </div>
													<input class="razonSocialSubItem" name="razonSocialSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Razón Social" value="<?= !empty($dataSubItem['razonSocial']) ? $dataSubItem['razonSocial'] : '' ?>" readonly>
												</div>
												<div class="five wide field">
													<div class="ui sub header">Marca </div>
													<input class="marcaSubItem" name="marcaSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Marca" value="<?= !empty($dataSubItem['marca']) ? $dataSubItem['marca'] : '' ?>" readonly>
												</div>
												<div class="five wide field">
													<div class="ui sub header">Tipo Elemento </div>
													<input class="tipoElementoSubItem" name="tipoElementoSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Tipo Elemento" value="<?= !empty($dataSubItem['tipoElemento']) ? $dataSubItem['tipoElemento'] : '' ?>" readonly>
												</div>
												<div class="six wide field">
													<div class="ui sub header">Descripción </div>
													<input class="nombreSubItem" name="nombreSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="Nombre" value="<?= !empty($dataSubItem['nombre']) ? $dataSubItem['nombre'] : '' ?>" readonly>
												</div>
												<div class="three wide field">
													<div class="ui sub header">Cantidad</div>
													<input class="onlyNumbers cantidadSubItem" name="cantidadSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['cantidad']) ? $dataSubItem['cantidad'] : '' ?>" readonly>
												</div>
												<div class="three wide field">
													<div class="ui sub header">Costo</div>
													<input class="onlyNumbers costoSubItem" name="costoSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['costoSubItem']) ? $dataSubItem['costoSubItem'] : '' ?>" readonly>
												</div>
												<div class="four wide field">
													<div class="ui sub header">Sub Total</div>
													<input class="onlyNumbers subtotalSubItem d-none" name="subtotalSubItemServicio[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= !empty($dataSubItem['subtotal']) ? $dataSubItem['subtotal'] : '0' ?>" readonly>
													<input class="subtotalSubItemGap" value="<?= !empty($dataSubItem['subtotal']) ? $dataSubItem['subtotal'] : '0' ?>" readonly>
												</div>
											</div>
										<?php endforeach; ?>
										<div class="fields">
											<div class="field fourteen wide ui transparent input">
												<input readonly="readonly" class="text-right" value="SUBTOTAL" readonly style="font-size: 20px;">
											</div>
											<div class="field two wide ui transparent input">
												<input readonly="readonly" class="subtotalSubItemCT" value="<?= $costoTotal; ?>" readonly style="font-size: 20px;">
											</div>
										</div>
										<hr class="solid">
									<?php endif; ?>
								</div>
							</div>
							<!-- TRANSPORTE -->
							<?php if (!empty($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TRANSPORTE['id']])) : ?>
								<div class="div-features pb-5 div-feature-<?= COD_TRANSPORTE['id'] ?> <?= $row['idItemTipo'] == COD_TRANSPORTE['id'] ? '' : 'd-none' ?>">
									<div class="content-body-sub-item">
										<?php foreach ($cotizacionDetalleSub[$row['idCotizacionDetalle']][COD_TRANSPORTE['id']] as $dataSubItem) : ?>
											<div class="body-sub-item body-sub-item-servicio">
												<div class="fields">
													<div class="four wide field">
														<div class="ui sub header">Departamento</div>
														<select class="ui simpleDropdown depT formTransporte departamento_transporte" name="departamentoTransporte[<?= $row['idCotizacionDetalle'] ?>]" onchange="Cotizacion.buscarProvincias(this);">
															<?= htmlSelectOptionArray2([
																'title' => 'Seleccione', 'id' => 'cod_departamento',
																'value' => 'departamento', 'query' => $departamento, 'class' => 'text-titlecase',
																'selected' => $dataSubItem['cod_departamento']
															]); ?>
														</select>
													</div>
													<div class="four wide field">
														<div class="ui sub header">Provincia</div>
														<select class="ui simpleDropdown provT formTransporte provincia_transporte" name="provinciaTransporte[<?= $row['idCotizacionDetalle'] ?>]" onchange="Cotizacion.buscarDistritos(this);">
															<option value="<?= $dataSubItem['cod_provincia'] ?>"><?= $dataSubItem['provincia'] ?></option>
														</select>
													</div>
													<div class="four wide field">
														<div class="ui sub header">Distrito</div>
														<select class="ui simpleDropdown disT formTransporte distrito_transporte" name="distritoTransporte[<?= $row['idCotizacionDetalle'] ?>]" onchange="Cotizacion.buscarTipoTransporte(this);">
															<option value="<?= $dataSubItem['cod_distrito'] ?>"><?= $dataSubItem['distrito'] ?></option>
														</select>
													</div>
													<div class="four wide field">
														<div class="ui sub header">Tipo</div>
														<select class="ui simpleDropdown tipoT formTransporte tipoTransporte_transporte" name="tipoTransporte[<?= $row['idCotizacionDetalle'] ?>]" onchange="Cotizacion.buscarCosto(this);">
															<option value="<?= $dataSubItem['idTipoServicioUbigeo'] ?>"><?= $dataSubItem['tipoServicioUbigeo'] ?></option>
														</select>
													</div>
												</div>
												<div class="fields">
													<div class="three wide field">
														<div class="ui sub header">Csto Visual</div>
														<input class="inpCostoVisual formTransporte costoVisual_transporte onlyNumbers" name="costoVisualTransporte[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= $dataSubItem['costoVisual'] ?>" readonly>
													</div>
													<div class="two wide field">
														<div class="ui sub header">% Adic.</div>
														<div class="ui right labeled input">
															<input class="inpPorcTransporte keyUpChange formTransporte onlyNumbers" name="porcAdicionalTransporte[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= $dataSubItem['porcentajeParaCosto'] ?>" onchange="Cotizacion.calcularValorTransporte(this);">
															<div class="ui basic label">
																%
															</div>
														</div>
													</div>
													<div class="three wide field">
														<div class="ui sub header">Csto Cliente</div>
														<input class="inpCosto formTransporte costoCliente_transporte onlyNumbers" name="costoClienteTransporte[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= $dataSubItem['costoSubItem'] ?>" onchange="Cotizacion.calcularValorTransporte(this);" readonly>
													</div>
													<div class="two wide field">
														<div class="ui sub header">Días</div>
														<input class="formTransporte dias_transporte keyUpChange onlyNumbers" name="diasTransporte[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= $dataSubItem['dias'] ?>" onchange="Cotizacion.calcularValorTransporte(this);">
													</div>
													<div class="two wide field">
														<div class="ui sub header">Moviles</div>
														<input class="formTransporte cantidad_transporte keyUpChange onlyNumbers" name="cantidadTransporte[<?= $row['idCotizacionDetalle'] ?>]" placeholder="0" value="<?= $dataSubItem['cantidad'] ?>" onchange="Cotizacion.calcularValorTransporte(this);">
													</div>
													<div class="two wide field">
														<div class="ui sub header">Eliminar</div>
														<button type="button" class="ui button btn-eliminar-sub-item red">
															<i class="trash icon"></i>
														</button>
													</div>
												</div>
												<div class="ui divider"></div>
											</div>
										<?php endforeach; ?>
									</div>
									<button type="button" class="ui button btn-add-sub-item teal">
										<i class="plus icon"></i>
										Agregar
									</button>
								</div>
							<?php endif; ?>
							<div class="fields datosTable pt-5">
								<?= $tablaGen[$row['idCotizacionDetalle']]; ?>
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
									<?php if (!empty($cotizacionDetalleArchivosDelProveedor[$row['idCotizacionDetalle']])) : ?>
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
									<input class="form-control cantidadForm" id="cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" patron="requerido,numerico" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
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
										<label for="amount" class="ui label"><?= $cotizacion['idTipoMoneda'] == 1 ? 'S/' : '$' ?></label>
										<input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo'], false, 4) : '' ?>" readonly>
										<input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="eight wide field">
									<div class="ui sub header">GAP %</div>
									<div class="ui right labeled input">
										<input data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= empty($row['gap']) ? '0' : $row['gap']; ?>">
										<div class="ui basic label">
											%
										</div>
									</div>
								</div>
								<div class="eight wide field">
									<div class="ui sub header">Precio</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label"><?= $cotizacion['idTipoMoneda'] == 1 ? 'S/' : '$' ?></label>
										<input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" readonly>
										<input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>" readonly>
									</div>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Subtotal</div>
									<div class="ui right labeled input">
										<label for="amount" class="ui label teal"><?= $cotizacion['idTipoMoneda'] == 1 ? 'S/' : '$' ?></label>
										<input class=" subtotalFormLabel" type="text" placeholder="0.00" patron="requerido" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal'], false, 4) : '' ?>" readonly>
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
		<div class="ui black four column center aligned stackable divided grid segment">
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
					<input data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();">
					<div class="ui basic label">
						%
					</div>
				</div>
			</div>
			<div class="column">
				<!-- <div class="ui sub header">Total</div> -->
				<div class="ui right labeled input">
					<label for="feeForm3" class="ui label">Fee: </label>
					<input data-min='0' type="number" id="feeForm3" class="onlyNumbers" name="feeForm3" placeholder="Fee" value="<?= !empty($cotizacion['feeTarjetaVales']) ? $cotizacion['feeTarjetaVales'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();">
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