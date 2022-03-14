<div class="tab-content">
    <div id="tab1_b" class="tab-pane fade in active">
        <div style="padding:20px; border: 1px solid #E6E9ED;">
            <form id="formCarteraObjetivo" role="form">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 filtros_asistencia">
                        <div class="col-md-12" id="divTablaCargaMasiva">
                            <div class="tab-content mt-4 text-white">
                                <div class="">
                                    <div class="alert alert-warning" role="alert" style="color:#695560 !important;">
                                        <i class="fa fa-check-circle"></i> <label class="">Se pide que solo una fila (la última) debe quedar en <strong>BLANCO</strong></label>.<br>
                                        <i class="fa fa-check-circle"></i> <label class="">Los campos con una cabecera que contenga (*) son <strong>OBLIGATORIOS</strong></label>.<br>
                                    </div>
                                </div>
                                <? foreach ($hojas as $key => $row) { ?>
                                    <div class="tab-pane <?= ($key == 0) ? 'active' : '' ?>" id="hoja<?= $key ?>" role="tabpanel" aria-labelledby="hoja<?= $key ?>-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="divHT<?= $key ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="resultadoGuardarCargos" style="margin-top:15px;"></div>
        </div>
    </div>
</div>