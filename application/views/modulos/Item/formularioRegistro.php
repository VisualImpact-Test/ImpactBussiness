<form class="form" role="form" id="formRegistroItems" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7 <?= (!empty($nombreItem)) ? "disabled" : "" ?>" id="nombre" name="nombre" patron="requerido" value="<?= (!empty($nombreItem)) ? $nombreItem : "" ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente">
                <input class="d-none" id="idItemLogistica" name="idItemLogistica">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
                <select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
                <select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase']); ?>
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