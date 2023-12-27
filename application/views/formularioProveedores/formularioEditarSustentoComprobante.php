<form class="form" role="form" id="formEdicionSustentoComprobante" method="post">
	<input type="hidden" name="idSustentoAdjunto" value="<?= $idSustentoAdjunto ?>">
	<input type="hidden" name="idFormatoDocumento" value="<?= $idFormatoDocumento ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Archivos</legend>
				<?php if($idFormatoDocumento == 1 || $idFormatoDocumento == 2) { ?>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" style="border:0px;">N° Documento</label>
						<input class="form-control col-md-8" id="nDocumento" name="nDocumento" 
							patron="requerido" value="<?= $numeroDocumento ?>">
					</div>
				<? } ?>
				<?php if($idFormatoDocumento == 2) { ?>
					<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Fecha Emisión</label>
					<input type="date" class="form-control col-md-8" id="fechaEmision" name="fechaEmision" patron="requerido" value="<?= $fechaEmision ?>">
					</div>
				<? } ?>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;"></label>
					<div class="form-control custom-file col-md-8">
						<input type="file" class="custom-file-input files-uploadedd file-uploadedd" 
						lang="es" accept="<?= $acept; ?>" patron="requerido">
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