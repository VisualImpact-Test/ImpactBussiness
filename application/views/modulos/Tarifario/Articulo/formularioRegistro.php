<form class="form" role="form" id="formRegistroTarifarioArticulos" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Articulo :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre">
                <input class="d-none" id="idArticulo" name="idArticulo" patron="requerido">
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
                    <input type="text" lang="en" name="costo" class="form-control input-sm soloNumeros" id="costo" value="" patron="requerido, numeros">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;"></label>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="actual" id="actual">
                    <label class="form-check-label" for="actual">Este articulo es el actual</label>
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