<div>
	<div class="row child-divcenter">
		<img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
	</div>
	<div class="mb-3 card child-divcenter" style="width:75%">
		<div class="card-head text-center" style="margin-top: 10px;">
			<h2>Cargar Peso</h2>
			<hr>
		</div>

		<div class="card-body">
			<form class="form" role="form" id="formRegistroPeso" method="post" autocomplete="off">
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Indicados</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-3" style="border:0px;">Ítem :</label>
								<input class="form-control col-md-9" value="<?= $item['nombre'] ?>" readonly>
								<input type="hidden" name="idItemLogistica" value="<?= $item['idItemLogistica']; ?>">
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-3" style="border:0px;">Fecha Emisión :</label>
								<input class="form-control col-md-9" value="<?= date_change_format($item['fechaEmision']) ?>" readonly>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border" style="padding: 1.4em 1.4em 1.4em 1.4em !important;">
							<div class="mb-2 child-divcenter row" style="width:85%">
								<label class="form-control col-md-3" style="border:0px;">Peso :</label>
								<input class="form-control col-md-9" name="peso" patron="requerido" value="">
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 child-divcenter" style="text-align: right;">
						<button class="btn btn-outline-primary" id="btn-registroPeso" style="width: 25%;" value="Enviar">
							<i class="fas fa-paper-plane"></i> Enviar
						</button>
					</div>
				</div>
			</form>
		</div>

	</div>