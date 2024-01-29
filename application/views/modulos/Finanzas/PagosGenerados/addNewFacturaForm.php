<div class="">
                    <div class="fields">
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Monto</label>
                                <div class="ui calendar date-semantic col-md-12">
                                    <div class="ui input left icon fluid">
                                        <i class="calendar icon"></i>
                                        <input type="text" value="" patron="requerido">
                                    </div>
                                </div>
                                <input type="hidden" class="date-semantic-value" name="fechaEmision" value="">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Recepción:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaRecepcion" value="">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Fecha de Vencimiento:</label>
                            <div class="ui calendar date-semantic col-md-12">
                                <div class="ui input left icon fluid">
                                    <i class="calendar icon"></i>
                                    <input type="text" value="" patron="requerido">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaVencimiento" value="">
                            </div>    
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                            <label>Tipo Comprobante:</label>
                            <select class="form-control col-md-12 semantic-dropdown" id="tipoComprobante" name="tipoComprobante"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $tipoComprobante,'selected' => $pagosGenerados[0]['idComprobante'] , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							</select>
                            </div>    
                        </div>  
                    </div>
               
                    <div class="fields">
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Numero Comprobante</label>
                                <input  id="numeroComprobante" name="numeroComprobante" patron="requerido" value="">
                            </div>
                        </div> 
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen">
                                <label>Moneda</label>
                                <select class="form-control  semantic-dropdown" id="moneda" name="moneda"   patron="requerido">
								<?= htmlSelectOptionArray2(['query' => $moneda, 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
							    </select>

                            </div>
                        </div> 
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen" >
                                <label>Monto</label>
                                <input class="onlyNumbers" id="monto" name="monto" patron="requerido" value="">
                            </div>
                        </div>
                        <div class="field" style="width:25%;margin-left: 0px;margin-right: 0px;">
                            <div class="field sixteen" >
                                <label>Subir Archivo</label>
                                <?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cuentaPrincipal', 'visible' => false , 'tipo' => 2]) ?>
                            </div>
                        </div>

                    </div>
                    <div class="ui divider"></div>
                </div>