<!-- <div class="ui attached  message">
  <div class="header">
    Registrar Cotización
  </div>
</div> -->
<style>
    .img-lsck-capturas {
        height: 150px !important;
    }

    .floating-container {
        height: 150px !important;
    }
</style>
<div class="ui form attached fluid segment p-4 <?= !empty($disabled) ? 'disabled' : '' ?>">
    <form class="ui form" role="form" id="formRegistroOrdenCompra" method="post">
        <input type="hidden" name="idOper" value="<?= $idOper ?>"> 
    <!-- <h4 class="ui dividing header">DETALLE DEL OPER
        </h4>
        <div class="fields">
            <div class="three wide field">
                <div class="ui sub header">&nbsp;</div>

            </div>
        </div> -->
        <? foreach ($cotizaciones as $cotizacion) { ?>
            <input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
            <div class="default-item">
                <? foreach ($cotizacionDetalle as $row) : ?>
                    <input type="hidden" name="idCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>" id="">
                    <div class="ui segment body-item nuevo" data-id="<?= $row['idCotizacionDetalle'] ?>">
                        <div class="ui right floated header">
                            <div class="ui icon menu">
                                <a class="item chk-item" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); ">
                                    <i class="square outline icon"></i>
                                    <input type="checkbox" name="checkItem" class="d-none">
                                </a>
                                <a class="item btnPopupCotizacionesProveedor" data-proveedores='<?= !empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas']) ?>' data-id="<?= $row['idCotizacionDetalle'] ?>">
                                    <i class="hand holding usd icon"></i>
                                    <? if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'])) { ?>
                                        <div class="floating ui teal label"><?= $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'] ?></div>
                                    <? } ?>
                                </a>
                            </div>
                            <? if (!empty($cotizacionProveedorVista[$row['idCotizacionDetalle']])) { ?>
                                <div class="ui flowing custom popup custom-popup-<?= $row['idCotizacionDetalle'] ?> top left transition hidden">
                                    <?
                                    $wide = 'one';
                                    if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'])) {
                                        $prov = $cotizacionProveedor[$row['idCotizacionDetalle']]['cotizacionesConfirmadas'];

                                        if ($prov == 2) {
                                            $wide = 'two';
                                        }
                                        if ($prov == 3) {
                                            $wide = 'three';
                                        }
                                        if ($prov >= 4) {
                                            $wide = 'four';
                                        }
                                    }
                                    ?>
                                    <div class="ui <?= $wide ?> column divided center aligned grid">
                                        <? foreach ($cotizacionProveedorVista[$row['idCotizacionDetalle']] as $view) { ?>
                                            <div class="column">
                                                <h4 class="ui header"><?= $view['razonSocial'] ?></h4>
                                                <p><b><?= $view['cantidad'] ?></b> cantidad, <?= moneda($view['subTotal']) ?></p>
                                                <p><b>Costo Unitario: </b> <?= moneda($view['costoUnitario']) ?></p>


                                                <div class="ui button btnElegirProveedor">Elegir
                                                    <input type="hidden" class="txtCostoProveedor" value="<?= $view['costoUnitario'] ?>">
                                                    <input type="hidden" class="txtProveedorElegido" value="<?= $view['idProveedor'] ?>">
                                                    <input type="hidden" class="txtProveedorElegidoName" value="<?= $view['razonSocial'] ?>">
                                                </div>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                            <? } ?>

                        </div>
                        <div class="ui left floated header">
                            <span class="ui medium text "><?= $row['item'] ?></span></span>
                        </div>
                        <div class="ui clearing divider"></div>
                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <div class="fields">
                                    <div class="six wide field d-none">
                                        <div class="ui sub header">Item</div>
                                        <div class="ui-widget">
                                            <div class="ui icon input w-100">
                                                <input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>">
                                                <i class="semaforoForm flag link icon"></i>
                                            </div>

                                            <input class="codItems" type='hidden' name='idItemForm' value="<?= $row['idItem'] ?>">

                                            <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                            <input class="idProveedor" type='hidden' name='idProveedorForm' value="<?= !empty($row['idProveedor']) ? $row['idProveedor'] : '' ?>">
                                            <input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
                                        </div>
                                    </div>
                                    <div class="six wide field">
                                        <div class="ui sub header">Proveedor</div>
                                        <input class="proveedoresForm" type='text' value="<?= $row['razonSocial'] ?>" readonly>
                                    </div>
                                    <div class="five wide field">
                                        <div class="ui sub header">Tipo Item</div>
                                        <select class="ui dropdown simpleDropdown read-only idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                            <?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
                                        </select>
                                    </div>
                                    <div class="five wide field">
                                        <div class="ui sub header">Características</div>
                                        <div class="ui right labeled input w-100">
                                            <input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Caracteristicas del item">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide column">
                                <div class="fields">
                                    <div class="three wide field">
                                        <div class="ui sub header">Cantidad</div>
                                        <input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" readonly patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">
                                            Costo
                                            <?if(!empty($autorizaciones[$row['idCotizacionDetalle']])){?>
                                                <?=$autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_PENDIENTE ?'<small class="text-info">(Solicitud enviada)</small>' : '' ?>
                                                <?=$autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_ACEPTADO ?'<small class="text-sucess">(Solicitud aceptada)</small>' : '' ?>
                                                <?=$autorizaciones[$row['idCotizacionDetalle']]['idAutorizacionEstado'] == AUTH_ESTADO_RECHAZADO ?'<small class="text-danger">(Solicitud rechazada)</small>' : '' ?>
                                            <?}?>
                                        </div>
                                        <div class="ui right labeled input <?=!empty($autorizaciones[$row['idCotizacionDetalle']]) ? 'disabled disabled-visible' : ''?>">
                                            <label for="amount" class="ui label">S/</label>
                                            <input class="costoFormLabelEditable costoFormLabel onlyNumbers" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? number_format($row['costo'], 2, '.', ',') : '' ?>">
                                            <input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">GAP %</div>
                                        <div class="ui right labeled input">
                                            <input data-max='100' data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($row['gap']) ? $row['gap'] : '' ?>" readonly>
                                            <div class="ui basic label">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">Precio</div>
                                        <div class="ui right labeled input">
                                            <label for="amount" class="ui label">S/</label>
                                            <input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" readonly>
                                            <input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="four wide field">
                                        <div class="ui sub header">Subtotal</div>
                                        <div class="ui right labeled input">
                                            <label for="amount" class="ui label teal">S/</label>
                                            <input class=" subtotalFormLabel" type="text" placeholder="0.00" patron="requerido" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal']) : '' ?>" readonly>
                                            <input class=" subtotalForm" type="hidden" patron="requerido" name="subtotalForm" placeholder="0.00" value="<?= !empty($row['subtotal']) ? ($row['subtotal']) : '' ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        <? } ?>
        <div class="ui black three column center aligned stackable divided grid segment">
            <div class="column">
                <div class="ui test toggle checkbox ">
                    <input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();" <?= $cotizacion['igv'] ? 'checked' : '' ?>  readonly >
                    <label>Incluir IGV</label>
                </div>
            </div>
            <div class="column">
                <div class="ui right labeled input">
                    <label for="feeForm" class="ui label">Fee: </label>
                    <input data-max='100' data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();" readonly>
                    <div class="ui basic label">
                        %
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="ui right labeled input">
                    <label for="totalForm" class="ui label green">Total: </label>
                    <input class=" totalFormLabel" type="text" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? moneda($cotizacion['total']) : '0.00' ?>" readonly="">
                    <input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? ($cotizacion['total']) : '0.00' ?>" readonly="">
                    <input class=" totalFormFeeIgv" type="hidden" name="totalFormFeeIgv" placeholder="0.00" readonly="">
                    <input class=" totalFormFee" type="hidden" name="totalFormFee" placeholder="0.00" readonly="">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- FAB -->
