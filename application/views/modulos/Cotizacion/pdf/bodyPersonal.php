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
		<?php $montoSub = 0; ?>
		<?php $keyDelPersonal = NULL; ?>
		<?php foreach ($detalle as $key => $row) : ?>
			<?php $montoSub += floatval($row['subtotal']); ?>
		<?php endforeach; ?>
		<?php foreach ($detalle as $key => $row) : ?>
			<?php if ($row['idItemTipo'] == COD_PERSONAL['id']) : ?>
				<?php $keyDelPersonal = $key; ?>
				<tr style="background-color: #F6FAFD;">
					<td>1</td>
					<td colspan="4">
						<?= 'Recursos: ' . $row['cantidad_personal'] . ' ' . $row['cargo'] . ' ' . $row['mesInicio'] ?>
					</td>
					<td class="text-right"><?= moneda($montoSub) ?></td>
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
				<p><?= moneda($montoSub); ?></p>
			</td>
		</tr>
		<tr class="height:100px" style="background-color: #F6FAFD;">
			<td colspan="5" class="text-right bold">
				<p>FEE <?= verificarEmpty($detalle[$keyDelPersonal]['fee1Por'], 2) . '%' ?></p>
			</td>
			<td class="text-right">
				<p><?= moneda(verificarEmpty($detalle[$keyDelPersonal]['fee1Monto'], 2), false, 2, true) ?></p>
			</td>
		</tr>
		<tr class="height:100px" style="background-color: #F6FAFD;">
			<td colspan="5" class="text-right bold">
				<p>FEE <?= verificarEmpty($detalle[$keyDelPersonal]['fee2Por'], 2) . '%' ?></p>
			</td>
			<td class="text-right">
				<p><?= moneda(verificarEmpty($detalle[$keyDelPersonal]['fee2Monto'], 2), false, 2, true) ?></p>
			</td>
		</tr>
		<tr class="height:100px" style="background-color: #F6FAFD;">
			<td colspan="5" class="text-right bold">
				<p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '0%' ?></p>
			</td>
			<td class="text-right">
				<p><?= moneda(($cabecera['fee_prc'])) ?></p>
			</td>
		</tr>
		<tr class="height:100px" style="background-color: #FFE598;">
			<td colspan="5" class="text-right bold" style="color:black">
				<p>TOTAL</p>
			</td>
			<td class="text-right bold" style="color:black">
				<p>
					<?= moneda(floatval($montoSub) + floatval($cabecera['fee_prc']) +
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