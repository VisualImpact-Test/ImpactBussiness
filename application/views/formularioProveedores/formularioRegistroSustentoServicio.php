<form class="form" role="form" id="formRegistroSustentoServicio" method="post">
	<input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $idCotizacionDetalleProveedor ?>">
	<input type="hidden" name="idCotizacion" value="<?= $idCotizacion ?>">
	<input type="hidden" name="idProveedor" value="<?= $idProveedor ?>">
	<input type="hidden" name="ordencompra" value="<?= $ordencompra?>">
	<input type="hidden" name="flagoclibre" value="<?= $flagoclibre?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Enlaces</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<textarea class="form-control col-md-12" name="enlaces" style="resize: none; height:100px;" placeholder=""></textarea>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Archivos</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<div class="form-control custom-file">
						<input type="file" class="custom-file-input files-uploadedd file-uploadedd" lang="es" multiple accept="image/*, .ppt, .pdf, .xlsx">
						<label class="custom-file-label labelImagen" lang="es">Agregar Imagen</label>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>