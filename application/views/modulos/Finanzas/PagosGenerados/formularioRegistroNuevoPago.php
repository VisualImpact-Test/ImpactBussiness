<form class="ui  form" role="form" id="formRegistrarNuevoPago" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				
				<legend class="scheduler-border">Datos Generales</legend>
				    <div >
                        <div class="fields">
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Tipo Documento:</label>
                                    <select class="form-control  semantic-dropdown" id="tipoDocumento" name="tipoDocumento"   patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $tipoDocumento,'selected' =>  3  , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field" style="width:30%">
                                <div class="field sixteen">
                                    <label>Numero Documento:</label>
                                    <input class="form-control " id="numDocumento" name="numDocumento" patron="requerido" value="">
                                </div>
                            </div>
                            <div class="field" style="width:50%">
                                <div class="field sixteen">
                                    <label>Datos Proveedor:</label>
                                    <input class="form-control " id="datosProveedor" name="datosProveedor" patron="requerido" value="">
                                </div>
                            </div>
                            
                        </div>
                   
                    <div class="fields">
                            <div class="field" style="width:20%">
                                <div class="field sixteen">
                                    <label>Descripcion Servicio:</label>
                                    <input class="form-control " id="descripcionServicio" name="descripcionServicio" patron="requerido" value="">
                                </div>
                            </div>

                            <div class="field" style="width:45%">
                                <div class="field sixteen">
                                    <label>Nombre Contacto:</label>
                                    <input class="form-control " id="nomContacto" name="nomContacto" patron="requerido" value="">
                                </div>
                            </div>
                            <div class="field" style="width:35%">
                                <div class="field sixteen">
                                    <label>Telefono Contacto:</label>
                                    <input class="form-control " id="telContacto" name="telContacto" patron="requerido" value="">
                                </div>
                            </div>
                   
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
            <legend class="scheduler-border">Datos Pagos</legend>
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
                                <label>Moneda:</label>
                                    <select class="form-control  simpleDropdown" name="moneda" patron="requerido">
                                        <?= htmlSelectOptionArray2(['title' => 'Moneda',  'query' => $moneda, 'class' => 'text-titlecase']); ?>
                                    </select>
                                </div>
                            </div>
				

                            <div class="field" style="width:15%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto:</label>
                                <input class="form-control  onlyNumbers monto moneda" id="monto_M" name="monto_M" patron="requerido" value="">
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
                                <input class="form-control moneda" readonly id="montoDetraccion_M" name="montoDetraccion_M" patron="requerido" value="">
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