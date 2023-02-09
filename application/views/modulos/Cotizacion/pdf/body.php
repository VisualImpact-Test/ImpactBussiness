<div style="text-align:justify">
	<table>
		<tr>
			<td style="text-align: justify; height:30px;"><b><?= $cabecera['cotizacion'] ?></b></td>
		</tr>
		<? if (empty($cabecera['igv'])) { ?>
			<tr>
				<td style="margin-left: 3px; padding-top: -2px; text-align: justify; height: 15px; color:#CE3A3A;"><b>No Incluye IGV</b></td>
			</tr>
		<? } ?>
		<tr>
			<td style="text-align: justify; height: 20px;"><b>RUC: </b></td>
			<td><?= RUC_VISUAL ?></td>
		</tr>
		<tr>
			<td style="text-align: justify; height: 20px;"><b>ELABORADO: </b></td>
			<td>Área de Operaciones</td>
		</tr>
		<tr>
			<td style="text-align: justify; height: 20px;"><b>CUENTA:</b></td>
			<td style="text-align: justify; height: 20px;"><?= $cabecera['cuenta'] ?></td>
		</tr>
		<tr>
			<td style="text-align: left; height: 20px;"><b>CENTRO DE COSTO:</b></td>
			<td cstyle="text-align: justify; height: 20px;"><?= $cabecera['cuentaCentroCosto'] ?></td>
		</tr>
		<tr>
			<td style="text-align: justify; height: 20px;"><b>FECHA: </b></td>
			<td><?= ($cabecera['fecha']) ?></td>
		</tr>

	</table>
