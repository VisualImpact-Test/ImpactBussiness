<div class="card-datatable">
	<table id="tb-tracking" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="d-none">NRO</th>
				<th class="td-center">CORRELATIVO</th>
			
				<th class="td-center">AÑO</th>
				<th class="td-center">MES</th>
				<th class="td-left">CLIENTE</th>
				<th>CANAL</th>
				<th>SUB CANAL</th>
				<th>DESCRIPCIÓN</th>
				<th>USUARIO</th>
				<th>FECHA OC</th>
				<th>OC</th>
				<th>FECHA DE SUSTENTO</th>
				<th>FECHA GR</th>
				<th>FECHA ENVIO A FINANZAS</th>
				<th>GR</th>
				<th>MONTO COBRADO</th>
				<th>STATUS SERVICIO</th>
				<th>CONCEPTO CONTABILIDAD</th>
				<th>PLANILLAS</th>
				<th>INCENTIVOS</th>
				<th>SOPORTE</th>
				<th>COMPRAS</th>
				<th>FEE</th>
				<th>TOTAL</th>
				<th>FECHA ESTIMADA EJECUCIÓN</th>
				<th>ACTUALIZAR ESTADO TRACKING</th>
				<th>COMENTARIOS</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($tracking as $k => $row) : ?>
				<tr data-id="<?= $k ?>">
					<td class="d-none"> <?= $k + 1 ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['correlativa'], 3).'-'.formularSerie($row['id']) ?> </td>
					
					<td class="td-center"> <?= explode('-', $row['mes'])[0] ?> </td>
					<td class="td-center"> <?= NOMBRE_MES[explode('-', $row['mes'])[1]] ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['cliente'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['canal'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['subcanal'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['descripcion'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['usuario'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['fechaOC'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['oc'], 3) ?> </td>
					<td class="td-center"> <?= date_change_format(verificarEmpty($row['fechaSustento'], 4)) ?> </td>
					<td class="td-center"> <?= date_change_format(verificarEmpty($row['fechaGR'], 4)) ?> </td>
					<td class="td-center"> <?= date_change_format(verificarEmpty($row['fechaEnvioFinanzas'], 4)) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['gr'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['monto'], 3) ?> </td>
					<?php 
					$status = 'PENDIENTE';
					$span = 'yellow';
					if (!empty($row['fechaSustento'])) {
						$status = 'ENVIADO';
						$span = 'green';
					}
					if (!empty($row['fechaGR']) && !empty($row['idGr'])) {
						$status = 'POR FACTURAR';
						$span = 'purple';
					}
					if ($row['estadoSustento'] == 0) {
						$status = 'ELIMINADO';
						$span = 'red';
					}
					?>
					
					<td class="td-center"> <span class="ui <?= $span; ?> large label claseEstado"><?= $status; ?></span> </td>
					<td class="td-center"> <?= verificarEmpty($row['concepto'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['planillas'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['incentivo'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['soporte'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['compras'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['fee'], 3) ?> </td>
					<td class="td-center"> <?= verificarEmpty($row['total'], 3) ?> </td>
					<td class="td-center">
						<?= verificarEmpty($row['fechaEstimadaEjecucion']) ?>
						<?php if (!empty($row['idGr'])) : ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-trackingDatosAdicionales" data-idgr="<?= $row['idGr'] ?>" data-id="<?= $row['id'] ?>" title="Indicar Fecha Ejecución"><i class="fa fa-lg fa-edit"></i></a>
						<?php endif; ?>
					</td>
					<td class="td-center">
						<?php if ($row['tipoTracking'] == 'C') { 
							if (empty($row['fechaSustento'])) { 
								if($row['estadoSustento'] == 1){?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-trackingFechaSustento" data-idgr="<?= $row['idGr'] ?>" data-id="<?= $row['id'] ?>" title="Indicar Fecha Ejecución"><i class="fa fa-lg fa-edit"></i></a>
						<?php } } } ?>
					</td>
					<td class="td-center">
						<?= verificarEmpty($row['comentario']) ?>
						<?php if (!empty($row['idGr'])) : ?>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-trackingDatosAdicionales" data-idgr="<?= $row['idGr'] ?>" data-id="<?= $row['id'] ?>" title="Indicar Comentario"><i class="fa fa-lg fa-edit"></i></a>
						<?php endif; ?>
					</td>
				</tr>
				<? $ix++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>