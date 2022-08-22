<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formSendToCliente" method="post">

        <input type="hidden" name="idCotizacion" value="<?=$data['idCotizacion']?>">

        <h4 class="ui dividing header">Actualizar envío</h4>
        <div class="fields ">
            <div class="sixteen wide field">
                <div class="ui sub header">Enviar Correo:</div>
                <select name="flagEnviarCorreo" id="flagEnviarCorreo" class="ui fluid search clearable dropdown simpleDropdown" patron="requerido" onchange="$(this).val() == 1 ?  $(this).closest('form').find('#correos').attr('patron','requerido') : $(this).closest('form').find('#correos').removeAttr('patron') ">
                    <option value="2" selected>No (Solo actualizar la cotización)</option>
                    <option value="1">Sí (Debe ingresar los correos de contacto)</option>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui sub header">Correos de contacto</div>
                <select name="correos" id="correos" name="receptor" class="ui fluid search clearable dropdown dropdownSingleAditions"  multiple>

                </select>
            </div>
        </div>
    </form>
</div>