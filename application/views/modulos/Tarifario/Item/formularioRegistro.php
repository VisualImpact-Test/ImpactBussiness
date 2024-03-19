<form class="form" role="form" id="formRegistroItemTarifarios" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter loading registroDatos">
            <div class="control-group child-divcenter row w-100 ">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Item :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" placeholder="Buscar ">
                <input class="d-none idItem" id="idItem" name="idItem" patron="requerido">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" style="border:0px;">Proveedor :</label>
                <select class="form-control col-md-7 disabled proveedorTarifario" id="proveedor" name="proveedor" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Costo :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">S/</span>
                    </div>
                    <input type="text" lang="en" name="costo" class="form-control input-sm soloNumeros moneda" id="costo" value="" patron="requerido, numeros" autocomplete="off">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;">Fecha vigencia :</label>
                <div class="input-group mb-3 col-md-7" style="padding:0px;">
                    <input type="date" lang="es" name="fechaVigencia" class="form-control input-sm" id="fechaVigencia" value="" patron="">
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="costo" style="border:0px;"></label>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input checkActual" name="actual" id="actual">
                    <label class="form-check-label">Este item es el actual</label>
                </div>
            </div>
            <div class="control-group child-divcenter row w-100">
                <div class="tipoDiv ui bottom attached success message w-100 d-none">
                    <i class="tipoIcon icon check"></i> Ultima fecha de tarifario del Proveedor Indicado :
                    <label id="label_fecha"> 2022/02/15</label>
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