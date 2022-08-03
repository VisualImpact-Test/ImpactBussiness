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
        height: 200px !important;
    }
</style>
<div class="ui form attached fluid segment p-4 <?= !empty($disabled) ? 'disabled' : '' ?>">
    <form class="ui form" role="form" id="formRegistroCotizacion" method="post">
        <input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
        <h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
        <div class="fields">
            <div class="eleven wide field">
                <div class="ui sub header">Título</div>
                <input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
            </div>
            <div class="five wide field">
                <div class="ui sub header">Deadline compras</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>" patron="requerido">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>" patron="requerido">
            </div>
        </div>
        <div class="fields">
            <div class="four wide field">
                <div class="ui sub header">Fecha requerida</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Fecha Requerida" value="<?= !empty($cotizacion['fechaRequerida']) ? $cotizacion['fechaRequerida'] : '' ?>">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="<?= !empty($cotizacion['fechaRequerida']) ? $cotizacion['fechaRequerida'] : '' ?>">
            </div>
            <div class="four wide field">
                <div class="ui sub header">Solicitante</div>
                <select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idSolicitante']) ? $cotizacion['idSolicitante'] : '']); ?>
                </select>
            </div>
            <div class="four wide field">
                <div class="ui sub header">Cuenta</div>
                <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
                </select>
            </div>
            <div class="four wide field">
                <div class="ui sub header">Centro de costo</div>
                <select class="ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuentaCentroCosto']) ? $cotizacion['idCuentaCentroCosto'] : '']); ?>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="five wide field">
                <div class="ui sub header">Prioridad</div>
                <select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idPrioridad']) ? $cotizacion['idPrioridad'] : '']); ?>
                </select>
            </div>
            <div class="eleven wide field">
                <div class="ui sub header">Motivo</div>
                <input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
            </div>

        </div>
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui sub header">Comentario</div>
                <input id="comentarioForm" name="comentarioForm" placeholder="Comentario" value="<?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?>">
            </div>
        </div>
        <h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
        </h4>
        <div class="fields">
            <div class="thirteen wide field">
                <div class="ui sub header">Proveedor</div>
                <select class="ui fluid search <?= $col_dropdown ?> dropdown simpleDropdown proveedorSolicitudForm" multiple="" name="proveedorSolicitudForm">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedores, 'class' => 'text-titlecase', 'id' => 'idProveedor', 'value' => 'razonSocial']); ?>
                </select>
            </div>
            <div class="three wide field">
                <div class="ui sub header">&nbsp;</div>
                <button type='button' class="ui labeled icon green button w-100 btnSolicitarCotizacion">
                    <i class="hand holding usd icon"></i>
                    Solicitar cotización
                </button>

            </div>
        </div>
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
                                <? if (!empty($cotizacionProveedor[$row['idCotizacionDetalle']])) { ?>
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

                                    if ($prov >= 2) {
                                        $wide = 'two';
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
                        <div class="sixteen wide tablet twelve wide computer column">
                            <div class="fields">

                                <div class="six wide field">
                                    <div class="ui sub header">Item</div>
                                    <div class="ui-widget">
                                        <div class="ui icon input w-100">
                                            <input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>">
                                            <i class="semaforoForm flag link icon"></i>
                                        </div>

                                        <input class="codItems" type='hidden' name='idItemForm' value="<?= $row['idItem'] ?>">

                                        <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                        <input class="idProveedor" type='hidden' name='idProveedorForm' value="<?=!empty($row['idProveedor'])? $row['idProveedor'] : ""; ?>">
                                        <input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
                                    </div>
                                </div>
                                <div class="five wide field">
                                    <div class="ui sub header">Tipo Item</div>
                                    <select class="ui dropdown simpleDropdown idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                        <?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
                                    </select>
                                </div>
                                <div class="five wide field">
                                    <div class="ui sub header">Características</div>
                                    <div class="ui right labeled input w-100">
                                        <input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' patron="requerido" value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Caracteristicas del item">
                                    </div>
                                </div>
                            </div>
                            <!-- Textiles -->
                            <div class="fields d-none div-feature-<?= COD_TEXTILES['id'] ?>">
                                <div class="five wide field">
                                    <div class="ui sub header">Talla</div>
                                    <input name="<?= COD_TEXTILES['nombre'] ?>[talla]" placeholder="Talla" value="<?= !empty($row['talla']) ? $row['talla'] : '' ?>" readonly>
                                </div>
                                <div class="five wide field">
                                    <div class="ui sub header">Tela</div>
                                    <input name="<?= COD_TEXTILES['nombre'] ?>[tela]" placeholder="Tela" value="<?= !empty($row['tela']) ? $row['tela'] : '' ?>" readonly>
                                </div>
                                <div class="five wide field">
                                    <div class="ui sub header">Color</div>
                                    <input name="<?= COD_TEXTILES['nombre'] ?>[color]" placeholder="Color" value="<?= !empty($row['color']) ? $row['color'] : '' ?>" readonly>
                                </div>
                            </div>

                            <!-- Monto S/ -->
                            <div class="fields d-none div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
                                <div class="sixteen wide field">
                                    <div class="ui sub header">Monto S/</div>
                                    <input name="<?= COD_TARJETAS_VALES['nombre'] ?>[monto]" placeholder="Monto" value="<?= !empty($row['monto']) ? $row['monto'] : '' ?>" readonly>
                                </div>
                            </div>

                            <div class="fields">
                                <div class="four wide field">
                                    <div class="ui sub header">Archivos</div>
                                    <div class="ui small image btn-add-file text-center">
                                        <div class="ui dimmer">
                                            <div class="content">
                                                <div class="ui small primary button" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();">
                                                    Agregar
                                                </div>
                                            </div>
                                        </div>
                                        <img class="ui image" src="<?= IMG_WIREFRAME ?>">
                                    </div>
                                </div>
                                <div class="twelve wide field">
                                    <div class="ui sub header">Links</div>
                                    <div class="ui left corner labeled input">
                                        <div class="ui left corner label">
                                            <i class="linkify icon"></i>
                                        </div>
                                        <textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"><?= !empty($row['enlaces']) ? $row['enlaces'] : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="content-lsck-capturas">
                                <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*,.pdf" multiple="">
                                <div class="fields ">
                                    <div class="sixteen wide field">
                                        <div class="ui small images content-lsck-galeria">
                                            <? if (!empty($cotizacionDetalleArchivos[$row['idCotizacionDetalle']])) { ?>
                                                <? foreach ($cotizacionDetalleArchivos[$row['idCotizacionDetalle']] as $archivo) {
                                                    if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) { ?>
                                                        <div class="ui fluid image content-lsck-capturas">
                                                            <div class="ui dimmer dimmer-file-detalle">
                                                                <div class="content">
                                                                    <p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
                                                                </div>
                                                            </div>
                                                            <a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
                                                            <a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                                            <input type="hidden" name="file-item[<?= $row['idCotizacionDetalle'] ?>]" value="">
                                                            <input type="hidden" name="file-type[<?= $row['idCotizacionDetalle'] ?>]" value="image/<?= $archivo['extension'] ?>">
                                                            <input type="hidden" name="file-name[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
                                                            <img height="100" src="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                                                        </div>
                                                <? }
                                                } ?>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="fields ">
                                    <div class="sixteen wide field">
                                        <div class="ui small images content-lsck-files">
                                            <? if (!empty($cotizacionDetalleArchivos[$row['idCotizacionDetalle']])) { ?>
                                                <? foreach ($cotizacionDetalleArchivos[$row['idCotizacionDetalle']] as $archivo) {
                                                    if ($archivo['idTipoArchivo'] == TIPO_PDF) { ?>
                                                        <div class="ui fluid image content-lsck-capturas">
                                                            <div class="ui dimmer dimmer-file-detalle">
                                                                <div class="content">
                                                                    <p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
                                                                </div>
                                                            </div>
                                                            <a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
                                                            <a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                                            <input type="hidden" name="file-item[<?= $row['idCotizacionDetalle'] ?>]" value="">
                                                            <input type="hidden" name="file-type[<?= $row['idCotizacionDetalle'] ?>]" value="application/<?= $archivo['extension'] ?>">
                                                            <input type="hidden" name="file-name[<?= $row['idCotizacionDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
                                                            <img height="100" src="<?= RUTA_WIREFRAME . "pdf.png" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                                                        </div>
                                                <? }
                                                } ?>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide tablet four wide computer column">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui sub header">Cantidad</div>
                                    <input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui sub header">Costo</div>
                                    <div class="ui right labeled input">
                                        <label for="amount" class="ui label">S/</label>
                                        <input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo']) : '' ?>" readonly>
                                        <input class="costoForm" type="hidden" name="costoForm" patron="requerido" placeholder="0.00" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="eight wide field">
                                    <div class="ui sub header">GAP %</div>
                                    <div class="ui right labeled input">
                                        <input data-max='100' data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($row['gap']) ? $row['gap'] : '' ?>">
                                        <div class="ui basic label">
                                            %
                                        </div>
                                    </div>
                                </div>
                                <div class="eight wide field">
                                    <div class="ui sub header">Precio</div>
                                    <div class="ui right labeled input">
                                        <label for="amount" class="ui label">S/</label>
                                        <input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" readonly>
                                        <input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field">
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
        <div class="ui black three column center aligned stackable divided grid segment">
            <div class="column">
                <div class="ui test toggle checkbox">
                    <input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();" <?= $cotizacion['igv'] ? 'checked' : '' ?>>
                    <label>Incluir IGV</label>
                </div>
            </div>
            <div class="column">
                <!-- <div class="ui sub header">Total</div> -->
                <div class="ui right labeled input">
                    <label for="feeForm" class="ui label">Fee: </label>
                    <input data-max='100' data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['fee']) ? $cotizacion['fee'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();">
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
            <span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "<?= $controller ?>.registrarCotizacion(<?= $siguienteEstado ?>)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });'>
                <i class="send icon"></i>
            </span>
            <span class="float-element tooltip-left btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "<?= $controller ?>.registrarCotizacion(1)", content: "¿Esta seguro de guardar esta cotizacion?" });'>
                <i class="save icon"></i>
            </span>
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
<!-- Items -->
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>