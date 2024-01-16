<style>
    /* Estilos del Contenedor del Formulario */
    .form {
        width: 100%;
        /* Ancho completo */
        max-width: none;
        /* Anula cualquier max-width previo */
        margin: 0;
        /* Elimina márgenes externos */
        padding: 20px;
        /* Espaciado interno */
        background-color: #fff;
        /* Fondo blanco como el modal */
        border: 1px solid #ddd;
        /* Borde sutil */
        border-radius: 4px;
        /* Bordes redondeados */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Sombra suave */
    }

    /* Estilos de los Elementos del Formulario */
    .form-group {
        margin-bottom: 15px;
        /* Espaciado entre campos */
    }

    /* Estilos para las Etiquetas */
    label {
        display: block;
        color: #333;
        /* Color de texto oscuro */
        margin-bottom: 5px;
    }

    /* Estilos para los Campos de Entrada */
    .form-control {
        width: 100%;
        padding: 8px 12px;
        /* Ajusta el padding según sea necesario */
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        /* Asegura que el padding no afecte el ancho total */
    }
</style>
<form class="form" role="form" id="formRegistroProveedorServicio" method="post" autocomplete="off">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="tipoDocumento">Tipo de Documento:</label>
                <select class="form-control" id="tipoDocumento" name="tipoDocumento">
                    <option value="RUC">RUC</option>
                    <option value="DNI">DNI</option>
                    <option value="CE">Carnet de Extranjería</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="numeroDocumento">Número de Documento:</label>
                <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" placeholder="Ingrese el número del documento" pattern="\d{8,11}" maxlength="11" title="El número del documento debe contener entre 8 y 11 dígitos numéricos." patron="requerido">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="razonSocial">Razón Social:</label>
                <input type="text" class="form-control" id="razonSocial" name="razonSocial" placeholder="Ingrese la Razón Social" patron="requerido">
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="region">Región :</label>
                <select class="form-control" id="region" name="region" patron="requerido">
                    <option value="">Seleccione</option>
                    <?php foreach ($departamento as $k_dp => $v_dp) : ?>
                        <option value="<?= $k_dp ?>"><?= $v_dp['nombre'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="provincia">Provincia :</label>
                <select class="form-control" id="provincia" name="provincia" patron="requerido">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="distrito">Distrito :</label>
                <select class="form-control" id="distrito" name="distrito" patron="requerido">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese la Dirección">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="idProveedorEstado">Estado del Proveedor:</label>
                <select class="form-control" id="idProveedorEstado" name="idProveedorEstado" patron="requerido">
                    <option value="">Seleccione: </option>
                    <? foreach ($estado as $key => $value) { ?>
                        <option value="<?= $value['id'] ?>"><?= $value['value']; ?></option>
                    <? } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="nombreContacto">Nombre de Contacto:</label>
                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" placeholder="Ingrese el Nombre de Contacto" patron="requerido">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="numeroContacto">Número de Contacto:</label>
                <!-- Se agrega pattern y maxlength para la validación de 9 números -->
                <input type="tel" class="form-control" id="numeroContacto" name="numeroContacto" placeholder="Ingrese el Número de Contacto" pattern="\d{9}" maxlength="9" title="El número de contacto debe contener 9 dígitos numéricos." patron="requerido">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="correoContacto">Correo de Contacto:</label>
                <!-- El tipo email ya incorpora la validación de formato de correo electrónico -->
                <input type="email" class="form-control" id="correoContacto" name="correoContacto" placeholder="Ingrese el Correo de Contacto" patron="requerido">
            </div>
        </div>

    </div>

</form>

<script>
    var provincia = <?= json_encode($provincia); ?>;
    var distrito = <?= json_encode($distrito); ?>;
    var distrito_ubigeo = <?= json_encode($distrito_ubigeo); ?>;
</script>