<form class="form" role="form" id="formActualizacionProveedores" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedor" name="idProveedor" value="<?= $idProveedor ?>">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="<?= ($disabled) ? "disabled" : "" ?>">
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="razonSocial" style="border:0px;">Razón Social :</label>
						<input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $razonSocial ?>">
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
						<input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido,ruc" value="<?= $nroDocumento ?>">
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="rubro" style="border:0px;">Actividad :</label>
						<select class="form-control col-md-8 my_select2" id="rubro" name="rubro" patron="requerido" multiple data-live-search="true">
							<?php foreach ($listadoRubros as $rubro) : ?>
								<option value="<?= $rubro['id'] ?>" <?= !empty($proveedorRubro[$rubro['id']]) ? "selected" : "" ?>> <?= $rubro['value'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="metodoPago" style="border:0px;">Forma de pago :</label>
						<select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" patron="requerido" multiple data-live-search="true">
							<?php foreach ($listadoMetodosPago as $pagos) : ?>
								<option value="<?= $pagos['id'] ?>" <?= !empty($proveedorMetodoPago[$pagos['id']]) ? "selected" : "" ?>> <?= $pagos['value'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="ruc" style="border:0px;">Tipo Servicio :</label>
						<select class="form-control col-md-8 my_select2" id="tipoServicio" name="tipoServicio" multiple data-live-search="true" patron="requerido">
							<?php foreach ($listTipoServicio as $key => $value) : ?>
								<option value="<?= $value['idProveedorTipoServicio'] ?>" <?= isset($proveedorTipoServicio[$value['idProveedorTipoServicio']]) ? "selected" : "" ?>> <?= $value['nombre'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Dirección</legend>
				<div class="<?= ($disabled) ? "disabled" : "" ?>">
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="region" style="border:0px;">Región :</label>
						<select class="form-control col-md-8" id="region" name="region" patron="requerido">
							<?php foreach ($listadoDepartamentos as $k_dp => $v_dp) : ?>
								<option value="<?= $k_dp ?>" <?= ($k_dp == $cod_departamento) ? "selected" : "" ?>><?= $v_dp['nombre'] ?></option>;
							<?php endforeach; ?>
						</select>
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="provincia" style="border:0px;">Provincia :</label>
						<select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
							<option value="<?= $cod_provincia ?>"><?= textopropio($provincia) ?></option>
						</select>
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="distrito" style="border:0px;">Distrito :</label>
						<select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
							<option value="<?= $cod_ubigeo ?>"><?= textopropio($distrito) ?></option>
						</select>
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="direccion" style="border:0px;">Dirección :</label>
						<input class="form-control col-md-8" id="direccion" name="direccion" value="<?= $direccion ?>">
					</div>
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
						<tr class="<?= ($disabled) ? "disabled" : "" ?>">
							<th>REGION</th>
							<th>PROVINCIA</th>
							<th>DISTRITO</th>
							<th class="text-center">
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregar-zona" title="Agregar Zona"><i class="fa fa-lg fa-plus"></i></a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="d-none trParent ">
							<td class="w-25">
								<select class="form-control w-100 regionCobertura" name="regionCobertura" data-live-search="true" patron="requerido" disabled>
									<?php foreach ($listadoDepartamentos as $k_dp => $v_dp) : ?>
										<option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
									<?php endforeach; ?>
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
						<?php foreach ($zonasProveedor as $key => $row) : ?>
							<tr class="trChildren <?= ($disabled) ? "disabled" : "" ?>">
								<td>

									<input type="text" class="form-control w-100" value="<?= !empty($row['departamento']) ? $row['departamento'] : "" ?>" placeholder="Departamento" disabled>
									<input type="hidden" class="form-control w-100" name="regionCobertura" value="<?= !empty($row['cod_departamento']) ? $row['cod_departamento'] : "" ?>">
								</td>
								<td>
									<input type="text" class="form-control w-100" value="<?= !empty($row['provincia']) ? $row['provincia'] : "" ?>" placeholder="Provincia" disabled>
									<input type="hidden" class="form-control w-100" name="provinciaCobertura" value="<?= !empty($row['cod_provincia']) ? $row['cod_provincia'] : "" ?>">
								</td>
								<td>
									<input type="text" class="form-control w-100" value="<?= !empty($row['distrito']) ? $row['distrito'] : "" ?>" placeholder="Distrito" disabled>
									<input type="hidden" class="form-control w-100" name="distritoCobertura" value="<?= !empty($row['cod_distrito']) ? $row['cod_distrito'] : "" ?>">
								</td>
								<td class="w-25 text-center">
									<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-eliminar-zona" title="Eliminar Zona"><i class="fa fa-lg fa-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Contacto</legend>
				<div class="<?= ($disabled) ? "disabled" : "" ?>">
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre :</label>
						<input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido" value="<?= $nombreContacto ?>">
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="numeroContacto" style="border:0px;">Número :</label>
						<input class="form-control col-md-8" id="numeroContacto" name="numeroContacto" patron="requerido,numeros" value="<?= $numeroContacto ?>">
					</div>
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="correoContacto" style="border:0px;">Correo :</label>
						<input class="form-control col-md-8" id="correoContacto" name="correoContacto" patron="requerido,email" value="<?= $correoContacto ?>">
					</div>
					<div id="extraCorreo">
						<?php foreach ($correosAdicionales as $key => $value) : ?>
							<div class="input-group control-group child-divcenter row correoAdd" style="width:85%">
								<label class="form-control col-md-4" style="border:0px;">Correo Adicional:</label>
								<input class="form-control col-md-8" value="<?= $value['correo'] ?>">
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Costo</legend>
				<div class="<?= ($disabled) ? "disabled" : "" ?>">
					<div class="control-group child-divcenter row" style="width:85%">
						<label class="form-control col-md-4" for="costo" style="border:0px;">Costo (S/) :</label>
						<input class="form-control col-md-8" id="costo" name="costo" patron="numeros" value="<?= verificarEmpty($costo, 2) ?>">
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Banco</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Banco</label>
					<select class="form-control col-md-8 simpleDropdown" name="banco">
						<?= htmlSelectOptionArray2(['title' => 'Banco', 'id' => 'idBanco', 'value' => 'nombre', 'query' => $bancos, 'class' => 'text-titlecase', 'selected' => $idBanco]); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row" style="width:85%">
					<label class="form-control border-0 col-md-4">Tipo Cuenta</label>
					<select class="form-control col-md-8 simpleDropdown" name="tipoCuenta">
						<?= htmlSelectOptionArray2(['title' => 'Tipo Cuenta', 'id' => 'idTipoCuentaBanco', 'value' => 'nombre', 'query' => $tiposCuentaBanco, 'class' => 'text-titlecase', 'selected' => $idTipoCuentaBanco]); ?>
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
				<?php $chk = $chkDetraccion ? 'checked' : ''; ?>
				<?php $hdn = $chkDetraccion ? '' : 'd-none'; ?>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">Incluir Detracción</label>
					<div class="ui test toggle checkbox">
						<input class="chkDetraccion" name="chkDetraccion" type="checkbox" <?= $chk ?>>
					</div>
				</div>
				<div class="control-group child-divcenter row pt-2 detraccion <?= $hdn ?>" style="width:85%">
					<label class="form-control border-0 col-md-4">Cuenta detracción</label>
					<input class="form-control col-md-8 cuentaDetraccion" name="cuentaDetraccion" value="<?= $cuenta ?>">
				</div>
				<div class="control-group child-divcenter row pt-2 detraccion <?= $hdn ?>" style="width:85%">
					<label class="form-control border-0 col-md-4">Cuenta captura</label>
					<div class="divParaCarga col-md-8 pl-0" style="width:85%">
						<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf']) ?>
						<?php if (!empty($adjunto)) : ?>
							<div class="ui fluid image content-lsck-capturas" data-id="<?= $adjunto['idProveedorArchivo'] ?>">
								<div class="ui dimmer dimmer-file-detalle">
									<div class="content">
										<p class="ui tiny inverted header">.</p>
									</div>
								</div>
								<input class="file-considerarAdjunto" type="hidden">
								<a target="_blank" href="<?= RUTA_WASABI . 'proveedorAdjuntos/' . $adjunto['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
								<a class="ui red right floating label option-semantic-delete"><i class="trash icon m-0"></i></a>
								<img height="50" src="<?= RUTA_WASABI . 'proveedorAdjuntos/' . $adjunto['nombre_archivo'] ?>" class="img-lsck-capturas img-responsive img-thumbnail">
							</div>
						<?php endif; ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Información adicional</legend>
				<div class="<?= ($disabled) ? "disabled" : "" ?>">
					<div class="control-group child-divcenter row" style="width:85%">
						<textarea class="form-control col-md-12" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..." value="<?= $informacionAdicional ?>"><?= $informacionAdicional ?></textarea>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Información respuesta</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<textarea class="form-control col-md-12" id="informacionEstado" name="informacionEstado" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..." value=""></textarea>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter" style="text-align:center;">
			<div class="ui checkbox">
				<input type="checkbox" onchange="($(this).is(':checked')) ? $('#datosValidos, #contribuyenteValido').prop( 'checked', true) : $('#datosValidos, #contribuyenteValido').prop( 'checked', false);">
				<label>Datos Validos</label>
			</div>
			<div class="ui checkbox d-none">
				<input type="checkbox" name="datosValidos" id="datosValidos" patron="requerido">
				<label>Datos Validos</label>
			</div>
			<div class="ui checkbox d-none">
				<input type="checkbox" name="contribuyenteValido" id="contribuyenteValido" patron="requerido">
				<label>Contribuyente Valido</label>
			</div>
		</div>
	</div>
</form>
<script>
	var provincia = <?= json_encode($listadoProvincias); ?>;
	var distrito = <?= json_encode($listadoDistritos); ?>;
	var distrito_ubigeo = <?= json_encode($listadoDistritosUbigeo); ?>;

	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>