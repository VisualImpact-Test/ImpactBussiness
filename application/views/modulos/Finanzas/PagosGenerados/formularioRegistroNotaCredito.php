<form class="ui form" role="form" id="formRegistrarNotaCredito" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos </legend>
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
                        
                        <!-- <div class="control-group child-divcenter row" style="width:10%;">
                            <button class="ui button" type="button" name="new-notaCredito" id="new-notaCredito" style="padding-left: 15px;padding-right: 15px;"><i class="fa fa-plus"></i> Nota</button>
                        </div> -->
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
                            <select class="form-control col-md-12 semantic-dropdown" id="tipoComprobante_<?= $id ?>" name="tipoComprobante_<?= $id ?>"   patron="requerido" disabled>
								<?= htmlSelectOptionArray2(['query' => $tipoComprobante,'selected' => $v['idTipoNota'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							</select>
                            </div>
                        </div>
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Numero Comprobante:</label>
                            <input id="numComprobante_<?= $id ?>" name="numComprobante_<?= $id ?>" patron="requerido" value="<?= $v['numNota']; ?>" disabled>
                            </div>
                        </div>
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Monto Comprobante:</label>
                            <input id="montoComprobante_<?= $id ?>" name="montoComprobante_<?= $id ?>" patron="requerido" value="<?= $v['montoNota']; ?>" disabled>
                            </div>
                        </div>
                    
                    
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Emision:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="<?= $v['fechaEmision']; ?>" >
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaEmision_<?= $id ?>" value="<?= $v['fechaEmisionNota']; ?>">
                            </div>
                        </div>
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Recepción:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="<?= $v['fechaRecepcion']; ?>" >
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaRecepcion_<?= $id ?>" value="<?= $v['fechaRecepcionNota']; ?>">
                            </div>
                        </div>
                     
                    </div>
                    <div class="fields">
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Tipo Comprobante:</label>
                                <select class="form-control col-md-12 semantic-dropdown" id="tipoNota_<?= $id ?>" name="tipoNota_<?= $id ?>"  >
                                    <?= htmlSelectOptionArray2(['query' => $tipoNota, 'class' => 'text-titlecase', 'title' => 'Seleccione' , 'selected' => $v['idTipoNota'] ]); ?>
                                </select>
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Numero Nota:</label>
                            <input id="numNota_<?= $id ?>" name="numNota_<?= $id ?>"  value="<?= $v['numNota']; ?>">
                            </div>
                        </div>
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Monto:</label>
                            <input id="monto_<?= $id ?>" name="monto_<?= $id ?>"  value="<?= $v['montoNota']; ?>">

                            </div>
                        </div>
                        <div class="field" style="width:20%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen" >
                                <?php if ($v['nombre_archivo_nota']) { ?>
                                    <label>Archivo</label>
                                    <?php $direccion = RUTA_WASABI . 'FinanzasComprobantes/' . verificarEmpty($v['nombre_archivo_nota'], 3); ?>
                                    <a class="ui button" href="<?= $direccion ?>" target="_blank"><i class="icon eye"></i></a>
                                <?php } else { ?>
                                    <label>Subir Archivo</label>
                                    <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => $v['idServicioPagoComprobante'].'_cuentaPrincipal', 'visible' => false , 'tipo' => 2]) ?>
                                <?php }?>
                            </div>
                        </div>
                        <?php if (empty($v['numNota'])) { ?>
                        <div class="field" style="width:10%;margin-left: 0px;margin-right: 0px;">
                            <button class="ui button" type="button" name="new-notaCredito" id="new-notaCredito" data-id="<?= $id ?>" style="padding-left: 15px;padding-right: 15px;"><i class="fa fa-save"></i> Nota</button>
                        </div>
                        <?php } ?>
                    </div>
                   
                    <div class="ui divider"></div>
                  </br>

                 <?php } ?>
                </div>
				<div id="agregar-factura">

                </div>
			</fieldset>
		</div>
	</div>
</form>




