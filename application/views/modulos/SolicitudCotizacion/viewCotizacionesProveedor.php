<table class="ui compact celled definition table">
    <thead class="full-width">
        <tr>
            <th></th>
            <th>Item</th>
            <th>Proveedor</th>
            <th>Cantidad</th>
            <th>Costo</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($detalle as $k => $row) { ?>
            <tr>
                <td class="collapsing text-center">
                    <div class="ui fitted  checkbox">
                        <input type="checkbox">
                        <label></label>
                    </div>
                </td>
                <td><?= verificarEmpty($row['item'],2)?></td>
                <td><?= verificarEmpty($row['proveedor'],2)?></td>
                <td><?= verificarEmpty($row['cantidad'],3)?></td>
                <td><?= verificarEmpty($row['costo'],3)?></td>
            </tr>
        <? } ?>
    </tbody>
</table>