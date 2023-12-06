<div class="card-datatable">
	<table class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>OPCIONES</th>
				<th>REQUERIMIENTO</th>
				<th>CUENTA / CLIENTE</th>
				<th>MONEDA</th>
				<th>FECHA</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($datos as $k => $row) : ?>
				<tr data-id="<?= $row['idPresupuestoValido'] ?>">
					<td class="text-center"> <?= ($k + 1) ?></td>
					<td class="text-center">
						<button class="btn btn-outline-secondary border-0 btn-sincerar" title="Sincerar">
							<i class="icon calendar check outline"></i>
						</button>
						<button class="btn btn-outline-secondary border-0 btn-download" data-ruta="OrdenServicio/generarPdf/<?= $row['idPresupuesto'] ?>/<?= $row['idPresupuestoHistorico'] ?>" title="Imprimir">
							<i class="icon file pdf"></i>
						</button>
					</td>
					<td class="text-left"><?= $row['ordenServicio'] ?></td>
					<td class="text-left"><?= $row['cuenta_cliente'] ?></td>
					<td class="text-left"><?= $row['moneda'] ?></td>
					<td class="text-left"><?= date_change_format(verificarEmpty($row['fechaIni'], 4)) ?></td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>

<script>
	$(document).ready(function() {
		$('#filtroOper').DataTable();
	});
</script>