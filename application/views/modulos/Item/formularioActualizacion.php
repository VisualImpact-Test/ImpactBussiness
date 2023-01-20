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
            <div class="form-group child-divcenter row w-100 pb-2 divItemLogistica">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="d-none codItemLogistica" id="idItemLogistica" name="idItemLogistica" value="<?= $informacionItem['idItemLogistica'] ?>">
                <div class="input-group col-md-7 px-0">
                    <!-- <input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off" style="height: 40.5px;"> -->
                    <input class="form-control itemLogistica ui-autocomplete-input" id="equivalente" name="equivalente" value="<?= $informacionItem['equivalenteLogistica'] ?>" placeholder="Buscar" style="height: 40.5px;" autocomplete="off" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="Item.editItemLogisticaValue(this);"><i class="fa fa-edit"></i></button>
                    </div>
                </div>
            </div>
            <!--             
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente" value="<?= $informacionItem['equivalenteLogistica'] ?>" placeholder="Buscar">
                <input class="d-none" id="idItemLogistica" name="idItemLogistica" value="<?= $informacionItem['idItemLogistica'] ?>">
            </div> -->
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemTipo']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
                <select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemMarca']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
                <select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemCategoria']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="subcategoria" style="border:0px;">SubCategoria :</label>
                <select class="form-control col-md-7" id="subcategoria" name="subcategoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $subcategoriaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemSubCategoria']]); ?>
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