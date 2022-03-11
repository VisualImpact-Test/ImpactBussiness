<div class="card-datatable">
    <table id="tb-historialItemTarifario" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th>FECHA DE INICIO</th>
                <th>FECHA DE FIN</th>
                <th>COSTO</th>
                <th>PROVEEDOR</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($datos as $key => $row) {
            ?>
                <tr data-id="<?= $row['idItemTarifario'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-left"><?= verificarEmpty($row['fecIni'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['fecFin'], 3); ?></td>
                    <td class="text-right"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['proveedor'], 3); ?></td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>