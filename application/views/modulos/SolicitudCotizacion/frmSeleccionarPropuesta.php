<table class="ui selectable celled table w-100">
    <thead>
        <tr>
            <th class="two wide">Proveedor</th>
            <th class="three wide">Descripción</th>
            <th class="four wide">Motivo</th>
            <th class="three wide">Cotización</th>
            <th class="four wide">Adjuntos</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($cotizacionPropuesta as $cp) { ?>
            <tr class=" rowPropuesta" data-idcotizaciondetalle="<?=$cp['idCotizacionDetalle']?>" data-id="<?=$cp['idPropuestaItem']?>">
                <input class="jsonPropuesta" type="hidden" value='<?= json_encode($cp)?>'>
                <td><?= !empty($cp['proveedor']) ? $cp['proveedor'] : '' ?></td>
                <td><?= !empty($cp['nombre']) ? $cp['nombre'] : '' ?></td>
                <td><?= !empty($cp['motivo']) ? $cp['motivo'] : '' ?></td>
                <td>
                    <p style="display: inline-flex;">Cantidad: <strong><?= !empty($cp['cantidad']) ? $cp['cantidad'] : '' ?></strong></p> <br>
                    <p style="display: inline-flex;">Costo: <strong><?= !empty($cp['costo']) ? moneda($cp['costo']) : '' ?></strong></p> <br>
                    <p style="display: inline-flex;">Subtotal: <strong><?= !empty($cp['subtotal']) ? moneda($cp['subtotal']) : '' ?></strong></p>
                </td>
                <!-- <td><?= !empty($cp['observacion']) ? moneda($cp['observacion']) : '' ?></td> -->
                <td>
                    <? if (!empty($cotizacionPropuestaArchivos[$cp['idPropuestaItem']])) : ?>
                        <div class="ui relaxed divided list">
                            <? foreach ($cotizacionPropuestaArchivos[$cp['idPropuestaItem']] as $cpp) { ?>
                                <div class="item">
                                    <i class="large image icon middle aligned"></i>
                                    <div class="content">
                                        <a target="_blank" class="header" href="<?= !empty($cpp['archivoWasabi']) ? URL_WASABI_ITEM_PROPUESTA.$cpp['archivoWasabi'] : ''?>" ><?=!empty($cpp['archivo']) ? $cpp['archivo'] : ''?></a>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    <? endif; ?>

                </td>
            </tr>
        <? } ?>

    </tbody>
</table>