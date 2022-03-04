<form class="form" role="form" id="formvisualizacionCotizacion" method="post">
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;">COTIZACION ENERO 2022</label>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
                <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;">PROCTER &amp; GAMBLE</label>
            </div>
            <div class="control-group child-divcenter row w-100">
            </div>
        </div>
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Cod. Cotizacion :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;">COTI-0000026</label>
            </div>
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;">P&amp;G HSM MODERNO</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Progreso de la Cotizacion :</label>
                <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;">Finalizado</label>
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
                            <th style="width: 7%;">Proveedor</th>
                            <th style="width: 7%;">Fecha de Proceso</th>
                            <th style="width: 8%;">Estado</th>
                            <!-- <th style="width: 8%;">Opciones</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="default">
                            <td>1</td>
                            <td>Articulo</td>
                            <td>CAMISA AZUL MANGA LARGA M</td>
                            <td>20</td>
                            <td>S/ 12.00</td>
                            <td>Nueva Cuenta SAC</td>
                            <td>03/03/2022 17:42:38</td>
                            <td>Pendiente de Ingreso</td>
                            <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="26" data-nombrearticulo="CAMISA AZUL MANGA LARGA M"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                        </tr>
                        <tr class="default">
                            <td>2</td>
                            <td>Personal</td>
                            <td>GESTOR DE TRADE MARKETING</td>
                            <td>20</td>
                            <td>S/ 1,300.00</td>
                            <td>-</td>
                            <td>03/03/2022 17:41:45</td>
                            <td>Pendiente de Ingreso</td>
                            <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="26" data-nombrearticulo="GESTOR DE TRADE MARKETING"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                        </tr>
                        <tr class="default">
                            <td>3</td>
                            <td>Equipos Moviles</td>
                            <td>EQUIPO MOVIL SAMSUNG A10S</td>
                            <td>20</td>
                            <td>S/ 850.00</td>
                            <td>-</td>
                            <td>03/03/2022 17:42:05</td>
                            <td>Confirmado</td>
                            <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="26" data-nombrearticulo="EQUIPO MOVIL SAMSUNG A10S"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                        </tr>
                        <tr class="default">
                            <td>4</td>
                            <td>Servicio</td>
                            <td>TRANSPORTE DE PERSONAL</td>
                            <td>2</td>
                            <td>S/ 120.00</td>
                            <td>Proveedores San Marcos S.A.C</td>
                            <td>03/03/2022 17:42:56</td>
                            <td>Confirmado</td>
                            <!-- <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-agregarArticulo" data-idcotizacion="26" data-nombrearticulo="TRANSPORTE DE PERSONAL"><i class="fa fa-lg fa-plus" title="Agregar articulo al sistema"></i></a></td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>