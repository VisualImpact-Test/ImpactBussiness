<div>
	<div class="row child-divcenter">
		<img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
	</div>
	<div class="mb-3 card child-divcenter" style="width:75%">
		<div class="card-head text-center" style="margin-top: 10px;">
			<?php if (empty($documento['extension'])) :  ?>
				<h2>Adjuntar Documento</h2>
			<?php else : ?>
				<h2>Documento Adjunto Correctamente</h2>
			<?php endif; ?>
			<hr>
		</div>
		<?php if (empty($documento['extension'])) :  ?>
			<div class="card-body">
				<form class="form" role="form" id="formRegistrarDocumento" method="post" autocomplete="off">
					<div class="row">
						<div class="col-md-8 child-divcenter">
							<fieldset class="scheduler-border">
								<legend class="scheduler-border">Datos Indicados</legend>
								<div class="control-group child-divcenter row" style="width:85%">
									<label class="form-control col-md-4" style="border:0px;">Documento :</label>
									<input type="hidden" name="idDocumento" value="<?= $documento['idDocumento'] ?>" patron="requerido">
									<input class="form-control col-md-8" value="<?= $documento['documento'] ?>">
								</div>
								<div class="control-group child-divcenter row" style="width:85%">
									<label class="form-control col-md-4" style="border:0px;">Area :</label>
									<input class="form-control col-md-8" value="<?= $documento['area'] ?>">
								</div>
								<div class="control-group child-divcenter row" style="width:85%">
									<label class="form-control col-md-4" style="border:0px;">Personal :</label>
									<input class="form-control col-md-8" value="<?= $documento['personal'] ?>">
								</div>
							</fieldset>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 child-divcenter">
							<fieldset class="scheduler-border">
								<div class="control-group child-divcenter row pt-4" style="width:85%">
									<label class="form-control col-md-4" for="nombre" style="border:0px;">Archivo :</label>
									<div class="custom-file col-md-8">
										<input type="file" name="fileDocumento" class="custom-file-input files-upload file-upload" lang="es">
										<label class="custom-file-label" lang="es">Subir Documento</label>
										<input type="hidden" id="f_item" name="file-item" patron="requerido">
										<input type="hidden" id="f_name" name="file-name" patron="requerido">
										<input type="hidden" id="f_type" name="file-type" patron="requerido">
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 child-divcenter" style="text-align: right;">
							<button class="btn btn-outline-primary" id="btnEnviar" style="width: 25%;" value="Enviar">
								<i class="fas fa-paper-plane"></i> Enviar
							</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>
	</div>