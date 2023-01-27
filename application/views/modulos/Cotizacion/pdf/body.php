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
<!--
<table class="tb-detalle" style="width: 100%; margin-bottom: 100px;">
	<thead>
		<tr style="background-color: #222c33;">
			<th style="color:white">ITEM</th>
			<th style="color:white">DESCRIPCION</th>
			<th style="color:white">CANTIDAD</th>
			<? if (!empty($cabecera['mostrarPrecio'])) { ?>
				<th style="color:white">PRECIO</th>
			<? } ?>
			<th style="color:white">SUBTOTAL</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($detalle as $key => $row) : ?>>
		<tr style="background-color: #9db7c9;">
			<td style="text-align: center;"><?= $key + 1 ?></td>
			<td style="text-align: left;">
				<?= verificarEmpty($row['item'], 3) ?>
				<?php if (!empty($row['caracteristicas'])) :  ?>
					<p>
						<?= $row['caracteristicas'] ?>
					</p>
				<?php endif; ?>
				<?php if (!empty($row['idCotizacionDetalle'])) :  ?>
					<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
						<p> *
							<?= verificarEmpty($value['nombre'], 1, 'DESC: ', ', ') ?>
							<?= verificarEmpty($value['talla'], 1, 'TALLA: ', ', ') ?>
							<?= verificarEmpty($value['tela'], 1, 'TELA: ', ', ') ?>
							<?= verificarEmpty($value['color'], 1, 'COLOR: ', ', ') ?>
							<?= verificarEmpty($value['cantidad'], 1, 'CANT: ', ', ') ?>
							<?= verificarEmpty($value['costo'], 1, 'Cost. Unit: ' . 'S/ ', ', ') ?>
							<?= verificarEmpty(LIST_GENERO[$value['genero'] - 1]['value'], 1, 'GENERO: ', ', ') ?>
						</p>
					<?php endforeach; ?>
				<?php endif; ?>

			</td>
			<td style="text-align: right;"><?= verificarEmpty($row['cantidad'], 3) ?></td>
			<?php if (!empty($cabecera['mostrarPrecio'])) :  ?>
				<td style="text-align: right;"><?= empty($row['precio']) ? moneda(verificarEmpty($row['costo'], 2)) : moneda($row['precio']); ?></td>
			<?php endif; ?>
			<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>

		</tr>
		<? if (!empty($row['caracteristicas'])) { ?>
			<tr>
				<td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "4" : "3" ?>">
					<p>
						<?= $row['caracteristicas'] ?>
					</p>
				</td>
			</tr>
		<? } ?>
		<tr>
			<td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "4" : "3" ?>">
				<? if (!empty($archivos[$row['idCotizacionDetalle']])) { ?>
					<div class="ui fluid image content-lsck-capturas" style="display: inline-block;">
						<? foreach ($archivos[$row['idCotizacionDetalle']] as $archivo) { ?>
							<? if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) { ?>
								<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>">
									<img height="100" src="<?= RUTA_WASABI . "cotizacion/{$archivo['nombre_archivo']}" ?>">
								</a>
							<? } ?>
						<? } ?>
					</div>
				<? } ?>
			</td>
		</tr>
	<?php endforeach; ?>

	<? for ($i = 0; $i <= 2; $i++) { ?>
		<tr>
			<td></td>
			<td></td>
			<? if (!empty($cabecera['mostrarPrecio'])) { ?>
				<td></td>
			<? } ?>

			<td></td>
		</tr>
	<? } ?>

	</tbody>

	<tfoot class="full-widtd">
		<tr class="height:100px" style="background-color: #222c33;">
			<td colspan="<?= !empty($cabecera['mostrarPrecio']) ? "3" : "2" ?>" class="text-right" style="color:white">
				<p>SUB TOTAL</p>
				<p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '0%' ?></p>
				<? if (!empty($cabecera['igv'])) { ?>
					<p>IGV</p>
				<? } ?>
				<p>TOTAL GENERAL <?= empty($cabecera['igv']) ? '(No incluye igv)' : '' ?></p>
			</td>
			<td class="text-right" style="color:white">
				<p><?= moneda($cabecera['total']) ?></p>
				<p><?= moneda(($cabecera['fee_prc'])) ?></p>
				<? if (!empty($cabecera['igv'])) { ?>
					<p><?= moneda($cabecera['igv_prc']) ?></p>
				<? } ?>
				<p><?= moneda($cabecera['total_fee_igv'])  ?></p>
			</td>
		</tr>
	</tfoot>
