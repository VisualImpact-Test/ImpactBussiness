<div class="card-datatable">
    <table class="table table-striped table-bordered nowrap" width="100%">
        <thead>
            <tr>
                <th></th>
                <th class="text-center">#</th>
                <th class="text-center">ACCIONES</th>
                <th class="text-center">NOMBRE</th>
                <th class="text-center">ESTADO</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($data as $key => $value) {
                $badge = $value['estado'] == 1 ? 'badge-success' : 'badge-danger';
                $mensajeEstado = $value['estado'] == 1 ? 'Activo' : 'Inactivo';
                $iconoBotonEstado = $value['estado'] == 1 ? 'fal fa-lg fa-toggle-on' : 'fal fa-lg fa-toggle-off';
            ?>
                <tr data-id="<?= $value['idComprobante'] ?>" data-estado="<?= $value['estado'] ?>">
                    <td></td>
                    <td><?= $key+1 ?></td>
                    <td>
                        <div>
                            <!--button class="btn btn-Preguntas btn-outline-secondary border-0" title="Preguntas"><i class="fas fa-lg fa-question"></i></button-->
                            <button class="btn btn-Editar btn-outline-secondary border-0" title="Editar"><i class="fas fa-lg fa-edit"></i></button>
                            <button class="btn btn-CambiarEstado btn-outline-secondary border-0" title="Activar/Desactivar"><i class="<?= $iconoBotonEstado ?>"></i></button>
                        </div>
                    </td>
                    <td class="text-center"><?= !empty($value['nombre']) ? $value['nombre'] : '-' ?></td>

                    <td data-order="<?= $mensajeEstado ?>" class="style-icons text-center">
                        <span class="badge <?= $badge ?> "><?= $mensajeEstado ?></span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
