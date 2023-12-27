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
				<tr data-id="<?= $key ?>" data-presupuesto="<?= $row['idPresupuesto'] ?>" data-idsincerado="<?= $row['idSincerado'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center">
						<button class="btn btn-outline-secondary border-0 btn-formPendienteAprobar">
							<i class="fa fa-lg fa-check" title="Pendiente Aprobar"></i>
						</button>
						<button class="btn btn-outline-secondary border-0 btn-descargarExcelGr">
							<i class="fa fa-lg fa-file-excel" title=""></i>
						</button>
						<button class="btn btn-outline-secondary border-0 btn-cargarGR">
							<i class="fa fa-lg fa-list-ul" title="Cargar GR"></i>
						</button>
						<button type="button" class="btn btn-outline-trade-visual border-0" data-ruta="descargarExcel" id="btn-descargarExcelSincerado" title="Descargar">
							<i class="icon file excel"></i>
						</button>

					</td>
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