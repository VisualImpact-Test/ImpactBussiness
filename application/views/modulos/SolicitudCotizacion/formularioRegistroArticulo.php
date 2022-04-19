<form class="form" role="form" id="formRegistroArticulos" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7 <?= (!empty($nombreArticulo)) ? "disabled" : "" ?>" id="nombre" name="nombre" patron="requerido" value="<?= (!empty($nombreArticulo)) ? $nombreArticulo : "" ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente">
                <input class="d-none" id="idArticuloLogistica" name="idArticuloLogistica">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoArticulo, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
                <select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaArticulo, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
                <select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaArticulo, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="proveedor" style="border:0px;">Proveedor :</label>
                <select class="form-control col-md-7" id="proveedor" name="proveedor" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Costo :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">S/</span>
                    </div>
                    <input type="text" lang="en" name="costo" class="form-control input-sm soloNumeros" id="costo" patron="requerido, numeros" value="">
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>