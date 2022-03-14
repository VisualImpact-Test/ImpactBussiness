<form class="form" role="form" id="formActualizacionItems" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $informacionItem['item'] ?>">
                <input class="d-none" id="idItem" name="idItem" patron="requerido" value="<?= $informacionItem['idItem'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Caracteristicas :</label>
                <input class="form-control col-md-7" id="caracteristicas" name="caracteristicas" patron="requerido" value="<?= $informacionItem['caracteristicas'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente" value="<?= $informacionItem['equivalenteLogistica'] ?>">
                <input class="d-none" id="idItemLogistica" name="idItemLogistica" value="<?= $informacionItem['idItemLogistica'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idTipoItem']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
                <select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idMarcaItem']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
                <select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idCategoriaItem']]); ?>
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