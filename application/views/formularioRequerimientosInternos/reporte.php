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
						<? if ($row['estado'] != 'Anulado') { ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleRequerimientoInterno btn-dp-<?= $row['idRequerimientoInterno']; ?>">
								<i class="fa fa-lg fa-bars" title="Ver Detalle del Requerimiento Interno"></i>
							</a>
							<!--< ? if ($row['estado'] == 'Generado') { ?>
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btnActualizarRequerimiento btn-dp-<?= $row['idRequerimientoInterno']; ?>">
									<i class="fa fa-lg fa-edit" title="Actualizar Requerimiento Interno"></i>
								</a>
							< ? } ?>
							<a href="javascript:;" class="btn btn-outline-danger border-0 btnAnularRequerimientoInterno" data-id="<?= $row['idRequerimientoInterno'] ?>">
								<i class="fas fa-trash" title="Anular Requerimiento Interno"></i>
							</a>-->
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