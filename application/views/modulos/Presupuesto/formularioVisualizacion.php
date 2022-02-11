<form class="form" role="form" id="formvisualizacionPresupuesto" method="post">
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['presupuesto'], 3) ?></label>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
                <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
            </div>
            <div class="control-group child-divcenter row w-100">
            </div>
        </div>
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Tipo :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['tipoPresupuesto'], 3) ?></label>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        foreach ($detalle as $key => $row) {
                        ?>
                            <tr class="default">
                                <td><?= $key + 1 ?></td>
                                <td><?= verificarEmpty($row['item'], 3) ?></td>
                                <td><?= verificarEmpty($row['cantidad'], 3) ?></td>
                                <td><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                                <td><?= verificarEmpty($row['estadoItem'], 3) ?></td>
                            </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>