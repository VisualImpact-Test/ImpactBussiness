<style>
    .detail {
        background: none !important;
    }

    .ui.action.input input[type="file"] {
        display: none;
    }
</style>
<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="row">
        <div class="child-divcenter col-md-11 col-12">
            <h4 class="ui dividing header">Información de la Cotización</h4>
            <div class="ui form">
                <div class="fields">
                    <div class="five wide field">
                        <div class="ui sub header">Deadline compras</div>
                        <div class="ui calendar date-semantic">
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" placeholder="Deadline compras" value="" patron="requerido">
                            </div>
                        </div>
                        <input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="" patron="requerido">
                    </div>
                    <div class="eleven wide field">
                        <div class="ui sub header">Título de la cotizacion</div>
                        <input id="nombre" name="nombre" patron="requerido" placeholder="Nombre">
                    </div>

                </div>
                <div class="fields">
                    <div class="six wide field">
                        <div class="ui sub header">Solicitante</div>
                        <select name="solicitante" class="ui fluid search dropdown dropdownSingleAditions" patron="requerido">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase']); ?>
                        </select>
                    </div>
                    <div class="five wide field">
                        <div class="ui sub header">Cuenta</div>
                        <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
                        </select>
                    </div>
                    <div class="five wide field">
                        <div class="ui sub header">Centro de costo</div>
                        <select class="ui search dropdown childDependiente" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter col-12">
            <h4 class="ui dividing header">Detalle de la Cotización</h4>
            <div class="ui form">
                <div class="field">
                    <div class="ui sub header">Fecha requerida</div>
                    <div class="fields">
                        <div class="five wide field">
                            <div class="ui calendar date-semantic">
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <input type="text" placeholder="Fecha Requerida" value="">
                                </div>
                            </div>
                            <input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="">
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
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center;max-height:400px;overflow:auto;">
                <table class="ui celled padded table" id="listaItemsCotizacion">
                    <thead class="thead-default ui">
                        <tr>
                            <th style="width: 3%;" class="text-center">#</th>
                            <th style="width: 12%;">Tipo Item</th>
                            <th style="width: 31%;">Item</th>
                            <th style="width: 30%;">Características</th>
                            <th style="width: 5%;" class="text-center">Cantidad</th>
                            <th style="width: 5%;" class="text-center">Costo</th>
                            <th style="width: 6%;" class="text-center">Subtotal</th>
                            <th style="width: 3%;" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr hidden class="default">
                            <td>
                                <select class="ui dropdown" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase', 'simple'=>true]); ?>
                                </select>
                            </td>
                            <td>
                                <div class="ui-widget">
                                    <div class="ui icon input w-100">
                                        <input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item">
                                        <i class="semaforoForm flag link icon"></i>
                                    </div>

                                    <input class="codItems" type='hidden' name='idItemForm'>

                                    <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                    <input class="idProveedor" type='hidden' name='idProveedorForm' value="">
                                </div>
                            </td>
                            <td>
                                <div class="ui right labeled left icon input w-100">
                                    <i class="verCaracteristicaArticulo eye slash link icon"></i>
                                    <input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' patron="requerido" placeholder="Caracteristicas del item">
                                    <a class="ui label editFeatures">
                                        <i class="cog icon m-0"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            </td>
                            <td class="text-center">
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class="costoForm" type="text" name="costoForm" placeholder="0.00" readonly>

                                </div>
                            </td>

                            <td class="text-center">
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class=" subtotalFormLabel" type="text"  placeholder="0.00" readonly>
                                    <input class=" subtotalForm" type="hidden" name="subtotalForm" placeholder="0.00" readonly>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:;" class="btn btn-outline-danger border-0 btneliminarfila" title="Eliminar Fila"><i class="fad fa-lg fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="full-width">
                        <tr>
                            <th></th>
                            <th colspan="2"></th>
                            <th class="text-right">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" tabindex="0" class="hidden" name="igvForm">
                                    <label>Incluye IGV</label>
                                </div>
                            </th>
                            <th class="text-right">
                                <a class="warning ui tag large label">Total</a>
                            </th>
                            <th class="text-center">
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class=" totalFormLabel" type="text" placeholder="0.00" readonly="">
                                    <input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" readonly="">
                                </div>
                            </th>
                            <th colspan="3">
                                <div class="ui right floated small primary labeled icon button btn-add-row" title="Añadir Fila">
                                    <i class="plus icon"></i> Añadir Fila
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="ui form" style="margin-top:10px;">
                <div class="fields">
                    <div class="five wide field">
                        <label>Prioridad:</label>
                        <select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => $cotizacion['idPrioridad']]); ?>
                        </select>
                    </div>
                    <div class="six wide field">
                        <label>Motivo:</label>
                        <input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
                    </div>
                    <div class="five wide field">
                        <label>GAP%:</label>
                        <input id="gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($cotizacion['gap']) ? $cotizacion['gap'] : '' ?>">
                    </div>
                </div>
                <div class="fields">
                    <div class="eleven wide field">
                        <label>Comentario:</label>
                        <input id="comentarioForm" name="comentarioForm" placeholder="Comentario" value="<?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?>">
                    </div>
                    <div class="five wide field">
                        <label>FEE%:</label>
                        <input id="feeForm" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>