<?php if ($tipo == COD_TEXTILES['id']) {
	$cantidadDatos = 'eigth';
} else if ($tipo == COD_SERVICIO['id']) {
	$cantidadDatos = 'three';
} else {
	$cantidadDatos = 'cero';
} ?>
<div class="ui form col-md-12">
	<div class="subItemSpace border-bottom pt-2">
		<div class="<?= $cantidadDatos ?> fields">
			<div class="field <?= $tipo == 0  ? '' : 'd-none'; ?>">
				<label>Unidad Medida</label>
				<input name="subItem_idUm" patron="requerido">
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
				<select name="subItem_genero">
					<option class="item" value="">SELECCIONE</option>
					<option class="item" value="1">VARON</option>
					<option class="item" value="2">DAMA</option>
					<option class="item" value="3">UNISEX</option>
				</select>
			</div>
			<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
				<label>Tela</label>
				<input name="subItem_tela">
			</div>
			<div class="field <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
				<label>Color</label>
				<input name="subItem_color">
			</div>
			<div class="field <?= $tipo == COD_TEXTILES['id'] || $tipo == COD_SERVICIO['id'] ? '' : 'd-none'; ?>">
				<label>Cantidad</label>
				<input class="SbItCantidad keyUpChange" name="subItem_cantidad" patron="requerido"
					onchange="Oper.calcularCantidadSubItem(this);"
				>
			</div>
			<div class="field <?= $tipo == 0 ? '' : 'd-none'; ?>">
				<label>CantidadPDV</label>
				<input name="subItem_cantidadPdv" patron="requerido">
			</div>
			<div class="field <?= $tipo == COD_TEXTILES['id'] ? 'd-none' : ''; ?> ">
				<label>Costo</label>
				<input class="SbItCosto keyUpChange" name="subItem_costo" patron="requerido" 
					onchange="Oper.calcularCostoPromedioTextil(this);"
				>
			</div>
			<div class="field d-none <?= $tipo == COD_TEXTILES['id'] ? '' : 'd-none'; ?>">
				<label>Monto</label>
				<input name="subItem_monto">
			</div>
			<div class="field <?= $tipo == 0 ? '' : 'd-none'; ?>">
				<label></label>
				<input name="subItem_tipoServ" patron="requerido">
			</div>
			<div class="field <?= $tipo == 0 ? '' : 'd-none'; ?>">
				<label></label>
				<input name="subItem_itemLog" patron="requerido">
			</div>
			<div class="field <?= $cantidadDatos == 'cero' ? 'd-none' : ''; ?>">
				<label></label>
				<a class="btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
			</div>
		</div>
	</div>
</div>