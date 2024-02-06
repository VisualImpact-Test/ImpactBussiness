<form class="ui form" role="form" id="formRegistroTrackingDatosAdicionales" method="post" autoComplete="off">
	<div class="ui form attached fluid segment p-4">
		<h4 class="ui dividing header text-uppercase">Datos Adicionales</h4>
		<div class="fields">
			<div class="sixteen wide field">
				<div class="ui sub header">Fecha de Ejecución Estimada</div>
				<input type="text" class="ui" name="fechaEstimada" value="<?= isset($datosAdicionales[0]['fechaEstimadaEjecucion']) ? $datosAdicionales[0]['fechaEstimadaEjecucion'] : ''; ?>">
				<input type="hidden" class="ui" name="idSinceradoGr" value="<?= $idSinceradoGr ?>">
				<input type="hidden" class="ui" name="idOrdenServicio" value="<?= $idOrdenServicio ?>">
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<div class="ui sub header">Comentario</div>
				<input type="text" class="ui" name="comentario" value="<?= isset($datosAdicionales[0]['comentario']) ? $datosAdicionales[0]['comentario'] : ''; ?>">
				<?php if (!empty($datosAdicionales)) : ?>
					<input type="hidden" class="ui" name="idTrackingDatosAdicionales" value="<?= $datosAdicionales[0]['idTrackingDatosAdicionales'] ?>">
				<?php endif; ?>
			</div>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>