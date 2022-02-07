<div class="card-datatable">
    <table id="tb-articulo" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>TIPO</th>
                <th>MARCA</th>
                <th>CATEGORIA</th>
                <th>ITEM</th>
                <th>PROVEEDOR</th>
                <th>COSTO</th>
                <th class="td-center">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($datos as $key => $row) {
                $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
                $badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
                $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
                <tr data-id="<?= $row['idTarifarioArticulo'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-historialTarifarioArticulo"><i class="fa fa-lg fa-history" title="Historial de Tarifario"></i></a>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarTarifarioArticulo"><i class="fa fa-lg fa-edit" title="Actualizar Tarifario de Articulo"></i></a>
                        <a id="hrefEstado-<?= $row['idTarifarioArticulo']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoArticulo" data-id="<?= $row['idArticulo']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['tipoArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['marcaArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['categoriaArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['articulo'], 3); ?></td>
                    <td>
                        <div class="text-left" style="width:90%; display: inline-block;">
                            <?= verificarEmpty($row['proveedor'], 3); ?>
                        </div>
                        <div class="text-right" style="width:10%; display: inline-block;">
                            <?= $row['flag_actual'] == 1 ? '<i class="fas fa-lg fa-circle" style="color: royalblue;"></i>' : '' ?>
                        </div>
                    </td>
                    <td class="text-right"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                    <td class="text-right style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idTarifarioArticulo']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>