<form class="form" role="form" id="formEdicionValidacionArte" method="post">
	<input type="hidden" name="idValidacionArte" value="<?= $idValidacionArte ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Indicar Arte desde:</legend>
				<div class="control-group child-divcenter row" style="width:85%">
				<select name="opcion" class="form-control col-md-12" onchange="$(this).closest('.form').find('.dEn').toggleClass('d-none'); $(this).closest('.form').find('.dAr').toggleClass('d-none');">
					<option value="1">Enlace</option>
					<option value="2">Documento Adjunto</option>
				</select>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row dEn">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Enlace</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<input name="enlace" class="form-control col-md-12" value="">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row dAr d-none">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Archivos</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<div class="form-control custom-file">
						<input type="file" class="custom-file-input files-uploadedd file-uploadedd" lang="es">
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