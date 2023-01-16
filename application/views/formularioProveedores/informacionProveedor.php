<h4 style="margin:0px"><strong>Se ha registrado un nuevo proveedor desde el formulario web con la siguiente informacion:</strong></h4>

<div class="card-body" style="width: 100%; display: table; border-collapse:separate;border-spacing:15px;">
    <div style="display: table-row;">
        <div class="row" style="width: 50%; display: table-cell; margin:10px;">
            <div class="col-md-8 child-divcenter">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Datos Generales</legend>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" style="border:0px;"><strong>Razón Social :</strong></strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $razonSocial ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="ruc" style="border:0px;"><strong>RUC :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $nroDocumento ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="rubro" style="border:0px;"><strong>Actividad :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $rubro ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="metodoPago" style="border:0px;"><strong>Forma de pago :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $metodoPago ?></label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row" style="width: 50%; display: table-cell; margin:10px;">
            <div class="col-md-8 child-divcenter">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Direccion</legend>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="region" style="border:0px;"><strong>Region :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $departamento ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="provincia" style="border:0px;"><strong>Provincia :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $provincia ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="distrito" style="border:0px;"><strong>Distrito :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $distrito ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="direccion" style="border:0px;"><strong>Direccion :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $direccion ?></label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div style="display: table-row">
        <div class="row" style="width: 50%; display: table-cell; margin:10px;">
            <div class="col-md-8 child-divcenter">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Zonas de Cobertura</legend>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="regionCobertura" style="border:0px;"><strong>Region :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $departamentosCobertura ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="provinciaCobertura" style="border:0px;"><strong>Provincia :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $provinciasCobertura ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="distritoCobertura" style="border:0px;"><strong>Distrito :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $distritosCobertura ?></label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row" style="width: 50%; display: table-cell; margin:10px;">
            <div class="col-md-8 child-divcenter">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Contacto</legend>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="nombreContacto" style="border:0px;"><strong>Nombre :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $nombreContacto ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="correoContacto" style="border:0px;"><strong>Correo :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $correoContacto ?></label>
                    </div>
                    <div class="control-group child-divcenter row" style="width:85%">
                        <label class="form-control col-md-4" for="numeroContacto" style="border:0px;"><strong>Número :</strong></label>
                        <label class="form-control col-md-8" style="border:0px;"><?= $numeroContacto ?></label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<div class="col-md-8 child-divcenter" style="width: 80%;margin: auto;margin-bottom: 15px;">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Información adicional</legend>
        <div class="control-group child-divcenter row" style="width:85%">
            <p><?= empty($informacionAdicional) ? "Sin información adicional" : $informacionAdicional ?></p>
        </div>
    </fieldset>
</div>
<div class="text-box" style="text-align:center">
    <a href="<?= $link ?>" class="btn btn-white btn-animate" style="background-color: #d85151;
    color: #fff;
    text-transform: uppercase;
    text-decoration: none;
    padding: 15px 40px;
    display: inline-block;
    border-radius: 100px;
    transition: all .2s;
    position: relative;">Validar Proveedor</a>
</div>