<div>
    <div class="row child-divcenter">
        <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
    </div>
    <div class="mb-3 card child-divcenter" style="width: 90% !important;">
        <div class="col-md-12 ">
            <div id="accordion">
                <div class="">
                    <div class="card-header" id="headingOne">
                        <form id="frmCotizacionProveedorCabecera">
                            <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $idCotizacion ?>">
                        </form>
                        <h5 class="mb-0">
                            <button type="button" class="btn " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-solid fa-caret-right"></i> <?= !empty($cabecera['codCotizacion']) ? $cabecera['codCotizacion'] : $cabecera['cotizacion'] ?>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="row">
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                                    <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['cotizacion'], 3) ?></label>

                                </div>
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
                                    <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
                                </div>
                                <div class="control-group child-divcenter row w-100">
                                </div>
                            </div>
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Cod. Cotizacion :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['codCotizacion'], 3) ?></label>
                                </div>
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                                    <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Progreso de la Cotizacion :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['cotizacionEstado'], 3) ?></label>
                                </div>
                            </div>
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Fecha de Emision :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['fechaEmision'], 3) ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 w-100" id="content-tb-cotizaciones-proveedor" style="width:90%">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>