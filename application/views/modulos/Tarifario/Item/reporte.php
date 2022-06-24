<div class="card-datatable" >
    <table id="tb-item" class="ui celled table" width="100%">
        <thead>
        
            <tr>
                <th class="td-center text-center">#</th>
                <th class="td-center text-center">OPCIONES</th>
                <th class="text-center">TIPO</th>
                <th class="text-center">MARCA</th>
                <th class="text-center">CATEGORIA</th>
                <th class="text-center">ITEM</th>
                <?
            foreach ($dataProveedor as $key => $row) { 
                
                ?>
                <th nowrap class="text-center"><?= $row['nproveedor']; ?></th>
                <? 
            } ?>
                
                <th class="text-center">COSTO</th>
                <th class="text-center">VIGENCIA</th>
                <th class="td-center text-center">ESTADO</th>
            </tr>
      
        </thead>
    
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($dataTarifario as $key => $row) {
                $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
                $badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
                $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
                <tr data-id="<?= $row['idItemTarifario'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td nowrap class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-historialItemTarifario"><i class="fa fa-lg fa-history" title="Historial de Tarifario"></i></a>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarItemTarifario"><i class="fa fa-lg fa-edit" title="Actualizar Tarifario de Item"></i></a>
                        <a id="hrefEstado-<?= $row['idItemTarifario']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoItem" data-id="<?= $row['idItem']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['itemTipo'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemMarca'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['itemCategoria'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['item']); ?></td>
                    <?
            foreach ($dataProveedor as $key => $flag) { 
                
                ?>
                    <td class="td-center">
                        <?= ($flag['idProveedor'] == $row['idProveedor'] && $row['flag_actual'] == 1 ) ? '<i class="fa fa-lg fa-check" style="color: green;"></i>' : '' ?>
                        <?= ($flag['idProveedor'] == $row['idProveedor'] && $row['flag_actual'] == 0 ) ? '<i class="fas fa-lg fa-question" style="color: red;"></i>' : '' ?>
                    </td>

                    
                    
                    <? 
            } ?>
                    <td nowrap class="text-right"><?= empty($row['costo']) ? "-" : moneda($row['costo']); ?></td>
                    <td class="text-center"><?= empty($row['fechaVigencia']) ? "-" : date_change_format($row['fechaVigencia']); ?></td>
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idItemTarifario']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>