</table>
-->
<?php $idItemTipo = ''; ?>
<?php $col1 = 0; ?>
<?php $montoSub = 0; ?>
<?php foreach ($detalle as $key => $row) : ?>
	<?php if ($idItemTipo != $row['idItemTipo']) : ?>
		<?php if ($key != 0) :  ?>
			</tbody>
			<tfoot class="full-widtd">
				<tr class="height:100px" style="background-color: #222c33;">
					<td colspan="<?= $col1; ?>" class="text-right" style="color:white">
						<p>SUB TOTAL</p>
					</td>
					<td class="text-right" style="color:white">
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
				<?php if ($idItemTipo == COD_SERVICIO['id']) :  ?>
					<?php $col1 = 7; ?>
					<tr style="background-color: #222c33;">
						<th style="color:white">ITEM</th>
						<th style="color:white">SUCURSAL</th>
						<th style="color:white">RAZON SOCIAL</th>
						<th style="color:white">TIPO ELEMENTO</th>
						<th style="color:white">MARCA</th>
						<th style="color:white">DETALLES DE SERVICIO</th>
						<th style="color:white">CANTIDAD</th>
						<th style="color:white">TOTAL</th>
					</tr>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_DISTRIBUCION['id']) :  ?>
					<?php $col1 = 2; ?>
					<tr style="background-color: #222c33;">
						<th style="color:white; width:5%;">ITEM</th>
						<th style="color:white; width:80%; text-align:left;">DESCRIPCION</th>
						<th style="color:white; width:15%;">TOTAL</th>
					</tr>
				<?php endif; ?>
				<?php if ($idItemTipo == COD_ARTICULO['id']) :  ?>
					<?php $col1 = 3; ?>
					<tr style="background-color: #222c33;">
						<th style="color:white; width:5%;">ITEM</th>
						<th style="color:white; width:65%; text-align:left;">DESCRIPCION</th>
						<th style="color:white; width:15%; text-align:left;">CANTIDAD</th>
						<th style="color:white; width:15%;">SUBTOTAL</th>
					</tr>
				<?php endif; ?>
			</thead>
			<tbody>
			<?php endif; ?>
			<tr style="background-color: #9db7c9;">
				<?php if ($idItemTipo == COD_SERVICIO['id']) :  ?>
					<td style="text-align: center;"><?= $key + 1 ?></td>
					<td style="text-align: center;"> - </td>
					<td style="text-align: center;"> PROVEEDOR DE LA COTIZACION </td>
					<td style="text-align: center;"> - </td>
					<td style="text-align: center;"> ITEM MARCA </td>
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
						<?= verificarEmpty($row['item'], 1) ?>
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
				<tr class="height:100px" style="background-color: #222c33;">
					<td colspan="<?= $col1; ?>" class="text-right" style="color:white">
						<p>SUB TOTAL</p>
					</td>
					<td class="text-right" style="color:white">
						<p><?= moneda($montoSub); ?></p>
					</td>
				</tr>
				<tr class="height:100px" style="background-color: #9db7c9;">
					<td colspan="<?= $col1; ?>" class="text-right">
						<p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '0%' ?></p>
					</td>
					<td class="text-right">
						<p><?= moneda(($cabecera['fee_prc'])) ?></p>
					</td>
				</tr>
				<tr class="height:100px" style="background-color: #222c33;">
					<td colspan="<?= $col1; ?>" class="text-right" style="color:white">
						<p>TOTAL</p>
					</td>
					<td class="text-right" style="color:white">
						<p><?= moneda($cabecera['total_fee_igv'])  ?></p>
					</td>
				</tr>
			</tfoot>
		</table>

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