<div class="floating-container">
    <div class="floating-button ">
        <i class="cog icon"></i>
    </div>
    <div class="element-container">
        <a href="javascript:;">
            <span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroOrdenCompra", fn: "<?= $controller ?>.registrarOrdenCompra(<?= $siguienteEstado ?>)", content: "¿Esta seguro de generar ordenes de compra para cada proveedor seleccionado?" });'>
                <i class="send icon"></i>
            </span>
            <!-- <span class="float-element tooltip-left btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "formRegistroOrdenCompra", fn: "<?= $controller ?>.registrarCotizacion(1)", content: "¿Esta seguro de guardar esta cotizacion?" });'>
                <i class="save icon"></i>
            </span> -->
            <!-- <span class="float-element tooltip-left btn-add-detalle btn-add-row" onclick="" data-message="Agregar detalle">
                <i class="plus icon"></i>
            </span> -->
        </a>
    </div>
</div>

<!-- Popup Leyenda -->
<div class="ui leyenda popup top left transition hidden">
    <div class="ui list">
        <div class="item">
            <i class="flag icon teal"></i>
            <div class="content">
                7 días
            </div>
        </div>
        <div class="item">
            <i class="flag icon yellow"></i>
            <div class="content">
                8 a 15 días
            </div>
        </div>
        <div class="item">
            <i class="flag icon red"></i>
            <div class="content">
                +15 días
            </div>
        </div>
    </div>
    <div class="ui clearing divider"></div>
    <div class="ui list">
        <div class="item">
            <i class="square icon teal"></i>
            <div class="content">
                Subtotal
            </div>
        </div>
        <div class="item">
            <i class="square icon green"></i>
            <div class="content">
                Total
            </div>
        </div>
    </div>
</div>
<div class="ui modal">
    <div class="center aligned header">Header is centered</div>
    <div class="center aligned content">
        <p>Content is centered</p>
    </div>
    <div class="center aligned actions">
        <div class="ui negative button">Cancel</div>
        <div class="ui positive button">OK</div>
    </div>
</div>

<div class="ui red fixed nag" id="nagPrecioValidacion">
    <div class="title">EL COSTO NO PUEDE SER IGUAL O MAYOR AL PRECIO</div>
    <i class="close icon"></i>
</div>

<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>