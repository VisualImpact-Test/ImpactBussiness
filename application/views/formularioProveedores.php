<div>
	<div class="row child-divcenter">
		<img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
	</div>
	<div class="mb-3 card child-divcenter" style="width:75%">
		<div class="card-head text-center" style="margin-top: 10px;">
			<h2>Registro de Proveedores</h2>
			<hr>
		</div>
		<div class="card-body">
			<form class="form" role="form" id="formRegistroProveedores" method="post" autocomplete="off">
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Generales</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Razón Social :</label>
								<input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido">
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">RUC :</label>
								<input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido,ruc">
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Actividad :</label>
								<select class="form-control col-md-8 my_select2" id="rubro" name="rubro" multiple data-live-search="true" patron="requerido">
									<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $rubro, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Forma de pago :</label>
								<select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" multiple data-live-search="true" patron="requerido">
									<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $metodoPago, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control border-0 col-md-4">Tipo de Servicio :</label>
								<select class="form-control col-md-8 my_select2" name="tipoServicio" patron="requerido" multiple data-live-search="true">
									<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipoServicio, 'class' => 'text-titlecase', 'id' => 'idProveedorTipoServicio', 'value' => 'nombre']); ?>
								</select>
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
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Direccion</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Region :</label>
								<select class="form-control col-md-8" id="region" name="region" patron="requerido">
									<?php foreach ($departamento as $k_dp => $v_dp) : ?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>;
									<?php endforeach; ?>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Provincia :</label>
								<select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
									<option value="">Seleccione</option>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Distrito :</label>
								<select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
									<option value="">Seleccione</option>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Direccion :</label>
								<input class="form-control col-md-8" id="direccion" name="direccion" patron="requerido">
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border" style="overflow:auto; max-height:250px; min-height: 100px">
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
												<?php endforeach; ?>
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
												<?php foreach ($departamento as $k_dp => $v_dp) : ?>
													<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
												<?php endforeach; ?>
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
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Contacto</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Nombre :</label>
								<input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido">
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Número :</label>
								<input class="form-control col-md-8" id="numeroContacto" name="numeroContacto" patron="requerido,numeros">
							</div>
							<div class="input-group control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Correo :</label>
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
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Información Bancaria</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control border-0 col-md-4">Banco</label>
								<select class="form-control col-md-8 simpleDropdown" name="banco" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Banco', 'id' => 'idBanco', 'value' => 'nombre', 'query' => $bancos, 'class' => 'text-titlecase']); ?>
								</select>
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control border-0 col-md-4">Tipo Cuenta</label>
								<select class="form-control col-md-8 simpleDropdown" name="tipoCuenta" patron="requerido">
									<?= htmlSelectOptionArray2(['title' => 'Tipo Cuenta', 'id' => 'idTipoCuentaBanco', 'value' => 'nombre', 'query' => $tiposCuentaBanco, 'class' => 'text-titlecase']); ?>
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
								<div class="divParaCarga col-md-8 pl-0" style="width:85%">
									<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaPrincipal']) ?>
								</div>
							</div>
							<div class="control-group child-divcenter row pt-2" style="width:85%">
								<label class="form-control border-0 col-md-4">Incluir Detracción</label>
								<div class="ui test toggle checkbox">
									<input class="chkDetraccion" name="chkDetraccion" type="checkbox">
								</div>
							</div>
							<div class="control-group child-divcenter row pt-2 detraccion d-none" style="width:85%">
								<label class="form-control border-0 col-md-4">Cuenta detracción</label>
								<input class="form-control col-md-8 cuentaDetraccion" name="cuentaDetraccion">
							</div>
							<div class="control-group child-divcenter row pt-2 detraccion d-none" style="width:85%">
								<label class="form-control border-0 col-md-4">Captura de Cuenta Detracción</label>
								<div class="divParaCargaDetraccion col-md-8 pl-0" style="width:85%">
									<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCargaDetraccion', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaDetraccion']) ?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Información adicional</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<textarea class="form-control col-md-12" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..."></textarea>
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
	</div>

	<script>
		var provincia = <?= json_encode($provincia); ?>;
		var distrito = <?= json_encode($distrito); ?>;
		var distrito_ubigeo = <?= json_encode($distrito_ubigeo); ?>;
	</script>