<?php $montoFee = 0; ?>
<?php $montoFeeTarjVales = 0; ?>
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
<?php $col1 = 0; ?>
<?php $montoSub = 0; ?>
<?php $totalPacking = 0; ?>
<table class="tb-detalle" style="width: 100%; margin-bottom: 10px;">
	<thead>
		<?php if ($detalle[0]['idItemTipo'] == COD_SERVICIO['id']) : ?>
			<?php $col1 = 7; ?>
			<tr style="background-color: #FFE598;">
				<th>ITEM</th>
				<th>SUCURSAL</th>
				<th>RAZON SOCIAL</th>
				<th>TIPO ELEMENTO</th>
				<th>MARCA</th>
				<th>DETALLES DE SERVICIO</th>
				<th>CANTIDAD</th>
				<?php if ($cabecera['mostrarPrecio']) : ?>
					<?php $col1++; ?>
					<th>COSTO</th>
				<?php endif; ?>
				<th>TOTAL</th>
			</tr>
		<?php endif; ?>
		<?php if ($detalle[0]['idItemTipo'] == COD_ARTICULO['id'] || $detalle[0]['idItemTipo'] == COD_TEXTILES['id'] || $detalle[0]['idItemTipo'] == COD_MOVIL['id'] || $detalle[0]['idItemTipo'] == COD_DISTRIBUCION['id'] || $detalle[0]['idItemTipo'] == COD_TRANSPORTE['id'] || $detalle[0]['idItemTipo'] == COD_TARJETAS_VALES['id'] || $detalle[0]['idItemTipo'] == COD_CONCURSO['id']) : ?>
			<?php $col1 = 6; ?>
			<tr style="background-color: #FFE598;">
				<th width="7%">ITEM</th>
				<th width="63%" class="text-left" colspan="4">DESCRIPCIÓN</th>
				<th width="15%" class="text-center">
					<?php if ($detalle[0]['idItemTipo'] != COD_DISTRIBUCION['id'] || ($detalle[0]['idItemTipo'] == COD_DISTRIBUCION['id'] && $cabecera['incluyeTransporte'])) : ?>
						CANTIDAD
					<?php endif; ?>
				</th>
				<th width="15%">TOTAL</th>
			</tr>
		<?php endif; ?>
	</thead>
	<tbody>
		<?php foreach ($detalle as $key => $row) : ?>
			<!-- Calcular Fee -->
			<?php $montoFee += floatval($row['montoFee']); ?>
			<!-- Calcular FeeTarjValConc -->
			<?php $montoFeeTarjVales += floatval($row['montoFeeTarjValCon']); ?>
			<?php if ($row['idItemTipo'] == COD_SERVICIO['id']) : ?>
				<?php
				$cont = 0;
				$datos = [];
				?>
				<?php $redondear = $row['flagRedondear'] == '1' ? true : false; ?>;
				<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $ord => $value) : ?>
					<?php $datos[$value['sucursal'] . $value['razonSocial'] . $value['tipoElemento'] . $value['marca']][] = $value; ?>
					<?php if ($redondear) : ?>
						<?php $total[$value['sucursal'] . $value['razonSocial'] . $value['tipoElemento'] . $value['marca']] += ceil(floatval($value['cantidad'] * $value['costo'] * ($row['gap'] + 100) / 100)); ?>
					<?php else : ?>
						<?php $total[$value['sucursal'] . $value['razonSocial'] . $value['tipoElemento'] . $value['marca']] += floatval($value['cantidad'] * $value['costo'] * ($row['gap'] + 100) / 100); ?>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php foreach ($datos as $key => $value) : ?>
					<?php $cont++ ?>
					<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
						<td class="text-center" rowspan="<?= count($value); ?>"><?= $cont; ?></td>
						<td class="text-center" rowspan="<?= count($value); ?>"><?= $value[0]['sucursal']; ?></td>
						<td class="text-center" rowspan="<?= count($value); ?>"><?= $value[0]['razonSocial']; ?></td>
						<td class="text-center" rowspan="<?= count($value); ?>"><?= $value[0]['tipoElemento']; ?></td>
						<td class="text-center" rowspan="<?= count($value); ?>"><?= $value[0]['marca']; ?></td>
						<td class="text-left" rowspan="1"><?= $value[0]['nombre']; ?></td>
						<td class="text-center" rowspan="1"><?= $value[0]['cantidad']; ?></td>
						<?php if ($cabecera['mostrarPrecio']) : ?>
							<?php if ($redondear) : ?>
								<td class="text-center" rowspan="1"><?= moneda(ceil($value[0]['costo'] * ($row['gap'] + 100) / 100)); ?></td>
							<?php else : ?>
								<td class="text-center" rowspan="1"><?= moneda($value[0]['costo'] * ($row['gap'] + 100) / 100); ?></td>
							<?php endif; ?>
						<?php endif; ?>
						<td class="text-center" rowspan="<?= count($value); ?>"><?= moneda($total[$key]); ?></td>
					</tr>
					<?php foreach ($value as $k => $v) : ?>
						<?php if ($k != 0) : ?>
							<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
								<td class="text-left" rowspan="1"><?= $v['nombre']; ?></td>
								<td class="text-center" rowspan="1"><?= $v['cantidad']; ?></td>
								<?php if ($cabecera['mostrarPrecio']) : ?>
									<?php if ($redondear) : ?>
										<td class="text-center" rowspan="1"><?= moneda(ceil($v['costo'] * ($row['gap'] + 100) / 100)); ?></td>
									<?php else : ?>
										<td class="text-center" rowspan="1"><?= moneda($v['costo'] * ($row['gap'] + 100) / 100); ?></td>
									<?php endif; ?>
								<?php endif; ?>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($row['idItemTipo'] == COD_PERSONAL['id']) : ?>
				<?php $rowspan = 1; ?>
				<tr style="background-color: #F6FAFD; border: 1px solid #cccccc; ">
					<td class="text-center"><?= $key + 1 ?></td>
					<td class="text-center bold"> <?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : 'Recursos: ' . $row['cantidad_personal'] . ' ' . $row['cargo'] . ' ' . $row['mesInicio']; ?> </td>
					<td class="text-right"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($row['idItemTipo'] == COD_TRANSPORTE['id']) : ?>
				<?php $rowspan = 1; ?>
				<?php $cantidadMoviles = 0; ?>
				<?php $cantidadDias = 0; ?>
				<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $k => $v) : ?>
					<?php $cantidadMoviles += $v['cantidad']; ?>
					<?php $cantidadDias += $v['dias']; ?>
				<?php endforeach; ?>
				<tr style="background-color: #F6FAFD;">
					<td class="text-center"><?= $key + 1 ?></td>
					<td class="text-left" colspan="4"> <?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : $row['item']; ?> </td>
					<td class="text-left" style="font-size: 11px;"> <?= $cantidadMoviles; ?> MOV. X <?= $cantidadDias; ?> DÍAS</td>
					<td class="text-right"><?= empty($row['subtotal']) ? "-" : moneda($row['subtotal']); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($row['idItemTipo'] == COD_ARTICULO['id'] || $row['idItemTipo'] == COD_TEXTILES['id'] || $row['idItemTipo'] == COD_MOVIL['id'] || $row['idItemTipo'] == COD_DISTRIBUCION['id'] || $row['idItemTipo'] == COD_TARJETAS_VALES['id'] || $row['idItemTipo'] == COD_CONCURSO['id']) : ?>
				<tr class="bg-gray">
					<td class="text-center"><?= $key + 1 ?></td>
					<td class="text-left" colspan="4">
						<?= $row['flagAlternativo'] ? $row['nombreAlternativo'] : $row['item'] ?> <?= verificarEmpty($row['caracteristicas'], 1, '(', ')'); ?>
					</td>
					<td class="text-center">
						<?php if ($row['idItemTipo'] != COD_DISTRIBUCION['id']) : ?>
							<?= verificarEmpty($row['cantidad'], 3) ?>
						<?php elseif ($row['idItemTipo'] == COD_DISTRIBUCION['id'] && $cabecera['incluyeTransporte']) : ?>
							-
						<?php endif; ?>
					</td>
					<td class="text-right">
						<?php if (!empty($row['costoPacking'])) : ?>
							<?php $totalPacking += 0; //floatval($row['costoPacking']); 
							?>
						<?php endif; ?>
						<?= empty($row['subtotal']) ? "-" : moneda(floatval($row['subtotal']) + 0); //floatval($row['costoPacking'])); 
						?>
					</td>
				</tr>
				<?php if ($row['idItemTipo'] == COD_TEXTILES['id'] && count($detalleSub[$row['idCotizacionDetalle']]) > 0) : ?>
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
						<td class="text-right bold">Talla</td>
						<?php if (count($dataGenero) == 1) : ?>
							<td colspan="3" class="text-center bold">Cantidad</td>
						<?php else : ?>
							<?php foreach ($dataGenero as $kg => $vg) : ?>
								<td class="text-center bold"><?= $vg; ?></td>
							<?php endforeach; ?>
							<?php if (3 - count($dataGenero) > 0) : ?>
								<td colspan="<?= 3 - count($dataGenero); ?>" class="bold"></td>
							<?php endif; ?>
						<?php endif; ?>
						<td></td>
						<td></td>
					</tr>
					<?php foreach ($dataTalla as $kt => $vt) : ?>
						<tr style="background-color: #F6FAFD;">
							<td></td>
							<td class="text-right"><?= $vt; ?></td>
							<?php foreach ($dataGenero as $kg => $vg) : ?>
								<td class="text-center"><?= verificarEmpty($dataTextil[$vt][$kg]['cantidad'], 2); ?></td>
							<?php endforeach; ?>
							<?php if (3 - count($dataGenero) > 0) : ?>
								<td colspan="<?= 3 - count($dataGenero); ?>"></td>
							<?php endif; ?>
							<td></td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($row['idItemTipo'] == COD_CONCURSO['id']) : ?>
				<?php foreach ($detalleSub[$row['idCotizacionDetalle']] as $dsK => $dsV) : ?>
					<tr class="bg-gray">
						<td class="text-center"></td>
						<td class="text-left" colspan="4">
							&nbsp; &nbsp; &nbsp;<?= $dsV['nombre'] ?>
						</td>
						<td class="text-center">
							<?= floatval($dsV['cantidad'])  ?>
						</td>
						<td class="text-right">
							<?= moneda(floatval($dsV['costo'])) ?>
						</td>
					</tr>
					<?php if (!empty($dsV['porcentajeParaCosto'])) : ?>
						<tr class="bg-gray">
							<td class="text-center"></td>
							<td class="text-left" colspan="4">
								&nbsp; &nbsp; &nbsp;Gasto administrativo concurso
							</td>
							<td class="text-center">
								-
							</td>
							<td class="text-right">
								<?= moneda(floatval($dsV['costo']) * floatval($dsV['porcentajeParaCosto']) / 100) ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php $montoSub += floatval($row['subtotal']); ?>
		<?php endforeach; ?>
	</tbody>
	<tfoot class="full-widtd">
		<tr class="height:100px" style="background-color: #FFE598;">
			<td colspan="<?= $col1; ?>" class="text-right bold" style="color:black">
				<p>SUB TOTAL</p>
			</td>
			<td class="text-right bold" style="color:black">
				<?php $montoSub += floatval($totalPacking) ?>
				<p><?= moneda($montoSub); ?></p>
			</td>
		</tr>
		<?php if (!empty($montoFee)) : ?>
			<tr class="height:100px" style="background-color: #F6FAFD;">
				<td colspan="<?= $col1; ?>" class="text-right bold">
					<p>FEE <?= !empty($cabecera['fee']) ? $cabecera['fee'] . '%' : '0%' ?></p>
				</td>
				<td class="text-right">
					<p><?= moneda($montoFee) ?></p>
				</td>
			</tr>
		<?php endif; ?>
		<?php if (!empty($montoFeeTarjVales)) : ?>
			<tr class="height:100px" style="background-color: #F6FAFD;">
				<td colspan="<?= $col1; ?>" class="text-right bold">
					<p>FEE <?= !empty($cabecera['feeTarjetaVales']) ? $cabecera['feeTarjetaVales'] . '%' : '0%' ?></p>
				</td>
				<td class="text-right">
					<p><?= moneda($montoFeeTarjVales) ?></p>
				</td>
			</tr>
		<?php endif; ?>
		<tr class="height:100px" style="background-color: #FFE598;">
			<td colspan="<?= $col1; ?>" class="text-right bold" style="color:black">
				<p>TOTAL</p>
			</td>
			<td class="text-right bold" style="color:black">
				<p>
					<?=
						// moneda(floatval($montoSub) + floatval($cabecera['fee_prc']));
						moneda($cabecera['total_fee'])
					?>
				</p>
			</td>
		</tr>
	</tfoot>
