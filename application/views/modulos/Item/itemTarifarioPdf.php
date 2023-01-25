<?
$filas = 10;
?>

<br>
<table border="1" class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">ITEM</th>
            <th class="text-center">PROVEEDOR</th>
            <th class="text-center">COSTO UNIT</th>
            <th class="text-center">FECHA VIGENCIA</th>
            <th class="text-center">DIAS RESTANTES</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($itemTarifario as $key => $row) { ?>
            <tr>
                <td style="text-align: center;"><?= ($key + 1) ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['item'], 3) ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['proveedor'], 3) ?></td>
                <td style="text-align: right;"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                <td style="text-align: center;"><?= verificarEmpty($row['fechaVigencia'], 3) ?></td>
                <td style="text-align: center;"><?= verificarEmpty($row['diasRestantes'], 3) ?></td>
            </tr>
        <? } ?>
    </tbody>
</table>