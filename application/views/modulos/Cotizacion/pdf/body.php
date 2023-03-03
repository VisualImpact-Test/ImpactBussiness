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
	<!-- PARA UTILIZAR ARTICULO Y TEXTIL BAJO EL MISMO FORMATO -->
	<?php if (($idItemTipo == COD_ARTICULO['id'] || $idItemTipo == COD_MOVIL['id']) && $row['idItemTipo'] == COD_TEXTILES['id']) :  ?>
		<?php $idItemTipo = COD_TEXTILES['id'] ?>
	<?php endif; ?>
	<?php if (($idItemTipo == COD_TEXTILES['id'] || $idItemTipo == COD_MOVIL['id']) && $row['idItemTipo'] == COD_ARTICULO['id']) :  ?>
		<?php $idItemTipo = COD_ARTICULO['id'] ?>
	<?php endif; ?>
	<?php if (($idItemTipo == COD_TEXTILES['id'] || $idItemTipo == COD_ARTICULO['id']) && $row['idItemTipo'] == COD_MOVIL['id']) :  ?>
		<?php $idItemTipo = COD_MOVIL['id'] ?>
	<?php endif; ?>
	<!-- FIN: PARA UTILIZAR ARTICULO Y TEXTIL BAJO EL MISMO FORMATO -->
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
		<table class="tb-detalle" style="width: 100%; margin-bottom: 10px;">
			<thead>
				<?php if ($idItemTipo == COD_TRANSPORTE['id']) :  ?>
					<?php $col1 = 2; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black">ITEM</th>
						<th style="color:black">DESCRIPCIÓN</th>
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
						<!-- <th style="color:black">COSTO</th> -->
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
				<?php if ($idItemTipo == COD_ARTICULO['id'] || $idItemTipo == COD_TEXTILES['id'] || $idItemTipo == COD_MOVIL['id']) :  ?>
					<?php $col1 = 6; ?>
					<tr style="background-color: #FFE598;">
						<th style="color:black; width:5%;">ITEM</th>
						<th style="color:black; width:65%; text-align:left;" colspan="4">DESCRIPCION</th>
						<th style="color:black; width:15%; text-align:left;">CANTIDAD</th>
						<th style="color:black; width:15%;">SUBTOTAL</th>
					</tr>
				<?php endif; ?>
			</thead>
			<tbody>
			<?php endif; ?>
			<tr style="background-color: #F6FAFD;">
				<?php if ($idItemTipo == COD_SERVICIO['id']) :  ?>
					<?php
					$cont = 0;
					$datos = [];
					?>
					<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
						<?php $datos[$value['sucursal'] . $value['razonSocial'] . $value['tipoElemento'] . $value['marca']][] = $value; ?>
					<?php endforeach; ?>
					<?php foreach ($datos as $key => $value) : ?>
						<?php $cont++ ?>
			<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= $cont; ?></td>
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= $value[0]['sucursal']; ?></td>
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= $value[0]['razonSocial']; ?></td>
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= $value[0]['tipoElemento']; ?></td>
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= $value[0]['marca']; ?></td>
				<td style='text-align: center;' rowspan="1"><?= $value[0]['nombre']; ?></td>
				<td style='text-align: center;' rowspan="1"><?= $value[0]['cantidad']; ?></td>
				<!-- <td style='text-align: center;' rowspan="1"><?= $value[0]['costo'] * ($row['gap'] + 100) / 100; ?></td> -->
				<td style='text-align: center;' rowspan="<?= count($value); ?>"><?= moneda($row['costo'] * ($row['gap'] + 100) / 100); ?></td>
			</tr>
			<?php foreach ($value as $k => $v) : ?>
				<?php if ($k != 0) :  ?>
					<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
						<td style='text-align: center;' rowspan="1"><?= $v['nombre']; ?></td>
						<td style='text-align: center;' rowspan="1"><?= $v['cantidad']; ?></td>
						<!-- <td style='text-align: center;' rowspan="1"><?= $v['costo'] * ($row['gap'] + 100) / 100; ?></td> -->
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($idItemTipo == COD_TRANSPORTE['id']) :  ?>
		<?php $rowspan = 1; ?>
		<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
			<td style="text-align: center;" rowspan="<?= count($detalleSub[$row['idCotizacionDetalle']]) + 1; ?>"><?= $key + 1 ?></td>
			<td class="bold" style="text-align: left; text-right bold;" rowspan="1"> <?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : $row['item']; ?> </td>
			<td style="text-align: right;" rowspan="<?= count($detalleSub[$row['idCotizacionDetalle']]) + 1; ?>"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
		</tr>
		<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $k => $v) : ?>
			<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
				<td style="text-align: left; text-right bold" rowspan="1"> <?= $v['nombre']; ?> </td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($idItemTipo == COD_DISTRIBUCION['id']) :  ?>
		<td style="text-align: center;"><?= $key + 1 ?></td>
		<td style="text-align: left;">
			<?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : $row['item'] ?>
		</td>
		<td style="text-align: right;"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
	<?php endif; ?>
	<?php if ($idItemTipo == COD_ARTICULO['id'] || $idItemTipo == COD_TEXTILES['id'] || $idItemTipo == COD_MOVIL['id']) :  ?>
		<tr style="background-color: #F6FAFD;">
			<td style="text-align: center;"><?= $key + 1 ?></td>
			<td style="text-align: left;" colspan="4">
				<?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : $row['item'] ?> <?= verificarEmpty($row['caracteristicas'], 1, '(', ')'); ?>
			</td>
			<td style="text-align: left;">
				<?= verificarEmpty($row['cantidad'], 1) ?>
			</td>
			<td style="text-align: right;">
				<?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?>
			</td>
		</tr>
		<?php if ($row['idItemTipo'] == COD_TEXTILES['id'] && count($detalleSub[$row['idCotizacionDetalle']]) > 0) :  ?>
			<?php $dataTextil = []; ?>
			<?php $dataTalla = []; ?>
			<?php $dataGenero = []; ?>
			<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $kt => $vt) : ?>
				<?php $dataTextil[$vt['talla']][$vt['genero']] = $vt; ?>
				<?php $dataGenero[$vt['genero']] = RESULT_GENERO[$vt['genero']]; ?>
				<?php $dataTalla[$vt['talla']] = $vt['talla']; ?>
			<?php endforeach; ?>
			<tr style="background-color: #F6FAFD;">
				<td></td>
				<td style="text-align: right;">Talla</td>
				<?php if (count($dataGenero) == 1) :  ?>
					<td colspan="3" style="text-align: center;">Cantidad</td>
				<?php else : ?>
					<?php foreach ($dataGenero as $kg => $vg) : ?>
						<td style="text-align: center;"><?= $vg; ?></td>
					<?php endforeach; ?>
					<?php if (3 - count($dataGenero) > 0) :  ?>
						<td colspan="<?= 3 - count($dataGenero); ?>"></td>
					<?php endif; ?>
				<?php endif; ?>
				<td></td>
				<td></td>
			</tr>
			<?php foreach ($dataTalla as $kt => $vt) : ?>
				<tr>
					<td></td>
					<td style="text-align: right;"><?= $vt; ?></td>
					<?php foreach ($dataGenero as $kg => $vg) : ?>
						<td style="text-align: center;"><?= verificarEmpty($dataTextil[$vt][$kg]['cantidad'], 2); ?></td>
					<?php endforeach; ?>
					<?php if (3 - count($dataGenero) > 0) :  ?>
						<td colspan="<?= 3 - count($dataGenero); ?>"></td>
					<?php endif; ?>
					<td></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
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
		<div>
			<label>
				<b>Solicitante: </b> <?= isset($cabecera['solicitante']) ? $cabecera['solicitante'] : ''; ?>
			</label>
		</div>



		<? if (!empty($anexos)) { ?>
			<h3>Anexos</h3>
			<div class="ui fluid image content-lsck-capturas" data-id="<?= $anexo['idCotizacionDetalleArchivo'] ?> " style="display: inline-block;">
				<? foreach ($anexos as $anexo) { ?>
					<a target="_blank" href="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>">
						<img height="520" src="<?= RUTA_WASABI . "cotizacion/{$anexo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
					</a>
				<? } ?>
			</div>
		<? } ?>