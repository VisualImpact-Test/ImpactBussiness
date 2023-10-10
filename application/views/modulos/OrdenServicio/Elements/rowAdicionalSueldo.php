<tr>
	<td>
		<select class="ui dropdown clearable semantic-dropdown parentDependienteSemantic fluid" name="cargoSueldoAdicional" data-childDependiente=".cboPersonal" data-closest="tr">
			<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cargos, 'id' => 'idCargoTrabajo', 'value' => 'cargo', 'simple' => true, 'class' => 'text-titlecase']); ?>
		</select>
	</td>
	<td>
		<select class="ui dropdown clearable semantic-dropdown read-only childdependienteSemantic fluid cboPersonal" name="empleadoSueldoAdicional">
			<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $empleados, 'simple' => true, 'class' => 'text-titlecase']); ?>
		</select>
	</td>
	<td>
		<div class="ui input">
			<input class="onlyNumbers keyUpChange montoSueldoAdicional" patron="requerido" type="text" value="0" name="montoSueldoAdicional" onchange="$('#calculateTablaSueldo').click();">
		</div>
	</td>
	<td>
		<div class="ui input">
			<input class="onlyNumbers keyUpChange movilidadSueldoAdicional" type="text" value="0" name="movilidadSueldoAdicional" onchange="OrdenServicio.calcularMovilidad();">
		</div>
	</td>
</tr>