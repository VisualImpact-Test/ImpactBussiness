<div>
    <div class="row child-divcenter">
        <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
    </div>
    <div class="mb-3 card child-divcenter" style="width:75%">
        <div class="card-head text-center" style="margin-top: 10px;">
            <h2>Registro de Proveedores</h2>
            <hr>
        </div>
        <div class="card-body">
            <form class="form" role="form" id="formRegistroProveedores" method="post">
                <div class="row">
                    <div class="col-md-8 child-divcenter">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Datos Generales</legend>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="razonSocial" style="border:0px;">Razón Social :</label>
                                <input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido">
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
                                <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido,ruc">
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="rubro" style="border:0px;">Rubro :</label>
                                <select class="form-control col-md-8" id="rubro" name="rubro" patron="requerido">
                                    <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $rubro, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="metodoPago" style="border:0px;">Método de pago :</label>
                                <select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" multiple data-live-search="true" patron="requerido">
                                    <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $metodoPago, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 child-divcenter">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Direccion</legend>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="region" style="border:0px;">Region :</label>
                                <select class="form-control col-md-8" id="region" name="region" patron="requerido">
                                    <?
                                    foreach ($departamento as $k_dp => $v_dp) {
                                    ?>
                                        <option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>;
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="provincia" style="border:0px;">Provincia :</label>
                                <select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="distrito" style="border:0px;">Distrito :</label>
                                <select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="direccion" style="border:0px;">Direccion :</label>
                                <input class="form-control col-md-8" id="direccion" name="direccion" patron="requerido">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 child-divcenter">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Zonas de Cobertura</legend>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="regionCobertura" style="border:0px;">Region :</label>
                                <select class="form-control col-md-8 my_select2" id="regionCobertura" name="regionCobertura" multiple data-live-search="true" patron="requerido">
                                    <?
                                    foreach ($departamento as $k_dp => $v_dp) {
                                    ?>
                                        <option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="provinciaCobertura" style="border:0px;">Provincia :</label>
                                <select class="form-control col-md-8 my_select2" id="provinciaCobertura" name="provinciaCobertura" multiple data-live-search="true">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="distritoCobertura" style="border:0px;">Distrito :</label>
                                <select class="form-control col-md-8 my_select2" id="distritoCobertura" name="distritoCobertura" multiple data-live-search="true">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 child-divcenter">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Contacto</legend>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre :</label>
                                <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido">
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="correoContacto" style="border:0px;">Correo :</label>
                                <input class="form-control col-md-8" id="correoContacto" name="correoContacto" patron="requerido,email">
                            </div>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <label class="form-control col-md-4" for="numeroContacto" style="border:0px;">Número :</label>
                                <input class="form-control col-md-8" id="numeroContacto" name="numeroContacto" patron="requerido,numeros">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 child-divcenter">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Información adicional</legend>
                            <div class="control-group child-divcenter row" style="width:85%">
                                <textarea class="form-control col-md-12" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..."></textarea>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 child-divcenter" style="text-align: right;">
                        <button class="btn btn-outline-primary" id="btnEnviar" style="width: 25%;" value="Enviar">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var provincia = <?= json_encode($provincia); ?>;
        var distrito = <?= json_encode($distrito); ?>;
        var distrito_ubigeo = <?= json_encode($distrito_ubigeo); ?>;
    </script>