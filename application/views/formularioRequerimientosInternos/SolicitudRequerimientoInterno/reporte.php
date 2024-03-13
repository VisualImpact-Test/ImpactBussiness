<style>
	#validez {
		cursor: pointer;
		/* Esto cambiará el cursor a una mano al pasar sobre el div */
	}
</style>
<div class="card-datatable">
	<table id="tb-requerimientos-solicitanteInterno" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th>OPCIONES</th>
				<th>NOMBRE</th>
				<th>NRO REQUERIMIENTO</th>
				<th>FECHA EMISION</th>
				<th>CUENTA</th>
				<th>CENTRO COSTO</th>
				<th>ESTADO DEL PROCESO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<? foreach ($datos['requerimientoInterno'] as $k => $row) : ?>
				<tr data-id="<?= $row['idRequerimientoInterno'] ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td>
						<? if ($row['idRequerimientoInternoEstado'] != '5') { ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleRequerimientoInterno btn-dp-<?= $row['idRequerimientoInterno']; ?>">
								<i class="fa fa-lg fa-bars" title="Ver Detalle del Requerimiento Interno"></i>
							</a>
						<? } ?>
						<? if ($row['idRequerimientoInternoEstado'] == '2') { ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-viewSolicitudRequerimientoInterno">
								<i class="fas fa-money-check-edit-alt" title="Cotizar items sin precio"></i>
							</a>
							<a href="javascript:;" class="btn btn-outline-danger border-0 btnAnularRequerimientoInterno" data-id="<?= $row['idRequerimientoInterno'] ?>">
								<i class="fas fa-trash" title="Anular Requerimiento Interno"></i>
							</a>
						<? } ?>
						<? if ($row['idRequerimientoInternoEstado'] == '4') { ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-viewGenerarOC">
								<i class="fa fa-lg fa-badge-dollar" title="Generar Orden Compra"></i>
							</a>
						<? } ?>
					</td>
					<td class="td-left"><?= $row['nombreRequerimiento']; ?></td>
					<td class="td-left"><?= $row['codRequerimientoInterno']; ?></td>
					<td class="td-left"><?= $row['fechaEmision']; ?></td>
					<td class="td-left"><?= $row['cuenta']; ?></td>
					<td class="td-left"><?= $row['centroCosto']; ?></td>
					<td class="text-center style-icons">
						<?php $row['icono'] = str_replace("<a", "<span", $row['icono']); ?>
						<?= $row['icono']; ?></td>
				</tr>
				<? $ix++; ?>
			<? endforeach ?>
		</tbody>
	</table>
</div>