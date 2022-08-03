<br><br><br>
<div style="text-align:justify">
    <table border="1" style="width: 100%; float: left;">
        <tr>
            <td class="text-left w-40">RUC: 0000000000 </td>
            <td class="text-left w-20">N° DE RQ: </td>
            <td class="text-left w-20"><?= verificarEmpty($data['requerimiento'], 3) ?></td>
            <td class="text-left w-40">N° DE ORDEN: <span style="margin-left: 50px"> <?= generarCorrelativo($data['idOrdenCompra'], 6) ?></span></td>
        </tr>
        <tr>
            <td class="text-left w-20">Unidad de Negocio</td>
            <td class="text-left w-40"><?= verificarEmpty($data['cuentas'], 3) ?></td>
            <td class="text-left w-20">Cotizacion:</td>
            <td class="text-center w-20"><?= verificarEmpty($data['cotizaciones'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Centro de Costo:</td>
            <td class="text-center w-20"><?= verificarEmpty($data['centrosCosto'], 3) ?></td>
            <td class="text-left w-20">Fecha:</td>
            <td class="text-center w-20"><?= verificarEmpty($data['fechaRequerida'], 3) ?></td>
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
        <tdead class="full-widtd">
            <tr>
                <td class="text-center">Item</td>
                <td class="text-center">Cantidad</td>
                <td class="text-center" colspan="2">Descripción</td>
                <td class="text-center">Precio Unit.</td>
                <td class="text-center">Precio Total</td>
            </tr>
        </tdead>
        <tbody>
            <? foreach ($detalle as $k => $row) {
                $total = $row['subTotalOrdenCompra'];
                $igv_total = ($row['subTotalOrdenCompra'] * IGV);
            ?>
                <tr>
                    <td class="text-center"><?= ($k + 1) ?>

                        <input type="hidden" name="idCotizacion" value="<?= $row['idCotizacion'] ?>">
                    </td>
                    <td class="text-center"><?= verificarEmpty($row['cantidad'], 2) ?></td>
                    <td class="text-left" colspan="2"><?= verificarEmpty($row['nombre'], 3) ?></td>
                    <td class="text-right">
                        <?= !empty($row['precio']) ? moneda($row['precio']) : 0 ?>
                    </td>
                    <td class="text-right">
                        <?= !empty($row['subtotal']) ? moneda($row['subtotal']) : 0 ?>
                    </td>
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
                    <p><?= (IGV * 100) . "%" ?></p>
                </td>
                <td class="text-right">
                    <p><?= moneda($total) ?></p>
                    <p><?= moneda($igv_total) ?></p>
                    <p><?= moneda($igv_total + $total)  ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-left">
                    Son: <?= moneyToText(['numero' => ($igv_total + $total)]) ?>
                </td>
            </tr>
            <tr style="border-bottom: none;">
                <td colspan="2">
                    <strong>Forma de Pago</strong>
                </td>
                <td>
                    <strong>
                        90 Días
                    </strong>
                </td>
                <td>
                    <strong>
                        Observaciones
                    </strong>
                </td>
                <td colspan="2">
                    <strong>
                        Visual Impact
                    </strong>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="height: 50px;"></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<div style="border: 2px solid black; text-align:justify;height:100px">
    <table style="border:none;width: 100%;margin-top:100px">
        <tr>
            <td class="w-30 text-center">
                <div style="text-align:center; ">
                    <hr style="height: 3px; color:black">
                    Área de logística
                </div>
            </td>
            <td class="w-5">
            </td>
            <td class="w-30 text-center">
                <div style="text-align:center">
                    <hr style="height: 3px; color:black; ">
                    Gerencia de administración
                </div>
            </td>
            <td class="w-5">
            </td>
            <td class="w-30 text-center">
                <div style="text-align:center">
                    <hr style="height: 3px; color:black; ">
                    Gerencia General
                </div>
            </td>
        </tr>
    </table>
</div>