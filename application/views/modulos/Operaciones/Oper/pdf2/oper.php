<? $filas = 10; ?>

<div style="text-align:justify">
	<table border="1" style="width: 100%; float: left;">
		<?php $w1 = '22%'; ?>
		<?php $w2 = '38%'; ?>
		<?php $w3 = '20%'; ?>
		<?php $w4 = '20%'; ?>
		<tr>
			<td class="text-left bold" width="<?= $w1 ?>">Dirigido a: </td>
			<td class="text-left" width="<?= $w2 ?>"><?= verificarEmpty($dataOper['usuarioReceptor'], 3) ?></td>
			<td class="text-left bold" width="<?= $w3 ?>">N° de Requerimiento</td>
			<td class="text-center" width="<?= $w4 ?>"><?= verificarEmpty($dataOper['requerimiento'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold">De:</td>
			<td class="text-left"><?= verificarEmpty($dataOper['usuarioRegistro'], 3) ?></td>
			<td class="text-left bold">OC del Cliente</td>
			<td class="text-center"><?= verificarEmpty($dataOper['numeroOC'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold">Descripción PO:</td>
			<td class="text-left" colspan="3"><?= verificarEmpty($dataOper['concepto'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold">Unidad de Negocio:</td>
			<td class="text-left"><?= verificarEmpty($dataOper['cuenta'], 3) ?></td>
			<td class="text-left bold">Centro de Costo</td>
			<td class="text-center"><?= verificarEmpty($dataOper['centroCosto'], 3) . " " . verificarEmpty($dataOper['valor'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold">Fecha de requerimiento:</td>
			<td class="text-center"><?= date_change_format(verificarEmpty($dataOper['fechaReg'], 4)) ?></td>
			<td class="text-left bold">Probable fecha de entrega</td>
			<td class="text-center"><?= date_change_format(verificarEmpty($dataOper['fechaEntrega'], 4)) ?></td>
		</tr>
	</table>
</div>
<br>
<?php $tieneTextil = false; ?>
<?php $generos = []; ?>

<?php foreach ($operDetalle as $k => $v) : ?>
	<?php if ($v['idTipo'] == COD_TEXTILES['id']) $tieneTextil = true; ?>
	<?php foreach ($operDetalleSub[$v['idOperDetalle']] as $ks => $vs) : ?>
		<?php $generos[$vs['genero']] = RESULT_GENERO[$vs['genero']]; ?>
	<?php endforeach; ?>
<?php endforeach; ?>

<?php $colGen = count($generos) ?>
<table border="1" class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
	<thead>
		<tr>
			<th class="text-center">ÍTEM</th>
			<?php if ($operDetalle[0]['idTipo'] == COD_SERVICIO['id']) : ?>
				<th class="text-center">RAZÓN SOCIAL</th>
				<th class="text-center">TIPO ELEMENTO</th>
				<th class="text-center">MARCA</th>
				<th class="text-center">ZONA</th>
			<?php else : ?>
				<th class="text-center" colspan="4">DESCRIPCIÓN - UNIDAD MEDIDA</th>
			<?php endif; ?>
			<?php if ($tieneTextil) : ?>
				<th class="text-center">TALLA</th>
				<?php foreach ($generos as $kg => $vg) : ?>
					<th class="text-center"><?= $vg; ?></th>
				<?php endforeach; ?>
			<?php endif; ?>
			<th class="text-center">CANT. TOTAL</th>
			<th class="text-center">COSTO UNIT</th>
			<th class="text-center">COSTOS PROVEEDOR <br> SIN IGV</th>
		</tr>
	</thead>
	<tbody>
		<?php $indexT = 0; ?>
		<?php $sbTotal = 0 ?>
		<?php foreach ($operDetalle as $key => $row) : ?>
			<?php $rowT = ($row['idTipo'] == COD_TEXTILES['id'] && !empty($detalleSubTalla[$row['idOperDetalle']])) ? count($detalleSubTalla[$row['idOperDetalle']]) : 1; ?>
			<?php if ($row['idTipo'] == COD_TEXTILES['id'] && !empty($detalleSubTalla[$row['idOperDetalle']])) : ?>
				<?php $first = true; ?>
				<?php foreach ($detalleSubTalla[$row['idOperDetalle']] as $kcds => $vcds) : ?>
					<?php if ($first) : ?>
						<?php $first = false; ?>
						<tr>
							<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= ++$indexT ?> </td>
							<td style="text-align: left;" colspan="3" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['item'], 3) ?></td>
							<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['unidadMedida'], 3) ?></td>
							<td style="text-align: center;" colspan="1" rowspan="1"><?= $kcds; ?></td>
							<?php foreach ($generos as $kg => $vg) : ?>
								<td style="text-align: center;" colspan="1" rowspan="1"><?= $vcds[$kg]['cantidad']; ?></td>
							<?php endforeach; ?>
							<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['cantidad_item'], 3) ?></td>
							<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= empty($row['costo_item']) ? "-" : moneda($row['costo_item']); ?></td>
							<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= !empty($row['cs_item']) ? moneda($row['cs_item']) : '-' ?></td>
							<?php $sbTotal += floatval($row['cs_item']) ?>
						</tr>
					<?php else : ?>
						<tr>
							<td style="text-align: center;" colspan="1" rowspan="1"><?= $kcds; ?></td>
							<?php foreach ($generos as $kg => $vg) : ?>
								<td style="text-align: center;" colspan="1" rowspan="1"><?= $vcds[$kg]['cantidad']; ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php else : ?>
				<?php if ($row['idTipo'] == COD_SERVICIO['id']) : ?>
					<?php $v1 = $operDetalleSub[$row['idOperDetalle']][0]['sucursal'] ?>
					<?php $v2 = $operDetalleSub[$row['idOperDetalle']][0]['razonSocial'] ?>
					<?php $v3 = $operDetalleSub[$row['idOperDetalle']][0]['tipoElemento'] ?>
					<?php $v4 = $operDetalleSub[$row['idOperDetalle']][0]['marca'] ?>
					<?php $costoTotal = 0; ?>
					<?php foreach ($operDetalleSub[$row['idOperDetalle']] as $ks => $vs) : ?>
						<?php if (!($v1 == $vs['sucursal'] && $v2 == $vs['razonSocial'] && $v3 == $vs['tipoElemento'] && $v4 == $vs['marca'])) : ?>
							<tr>
								<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= ++$indexT ?></td>
								<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v2; ?></td>
								<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v3; ?></td>
								<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v4; ?></td>
								<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v1; ?></td>
								<?php if ($tieneTextil) : ?>
									<td style="text-align: center;" colspan="<?= $colGen + 1; ?>" rowspan="<?= $rowT; ?>">-</td>
								<?php endif; ?>
								<td style="text-align: center;" rowspan="<?= $rowT; ?>">1</td>
								<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= moneda($costoTotal); ?></td>
								<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= moneda($costoTotal); ?></td>
								<?php $sbTotal += floatval($costoTotal) ?>
							</tr>
							<?php $v1 = $vs['sucursal']; ?>
							<?php $v2 = $vs['razonSocial']; ?>
							<?php $v3 = $vs['tipoElemento']; ?>
							<?php $v4 = $vs['marca']; ?>
							<?php $costoTotal = 0; ?>
						<?php endif; ?>
						<?php $costoTotal += (floatval($vs['cantidad']) * floatval($vs['costo'])); ?>
					<?php endforeach; ?>
					<tr>
						<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= ++$indexT ?></td>
						<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v2; ?></td>
						<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v3; ?></td>
						<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v4; ?></td>
						<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= $v1; ?></td>
						<?php if ($tieneTextil) : ?>
							<td style="text-align: center;" colspan="<?= $colGen + 1; ?>" rowspan="<?= $rowT; ?>">-</td>
						<?php endif; ?>
						<td style="text-align: center;" rowspan="<?= $rowT; ?>">1</td>
						<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= moneda($costoTotal); ?></td>
						<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= moneda($costoTotal); ?></td>
						<?php $sbTotal += floatval($costoTotal) ?>
					</tr>
				<?php else : ?>
					<tr>
						<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= ++$indexT ?></td>
						<td style="text-align: left;" colspan="3" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['item'], 3) ?></td>
						<td style="text-align: left;" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['unidadMedida'], 3) ?></td>
						<?php if ($tieneTextil) : ?>
							<td style="text-align: center;" colspan="<?= $colGen + 1; ?>" rowspan="<?= $rowT; ?>">-</td>
						<?php endif; ?>
						<td style="text-align: center;" rowspan="<?= $rowT; ?>"><?= verificarEmpty($row['cantidad_item'], 3) ?></td>
						<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= empty($row['costo_item']) ? "-" : moneda($row['costo_item']); ?></td>
						<td style="text-align: right;" rowspan="<?= $rowT; ?>"><?= !empty($row['cs_item']) ? moneda($row['cs_item']) : '-' ?></td>
						<?php $sbTotal += floatval($row['cs_item']) ?>
					</tr>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?= completarFilasPdf(['data' => $indexT, 'filas' => $filas, 'columnas' => 8 + ($tieneTextil ? ($colGen + 1) : 0)]) ?>
		<tr>
			<td style="border: none;" colspan="<?= 6 + ($tieneTextil ? $colGen + 1 : 0); ?>"></td>
			<td class="text-center bold">TOTAL</td>
			<td class="text-right"><?= moneda($sbTotal); ?></td>
		</tr>
	</tbody>
</table>
<p style="width: 100%; margin-bottom: 100px;">
	Del ítem 1 al ítem <?= $filas ?>
</p>
<table style="border:none;width: 100%;">
	<tr>
		<td class="w-30 text-center">
			<div style="text-align:center; ">
				<hr style="height: 3px; color:black">
				Nombre y firma del solicitante
			</div>
		</td>
		<td class="w-40">
		</td>
		<td class="w-30 text-center">
			<div style="text-align:center">
				<hr style="height: 3px; color:black; ">
				Nombre y firma del Jefe Directo
			</div>
		</td>
	</tr>
</table>