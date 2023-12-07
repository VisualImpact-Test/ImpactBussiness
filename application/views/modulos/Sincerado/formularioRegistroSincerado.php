<form class="ui form" autocomplete="off">
	<div class="fields">
		<div class="six wide field">
			<div class="ui sub header">Título</div>
			<input placeholder="Título" name="titulo">
		</div>
		<div class="four wide field">
			<div class="ui sub header">Deadline compras</div>
			<div class="ui calendar date-semantic">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input type="text" placeholder="Deadline compras" value="">
				</div>
			</div>
			<input type="hidden" class="date-semantic-value" name="deadline" value="">
		</div>
		<div class="four wide field">
			<div class="ui sub header">Fecha Requerida</div>
			<div class="ui calendar date-semantic">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input type="text" placeholder="Fecha Requerida" value="">
				</div>
			</div>
			<input type="hidden" class="date-semantic-value" name="fechaRequerida" value="">
		</div>
		<div class="two wide field">
			<div class="ui sub header">Validez</div>
			<input class="onlyNumbers" placeholder="Validez" name="validez">
		</div>
	</div>
	<div class="fields">
		<div class="five wide field">
			<div class="ui sub header">Solicitante</div>
			<select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'id' => 'idSolicitante', 'value' => 'nombre']); ?>
			</select>
		</div>
		<div class="five wide field">
			<div class="ui sub header">Cuenta</div>
			<select class="ui dropdown parentDependiente centro-visible" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
			</select>
		</div>
		<div class="six wide field">
			<div class="ui sub header">Centro de costo</div>
			<select class="ui dropdown clearable semantic-dropdown centro-ocultado" id="cuentaCentroCostoForm" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $centroCosto, 'class' => 'text-titlecase']); ?>
			</select>
		</div>
	</div>
	<div class="fields">
		<div class="five wide field">
			<div class="ui sub header">Prioridad</div>
			<select class="ui dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cotizacionPrioridad, 'class' => 'text-titlecase', 'id' => 'idPrioridad', 'value' => 'nombre']); ?>
			</select>
		</div>
		<div class="eleven wide field">
			<div class="ui sub header">Motivo</div>
			<input id="motivoForm" name="motivoForm" placeholder="Motivo" value="">
		</div>
	</div>
	<div class="fields">
		<div class="five wide field">
			<div class="ui sub header">Tipo Servicio</div>
			<select class="ui dropdown semantic-dropdown clearable" name="tipoServicio" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicioCotizacion, 'class' => 'text-titlecase', 'id' => 'idTipoServicioCotizacion', 'value' => 'nombre']); ?>
			</select>
		</div>
		<div class="five wide field">
			<div class="ui sub header">Anexo</div>
			<input type="file" id="invisibleupload1" class="ui invisible file input d-none" multiple>
			<label class="ui red icon button">
				<i class="file icon"></i>
				Open any file
			</label>
		</div>
	</div>
</form>

<script>
	$(document).ready(function() {
		$('#filtroOper').DataTable();
	});
</script>