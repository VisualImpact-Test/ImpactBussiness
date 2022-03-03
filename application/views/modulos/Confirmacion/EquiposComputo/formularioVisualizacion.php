<? if ($idEquiposComputo == 25) { ?>
    <form class="form" role="form" id="formvisualizacionCotizacion" method="post">
        <div class="row">
            <div class="col-md-5 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                    <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;">COTIZACION EQUIPOS DICIEMBRE 2021</label>
                </div>
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
                    <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;">VISUAL IMPACT</label>
                </div>
                <div class="control-group child-divcenter row w-100">
                </div>
            </div>
            <div class="col-md-5 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Cod. Cotizacion :</label>
                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;">COTI-0000025</label>
                </div>
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                    <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;">TECNOLOGÍA DE LA INFORMACIÓN</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Progreso de la Cotizacion :</label>
                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;">Confirmado</label>
                </div>
            </div>
            <div class="col-md-5 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Fecha de Emision :</label>
                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;">03/03/2022</label>
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
                                <th style="width: 8%;">Estado</th>
                                <!-- <th style="width: 8%;">Opciones</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="default">
                                <td>1</td>
                                <td>Equipos de Computo</td>
                                <td>LAPTOP DELL 128GB 4GB RAM</td>
                                <td>5</td>
                                <td><input class="form-control" type="number" readonly="" value="2580"></td>
                                <td>Confirmado</td>
                                <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="25" data-nombrearticulo="LAPTOP DELL 128GB 4GB RAM"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                            </tr>
                            <tr class="default">
                                <td>2</td>
                                <td>Equipos de Computo</td>
                                <td>MODEM TP-LINK G534 100 Mbps</td>
                                <td>5</td>
                                <td><input class="form-control" type="number" readonly="" value="215"></td>
                                <td>Confirmado</td>
                                <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="25" data-nombrearticulo="MODEM TP-LINK G534 100 Mbps"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
<? } ?>