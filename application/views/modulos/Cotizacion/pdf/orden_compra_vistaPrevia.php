<div style="text-align:justify">
    <table border="1" style="width: 100%; float: left;">
        <tr>
            <td class="text-left w-40">RUC: <?= RUC_VISUAL ?> </td>
            <td class="text-left w-20">N° DE RQ: </td>
            <td class="text-left w-20"><?= verificarEmpty($data['requerimiento'], 3) ?></td>
            <td class="text-left w-40">N° DE ORDEN: <span style="margin-left: 50px"> OCXXXXXX</span></td>
        </tr>
        <tr>
            <td class="text-left w-20">Unidad de Negocio</td>
            <td class="text-left w-40"><?= verificarEmpty($cuentas, 3) ?></td>
            <td class="text-left w-20">PO Cliente:</td>
            <td class="text-center w-20"><?= verificarEmpty($data['pocliente'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left" colspan="4">Datos del proveedor:</td>
        </tr>
        <tr>
            <td class="text-left w-20">Srs.</td>
            <td class="text-left w-40"><?= verificarEmpty($data['razonSocial'], 3) ?></td>
            <td class="text-left w-20">RUC:</td>
            <td class="text-left w-20"><?= verificarEmpty($data['rucProveedor'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Atención</td>
            <td class="text-left w-40"><?= verificarEmpty($data['nombreContacto'], 3) ?></td>
            <td class="text-left w-20">Telefono fijo</td>
            <td class="text-left w-20"><?= '-' ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Dirección</td>
            <td class="text-left w-40"><?= verificarEmpty($data['direccion'], 3) ?></td>
            <td class="text-left w-20">Celular</td>
            <td class="text-left w-20"><?= verificarEmpty($data['numeroContacto'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Email</td>
            <td class="text-left w-40"><?= verificarEmpty($data['correoContacto'], 3) ?></td>
            <td class="text-left w-20">Fecha entrega</td>
            <td class="text-left w-20"><?= verificarEmpty($data['fechaEntrega'], 3) ?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%;">
        <thead class="full-widtd" style="border:1px solid black;">
            <tr>
                <td class="text-center">Item</td>
                <td class="text-center">Cantidad</td>
                <td class="text-center" colspan="2">Descripción</td>
                <td class="text-center">Precio Unit.</td>
                <td class="text-center">Precio Total</td>
            </tr>
        </thead>
        <tbody style="border:1px solid black;">
            <? foreach ($detalle as $k => $row) {
                $total = (($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra']);
                $igv_total = (((($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra'])) * (!empty($data['igv']) ? ($data['igv'] / 100) : 0 /*IGV */));
            ?>
                <tr style="border-bottom: none;">
                    <td class="text-center"><?= ($k + 1) ?>
                        <input type="hidden" name="idCotizacion" value="<?= $row['idCotizacion'] ?>">
                    </td>
                    <td class="text-center"><?= verificarEmpty($row['cantidad'], 2) ?></td>
                    <td class="text-left" colspan="2"><?= verificarEmpty($row['nombre'], 3) ?></td>
                    <td class="text-right">
                        <?= !empty($row['costo']) ? monedaNew(['valor' => $row['costo'], 'simbolo' => $data['simboloMoneda']]) : 0 ?>
                    </td>
                    <td class="text-right">
                        <?= !empty($row['subTotalOrdenCompra']) ? monedaNew(['valor' => (($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra']), 'simbolo' => $data['simboloMoneda']]) : 0 ?>
                    </td>
                </tr>
            <? } ?>
            <tr>
                <td><?= generar_espacios(1, 1) ?></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><?= generar_espacios(1, 1) ?></td>
                <td></td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
            </tr>
            <? if (!empty($data['entrega'])) { ?>
                <tr>
                    <td><?= generar_espacios(1, 1) ?></td>
                    <td></td>
                    <td colspan="2" style="font-weight: bold;">
                        <?= !empty($data['entrega']) ? "Entrega: {$data['entrega']}" : '' ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            <? } ?>
            <? if (!empty($data['observacion'])) { ?>
                <tr>
                    <td><?= generar_espacios(1, 1) ?></td>
                    <td></td>
                    <td colspan="2" style="font-weight: bold;">
                        <? if($data['mostrar_observacion']==1){ ?>
                        <?= !empty($data['observacion']) ? "Observación: {$data['observacion']}" : '' ?>
                        <? } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            <? } ?>

        </tbody>
        <tfoot class="full-widtd">
            <tr class="height:100px">
                <td colspan="4" class="text-right">
                    <p>Sub Total</p>
                    <p>IGV</p>
                    <p>TOTAL</p>
                </td>
                <td class="text-center">
                    <p><?= !empty($data['igv']) ? $data['igv'] : (IGV * 100) ?>%</p>
                </td>
                <td class="text-right">
                    <p><?= monedaNew(['valor' => $total, 'simbolo' => $data['simboloMoneda']]) ?></p>
                    <p><?= empty($igv_total) ? 'S/ 0.00' : (monedaNew(['valor' => $igv_total, 'simbolo' => $data['simboloMoneda']])) ?></p>
                    <p><?= monedaNew(['valor' => $igv_total + $total, 'simbolo' => $data['simboloMoneda']])  ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-left">
                    Son: <?= moneyToText(['numero' => ($igv_total + $total), 'moneda' => $data['monedaPlural']]) ?>
                </td>
            </tr>
            <? if (!empty($data['comentario'])) : ?>
                <tr>
                    <td colspan="6" class="text-left">
                        <?= !empty($data['comentario']) ? $data['comentario'] : '' ?>
                    </td>
                </tr>
            <? endif; ?>
            <tr style="border-bottom: none;">
                <td colspan="2" style="border-bottom: none;">
                    <strong>Forma de Pago</strong>
                </td>
                <td style="border-bottom: none;">
                    <strong>
                        <?= !empty($data['metodoPago']) ? $data['metodoPago'] : '' ?>
                    </strong>
                </td>
                <td style="border-bottom: none;">
                    <strong>
                        Observaciones
                    </strong>
                </td>
                <td colspan="2" style="border-bottom: none;">
                    <strong>
                        <?= !empty($data['pocliente']) ? $data['pocliente'] : '' ?>
                    </strong>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="height: 50px;border-top: none;"></td>
                <td style="height:50px;border-top: none;"></td>
                <td style="height:50px;border-top: none;"></td>
                <td colspan="2" style="height: 50px;border-top: none;"></td>
            </tr>
        </tfoot>
    </table>
</div>

<div style="border: 2px solid black; text-align:justify;height:100px">
    <table style="border:none !important;width: 100%; margin-top:30px">
        <tr>
            <td class="w-10">
            </td>
            <td class="w-30 text-center" style="padding-top:120px;">
                <div style="text-align:center;">
                    <hr style="height: 3px; color:black">
                    Área de logística
                </div>
            </td>
            <td class="w-20 text-center" style="padding-top:120px;">
            </td>
            <td class="w-30 text-center" style="padding-top:120px">
                <?php if (!empty($data['nombre_archivo'])) : ?>
                    <img id="imagenFirma" src="<?= empty($data['nombre_archivo']) ? '' : (RUTA_WASABI . 'usuarioFirma/' . $data['nombre_archivo']) ?>" style="padding-top: -120px; width: 200px; height: 120px;">
                <?php endif; ?>
                <div style="text-align:center">
                    <hr style="height: 3px; color:black; ">
                    Coordinador de compras
                </div>
            </td>
            <td class="w-10">
            </td>
        </tr>
    </table>
</div>