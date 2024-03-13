<div class="row itemData d-none" id="divItemData">
	<div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
		<div class="form-group col-md-6">
			<label class="font-weight-bold mb-0">Item:</label>
			<div class="input-group mb-3">
				<input class="form-control items ui-autocomplete-input item-id" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off">
				<div class="input-group-append divItemBlock">
					<button class="btn btn-outline-secondary" type="button" onclick="RequerimientoInterno.editItemValue(this);"><i class="fa fa-edit"></i></button>
				</div>
				<input class="codItems d-none" type='text' name='idItemForm'>
			</div>
		</div>
		<div class="form-group col-md-6">
			<label class="font-weight-bold mb-0">Tipo:</label>
			<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
				<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemTipo, 'class' => 'text-titlecase']); ?>
			</select>
		</div>
		<div class="form-row col-md-12 contentSemanticDiv divParaCarga">
			<input class="adjuntoItemCantidad" type="hidden" name="adjuntoItemCantidad" value="0">
			<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'adjuntoItem'], 2) ?>
		</div>
		<div class="form-row col-md-12 ui imagendivCarga"></div>
		<div class="form-row col-md-12 ui imagendivCarga"></div>
		<div class="form-row">
			<div class="form-group" onclick="RequerimientoInterno.quitarItem(this, this.value);">
				<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
			</div>
		</div>
	</div>
	<div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
		<div class="form-group">
			<label class="font-weight-bold mb-0">Cantidad:</label>
			<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);">
		</div>
		<div class="form-group">
			<label class="font-weight-bold mb-0">Costo:</label>
			<input class="form-control item_costo" name="costo" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="0">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6 d-none">
				<label class="font-weight-bold mb-0">GAP:</label>
				<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="0">
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold mb-0">Sub Total:</label>
				<input class="form-control item_precio" name="precio" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);">
			</div>
			<div class="form-group col-md-6 d-none">
				<label class="font-weight-bold mb-0">Sub Total real:</label>
				<input class="form-control item_precio_real" name="precio_real" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);">
			</div>
		</div>
	</div>
