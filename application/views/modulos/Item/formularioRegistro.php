<form class="form" role="form" id="formRegistroItems" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7 <?= (!empty($nombreItem)) ? "disabled" : "" ?>" id="nombre" name="nombre" patron="requerido" value="<?= (!empty($nombreItem)) ? $nombreItem : "" ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Caracteristicas :</label>
                <input class="form-control col-md-7 <?= (!empty($caracteristicasItem)) ? "disabled" : "" ?>" id="caracteristicas" name="caracteristicas" patron="requerido" value="">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
                <input class="form-control col-md-7" id="equivalente" name="equivalente" placeholder="Buscar ">
                <input class="d-none" id="idItemLogistica" name="idItemLogistica">
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

            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="subcategoria" style="border:0px;">SubCategoria :</label>
                <select class="form-control col-md-7" name="subcategoria" id="subcategoria" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $subcategoriaItem, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100 ">
                <label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control col-md-7 tipoArticulo" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="d-none campos_dinamicos div-feature-<?= COD_TEXTILES['id'] ?>">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control col-md-5" for="talla" style="border:0px;">Talla :</label>
                    <input class="form-control col-md-7" id="talla" name="talla">
                </div>
                <div class="control-group child-divcenter  row w-100">
                    <label class="form-control col-md-5" for="tela" style="border:0px;">Tela :</label>
                    <input class="form-control col-md-7" id="tela" name="tela">
                </div>
                <div class="control-group child-divcenter  row w-100 ">
                    <label class="form-control col-md-5" for="color" style="border:0px;">Color :</label>
                    <input class="form-control col-md-7" id="color" name="color">
                </div>

            </div>
            <div class="d-none campos_dinamicos_monto div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control col-md-5" for="monto" style="border:0px;">Monto :</label>
                    <input class="form-control col-md-7" id="monto" name="monto">
                </div>


            </div>

            <!-- vista de las imagenes -->

            <div class="content-lsck-capturas control-group child-divcenter  row w-100 ">
                <!-- vista de las imagenes -->
                <label class="form-control col-md-5" for="imagen" style="border:0px;">Imagen :</label>
                <input type="file" name="capturas" class=" form-control col-md-7 file-lsck-capturas form-control input-sm " placeholder="Cargar Imagen" data-row="0" accept="image/*" multiple="">
                <div class="fields ">
                    <div class="sixteen wide field">
                        <div class="ui small images content-lsck-galeria">

                        </div>
                    </div>
                </div>
                <div class="fields ">
                    <div class="sixteen wide field">
                        <div class="ui small images content-lsck-files">

                        </div>
                    </div>
                </div>
            </div>

            <!-- vista de las imagenes -->

        </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>