<div class="card-datatable">
    <table id="tb-usuario-detalle" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th>OPCIONES</th>
                <th>DATOS</th>
                <th>USUARIO</th>
                <th>CORREO</th>
                <th>DEMO</th>
            </tr>
        </thead>
        <tbody>
          <?php foreach ($datos as $key => $row): ?>
            <?php
              $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
              $badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
              $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
            <tr data-id="<?= $row['idUsuario'] ?>">
                <td><?= $key+1; ?></td>
                <td class="td-center style-icons">
                  <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarFirma"><i class="pen alternate icon" title="Actualizar Firma"></i></a>
                </td>
                <td class="td-left"><?= $row['apePaterno'].' '.$row['apeMaterno'].' '.$row['nombres']; ?></td>
                <td class="td-left"><?= $row['usuario']; ?></td>
                <td class="td-left"><?= $row['email']; ?></td>
                <td class="td-left"><?= $row['demo']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
    </table>
</div>
