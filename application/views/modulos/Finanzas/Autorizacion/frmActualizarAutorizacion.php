<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formActualizarAutorizacion" method="post">
        <input type="hidden" name="idAutorizacion" value="<?=$data['idAutorizacion']?>">
        <input type="hidden" name="nuevoValor" value="<?= $data['nuevoValor']?>">
        <input type="hidden" name="nuevoGap" value="<?= $data['nuevoGap']?>">
        <input type="hidden" name="costoAnterior" value="<?= $data['costo']?>">
        <input type="hidden" name="idCotizacionDetalle" value="<?= $data['idCotizacionDetalle']?>">
        <h4 class="ui dividing header">DATOS DE LA SOLICITUD</h4>
        <div class="fields">
            <div class="eight wide field">
                <div class="ui sub header">De:</div>
                <input type="text" value="<?= $this->usuario_completo ?>" readonly>
            </div>
            <div class="eight wide field">
                <div class="ui sub header">Dirigido a:</div>
                <input type="text" value="Gerente de Finanzas" readonly>
            </div>
        </div>
        <div class="fields disabled disabled-visible">
            <div class="four wide field">
                <div class="ui sub header">Ruc Proveedor:</div>
                <input type="text" value="<?= verificarEmpty($data['rucProveedor']) ?>" readonly>
            </div>
            <div class="twelve wide field">
                <div class="ui sub header">Proveedor:</div>
                <input type="text" value="<?= verificarEmpty($data['proveedor']) ?>" readonly>
            </div>

        </div>
        <div class="fields disabled disabled-visible">
            <div class="four wide field">
                <div class="ui sub header">Tipo autorización:</div>
                <input type="text" value="<?= verificarEmpty($data['tipoAutorizacion']) ?>" readonly>
            </div>
            <div class="twelve wide field">
                <div class="ui sub header">Cod Cotizacion:</div>
                <input type="text" value="<?= verificarEmpty($data['codCotizacion']) ?>" readonly>
            </div>

        </div>
        <div class="fields disabled disabled-visible">
            <div class="sixteen wide field">
                <div class="ui sub header">Item:</div>
                <input type="text" value="<?= verificarEmpty($data['item']) ?>" readonly>
            </div>
        </div>
        <div class="fields disabled disabled-visible">
            <div class="sixteen wide field">
                <div class="ui sub header">Comentario:</div>
                <input type="text" value="<?= verificarEmpty($data['comentario']) ?>" readonly>
            </div>
        </div>
        <div class="fields disabled disabled-visible">
            <div class="sixteen wide field">
                <div class="ui sub header">Costo actual:</div>
                <input type="text" value="<?= !empty($data['costo']) ? moneda($data['costo']) : 'Sin costo' ?>" readonly>
            </div>
            <div class="sixteen wide field">
                <div class="ui sub header">
                    <?=!empty($data['costoAnterior']) ? 'Costo Anterior' : 'Nuevo costo'?> : 
                </div>
                <input type="text" value="<?=!empty($data['costoAnterior']) ? moneda($data['costoAnterior']) : moneda($data['nuevoValor'])?>" readonly>
            </div>
        </div>
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui slider checkbox p-1 <?=$data['idAutorizacionEstado'] != AUTH_ESTADO_PENDIENTE ? 'disabled disabled-visible' : ''?> ">
                    <input type="checkbox" name="autorizacion" <?=$data['idAutorizacionEstado'] == AUTH_ESTADO_ACEPTADO ? 'checked' : ''?> > 
                    <label>Aceptar y autorizar la solicitud</label>
                </div>
                <div class="inline field <?=$data['idAutorizacionEstado'] != AUTH_ESTADO_PENDIENTE ? 'd-none' : ''?> ">
                    <div class="ui pointing red basic label">
                        En caso de no marcar y actualizar la autorización, se tomará como un rechazo.
                    </div>
                </div>
            </div>
        </div>
        <div class="fields">
            <div class="sixteen wide field anexos">
                <div class="ui sub header">
                    Anexos
                    <div class="ui label button <?=$data['idAutorizacionEstado'] != AUTH_ESTADO_PENDIENTE ? 'd-none' : ''?>" onclick="$(this).parents('.anexos').find('.file-lsck-capturas-anexos').click();">
                        <i class="mail icon"></i> Agregar
                    </div>

                </div>
                <div class="ui small images content-lsck-capturas p-2">
                    <div class="content-lsck-galeria">

                        <input type="file" name="capturas" class="file-lsck-capturas-anexos form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*" multiple="">
                        <? foreach ($anexos as $anexo) { ?>
                            <div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idAutorizacion'] ?>">
                                <div class="ui dimmer dimmer-file-detalle">
                                    <div class="content">
                                        <p class="ui tiny inverted header"><?= $anexo['nombre_inicial'] ?></p>
                                    </div>
                                </div>
                                <a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                <input type="hidden" name="anexo-type" value="image/<?= $anexo['extension'] ?>">
                                <input type="hidden" name="anexo-name" value="<?= $anexo['nombre_inicial'] ?>">
                                <img height="100" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('ui.checkbox').checkbox();

    })
</script>