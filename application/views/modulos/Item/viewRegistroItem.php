<!-- <div class="ui attached  message">
  <div class="header">
    Registrar Cotización
  </div>
</div> -->
<style>
    .img-lsck-capturas{
        height: 150px !important;
    }
</style>
<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formRegistroItems" method="post">
        <h4 class="ui dividing header">DETALLE DE LOS ITEMS 
        </h4>
        <div class="default-item">
            <div class="ui segment body-item nuevo">
                <div class="ui right floated header">
                    <div class="ui icon menu">
                        <a class="item btn-bloquear-detalle-item" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
                            <i class="lock icon"></i>
                        </a>
                        <a class="item btn-eliminar-detalle-item btneliminarfila">
                            <i class="trash icon"></i>
                        </a>
                    </div>
                </div>
                <div class="ui left floated header">
                    <span class="ui medium text ">Item N. <span class="title-n-detalle">00001</span></span>
                </div>
                <div class="ui clearing divider"></div>
                <div class="ui grid">
                    <div class="sixteen wide tablet twelve wide computer column">
                        <div class="fields">

                            <div class="six wide field">
                                <div class="ui sub header">Nombre</div>
                                <div class="ui-widget">
                                    <div class="ui icon input w-100">
                                        <input class="form-control items <?= (!empty($nombreItem)) ? "disabled" : "" ?>" id="nombre" name="nombre" patron="requerido" value="<?= (!empty($nombreItem)) ? $nombreItem : "" ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Tipo Item</div>
                                <select class="ui dropdown simpleDropdown tipoArticulo" id="tipo" name="tipo" patron="requerido">
                                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Características</div>
                                <div class="ui right labeled input w-100">
                                <input type='text' class=" <?= (!empty($caracteristicasItem)) ? "disabled" : "" ?>" id="caracteristicas" name="caracteristicas" patron="requerido" value="">
                                </div>
                            </div>
                        </div>
                        <!-- Textiles -->
                        <div class="fields d-none campos_dinamicos div-feature-<?= COD_TEXTILES['id'] ?>">
                            <div class="five wide field">
                                <div class="ui sub header">Talla</div>
                                <input class="form-control" id="talla" name="talla">
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Tela</div>
                                <input class="form-control" id="tela" name="tela">
                            </div>
                            <div class="five wide field">
                                <div class="ui sub header">Color</div>
                                <input class="form-control" id="color" name="color">
                            </div>
                        </div>

                        <!-- Monto S/ -->
                        <div class="fields d-none campos_dinamicos div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Monto S/</div>
                                <input class="form-control" id="monto" name="monto">
                            </div>
                        </div>

                        <div class="fields">

                        <!-- vista de las archivos -->
                            <div class="two wide field">
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
                            <!-- vista de los archivos -->

                            <div class="fourteen wide field">
                            <div class="content-lsck-capturas"> <!-- vista de las imagenes -->
                            <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*,.pdf" multiple="">
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
                        </div>
                        <!-- vista de las imagenes -->
                       
                        <!-- vista de las imagenes -->
                    </div>
                    <div class="sixteen wide tablet four wide computer column">
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Marca</div>
                                <select class="form-control" id="marca" name="marca" patron="requerido">
                                   <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <div class="ui sub header">Categoria</div>
                                <select class="form-control" id="categoria" name="categoria" patron="requerido">
                                   <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                        </div>
                        <div class="fields">
                        <div class="sixteen wide field">
                                <div class="ui sub header">Subcategoria</div>
                                <select class="form-control" name="subcategoria" id="subcategoria" patron="requerido">
                                   <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $subcategoriaItem, 'class' => 'text-titlecase']); ?>
                                </select>
                            </div>
                        </div>
                        <div class="fields">
                        <div class="sixteen wide field">
                                <div class="ui sub header">Equivalente en logistica</div>
                                <input class="form-control" id="equivalente" name="equivalente" placeholder="Buscar ">
                                <input class="d-none" id="idItemLogistica" name="idItemLogistica">
                            </div>
                        </div>
                    </div>
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
            <span class="float-element tooltip-left btn-send-item" data-message="Registrar" onclick='Fn.showConfirm({ idForm: "formRegistroItems", fn: "Item.registrarItem()", content: "¿Esta seguro de registrar este item?" });'>
                <i class="send icon"></i>
            </span>
            <span class="float-element tooltip-left btn-add-detalle-item btn-add-row" onclick="" data-message="Agregar Item">
                <i class="plus icon"></i>
            </span>
        </a>
    </div>
</div>

<!-- Items -->
<input id="itemsServicio" type="hidden" value='<?= json_encode($informacionItem) ?>'>
