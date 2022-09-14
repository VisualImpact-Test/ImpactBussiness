<div style="text-align:justify"><br><br><br>
    <table>
        <tr>
            <td style="text-align: justify;height: 20px;">FECHA:</td>
            <td style="text-align: justify; height: 20px;"><?= ($cabecera['fecha']) ?></td>
            <td style="width: 100px;"></td>
            <td style="text-align: justify; height: 20px;">N° COTIZACIÓN:</td>
            <td style="text-align: justify; height: 20px;"><?= generarCorrelativo($cabecera['idCotizacion'], 4) ?></td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td style="text-align: justify;height:20px;">NOMBRE:</td>
            <td style="text-align: justify;height:20px;"><?= $cabecera['cotizacion'] ?></td>
            <td style="width: 100px;"></td>
            <td style="text-align: justify; height: 20px;">CUENTA:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['cuenta'] ?></td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; height: 20px;">CENTRO DE COSTO:</td>
            <td colspan="4" style="text-align: justify; height: 20px;"><?= $cabecera['cuentaCentroCosto'] ?></td>
        </tr>
    </table>
</div><br>
<table class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr>
            <th>ITEM</th>
            <th>DESCRIPCION</th>
            <th>CANTIDAD</th>
            <!-- <th>COSTO ACTUAL</th> -->
            <!-- <th>GAP</th> -->
            <th>PRECIO ACTUAL</th>
            <th>SUBTOTAL</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($detalle as $key => $row) { ?>
            <tr>
                <td style="text-align: center;"><?= $key + 1 ?></td>
                <td style="text-align: left;">
                    <?= verificarEmpty($row['item'], 3) ?>
                    <? if (!empty($row['caracteristicas'])) { ?>
                        <p>
                            <?= $row['caracteristicas'] ?>
                        </p>
                    <? } ?>
                </td>
                <td style="text-align: right;"><?= verificarEmpty($row['cantidad'], 3) ?></td>
                <!-- <td style="text-align: right;"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td> -->
                <!-- <td style="text-align: left;"><?= verificarEmpty($row['gap'], 2) . '%' ?></td> -->
                <td style="text-align: right;"><?= empty($row['precio']) ? moneda(verificarEmpty($row['costo'], 2)) : moneda($row['precio']); ?></td>
                <td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>

            </tr>
        <? } ?>
        <?= completarFilasPdf(['data' => $detalle, 'filas' => 10, 'columnas' => 5]) ?>
    </tbody>
    <tfoot class="full-widtd">
        <tr class="height:100px">
            <td colspan="4" class="text-right">
                <p>SUB TOTAL</p>
                <p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '' ?></p>
                <? if (!empty($cabecera['igv'])) { ?>
                    <p>IGV</p>
                <? } ?>
                <p>TOTAL GENERAL <?= empty($cabecera['igv']) ? '(No incluye igv)' : '' ?></p>
            </td>
            <td class="text-right">
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
    <? foreach ($anexos as $anexo) { ?>
        <div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?>">
            <img height="100" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
        </div>
    <? } ?>

<? } ?>