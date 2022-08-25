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
    input[type="color"] {
        padding:initial !important;
    }
</style>
<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formRegistroCotizacion" method="post">
        <h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
        <div class="fields">
            <div class="six wide field">
                <div class="ui sub header">Título</div>
                <input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion">
            </div>
            <div class="four wide field">
                <div class="ui sub header">Deadline compras</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Deadline compras" value="" patron="requerido">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="" patron="requerido">
            </div>
            <div class="four wide field">
                <div class="ui sub header">Fecha requerida</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Fecha Requerida" value="">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="">
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
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="five wide field">
                <div class="ui sub header">Cuenta</div>
                <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
            <div class="six wide field">
                <div class="ui sub header">Centro de costo</div>
                <select class="ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="five wide field">
                <div class="ui sub header">Prioridad</div>
                <select class="ui search dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase', 'selected' => $cotizacion['idPrioridad']]); ?>
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
                                    <div class="ui small primary button" onclick="$(this).parents('.anexos').find('.file-lsck-capturas-anexos').click();">
                                        Agregar
                                    </div>
                                </div>
                            </div>
                            <img class="ui image" src="<?= IMG_WIREFRAME ?>">
                        </div>
                        <input type="file" name="capturas" class="file-lsck-capturas-anexos form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*" multiple="">
                    </div>
                </div>
            </div>
        </div>
        <h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
        </h4>
        <div class="default-item">
            <div class="ui segment body-item nuevo">
                <div class="ui right floated header">
                    <div class="ui icon menu">
                        <a class="item btn-bloquear-detalle" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
                            <i class="unlock icon"></i>
                        </a>
                        <a class="item btn-eliminar-detalle btneliminarfila">
                            <i class="trash icon"></i>
                        </a>
                    </div>
                </div>
                <div class="ui left floated header">
                    <span class="ui medium text ">Detalle N. <span class="title-n-detalle">00001</span></span>
                </div>
                <div class="ui clearing divider"></div>
                <div class="ui grid">
                    <div class="sixteen wide tablet twelve wide computer column">
                        <div class="fields">

                            <div class="six wide field">
                                <div class="ui sub header">Item</div>
                                <div class="ui-widget">
                                    <div class="ui icon input w-100">
                                        <input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item">
                                        <i class="semaforoForm flag link icon"></i>
                                    </div>

                                    <input class="codItems" type='hidden' name='idItemForm'>

                                    <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                    <input class="idProveedor" type='hidden' name='idProveedorForm' value="">
                                    <input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
                                </div>
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Tipo Item</div>
                                <select class="ui dropdown simpleDropdown idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione']); ?>
                                </select>
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Características para el cliente</div>
                                <div class="ui right labeled input w-100">
                                    <input class="" type='text' id="caracteristicasItem" name='caracteristicasItem' placeholder="Características del item">
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
                        <div class="ui form attached fluid segment my-3 d-none div-features div-feature-<?= COD_TEXTILES['id'] ?>">
                            <h4 class="ui dividing header">SUB ITEMS</h4>
                            <div class="content-body-sub-item">
                                <div class="fields body-sub-item ">
                                    <div class="six wide field">
                                        <div class="ui sub header">Talla</div>
                                        <input class="tallaSubItem camposTextil" name="tallaSubItem[0]" placeholder="Talla" value="<?= !empty($data['talla']) ? $data['talla'] : '' ?>">
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">Tela</div>
                                        <input class="telaSubItem camposTextil" name="telaSubItem[0]" placeholder="Tela" value="<?= !empty($data['tela']) ? $data['tela'] : '' ?>">
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">Color</div>
                                        <input  class="colorSubItem " name="colorSubItem[0]" placeholder="Color" value="<?= !empty($data['color']) ? $data['color'] : '' ?>" >
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">Cantidad</div>
                                        <input class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[0]" placeholder="Cantidad" value="<?= !empty($data['cantidadSubItem']) ? $data['cantidadSubItem'] : '' ?>">
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
                        <!-- Monto S/ -->
                        <div class="fields d-none div-features div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Monto S/</div>
                                <input class="montoSubItem" name="montoSubItem[0]" placeholder="Monto" value="<?= !empty($data['montoSubItem']) ? $data['montoSubItem'] : '' ?>">
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div class="ui form attached fluid segment my-3 d-none div-features div-feature-<?= COD_SERVICIO['id'] ?>" data-tipo="<?= COD_SERVICIO['id'] ?>">
                            <h4 class="ui dividing header">SUB ITEMS</h4>
                            <div class="content-body-sub-item">
                                <div class="fields body-sub-item body-sub-item-servicio">
                                    <div class="ten wide field">
                                        <div class="ui sub header">Sub item </div>
                                        <input class="nombreSubItem" name="nombreSubItemServicio[0]" placeholder="Nombre" value="<?= !empty($data['nombreSubItem']) ? $data['nombreSubItem'] : '' ?>">
                                    </div>
                                    <div class="five wide field">
                                        <div class="ui sub header">Cantidad</div>
                                        <input class="onlyNumbers cantidadSubItem cantidadSubItemAcumulativo" name="cantidadSubItemServicio[0]" placeholder="0" value="<?= !empty($data['cantidadSubItem']) ? $data['cantidadSubItem'] : '' ?>">
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
                                <select class="ui search dropdown simpleDropdown tipoServicioForm tipoServicioSubItem" name="tipoServicioSubItem[0]">
                                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
                                </select>
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Unidad de medida</div>
                                <input class="unidadMedidaTipoServicio" placeholder="Unidad Medida" value="<?= !empty($data['unidadMedidaTipoServicio']) ? $data['unidadMedidaTipoServicio'] : '' ?>" readonly>
                                <input type="hidden" class="unidadMedidaSubItem" name="unidadMedidaSubItem[0]" placeholder="Unidad Medida" value="<?= !empty($data['idUnidadMedidaTipoServicio']) ? $data['idUnidadMedidaTipoServicio'] : '' ?>" readonly>
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Costo S/</div>
                                <input class="costoTipoServicio costoSubItem" name="costoSubItem[0]" placeholder="Costo" value="<?= !empty($data['costoTipoServicio']) ? $data['costoTipoServicio'] : '' ?>" readonly>
                            </div>
                            <div class="three wide field">
                                <div class="ui sub header">Cantidad</div>
                                <input class="onlyNumbers cantidadSubItemDistribucion cantidadSubItem" name="cantidadSubItemDistribucion[0]" placeholder="Cantidad" value="<?= !empty($data['cantidadSubItem']) ? $data['cantidadSubItem'] : '' ?>">
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
                                    <textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-lsck-capturas">
                            <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="<?= ARCHIVOS_PERMITIDOS ?>" multiple="">
                            <div class="fields ">
                                <div class="sixteen wide field">
                                    <div class="ui small images content-lsck-galeria">

                                    </div>
                                </div>
                            </div>
                            <div class="fields ">
                                <div class="sixteen wide field">
                                    <div class="ui small images content-lsck-files">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sixteen wide tablet four wide computer column">
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">
                                    Cantidad <div class="ui btn-info-custom text-primary btn-info-cantidad"><i class="info circle icon"></i></div>
                                </div>
                                <input class="form-control cantidadForm" type="number" name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Costo</div>
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class="costoForm" type="text" name="costoForm" placeholder="0.00" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <div class="ui sub header">
                                    GAP <div class="ui btn-info-custom text-primary btn-info-gap"><i class="info circle icon"></i></div>
                                </div>
                                <div class="ui right labeled input">
                                    <input onkeypress="$(this).closest('.nuevo').find('.costoForm').val() == 0 ? $(this).attr('readonly','readonly') : $(this).removeAttr('readonly') "   data-max='100' data-min='0' type="number" id="gapForm" class="onlyNumbers gapForm" name="gapForm" placeholder="Gap" value="<?= !empty($cotizacion['gap']) ? $cotizacion['gap'] : '0' ?>">
                                    <div class="ui basic label">
                                        %
                                    </div>
                                </div>
                            </div>
                            <div class="eight wide field">
                                <div class="ui sub header">Precio</div>
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label">S/</label>
                                    <input class=" precioFormLabel" type="text" placeholder="0.00" readonly>
                                    <input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Subtotal</div>
                                <div class="ui right labeled input">
                                    <label for="amount" class="ui label teal">S/</label>
                                    <input class="subtotalFormLabel" type="text" placeholder="0.00" readonly>
                                    <input class="subtotalForm" type="hidden" name="subtotalForm" placeholder="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui black three column center aligned stackable divided grid segment">
            <div class="column">
                <div class="ui test toggle checkbox">
                    <input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();">
                    <label>Incluir IGV</label>
                </div>
            </div>
            <div class="column">
                <!-- <div class="ui sub header">Total</div> -->
                <div class="ui right labeled input">
                    <label for="feeForm" class="ui label">Fee: </label>
                    <input data-max='100' data-min='0' type="number" id="feeForm" class="onlyNumbers" name="feeForm" placeholder="Fee" value="<?= !empty($cotizacion['gap']) ? $cotizacion['gap'] : '' ?>" onkeyup="Cotizacion.actualizarTotal();">
                    <div class="ui basic label">
                        %
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="ui right labeled input">
                    <label for="totalForm" class="ui label green">Total: </label>
                    <input class=" totalFormLabel" type="text" placeholder="0.00" readonly="">
                    <input class=" totalFormFeeIgv" type="hidden" name="totalFormFeeIgv" placeholder="0.00" readonly="">
                    <input class=" totalFormFee" type="hidden" name="totalFormFee" placeholder="0.00" readonly="">
                    <input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" readonly="">
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
            <span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(2)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });'>
                <i class="send icon"></i>
            </span>
            <!-- <span class="float-element tooltip-left btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(1)", content: "¿Esta seguro de guardar esta cotizacion?" });'>
                <i class="save icon"></i>
            </span> -->
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