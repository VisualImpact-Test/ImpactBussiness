<div class="card-datatable">
	<table id="tb-oc" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th>OPCIONES</th>
				<th>NRO OC</th>
				<th>CONCEPTO</th>
				<th>REQUERIMIENTO</th>
				<th>PROVEEDOR</th>
				<th>LUGAR ENTREGA</th>
				<th>FECHA ENTREGA</th>
				<th>ITEM</th>
				<th>IGVPORCENTAJE</th>
				<th>TOTAL</th>
				<th>ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($datos as $key => $row) : ?>
				<?php
				$mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
				$badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
				$toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
				?>
				<tr data-id="<?= $key ?>" data-idproveedor="<?= $row['idProveedor']; ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-center style-icons">
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar OC"><i class="fa fa-lg fa-edit"></i></a>
						<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-descargarOC" title="Imprimir OC"><i class="fa fa-lg fa-file-pdf"></i></a>
					</td>
					<td class="td-center"><?= verificarEmpty($row['seriado'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['concepto'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['requerimiento'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['proveedor'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['entrega'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['fechaEntrega'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['item'], 3); ?></td>
					<td class="td-center"><?= verificarEmpty($row['IGVPorcentaje'], 3); ?></td>
					<td class="td-center"><?= monedaNew(['valor' => $row['totalIGV'], 'simbolo' => $row['simboloMoneda'], 'cambio' => $row['monedaCambio']]); ?></td>
					<td class="text-center style-icons">
						<span class="badge <?= $badge ?>" id="spanEstado-<?= $row['estado']; ?>"><?= $mensajeEstado; ?></span>
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>