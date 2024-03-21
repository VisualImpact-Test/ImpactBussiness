<tr class="detalleTr_<?= $numeroDeFila ?>" data-nrofila="<?= $numeroDeFila ?>">
	<td>
		<div class="ui action input" style="min-width: 400px; max-width: 500px;">
			<select class="ui fluid search dropdown dropdownSingleAditions cboTPD keyUpChange" name="tipoPresupuestoDetalleSub[<?= $idTipoPresupuesto; ?>]" onchange="$('#textDescripcionDetalle_<?= $idTipoPresupuesto ?>_<?= $numeroDeFila ?>').html(this.options[this.selectedIndex].text);">
				<option value=""></option>
				<?php foreach ($tipoPresupuestoDetalle as $tpd) : ?>
					<option value="<?= $tpd['idTipoPresupuestoDetalle']; ?>" data-preciounitario="<?= $tpd['costo']; ?>" data-split="<?= $tpd['split']; ?>" data-frecuencia="<?= $tpd['frecuencia']; ?>"><?= $tpd['nombre']; ?></option>
				<?php endforeach; ?>
			</select>
			<a class="ui button" onclick="$(this).closest('tbody').find('tr.cantidadElementos_<?= $numeroDeFila ?>').toggleClass('d-none'); $(this).find('i').toggleClass('open');"><i class="icon folder outline"></i></a>
		</div>
	</td>
	<td class="splitDetalle">
		<div class="ui input fluid">
			<input type="text" class="onlyNumbers keyUpChange" name="splitDS[<?= $idTipoPresupuesto ?>]" value="1" onchange="OrdenServicio.cantidadSplitCargo(this);">
		</div>
	</td>
	<td class="precioUnitarioDetalle">
		<div class="ui input fluid">
			<input type="text" class="text-right moneda" name="precioUnitarioDS[<?= $idTipoPresupuesto ?>]" value="0" onchange="OrdenServicio.cantidadSplitCargo(this);">
		</div>
	</td>
	<td class="gapDetalle">
		<div class="ui right labeled input fluid">
			<input type="text" class="text-right onlyNumbers keyUpChange" name="gapDS[<?= $idTipoPresupuesto ?>]" value="15" onchange="OrdenServicio.cantidadSplitCargo(this);">
			<div class="ui basic label"> % </div>
		</div>
	</td>
	<td class="cantidadDeTabla">
		<div class="ui action input fluid">
			<input type="text" value="<?= $totalCargo; ?>" readonly name="cantidadDS[<?= $idTipoPresupuesto ?>]" onchange="OrdenServicio.calcularSTotal(this);" data-detallesub="<?= $numeroDeFila ?>" data-detalle="<?= $idTipoPresupuesto ?>">
			<a class="ui button" onclick="$(this).closest('tbody').find('tr.cantidadCargo_<?= $numeroDeFila ?>').toggleClass('d-none'); $(this).find('i').toggleClass('slash');"><i class="icon user slash"></i></a>
		</div>
	</td>
	<td>
		<div class="ui input transparent totalCantidadSplit fluid">
			<input type="text" class="text-right" value="0" readonly name="montoDS[<?= $idTipoPresupuesto ?>]">
		</div>
	</td>
	<td class="frecuenciaDetalle">
		<select class="ui fluid search dropdown toast semantic-dropdown frecuenciaID" onchange="OrdenServicio.cantidadSplitCargo(this);" name="frecuenciaDS[<?= $idTipoPresupuesto ?>]" patron="requerido">
			<?= htmlSelectOptionArray2(['title' => 'Frecuencia', 'query' => LIST_FRECUENCIA, 'class' => 'text-titlecase']); ?>
		</select>
	</td>
</tr>
<tr class="cantidadCargo_<?= $numeroDeFila ?> d-none">
	<td colspan="7">
		<h4 class="ui horizontal divider header" style="background: none; overflow: inherit;">
			<i class="bar chart icon"></i>
			Cantidad por Cargo
		</h4>
		<div class="ui grid centered">
			<div class="eight wide column">
				<table class="ui very compact celled table">
					<thead>
						<tr>
							<th class="two wide column"></th>
							<th class="eight wide column">Cargo</th>
							<th class="six wide column">Cantidad</th>
						</tr>
					</thead>
					<tbody class="listCheck">
						<?php foreach ($cargos as $i => $cargo) : ?>
							<tr>
								<td class="text-center">
									<div class="fields">
										<div class="ui checkbox">
											<input type="checkbox" name="chkDS[<?= $cargo['idCargo']; ?>][<?= $idTipoPresupuesto ?>][<?= $numeroDeFila ?>]" data-cargo="<?= $i; ?>" checked onchange="$(this).closest('.cantidadCargo_<?= $numeroDeFila ?>').closest('tbody').find('tr.detalleTr_<?= $numeroDeFila ?>').find('.onlyNumbers').change();">
											<label style="font-size: 1.5em;"></label>
										</div>
									</div>
								</td>
								<td><?= $cargo['cargo']; ?></td>
								<td>
									<div class="ui input">
										<input class="onlyNumbers keyUpChange subCantDS cantCargoxItm_<?= $cargo['idCargo'] ?> " name="subCantDS[<?= $cargo['idCargo']; ?>][<?= $idTipoPresupuesto ?>][<?= $numeroDeFila ?>]" data-max="<?= $cargo['cantidad']; ?>" type="number" value="<?= $cargo['cantidad']; ?>" onchange="$(this).closest('.cantidadCargo_<?= $numeroDeFila ?>').closest('tbody').find('tr.detalleTr_<?= $numeroDeFila ?>').find('.onlyNumbers').change();">
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</td>
</tr>
<tr class="d-none cantidadElementos_<?= $numeroDeFila ?>">
	<td colspan="7">
		<h4 class="ui horizontal divider header" style="background: none; overflow: inherit;">
			<i class="bar chart icon"></i>
			Cantidad de Elementos
		</h4>
		<div class="ui grid centered">
			<div class="twelve wide column">
				<table class="ui very compact celled table">
					<thead>
						<tr>
							<th class="six wide column">
								<label class="ui left floated" style="font-size: 1em; vertical-align:middle; margin-bottom: 0px; padding: 11px 21px 11px 21px; ">Elemento</label>
								<a class="ui button right floated green" onclick="OrdenServicio.addElemento(this);" data-nrofila="<?= $numeroDeFila ?>" data-detalle="<?= $idTipoPresupuesto; ?>"><i class="icon plus"></i> Agregar</a>
							</th>
							<th class="three wide column text-right">Cantidad</th>
							<th class="three wide column text-right">Monto</th>
							<th class="four wide column text-right">Sub Total</th>
						</tr>
					</thead>
					<tbody data-nrofila="<?= $numeroDeFila ?>">
					</tbody>
				</table>
			</div>
		</div>
	</td>
</tr>