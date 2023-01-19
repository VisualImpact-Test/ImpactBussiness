<style>
    .detail {
        background: none !important;
    }
</style>
<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="child-divcenter" style="width:90%">
        <input type="hidden" name="idCotizacion" id="" value="<?= $cotizacion['idCotizacion'] ?>">
        <div class="ui form">
            <div class="fields">
                <div class="sixteen wide field">
                    <label>Código OC:</label>
                    <input id="motivo" name="codigo_oc" patron="" placeholder="Código Orden de compra" value="<?= !empty($cotizacion['codOrdenCompra']) ? $cotizacion['codOrdenCompra'] : '' ?>">
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field">
                    <label>Monto de OC:</label>
                    <input class="soloNumeros" id="motivo" name="monto_oc" patron="" placeholder="Monto de compra" value="<?= !empty($cotizacion['montoOrdenCompra']) ? $cotizacion['montoOrdenCompra'] : '' ?>">
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field">
                    <div class="ui labeled button" tabindex="0">
                        <div class="ui blue button" onclick="$('.file-lsck-capturas').click();">
                            <i class="paperclip icon"></i> Adjuntar
                        </div>
                        <a class="ui basic blue left pointing label">
                            Orden Compra
                        </a>
                    </div>
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field">
                    <div class="content-lsck-capturas">
                        <input data-file-max="1" data-show-name="true" type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept=".pdf" multiple="">
                        <div class="fields ">
                            <div class="sixteen wide field">
                                <div class="ui small images content-lsck-galeria">

                                </div>
                            </div>
                        </div>
                        <div class="fields ">
                            <div class="sixteen wide field">
                                <div class="ui small images content-lsck-files">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fields">
                <div class="sixteen wide field">
                    <label>Motivo:</label>
                    <input id="motivo" name="motivo" patron="" placeholder="Motivo" value="<?= !empty($cotizacion['motivoAprobacion']) ? $cotizacion['motivoAprobacion'] : '' ?>">
                </div>
            </div>
        </div>
    </div>
</form>