<div class="card-datatable">
    <table id="tb-articulosServicios" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>FECHA EMISION</th>
                <th>NOMBRE</th>
                <th>CUENTA</th>
                <th>CENTRO COSTO</th>
                <th>COD COTIZACION</th>
                <th>CANTIDAD ITEMS POR CONFIRMAR</th>
            </tr>
        </thead>
        <tbody>
        <? $ix = 1; ?>
            <?
            foreach ($datos as $key => $row) {
            ?>
                <tr data-id="<?= $key ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <?
                        if (!empty($row['estadoToggle'])) {
                        ?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Actualizar Proveedor"><i class="fa fa-lg fa-edit"></i></a>
                            <a id="hrefEstado-<?= $key; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizar-estado" data-id="<?= $key; ?>" data-estado="<?= $row['idEstado']; ?>">
                                <i class="<?= $row['estadoToggle'] ?>"></i>
                            </a>
                        <?
                        } else {
                        ?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-validar" title="Aprobar"><i class="fa fa-lg fa-check-double"></i></a>
                        <?
                        }
                        ?>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['razonSocial'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['nroDocumento'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['rubro'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['metodoPago'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['direccion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['nombreContacto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['correoContacto'], 3); ?></td>
                    <td class="td-center"><?= verificarEmpty($row['numeroContacto'], 3); ?></td>
                    <td class="text-center style-icons">
                        <span class="<?= $row['estadoIcono'] ?>" id="spanEstado-<?= $key; ?>"><?= $row['estado']; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>