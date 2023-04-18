<style>

</style>
<div class="card-datatable">
	<table id="tb-licitacion" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>OPCIONES</th>
				<th class="td-center">NRO</th>
				<th>CLIENTE</th>
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
			<?php foreach ($licitacion as $key => $row) : ?>
				<tr data-id="<?= $key ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar Licitación"><i class="fa fa-lg fa-edit"></i></a>
						<?php if ($row['chkAprobado']) :  ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btnPresupuesto" title="Generar Presupuesto"><i class="fa fa-lg fa-user"></i></a>
						<?php else : ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btnAprobar" title="Aprobar Licitación"><i class="fa fa-lg fa-check"></i></a>
						<?php endif; ?>
					</td>
					<td class="td-center">
						<?= str_pad($key, 8, "0", STR_PAD_LEFT);; ?>
					</td>
					<td class="td-left"><?= verificarEmpty($row['cliente'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['departamento'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['provincia'], 3); ?></td>
					<td class="td-left"><?= $row['idDistrito'] ? $row['distrito'] : '-' ?></td>
					<td class="td-left"><?= verificarEmpty($row['moneda'], 3); ?></td>
					<td class="td-left"><?= verificarEmpty($row['documento'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['cargo'], 3); ?></td>
					<td class="text-center style-icons">
						<?php if ($row['estado'] == 1) :  ?>
							<?php if ($row['chkAprobado']) :  ?>
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