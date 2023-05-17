<div class="card-datatable">
	<table id="filtroCotiPro" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th></th>
				<th>COTIZACIÓN</th>
				<th>PROVEEDOR</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($datos as $k => $row) : ?>
				<tr>
					<td class="text-center"> <?= ($k + 1) ?></td>
					<td class="text-center">
						<?php if (empty($row['doc'])) :  ?>
							<a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOCdelProveedor" data-id="<?= $row['idCotizacionDetalle'] ?>" data-proveedor="<?= $row['idProveedor'] ?>"><i class="fa fa-lg fa-file-excel" title="Descargar Cotización"></i></a>
						<?php else :  ?>
							<a href="<?= $row['doc'] ?>" target="_blank"><i class="fa fa-lg fa-file-<?= $row['iconFile'] ?>" title="Descargar Cotización"></i></a>
						<?php endif; ?>
					</td>
					<td class="text-left"> <?= !empty($row['nombre']) ? $row['nombre'] : '-' ?></td>
					<td class="text-left"> <?= !empty($row['razonSocial']) ? $row['razonSocial'] : '-' ?></td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>

<script>
	$(document).ready(function() {
		$('#filtroCotiPro').DataTable();
	});
</script>