<div class="card-datatable">
	<table id="tb-proveedorTipoServicio-detalle" class="mb-0 table table-bordered text-nowrap" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>OPCIONES</th>
				<th>TIPO SERVICIO</th>
				<th>ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($datos as $key => $row) : ?>
				<?php
				$mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
				$badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
				$toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
				?>
				<tr data-id="<?= $row['idProveedorTipoServicio'] ?>">
					<td class="td-center"><?= $key + 1; ?></td>
					<td class="td-center style-icons">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizarProveedorTipoServicio"><i class="fa fa-lg fa-edit" title="Actualizar Tipos Servicio"></i></a>
						<a id="hrefEstado-<?= $row['idProveedorTipoServicio']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoTipo" data-id="<?= $row['idProveedorTipoServicio']; ?>" data-estado="<?= $row['estado']; ?>">
							<i class="fal fa-lg <?= $toggle ?>"></i>
						</a>
					</td>
					<td class="td-left"><?= $row['nombre']; ?></td>
					<td class="text-center style-icons">
						<span class="badge <?= $badge ?>" id="spanEstado-<?= $row['estado']; ?>"><?= $mensajeEstado; ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>