<?php if ($tipo == COD_TEXTILES['id']) {
	$cantidadDatos = 'seven';
}
if ($tipo == COD_SERVICIO['id']) {
	$cantidadDatos = 'seven';
} ?>
<div class="ui form">
	<div class="<?= $cantidadDatos ?> fields">
		<div class="field <?= $tipo == 0  ? '' : 'd-none'; ?>">
			<label>Unidad Medida</label>
			<input name="subItem_idUm" patron="requerido"><!-- Select -->
		</div>
		<div class="field <?= $tipo == COD_SERVICIO['id'] ? '' : 'd-none'; ?>">
			<label>Descripción</label>
			<input name="subItem_nombre" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Talla</label>
			<input name="subItem_talla" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Genero</label>
			<input name="subItem_genero" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Tela</label>
			<input name="subItem_tela" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Color</label>
			<input name="subItem_color" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] || $tipo == COD_SERVICIO['id'] ? '' : 'd-none'; ?>">
			<label>Cantidad</label>
			<input name="subItem_cantidad" patron="requerido">
		</div>
		<div class="field <?= $tipo == 0 ? '' : 'd-none'; ?>">
			<label>CantidadPDV</label>
			<input name="subItem_cantidadPdv" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Costo</label>
			<input name="subItem_costo" patron="requerido">
		</div>
		<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
			<label>Monto</label>
			<input name="subItem_monto" patron="requerido">
		</div>
	</div>
</div>



<!-- 
<div class="form-row subItemSpace col-md-12">
	<div class="form-row subItemSpaceTextil col-md-12 border-bottom pt-2">
		<div class="form-group col-md-1">
			<label class="font-weight-bold">Talla:</label>
			<input class="form-control" name="subItem_talla" patron="requerido">
		</div>
		<div class="form-group col-md-2">
			<label class="font-weight-bold">Genero:</label>
			<select class="form-control" name="subItem_genero">
				<option class="item" value="">SELECCIONE</option>
				<option class="item" value="1">VARON</option>
				<option class="item" value="2">DAMA</option>
				<option class="item" value="3">UNISEX</option>
			</select>
		</div>
		<div class=" col-md-3" style="display: flex;">
			<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
				<label class="font-weight-bold">Tela:</label>
				<input class="form-control" name="subItem_tela" patron="requerido">
			</div>
			<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
				<label class="font-weight-bold">Color:</label>
				<input class="form-control" name="subItem_color" patron="requerido">
			</div>
		</div>
		<div class=" col-md-3" style="display: flex;">
			<div class="form-group col-md-6 cantidadTextil" style="padding-right: 3px;padding-left: 3px;">
				<label class="font-weight-bold">Cantidad:</label>
				<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');" onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
			</div>
			<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
				<label class="font-weight-bold">Costo:</label>
				<input class="form-control SbItCosto" name="subItem_costo" patron="requerido" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');" onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
			</div>
		</div>
		<div class="form-group col-md-2">
			<label class="font-weight-bold">Sb Tot:</label>
			<input class="form-control SbItSubTotal" name="subItem_st" patron="requerido" readonly onchange="Oper.calcularTextilPrecio(this);">
		</div>
		<div class="form-group col-md-1">
			<label class="font-weight-bold" style="color: white;">:</label>
			<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
		</div>
	</div>
	<div class="form-row subItemSpaceServicio col-md-12 border-bottom pt-2 d-none">
		<div class="form-group col-md-6">
			<label class="font-weight-bold">Descripción Serv.:</label>
			<input class="form-control" name="subItem_nombre" patron="requerido">
		</div>
		<div class="form-group col-md-5 cantidadServicio">
			<label class="font-weight-bold">Cantidad:</label>
			<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="Oper.cantidadServicio(this);" onkeyup="Oper.cantidadServicio(this);">
		</div>
		<div class="form-group col-md-1">
			<label class="font-weight-bold" style="color: white;">:</label>
			<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
		</div>
		<input type="hidden" name="subItem_tipoServ" value="">
		<input type="hidden" name="subItem_idUm" value="">
		<input type="hidden" name="subItem_itemLog" value="">
		<input type="hidden" name="subItem_cantidadPdv" value="">
		<input type="hidden" name="subItem_monto" value="">
	</div>
</div>
-->