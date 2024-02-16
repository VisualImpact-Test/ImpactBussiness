<form class="ui  form" role="form" id="formRegistrarPagoGenerado" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="disabled">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:60%">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $pagosGenerados[0]['datosProveedor'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:39%">
                            <label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
                            <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido" value="<?= $pagosGenerados[0]['numDocumento'] ?>">
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
                            <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto"  value="">
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
            <legend class="scheduler-border">Datos Comprobante</legend>
                <div id="cargar-factura">
                    <?php foreach ($facturas as $k => $v) { ?>
                        
                        <div class="fields">
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <?php $id = $v['idServicioPagoComprobante']; ?>
                                <input  class="d-none"  type="text"  id="idServicioPagoComprobante" name="idServicioPagoComprobante" patron="requerido" value="<?= $id ?>" >

                                <div class="field sixteen">
                                <label>Tipo Comprobante:</label>
                                    <select class="form-control  semantic-dropdown" id="tipoComprobante<?= $id ?>" name="tipoComprobante_<?= $id ?>"   patron="requerido" disabled>
                                        <?= htmlSelectOptionArray2(['query' => $tipoComprobante,'selected' => $v['tipoComprobanteFactura'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Numero Comprobante:</label>
                                <input id="numComprobante_<?= $id ?>" name="numComprobante_<?= $id ?>" patron="requerido" value="<?= $v['numComprobanteFactura']; ?>" disabled>
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto Comprobante:</label>
                                <input id="montoComprobante_<?= $id ?>" name="montoComprobante_<?= $id ?>" patron="requerido" value="<?= $v['montoFactura']; ?>" disabled>
                                </div>
                            </div>  
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Metodo de Pago:</label>
                                    <select class="form-control  semantic-dropdown" id="tipoComprobante_P<?= $id ?>" name="tipoComprobante_P<?= $id ?>"   patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $motodoPago,'selected' => $v['idTipoPago'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Codigo Pago:</label>
                                <input class="form-control " id="numeroComprobante_P<?= $id ?>" name="numeroComprobante_P<?= $id ?>" patron="requerido" value="<?= $v['numComprobantePago']; ?>">
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
                                            <input type="text" value="<?= $v['fechaPagoComprobantePago']; ?>" patron="requerido">
                                        </div>
                                    </div>
                                    <input type="hidden" class="date-semantic-value" name="fechaPagoComprobante_P<?= $id ?>" value="<?= $v['fechaPagoComprobantePago']; ?>"> 
                                </div>
                            </div>

                            

                            <div class="field" style="width:30%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Cuenta:</label>
                                        <select class="form-control   semantic-dropdown parentDependienteSemantic " data-cuentap="cuenta_p<?= $id ?>" id="cboCuenta" name="cuentaForm" patron="requerido" data-childDependiente=".cboCentroCosto" data-closest=".fields">
                                        <?php $selected = isset($ordenServicio['idCuenta']) ? verificarEmpty($ordenServicio['idCuenta']) : NULL; ?>
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $v['idCuenta'], 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
                                        </select>
                                </div>
                            </div>  

                            <div class="field" style="width:30%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                    <label>Centro Costo:</label>
                                        <select class="form-control  semantic-dropdown childdependienteSemantic cboCentroCosto" data-centrop="centro_p<?= $id ?>" name="centroCostoForm" patron="requerido">
                                        <?php $selected = isset($ordenServicio['idCentroCosto']) ? verificarEmpty($ordenServicio['idCentroCosto']) : NULL; ?>
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $v['idCentroCosto'] , 'query' => $centroCosto, 'simple' => true, 'class' => 'text-titlecase']); ?>
                                        </select>
                                </div>
                            </div> 

                        </div>

                        <div class="fields">
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto:</label>
                                <input class="form-control  onlyNumbers monto" data-id="<?= $id ?>" id="monto_P<?= $id ?>" name="monto_P<?= $id ?>" patron="requerido" value="<?= $v['montoPagado']; ?>">
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Porcentaje Detracción:</label>
                                <input class="form-control onlyNumbers porcentaje" data-id="<?= $id ?>"  id="porcentajeDetraccion_P<?= $id ?>" name="porcentajeDetraccion_P<?= $id ?>" patron="requerido" value="<?= $v['porcentajeDetraccion']; ?>">
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <label>Monto Detracción:</label>
                                <input class="form-control  " readonly id="montoDetraccion_P<?= $id ?>" name="montoDetraccion_P<?= $id ?>" patron="requerido" value="<?= $v['montoDetraccion']; ?>">
                                </div>
                            </div>
                            <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                                <div class="field sixteen">
                                <?php if ($v['nombre_archivo_pago']) { ?>
                                    <label>Archivo</label>
                                    <?php $direccion = RUTA_WASABI . 'FinanzasComprobantes/' . verificarEmpty($v['nombre_archivo_pago'], 3); ?>
                                    <a class="ui button" href="<?= $direccion ?>" target="_blank"><i class="icon eye"></i></a>
                                <?php } else { ?>
                                <label>Archivo:</label>  
                                <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => $id.'_cuentaPrincipalPago', 'visible' => false , 'tipo' => 2]) ?>
                               <?php }?>
                               </div>
                            </div>
                            <?php if (empty($v['montoPagado'])) { ?>
                            <div class="field" style="width:10%;margin-left: 0px;margin-right: 0px;">
                                <button class="ui button" type="button" name="new-RegistrarPago" id="new-RegistrarPago" data-id="<?= $id ?>" style="padding-left: 15px;padding-right: 15px;"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="ui divider"></div>
                    <?php } ?>
                </div>
                <?php if (empty($facturas)) { ?>
                  <div style="font-size: 18px;color: red;text-align: center;">  Se requiere la presentación del comprobante de pago  </div>
                <?php } ?>
                 
			</fieldset>
		</div>
	</div>
</form>