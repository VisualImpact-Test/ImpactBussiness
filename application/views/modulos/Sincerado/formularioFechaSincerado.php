<form class="form" role="form" id="formFechaSincerado" method="post" autocomplete="off">
	<div class="fields">
		<div class="six wide field fluid">
			<input type="hidden" name="idPresupuestoValido" value="<?php echo $idPresupuestoValido ?>">
			<select class="ui dropdown fluid parentDependiente centro-visible" id="fechaSincerado" name="fechaSincerado" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $fechaSincerado, 'simple' => true, 'class' => 'text-titlecase']); ?>
			</select>
		</div>
	</div>
</form>