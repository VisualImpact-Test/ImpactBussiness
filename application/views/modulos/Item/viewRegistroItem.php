<style>
	.img-lsck-capturas {
		height: 150px !important;
	}
</style>
<div class="ui form attached fluid segment p-4">
	<form class="ui form" role="form" id="formRegistroItems" method="post" autocomplete="off">
		<!-- <h4 class="ui dividing header">DETALLE DE LOS ITEMS</h4> -->
		<div class="default-item">
			<div class="ui segment body-item nuevo">
				<div class="ui left floated header">
					<span class="ui medium text ">Item N. <span class="title-n-detalle">00001</span></span>
				</div>
				<div class="ui clearing divider"></div>
				<div class="ui grid">
					<div class="sixteen wide tablet wide computer column">
						<div class="fields">
							<div class="four wide field">
								<div class="ui sub header">Nombre</div>
								<div class="ui-widget">
									<div class="ui icon input w-100">
										<input class="form-control items <?= (!empty($nombreItem)) ? "disabled" : "" ?>" id="nombre" name="nombre" patron="requerido" value="<?= (!empty($nombreItem)) ? $nombreItem : "" ?>">
									</div>
								</div>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Tipo Item</div>
								<select class="ui dropdown simpleDropdown tipoArticulo" id="tipo" name="tipo" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Características</div>
								<div class="ui right labeled input w-100">
									<input type='text' class=" <?= (!empty($caracteristicasItem)) ? "disabled" : "" ?>" id="caracteristicas" name="caracteristicas" patron="requerido" value="">
								</div>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Marca</div>
								<select name="marca" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
						</div>
						<div class="fields">
							<div class="four wide field">
								<div class="ui sub header">Categoria</div>
								<select name="categoria" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Subcategoria</div>

								<select name="subcategoria" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $subcategoriaItem, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Unidad Medida</div>
								<select class="ui dropdown simpleDropdown" name="unidadMedida" id="unidadMedida">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $unidadMedida, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field itemLogisticaDiv">
								<div class="ui sub header">Equivalente en logistica</div>
								<div class="ui action input">
									<input class="form-control itemLogistica" id="equivalente" name="equivalente" placeholder="Buscar " autocomplete="off">
									<a class="ui button" onclick="Cotizacion.editItemLogisticaValue(this);"><i class="fa fa-edit"></i></a>
								</div>
								<input class="d-none codItemLogistica" name="idItemLogistica">
							</div>
						</div>
						<div class="fields">
							<div class="four wide field">
								<div class="ui sub header">¿ Es Packing ?</div>
								<select class="ui dropdown simpleDropdown" name="flagPacking">
									<option value="0" selected>NO</option>
									<option value="1">SI, EL ITEM SERÁ CONSIDERADO POR ALMACÉN</option>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Cuenta</div>
								<select class="ui dropdown parentDependiente centro-visible" name="cuenta" id="cuenta" data-childDependiente="cuentaCentroCostoForm">
									<?= htmlSelectOptionArray2(['title' => 'TODAS LAS CUENTAS', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Centro Costo</div>
								<select class="ui dropdown clearable semantic-dropdown centro-ocultado" multiple="" name="centroCosto" id="cuentaCentroCostoForm">
									<?= htmlSelectOptionArray2(['title' => 'TODAS LOS CENTROS DE COSTO', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">¿ Considerar Item para Presupuesto ?</div>
								<select class="ui dropdown simpleDropdown" name="flagParaPresupuesto" onchange="$(this).closest('.column').find('.isPresupuesto').toggleClass('d-none');">
									<option value="0" selected>NO</option>
									<option value="1">SI, EL ITEM SERÁ CONSIDERADO AL GENERAR EL PRESUPUESTO</option>
								</select>
							</div>
						</div>
						<div class="fields isPresupuesto d-none">
							<div class="sixteen wide field">
								<h3 class="ui header">
									<i class="settings icon"></i>
									<div class="content">
										Detalle del Presupuesto
										<div class="sub header">Indicar en que detalle del presupuesto se encontrará el item que esta registrando.</div>
									</div>
								</h3>
							</div>
						</div>
						<div class="fields isPresupuesto d-none">
							<div class="four wide field">
								<div class="ui sub header">Detalle</div>
								<select class="ui dropdown clearable semantic-dropdown parentDependienteSemantic" name="tipoPresupuesto" data-childDependiente="#cboSubDetallePresupuesto">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoPresupuesto, 'id' => 'idTipoPresupuesto', 'value' => 'nombre', 'simple' => true, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Sub Detalle</div>
								<select class="ui dropdown clearable semantic-dropdown read-only childdependienteSemantic" id="cboSubDetallePresupuesto" name="tipoPresupuestoDetalle">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoPresupuestoDetalle, 'simple' => true, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
						</div>
						<div class="fields">
							<div class="two wide field">
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
							<div class="fourteen wide field">
								<div class="content-lsck-capturas">
									<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*,.pdf" multiple="">
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- FAB -->
<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
<div class="floating-container">
	<div class="floating-button ">
		<i class="cog icon"></i>
	</div>
	<div class="element-container">
		<a href="javascript:;">
			<span class="float-element tooltip-left btn-send-item" data-message="Registrar" onclick='Fn.showConfirm({ idForm: "formRegistroItems", fn: "Item.registrarItem()", content: "¿Esta seguro de registrar este item?" });'>
				<i class="send icon"></i>
			</span>
		</a>
	</div>
</div>

<!-- Items -->

<input id="itemsServicio" type="hidden" value='<?= json_encode($informacionItem) ?>'>
<textarea class="d-none" id="itemsLogistica"><?= json_encode($itemsLogistica) ?></textarea>

<input id="marcas" type="hidden" value='<?= json_encode($marcaItem) ?>'>
<input id="categorias" type="hidden" value='<?= json_encode($categoriaItem) ?>'>
<input id="subcategorias" type="hidden" value='<?= json_encode($subcategoriaItem) ?>'>