<style>
	.detail {
		background: none !important;
	}
</style>
<form class="form" role="form" id="formRegistrodatosOc" method="post" autocomplete="off">
	<div class="child-divcenter" style="width:90%">
		<input type="hidden" name="idOrdenServicio" id="idOrdenServicio" value="<?= $idOrdenServicio ?>">
		<input type="hidden" name="idOrdenServicioDatosOc" id="idOrdenServicioDatosOc" value="<?= !empty($datosOC[0]['idOrdenServicioDatosOc']) ? $datosOC[0]['idOrdenServicioDatosOc'] : '' ?>">
		<div class="ui form">
			<div class="fields">
				<div class="sixteen wide field">
					<label>Código OC: </label>
					<input id="motivo" name="codigo_oc" patron="" placeholder="Código Orden de compra" value="<?= !empty($datosOC[0]['codigoOc']) ? $datosOC[0]['codigoOc'] : '' ?>">
				</div>
			</div>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Monto de OC:</label>
					<input class="onlyNumbers" id="motivo" name="monto_oc" patron="" placeholder="Monto de compra" value="<?= !empty($datosOC[0]['montoOc']) ? $datosOC[0]['montoOc'] : '' ?>">
				</div>
			</div>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Fecha OC: </label>
					<div class="ui calendar date-semantic">
						<div class="ui input left icon fluid">
							<i class="calendar icon"></i>
							<input type="text" placeholder="Fecha OC" value="<?= !empty($datosOC[0]['fechaOC']) ? $datosOC[0]['fechaOC'] : '' ?>">
						</div>
					</div>
					<input type="hidden" class="date-semantic-value" name="fechaClienteOC" placeholder="Fecha OC" value="<?= !empty($datosOC[0]['fechaOC']) ? $datosOC[0]['fechaOC'] : '' ?>">
				</div>
			</div>
			<div class="fields">
				<div class="sixteen wide field">
					<div class="content-lsck-capturas">
						<input data-file-max="1" data-show-name="true" type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept=".pdf" multiple="">
						<div class="fields ">
							<div class="sixteen wide field">
								<div class="ui small images content-lsck-galeria">

								</div>
							</div>
						</div>
						<div class="fields ">
							<div class="sixteen wide field">
								<div class="ui small images content-lsck-files">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Descripción de OC:</label>
					<input id="motivo" name="motivo" patron="" placeholder="Descripción de Orden de Compra" value="<?= !empty($datosOC[0]['descripcionOc']) ? $datosOC[0]['descripcionOc'] : '' ?>">
				</div>
			</div>
		</div>
	</div>
</form>