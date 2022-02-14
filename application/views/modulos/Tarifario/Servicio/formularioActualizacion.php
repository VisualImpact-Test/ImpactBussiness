<form class="form" role="form" id="formActualizacionTarifarioServicios" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Servicio :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" value="<?= $informacionTarifarioServicio['servico_nombre'] ?>">
                <input class="d-none" id="idServicio" name="idServicio" patron="requerido" value="<?= $informacionTarifarioServicio['idServicio'] ?>">
                <input class="d-none" id="idTarifarioServicio" name="idTarifarioServicio" patron="requerido" value="<?= $informacionTarifarioServicio['idTarifarioServicio'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="proveedor" style="border:0px;">Proveedor :</label>
                <select class="form-control col-md-7" id="proveedor" name="proveedor" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'selected' => $informacionTarifarioServicio['idProveedor']]); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Costo :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">S/</span>
                    </div>
                    <input type="text" lang="en" name="costo" class="form-control input-sm soloNumeros" id="costo" value="<?= $informacionTarifarioServicio['tarifa_servicio_costo'] ?>" patron="requerido, numeros">
                    <input type="text" lang="en" name="costoAnterior" class="d-none" id="costoAnterior" value="<?= $informacionTarifarioServicio['tarifa_servicio_costo'] ?>">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;"></label>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="actual" id="actual" <?= $informacionTarifarioServicio['flag_actual'] == 1 ? "checked" : "" ?>>
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