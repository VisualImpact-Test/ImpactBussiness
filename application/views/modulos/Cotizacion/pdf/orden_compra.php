<div style="text-align:justify">
	<?php $incluirImagen = false; ?>
	<?php foreach ($imagenesDeItem as $key => $value) : ?>
		<?php if (!empty($value)) : ?>
			<?php $incluirImagen = true; ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<table border="1" style="width: 100%;">
		<?php $w1 = '18%'; ?>
		<?php $w2 = '44%'; ?>
		<?php $w3 = '13%'; ?>
		<?php $w4 = '25%'; ?>
		<tr>
			<td class="text-left bold" width="<?= $w1 ?>">RUC: <?= RUC_VISUAL ?> </td>
			<td class="text-right bold" width="<?= $w2 ?>">N° DE RQ </td>
			<td class="text-center" width="<?= $w3 ?>"><?= verificarEmpty($data['requerimiento'], 3) ?></td>
			<td class="text-left bold" width="<?= $w4 ?>">N° DE ORDEN: <span style="margin-left: 50px"> OC<?= generarCorrelativo($data['idOrdenCompra'], 6) ?></span></td>
		</tr>
		<tr>
			<td class="text-left bold" width="<?= $w1 ?>" rowspan="2">Unidad de Negocio</td>
			<td class="text-center" width="<?= $w2 ?>" rowspan="2"><?= verificarEmpty($cuentas, 3) ?></td>
			<td class="text-left bold" width="<?= $w3 ?>">PO Cliente</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['pocliente'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold" width="<?= $w3 ?>">Servicio</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['concepto'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold" width="<?= $w1 ?>">Centro de Costo</td>
			<td class="text-center" width="<?= $w2 ?>"><?= verificarEmpty($centrosCosto, 3) ?></td>
			<td class="text-left bold" width="<?= $w3 ?>">Fecha</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['fechaRegistro'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left bold" colspan="4">Datos del proveedor</td>
		</tr>
		<tr>
			<td class="text-left" width="<?= $w1 ?>">Srs.</td>
			<td class="text-left" width="<?= $w2 ?>"><?= verificarEmpty($data['razonSocial'], 3) ?></td>
			<td class="text-left" width="<?= $w3 ?>">RUC:</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['rucProveedor'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left" width="<?= $w1 ?>">Atención</td>
			<td class="text-left" width="<?= $w2 ?>"><?= verificarEmpty($data['nombreContacto'], 3) ?></td>
			<td class="text-left" width="<?= $w3 ?>">Teléfono fijo</td>
			<td class="text-left" width="<?= $w4 ?>"><?= '-' ?></td>
		</tr>
		<tr>
			<td class="text-left" width="<?= $w1 ?>">Dirección</td>
			<td class="text-left" width="<?= $w2 ?>"><?= verificarEmpty($data['direccion'], 3) ?></td>
			<td class="text-left" width="<?= $w3 ?>">Celular</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['numeroContacto'], 3) ?></td>
		</tr>
		<tr>
			<td class="text-left" width="<?= $w1 ?>">Email</td>
			<td class="text-left" width="<?= $w2 ?>"><?= verificarEmpty($data['correoContacto'], 3) ?></td>
			<td class="text-left" width="<?= $w3 ?>">Fecha entrega</td>
			<td class="text-left" width="<?= $w4 ?>"><?= verificarEmpty($data['fechaEntrega'], 3) ?></td>
		</tr>
	</table>
</div>
<div style="text-align:justify; padding-top: 3%;">
	<table border="1" style="width: 100%;">
		<thead class="full-width" style="border:1px solid black;">
			<tr>
				<td class="text-center bold py">Item</td>
				<td class="text-center bold">Cantidad</td>
				<?php if ($incluirImagen) : ?>
					<td class="text-center bold">Imagen</td>
				<?php endif; ?>
				<td class="text-center bold" colspan="4">Descripción</td>
				<td class="text-center bold">Precio Unit.</td>
				<td class="text-center bold">Precio Total</td>
			</tr>
		</thead>
		<tbody style="border:1px solid black;">
			<?php $total = 0; ?>
			<?php $igv_total = 0; ?>
			<?php $indexT = 0 ?>
			<?php foreach ($detalle as $k => $row) : ?>
				<?php $row['subTotalOrdenCompra'] = $row['cantidad'] * $row['costo']; ?>
				<?php $total += (($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra']); ?>
				<?php $igv_total += (((($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra'])) * (!empty($row['igv']) ? ($row['igv'] / 100) : 0)); ?>
				<?php $mostrarSubDetalle = false; ?>
				<?php $rowS = 1; ?>
				<?php if ($row['idItemTipo'] == COD_TEXTILES['id']) :  ?>
					<?php $mostrarSubDetalle = true; ?>
					<?php $rowS = count($subDetalleItem[$row['idItem']]) + 1; ?>
				<?php endif; ?>
				<?php if ($row['idItemTipo'] == COD_SERVICIO['id']) :  ?>
					<?php $v1 = $subDetalleItem[$row['idItem']][0]['sucursal'] ?>
					<?php $v2 = $subDetalleItem[$row['idItem']][0]['razonSocial'] ?>
					<?php $v3 = $subDetalleItem[$row['idItem']][0]['tipoElemento'] ?>
					<?php $v4 = $subDetalleItem[$row['idItem']][0]['marca'] ?>
					<?php $costoTotal = 0; ?>
					<?php foreach ($subDetalleItem[$row['idItem']] as $ks => $vs) : ?>
						<?php if (!($v1 == $vs['sucursal'] && $v2 == $vs['razonSocial'] && $v3 == $vs['tipoElemento'] && $v4 == $vs['marca'])) :  ?>
							<tr>
								<td class="text-center" rowspan="<?= $rowS ?>"><?= ++$indexT ?></td>
								<td class="text-center"><?= '1'; ?></td>
								<td class="text-left" colspan="4">
									<?= $v2 . '_' . $v3 . '_' . $v4 . '_' . $v1 ?>
								</td>
								<td class="text-center"><?= $costoTotal; ?></td>
								<td class="text-center"><?= $costoTotal; ?></td>
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
						<td class="text-center" rowspan="<?= $rowS ?>"><?= ++$indexT ?></td>
						<td class="text-center"><?= '1'; ?></td>
						<td class="text-left" colspan="4">
							<?= $v2 . '_' . $v3 . '_' . $v4 . '_' . $v1 ?>
						</td>
						<td class="text-center"><?= $costoTotal; ?></td>
						<td class="text-center"><?= $costoTotal; ?></td>
					</tr>
				<?php else :  ?>
					<tr style="border-bottom: none;">
						<td class="text-center" rowspan="<?= $rowS ?>"><?= ($k + 1) ?></td>
						<td class="text-center" rowspan="<?= $rowS ?>"><?= verificarEmpty($row['cantidad'], 2) ?></td>
						<?php if ($incluirImagen) : ?>
							<td rowspan="<?= $rowS ?>">
								<?php if (($data['mostrar_imagenes'] == '1' || $data['mostrar_imagenesCoti'] == '1') && count($imagenesDeItem[$row['idItem']])) : ?>
									<?php foreach ($imagenesDeItem[$row['idItem']] as $kkk => $imagenDeItem) : ?>
										<p style="text-align:center;"><img class="imgCenter" src="<?= RUTA_WASABI . 'item/' . $imagenDeItem['nombre_archivo'] ?>" style="width: 80px; height: 80px;"></p>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<?php if ($mostrarSubDetalle) :  ?>
							<td class="text-center bold">CARACTERISTICA</td>
							<td class="text-center bold">TALLA</td>
							<td class="text-center bold">SEXO</td>
							<td class="text-center bold">CANTIDAD</td>
						<?php else : ?>
							<td class="text-left" colspan="4">
								<?= verificarEmpty($row['nombre'] . ' ' . $row['caracteristicasCompras'], 3) ?>
							</td>
						<?php endif; ?>
						<td class="text-right" rowspan="<?= $rowS ?>">
							<?= !empty($row['costo']) ? monedaNew(['valor' => $row['costo'], 'simbolo' => $data['simboloMoneda']]) : 0 ?>
						</td>
						<td class="text-right" rowspan="<?= $rowS ?>">
							<?= !empty($row['subTotalOrdenCompra']) ? monedaNew(['valor' => (($row['idItemTipo'] == COD_DISTRIBUCION['id']) ? $row['cotizacionSubTotal'] : $row['subTotalOrdenCompra']), 'simbolo' => $data['simboloMoneda']]) : 0 ?>
						</td>
					</tr>
					<?php if ($mostrarSubDetalle) :  ?>
						<?php foreach ($subDetalleItem[$row['idItem']] as $km => $vm) : ?>
							<tr>
								<?php if ($km == 0) :  ?>
									<td class="text-left" rowspan="<?= $rowS - 1 ?>">
										<?= verificarEmpty($row['nombre'] . ' ' . $row['caracteristicasCompras'], 3) ?>
									</td>
								<?php endif; ?>
								<td class="text-center"><?= $vm['talla']; ?></td>
								<td class="text-center"><?= RESULT_GENERO[$vm['genero']]; ?></td>
								<td class="text-center"><?= $vm['cantidad']; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
		<tfoot class="full-width">
			<tr class="height:100px">
				<td colspan="<?= $incluirImagen ? 7 : 6; ?>" class="text-right">
					<p>Sub Total</p>
					<p>IGV</p>
					<p>TOTAL</p>
				</td>
				<td class="text-center">
					<p><?= !empty($data['igv']) ? $data['igv'] : (IGV * 100) ?>%</p>
				</td>
				<td class="text-right">
					<p><?= monedaNew(['valor' => $total, 'simbolo' => $data['simboloMoneda']]) ?></p>
					<p><?= empty($igv_total) ? 'S/ 0.00' : (monedaNew(['valor' => $igv_total, 'simbolo' => $data['simboloMoneda']])) ?></p>
					<p><?= monedaNew(['valor' => $igv_total + $total, 'simbolo' => $data['simboloMoneda']]) ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="bold">Son :</td>
				<td colspan="<?= $incluirImagen ? 7 : 6; ?>" class="text-left">
					<?= moneyToText(['numero' => ($igv_total + $total), 'moneda' => $data['monedaPlural']]) ?>
				</td>
			</tr>
			<? if (!empty($data['entrega'])) { ?>
				<tr>
					<td colspan="2" class="bold">Entrega :</td>
					<td colspan="<?= $incluirImagen ? 7 : 6; ?>" class="bold">
						<?= !empty($data['entrega']) ? "{$data['entrega']}" : '' ?>
					</td>
				</tr>
			<? } ?>

			<? if (!empty($data['observacion'])) { ?>
				<tr>
					<td colspan="2" class="bold">Observación :</td>
					<td colspan="<?= $incluirImagen ? 7 : 6; ?>" class="bold">
						<?php if ($data['mostrar_observacion'] == 1) : ?>
							<?= !empty($data['observacion']) ? "{$data['observacion']}" : '' ?>
						<?php endif; ?>
					</td>
				</tr>
			<? } ?>
			<? if (!empty($data['comentario'])) : ?>
				<tr>
					<td colspan="<?= $incluirImagen ? 9 : 8; ?>" class="text-left">
						<?= !empty($data['comentario']) ? $data['comentario'] : '' ?>
					</td>
				</tr>
			<? endif; ?>
			<tr style="border-bottom: none;">
				<td class="text-center bold" colspan="3" style="border-bottom: none; height: 55px; vertical-align: middle;">
					Forma de Pago
				</td>
				<td class="text-center bold" colspan="2" style="border-bottom: none; height: 55px; vertical-align: middle;">
					<?= !empty($data['metodoPago']) ? $data['metodoPago'] : '' ?>
				</td>
				<td class="text-center bold" style="border-bottom: none; height: 55px; vertical-align: middle;">
					Observaciones
				</td>
				<td class="text-center bold" colspan="4" style="border-bottom: none; height: 55px; vertical-align: middle;">
					<?= !empty($data['pocliente']) ? $data['pocliente'] : '' ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

<div style="border: 2px solid black; text-align:justify;height:100px">
	<table style="border:none !important;width: 100%; margin-top:30px">
		<tr>
			<td class="w-10">
			</td>
			<td class="w-30 text-center" style="padding-top:120px;">
				<div style="text-align:center;">
					<hr style="height: 3px; color:black">
					Área de Logística
				</div>
			</td>
			<td class="w-20 text-center" style="padding-top:120px;">
			</td>
			<td class="w-30 text-center" style="padding-top:120px">
				<?php if (!empty($data['nombre_archivo'])) : ?>
					<img id="imagenFirma" src="<?= empty($data['nombre_archivo']) ? '' : (RUTA_WASABI . 'usuarioFirma/' . $data['nombre_archivo']) ?>" style="padding-top: -120px; width: 200px; height: 120px;">
				<?php endif; ?>
				<div style="text-align:center">
					<hr style="height: 3px; color:black; ">
					Coordinador de Compras
				</div>
			</td>
			<td class="w-10">
			</td>
		</tr>
	</table>
</div>