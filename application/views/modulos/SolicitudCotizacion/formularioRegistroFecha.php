<style>
    .detail {
        background: none !important;
    }
</style>
<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="child-divcenter" style="width:90%">
        <h4 class="ui dividing header">Información de la Cotización</h4>
        <input type="hidden" name="idCotizacion" id="" value="<?=$cotizacion['idCotizacion']?>">
        <div class="ui form">
            <div class="fields">
                <div class="ten wide field">
                    <label>Titulo de la Cotización:</label>
                    <input id="nombre" name="nombre" patron="requerido" placeholder="Nombre" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
                </div>
                <div class="five wide field">
                    <label>Cuenta:</label>
                    <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
                    </select>
                </div>
                <div class="five wide field">
                    <label>Centro de Costo:</label>
                    <select class="ui search dropdown childDependiente" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
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
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter">
            <h4 class="ui dividing header">Detalle de la Cotización</h4>
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center;max-height:400px;overflow:auto;">
                <table class="ui celled padded table" id="listaItemsCotizacion">
                    <thead class="thead-default ui">
                        <tr>
                            <th style="width: 3%;" class="text-center">#</th>
                            <th style="width: 12%;">Tipo Item</th>
                            <th style="width: 31%;">Item</th>
                            <th style="width: 16%;">Características</th>
                            <th style="width: 10%;" class="text-center">Cantidad</th>
                            <th style="width: 3%;" class="text-center">Fecha Entrega</th>
						</tr>
                    </thead>
                    <tbody>
                        <? foreach ($cotizacionDetalle as $k => $row) { ?>
                            <input class="idCotizacionDetalleForm" type='hidden' name='idCotizacionDetalle' value="<?=!empty($row['idCotizacionDetalle']) ? $row['idCotizacionDetalle'] : '' ?>">
                            <tr>
                                <td>
                                    <?=($k+1)?>
                                </td>
                                <td>
                                    <select class="form-control" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $itemTipo, 'class' => 'text-titlecase','selected'=>$row['idItemTipo']]); ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="ui-widget">
                                        <input class="form-control items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?=$row['item']?>">
                                        <input class="codItems" type='hidden' name='idItemForm' value="<?=!empty($row['idItem']) ? $row['idItem'] : '' ?>">
                                        <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="<?=!empty($row['idItem']) ? 1 : 2 ?>">
                                        <input class="idProveedor" type='hidden' name='idProveedorForm' value="<?=!empty($row['idProveedor']) ? $row['idProveedor'] : '' ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="ui-widget">
                                        <input class="form-control" type='text' name='caracteristicasItem' patron="requerido" placeholder="Caracteristicas del item" value="<?=!empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>">
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control cantidadForm" type="number" name="cantidadForm" value="<?=!empty($row['cantidad']) ? $row['cantidad'] : '' ?>" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                </td>
                               
                                <td>
                                     <div class="ui calendar date_h1">
										<div class="ui input left icon">
											<i class="calendar icon"></i>
											<input type="text" placeholder="dd/mm/aaaa" value="">
											<input type="hidden" class="date_h1-value" name="fechaRequerimiento" placeholder="Fecha de Entrega" value="">
										</div>
									</div>
                                </td>
                                
                            </tr>
                        <? } ?>
                    </tbody>
                    <!-- <tfoot class="full-width">
                        <tr>
                            <th></th>
                            <th colspan="3"></th>
                            <th class="text-right"><a class="ui tag large label">Total</a></th>
                            <th class="text-center">
                                <div class="ui right floated">
                                    <div class="ui image large label">
                                        <img src="assets/images/iconos/sol_peruano.png">
                                        <label class="totalFormLabel" style="margin:0px;"><?=!empty($row['total']) ? $row['total'] : 0 ?></label>
                                    </div>
                                    <input class="form-control totalForm" type="hidden" name="totalForm" value="<?=!empty($row['total']) ? $row['total'] : 0 ?>" placeholder="0" readonly="">
                                </div>
                            </th>
                            <th colspan="3">

                            </th>
                        </tr>
                    </tfoot> -->
                </table>
            </div>
        </div>
    </div>
</form>