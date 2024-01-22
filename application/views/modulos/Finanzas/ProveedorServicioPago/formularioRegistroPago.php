<form class="form" role="form" id="formRegistroProveedorServicioPago" method="post" autocomplete="off">
	
<div class="row">
    <div class="col-md-12 child-divcenter">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Datos Generales</legend>
			<div class="">
				<div class="row">
					<div class="control-group child-divcenter row" style="width:99%;margin-left: 0px;margin-right: 0px;">
						<label class="form-control col-md-2" for="proveedorServicio" style="border:0px;">Proveedor</label>
						<select class="form-control proveedorServicio col-md-10 simpleDropdown" name="proveedorServicio" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Proveedores Servicio', 'id' => 'idProveedorServicio', 'value' => 'datosProveedor', 'query' => $proveedorServicio, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					
				</div>
				<br>
				<div class="row">
					<div class="control-group child-divcenter row" style="width:52%;margin-left: 0px;margin-right: 0px;">
						<label class="form-control border-0 col-md-4">Moneda:</label>
						<select class="form-control moneda col-md-8 simpleDropdown" name="moneda" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Moneda', 'id' => 'idMoneda', 'value' => 'nombre', 'query' => $moneda, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="control-group child-divcenter row " style="width:48%">
						<label class="form-control border-0 col-md-4">Frecuencia:</label>
						<select class="form-control frecuenciaPago col-md-8 simpleDropdown" name="frecuenciaPago" patron="requerido">
							<?= htmlSelectOptionArray2([
								'title' => 'Frecuencia Pago', 'id' => 'idFrecuenciaPago', 'value' => 'nombre',
								'query' => $frecuenciaPago, 'class' => 'text-titlecase'
							]); ?>
						</select>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="control-group child-divcenter row pt-2" style="width:33%">
						<label class="form-control border-0 col-md-6">Dia Pago:</label>
						<input class="form-control col-md-6" patron="requerido,numeros" type="text" id="diaPago" name="diaPago" maxlength="2">
					</div>
					<div class="control-group child-divcenter row pt-2" style="width: 33%">
						<label class="form-control border-0 col-md-4">F Inicio:</label>
						<input class="form-control col-md-8" patron="requerido" name="fechaInicio" type="date">
					</div>
					<div class="control-group child-divcenter row pt-2" style="width: 33%">
						<label class="form-control border-0 col-md-4">F Fin:</label>
						<input class="form-control col-md-8" name="fechaTermino" type="date">
					</div>

				</div>
				<br>
				<div class="row">
					<div class="control-group child-divcenter row pt-2" style="width:50%">
						<label class="form-control border-0 col-md-6">Incluir Monto Fijo</label>
						<div class="ui test toggle checkbox">
							<input class="chkMontoFijo" name="chkMontoFijo" type="checkbox">
						</div>
					</div>
					<div class="control-group child-divcenter row pt-2 fijo d-none" style="width:50%">
						<label class="form-control border-0 col-md-4">Monto:</label>
						<input class="form-control col-md-8 onlyNumbers" type="number" id="monto" name="monto">
					</div>
				</div>
				<div class="row">
					<div class="control-group child-divcenter row pt-2" style="width:80%">
						<legend class="scheduler-border col-md-6">Descripción Servicio</legend>
						<div class="control-group child-divcenter col-md-6" style="width:85%">
							<textarea class="form-control col-md-12" patron="requerido" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..."></textarea>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

</form>