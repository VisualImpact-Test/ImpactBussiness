<form class="form" role="form" id="formUsuarioFirmaRegistro" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-4" for="nombre" style="border:0px;">Usuario :</label>
                <input class="form-control col-md-8" id="nombre" patron="requerido" value="<?= $informacionUsuario['usuario'] ?>" readonly>
                <input class="d-none" name="idUsuario" patron="requerido" value="<?= $informacionUsuario['idUsuario'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-4" for="nombre" style="border:0px;">Archivo :</label>
                <input class="form-control col-md-8 file-upload" type="file" accept="image/png, image/jpeg">
                <input type="hidden" id="f_item" name="file-item" patron="requerido">
                <input type="hidden" id="f_name" name="file-name" patron="requerido">
                <input type="hidden" id="f_type" name="file-type" patron="requerido">
            </div>
            <?php if (!empty($informacionUsuario['nombre_archivo'])): ?>
              <div class="alert alert-danger" role="alert">
                Ya existe una firma registrada para este usuario
              </div>
            <?php endif; ?>
            <img id="imagenFirma" src="<?= empty($informacionUsuario['nombre_archivo'])?'':(RUTA_WASABI.'usuarioFirma/'.$informacionUsuario['nombre_archivo']) ?>" class="d-none col-md-5 rounded mx-auto d-block pt-5">
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>
