<style>
</style>
<div class="card-datatable">
	<table id="tb-sincerado" class="ui celled table" width="100%">
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
			<?php foreach ($sincerado as $key => $row) : ?>
				<tr data-id="<?= $key ?>" data-presupuesto="<?= $row['idPresupuesto'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center">
						<?php if ($row['estado'] == 1) { ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar Orden de Servicio"><i class="fa fa-lg fa-edit"></i></a>
							<button class="btn btn-outline-secondary border-0 btn-copySincerado"><i class="icon copy"></i></button>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btnPresupuesto<?= $row['chkPresupuesto'] ? 'Edit' : '' ?>" title="Generar Presupuesto"><i class="icon dollar"></i></a>
							<?php if ($row['chkPresupuesto']) : ?>
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-download" data-ruta="Sincerado/generarPdf/<?= $row['idPresupuesto'] ?>" title="Imprimir"><i class="icon file pdf"></i></a>
							<?php endif; ?>
							<?php if ($row['chkPresupuesto']) : ?>
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-version-presupuesto" title="Versiones Presupuesto"><i class="fa fa-lg fa-book"></i></a>
							<?php endif; ?>
						<?php } ?>
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
						<?php if ($row['estado'] == 1) { ?>
							<span class="ui <?= verificarEmpty($row['colorEstado'], 3); ?> inverted  large label" id="spanEstado"><?= $row['estadoServicio']; ?></span>
						<?php } else { ?>
							<span class="ui red inverted  large label" id="spanEstado">Anulado</span>
						<?php } ?>
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>