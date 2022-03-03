<form class="form" role="form" id="formActualizacionTipos" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $informacionTipo['tipo'] ?>">
                <input class="d-none" id="idTipo" name="idTipo" patron="requerido" value="<?= $informacionTipo['idTipo'] ?>">
            </div>
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>