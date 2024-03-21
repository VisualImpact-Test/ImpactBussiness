<tr>
	<td>
		<div class="fields">
			<div class="sixteen wide field divRegion">
				<div class="ui sub header">Region</div>
				<select class="ui dropdown cboRegion" name="departamento" onchange="OrdenServicio.buscarProvincia(this,'tr');" patron="requerido">
					<option value="">Seleccione</option>
					<?php foreach ($departamento as $k => $v) : ?>
						<option value="<?= $v['id'] ?>"><?= $v['nombre'] ?></option>;
					<?php endforeach; ?>
				</select>
			</div>
			<div class="sixteen wide field d-none divProvincia">
				<div class="ui sub header">Provincia</div>
				<select class="ui dropdown cboProvincia" name="provincia" onchange="OrdenServicio.buscarDistrito(this,'tr');" patron="requerido">
					<option value="">Seleccione</option>
				</select>
			</div>
			<div class="sixteen wide field d-none divDistrito">
				<div class="ui sub header">Distrito</div>
				<select class="ui dropdown cboDistrito" name="distrito" patron="requerido">
					<option value="">Seleccione</option>
				</select>
			</div>
		</div>
	</td>
	<?php for ($i = 1; $i <= $cantidadDeMeses; $i++) : ?>
		<td>
			<div class="ui input fluid">
				<input name="cantidadCargoFecha[<?= $idCargo ?>][<?= ($i - 1) ?>]"  class="text-center keyUpChange mesNro<?= $i ?>" value="0" onchange="OrdenServicio.calcularMontoZonaMes(this)" data-nromes="<?= $i ?>">
			</div>
		</td>
	<?php endfor; ?>
	<td>
		<button class="ui red button" type="button" onclick="$(this).closest('tr').find('input').val('0').change(); $(this).closest('tr').remove();"><i class="icon trash"></i></button>
	</td>
</tr>