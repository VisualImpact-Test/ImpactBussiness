<form class="form" role="form" id="formRegistroSustento" method="post" autocomplete="off">
	<input type="hidden" name="proveedor" value="<?= $proveedor ?>">
	<input type="hidden" name="cotizacion" value="<?= $cotizacion ?>">
	<input type="hidden" name="ordencompra" value="<?= $ordencompra ?>">
	<input type="hidden" name="flag" value="<?= $flag ?>">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Guia</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Guia</label>
					<div class="form-control custom-file col-md-8">
						<input type="file" class="custom-file-input files-upload_guia file-upload_guia" lang="es" accept="image/*, .pdf" patron="<?= $requiereguia == 1 ? 'requerido' : ''; ?>">
						<label class="custom-file-label labelImagen" lang="es">Agregar Archivos</label>
					</div>
					<label class="form-control col-md-4" style="border:0px;">N° Guia</label>
					<input class="form-control col-md-8" id="nguia" name="nguia" value="">
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Factura</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Factura</label>
					<div class="form-control custom-file col-md-8">
						<input type="file" class="custom-file-input files-upload_factura file-upload_factura" lang="es" accept=".pdf" patron="requerido">
						<label class="custom-file-label labelImagen" lang="es">Agregar Archivos</label>
					</div>
					<label class="form-control col-md-4" style="border:0px;">N° Factura</label>
					<input class="form-control col-md-8" id="nfactura" name="nfactura" patron="requerido">
					<label class="form-control col-md-4" style="border:0px;">Fecha Emisión</label>
					<input type="date" class="form-control col-md-8" id="fechaEmision" name="fechaEmision" patron="requerido">
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Otros</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">XML</label>
					<div class="form-control custom-file col-md-8">
						<input type="file" class="custom-file-input files-upload_xml file-upload_xml" lang="es" accept=".xml, .zip" patron="requerido">
						<label class="custom-file-label labelImagen" lang="es">Agregar Archivos</label>
					</div>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Doc Adicional</label>
					<div class="form-control custom-file col-md-8">
						<input type="file" class="custom-file-input files-upload_da file-upload_da" lang="es" accept=".xlsx, .zip" multiple>
						<label class="custom-file-label labelImagen" lang="es">Agregar Archivos</label>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Adicional</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Comentario</label>
					<select name="incidencia" class="form-control col-md-8">
						<option value="1">Finalizado con Incidencia</option>
						<option value="0">Finalizado al 100%</option>
					</select>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<div class="ui bottom attached warning message">
				<i class="icon warning"></i>CONSIDERAR QUE LA FACTURA CARGADA DEBE TENER FECHA DE EMISION DEL MES EN CURSO
			</div>
			<div class="ui bottom attached warning message">
				<i class="icon warning"></i>SUBIR SUSTENTOS DE LUNES A VIERNES DE 00:00 AM HASTA LAS 14:00
			</div>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>