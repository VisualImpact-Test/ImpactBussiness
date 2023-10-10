<style>

</style>
<div class="card-datatable">
	<table id="tb-ordenServicio" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>OPCIONES</th>
				<th class="td-center">NRO</th>
				<th>TÍTULO</th>
				<th>CUENTA & CENTRO COSTO / CLIENTE</th>
				<th>DEPARTAMENTO</th>
				<th>PROVINCIA</th>
				<th>DISTRITO</th>
				<th>MONEDA</th>
				<th>DOCUMENTOS</th>
				<th>CARGOS</th>
				<th class="td-center">ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($ordenServicio as $key => $row) : ?>
				<tr data-id="<?= $key ?>" data-presupuesto="<?= $row['idPresupuesto'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar Orden de Servicio"><i class="fa fa-lg fa-edit"></i></a>
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btnPresupuesto<?= $row['chkPresupuesto'] ? 'Edit' : '' ?>" title="Generar Presupuesto"><i class="icon dollar"></i></a>
						<!-- <a href="javascript:;" class="btn btn-outline-secondary border-0 btnAprobar" title="Aprobar Orden de Servicio"><i class="fa fa-lg fa-check"></i></a> -->
					</td>
					<td class="td-center">
						<?= str_pad($key, 8, "0", STR_PAD_LEFT);; ?>
					</td>
					<td class="td-left"><?= verificarEmpty($row['nombre'], 3); ?></td>
					<td class="td-left"><?= $row['chkUtilizarCliente'] ? verificarEmpty($row['cliente'], 3) : (verificarEmpty($row['cuenta'], 3) . ' ( ' . verificarEmpty($row['centroCosto'], 3) . ' )'); ?></td>
					<td class="td-left"><?= verificarEmpty($row['departamento'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['provincia'], 3); ?></td>
					<td class="td-left"><?= $row['idDistrito'] ? $row['distrito'] : '-' ?></td>
					<td class="td-left"><?= verificarEmpty($row['moneda'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['documento'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['cargo'], 3); ?></td>
					<td class="text-center style-icons">
						<?php if ($row['estado'] == 1) : ?>
							<?php if ($row['chkAprobado']) : ?>
								<span class="badge badge-success" id="spanEstado-<?= $row['estado']; ?>">Aprobado</span>
							<?php else : ?>
								<span class="badge badge-secondary" id="spanEstado-<?= $row['estado']; ?>">Pendiente</span>
							<?php endif; ?>
						<?php else : ?>
							<span class="badge badge-danger" id="spanEstado-<?= $row['estado']; ?>">Inactivo</span>
						<?php endif; ?>
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>