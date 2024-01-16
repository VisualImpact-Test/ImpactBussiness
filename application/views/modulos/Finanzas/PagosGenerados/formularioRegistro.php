<form class="form" role="form" id="formRegistrarPagoGenerado" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="disabled">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:60%">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $pagosGenerados[0]['razonSocial'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:39%">
                            <label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
                            <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido" value="<?= $pagosGenerados[0]['ruc'] ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:45%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="descripcionServicio" style="border:0px;">Descripcion Servicio :</label>
                            <input class="form-control col-md-8" id="descripcionServicio" name="descripcionServicio"  value="<?= $pagosGenerados[0]['descripcionServicio'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre Contacto :</label>
                            <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto"  value="<?= $pagosGenerados[0]['nombreContacto'] ?>">
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
				
				<legend class="scheduler-border">Datos de Pago</legend>
				<div class="">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:33%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="fechaPagoComprobante" style="border:0px;">Fecha Comprobante:</label>
                            <div class="ui calendar date-semantic col-md-8">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaPagoComprobante" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:30%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="tipoComprobante" style="border:0px;">Tipo Comprobante :</label>
                            <select class="form-control col-md-8 semantic-dropdown" id="tipoComprobante" name="tipoComprobante"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $tipoComprobante, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
                        </div>
                        <div class="control-group child-divcenter row" style="width:35%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="numeroComprobante" style="border:0px;">Comprobante:</label>
                            <input class="form-control col-md-7" id="numeroComprobante" name="numeroComprobante" patron="requerido" value="">
                        </div>
                        
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:45%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-3" for="cuentaForm" style="border:0px;">Cuenta :</label>
                            <select class="form-control col-md-9  semantic-dropdown parentDependienteSemantic" id="cboCuenta" name="cuentaForm" patron="requerido" data-childDependiente="#cboCentroCosto">
                                <?php $selected = isset($ordenServicio['idCuenta']) ? verificarEmpty($ordenServicio['idCuenta']) : NULL; ?>
                                <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
                            </select>
                        </div>
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-3" for="centroCostoForm" style="border:0px;">Centro Costo :</label>
                            <select class="form-control col-md-9 semantic-dropdown childdependienteSemantic read-only" id="cboCentroCosto" name="centroCostoForm" patron="requerido">
                                <?php $selected = isset($ordenServicio['idCentroCosto']) ? verificarEmpty($ordenServicio['idCentroCosto']) : NULL; ?>
                                <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'query' => $centroCosto, 'simple' => true, 'class' => 'text-titlecase']); ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="monto" style="border:0px;">Monto :</label>
                            <input class="form-control col-md-7 onlyNumbers" id="monto" name="monto" patron="requerido" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:35%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="porcentajeDetraccion" style="border:0px;">% Detraccion :</label>
                            <input class="form-control col-md-7 onlyNumbers" id="porcentajeDetraccion" name="porcentajeDetraccion" patron="requerido" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:40%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="montoDetraccion" style="border:0px;">Monto Detraccion :</label>
                            <input class="form-control col-md-6 " readonly id="montoDetraccion" name="montoDetraccion" patron="requerido" value="">
                        </div>
                    </div>
                 
			</fieldset>
		</div>
	</div>
</form>