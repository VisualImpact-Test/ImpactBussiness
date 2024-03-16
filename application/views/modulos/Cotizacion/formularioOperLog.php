<form class="form" role="form" id="formOperLog" method="post" autocomplete="off">
	<input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $cabOperLog[0]['idCotizacion'] ?>">
	<div class="ui form">
		<div class="fields">
			<div class="five wide field">
				<label>Cuenta:</label>
				<input type="text" name="Cuenta" placeholder="Código Orden de compra" value="<?= $cabOperLog[0]['cuenta'] ?>">
			</div>
			<div class="five wide field">
				<label>Centro Costo:</label>
				<input type="text" name="centroCosto" placeholder="Monto de compra" value="<?= $cabOperLog[0]['cuentaCentroCosto'] ?>">
			</div>
			<div class="five wide field">
				<label>Usuario:</label>
				<select class="ui dropdown simpleDropdown search" id="CuentaUsuario" name="CuentaUsuario" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $CuentaUsuario, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
		</div>
		<div class="fields">
			<div class="five wide field">
				<label>Origen:</label>
				<select class="ui dropdown simpleDropdown search" id="AlmacenOrigen" name="AlmacenOrigen" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $Almacen, 'class' => 'text-titlecase', 'selected' => 1]); ?>
				</select>
			</div>
			<div class="three wide field">
				<label>Cotizacion:</label>
				<a href="javascript:;" download="" class="btn btn-outline-secondary border-0 btn-descargarCotizacionOper" data-id="<?= $cabOperLog[0]['idCotizacion'] ?>">
					<i class="file pdf icon large" title="Generar PDF cotizacion"></i>
				</a>

				<!-- <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cotizacion', 'visible' => false, 'tipo' => 2]) ?> -->
			</div>
			<div class="three wide field">
				<label>Orden de compra:</label>
				<?php $direccion = RUTA_WASABI . 'cotizacion/' . $cabOperLog[0]['nombre_archivo']; ?>
				<a class="btn btn-outline-secondary border-0" href="<?= $direccion ?>" target="_blank"><i class="file pdf icon large"></i></a>
				<!-- <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'ordenCompra', 'visible' => false, 'tipo' => 2]) ?> -->
			</div>
			<div class="three wide field">
				<label>Detalle OPERLOG:</label>
				<a href="javascript:;" download="" class="btn btn-outline-secondary border-0 btn-descargarOperExcel" data-id="<?= $cabOperLog[0]['idCotizacion'] ?>">
					<i class="file excel icon large" title="Generar PDF cotizacion"></i>
				</a>
			</div>
		</div>
		<?php if (!empty($distribucion)) : ?>
			<?php foreach ($distribucion as $key => $rows) : ?>
				<table class="ui celled table">
					<thead>
						<tr>
							<th class="td-center">#</th>
							<th class="td-center">Zona</th>
							<th class="td-center">Nombre</th>
							<th class="td-center">Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($rows as $row) : ?>
							<tr>
								<td class="td-center"><?= $ix = 1; ?></td>
								<td class="td-center"><?= verificarEmpty($row['zona'], 3); ?></td>
								<td class="td-center"><?= verificarEmpty($row['nombre'], 3); ?></td>
								<td class="td-center"><?= verificarEmpty($row['cantidad'], 2); ?></td>
							</tr>
							<?php $ix++; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</form>