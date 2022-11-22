
<div style="text-align:justify">
    <table>
        <tr>
            <td style="text-align: justify;height:20px;"><?= $cabecera['cotizacion'] ?></td>
        </tr>
        <tr>
            <td style="text-align: justify;height: 20px;">Fecha: <?= ($cabecera['fecha']) ?></td>
        </tr>
        <!-- <tr>
            <td style="width: 100px;"></td>
            <td style="text-align: justify; height: 20px;">CUENTA:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['cuenta'] ?></td>
            <td style="width: 100px;"></td>
        </tr> -->
        <!-- <tr>
            <td colspan="2" style="text-align: left; height: 20px;">CENTRO DE COSTO:</td>
            <td colspan="4" style="text-align: justify; height: 20px;"><?= $cabecera['cuentaCentroCosto'] ?></td>
        </tr> -->
        <tr>
            <td style="text-align: justify;height: 20px;">Elaborado: Area de Operaciones</td>
        </tr>
        <tr>
            <td style="text-align: justify;height: 20px;">RUC: <?= RUC_VISUAL ?></td>
        </tr>
        <? if (empty($cabecera['igv'])) { ?>
            <tr>
                <td style="text-align: justify;height: 20px;">No Incluye IGV</td>
            </tr>
        <? } ?>
    </table>
</div><br>
<table class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr style="background-color: #222c33;">
            <!-- <th style="color:white">ITEM</th> -->
            <th style="color:white">DESCRIPCION</th>
            <th style="color:white">CANTIDAD</th>
            <? if (!empty($cabecera['mostrarPrecio'])) { ?>
                <th style="color:white">PRECIO</th>
            <? } ?>
            <th style="color:white">SUBTOTAL</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($detalle as $key => $row) { ?>
            <tr style="background-color: #9db7c9;">
                <!-- <td style="text-align: center;"><?= $key + 1 ?></td> -->
                <td style="text-align: left;">
                    <?= verificarEmpty($row['item'], 3) ?>
                    <? if (!empty($row['caracteristicas'])) { ?>
                        <p>
                            <?= $row['caracteristicas'] ?>
                        </p>
                    <? } ?>

                </td>
                <td style="text-align: right;"><?= verificarEmpty($row['cantidad'], 3) ?></td>
                <? if (!empty($cabecera['mostrarPrecio'])) { ?>
                    <td style="text-align: right;"><?= empty($row['precio']) ? moneda(verificarEmpty($row['costo'], 2)) : moneda($row['precio']); ?></td>
                <? } ?>

                <td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>

            </tr>
            <? if (!empty($row['caracteristicas'])) { ?>
                <tr>
                    <td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "4" : "3" ?>">
                        <p>
                            <?= $row['caracteristicas'] ?>
                        </p>
                    </td>
                </tr>
            <? } ?>
            <tr>
                <td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "4" : "3" ?>">
                    <? if (!empty($archivos[$row['idCotizacionDetalle']])) { ?>
                        <div class="ui fluid image content-lsck-capturas" style="display: inline-block;">
                            <? foreach ($archivos[$row['idCotizacionDetalle']] as $archivo) { ?>
                                <? if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) { ?>
                                    <a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>">
                                        <img height="100" src="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>">
                                    </a>
                                <? } ?>
                            <? } ?>
                        </div>
                    <? } ?>
                </td>
            </tr>
        <? } ?>

        <? for ($i = 0; $i <= 2; $i++) { ?>
            <tr>
                <td></td>
                <td></td>
                <? if (!empty($cabecera['mostrarPrecio'])) { ?>
                    <td></td>
                <? } ?>

                <td></td>
            </tr>
        <? } ?>

    </tbody>
    <tfoot class="full-widtd">
        <tr class="height:100px" style="background-color: #222c33;">
            <td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "3" : "2" ?>" class="text-right" style="color:white">
                <p>SUB TOTAL</p>
                <p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '' ?></p>
                <? if (!empty($cabecera['igv'])) { ?>
                    <p>IGV</p>
                <? } ?>
                <p>TOTAL GENERAL <?= empty($cabecera['igv']) ? '(No incluye igv)' : '' ?></p>
            </td>
            <td class="text-right" style="color:white">
                <p><?= moneda($cabecera['total']) ?></p>
                <p><?= moneda(($cabecera['fee_prc'])) ?></p>
                <? if (!empty($cabecera['igv'])) { ?>
                    <p><?= moneda($cabecera['igv_prc']) ?></p>
                <? } ?>
                <p><?= moneda($cabecera['total_fee_igv'])  ?></p>
            </td>
        </tr>
    </tfoot>
</table>

<? if (!empty($anexos)) { ?>
    <h3>Anexos</h3>
    <div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?> " style="display: inline-block;">
        <? foreach ($anexos as $anexo) { ?>
            <a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>">
                <img height="100" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
            </a>
        <? } ?>
    </div>
<? } ?>