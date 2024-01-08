<form class="form" role="form" id="formRegistroProveedores" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Razón Social :</label>
					<input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">RUC :</label>
					<input class="form-control col-md-8 onlyNumbers" id="ruc" name="ruc" patron="requerido,ruc">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Actividad :</label>
					<select class="form-control col-md-8 my_select2" id="rubro" name="rubro" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $rubro, 'class' => 'text-titlecase', 'data-option' => ['codigoSunat']]); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Forma de pago :</label>
					<select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $metodoPago, 'class' => 'text-titlecase']); ?>
					</select>
				</div>
				<div class="mb-2 input-group control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Tipo de Servicio :</label>
					<select class="form-control col-md-8 my_select2" name="tipoServicio" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipoServicio, 'class' => 'text-titlecase', 'id' => 'idProveedorTipoServicio', 'value' => 'nombre']); ?>
					</select>
					<div class="input-group-append align-items-center" id="button-addon4">
					<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregar-tipo-servicio" title="Agregar Tipo Servicio"><i class="fa fa-lg fa-plus"></i></a>
					</div>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Tipo de comprobante :</label>
					<select class="form-control col-md-8 my_select2" id="comprobante" name="comprobante" patron="requerido" multiple data-live-search="true">
						<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $comprobante, 'class' => 'text-titlecase', 'data-option' => ['idComprobante']]); ?>
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
					<label class="form-control border-0 col-md-4">Región :</label>
					<select class="form-control col-md-8" id="region" name="region">
						<option value="">Seleccione</option>
						<?php foreach ($departamento as $k_dp => $v_dp) : ?>
							<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Provincia :</label>
					<select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Distrito :</label>
					<select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Dirección :</label>
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
									<?php foreach ($departamento as $k_dp => $v_dp) : ?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control w-100 provinciaCobertura" name="provinciaCobertura" data-live-search="true" disabled>
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
									<?php foreach ($departamento as $k_dp => $v_dp) : ?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td class="w-25">
								<select class="form-control w-100 provinciaCobertura" name="provinciaCobertura" data-live-search="true">
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
					<label class="form-control border-0 col-md-4">Nombre :</label>
					<input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido">
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Número :</label>
					<input class="form-control col-md-8 onlyNumbers" id="numeroContacto" name="numeroContacto" patron="requerido,numeros">
				</div>
				<div class="mb-2 input-group control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Correo :</label>
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
					<label class="form-control border-0 col-md-4">Costo (S/) :</label>
					<input class="form-control col-md-8 onlyNumbers" id="costo" name="costo" patron="numeros" value="0">
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Información Bancaria</legend>
				<div class="row InfoBancData" id="divInfoBancData">
					<div class="form-row order-md-1 divItem pt-3 border-bottom">
						<div class="control-group child-divcenter row" style="width:85%">
							<label class="form-control border-0 col-md-4">Banco</label>
							<select class="form-control banco col-md-8 simpleDropdown" name="banco" patron="requerido">
								<?= htmlSelectOptionArray2(['title' => 'Banco', 'id' => 'idBanco', 'value' => 'nombre', 'query' => $bancos, 'class' => 'text-titlecase']); ?>
							</select>
						</div>
						<div class="control-group child-divcenter row" style="width:85%">
							<label class="form-control border-0 col-md-4">Tipo Cuenta</label>
							<select class="form-control col-md-8 simpleDropdown" name="tipoCuenta" patron="requerido">
								<?= htmlSelectOptionArray2(['title' => 'Tipo Cuenta', 'id' => 'idTipoCuentaBanco', 'value' => 'nombre', 'query' => $tiposCuentaBanco, 'class' => 'text-titlecase']); ?>
							</select>
						</div>
						<div class="control-group child-divcenter row" style="width:85%">
							<label class="form-control border-0 col-md-4">Moneda</label>
							<select class="form-control moneda col-md-8 simpleDropdown" name="moneda" patron="requerido">
								<?= htmlSelectOptionArray2(['title' => 'Moneda', 'id' => 'idMoneda', 'value' => 'nombre', 'query' => $moneda, 'class' => 'text-titlecase']); ?>
							</select>
						</div>
						<div class="control-group child-divcenter row pt-2" style="width:85%">
							<label class="form-control border-0 col-md-4">Nº de Cuenta</label>
							<input class="form-control col-md-8" name="cuentaPrincipal" patron="requerido" value="">
						</div>
						<div class="control-group child-divcenter row pt-2" style="width:85%">
							<label class="form-control border-0 col-md-4">CCI</label>
							<input class="form-control col-md-8" name="cuentaInterbancariaPrincipal" patron="requerido" value="">
						</div>
						<div class="control-group child-divcenter row pt-2" style="width:85%">
							<label class="form-control border-0 col-md-4">Captura de Cuenta</label>
							<div class="divImgCuenta col-md-8 pl-0" style="width:85%">
								<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divImgCuenta', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaPrincipal']) ?>
							</div>
						</div>
						<label class="form-control border-0 col-md-4"></label>
						<div class="control-group child-divcenter row pt-2" style="width:85%">
							<label class="form-control border-0 col-md-2"></label>
							<div class="form-group col-md-8" onclick="Proveedor.quitarInfBancaria(this, this.value);">
								<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
							</div>
						</div>
					</div>
				</div>
				<div class="extraInfoBanc">
				</div>
				<div class="control-group child-divcenter row pt-2" style="width:92%">
					<label class="form-control border-0 col-md-2"></label>
					<div class="form-group col-md-8" onclick="Proveedor.generarInfBancaria(this, this.value);">
						<a class="form-control btn btn-info"><i class="fa fa-plus"></i> Agregar</a>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Detracción</legend>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">Incluir Detracción</label>
					<div class="ui test toggle checkbox">
						<input class="chkDetraccion" name="chkDetraccion" type="checkbox">
					</div>
				</div>
				<div class="control-group child-divcenter row pt-2 detraccion d-none" style="width:85%">
					<label class="form-control border-0 col-md-4">Nº Cuenta Detracción</label>
					<input class="form-control col-md-8 cuentaDetraccion" name="cuentaDetraccion">
				</div>
				<div class="control-group child-divcenter row pt-2 detraccion d-none" style="width:85%">
					<label class="form-control border-0 col-md-4">Captura de Cuenta Detracción</label>
					<div class="divParaCarga col-md-8 pl-0" style="width:85%">
						<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaDetraccion']) ?>
					</div>
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
<input id="itemsData" type="hidden" value='<?= json_encode($bancos) ?>'>
<script>
	var provincia = <?= json_encode($provincia); ?>;
	var distrito = <?= json_encode($distrito); ?>;
	var distrito_ubigeo = <?= json_encode($distrito_ubigeo); ?>;

	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>