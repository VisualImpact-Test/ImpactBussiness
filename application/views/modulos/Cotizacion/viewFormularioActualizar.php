<!-- <div class="ui attached  message">
  <div class="header">
    Registrar Cotización
  </div>
</div> -->
<style>
    .img-lsck-capturas {
        height: 150px !important;
    }

    .btn-info-custom {
        cursor: pointer;
        display: inline-block;
        line-height: 1;
    }
</style>
<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formActualizarCotizacion" method="post">
        <input type="hidden" name="idCotizacion" value="<?= !empty($cotizacion['idCotizacion']) ? $cotizacion['idCotizacion'] : '' ?>">
        <h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
        <div class="fields">
            <div class="six wide field">
                <div class="ui sub header">Título</div>
                
                <input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion" value="<?= !empty($cotizacion['cotizacion']) ? $cotizacion['cotizacion'] : '' ?>">
            </div>
            <div class="four wide field">
                <div class="ui sub header">Deadline compras</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>" patron="requerido">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="<?= !empty($cotizacion['fechaDeadline']) ? $cotizacion['fechaDeadline'] : '' ?>" patron="requerido">
            </div>
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
            <div class="two wide field">
                <div class="ui sub header">
                    Validez <div class="ui btn-info-validez btn-info-custom text-primary"><i class="info circle icon"></i></div>
                </div>
                <input class="onlyNumbers" id="diasValidez" name="diasValidez" patron="requerido" placeholder="Días de validez">
            </div>
        </div>
        <div class="fields">
            <div class="five wide field">
                <div class="ui sub header">Solicitante</div>
                <select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idSolicitante']) ? $cotizacion['idSolicitante'] : '']); ?>
                </select>
            </div>
            <div class="five wide field">
                <div class="ui sub header">Cuenta</div>
                <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idCuenta']) ? $cotizacion['idCuenta'] : '']); ?>
                </select>
            </div>
            <div class="six wide field">
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
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => $cotizacion['idPrioridad'], 'selected' => !empty($cotizacion['idPrioridad']) ? $cotizacion['idPrioridad'] : '']); ?>
                </select>
            </div>
            <div class="eleven wide field">
                <div class="ui sub header">
                    Motivo <div class="ui btn-info-motivo btn-info-custom text-primary"><i class="info circle icon"></i></div>
                </div>
                <input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
            </div>

        </div>
        <div class="fields">
            <div class="eight wide field">
                <div class="ui sub header">Comentario</div>
                <textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?></textarea>
                <!-- <input id="comentarioForm" name="comentarioForm" placeholder="Comentario" value="<?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?>"> -->
            </div>
            <div class="eight wide field anexos">
                <div class="ui sub header">Anexos</div>
                <div class="ui small images content-lsck-capturas">
                    <div class="content-lsck-galeria">
                        <div class="ui small image text-center btn-add-file">
                            <div class="ui dimmer">
                                <div class="content">
                                    <div class="ui small primary button" onclick="$(this).parents('.anexos').find('.file-lsck-capturas').click();">
                                        Agregar
                                    </div>
                                </div>
                            </div>
                            <img class="ui image" src="<?= IMG_WIREFRAME ?>">
                        </div>
                        <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*" multiple="">
                    </div>
                </div>
            </div>
        </div>
        <h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
        </h4>
        <div class="default-item">
        <? foreach ($cotizacionDetalle as $row) : ?>
            <input type="hidden" name="idCotizacionDetalle" value="<?= $row['idCotizacionDetalle'] ?>" id="">
            <div class="ui segment body-item nuevo" data-id="<?= $row['idCotizacionDetalle'] ?>">
                <div class="ui right floated header">
                    <div class="ui icon menu">
                        <a class="item btn-bloquear-detalle" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
                            <i class="lock icon"></i>
                        </a>
                        <a class="item btn-eliminar-detalle btneliminarfila">
                            <i class="trash icon"></i>
                        </a>
                    </div>
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
                                <div class="ui sub header">Características para el cliente</div>
                                <div class="ui right labeled input w-100">
                                    <input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' value="<?= !empty($row['caracteristicas']) ? $row['caracteristicas'] : '' ?>" placeholder="Características del item">
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Características para compras</div>
                                <input name="caracteristicasCompras" placeholder="Características" value="">
                            </div>
                        </div>
                        <!-- Textiles -->
                        <div class="fields d-none div-features div-feature-<?= COD_TEXTILES['id'] ?>">
                            <div class="five wide field">
                                <div class="ui sub header">Talla</div>
                                <input name="<?= COD_TEXTILES['nombre'] ?>[talla]" placeholder="Talla" value="<?= !empty($row['talla']) ? $row['talla'] : '' ?>">
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Tela</div>
                                <input name="<?= COD_TEXTILES['nombre'] ?>[tela]" placeholder="Tela" value="<?= !empty($row['tela']) ? $row['tela'] : '' ?>">
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Color</div>
                                <input name="<?= COD_TEXTILES['nombre'] ?>[color]" placeholder="Color" value="<?= !empty($row['color']) ? $row['color'] : '' ?>">
                            </div>
                        </div>

                        <!-- Monto S/ -->
                        <div class="fields d-none div-features div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Monto S/</div>
                                <input name="<?= COD_TARJETAS_VALES['nombre'] ?>[monto]" placeholder="Monto" value="<?= !empty($row['monto']) ? $row['monto'] : '' ?>">
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div class="ui form attached fluid segment my-3 d-none div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
                            <h4 class="ui dividing header">SUB ITEMS</h4>
                            <div class="content-body-sub-item">
                                <div class="fields body-sub-item body-sub-item-servicio">
                                    <div class="ten wide field">
                                        <div class="ui sub header">Sub item </div>
                                        <input name="<?= COD_SERVICIO['nombre'] ?>[nombre]" placeholder="Nombre" value="<?= !empty($row['nombreSubItem']) ? $row['nombreSubItem'] : '' ?>">
                                    </div>
                                    <div class="five wide field">
                                        <div class="ui sub header">Cantidad</div>
                                        <input class="onlyNumbers" name="<?= COD_SERVICIO['nombre'] ?>[cantidad]" placeholder="0" value="<?= !empty($row['cantidadSubItem']) ? $row['cantidadSubItem'] : '' ?>">
                                    </div>
                                    <div class="one wide field">
                                        <div class="ui sub header">Eliminar</div>
                                        <button type="button" class="ui basic button btn-eliminar-sub-item">
                                            <i class="trash icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="ui basic button btn-add-sub-item">
                                <i class="plus icon"></i>
                                Agregar
                            </button>
                        </div>
                        <!-- Distribucion -->
                        <div class="fields d-none div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>">
                            <div class="seven wide field">
                                <div class="ui sub header">Tipo Servicio</div>
                                <select class="ui search dropdown simpleDropdown tipoServicioForm" name="<?=COD_DISTRIBUCION['nombre']?>">
                                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase','data-option'=>['costo','unidadMedida']]); ?>
                                </select>
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Unidad de medida</div>
                                <input class="unidadMedidaTipoServicio" placeholder="Unidad Medida" value="<?= !empty($row['unidadMedidaTipoServicio']) ? $row['unidadMedidaTipoServicio'] : '' ?>" >
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Costo S/</div>
                                <input class="costoTipoServicio" placeholder="Costo" value="<?= !empty($row['costoTipoServicio']) ? $row['costoTipoServicio'] : '' ?>" >
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Cantidad</div>
                                <input class="onlyNumbers" name="<?=COD_DISTRIBUCION['nombre']?>" placeholder="Cantidad" value="<?= !empty($row['cantidadTipoServicio']) ? $row['cantidadTipoServicio'] : '' ?>">
                            </div>
                        </div>

                        <div class="fields">
                            <div class="four wide field">
                                <div class="ui sub header">Archivos <div class="ui btn-info-custom text-primary btn-info-archivo"><i class="info circle icon"></i></div>
                                </div>
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
                            <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="<?= ARCHIVOS_PERMITIDOS ?>" multiple="">
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
                                <input class="form-control cantidadForm" type="number" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>"  name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Costo</div>
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class="costoFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['costo']) ? moneda($row['costo']) : '' ?>" >
                                    <input class="costoForm" type="hidden" name="costoForm" value="<?= !empty($row['costo']) ? ($row['costo']) : '' ?>" placeholder="0.00" >
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <div class="ui sub header">GAP</div>
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
                                    <input class=" precioFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['precio']) ? moneda($row['precio']) : '' ?>" >
                                    <input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" value="<?= !empty($row['precio']) ? ($row['precio']) : '' ?>"  >
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Subtotal</div>
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label teal">S/</label>
                                    <input class="subtotalFormLabel" type="text" placeholder="0.00" value="<?= !empty($row['subtotal']) ? moneda($row['subtotal']) : '' ?>" >
                                    <input class="subtotalForm" type="hidden" name="subtotalForm" placeholder="0.00" value="<?= !empty($row['subtotal']) ? ($row['subtotal']) : '' ?>" >
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
                    <input class=" totalFormLabel" type="text" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? moneda($cotizacion['total']) : '0.00' ?>" >
                    <input class=" totalFormFeeIgv" type="hidden" name="totalFormFeeIgv" placeholder="0.00" value="<?= !empty($cotizacion['total']) ? ($cotizacion['total']) : '0.00' ?>" >
                    <input class=" totalFormFee" type="hidden" name="totalFormFee" placeholder="0.00" >
                    <input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" >
                </div>
            </div>
        </div>
    </form>
</div>

<!-- FAB -->
<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
<div class="floating-container">
    <div class="floating-button ">
        <i class="cog icon"></i>
    </div>
    <div class="element-container">
        <a href="javascript:;">
           
            <span class="float-element tooltip-left btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "formActualizarCotizacion", fn: "Cotizacion.actualizarCotizacionView()", content: "¿Esta seguro de guardar esta cotizacion?" });'>
                <i class="save icon"></i>
            </span>
            <span class="float-element tooltip-left btn-add-detalle btn-add-row" onclick="" data-message="Agregar detalle">
                <i class="plus icon"></i>
            </span>
        </a>
    </div>
</div>

<!-- Popup Leyenda -->
<div class="ui leyenda popup top left transition hidden">
    <div class="ui sub header">Semáforo tarifario</div>
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
    <div class="ui sub header">Otros</div>
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
<!-- Items -->
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>