</table>
<div>
	<table>
		<tr>
			<td style="height: 20px;"><b>Fecha Vigencia: </b></td>
			<td><?= date_change_format($cabecera['fechaValido']); ?></td>
		</tr>
	</table>
</div>
<div>
	<label>
		<?= isset($cabecera['comentario']) ? $cabecera['comentario'] : ''; ?>
	</label>
</div>
<?php if (!empty($detalleSubT)) : ?>
	<table id="customers">
		<thead>
			<tr>
				<th>DEPARTAMENTO</th>
				<th>PROVINCIA</th>
				<th>DÍAS</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($detalleSubT as $idCD => $dataD) : ?>
				<?php $dp = '' ?>
				<?php $pr = '' ?>
				<?php $totalD = 0 ?>
				<?php foreach ($dataD as $k => $v) : ?>
					<?php if ($dp != $v['cod_departamento'] || $pr != $v['cod_provincia']) : ?>
						<?php if ($k != 0) : ?>
							<tr>
								<td><?= $zonas[$dp][$pr]['departamento']; ?></td>
								<td><?= $zonas[$dp][$pr]['provincia']; ?></td>
								<td><?= $totalD; ?></td>
							</tr>
						<?php endif; ?>
						<?php $dp = $v['cod_departamento']; ?>
						<?php $pr = $v['cod_provincia']; ?>
						<?php $totalD = 0 ?>
					<?php endif; ?>
					<?php $totalD += intval($v['dias']) ?>
				<?php endforeach; ?>
				<tr>
					<td><?= $zonas[$dp][$pr]['departamento']; ?></td>
					<td><?= $zonas[$dp][$pr]['provincia']; ?></td>
					<td><?= $totalD; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php if (!empty($detalleDistribucionZonas)) : ?>
	<?php foreach ($detalle as $kd => $vd) : ?>
		<?php if ($vd['idItemTipo'] == COD_DISTRIBUCION['id'] && $vd['flagMostrarDetalle'] == '1') : ?>
			<table id="customers">
				<thead>
					<tr>
						<th colspan="2">PRODUCTO</th>
						<?php $tot = []; ?>
						<?php foreach ($detalleDistribucionItems[$vd['idCotizacionDetalle']] as $ki => $vi) : ?>
							<th>
								<?= $vi[0]['itemNombre']; ?>
							</th>
							<?php $tot[$ki] = 0; ?>
							<?php foreach ($vi as $v_) : ?>
								<?php $tot[$ki] += floatval($v_['cantidad']); ?>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<th rowspan="2"> PESO TOTAL </th>
						<th rowspan="2"> TIPO </th>
					</tr>
					<tr>
						<th colspan="2">CANTIDAD</th>
						<?php foreach ($tot as $valueT) : ?>
							<th class="text-right"><?= $valueT; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<?php foreach ($detalleDistribucionZonas[$vd['idCotizacionDetalle']] as $kf => $vf) : ?>
					<?php $totZC[$kf] = 0; ?>
					<?php $totZP[$kf] = 0; ?>
					<?php foreach ($vf as $v_) : ?>
						<?php $totZC[$kf] += floatval($v_['cantidad']); ?>
						<?php $totZP[$kf] += (floatval($v_['peso']) * floatval($v_['cantidad']) * (100 + floatval($v_['gap'])) / 100); ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
				<tbody>
					<?php foreach ($detalleDistribucionZonas[$vd['idCotizacionDetalle']] as $kf => $vf) : ?>
						<tr>
							<td>
								<?= $vf[0]['zonaNombre']; ?>
							</td>
							<td>
								<?= $totZC[$kf]; ?>
							</td>
							<?php foreach ($vf as $valueF) : ?>
								<td class="text-right">
									<?= $valueF['cantidad']; ?>
								</td>
							<?php endforeach; ?>
							<td class="text-right">
								<?= $totZP[$kf]; ?>
							</td>
							<td>
								<?= $vf[0]['tipoServicioNombre']; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
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