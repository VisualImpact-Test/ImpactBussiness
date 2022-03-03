<div class="card-datatable">
    <table id="tb-item" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>TIPO</th>
                <th>MARCA</th>
                <th>CATEGORIA</th>
                <th>ITEM</th>
                <th>EQUIVALENTE EN LOGISTICA</th>
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
                <tr data-id="<?= $row['idItem'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarItem"><i class="fa fa-lg fa-edit" title="Actualizar Item"></i></a>
                        <a id="hrefEstado-<?= $row['idItem']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoItem" data-id="<?= $row['idItem']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['tipoItem'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemMarca'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemCategoria'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['item'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['equivalenteLogistica'], 3); ?></td>
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idItem']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>