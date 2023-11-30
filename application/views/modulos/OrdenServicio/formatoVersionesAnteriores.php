<div class="ui table">
    <table class="ui celled table" id="tablaMovilidad" data-personal="">
        <thead>
            <tr>
                <th class="one wide">Version</th>
                <th class="six wide">Nombre</th>
                <th class="five wide">Cuenta - Centro de costo / Usuario</th>

                <th class="two wide">Fecha</th>
                <th class="two wide">Total</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($versionesAnteriores as $key => $row) : ?>
                <tr class="data">
                    <td><?= verificarEmpty($row['versionPresupuesto'], 3); ?></td>
                    <td><?= verificarEmpty($row['nombreOrdenServicio'], 3); ?></td>
                    <?php if($row['chkUtilizarCliente'] == 1){ ?>
                    <td><?= verificarEmpty($row['nombreCliente'], 3); ?> </td>
                    <?php }else{ ?>
                    <td><?= verificarEmpty($row['nombreCuenta'], 3); ?> - <?= verificarEmpty($row['centroCosto'], 3); ?></td>
                    <?php } ?>
                   
                    <td><?= verificarEmpty($row['Fecha'], 3); ?></td>
                    <td><?= verificarEmpty($row['total'], 3); ?></td>
                   
                </tr>   
            <?php endforeach; ?>
        </tbody>
        
</table>

</div>