</div>
<?php $idItemTipo = ''; ?>
<?php $col1 = 0; ?>
<?php $montoSub = 0; ?>
<?php foreach ($detalle as $key => $row) : ?>
	<?php if ($idItemTipo != $row['idItemTipo']) : ?>
		<?php if ($key != 0) :  ?>
			</tbody>
			<tfoot class="full-widtd">
				<tr style="height:100px; background-color: #FFE598;">
					<td colspan="<?= $col1; ?>" class="text-right" style="height: 20px; color:black;">
						<p>SUB TOTAL</p>
					</td>
					<td class="text-right" style="color:black">
						<p><?= moneda($montoSub); ?></p>
					</td>
				</tr>
			</tfoot>
			</table>
		<?php endif; ?>
		<?php $idItemTipo = $row['idItemTipo']; ?>
		<br>
		<table class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
			<thead>
				<?php if ($idItemTipo == COD_TRANSPORTE['id']) :  ?>
					<?php $col1 = 7; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black">ITEM</th>
						<th style="color:black">SUCURSAL</th>
						<th style="color:black">RAZON SOCIAL</th>
						<th style="color:black">TIPO ELEMENTO</th>
						<th style="color:black">MARCA</th>
						<th style="color:black">DETALLES DE SERVICIO</th>
						<th style="color:black">CANTIDAD</th>
						<th style="color:black">TOTAL</th>
					</tr>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_SERVICIO['id']) :  ?>
					<?php $col1 = 7; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black">ITEM</th>
						<th style="color:black">SUCURSAL</th>
						<th style="color:black">RAZON SOCIAL</th>
						<th style="color:black">TIPO ELEMENTO</th>
						<th style="color:black">MARCA</th>
						<th style="color:black">DETALLES DE SERVICIO</th>
						<th style="color:black">CANTIDAD</th>
						<th style="color:black">TOTAL</th>
					</tr>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_DISTRIBUCION['id']) :  ?>
					<?php $col1 = 2; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black; width:5%;">ITEM</th>
						<th style="color:black; width:80%; text-align:left;">DESCRIPCION</th>
						<th style="color:black; width:15%;">TOTAL</th>
					</tr>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_ARTICULO['id']) :  ?>
					<?php $col1 = 3; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black; width:5%;">ITEM</th>
						<th style="color:black; width:65%; text-align:left;">DESCRIPCION</th>
						<th style="color:black; width:15%; text-align:left;">CANTIDAD</th>
						<th style="color:black; width:15%;">SUBTOTAL</th>
					</tr>
				<?php endif; ?>
			</thead>
			<tbody>
			<?php endif; ?>
			<tr style="background-color: #F6FAFD;">
				<?php if ($idItemTipo == COD_SERVICIO['id']) :  ?>
					<td style="text-align: center;"><?= $key + 1 ?></td>
					<td style="text-align: center;"> - </td>
					<td style="text-align: center;"> <?= $row['proveedor']; ?></td>
					<td style="text-align: center;"> <?= $row['item']; ?> </td>
					<td style="text-align: center;"> <?= $row['itemMarca']; ?> </td>
					<td style="text-align: left;">
						<?php if (!empty($row['idCotizacionDetalle'])) :  ?>
							<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
								<p> <?= verificarEmpty($value['nombre'], 1) ?> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td style="text-align: center;">
						<?php if (!empty($row['idCotizacionDetalle'])) :  ?>
							<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
								<p> <?= verificarEmpty($value['cantidad'], 1) ?> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_TRANSPORTE['id']) :  ?>
					<td style="text-align: center;"><?= $key + 1 ?></td>
					<td style="text-align: center;"> - </td>
					<td style="text-align: center;"> <?= $row['proveedor']; ?></td>
					<td style="text-align: center;"> <?= $row['item']; ?> </td>
					<td style="text-align: center;"> <?= $row['itemMarca']; ?> </td>
					<td style="text-align: left;">
						<?php if (!empty($row['idCotizacionDetalle'])) :  ?>
							<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
								<p> <?= verificarEmpty($value['nombre'], 1) ?> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td style="text-align: center;">
						<?php if (!empty($row['idCotizacionDetalle'])) :  ?>
							<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
								<p> <?= verificarEmpty($value['cantidad'], 1) ?> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
					<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_DISTRIBUCION['id']) :  ?>
					<td style="text-align: center;"><?= $key + 1 ?></td>
					<td style="text-align: left;">
						<?= verificarEmpty($row['item'], 1) ?>
					</td>
					<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_ARTICULO['id']) :  ?>
					<td style="text-align: center;"><?= $key + 1 ?></td>
					<td style="text-align: left;">
						<?= verificarEmpty($row['item'], 1) ?> <?= verificarEmpty($row['caracteristicas'], 1, '(', ')'); ?>
					</td>
					<td style="text-align: left;">
						<?= verificarEmpty($row['cantidad'], 1) ?>
					</td>
					<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				<?php endif; ?>
				<?php $montoSub += floatval($row['subtotal']); ?>
			</tr>
		<?php endforeach; ?>
			</tbody>
			<tfoot class="full-widtd">
				<tr class="height:100px" style="background-color: #FFE598;">
					<td colspan="<?= $col1; ?>" class="text-right bold" style="color:black">
						<p>SUB TOTAL</p>
					</td>
					<td class="text-right bold" style="color:black">
						<p><?= moneda($montoSub); ?></p>
					</td>
				</tr>
				<tr class="height:100px" style="background-color: #F6FAFD;">
					<td colspan="<?= $col1; ?>" class="text-right bold">
						<p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '0%' ?></p>
					</td>
					<td class="text-right">
						<p><?= moneda(($cabecera['fee_prc'])) ?></p>
					</td>
				</tr>
				<tr class="height:100px" style="background-color: #FFE598;">
					<td colspan="<?= $col1; ?>" class="text-right bold" style="color:black">
						<p>TOTAL</p>
					</td>
					<td class="text-right bold" style="color:black">
						<p><?= moneda($cabecera['total_fee_igv'])  ?></p>
					</td>
				</tr>
			</tfoot>
		</table>
		<div>
			<label>
				<?= isset($cabecera['comentario']) ? $cabecera['comentario'] : ''; ?>
			</label>
		</div>



		<? if (!empty($anexos)) { ?>
			<h3>Anexos</h3>
			<div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?> " style="display: inline-block;">
				<? foreach ($anexos as $anexo) { ?>
					<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>">
						<img height="100" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
					</a>
				<? } ?>
			</div>
		<? } ?>