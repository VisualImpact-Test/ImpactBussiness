<form class="form" role="form" id="formRegistrarPagoGenerado" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="disabled">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $pagosGenerados[0]['datosProveedor'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:45%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="descripcionServicio" style="border:0px;">Descripcion Servicio :</label>
                            <input class="form-control col-md-8" id="descripcionServicio" name="descripcionServicio"  value="<?= $pagosGenerados[0]['descripcionServicio'] ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:39%">
                            <label class="form-control col-md-4" for="ruc" style="border:0px;">Tipo de Documento :</label>
                            <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido" value="<?= $pagosGenerados[0]['breve'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Numero de Documento :</label>
                            <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto"  value="<?= $pagosGenerados[0]['numDocumento'] ?>">
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
                <!-- fecha de vencimiento 
                fecha de emicion
                fecha de recepcion 
				fecha tipo numcomprobate monto archivo -->
				<legend class="scheduler-border">Datos de Pago</legend>
				<div class="">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:33%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="fechaEmision" style="border:0px;">Fecha de Emisión:</label>
                            <div class="ui calendar date-semantic col-md-6">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaEmision" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:33%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="fechaRecepcion" style="border:0px;">Fecha de Recepción:</label>
                            <div class="ui calendar date-semantic col-md-6">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaRecepcion" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:33%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="fechaVencimiento" style="border:0px;">Fecha de Vencimiento:</label>
                            <div class="ui calendar date-semantic col-md-6">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaVencimiento" value="">
                        </div>                        
                    </div>
                    <br>
                    <div  class="row">
                        <div class="control-group child-divcenter row" style="width:50%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="tipoComprobante" style="border:0px;">Tipo Comprobante :</label>
                            <select class="form-control col-md-8 semantic-dropdown" id="tipoComprobante" name="tipoComprobante"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $tipoComprobante,'selected' => $pagosGenerados[0]['idComprobante'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							</select>
                        </div>
                        <div class="control-group child-divcenter row" style="width:50%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="numeroComprobante" style="border:0px;">Comprobante:</label>
                            <input class="form-control col-md-7" id="numeroComprobante" name="numeroComprobante" patron="requerido" value="">
                        </div> 
                    </div>
                 
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="monto" style="border:0px;">Monto :</label>
                            <input class="form-control col-md-7 onlyNumbers" id="monto" name="monto" patron="requerido" value="<?= $pagosGenerados[0]['monto'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-5" for="porcentajeDetraccion" style="border:0px;">Subir Archivo :</label>
                            <div class="divParaCarga col-md-7 pl-0" style="width:85%">
                                <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaPrincipal', 'visible' => false , 'tipo' => 2]) ?>
                            </div>
                        </div>
                        <div class="control-group child-divcenter row" style="width:15%;margin-left: 0px;margin-right: 0px;">
                        <!-- <button type="button"></button>    -->
                        </div>
                    </div>

                 
			</fieldset>
		</div>
	</div>
</form>