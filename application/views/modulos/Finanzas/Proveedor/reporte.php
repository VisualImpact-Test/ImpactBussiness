<div class="card-datatable">
    <table id="tb-proveedor" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>RAZON SOCIAL</th>
                <th>RUC</th>
                <th>RUBRO</th>
                <th>CUENTA</th>
                <th>METODO DE PAGO</th>
                <th>DIRECCION</th>
                <th>CONTACTO</th>
                <th>CORREO</th>
                <th>NUMERO</th>
                <th class="td-center">ESTADO</th>
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
                    <td class="td-left"><?= verificarEmpty($row['rubros'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentas_bancos'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['metodosPago'], 3); ?></td>
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
