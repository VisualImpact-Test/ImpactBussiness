<?
$filas = 10;
?>

<br>
<table border="1" class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">ITEM</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($items as $key => $row) { ?>
            <tr>
                <td style="text-align: center;"><?= ($key + 1) ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['nombre'], 3) ?></td>
            </tr>
        <? } ?>
    </tbody>
</table>