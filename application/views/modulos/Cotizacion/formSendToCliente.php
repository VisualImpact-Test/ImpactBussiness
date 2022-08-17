<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formSendToCliente" method="post">
        <h4 class="ui dividing header">Actualizar envío</h4>
        <div class="fields ">
            <div class="sixteen wide field">
                <div class="ui sub header">Enviar Correo:</div>
                <select name="flagEnviarCorreo" id="flagEnviarCorreo" class="ui fluid search clearable dropdown simpleDropdown" patron="requerido">
                    <option value="1">Sí (Debe ingresar los correos de contacto)</option>
                    <option value="2">No (Solo actualizar la cotización)</option>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui sub header">Correos de contacto</div>
                <select name="correos" id="correos" name="receptor" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="email" multiple>

                </select>
            </div>
        </div>
    </form>
</div>