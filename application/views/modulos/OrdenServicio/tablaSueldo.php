<div class="col-md-11 child-divcenter" style="width: 100%">
	<div class="ui top attached tabular menu">
		<a class="item active" data-tab="first">Sueldo</a>
		<a class="item" data-tab="second">Calculo</a>
	</div>
	<div class="ui bottom attached tab segment active" data-tab="first">
		Datos de Sueldo
	</div>
	<div class="ui bottom attached tab segment" data-tab="second">
		<div><a id="btnSueldo" class="btn btn-success" href="javascript:;">Agregar Fila</a></div>
		<table class="ui table">
			<thead>
				<tr class="trSueldo">
					<th class="one wide">Tipo</th>
					<th class="two wide">Sueldo</th>
					<th class="two wide">% CL</th>
					<?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
						<th id="th_P<?= $personaContador ?>">-</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody id="bodySueldo">
				<tr>
					<td><input class="form-control tipoSueldo" value="1"></td>
					<td>
						<select class="ui search dropdown semantic-dropdown cboSueldo">
							<option value="">Sueldo</option>
							<option value="1" data-tipo="1">Salario</option>
							<option value="2" data-tipo="1">Asignación Familiar</option>
							<option value="3" data-tipo="2">Movilidad</option>
							<option value="4" data-tipo="3">Comision Variable</option>
						</select>
					</td>
					<td><input class="form-control tipoSueldo" value="0"></td>
					<?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
						<td><input class="form-control dSueldo" data-persona="<?= $personaContador ?>" data-sueldo="0" value="0"></td>
					<?php endfor; ?>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="center aligned" colspan="3">Total</td>
					<?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
						<td><label id="sTotal-<?= $personaContador ?>">3000</label></td>
					<?php endfor; ?>
				</tr>
			</tfoot>
		</table>
		<div><a id="btnBeneficio" class="btn btn-success" href="javascript:;">Agregar Fila</a></div>
		<table class="ui table">
			<thead class="">
				<tr>
					<th class="one wide">Tipo</th>
					<th class="two wide">Sueldo</th>
					<th class="two wide">% CL</th>
					<?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
						<th id="thP<?= $personaContador ?>">-</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody id="bodyBeneficio">
			</tbody>
			<tfoot>
				<tr>
					<td class="center aligned" colspan="3">Total</td>
					<?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
						<td><label id="sTotal-<?= $personaContador ?>">3000</label></td>
					<?php endfor; ?>
				</tr>
			</tfoot>
		</table>
	</div>
</div>