<form class="form" role="form" id="formActualizacionItemTarifarios" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter divDatos">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Item :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" value="<?= $informacionItem['item'] ?>" readonly>
                <input class="d-none" id="idItem" name="idItem" patron="requerido" value="<?= $informacionItem['idItem'] ?>">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="proveedor" style="border:0px;">Proveedor :</label>
                <select class="form-control col-md-7 proveedorTarifarioActualizar" id="proveedor" name="proveedor" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'selected' => $informacionItem['idProveedor'], 'id' => 'idProveedor', 'value' => 'razonSocial', 'data-option' => ['idItemTarifario', 'costo', 'flag_actual', 'fechaVigencia']]); ?>
                    <input type="hidden" id="idItemTarifario" name="idItemTarifario" patron="requerido" value="<?= $informacionItem['idItemTarifario'] ?>">
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Costo :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">S/</span>
                    </div>
                    <input type="text" lang="en" name="costo" class="form-control input-sm soloNumeros moneda" id="costo" patron="requerido, numeros" value="<?= $informacionItem['costo']; ?>">
                    <input type="text" lang="en" name="costoAnterior" class="d-none" id="costoAnterior" value="<?= $informacionItem['costo']; ?>">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Fecha vigencia :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <input type="date" lang="es" name="fechaVigencia" class="form-control input-sm" id="fechaVigencia" value="<?= !empty($informacionItem['fechaVigencia']) ? $informacionItem['fechaVigencia'] : '' ?>" patron="">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;"></label>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="actual" id="actual" <?= $informacionItem['flag_actual'] == 1 ? "checked" : "" ?>>
                    <label class="form-check-label" for="actual">Este item es el actual</label>
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