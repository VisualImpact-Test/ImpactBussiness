<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

	.btn-info-custom {
		cursor: pointer;
		display: inline-block;
		line-height: 1;
	}

	input[type="color"] {
		padding: initial !important;
	}

	.floating-container {
		height: 275px !important;
	}

	.plomo {
		color: #d2d2d2 !important;
	}
</style>
<div class="ui form attached fluid segment p-4">
	<form class="ui form" role="form" id="formRegistroRequerimientoInterno" method="post" autocomplete="off">
		<h4 class="ui dividing header">DATOS DE LA SOLICITUD DE REQUERIMIENTO INTERNO</h4>
		<div class="fields">
			<div class="three wide field">
				<div class="ui sub header">Título</div>
				<input id="nombre" name="nombre" patron="requerido" placeholder="Título del Requerimiento" patron="requerido">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Cuenta</div>
				<select class="ui search clearable dropdown semantic-dropdown centro-visible parentDependienteSemantic" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="#cuentaCentroCostoForm">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="five wide field">
				<div class="ui sub header">Centro de costo</div>
				<select class="ui search dropdown clearable semantic-dropdown centro-ocultado childdependienteSemantic" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="three wide field">
				<div class="ui sub header">Aprobación</div>
				<select class="ui search clearable dropdown semantic-dropdown centro-visible" id="aprobacionForm" name="aprobacionForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $usuarioAprobar, 'simple' => true, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="two wide field">
				<div class="ui sub header">Tipo Moneda</div>
				<select class="ui dropdown semantic-dropdown" id="tipoMoneda" name="tipoMoneda" patron="requerido" onchange="RequerimientoInterno.SimboloMoneda(this)">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoMoneda, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<!--<div class="five wide field">
				<div class="ui sub header">Fecha requerida</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Fecha Requerida" value="" patron="requerido">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="">
			</div>
			<div class="five wide field">
				<div class="ui sub header">Prioridad</div>
				<select class="ui dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
					< ?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridad, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="two wide field">
				<div class="ui sub header">
					Validez <div class="ui btn-info-validez btn-info-custom text-primary"><i class="info circle icon"></i></div>
				</div>
				<input class="onlyNumbers" id="diasValidez" name="diasValidez" patron="requerido" placeholder="Días de validez">
			</div>
		</div>
		<div class="fields">
			<div class="four wide field">
				<div class="ui sub header">Motivo</div>
				<input id="motivoForm" name="motivoForm" placeholder="Motivo">
			</div>-->
		</div>
		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Comentario</div>
				<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?></textarea>
			</div>
		</div>
		<h4 class="ui dividing header">DETALLE DEL REQUERIMIENTO <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
		</h4>
		<div class="default-item">
			<div class="ui segment body-item nuevo">
				<div class="ui right floated header">
					<div class="ui icon menu">
						<!--<a class="item chk-itemTextoPdf" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); $(this).find('i').hasClass('check square') ? $(this).closest('.body-item').find('.itemTextoPdf').removeClass('d-none') : $(this).closest('.body-item').find('.itemTextoPdf').addClass('d-none');">
							<i class="icon square outline"></i>
							<input type="checkbox" name="chkItemTextoPdf" class="checkItemTextoPdf d-none">
						</a>-->
						<a class="item btn-bloquear-detalle" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
							<i class="unlock icon"></i>
						</a>
						<a class="item btn-eliminar-detalle btneliminarfila">
							<i class="trash icon"></i>
						</a>
					</div>
				</div>
				<div class="ui left floated header">
					<span class="ui medium text ">Detalle N. <span class="title-n-detalle">00001</span></span>
				</div>
				<div class="ui clearing divider"></div>
				<div class="ui grid">
					<div class="columna_itemss sixteen wide tablet twelve wide computer column itemDet_1">
						<div class="fields">
							<div class="seven wide field">
								<div class="ui sub header">Item</div>
								<div class="ui-widget">
									<div class="ui left icon input w-100">
										<i class="semaforoForm flag link icon"></i>
										<input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item">
										<!--<div class="ui basic floating flagCuentaSelect dropdown button simpleDropdown read-only">
											<input type="hidden" class="flagCuentaForm" name="flagCuenta" value="0" patron="requerido">
											<div class="text">Cuenta</div>
											<i class="dropdown icon"></i>
											<div class="menu">
												<div class="item" data-value="1">De la cuenta</div>
												<div class="item" data-value="0">Externo</div>
											</div>
										</div>-->
									</div>
									<input class="codItems" type='hidden' name='idItemForm'>
									<input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
									<input class="idProveedor" type='hidden' name='idProveedorForm' value="">
								</div>
								<div class="ui-widget">
									<input class="itemTextoPdf d-none" type='text' name='itemTextoPdf' placeholder="Descripción de Item para Cotización">
								</div>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Tipo Item</div>
								<select class="ui dropdown simpleDropdown idTipoItem clearable" id="tipoItemForm" name="tipoItemForm" patron="requerido" data-correlativo="1">
									<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione']); ?>
								</select>
							</div>
							<div class="five wide field">
								<div class="ui sub header">Proveedor Referencial
								<div class="ui btn-info-custom text-primary btn-info-proveedor"><i class="info circle icon"></i></div>
								</div>
								<select class="ui dropdown simpleDropdown search clearable" id="proveedorForm" name="proveedorForm" patron="requerido" data-correlativo="1">
									<?= htmlSelectOptionArray2(['query' => $proveedor, 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione']); ?>
								</select>
							</div>
							<div class="one wide field">
								<br>
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregar-proveedor" title="Agregar Proveedor"><i class="fa fa-lg fa-plus"></i></a>
							</div>
						</div>
						<!--
							<div class="fields">
							<div class="five wide field no-personal">
								<div class="ui sub header">Características para el cliente</div>
								<div class="ui labeled input w-100">
									<input class="caracteristicasCliente" type='text' id="caracteristicasItem" name='caracteristicasItem' placeholder="Características del item">
								</div>
							</div>
							<div class="six wide field cCompras no-personal">
								<div class="ui sub header">Características para compras</div>
								<input name="caracteristicasCompras" placeholder="Características">
							</div>
							<div class="five wide field no-personal divCarProv">
								<div class="ui sub header">Características para proveedor</div>
								<input name="caracteristicasProveedor" placeholder="Características">
							</div>
						</div>
					-->
						<div class="fields pt-5">
							<div class="four wide field">
								<div class="ui sub header">Archivos <div class="ui btn-info-custom text-primary btn-info-archivo"><i class="info circle icon"></i></div>
								</div>
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
									<textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"></textarea>
								</div>
							</div>
						</div>
						<div class="content-lsck-capturas">
							<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="<?= ARCHIVOS_PERMITIDOS ?>" multiple="">
							<div class="fields ">
								<div class="sixteen wide field">
									<div class="ui small images content-lsck-galeria">

									</div>
								</div>
							</div>
							<div class="fields ">
								<div class="sixteen wide field">
									<div class="ui small images content-lsck-files">

									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="sixteen wide tablet four wide computer column">
						<div class="fields">
							<div class="sixteen wide field">
								<div class="ui sub header">
									Cantidad de Elementos
									<div class="ui btn-info-custom text-primary btn-info-cantidad"><i class="info circle icon"></i></div>
									<div class="ui btn-info-custom text-primary btn-info-descripcion"><i class="info circle icon"></i></div>
								</div>
								<div class="ui-widget">
									<div class="ui right input w-100">
										<input class="cantidadForm onlyNumbers" data-min="1" type="number" name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
									</div>
								</div>
							</div>
						</div>
						<div class="fields">
							<div class="sixteen wide field">
								<div class="ui sub header">Precio Referencial</div>
								<div class="ui labeled input">
									<label for="amount" class="ui label monedaSimbolo">S/</label>
									<input class="costoReferencialForm" name="costoReferencialForm" placeholder="0.00" type="number" patron="numerico">
								</div>
							</div>
						</div>
						<!--<div class="fields">
							<div class="sixteen wide field">
								<div class="ui sub header">Subtotal</div>
								<div class="ui right labeled input">
									<label for="amount" class="ui label teal monedaSimbolo">S/</label>
									<input class="subtotalFormLabel" type="text" placeholder="0.00" readonly>
									<input class="subtotalForm" type="hidden" name="subtotalForm" placeholder="0.00" readonly>
									<input type="hidden" class="costoRedondeadoForm" name="costoRedondeadoForm" placeholder="0" value="0">
									<input type="hidden" class="costoNoRedondeadoForm" name="costoNoRedondeadoForm" placeholder="0" value="0">
									<div class="ui basic floating dropdown button simpleDropdown">
										<input type="hidden" class="flagRedondearForm" name="flagRedondearForm" value="0" patron="requerido">
										<div class="text">Redondear</div>
										<i class="dropdown icon"></i>
										<div class="menu">
											<div class="item" data-value="1">Redondear arriba</div>
											<div class="item" data-value="0">No redondear</div>
										</div>
									</div>
								</div>
							</div>
						</div>-->
					</div>
				</div>
			</div>
		</div>
		<!--<div class="ui black three column center aligned stackable grid segment">
			<div class="column">
				<div class="ui test toggle checkbox">
					<input class="igvForm" name="igvForm" type="checkbox" onchange="RequerimientoInterno.actualizarTotal();">
					<label>Incluir IGV</label>
				</div>
			</div>
			<div class="column">
				<div class="ui labeled input">
					<label for="totalForm" class="ui label green">Total: </label>
					<input class="totalFormLabel" type="text" placeholder="0.00" readonly="">
					<input class="totalFormIgv" type="hidden" name="totalFormIgv" placeholder="0.00" readonly="">
					<input class="totalForm" type="hidden" name="totalForm" placeholder="0.00" readonly="">
				</div>
			</div>
		</div>-->
	</form>
</div>
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>