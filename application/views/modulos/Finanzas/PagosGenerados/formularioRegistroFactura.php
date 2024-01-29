<form class="ui form" role="form" id="formRegistrarFactura" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos Facturas</legend>
				<div class="">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:53%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $pagosGenerados[0]['datosProveedor'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:47%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="descripcionServicio" style="border:0px;">Descripcion Servicio :</label>
                            <input class="form-control col-md-8" id="descripcionServicio" name="descripcionServicio"  value="<?= $pagosGenerados[0]['descripcionServicio'] ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:30%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-7" for="ruc" style="border:0px;">Tipo de Documento :</label>
                            <input class="form-control col-md-5" id="ruc" name="ruc" patron="requerido" value="<?= $pagosGenerados[0]['breve'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:35%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="nombreContacto" style="border:0px;">Numero de Documento :</label>
                            <input class="form-control col-md-6" id="nombreContacto" name="nombreContacto"  value="<?= $pagosGenerados[0]['numDocumento'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:25%;">
                            <label class="form-control col-md-5" for="montoTotal" style="border:0px;">Monto Total :</label>
                            <input class="form-control col-md-6" id="montoTotal" name="montoTotal"  value="<?= $pagosGenerados[0]['monto_total'] ?>">
                        </div>
                        
                        <div class="control-group child-divcenter row" style="width:10%;">
                            <button class="ui button" type="button" name="new-factura" id="new-factura" style="padding-left: 15px;padding-right: 15px;"><i class="fa fa-plus"></i> Factura</button>
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Comprobante</legend>
                <div id="cargar-factura">
                    <?php foreach ($facturas as $k => $v) { ?>
                        <div class="fields">
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Monto</label>
                                <div class="ui calendar date-semantic col-md-12">
                                    <div class="ui input left icon fluid">
                                        <i class="calendar icon"></i>
                                        <input type="text" value="<?= $v['fechaEmision']; ?>" patron="requerido">
                                    </div>
                                </div>
                                <input type="hidden" class="date-semantic-value" name="fechaEmision_reg" value="<?= $v['fechaEmision']; ?>">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Recepción:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="<?= $v['fechaRecepcion']; ?>" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaRecepcion_reg" value="<?= $v['fechaRecepcion']; ?>">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Vencimiento:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="<?= $v['fechaVencimiento']; ?>" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaVencimiento_reg" value="<?= $v['fechaVencimiento']; ?>">
                            </div>    
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Tipo Comprobante:</label>
                            <select class="form-control col-md-12 semantic-dropdown" id="tipoComprobante_reg" name="tipoComprobante_reg"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $tipoComprobante,'selected' => $v['tipoComprobanteFactura'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							</select>
                            </div>    
                        </div>  
                    </div>
               
                    <div class="fields">
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Numero Comprobante</label>
                                <input  id="numeroComprobante_reg" name="numeroComprobante_reg" patron="requerido" value="<?= $v['numComprobanteFactura']; ?>">
                            </div>
                        </div> 
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Moneda</label>
                                <select class="form-control  semantic-dropdown" id="moneda_reg" name="moneda_reg"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $moneda,'selected' => $v['idMoneda'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							    </select>

                            </div>
                        </div> 
                        
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen" >
                                <label>Monto</label>
                                <input class="onlyNumbers" id="monto_reg" name="monto_reg" patron="requerido" value="<?= $v['montoFactura']; ?>">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen" >
                                <label>Archivo</label>
                                <?php $direccion = RUTA_WASABI . 'FinanzasComprobantes/' . verificarEmpty($v['nombre_archivo_factura'], 3); ?>
                                <a class="ui button" href="<?= $direccion ?>" target="_blank"><i class="icon eye"></i></a>
                            </div>
                        </div>

                    </div>
                    <div class="ui divider"></div>
                    <?php } ?>
                </div>
				<div id="agregar-factura">

                </div>
			</fieldset>
		</div>
	</div>
</form>




