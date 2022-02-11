<div class="card-datatable">
    <table id="tb-presupuesto" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>FECHA</th>
                <th>NOMBRE</th>
                <th>TIPO PRESUPUESTO</th>
                <th>CUENTA</th>
                <th>CENTRO COSTO</th>
                <th>NRO PRESUPUESTO</th>
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
                <tr data-id="<?= $row['idPresupuesto'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-presupuesto-pdf"><i class="fa fa-lg fa-file-pdf" title="Generar PDF"></i></a>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detallePresupuesto"><i class="fa fa-lg fa-bars" title="Ver Detalle de Presupuesto"></i></a>
                        <a id="hrefEstado-<?= $row['idPresupuesto']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoPresupuesto" data-id="<?= $row['idPresupuesto']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['fecha'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['presupuesto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['tipoPresupuesto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuenta'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentaCentroCosto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['nroPresupuesto'], 3); ?></td>
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idPresupuesto']; ?>"><?= $mensajeEstado; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>