<form class="form" role="form" id="formvisualizacionCotizacion" method="post">
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['cotizacion'], 3) ?></label>
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
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Cod. Cotizacion :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['codCotizacion'], 3) ?></label>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Progreso de la Cotizacion :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['cotizacionEstado'], 3) ?></label>
            </div>
        </div>
        <div class="col-md-5 child-divcenter">
        <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Fecha de Emision :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['fechaEmision'], 3) ?></label>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter">
            <!-- <button type="button" class="btn btn-outline-secondary btn-generarCotizacion" style="margin-bottom: 15px;">Generar Cotizacion</button> -->
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
                <table class="mb-0 table table-bordered text-nowrap" id="listaItemsCotizacion">
                    <thead class="thead-default">
                        <tr>
                            <th style="width: 5%;" class="text-center">#</th>
                            <th style="width: 15%;">Tipo de Item</th>
                            <th style="width: 50%;">Item</th>
                            <th style="width: 15%;" class="text-center">Cantidad</th>
                            <th style="width: 7%;">Costo Actual</th>
                            <th style="width: 7%;">Proveedor</th>
                            <th style="width: 7%;">Fecha de Proceso</th>
                            <th style="width: 8%;">Estado</th>
                            <!-- <th style="width: 8%;">Opciones</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        foreach ($detalle as $key => $row) {
                        ?>
                            <tr class="default">
                                <td><?= $key + 1 ?></td>
                                <td><?= verificarEmpty($row['itemTipo'], 3) ?></td>
                                <td><?= verificarEmpty($row['item'], 3) ?></td>
                                <td><?= verificarEmpty($row['cantidad'], 3) ?></td>
                                <td><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                                <td><?= verificarEmpty($row['proveedor'], 3) ?></td>
                                <td><?= verificarEmpty($row['fecha'], 3) ?></td>
                                <td><?= verificarEmpty($row['cotizacionDetalleEstado'], 3) ?></td>
                                <!-- <td><?= ($row['idItemEstado'] == 2) ? '<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="' . verificarEmpty($cabecera['idCotizacion'], 3) . '" data-nombrearticulo="' . verificarEmpty($row['item'], 3) . '"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a>' : '' ?></td> -->
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