<style>
    .floating-container {
        height: 150px !important;
    }
</style>
<form id="frmOrdenCompraProveedorCabecera">
    <div>
        <div class="row child-divcenter">
            <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
        </div>
        <div class="mb-3 card child-divcenter w-75">
            <div class="col-md-12 ">
                <div id="accordion">
                    <div class="">
                        <div class="card-header" id="headingOne">
                            <input type="hidden" name="idOrdenCompra" id="idOrdenCompra" value="<?= $idOrdenCompra ?>">
                            <h5 class="mb-0">
                                <button type="button" class="btn " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-solid fa-caret-right"></i> N° DE ORDEN <?= !empty($cabecera['idOrdenCompra']) ? generarCorrelativo($cabecera['idOrdenCompra'], 6) : '-' ?>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="row">
                                <div class="col-md-5 child-divcenter">
                                    <div class="control-group child-divcenter row w-100">
                                        <label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">N° DE RQ :</label>
                                        <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['requerimiento'], 3) ?></label>

                                    </div>
                                    <div class="control-group child-divcenter row w-100">
                                        <label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">CUENTA :</label>
                                        <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
                                    </div>
                                    <div class="control-group child-divcenter row w-100">
                                    </div>
                                </div>
                                <div class="col-md-5 child-divcenter">
                                    <div class="control-group child-divcenter row w-100">
                                        <label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">FECHA :</label>
                                        <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['fechaReg'], 3) ?></label>
                                    </div>
                                    <div class="control-group child-divcenter row w-100">
                                        <label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">CENTRO DE COSTO :</label>
                                        <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 w-100" id="content-tb-ordenCompra-proveedor" style="width:75%">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3 card child-divcenter w-75 p-1">
            <div class="sixteen wide field w-100">
                <div class="ui sub header">Fecha de entrega</div>
                <div class="ui calendar date-semantic ">
                    <div class="ui input left icon w-100">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Fecha de entrega" value="" patron="requerido">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="fechaEntrega" placeholder="Fecha de entrega" value="" patron="requerido">
            </div>
            <table id="tb-cotizaciones" class="ui compact celled definition table">
                <thead class="full-width">
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center" colspan="2">Descripción</th>
                        <th class="text-center">Precio Unit.</th>
                        <th class="text-center">Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($detalle as $k => $row) {
                        $total = $row['subTotalOrdenCompra'];
                        $igv_total = ($row['subTotalOrdenCompra'] * IGV);
                    ?>
                        <tr>
                            <td class="text-center"><?= ($k + 1) ?>

                                <input type="hidden" name="idCotizacion" value="<?=$row['idCotizacion']?>">
                            </td>
                            <td class="text-center"><?= verificarEmpty($row['cantidad'], 2) ?></td>
                            <td class="text-left" colspan="2"><?= verificarEmpty($row['nombre'], 3) ?></td>
                            <td class="text-right">
                                <?= !empty($row['precio']) ? moneda($row['precio']) : 0 ?>
                            </td>
                            <td class="text-right">
                                <?= !empty($row['subtotal']) ? moneda($row['subtotal']) : 0 ?>
                            </td>
                        </tr>
                    <? } ?>
                    <tr style="height: 100px;"></tr>
                </tbody>
                <tfoot class="full-width">
                    <tr>
                        <th colspan="4" class="text-right">
                            <p>Sub Total</p>
                            <p>IGV</p>
                            <p>TOTAL</p>
                        </th>
                        <th class="text-center">
                            <p><?= (IGV * 100) . "%" ?></p>
                        </th>
                        <th class="text-right">
                            <p><?= moneda($total) ?></p>
                            <p><?= moneda($igv_total) ?></p>
                            <p><?= moneda($igv_total + $total)  ?></p>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            Son: <?= moneyToText(['numero' => ($igv_total + $total)]) ?>
                        </th>
                    </tr>
                    <tr style="height: 100px">
                        <th colspan="2">
                            <strong>Forma de Pago</strong>
                        </th>
                        <th>
                            <strong>
                                90 Días
                            </strong>
                        </th>
                        <th>
                            <strong>
                                Observaciones
                            </strong>
                        </th>
                        <th colspan="2">
                            <strong>
                                Visual Impact
                            </strong>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<!-- FAB -->
<div class="floating-container">
    <div class="floating-button ">
        <i class="cog icon"></i>
    </div>
    <div class="element-container">
        <a href="javascript:;">
            <span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "frmOrdenCompraProveedorCabecera", fn: "FormularioProveedoresOC.confirmarOrdenCompra()", content: "¿Está seguro de confirmar la fecha de entrega para la orden de compra?" });'>
                <i class="send icon"></i>
            </span>
        </a>
    </div>
</div>