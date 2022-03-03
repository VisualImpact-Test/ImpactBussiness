<div style="text-align:justify"><br><br><br>
    <table>
        <tr>
            <td style="text-align: justify;height: 20px;">Fecha:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['fecha'] ?></td>
            <td style="width: 100px;"></td>
            <td style="text-align: justify; height: 20px;">N° Presupuesto:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['idPresupuesto'] ?></td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td style="text-align: justify;height:20px;">Nombre:</td>
            <td style="text-align: justify;height:20px;"><?= $cabecera['presupuesto'] ?></td>
            <td style="width: 100px;"></td>
            <td style="text-align: justify;height: 20px;">Tipo de Presupuesto:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['tipoPresupuesto'] ?></td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td style="text-align: justify; height: 20px;">Cuenta:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['cuenta'] ?></td>
            <td style="width: 100px;"></td>
            <td style="text-align: justify; height: 20px;">Centro de Costo:</td>
            <td style="text-align: justify; height: 20px;"><?= $cabecera['cuentaCentroCosto'] ?></td>
        </tr>
    </table>
</div><br>
<table class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Cantidad</th>
            <th>Costo Actual</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($detalle as $key => $row) { ?>
            <tr>
                <td style="text-align: left;"><?= $key + 1 ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['item'], 3) ?></td>
                <td style="text-align: right;"><?= verificarEmpty($row['cantidad'], 3) ?></td>
                <td style="text-align: right;"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                <td style="text-align: left;"><?= verificarEmpty($row['estadoItem'], 3) ?></td>
            </tr>
        <? } ?>
    </tbody>
</table>