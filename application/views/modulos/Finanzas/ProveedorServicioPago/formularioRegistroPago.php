<form class="form" role="form" id="formRegistroProveedorServicioPago" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="mb-2 input-group control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">Proveedor:</label>
					<select class="form-control proveedorServicio col-md-8 simpleDropdown" name="proveedorServicio" patron="requerido">
						<?= htmlSelectOptionArray2(['title' => 'Proveedores Servicio', 'id' => 'idProveedorServicio', 'value' => 'razonSocial', 'query' => $proveedorServicio, 'class' => 'text-titlecase']); ?>
					</select>
					<!--<div class="input-group-append align-items-center" id="button-addon4">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregar-proveedor-servicio" title="Agregar Proveedor Servicio"><i class="fa fa-lg fa-plus"></i></a>
					</div>-->
				</div>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">Moneda:</label>
					<select class="form-control moneda col-md-8 simpleDropdown" name="moneda" patron="requerido">
						<?= htmlSelectOptionArray2(['title' => 'Moneda', 'id' => 'idMoneda', 'value' => 'nombre', 'query' => $moneda, 'class' => 'text-titlecase']); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">DIA PAGO:</label>
					<input class="form-control col-md-8" patron="requerido,numeros" type="text" id="diaPago" name="diaPago" maxlength="2">
				</div>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">FRECUENCIA:</label>
					<select class="form-control frecuenciaPago col-md-8 simpleDropdown" name="frecuenciaPago" patron="requerido">
						<?= htmlSelectOptionArray2([
							'title' => 'Frecuencia Pago', 'id' => 'idFrecuenciaPago', 'value' => 'nombre',
							'query' => $frecuenciaPago, 'class' => 'text-titlecase'
						]); ?>
					</select>
				</div>
				<div class="control-group child-divcenter row pt-2" style="width: 85%">
					<label class="form-control border-0 col-md-4">FECHA INICIO:</label>
					<input class="form-control col-md-8" patron="requerido" name="fechaInicio" type="date">
				</div>
				<div class="control-group child-divcenter row pt-2" style="width: 85%">
					<label class="form-control border-0 col-md-4">FECHA TERMINO:</label>
					<input class="form-control col-md-8" name="fechaTermino" type="date">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Monto</legend>
				<div class="control-group child-divcenter row pt-2" style="width:85%">
					<label class="form-control border-0 col-md-4">Incluir Monto Fijo</label>
					<div class="ui test toggle checkbox">
						<input class="chkMontoFijo" name="chkMontoFijo" type="checkbox">
					</div>
				</div>
				<div class="control-group child-divcenter row pt-2 fijo d-none" style="width:85%">
					<label class="form-control border-0 col-md-4">Monto:</label>
					<input class="form-control col-md-8 onlyNumbers" type="number" id="monto" name="monto">
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Descripción Servicio</legend>
				<div class="control-group child-divcenter row" style="width:85%">
					<textarea class="form-control col-md-12" patron="requerido" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..."></textarea>
				</div>
			</fieldset>
		</div>
	</div>
</form>