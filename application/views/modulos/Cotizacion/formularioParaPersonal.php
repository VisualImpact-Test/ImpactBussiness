<div class="ui form attached fluid segment p-4">
	<form class="ui form" role="form" id="formAsignarItemEnPersonal" method="post">
		<h4 class="ui dividing header">Lista de Items (No personal) </h4>
		<?php foreach ($otros as $k => $v) : ?>
			<div class="fields">
				<div class="sixteen wide field">
					<div class="ui sub header"><?= $v['nombre'] ?></div>
					<select class="ui simpleDropdown" name="idCotizacionDetallePersonal">
						<?= htmlSelectOptionArray2([
							'id' => 'idCotizacionDetalle', 'value' => 'nombre',
							'query' => $personal, 'class' => 'text-titlecase'
						]); ?>
					</select>
					<input type="hidden" name="idCotizacionDetalle" value="<?= $v['idCotizacionDetalle'] ?>">
				</div>
			</div>
		<?php endforeach; ?>
	</form>
</div>