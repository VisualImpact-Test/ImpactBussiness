<form class="form" role="form" id="formRegistroOC" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="form-row pt-3">
					<div class="form-group col-md-4">
						<input type="hidden" name="idOper" value="">
						<label class="font-weight-bold mb-0">Requerimiento:</label>
						<input class="form-control" name="requerimiento" patron="requerido">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Concepto:</label>
						<input class="form-control" name="concepto">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Descripcion Compras:</label>
						<input class="form-control" name="descripcionCompras">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Proveedor:</label>
						<select id="proveedor" name="proveedor" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'value' => 'razonSocial', 'id' => 'idProveedor']); ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Cuenta:</label>
						<select class="form-control ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Centro Costo:</label>
						<select class="form-control ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $centroCosto, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Metodo Pago:</label>
						<select id="metodoPago" name="metodoPago" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Moneda:</label>
						<!-- Revisar https://fomantic-ui.com/modules/dropdown.html -->
						<div class="ui fluid search selection dropdown simpleDropdown semantic-dropdown ">
							<input type="hidden" name="moneda" value="1" patron="requerido">
							<i class="dropdown icon"></i>
							<div class="default text">Moneda</div>
							<div class="menu">
								<?php foreach ($moneda as $value) : ?>
									<div class="item" data-value="<?= $value['idMoneda'] ?>">
										<i class="<?= $value['icono'] ?>"></i><?= $value['nombre'] ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<!-- Fin Revisar -->
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">IGV 18%</label>
						<div class="custom-control custom-switch custom-switch-lg">
							<input type="checkbox" class="custom-control-input" id="incluyeIgv" name="incluyeIgv" onchange="$(this).prop('checked') ? $('#valorIGV').val('118').change() : $('#valorIGV').val('100').change();">
							<label class="custom-control-label" for="incluyeIgv"></label>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">Fecha Entrega:</label>
						<input type="date" class="form-control" name="fechaEntrega" patron="requerido">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">PO Cliente:</label>
						<input class="form-control" name="poCliente" patron="requerido">
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold mb-0">Lugar de entrega:</label>
						<select class="form-control ui fluid search dropdown semantic-dropdown" name="idAlmacen" onchange="$('.inpEntrega').val($(this).find('option:selected').data('direccion'));">
							<option>-</option>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $almacenes, 'id' => 'idAlmacen', 'value' => 'nombre', 'class' => 'text-titlecase', 'data-option' => ['direccion']]); ?>
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Lugar de Entrega:</label>
						<input class="form-control inpEntrega" name="entrega">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-10">
						<label class="font-weight-bold mb-0">Observación:</label>
						<input class="form-control" name="observacion" value="En caso de incumplimiento en fecha de entrega, se estará ejecutando penalidad del 1% por cada día de retraso.">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">Mostrar Observación</label>
						<div class="custom-control custom-switch custom-switch-lg">
							<input type="checkbox" class="custom-control-input" id="mostrar_observacion" name="mostrar_observacion">
							<label class="custom-control-label" for="mostrar_observacion"></label>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label class="font-weight-bold mb-0">Comentario:</label>
						<input class="form-control" name="comentario">
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos de Item<small>(s)</small></legend>
				<div class="row itemData" id="divItemData">
					<div class="form-row col-md-9 order-md-1 divItem pt-3 border-bottom">
						<div class="form-group col-md-6">
							<label class="font-weight-bold mb-0">Item:</label>
							<div class="input-group mb-3">
								<input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" onclick="Oc.editItemValue(this);"><i class="fa fa-edit"></i></button>
								</div>
							</div>
							<input class="codItems d-none" type='text' name='idItemForm'>
						</div>
						<div class="form-group col-md-6">
							<label class="font-weight-bold mb-0">Tipo:</label>
							<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
								<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'tipo']); ?>
							</select>
						</div>
						<div class="form-row col-md-12 subItem"></div>
						<div class="form-row">
							<div class="form-group" onclick="Oc.generarSubItem(this, this.value);">
								<a class="form-control btn btn-info btnAdicionar" style="display:none;"><i class="fa fa-plus"></i> Adicionar</a>
							</div>
							<div class="form-group" onclick="Oc.quitarItem(this, this.value);">
								<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
							</div>
						</div>
					</div>
					<div class="col-md-3 order-md-2 pt-3 border-bottom itemValor">
						<div class="form-group">
							<label class="font-weight-bold mb-0">Cantidad:</label>
							<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
						</div>
						<div class="form-group d-none">
							<label class="font-weight-bold mb-0">CantidadSubItem:</label>
							<input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="0">
						</div>
						<div class="form-group">
							<label class="font-weight-bold mb-0">Costo:</label>
							<input class="form-control item_costo" name="costo" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="0">
						</div>
						<div class="form-row">
							<div class="form-group col-md-6 d-none">
								<label class="font-weight-bold mb-0">GAP:</label>
								<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="0">
							</div>
							<div class="form-group col-md-12">
								<label class="font-weight-bold mb-0">Sub Total:</label>
								<input class="form-control item_precio" name="precio" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
							</div>
							<div class="form-group col-md-12 d-none">
								<label class="font-weight-bold mb-0">Sub Total real:</label>
								<input class="form-control item_precio_real" name="precio_real" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
							</div>
						</div>
					</div>
				</div>
				<div class="extraItem">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Consolidados</legend>
				<div class="form-row pt-3">
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">IGV:</label>
						<select name="igvPorcentaje" patron="requerido" class="form-control" id="valorIGV" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();">
							<option selected value="100">No incluir IGV</option>
							<option value="118">Incluir IGV</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total:</label>
						<input class="form-control" name="total" patron="requerido" id="total" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();">
						<input type="hidden" class="form-control" name="total_real" patron="requerido" id="total_real" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();">

					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total Final:</label>
						<input class="form-control" name="totalIGV" patron="requerido" id="totalFinal" readOnly>
						<input type="hidden" class="form-control" name="totalIGV_real" patron="requerido" id="totalFinal_real" readOnly>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>
<input id="itemsData" type="hidden" value='<?= json_encode($item) ?>'>
<div class="d-none" id="divItemLogistica">
	<select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
		<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label']); ?>
	</select>
</div>
<div class="d-none" id="divTipoServicio">
	<select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
		<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
	</select>
</div>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>