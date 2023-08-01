<form class="form" role="form" id="formRegistroFechaEjecucion" method="post">
	<input type="hidden" name="proveedor" value="<?= $proveedor ?>">
	<input type="hidden" name="cotizacion" value="<?= $cotizacion ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Fecha Inicial</label>
					<input class="form-control col-md-8" type="date" name="fechaIni">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Fecha Final</label>
					<input class="form-control col-md-8" type="date" name="fechaFin">
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
						<input type="file" class="custom-file-input files-uploadedd file-uploadedd" lang="es" multiple>
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