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
                <tr data-id="<?= $row['idArticulo'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarArticulo"><i class="fa fa-lg fa-edit" title="Actualizar Articulo"></i></a>
                        <a id="hrefEstado-<?= $row['idArticulo']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoArticulo" data-id="<?= $row['idArticulo']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['tipoArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['marcaArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['categoriaArticulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['articulo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['equivalenteLogistica'], 3); ?></td>
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idArticulo']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>