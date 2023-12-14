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
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($sincerado as $key => $row) : ?>
				<tr data-id="<?= $key ?>" data-presupuesto="<?= $row['idPresupuesto'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center"></td>
					<td class="td-center">
						<?= str_pad($key, 8, "0", STR_PAD_LEFT);; ?>
					</td>
					<td class="td-left"><?= verificarEmpty($row['nombre'], 3); ?></td>
					<td class="td-left"><?= $row['chkUtilizarCliente'] ? verificarEmpty($row['cliente'], 3) : (verificarEmpty($row['cuenta'], 3) . ' ( ' . verificarEmpty($row['centroCosto'], 3) . ' )'); ?></td>
					<td class="td-left"><?= verificarEmpty($row['moneda'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['cargo'], 3); ?></td>
					<td class="text-center style-icons">
						
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>