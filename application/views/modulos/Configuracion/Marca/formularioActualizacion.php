<form class="form" role="form" id="formActualizacionMarcas" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $informacionMarca['marca'] ?>">
                <input class="d-none" id="idMarcaArticulo" name="idItemMarca" patron="requerido" value="<?= $informacionMarca['idItemMarca'] ?>">
            </div>
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>