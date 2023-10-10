<tr class="dataItem">
	<td id="textDescripcionDetalle_<?= $idTipoPresupuesto ?>_<?= $numeroDeFila ?>"></td>
	<?php foreach ($fechas as $k => $fecha) : ?>
		<td>
			<div class="ui input transparent fluid">
				<input class="text-right" type="text" value="0" readonly id="montoLDS_<?= $idTipoPresupuesto ?>_<?= $numeroDeFila ?>_<?= $k ?>">
			</div>
		</td>
	<?php endforeach; ?>
	<td>
		<div class="ui input transparent fluid">
			<input class="text-right keyUpChange totalFila" type="text" value="0" readonly id="totalLineaDS_<?= $idTipoPresupuesto ?>_<?= $numeroDeFila ?>" data-detalle="<?= $idTipoPresupuesto ?>" onchange="OrdenServicio.calcularTotalColumna(this);">
		</div>
	</td>
</tr>