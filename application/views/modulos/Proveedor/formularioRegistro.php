<form class="form" role="form" id="formRegistroProveedores" method="post">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="razonSocial" style="border:0px;">Razón Social :</label>
					<input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
					<input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido,ruc">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="rubro" style="border:0px;">Actividad :</label>
					<select class="form-control col-md-8 my_select2" id="rubro" name="rubro" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $rubro, 'class' => 'text-titlecase', 'data-option' => ['codigoSunat']] ); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="metodoPago" style="border:0px;">Forma de pago :</label>
					<select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $metodoPago, 'class' => 'text-titlecase']); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="tipoServicio" style="border:0px;">Tipo de Servicio :</label>
					<select class="form-control col-md-8 my_select2" name="tipoServicio" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipoServicio, 'class' => 'text-titlecase', 'id' => 'idProveedorTipoServicio', 'value' => 'nombre'] ); ?>
					</select>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Dirección</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="region" style="border:0px;">Región :</label>
					<select class="form-control col-md-8" id="region" name="region">
						<option value="">Seleccione</option>
						<?
						foreach ($departamento as $k_dp => $v_dp) {
						?>
							<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>;
						<?
						}
						?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="provincia" style="border:0px;">Provincia :</label>
					<select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="distrito" style="border:0px;">Distrito :</label>
					<select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="direccion" style="border:0px;">Dirección :</label>
					<input class="form-control col-md-8" id="direccion" name="direccion">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border" style="overflow:auto; max-height:350px; min-height: 100px">
				<legend class="scheduler-border">Zonas de Cobertura</legend>
				<table class="w-100 tb-zona-cobertura">
					<thead>
						<tr>
							<th>REGION</th>
							<th>PROVINCIA</th>
							<th>DISTRITO</th>
							<th class="text-center">
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregar-zona" title="Agregar Zona"><i class="fa fa-lg fa-plus"></i></a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="d-none trParent">
							<td class="w-25">
								<select class="form-control w-100 regionCobertura" name="regionCobertura" data-live-search="true" patron="requerido" disabled>
									<?
									foreach ($departamento as $k_dp => $v_dp) {
									?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
									<?
									}
									?>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control  w-100 provinciaCobertura" name="provinciaCobertura" data-live-search="true" disabled>
									<option value="">Seleccione</option>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control w-100 distritoCobertura" name="distritoCobertura" data-live-search="true" disabled>
									<option value="">Seleccione</option>
								</select>
							</td>
							<td class="w-25 text-center">
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-eliminar-zona" title="Eliminar Zona"><i class="fa fa-lg fa-trash"></i></a>
							</td>
						</tr>
						<tr class="trChildren">
							<td class="w-25">
								<select class="form-control w-100 regionCobertura" name="regionCobertura" data-live-search="true" patron="requerido">
									<option value="">Seleccione</option>
									<?
									foreach ($departamento as $k_dp => $v_dp) {
									?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
									<?
									}
									?>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control  w-100 provinciaCobertura" name="provinciaCobertura" data-live-search="true">
									<option value="">Seleccione</option>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control w-100 distritoCobertura" name="distritoCobertura" data-live-search="true">
									<option value="">Seleccione</option>
								</select>
							</td>
							<td class="w-25 text-center">
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-eliminar-zona" title="Eliminar Zona"><i class="fa fa-lg fa-trash"></i></a>
							</td>
						</tr>
					</tbody>
				</table>

			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Contacto</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre :</label>
					<input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="numeroContacto" style="border:0px;">Número :</label>
					<input class="form-control col-md-8" id="numeroContacto" name="numeroContacto" patron="requerido,numeros">
				</div>
				<div class="mb-2 input-group control-group child-divcenter row" style="width:85%">
					<label class="form-control col-md-4" for="correoContacto" style="border:0px;">Correo :</label>
					<input class="form-control col-md-8" id="correoContacto" name="correoContacto" patron="requerido,email">
					<div class="input-group-append" id="button-addon4">
						<button class="btn btn-outline-success btnAddCorreo" type="button"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div id="extraCorreo"></div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Costo</legend>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control col-md-4" style="border:0px;">Costo (S/) :</label>
					<input class="form-control col-md-8" id="costo" name="costo" patron="numeros" value="0">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Información adicional</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<textarea class="form-control col-md-12" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..."></textarea>
				</div>
			</fieldset>
		</div>
	</div>
</form>
<script>
	var provincia = <?= json_encode($provincia); ?>;
	var distrito = <?= json_encode($distrito); ?>;
	var distrito_ubigeo = <?= json_encode($distrito_ubigeo); ?>;

	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>