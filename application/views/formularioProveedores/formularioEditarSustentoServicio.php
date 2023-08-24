<form class="form" role="form" id="formEdicionSustentoServicio" method="post">
	<input type="hidden" name="idCotizacionDetalleProveedorSustentoCompra" value="<?= $idCotizacionDetalleProveedorSustentoCompra ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Archivos</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<div class="form-control custom-file">
						<input type="file" class="custom-file-input files-uploadedd file-uploadedd" lang="es" accept="image/*, .ppt, .pdf, .xlsx">
						<label class="custom-file-label labelImagen" lang="es">Agregar Archivos</label>
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