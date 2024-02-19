<form class="ui  form" role="form" id="formRegistrarNuevoPago" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				
				<legend class="scheduler-border">Datos Generales</legend>
				<div >
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:60%">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:39%">
                            <label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
                            <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido" value="">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:45%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="descripcionServicio" style="border:0px;">Descripcion Servicio :</label>
                            <input class="form-control col-md-8" id="descripcionServicio" name="descripcionServicio"  value="">
                        </div>
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre Contacto :</label>
                            <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto"  value="">
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
            <legend class="scheduler-border">Datos Comprobante</legend>
                <div id="cargar-factura">
                    
                        
                        <div class="fields">
                            
                            
                          
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Metodo de  Pago:</label>
                                    <select class="form-control  semantic-dropdown" id="tipoComprobante" name="tipoComprobante"   patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $motodoPago, 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Codigo Pago:</label>
                                <input class="form-control " id="numeroComprobante" name="numeroComprobante" patron="requerido" value="">
                                </div>
                            </div>  
                            <div class="field" style="width:30%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Cuenta:</label>
                                        <select class="form-control   semantic-dropdown parentDependienteSemantic " data-cuentap="cuenta" id="cboCuenta" name="cuentaForm" patron="requerido" data-childDependiente=".cboCentroCosto" data-closest=".fields">
                                        <?php $selected = isset($ordenServicio['idCuenta']) ? verificarEmpty($ordenServicio['idCuenta']) : NULL; ?>
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $v['idCuenta'], 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
                                        </select>
                                </div>
                            </div>  

                            <div class="field" style="width:30%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Centro Costo:</label>
                                        <select class="form-control  semantic-dropdown childdependienteSemantic cboCentroCosto" data-centrop="centro" name="centroCostoForm" patron="requerido">
                                        <?php $selected = isset($ordenServicio['idCentroCosto']) ? verificarEmpty($ordenServicio['idCentroCosto']) : NULL; ?>
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $v['idCentroCosto'] , 'query' => $centroCosto, 'simple' => true, 'class' => 'text-titlecase']); ?>
                                        </select>
                                </div>
                            </div> 
                        </div>
                        <div class="fields">
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Fecha Comprobante Pago:</label>
                                    <div class="ui calendar date-semantic ">
                                        <div class="ui input left icon fluid">
                                            <i class="calendar icon"></i>
                                            <input type="text" value="" patron="requerido">
                                        </div>
                                    </div>
                                    <input type="hidden" class="date-semantic-value" name="fechaPagoComprobante" value=""> 
                                </div>
                            </div>
                       
                            <div class="field" style="width:15%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto:</label>
                                <input class="form-control  onlyNumbers monto" id="monto_M" name="monto_M" patron="requerido" value="">
                                </div>
                            </div>
                            <div class="field" style="width:15%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Porcentaje Detracción:</label>
                                <input class="form-control onlyNumbers porcentaje"   id="porcentajeDetraccion_M" name="porcentajeDetraccion_M" patron="requerido" value="">
                                </div>
                            </div>
                            <div class="field" style="width:15%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto Detracción:</label>
                                <input class="form-control  " readonly id="montoDetraccion_M" name="montoDetraccion_M" patron="requerido" value="">
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Archivo:</label>  
                                <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaPrincipalPago', 'visible' => false , 'tipo' => 2]) ?>
 
                               </div>
                            </div>
                       
                        </div>
                        
                    
                </div>
           
                 
			</fieldset>
		</div>
	</div>
</form>