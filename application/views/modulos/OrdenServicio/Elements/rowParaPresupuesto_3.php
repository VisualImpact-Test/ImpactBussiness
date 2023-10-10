<tr>
	<td>
		<div class="ui action input" style="min-width: 400px; max-width: 500px;">
			<select class="ui fluid search dropdown semantic-dropdown" name="elementoPresupuesto[<?= $idTipoPresupuesto; ?>][<?= $nroFila; ?>]" onchange="Fn.buscarParaReemplazar(this, 'tr', 'preciounitario', '.montoElemento');">
				<option value=""></option>
				<?php foreach ($items as $item) : ?>
					<option value="<?= $item['idItem']; ?>" data-preciounitario="<?= isset($itemPrecio[$item['idItem']]['costo']) ? $itemPrecio[$item['idItem']]['costo'] : '0'; ?>"><?= $item['nombre']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</td>
	<td>
		<div class="ui input">
			<input type="text" class="text-right onlyNumbers cantidadElemento keyUpChange" name="cantidadElementos[<?= $idTipoPresupuesto; ?>][<?= $nroFila; ?>]" value="0" onchange="Fn.buscarParaMultiplicar(this, 'tr', '.montoElemento', '.subTotalElemento');">
		</div>
	</td>
	<td>
		<div class="ui input">
			<input type="text" class="text-right onlyNumbers montoElemento keyUpChange" name="montoElementos[<?= $idTipoPresupuesto; ?>][<?= $nroFila; ?>]" value="0" onchange="Fn.buscarParaMultiplicar(this, 'tr', '.cantidadElemento', '.subTotalElemento');">
		</div>
	</td>
	<td>
		<div class="ui input">
			<input type="text" class="text-right onlyNumbers subTotalElemento" name="subTotalElemento[<?= $idTipoPresupuesto; ?>][<?= $nroFila; ?>]" value="0" readonly onchange="OrdenServicio.evaluarSubTotalElemento(this);">
		</div>
	</td>
</tr>