<style>
    .detail {
        background: none !important;
    }
</style>
<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="child-divcenter" style="width:90%">
        <input type="hidden" name="idCotizacion" id="" value="<?=$cotizacion['idCotizacion']?>">
        <div class="ui form">
            <div class="fields">
                <div class="sixteen wide field">
                    <label>Motivo:</label>
                    <input id="motivo" name="motivo" patron="requerido" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
                </div>
            </div>
        </div>
    </div>
</form>