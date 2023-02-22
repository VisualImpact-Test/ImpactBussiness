<div class="card-datatable">
    <table id="tb-item" class="ui celled table" width="100%">
        <thead>

            <tr>
                <th class="td-center text-center">#</th>
                <th class="td-center text-center">OPCIONES</th>
                <th class="text-center">MARCA</th>
                <th class="text-center">CATEGORIA</th>
                <th class="text-center">SUBCATEGORIA</th>
                <th class="text-center">ITEM</th>
                <th class="text-center">CARACTERISTICAS</th>
                <?
                foreach ($dataProveedor as $key => $row) {

                ?>
                    <th nowrap class="text-center"><?= $row['nproveedor']; ?></th>
                <?
                } ?>

                <th class="td-center text-center">ESTADO</th>
            </tr>

        </thead>

        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($dataItem as $key => $row) {
                $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
                $badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
                $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
                <tr data-id="<?= $row['idItemTarifario'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td nowrap class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-historialItemTarifario"><i class="fa fa-lg fa-history" title="Historial de Tarifario"></i></a>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarItemTarifario"><i class="fa fa-lg fa-edit" title="Actualizar Tarifario de Item"></i></a>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-fotosItemTarifario"><i class="fa fa-lg fa-image" title="Ver fotos de Tarifario de Item"></i></a>
                        <a id="hrefEstado-<?= $row['idItemTarifario']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoItem" data-id="<?= $row['idItem']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['itemMarca'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemCategoria'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemSubCategoria'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['item']); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['caracteristicas'], 3); ?></td>
                    <?php $idProveedor = 0; ?>
                    <?
                    foreach ($dataProveedor as $key => $rProveedor) {

                    ?>
                        <?php $idProveedor = (!empty($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]) ? $rProveedor['idProveedor'] : $row['idProveedor']) ?>
                        <?php $flag = !empty($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['flag_actual']) ? $dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['flag_actual'] : 0; ?>
                        <? if ($rProveedor['idProveedor'] == $idProveedor && $flag == '1') { ?>
                            <th nowrap class="text-center" style="color: green">

                                <?= isset($dataItemProveedor[$row['idItem']][$idProveedor]['costo']) ? $dataItemProveedor[$row['idItem']][$idProveedor]['costo'] : '-'; ?>
                                <br>
                                <br>
                                <?= isset($dataItemProveedor[$row['idItem']][$idProveedor]['fechaVigencia']) ?  date_change_format($dataItemProveedor[$row['idItem']][$idProveedor]['fechaVigencia']) : '-'; ?>

                            </th>

                        <? } else { ?>

                            <th nowrap class="text-center" style="color: red">
                                <?= isset($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['costo']) ? $dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['costo'] : '-'; ?>
                                <br>
                                <br>
                                <?= isset($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['fechaVigencia']) ?  date_change_format($dataItemProveedor[$row['idItem']][$rProveedor['idProveedor']]['fechaVigencia']) : '-'; ?>

                            </th>
                        <? } ?>
                    <?
                    } ?>

                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idItemTarifario']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>