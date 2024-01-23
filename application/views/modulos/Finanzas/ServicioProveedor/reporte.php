
<div class="card-datatable">
    <table id="tb-servicioProveedor" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th>OPCIONES</th>
                
                <th>TIPO DOCUMENTO</th>
                <th>NUMERO DOCUMENTO</th>
                <th>DATOS PROVEEDOR</th>
                <th>DEPARTAMENTO</th>
                <th>PROVINCIA</th>
                <th>DISTRITO</th>
                <th>DIRECCION</th>
                
                <th class="td-center">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 0; ?>
            <?php foreach ($datos as $k => $row) : ?>
                <? $ix++; ?>
                <tr data-id="<?= $row['idProveedorServicio']; ?>">
                    <td class="td-center">
                        <?= $ix; ?>
                    </td>
                    <td class="td-center style-icons" nowrap>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Actualizar Proveedor Servicio"><i class="fa fa-lg fa-edit"></i></a>
                        <a id="hrefEstado-<?= $row['idProveedorServicio']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizar-estado" data-id="<?= $row['idProveedorServicio']; ?>" data-estado="<?= $row['idProveedorEstado']; ?>">
                            <i class="<?= $row['toggle']; ?>"></i>
                        </a>
                    </td>

                    <td><?= verificarEmpty($row['breve'], 3); ?></td>
                    <td><?= verificarEmpty($row['numDocumento'], 3); ?></td>
                    <td><?= verificarEmpty($row['datosProveedor'], 3); ?></td>
                    <td><?= verificarEmpty($row['departamento'], 3); ?></td>
                    <td><?= verificarEmpty($row['provincia'], 3); ?></td>
                    <td><?= verificarEmpty($row['distrito'], 3); ?></td>
                    <td><?= verificarEmpty($row['direccion'], 3); ?></td>
                   
                    <td class="text-center style-icons">
                        <span class="<?= $row['icono'] ?>" id="spanEstado-<?= $k; ?>"><?= $row['nombre']; ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
 </div>
