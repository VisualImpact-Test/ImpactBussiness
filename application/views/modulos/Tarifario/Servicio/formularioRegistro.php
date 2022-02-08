<form class="form" role="form" id="formRegistroServicios" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicio, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>