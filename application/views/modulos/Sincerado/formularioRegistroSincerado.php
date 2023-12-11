<form class="ui form"  id="formSincerado" method="post" autocomplete="off">

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
			
		</div>
	</div>
	<div class="fields">
		<div class="eight wide field">
			<div class="ui sub header">Comentario</div>
			<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"></textarea>
		</div>
		<div class="eight wide field anexos">
			<div class="ui sub header">Anexos</div>
			<div class="ui small images content-lsck-capturas">
				<div class="content-lsck-galeria content-lsck-files">
					<div class="ui small image text-center btn-add-file">
						<div class="ui dimmer">
							<div class="content">
								<div class="ui small primary button" onclick="$(this).parents('.anexos').find('.file-lsck-capturas-anexos').click();">
									Agregar
								</div>
							</div>
						</div>
						<img class="ui image" src="<?= IMG_WIREFRAME ?>">
					</div>
					<input type="file" name="capturas" class="file-lsck-capturas-anexos form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*, .xlsx, .pdf, .xlsm" multiple="">
				</div>
			</div>
		</div>
	</div>
	
	<h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
	</h4>
	<div class="default-item">
		<div class="ui segment body-item nuevo">
			<div class="ui right floated header">
				<div class="ui icon menu">
					
				</div>
			</div>
			<div class="ui left floated header">
					<span class="ui medium text ">Detalle N. <span class="title-n-detalle">00001</span></span>
			</div>
			<div class="ui clearing divider"></div>
			<?php foreach ($datoSincerado as $key => $row) : ?>
			<div class="ui grid">
					<div class="columna_itemss sixteen wide tablet twelve wide computer column itemDet_1">
					
						<div class="fields">
						
							<div class="eight wide field">
									<div class="ui sub header">Item</div>
									<input name="items" placeholder="items" value = "<?= $row['descripcionTipoPresupuestoDetalle'] ?>">
							</div>	
							<div class="five wide field">
									<div class="ui sub header">costo</div>
									<input class="onlynumbers" name="monto" placeholder="monto" value = "<?= $row['monto'] ?>">
							</div>	
						</div>
						
					</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

</form>

<script>
	$(document).ready(function() {
		$('#filtroOper').DataTable();
	});
</script>