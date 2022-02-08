<div class="card-datatable">
    <table id="tb-servicio" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Opciones</th>
                <th>Tipo</th>
                <th>Item</th>
                <th>Proveedor</th>
                <th>Costo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?
            foreach ($datos as $key => $row) {
                $badge = $row['tarifa_servicio_estado'] == 'Activo' ? 'badge-success' : 'badge-danger';
                $toggle = $row['tarifa_servicio_estado'] == 'Activo' ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
                <tr data-id="<?= $row['idServicio'] ?>">
                    <td class="td-center"><?= $row['num_fila'] ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarServicio"><i class="fa fa-lg fa-edit" title="Actualizar Servicio"></i></a>
                        <a id="hrefEstado-<?= $row['idServicio']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoServicio" data-id="<?= $row['idServicio']; ?>" data-estado="<?= $row['tarifa_servicio_estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['tipo_servicio_nombre'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['servico_nombre'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['proveedor_nombre'], 3); ?></td>
                    <td class="td-left"><?= empty($row['tarifa_servicio_costo']) ? "-" : moneda($row['tarifa_servicio_costo']); ?></td>
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idServicio']; ?>">
                            <?= $row['tarifa_servicio_estado']; ?>
                        </span>
                    </td>
                </tr>
            <? } ?>
        </tbody>
    </table>
</div>