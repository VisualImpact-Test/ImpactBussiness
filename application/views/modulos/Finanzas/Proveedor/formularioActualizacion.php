<form class="form" role="form" id="formActualizacionProveedores" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <fieldset class="scheduler-border">
                <input class="d-none" id="idProveedor" name="idProveedor" value="<?= $idProveedor ?>">
                <legend class="scheduler-border">Datos Generales</legend>
                <div class="<?= ($disabled) ? "disabled" : "" ?>">
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="razonSocial" style="border:0px;">Razón Social :</label>
                        <input class="form-control col-md-8" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $razonSocial ?>">
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="ruc" style="border:0px;">RUC :</label>
                        <input class="form-control col-md-8" id="ruc" name="ruc" patron="requerido,ruc" value="<?= $nroDocumento ?>">
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="rubro" style="border:0px;">Rubro :</label>
                        <select class="form-control col-md-8" id="rubro" name="rubro" patron="requerido">
                            <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $listadoRubros, 'class' => 'text-titlecase', 'selected' => $idRubro]); ?>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="metodoPago" style="border:0px;">Método de pago :</label>
                        <select class="form-control col-md-8 my_select2" id="metodoPago" name="metodoPago" patron="requerido" multiple data-live-search="true">
                        <?
                            foreach ($listadoMetodosPago as $pagos) {
                            ?>
                                <option value="<?= $pagos['id']  ?>"  <?= !empty($metodoPago[$pagos['id']]) ?"selected":""  ?>> <?= $pagos['value'] ?></option>
                            <?
                            }

                        ?>

                            
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Direccion</legend>
                <div class="<?= ($disabled) ? "disabled" : "" ?>">
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="region" style="border:0px;">Region :</label>
                        <select class="form-control col-md-8" id="region" name="region" patron="requerido">
                            <?
                            foreach ($listadoDepartamentos as $k_dp => $v_dp) {
                            ?>
                                <option value="<?= $k_dp ?>" <?= ($k_dp == $cod_departamento) ? "selected" : "" ?>><?= $v_dp['nombre'] ?></option>;
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="provincia" style="border:0px;">Provincia :</label>
                        <select class="form-control col-md-8" id="provincia" name="provincia" patron="requerido">
                            <option value="<?= $cod_provincia ?>"><?= textopropio($provincia) ?></option>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="distrito" style="border:0px;">Distrito :</label>
                        <select class="form-control col-md-8" id="distrito" name="distrito" patron="requerido">
                            <option value="<?= $cod_ubigeo ?>"><?= textopropio($distrito) ?></option>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="direccion" style="border:0px;">Direccion :</label>
                        <input class="form-control col-md-8" id="direccion" name="direccion" patron="requerido" value="<?= $direccion ?>">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Zonas de Cobertura</legend>
                <div class="<?= ($disabled) ? "disabled" : "" ?>">
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="regionCobertura" style="border:0px;">Region :</label>
                        <select class="form-control col-md-8 my_select2" id="regionCobertura" name="regionCobertura" multiple data-live-search="true" patron="requerido">
                            <?
                            foreach ($listadoDepartamentos as $k_dp => $v_dp) {
                            ?>
                                <option value="<?= $k_dp ?>" <?= (isset($departamentosCobertura[strtoupper($v_dp['nombre'])])) ? "selected" : "" ?>><?= $v_dp['nombre'] ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="provinciaCobertura" style="border:0px;">Provincia :</label>
                        <select class="form-control col-md-8 my_select2" id="provinciaCobertura" name="provinciaCobertura" multiple data-live-search="true">
                            <option value="">Seleccione</option>
                            <?
                            foreach ($provinciasCobertura as $k_p => $v_p) {
                            ?>
                                <option value="<?= $k_p ?>" selected><?= textopropio($v_p) ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="distritoCobertura" style="border:0px;">Distrito :</label>
                        <select class="form-control col-md-8 my_select2" id="distritoCobertura" name="distritoCobertura" multiple data-live-search="true">
                            <option value="">Seleccione</option>
                            <?
                            foreach ($distritosCobertura as $k_d => $v_d) {
                            ?>
                                <option value="<?= $k_d ?>" selected><?= textopropio($v_d) ?></option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Contacto</legend>
                <div class="<?= ($disabled) ? "disabled" : "" ?>">
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="nombreContacto" style="border:0px;">Nombre :</label>
                        <input class="form-control col-md-8" id="nombreContacto" name="nombreContacto" patron="requerido" value="<?= $nombreContacto ?>">
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="correoContacto" style="border:0px;">Correo :</label>
                        <input class="form-control col-md-8" id="correoContacto" name="correoContacto" patron="requerido,email" value="<?= $correoContacto ?>">
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="numeroContacto" style="border:0px;">Número :</label>
                        <input class="form-control col-md-8" id="numeroContacto" name="numeroContacto" patron="requerido,numeros" value="<?= $numeroContacto ?>">
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Información adicional</legend>
                <div class="<?= ($disabled) ? "disabled" : "" ?>">
                    <div class="control-group child-divcenter row" style="width:85%">
                        <textarea class="form-control col-md-12" id="informacionAdicional" name="informacionAdicional" style="resize: none; height:100px;" placeholder="Máximo 500 caracteres..." value="<?= $informacionAdicional ?>"><?= $informacionAdicional ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 child-divcenter" style="text-align:center;">
            <div class="ui checkbox">
                <input type="checkbox" name="datosValidos" id="datosValidos" patron="requerido">
                <label>Datos Validos</label>
            </div>
            <div class="ui checkbox">
                <input type="checkbox" name="contribuyenteValido" id="contribuyenteValido" patron="requerido">
                <label>Contribuyente Valido</label>
            </div>
        </div>
    </div>
</form>
<script>
    var provincia = <?= json_encode($listadoProvincias); ?>;
    var distrito = <?= json_encode($listadoDistritos); ?>;
    var distrito_ubigeo = <?= json_encode($listadoDistritosUbigeo); ?>;

    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>