<style>
    .detail {
        background: none !important;
    }
</style>
<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="child-divcenter" style="width:90%">
        <h4 class="ui dividing header">Información de la Cotización</h4>
        <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $cotizacion['idCotizacion'] ?>">
        <div class="ui form">
            <div class="fields">
                <div class="ten wide field">
                    <label>Titulo de la Cotización:</label>
                    <input id="nombre" name="nombre" patron="requerido_reemplazar" placeholder="Nombre" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
                </div>
                <div class="five wide field">
                    <label>Cuenta:</label>
                    <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido_reemplazar" data-childDependiente="cuentaCentroCostoForm">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
                    </select>
                </div>
                <div class="five wide field">
                    <label>Centro de Costo:</label>
                    <select class="ui search dropdown childDependiente" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido_reemplazar">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuentaCentroCosto']) ? $cotizacion['idCuentaCentroCosto'] : '']); ?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Fecha de Requerimiento:</label>
                <div class="fields">
                    <div class="five wide field">
                        <div class="ui calendar date-semantic">
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" placeholder="Fecha de Requerimiento" value="<?= !empty($cotizacion['fechaRequerimiento']) ? $cotizacion['fechaRequerimiento'] : '' ?> ">
                            </div>
                        </div>
                        <input type="hidden" class="date-semantic-value" name="fechaRequerimiento" placeholder="Fecha de Requerimiento" value="<?= !empty($cotizacion['fechaRequerimiento']) ? $cotizacion['fechaRequerimiento'] : '' ?>">
                    </div>
                    <div class="five wide field">
                        <div class="inline field">
                            <div class="ui toggle checkbox">
                                <input type="checkbox" tabindex="0" class="hidden" name="igvForm" <?= !empty($cotizacion['igv']) ? "checked" : '' ?>>
                                <label>Incluye IGV</label>
                            </div>
                        </div>
                    </div>
                    <div class="inline fields">
                        <div class="twelve wide field">
                            <a class="ui teal image label">
                                <i class="fa fa-flag-alt"></i>
                                Vigencia
                                <div class="detail">- 7 dias</div>
                            </a>
                            <a class="ui yellow image label">
                                <i class="fa fa-flag-alt"></i>
                                Vigencia
                                <div class="detail">8 a 15 dias</div>
                            </a>
                            <a class="ui red image label">
                                <i class="fa fa-flag-alt"></i>
                                Vigencia
                                <div class="detail">+ 15 dias</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter">
            <h4 class="ui dividing header">Detalle de la Cotización</h4>
            <div class="sixteen wide field">
                <div class="ui sub header">Proveedor</div>
                <div class="ui action input w-100">
                    <select class="ui search dropdown w-100 parentDependiente" id="proveedorForm" name="proveedorForm" patron="requerido_reemplazar">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedores, 'class' => 'text-titlecase', 'id' => 'idProveedor', 'value' => 'razonSocial']); ?>
                    </select>
                    <div class="ui button btnSolicitarCotizacion"><i class="send icon"></i> Solicitar Cotizacion</div>
                </div>
            </div>
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center;max-height:400px;overflow:auto;">
                <table class="ui celled padded table" id="listaItemsCotizacion">
                    <thead class="thead-default ui">
                        <tr>
                            <th></th>
                            <th style="width: 3%;" class="text-center">#</th>
                            <th style="width: 12%;">Tipo Item</th>
                            <th style="width: 31%;">Item</th>
                            <th style="width: 19%;">Características</th>
                            <th style="width: 10%;" class="text-center">Cantidad</th>
                            <th style="width: 11%;" class="text-center">Costo</th>
                            <th style="width: 3%;" class="text-center"></th>
                            <th style="width: 14%;" class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($cotizacionDetalle as $k => $row) { ?>
                            <input class="idCotizacionDetalleForm" type='hidden' name='idCotizacionDetalle' value="<?= !empty($row['idCotizacionDetalle']) ? $row['idCotizacionDetalle'] : '' ?>">
                            <tr>
                                <td class="collapsing">
                                    <div class="ui fitted slider checkbox">
                                        <input type="checkbox" name="checkItem"> <label></label>
                                    </div>
                                </td>
                                <td>
                                    <?= ($k + 1) ?>
                                </td>
                                <td>
                                    <select class="form-control" id="tipoItemForm" name="tipoItemForm" patron="requerido_reemplazar">
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $itemTipo, 'class' => 'text-titlecase', 'selected' => $row['idItemTipo']]); ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="ui-widget">
                                        <input class="form-control items" type='text' name='nameItem' patron="requerido_reemplazar" placeholder="Buscar item" value="<?= $row['item'] ?>">
                                        <input class="codItems" type='hidden' name='idItemForm' value="<?= !empty($row['idItem']) ? $row['idItem'] : '' ?>">
                                        <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="<?= !empty($row['idItem']) ? 1 : 2 ?>">
                                        <input class="idProveedor" type='hidden' name='idProveedorForm' value="<?= !empty($row['idProveedor']) ? $row['idProveedor'] : '' ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="ui-widget">
                                        <input class="form-control" type='text' name='caracteristicasItem' patron="requerido_reemplazar" placeholder="Caracteristicas del item" value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>">
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control cantidadForm" type="number" name="cantidadForm" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                </td>
                                <td class="text-center">
                                    <div class="ui image large label">
                                        <img src="assets/images/iconos/sol_peruano_bn.png">
                                        <label class="costoFormLabel" style="margin:0px;"><?= !empty($row['costo']) ? $row['costo'] : 0 ?></label>
                                    </div>
                                    <input class="form-control costoForm" type="hidden" name="costoForm" value="<?= !empty($row['costo']) ? $row['costo'] : 0 ?>" placeholder="0" readonly>
                                </td>
                                <td>
                                    <i class="semaforoForm fad fa-lg fa-flag-alt"></i>
                                </td>
                                <td class="text-center">
                                    <div class="ui image large label">
                                        <img src="assets/images/iconos/sol_peruano_bn.png">
                                        <label class="subtotalFormLabel" style="margin:0px;"><?= !empty($row['subtotal']) ? $row['subtotal'] : 0 ?></label>
                                    </div>
                                    <input class="form-control subtotalForm" type="hidden" name="subtotalForm" value="<?= !empty($row['subtotal']) ? $row['subtotal'] : 0 ?>" placeholder="0" readonly>
                                </td>
                              
                            </tr>
                        <? } ?>
                    </tbody>
                    <tfoot class="full-width">
                        <tr>
                            <th></th>
                            <th colspan="6">
                                <div class="ui small button btnVerCotizaciones">
                                    Ver cotizaciones
                                </div>
                                <div class="ui small  disabled button">
                                    Eliminar Solicitudes
                                </div>
                            </th>
                            <th class="text-right"><a class="ui tag large label">Total</a></th>
                            <th class="text-center">
                                <div class="ui right floated">
                                    <div class="ui image large label">
                                        <img src="assets/images/iconos/sol_peruano.png">
                                        <label class="totalFormLabel" style="margin:0px;"><?= !empty($row['total']) ? $row['total'] : 0 ?></label>
                                    </div>
                                    <input class="form-control totalForm" type="hidden" name="totalForm" value="<?= !empty($row['total']) ? $row['total'] : 0 ?>" placeholder="0" readonly="">
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
      
            <div class="ui form disabled" style="margin-top:10px;">
                <div class="fields">
                    <div class="five wide field">
                        <label>Prioridad:</label>
                        <select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido_reemplazar">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => $cotizacion['idPrioridad']]); ?>
                        </select>
                    </div>
                    <div class="six wide field">
                        <label>Motivo:</label>
                        <input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?=!empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
                    </div>
                    <div class="five wide field">
                        <label>GAP%:</label>
                        <input id="gapForm" name="gapForm" placeholder="Gap" value="<?=!empty($cotizacion['gap']) ? $cotizacion['gap'] : '' ?>">
                    </div>
                </div>
                <div class="fields">
                    <div class="eleven wide field">
                        <label>Comentario:</label>
                        <input id="comentarioForm" name="comentarioForm" patron="requerido_reemplazar" placeholder="Comentario" value="<?=!empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?>">
                    </div>
                    <div class="five wide field">
                        <label>FEE%:</label>
                        <input id="feeForm" name="feeForm" placeholder="Fee" value="<?=!empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>