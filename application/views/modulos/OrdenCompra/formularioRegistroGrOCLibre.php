<form class="ui form" role="form" id="formRegistroGrOrdenCompraLibre" method="post" autoComplete="off">
	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Datos GR</legend>
		<div class="ui form attached fluid segment p-4">
			<div class="fields">
				<div class="field">
					<a class="ui btn btn-trade-visual" onclick='Oc.addGr()'>Agregar GR</a>
					<a class="ui btn btn-danger" onclick='Oc.deleteGr()'>Eliminar GR</a>
					<input type="hidden" name="idOrdenCompra" value="<?= $idOrdenCompra ?>" patron="requerido">
				</div>
			</div>
			<?php if (empty($dataCargada)) : ?>
				<div id="grBase">
					<div class="fields">
						<div class="seven wide field">
							<div class="ui sub header">GR Número</div>
							<input type="text" name="numeroGr" value="" patron="requerido">
						</div>
						<div class="seven wide field">
							<div class="ui sub header">Fecha Gr</div>
							<div class="ui calendar date-semantic">
								<div class="ui input left icon">
									<i class="calendar icon"></i>
									<input type="text" placeholder="Fecha Gr" value="" readonly>
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaGr" placeholder="Fecha Gr" value="" patron="requerido">
						</div>
					</div>
				</div>
				<div id="grAdicional"></div>
			<?php else : ?>
				<div id="grBase" class="d-none">
					<div class="fields">
						<div class="seven wide field">
							<div class="ui sub header">GR Número</div>
							<input type="text" name="numeroGr" value="" patron="requerido">
						</div>
						<div class="seven wide field">
							<div class="ui sub header">Fecha Gr</div>
							<div class="ui calendar date-semantic">
								<div class="ui input left icon">
									<i class="calendar icon"></i>
									<input type="text" placeholder="Fecha Gr" value="" readonly>
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaGr" placeholder="Fecha Gr" value="" patron="requerido">
						</div>
					</div>
				</div>
				<div id="grAdicional">
					<?php foreach ($dataCargada as $k => $v) : ?>
						<div class="fields">
							<div class="seven wide field">
								<div class="ui sub header">GR Número</div>
								<input type="text" name="numeroGr" value="<?= $v['numeroGr'] ?>" patron="requerido">
							</div>
							<div class="seven wide field">
								<div class="ui sub header">Fecha Gr</div>
								<div class="ui calendar date-semantic">
									<div class="ui input left icon">
										<i class="calendar icon"></i>
										<input type="text" placeholder="Fecha Gr" value="<?= $v['fechaGr'] ?>" readonly>
									</div>
								</div>
								<input type="hidden" class="date-semantic-value" name="fechaGr" placeholder="Fecha Gr" value="<?= $v['fechaGr'] ?>" patron="requerido">
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</fieldset>

</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
	<?php if (!empty($ordenServicioDocumento)) : ?>
		OrdenServicio.documentoCont = <?= count($ordenServicioDocumento) ?>
	<?php endif; ?>
</script>