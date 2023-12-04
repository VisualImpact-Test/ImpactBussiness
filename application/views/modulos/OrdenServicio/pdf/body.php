<div>
	<table>
		<!-- Fila de fechas -->
		<tr>
			<th></th>
			<?php foreach ($fechas as $k => $v) : ?>
				<th><?= date_change_format($k) ?></th>
			<?php endforeach ?>
			<th>TOTAL</th>
		</tr>
		<!-- Fila de cargos & cantidades -->
		<?php foreach ($cargosOS as $kC => $vC) : ?>
			<tr>
				<td><?= $cargos[$kC]['cargo'] ?></td>
				<?php $max = 0; ?>
				<?php foreach ($fechas as $kF => $vF) : ?>
					<?php if ($cantidadPorCargoFecha[$kC][$kF]['cantidad'] > $max) $max = $cantidadPorCargoFecha[$kC][$kF]['cantidad']; ?>
					<td class="text-center"><?= $cantidadPorCargoFecha[$kC][$kF]['cantidad'] ?></td>
				<?php endforeach ?>
				<td class="text-center bold"><?= $max ?></td>
			</tr>
		<?php endforeach ?>
		<!-- Fila qwer -->
		<?php foreach ($presupuestoDetalle as $k_pd => $v_pd) : ?>
			<tr style="background-color: #BDD7EE;">
				<td class="bold"><?= $tiposPresupuesto[$v_pd['idTipoPresupuesto']]['nombre'] ?></td>
				<?php foreach ($fechas as $kF => $vF) : ?>
					<td class="text-right bold"><?= moneda($totalCargoFechaServicio[$k_pd][$kF]) ?></td>
				<?php endforeach ?>
				<td class="text-right bold"><?= moneda($totalCargoFechaServicio['totalFinal'][$k_pd]) ?></td>
			</tr>
			<!-- Para Sueldo -->
			<?php if ($v_pd['idTipoPresupuesto'] == COD_SUELDO) : ?>
				<?php $k_pd_sueldo = $k_pd; ?>
				<?php foreach ($cargosOS as $kC => $vC) : ?>
					<tr>
						<td><?= $cargos[$kC]['cargo'] ?></td>
						<?php foreach ($fechas as $kF => $vF) : ?>
							<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd][$kC][$kF]) ?></td>
						<?php endforeach ?>
						<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio'][$kC]) ?></td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td>INCENTIVO</td>
					<?php foreach ($fechas as $kF => $vF) : ?>
						<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd]['incentivo'][$kF]) ?></td>
					<?php endforeach ?>
					<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio']['incentivo']) ?></td>
				</tr>
			<?php endif; ?>

			<!-- Para Movilidad -->
			<?php if ($v_pd['idTipoPresupuesto'] == COD_MOVILIDAD) : ?>
				<tr>
					<td>VIAJES SUPERVISIÓN</td>
					<?php foreach ($fechas as $kF => $vF) : ?>
						<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd]['viajes'][$kF]) ?></td>
					<?php endforeach ?>
					<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio']['viajes']) ?></td>
				</tr>
				<tr>
					<td>ADICIONALES</td>
					<?php foreach ($fechas as $kF => $vF) : ?>
						<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd]['movAdicional'][$kF]) ?></td>
					<?php endforeach ?>
					<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio']['movAdicional']) ?></td>
				</tr>
			<?php endif; ?>

			<!-- Para Detalle PresupuestoDetalleSubCargo -->
			<?php if (!empty($presupuestoDetalleSub[$k_pd])) : ?>
				<?php foreach ($presupuestoDetalleSub[$k_pd] as $k_pds => $v_pds) : ?>
					<tr>
						<td><?= $tiposPresupuestoDetalle[$k_pds]['nombre'] ?></td>
						<?php foreach ($fechas as $kF => $vF) : ?>
							<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd][$k_pds][$kF]) ?></td>
						<?php endforeach ?>
						<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio'][$k_pds]) ?></td>
					</tr>
				<?php endforeach ?>
				<!-- Para SCTR -->
				<?php if ($v_pd['idTipoPresupuesto'] == COD_GASTOSADMINISTRATIVOS && $presupuesto['sctr'] > 0) : ?>
					<tr>
						<td>SCTR ( <?= $presupuesto['sctr'] ?> % )</td>
						<?php foreach ($fechas as $kF => $vF) : ?>
							<td class="text-right"><?= moneda($calculoCargoFechaServicio[$k_pd]['sctr'][$kF]) ?></td>
						<?php endforeach ?>
						<td class="text-right"><?= moneda($totalCargoFechaServicio['totalServicio']['sctr']) ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach ?>

		<tr>
			<td colspan="<?= count($fechas) + 2 ?>" style="border-left: 1px solid transparent; border-right: 1px solid transparent; padding: 15px; 0px;"></td>
		</tr>
		<tr style="background-color: #CCFF66">
			<td class="text-right bold">SUBTOTAL</td>
			<?php foreach ($fechas as $kF => $vF) : ?>
				<td class="text-right bold"><?= moneda($totalCargoFechaServicio['acumuladoPorFecha'][$kF]) ?></td>
				<?php $totalFinalFecha[$kF] += $totalCargoFechaServicio['acumuladoPorFecha'][$kF]; ?>
			<?php endforeach ?>
			<td class="text-right bold"><?= moneda($totalCargoFechaServicio['acumuladoTotal']) ?></td>
			<?php $totalFinal += $totalCargoFechaServicio['acumuladoTotal']; ?>
		</tr>
		<tr>
			<td class="text-right bold">FEE 1 (<?= $presupuesto['fee1'] ?>%)</td>
			<?php $totalFila = 0; ?>
			<?php foreach ($fechas as $kF => $vF) : ?>
				<td class="text-right"><?= moneda($totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee1'] / 100) ?></td>
				<?php $totalFinalFecha[$kF] += $totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee1'] / 100; ?>
				<?php $totalFila += $totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee1'] / 100; ?>
			<?php endforeach ?>
			<td class="text-right"><?= moneda($totalFila) ?></td>
			<?php $totalFinal += $totalFila; ?>
		</tr>
		<tr>
			<td class="text-right bold">FEE 2 (<?= $presupuesto['fee2'] ?>%)</td>
			<?php $totalFila = 0; ?>
			<?php foreach ($fechas as $kF => $vF) : ?>
				<td class="text-right"><?= moneda($totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee2'] / 100) ?></td>
				<?php $totalFinalFecha[$kF] += $totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee2'] / 100; ?>
				<?php $totalFila += $totalCargoFechaServicio[$k_pd_sueldo][$kF] * $presupuesto['fee2'] / 100; ?>
			<?php endforeach ?>
			<td class="text-right"><?= moneda($totalFila) ?></td>
			<?php $totalFinal += $totalFila; ?>
		</tr>
		<tr>
			<td class="text-right bold">FEE 3 (<?= $presupuesto['fee3'] ?>%)</td>
			<?php $totalFila = 0; ?>
			<?php foreach ($fechas as $kF => $vF) : ?>
				<td class="text-right"><?= moneda(($totalCargoFechaServicio['acumuladoPorFecha'][$kF] - $totalCargoFechaServicio[$k_pd_sueldo][$kF]) * $presupuesto['fee3'] / 100) ?></td>
				<?php $totalFinalFecha[$kF] += ($totalCargoFechaServicio['acumuladoPorFecha'][$kF] - $totalCargoFechaServicio[$k_pd_sueldo][$kF]) * $presupuesto['fee3'] / 100; ?>
				<?php $totalFila += ($totalCargoFechaServicio['acumuladoPorFecha'][$kF] - $totalCargoFechaServicio[$k_pd_sueldo][$kF]) * $presupuesto['fee3'] / 100; ?>
			<?php endforeach ?>
			<td class="text-right"><?= moneda($totalFila) ?></td>
			<?php $totalFinal += $totalFila; ?>
		</tr>
		<tr style="background-color: #CCFF66">
			<td class="text-right bold">TOTAL</td>
			<?php foreach ($fechas as $kF => $vF) : ?>
				<td class="text-right bold"><?= moneda($totalFinalFecha[$kF]) ?></td>
			<?php endforeach ?>
			<td class="text-right bold"><?= moneda($totalFinal) ?></td>
		</tr>
	</table>
</div>