</div>
<form class="form" role="form" id="formGenerarOC" method="post" autocomplete="off">
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
					<div class="form-group col-md-4 disabled disabled-visible">
						<input type="hidden" name="idRequerimientoInterno" value="<?= $idRequerimientoInterno ?>">
						<label class="font-weight-bold mb-0">Proveedor:</label>
						<select id="proveedor" name="proveedor" patron="requerido" class="form-control ui search dropdown parentDependiente">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'value' => 'razonSocial', 'id' => 'idProveedor', 'selected' => $proveedorFinal]); ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Cuenta:</label>
						<select class="form-control ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => $requerimientoInterno['idCuenta']]); ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Centro Costo:</label>
						<select class="form-control ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => $requerimientoInterno['idCentroCosto']]); ?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Metodo Pago:</label>
						<select id="metodoPago" name="metodoPago" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $metodoPago, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Moneda:</label>
						<!-- Revisar https://fomantic-ui.com/modules/dropdown.html -->
						<div class="ui fluid search selection dropdown simpleDropdown semantic-dropdown ">
							<input type="hidden" name="moneda" value="<?= $requerimientoInterno['idTipoMoneda'] ?>" patron="requerido">
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
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $almacenes, 'id' => 'idAlmacen', 'value' => 'nombre', 'class' => 'text-titlecase', 'data-option' => ['direccion'], 'selected' => $oc[0]['idAlmacen']]); ?>
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Lugar de Entrega:</label>
						<input class="form-control" name="entrega">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-10">
						<label class="font-weight-bold mb-0">Observación:</label>
						<input class="form-control" name="observacion">
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
				<div class="extraItem">
					<?php foreach ($requerimientoTarifario as $key => $value) : ?>
						<div class="row itemData">
							<div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
								<div class="form-group col-md-6">
									<label class="font-weight-bold mb-0">Item:</label>
									<input type="hidden" name="idRequerimientoInternoDetalle" value="<?= $value['idRequerimientoInternoDetalle'] ?>">
									<div class="input-group mb-3">
										<input class="form-control items ui-autocomplete-input item-id" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off" value="<?= $value['item'] ?>" readonly>
										<div class="input-group-append divItemBlock">
											<button class="btn btn-outline-secondary" type="button" onclick="RequerimientoInterno.editItemValue(this);"><i class="fa fa-edit"></i></button>
										</div>
									</div>
									<input class="codItems d-none" type='text' name='idItemForm' value="<?= $value['idItem'] ?>">
								</div>
								<div class="form-group col-md-6">
									<label class="font-weight-bold mb-0">Tipo:</label>
									<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
										<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemTipo, 'class' => 'text-titlecase', 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="form-row col-md-12 contentSemanticDiv divParaCarga">
									<?php $count = isset($ocAdjunto[$value['idRequerimientoInternoDetalle']]) ? count($ocAdjunto[$value['idRequerimientoInternoDetalle']]) : 0; ?>
									<input class="adjuntoItemCantidad" type="hidden" name="adjuntoItemCantidad" value="<?= $count ?>">
									<?= htmlSemanticCargaDeArchivos([
										'classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*',
										'name' => 'adjuntoItem',
										'data' => $ocAdjunto[$value['idRequerimientoInternoDetalle']]
									], 2) ?>
								</div>
								<div class="form-row col-md-12 ui imagendivCarga"></div>
								<div class=" form-row">
									<div class="form-group" onclick="RequerimientoInterno.quitarItem(this, this.value);">
										<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
									</div>
								</div>
							</div>
							<div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
								<div class="form-group">
									<label class="font-weight-bold mb-0">Cantidad:</label>
									<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="<?= $value['cantidad'] ?>">
								</div>
								<div class="form-group">
									<label class="font-weight-bold mb-0">Costo:</label>
									<input class="form-control item_costo" name="costo" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="<?= $value['costo'] ?>">
								</div>
								<div class="form-row">
									<div class="form-group col-md-6 d-none">
										<label class="font-weight-bold mb-0">GAP:</label>
										<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="0">
									</div>
									<div class="form-group col-md-12">
										<label class="font-weight-bold mb-0">Sub Total:</label>
										<input class="form-control item_precio" name="precio" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="<?= $value['cantidad'] * $value['costo']; ?>">
									</div>
									<div class="form-group col-md-12 d-none">
										<label class="font-weight-bold mb-0">Sub Total real:</label>
										<input class="form-control item_precio_real" name="precio_real" patron="requerido" onchange="RequerimientoInterno.cantidadPorItem(this);" onkeyup="RequerimientoInterno.cantidadPorItem(this);" value="<?= $value['cantidad'] * $value['costo']; ?>">
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
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
						<select name="igvPorcentaje" patron="requerido" class="form-control" id="valorIGV" onchange="RequerimientoInterno.cantidadTotal();" onkeyup="RequerimientoInterno.cantidadTotal();">
							<option value="100">No incluir IGV</option>
							<option value="118">Incluir IGV</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total:</label>
						<input class="form-control totalTotal" name="total" patron="requerido" id="total" onchange="RequerimientoInterno.cantidadTotal();" onkeyup="RequerimientoInterno.cantidadTotal();">
						<input type="hidden" class="form-control total_real" name="total_real" patron="requerido" id="total_real" onchange="RequerimientoInterno.cantidadTotal();" onkeyup="RequerimientoInterno.cantidadTotal();">

					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total:</label>
						<input class="form-control totalIGV" name="totalIGV" patron="requerido" id="totalFinal" readOnly>
						<input type="hidden" class="form-control" name="totalIGV_real" patron="requerido" id="totalFinal_real" readOnly>

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
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>