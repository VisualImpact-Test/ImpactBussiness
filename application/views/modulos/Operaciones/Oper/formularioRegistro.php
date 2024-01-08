<form class="form" role="form" id="formRegistroOper" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="form-row pt-3">
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Dirigido a:</label>
						<select name="usuarioReceptor" patron="requerido" class="form-control">
							<option selected value="1">Coordinadora de Compras</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Cuenta:</label>
						<select class="form-control ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'value' => 'nombre', 'id' => 'idEmpresa']); ?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Centro Costo:</label>
						<select class="form-control ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $centroCosto, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Valor</label>
						<input type="text" class="form-control" name="valor">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Concepto:</label>
						<input class="form-control" name="concepto" patron="requerido">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold">Número PO/OC:</label>
						<input type="text" class="form-control" name="numeroPO">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold">Fecha Requerimiento:</label>
						<input type="date" class="form-control" name="fechaRequerimiento" patron="requerido">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold">Fecha Entrega:</label>
						<input type="date" class="form-control" name="fechaEntrega" patron="requerido">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label class="font-weight-bold">Observación:</label>
						<input class="form-control" name="observacion">
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
							<label class="font-weight-bold">Item:</label>
							<div class="input-group mb-3">
								<input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" onclick="Oper.editItemValue(this);"><i class="fa fa-edit"></i></button>
								</div>
							</div>
							<input class="codItems d-none" type='text' name='idItemForm'>
							<input class="codProveedor d-none" type='text' name='idProveedor'>
						</div>
						<div class="form-group col-md-6">
							<label class="font-weight-bold">Tipo:</label>
							<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
								<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'nombre']); ?>
							</select>
						</div>
						<div class="form-row col-md-12 subItem"></div>
						<div class="form-row">
							<div class="form-group" onclick="Oper.generarSubItem(this, this.value);">
								<a class="form-control btn btn-info btnAdicionar" style="display:none;"><i class="fa fa-plus"></i> Adicionar</a>
							</div>
							<div class="form-group" onclick="Oper.quitarItem(this, this.value);">
								<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
							</div>
						</div>
						<!-- <div class="form-group col-md-4">
							<label class="font-weight-bold">Caract. para Cliente:</label>
							<input class="form-control" name="caracteristica" patron="requerido">
						</div> -->
					</div>
					<div class="col-md-3 order-md-2 pt-3 border-bottom itemValor">
						<div class="form-group">
							<label class="font-weight-bold">Cantidad:</label>
							<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oper.cantidadPorItem(this);" onkeyup="Oper.cantidadPorItem(this);">
						</div>
						<div class="form-group d-none">
							<label class="font-weight-bold">CantidadSubItem:</label>
							<input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="0">
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Costo:</label>
							<input class="form-control item_costo" name="costo" patron="requerido" onchange="Oper.cantidadPorItem(this);" onkeyup="Oper.cantidadPorItem(this);" value="0">
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="font-weight-bold">GAP:</label>
								<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oper.cantidadPorItem(this);" value="15">
							</div>
							<div class="form-group col-md-6">
								<label class="font-weight-bold">Sub Total:</label>
								<input class="form-control item_precio" name="precio" patron="requerido" onchange="Oper.cantidadPorItem(this);" onkeyup="Oper.cantidadPorItem(this);">
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
						<label class="font-weight-bold">IGV:</label>
						<select name="igvPorcentaje" patron="requerido" class="form-control" id="valorIGV" onchange="Oper.cantidadTotal();" onkeyup="Oper.cantidadTotal();">
							<option selected value="100">No incluir IGV</option>
							<option value="118">Incluir IGV</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold">Fee:</label>
						<input class="form-control" name="feePorcentaje" patron="requerido" id="fee" onchange="Oper.cantidadTotal();" onkeyup="Oper.cantidadTotal();">
					</div>
					<div class="form-group col-md-4 d-none">
						<label class="font-weight-bold">Total:</label>
						<input class="form-control" name="total" patron="requerido" id="total" onchange="Oper.cantidadTotal();" onkeyup="Oper.cantidadTotal();">
					</div>
					<div class="form-group col-md-4 d-none">
						<label class="font-weight-bold">TotalFee:</label>
						<input class="form-control" name="totalFee" patron="requerido" id="totalFee" onchange="Oper.cantidadTotal();" onkeyup="Oper.cantidadTotal();">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold">Total:</label>
						<input class="form-control" name="totalFeeIGV" patron="requerido" id="totalFinal" readOnly>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>
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