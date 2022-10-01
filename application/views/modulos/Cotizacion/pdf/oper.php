<?
$filas = 10;
?>

<div style="text-align:justify">
    <table border="1" style="width: 100%; float: left;">
        <tr>
            <td class="text-left w-20">Dirigido a: </td>
            <td class="text-left w-40"><?=verificarEmpty($dataOper['usuarioReceptor'],3)?></td>
            <td class="text-left w-20">N° Requerimiento: </td>
            <td class="text-center w-20"><?=verificarEmpty($dataOper['requerimiento'],3)?></td>
        </tr>
        <tr>
            <td class="text-left w-20">De:</td>
            <td class="text-left w-40"><?=verificarEmpty($dataOper['usuarioRegistro'],3)?></td>
            <td class="text-left w-20">OC del Cliente:</td>
            <td class="text-center w-20"><?=verificarEmpty($dataOper['ordenCompra'],3)?></td>
        </tr>
        <tr>
            <td class="text-left">Concepto PO / CR Cliente:</td>
            <td class="text-left" colspan="3"><?=verificarEmpty($dataOper['concepto'],3)?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Unidad de Negocio</td>
            <td class="text-left w-40"><?=verificarEmpty($dataOper['cuentas'],3)?></td>
            <td class="text-left w-20">Centro de Costo:</td>
            <td class="text-center w-20"><?=verificarEmpty($dataOper['centrosCosto'],3)?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Fecha de requerimiento</td>
            <td class="text-center w-40"><?=verificarEmpty($dataOper['fechaReg'],3)?></td>
            <td class="text-left w-20">Probable fecha de entrega</td>
            <td class="text-center w-20"><?=verificarEmpty($dataOper['fechaEntrega'],3)?></td>
        </tr>
    </table>
</div>
<br>
<table border="1" class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr>
            <th class="text-center">ITEM</th>
            <th class="text-center">DESCRIPCION</th>
            <th class="text-center">CANTIDAD</th>
            <th class="text-center">COSTO UNIT</th>
            <th class="text-center">TOTAL PROVEEDOR <br> SIN IGV</th>
            <th class="text-center">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($cotizacionDetalle as $key => $row) { ?>
            <tr>
                <td style="text-align: center;"><?= ($key + 1) ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['item'], 3) ?></td>
                <td style="text-align: center;"><?= verificarEmpty($row['cantidad'], 3) ?></td>
                <td style="text-align: right;"><?= empty($row['precio']) ? "-" : moneda($row['precio']); ?></td>
                <td style="text-align: right;"><?= !empty($row['subtotal']) ? moneda($row['subtotal']) : '-' ?></td>
                <td style="text-align: center;"><?= !empty($row['observaciones']) ? moneda($row['observaciones']) : '-' ?></td>
            </tr>
        <? } ?>
        <!-- Enviamos el arreglo que estamos recorriendo, el numero de FILAS que requerimos, 
        y el número de COLUMNAS con que estamos trabajando
         -->
        <?= completarFilasPdf(['data' => $cotizacionDetalle, 'filas' => $filas, 'columnas' => 6]) ?>
    </tbody>
</table>

<p style="width: 100%; margin-bottom: 100px;">
    Del ítem 1 al ítem <?=$filas?>
</p>
<table style="border:none;width: 100%;">
    <tr>
        <td class="w-30 text-center">
            <div style="text-align:center; ">
                <hr style="height: 3px; color:black">
                Nombre y firma del solicitante
            </div>
        </td>
        <td class="w-40">
        </td>
        <td class="w-30 text-center">
            <div style="text-align:center">
                <hr style="height: 3px; color:black; ">
                Nombre y firma del Jefe Directo
            </div>
        </td>
    </tr>
</table>