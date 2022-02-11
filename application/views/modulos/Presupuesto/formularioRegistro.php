<form class="form" role="form" id="formRegistroPresupuesto" method="post">
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control form-control-sm col-md-7" id="nombre" name="nombre" patron="requerido">
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
                <select class="form-control form-control-sm col-md-7 ui my_select2 parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
            </div>
        </div>
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <select class="form-control form-control-sm col-md-7 ui my_select2" id="tipo" name="tipo" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoPresupuesto, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                <select class="form-control form-control-sm col-md-7 ui childDependiente" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter">
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
                <table class="mb-0 table table-bordered text-nowrap" id="listaItemsPresupuesto">
                    <thead class="thead-default">
                        <tr>
                            <th style="width: 5%;" class="text-center">#</th>
                            <th style="width: 50%;">Item</th>
                            <th style="width: 15%;" class="text-center">Cantidad</th>
                            <th style="width: 7%;">Costo Actual</th>
                            <th style="width: 8%;">Estado</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr hidden class="default">
                            <td>
                                <div class="ui-widget">
                                    <input class="form-control items" type='text' name='nameItem' patron="requerido">
                                    <input class="codArticulos" type='hidden' name='idItemForm'>
                                </div>
                            </td>
                            <td>
                                <input class="form-control cantidadForm" type="number" name="cantidadForm" patron="requerido,numerico" min="1" max="10000">
                            </td>
                            <td>
                                <input class="form-control costoForm" type="number" name="costoForm" patron="requerido,numerico" min="1" max="10000">
                            </td>
                            <td>
                                <label for="" class="estadoItemForm">NUEVO</label>
                                <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                <input class="idTipoArticulo" type='hidden' name='idTipoArticulo' value="">
                            </td>
                            <td class="text-center">
                                <button class="btn btneliminarfila"><i class="fa fa-minus-circle"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <i style="font-size:25px" class="btn  fa fa-plus-circle btn-add-row"></i>
            </div>
        </div>
    </div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>