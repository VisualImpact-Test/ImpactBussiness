<form class="form" role="form" id="formActualizacionArticulos" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $informacionArticulo['articulo'] ?>">
                <input class="d-none" id="idArticulo" name="idArticulo" patron="requerido" value="<?= $informacionArticulo['idArticulo'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente" value="<?= $informacionArticulo['equivalenteLogistica'] ?>">
                <input class="d-none" id="idArticuloLogistica" name="idArticuloLogistica" patron="requerido" value="<?= $informacionArticulo['idArticuloLogistica'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoArticulo, 'class' => 'text-titlecase', 'selected' => $informacionArticulo['idTipoArticulo']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
                <select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaArticulo, 'class' => 'text-titlecase', 'selected' => $informacionArticulo['idMarcaArticulo']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
                <select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaArticulo, 'class' => 'text-titlecase', 'selected' => $informacionArticulo['idCategoriaArticulo']]); ?>
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