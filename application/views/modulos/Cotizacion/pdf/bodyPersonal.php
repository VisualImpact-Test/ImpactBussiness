<div>
	<table>
		<tr>
			<td style="height:50px;"><b><?= $cabecera['cotizacion'] ?></b></td>
		</tr>
		<?php if (empty($cabecera['igv'])) : ?>
			<tr>
				<td style="margin-left: 3px; padding-top: -2px; height: 15px; color:#CE3A3A;"><b>No Incluye IGV</b></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td style="height: 20px;"><b>RUC: </b></td>
			<td><?= RUC_VISUAL ?></td>
		</tr>
		<tr>
			<td style="height: 20px;"><b>ELABORADO: </b></td>
			<td>Área de Operaciones</td>
		</tr>
		<tr>
			<td style="height: 20px;"><b>CUENTA:</b></td>
			<td style="height: 20px;"><?= $cabecera['cuenta'] ?></td>
		</tr>
		<tr>
			<td class="text-left" style="height: 20px;"><b>CENTRO DE COSTO:</b></td>
			<td cstyle="height: 20px;"><?= $cabecera['cuentaCentroCosto'] ?></td>
		</tr>
		<tr>
			<td style="height: 20px;"><b>FECHA: </b></td>
			<td><?= ($cabecera['fecha']) ?></td>
		</tr>
	</table>
</div>


<table class="tb-detalle" style="width: 100%; margin-bottom: 10px;">
	<thead>
		<tr style="background-color: #FFE598;">
			<th style="width: 10%;">ITEM</th>
			<th style="width: 70%;" colspan="4">DESCRIPCIÓN</th>
			<th style="width: 20%;">TOTAL</th>
		</tr>
	</thead>
	<tbody>
		<?php $montoSub = []; ?>
		<?php $montoTotal = 0; ?>
		<?php $fee = []; ?>
		<?php // SEPARO EL CALCULO DEL FEE DE CABECERA PARA QUE NO SE ACUMULE EN EL FOREACH
		if (!empty($cabecera['fee'])) {
			$fee[$cabecera['fee']] = floatval($cabecera['fee_prc']);
		}
		?>
		<!-- PARA CALCULAR LOS MONTOS DE PERSONAL -->
		<?php foreach ($detalle as $key => $row) : ?>
			<?php if ($row['idItemTipo'] == COD_PERSONAL['id']) : ?>
				<?php if (!isset($montoSub[$row['idCotizacionDetalle']])) $montoSub[$row['idCotizacionDetalle']] = 0; ?>
				<?php $montoSub[$row['idCotizacionDetalle']] += floatval($row['subtotal']) ?>
				<!-- PARA EL FEE QUE SE REGISTRA EN PERSONAL -->
				<?php
				if (!empty($row['fee1Por'])) {
					if (!isset($fee[$row['fee1Por']])) {
						$fee[$row['fee1Por']] = 0;
					}
					$fee[$row['fee1Por']] += $row['fee1Monto'];
				}
				if (!empty($row['fee2Por'])) {
					if (!isset($fee[$row['fee2Por']])) {
						$fee[$row['fee2Por']] = 0;
					}
					$fee[$row['fee2Por']] += $row['fee2Monto'];
				}
				?>
				<!--FIN:PARA EL FEE QUE SE REGISTRA EN PERSONAL -->
			<?php else : ?>
				<?php if (!isset($montoSub[$row['idCotizacionDetallePersonal']])) $montoSub[$row['idCotizacionDetallePersonal']] = 0; ?>
				<?php $montoSub[$row['idCotizacionDetallePersonal']] += floatval($row['subtotal']) ?>
			<?php endif; ?>
			<?php $montoTotal += floatval($row['subtotal']); ?>
		<?php endforeach; ?>

		<?php foreach ($detalle as $key => $row) : ?>
			<?php if ($row['idItemTipo'] == COD_PERSONAL['id']) : ?>
				<?php $keyDelPersonal = $key; ?>
				<tr style="background-color: #F6FAFD;">
					<td>1</td>
					<td colspan="4">
						<?= 'Recursos: ' . $row['cantidad_personal'] . ' ' . $row['cargo'] . ' ' . $row['mesInicio'] ?>
					</td>
					<td class="text-right"><?= moneda($montoSub[$row['idCotizacionDetalle']]) ?></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</tbody>
	<tfoot class="full-widtd">
		<tr class="height:100px" style="background-color: #FFE598;">
			<td colspan="5" class="text-right bold" style="color:black">
				<p>SUB TOTAL</p>
			</td>
			<td class="text-right bold" style="color:black">
				<p><?= moneda($montoTotal); ?></p>
			</td>
		</tr>
		<?php foreach ($fee as $k => $v) : ?>
			<tr class="height:100px" style="background-color: #F6FAFD;">
				<td colspan="5" class="text-right bold">
					<p>FEE <?= $k . '%' ?></p>
				</td>
				<td class="text-right">
					<p><?= moneda($v, false, 2, true) ?></p>
				</td>
			</tr>
		<?php endforeach; ?>
		<tr class="height:100px" style="background-color: #FFE598;">
			<td colspan="5" class="text-right bold" style="color:black">
				<p>TOTAL</p>
			</td>
			<td class="text-right bold" style="color:black">
				<p>
					<?= moneda(floatval($montoTotal) + floatval($cabecera['fee_prc']) +
						floatval($detalle[$keyDelPersonal]['fee1Monto']) + floatval($detalle[$keyDelPersonal]['fee2Monto'])); ?>
				</p>
			</td>
		</tr>
	</tfoot>
</table>
<div>
	<label>
		<?= isset($cabecera['comentario']) ? $cabecera['comentario'] : ''; ?>
	</label>
</div>
<?php if (!empty($anexos)) : ?>
	<h3>Anexos</h3>
	<div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?> " style="display: inline-block;">
		<?php foreach ($anexos as $anexo) : ?>
			<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>">
				<img src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>