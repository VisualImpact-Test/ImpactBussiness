<?php $dataRow = 0; ?>
<?php foreach ($fechaDelPre as $k => $v) : 
$anio = date('Y', strtotime($v['fecha'])); 
$mes = date('n', strtotime($v['fecha']));
if (!isset($contadorMeses[$anio])) { $contadorMeses[$anio] = array_fill(1, 12, 0); }
$contadorMeses[$anio][$mes]++; 
endforeach;  ?>
<form class="form" role="form" id="formEditarPresupuesto" method="post" autoComplete="off">
	
	<input type="hidden" id="idCuenta" value="<?= $idCuenta; ?>">
	<div class="row pt-4">
		<?php $cantidadCargo = 0; ?>
		<?php foreach ($fechaDelPre as $key => $value) : ?>
			<?php foreach ($cargoDelPre as $k => $v) : ?>
				<?php $cantidadCargo += intval($v['cantidad']); ?>
			<?php endforeach; ?>
			<?php break; ?>
		<?php endforeach; ?>
		<div class="col-md-11 child-divcenter" style="width: 100%">
			<input type="hidden" name="idOrdenServicio" value="<?= $presupuesto['idOrdenServicio']; ?>">
			<input type="hidden" name="idPresupuesto" value="<?= $presupuesto['idPresupuesto']; ?>">
			<div class="ui top attached tabular menu">
				<a class="item active" data-tab="datos">Datos</a>
				<?php foreach ($presupuestoDetalle as $kd => $vd) : ?>
					<input class="idTP" type="hidden" value="<?= $vd['idTipoPresupuesto']; ?>">
					<a class="tabTiposPresupuestos item" data-tab="<?= $vd['idTipoPresupuesto']; ?>"><?= $vd['tipoPresupuesto']; ?></a>
				<?php endforeach; ?>
			</div>
			<div class="ui bottom attached tab segment active" data-tab="datos">
				<div id="divTabla" class="ui table">
					<table class="ui sortable table" id="tablaFechaPersona">
					<thead>
						<tr><th rowspan = "2" class="three wide p-0 "><label class="text-white">________________</label></th>
						<?php
							foreach ($contadorMeses as $anio => $meses) {
								$totalMeses = array_sum($meses); ?>
								<th  class ="one wide p-0 " colspan="<?php echo $totalMeses ?>" style="text-align: center;" ><?php echo $anio ?></th>
							<?php } ?>
							<th class ="one wide p-0 " rowspan = "2"></th>
							</tr>
							<tr>
								<?php foreach ($fechaDelPre as $k => $v) : ?>
									<?php $numeroMes = date('n', strtotime($v['fecha'])); ?>
									
									<th class="one wide p-0 ">
										<div class="ui input transparent">
											<input type="hidden" name="fechaList" value="<?= strpos($v['fecha'], '-') ? date_change_format($v['fecha']) : $v['fecha']; ?>" class="form-control text-center" patron="requerido" readonly>
											<input type="text" class="form-control text-center" value="<?= NOMBRE_MES_REDU[$numeroMes];?>">
											
										</div>
									</th>
								<?php endforeach; ?>
							
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($cargoDelPre as $kp => $vp) : ?>
								<tr>
									<td class="three wide"><?= $vp['cargo']; ?></td>
									<?php foreach ($fechaDelPre as $kf => $vf) : ?>
										<td class="one wide">
											<div class="ui input">
												<input type="text" name="cantidadCargoFecha[<?= $vp['idCargo'] ?>][<?= $kf ?>]" value="<?= $presupuestoCargo[$vf['fecha']][$vp['idCargo']]['cantidad']; ?>" class="form-control text-center keyUpChange cntColmFC <?= $kf == 0 ? 'cloneAll' : ('cloned' . $kp) ?>" <?php if ($kf == 0) : ?> id="cargoCantidad_<?= $kp ?>" <?php endif; ?> data-personal="<?= $kp ?>" patron="requerido">
											</div>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php foreach ($presupuestoDetalle as $kd => $vd) : ?>
					<input type="hidden" name="idTipoPresupuesto" value="<?= $vd['idTipoPresupuesto']; ?>">
					<div class="ui very compact table">
						<table class="ui table no-paddingR" id="tb_LD<?= $vd['idTipoPresupuesto'] ?>">
							<thead>
								<tr>
									<th class="three wide"><?= $vd['tipoPresupuesto']; ?></th>
									<?php foreach ($fechaDelPre as $kf => $vf) : ?>
										<th class="one wide pr-0">
											<div class="ui input transparent fluid">
												<input class="text-right" type="text" value=" - " readonly id="totalColumna_<?= $vd['idTipoPresupuesto'] ?>_<?= $kf ?>">
											</div>
										</th>
									<?php endforeach; ?>
									<th class="one wide text-right">
										TOTAL
										<input id="totPresupuesto_<?= $vd['idTipoPresupuesto'] ?>" type="text" name="totalPorPresupuesto" value="0">
									</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($vd['idTipoPresupuesto'] == COD_SUELDO) : ?>
									<?php foreach ($cargoDelPre as $k => $v) : ?>
										<tr>
											<td><?= $v['cargo']; ?></td>
											<input type="hidden" name="cargoList" value="<?= $v['idCargo'] ?>">
											<?php foreach ($fechaDelPre as $kf => $vf) : ?>
												<td>
													<div class="ui input transparent fluid">
														<input class="text-right" type="text" value="0" readonly id="montoSueldo_<?= $k ?>_<?= $kf ?>">
													</div>
												</td>
											<?php endforeach; ?>
											<td>
												<div class="ui input transparent fluid">
													<input class="text-right" type="text" value="0" readonly id="totalLineaSueldo_<?= $k ?>">
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
									<tr>
										<td> INCENTIVO </td>
										<?php foreach ($fechaDelPre as $kf => $vf) : ?>
											<td>
												<div class="ui input transparent fluid">
													<input class="text-right" type="text" value="0" readonly id="montoIncentivo_<?= $kf ?>">
												</div>
											</td>
										<?php endforeach; ?>
										<td>
											<div class="ui input transparent fluid">
												<input class="text-right" type="text" value="0" readonly id="totalLineaIncentivo" data-detalle="<?= $vd['idTipoPresupuesto'] ?>" onchange="OrdenServicio.calcularTotalColumnaSueldo(this);">
											</div>
										</td>
									</tr>
								<?php elseif ($vd['idTipoPresupuesto'] == COD_MOVILIDAD) : ?>
									<tr>
										<td> VIAJES SUPERVISIÓN </td>
										<?php foreach ($fechaDelPre as $kf => $vf) : ?>
											<td>
												<div class="ui input transparent fluid">
													<input class="text-right" type="text" value="0" readonly id="movilidadViajes_<?= $kf ?>">
												</div>
											</td>
										<?php endforeach; ?>
										<td>
											<div class="ui input transparent fluid">
												<input class="text-right" type="text" value="0" readonly id="totalMovilidadViajes">
											</div>
										</td>
									</tr>
									<tr>
										<td> ADICIONALES </td>
										<?php foreach ($fechaDelPre as $kf => $vf) : ?>
											<td>
												<div class="ui input transparent fluid">
													<input class="text-right" type="text" value="0" readonly id="movilidadAdicionales_<?= $kf ?>">
												</div>
											</td>
										<?php endforeach; ?>
										<td>
											<div class="ui input transparent fluid">
												<input class="text-right" type="text" value="0" readonly id="totalMovilidadAdicional">
											</div>
										</td>
									</tr>
								<?php elseif ($vd['idTipoPresupuesto'] == COD_ALMACEN) : ?>
									<!-- -->
								<?php else : ?>
									<?php if (!empty($presupuestoDetalleSub[$vd['idPresupuestoDetalle']])) : ?>
										<?php foreach ($presupuestoDetalleSub[$vd['idPresupuestoDetalle']] as $kLDS => $vLDS) : ?>
											<tr class="dataItem">
												<td><?= $vLDS['nombre']; ?></td>
												<?php foreach ($fechaDelPre as $kf => $vf) : ?>
													<td>
														<div class="ui input transparent fluid">
															<input class="text-right" type="text" value="0" readonly id="montoLDS_<?= $vd['idTipoPresupuesto'] ?>_<?= $kLDS ?>_<?= $kf ?>">
														</div>
													</td>
												<?php endforeach; ?>
												<td>
													<div class="ui input fluid transparent">
														<input class="text-right totalFila" type="text" value="0" readonly id="totalLineaDS_<?= $vd['idTipoPresupuesto'] ?>_<?= $kLDS ?>" data-detalle="<?= $vd['idTipoPresupuesto'] ?>" onchange="OrdenServicio.calcularTotalColumna(this);">
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
										<?php if ($vd['idTipoPresupuesto'] == COD_GASTOSADMINISTRATIVOS && $presupuesto['sctr'] !== NULL) :  ?>
											<tr>
												<td>SCTR</td>
												<?php foreach ($fechaDelPre as $kf => $vf) : ?>
													<td>
														<div class="ui input transparent fluid">
															<input class="text-right inputSctr" type="text" data-sctr="<?= $kLDS + 1 ?>" value="0" readonly id="montoLDS_<?= $vd['idTipoPresupuesto'] ?>_<?= $kLDS + 1 ?>_<?= $kf ?>">
														</div>
													</td>
												<?php endforeach; ?>
												<td>
													<div class="ui input fluid transparent">
														<input class="text-right totalFila" type="text" value="0" readonly id="totalLineaDS_<?= $vd['idTipoPresupuesto'] ?>_<?= $kLDS + 1 ?>" data-detalle="<?= $vd['idTipoPresupuesto'] ?>" onchange="OrdenServicio.calcularTotalColumna(this);">
													</div>
												</td>
											</tr>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endforeach; ?>
				<div class="ui table">
					<table class="ui celled striped table">
						<tbody>
							<tr>
								<td class="font-weight-bolder text-right three wide">SubTotal</td>
								<?php foreach ($fechaDelPre as $kc => $vc) : ?>
									<td class="one wide">
										<div class="ui input fluid">
											<input class="text-right" value="0" id="subtotalFinal_<?= $kc ?>" readonly>
										</div>
									</td>
								<?php endforeach; ?>
								<td class="one wide p-0">
									<div class="ui left corner labeled input fluid small">
										<input class="text-right" value="0" id="sumaSubtotalFinal" name="presupuestoSubTotal" readonly>
										<div class="ui left corner label">
											<i class="equals icon"></i>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="font-weight-bolder text-right">
									<div class="ui labeled input">
										<div class="ui label">FEE</div>
										<input class="fee1V text-right keyUpChange onlyNumbers" name="presupuestoFee1" value="<?= $presupuesto['fee1']; ?>" style="width: 70px;" onchange="OrdenServicio.calcularTotalFinal()">
										<div class="ui label">%</div>
									</div>
								</td>
								<?php foreach ($fechaDelPre as $kc => $vc) : ?>
									<td class="one wide">
										<div class="ui input fluid">
											<input class="text-right" value="0" id="fee1_<?= $kc ?>" readonly>
										</div>
									</td>
								<?php endforeach; ?>
								<td class="one wide p-0">
									<div class="ui left corner labeled input fluid small">
										<input class="text-right " value="0" id="sumaFee1Final" name="presupuestoTotalFee1" readonly>
										<div class="ui left corner label">
											<i class="equals icon"></i>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="font-weight-bolder text-right">
									<div class="ui labeled input">
										<div class="ui label">FEE</div>
										<input class="fee2V text-right keyUpChange onlyNumbers" name="presupuestoFee2" value="<?= $presupuesto['fee2']; ?>" style="width: 70px;" onchange="OrdenServicio.calcularTotalFinal()">
										<div class="ui label">%</div>
									</div>
								</td>
								<?php foreach ($fechaDelPre as $kc => $vc) : ?>
									<td class="one wide">
										<div class="ui input fluid">
											<input class="text-right" value="0" id="fee2_<?= $kc ?>" readonly>
										</div>
									</td>
								<?php endforeach; ?>
								<td class="one wide p-0">
									<div class="ui left corner labeled input fluid small">
										<input class="text-right" value="0" id="sumaFee2Final" name="presupuestoTotalFee2" readonly>
										<div class="ui left corner label">
											<i class="equals icon"></i>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="font-weight-bolder text-right">
									<div class="ui labeled input">
										<div class="ui label">FEE</div>
										<input class="fee3V text-right keyUpChange onlyNumbers" name="presupuestoFee3" value="<?= $presupuesto['fee3']; ?>" style="width: 70px;" onchange="OrdenServicio.calcularTotalFinal()">
										<div class="ui label">%</div>
									</div>
								</td>
								<?php foreach ($fechaDelPre as $kc => $vc) : ?>
									<td class="one wide">
										<div class="ui input fluid">
											<input class="text-right" value="0" id="fee3_<?= $kc ?>" readonly>
										</div>
									</td>
								<?php endforeach; ?>
								<td class="one wide p-0">
									<div class="ui left corner labeled input fluid small">
										<input class="text-right" value="0" id="sumaFee3Final" name="presupuestoTotalFee3" readonly>
										<div class="ui left corner label">
											<i class="equals icon"></i>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class="font-weight-bolder text-right">Total</td>
								<?php foreach ($fechaDelPre as $kc => $vc) : ?>
									<td class="one wide">
										<div class="ui input fluid">
											<input class="text-right" value="0" id="totalFinal_<?= $kc ?>" readonly>
										</div>
									</td>
								<?php endforeach; ?>
								<td class="one wide p-0">
									<div class="ui left corner labeled input fluid small">
										<input class="text-right" value="0" id="sumaTotalFinal" name="presupuestoTotal" readonly>
										<div class="ui left corner label">
											<i class="equals icon"></i>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<?php foreach ($presupuestoDetalle as $kd => $vd) : ?>
				<div class="ui bottom attached tab segment" data-tab="<?= $vd['idTipoPresupuesto']; ?>">
					<?php if ($vd['idTipoPresupuesto'] == COD_SUELDO) : ?>
						<div class="ui top attached tabular menu">
							<a class="item active" data-tab="<?= $vd['idTipoPresupuesto']; ?>/a">POR CARGO</a>
							<a class="item" data-tab="<?= $vd['idTipoPresupuesto']; ?>/b">ADICIONAL</a>
						</div>
						<div class="ui bottom attached tab segment active" data-tab="<?= $vd['idTipoPresupuesto']; ?>/a">
							<div class="control-group child-divcenter col-md-11" style="width:100%">
								<table class="ui table" id="tablaSueldo" data-personal="<?= count($cargoDelPre); ?>">
									<thead>
										<tr>
											<th class="two wide">Sueldos</th>
											<th class="one wide">% CL</th>
											<?php foreach ($cargoDelPre as $kp => $vp) : ?>
												<th><?= $vp['cargo']; ?></th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($presupuestoDetalleSueldo[$vd['idPresupuestoDetalle']] as $k1 => $v1) : ?>
											<?php $preDetSu = $v1[$idCargoRef] ?>
											<?php if ($preDetSu['tipo'] != 4) : ?>
												<tr data-row="<?= $dataRow ?>">
													<td>
														<input class="form-control tipoSueldo" type="hidden" value="<?= $preDetSu['tipo'] ?>" id="rowTipo_Sueldo<?= $dataRow ?>">
														<select class="ui search dropdown disabled toast semantic-dropdown cboSueldo" name="tpdS">
															<option value="">Sueldo</option>
															<?php foreach ($tipoPresupuestoDetalle[$vd['idTipoPresupuesto']] as $k2 => $v2) : ?>
																<?php if ($v2['tipo'] != 4) : ?>
																	<option value="<?= $v2['idTipoPresupuestoDetalle']; ?>" data-tipo="<?= $v2['tipo']; ?>" data-cl="<?= $v2['porCl']; ?>" <?= $v2['idTipoPresupuestoDetalle'] == $preDetSu['idTipoPresupuestoDetalle'] ? 'selected' : '' ?>><?= $v2['nombre']; ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</td>
													<td>
														<div class="ui right labeled input d-none">
															<input class="porCL" value="<?= $preDetSu['porCL']; ?>" name="clS">
															<div class="ui basic label"> % </div>
														</div>
														<?php if ($preDetSu['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR) : ?>
															<div class="ui right labeled input">
																<input value="<?= $valorPorcentual; ?>" readonly>
																<div class="ui basic label"> % </div>
															</div>
														<?php endif; ?>
													</td>
													<?php foreach ($cargoDelPre as $kp => $vp) : ?>
														<td>
															<input class="form-control text-right keyUpChange" name="monto[<?= $vp['idCargo'] ?>]" data-persona="<?= $kp ?>" id="rowMonto_Sueldo<?= $dataRow ?>-<?= $kp ?>" value="<?= $v1[$vp['idCargo']]['monto'] ?>" onchange="OrdenServicio.calcularTablaSueldo()" <?= $v1[$vp['idCargo']]['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR ? 'readonly' : ''; ?>>
															<?php if ($v1[$vp['idCargo']]['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR) :  ?>
																<input type="hidden" id="restoSueldoMinimo" value="<?= (floatval($sueldoMinimo) * 0.1) - floatval($v1[$vp['idCargo']]['monto']) ?>">
															<?php endif; ?>
														</td>

													<?php endforeach; ?>
												</tr>
												<?php $dataRow++; ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<?php foreach ($cargoDelPre as $kp => $vp) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" type="text" id="sTotalSueldo_<?= $kp ?>" value="" readonly>
													</div>
												</td>
											<?php endforeach; ?>
										</tr>
										<?php $totalPorcentaje = 0; ?>
										<?php foreach ($presupuestoDetalleSueldo[$vd['idPresupuestoDetalle']] as $k1 => $v1) : ?>
											<?php $preDetSu = $v1[$idCargoRef] ?>
											<?php if ($preDetSu['tipo'] == 4) : ?>
												<?php $totalPorcentaje += floatval($preDetSu['porCL']); ?>
												<tr data-row="<?= $dataRow; ?>">
													<td style="background: #fff">
														<input class="form-control beneficioSueldo" type="hidden" value="<?= $preDetSu['tipo'] ?>" id="rowBeneficio_Sueldo<?= $dataRow ?>">
														<select class="ui search dropdown semantic-dropdown disabled toast cboSueldo" name="tpdS">
															<option value="">Sueldo</option>
															<?php foreach ($tipoPresupuestoDetalle[$vd['idTipoPresupuesto']] as $k2 => $v2) : ?>
																<?php if ($v2['tipo'] == 4) : ?>
																	<option value="<?= $v2['idTipoPresupuestoDetalle']; ?>" data-tipo="<?= $v2['tipo']; ?>" data-cl="<?= $v2['porCl']; ?>" <?= $v2['idTipoPresupuestoDetalle'] == $preDetSu['idTipoPresupuestoDetalle'] ? 'selected' : '' ?>><?= $v2['nombre']; ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</td>
													<td style="background: #fff">
														<div class="ui right labeled input">
															<input class="porCL" value="<?= $preDetSu['porCL']; ?>" id="rowPorCL_Sueldo<?= $dataRow ?>" readonly name="clS">
															<div class="ui basic label"> % </div>
														</div>
													</td>
													<?php foreach ($cargoDelPre as $kp => $vp) : ?>
														<td style="background: #fff">
															<div class="ui transparent input">
																<input class="form-control text-right" name="monto[<?= $vp['idCargo'] ?>]" data-persona="<?= $kp ?>" id="rowMontoBeneficio_Sueldo<?= $dataRow ?>_<?= $kp ?>" value="<?= $v1[$vp['idCargo']]['monto'] ?>" readonly>
															</div>
														</td>
													<?php endforeach; ?>
													<!-- <a class="ui button red" onclick="$(this).parent('td').parent('tr').remove();"><i class="trash icon"></i></a> -->
												</tr>
												<?php $dataRow++; ?>
											<?php endif; ?>
										<?php endforeach; ?>
										<tr>
											<td>
												<a class="ui button teal d-none" id="calculateTablaSueldo" onclick="OrdenServicio.calcularTablaSueldo();"><i class="refresh icon"></i></a>
											</td>
											<td><label id="totalPorcentaje"><?= $totalPorcentaje; ?></label> %</td>
											<?php foreach ($cargoDelPre as $kp => $vp) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" type="text" id="totalSueldo_<?= $kp ?>" value="" readonly>
													</div>
												</td>
											<?php endforeach; ?>
										</tr>
									</tfoot>
								</table>
								<table class="ui definition table">
									<thead>
										<tr>
											<th></th>
											<?php foreach ($cargoDelPre as $k => $v) : ?>
												<th>
													<?= $v['cargo']; ?>
												</th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Sueldo</td>
											<?php foreach ($cargoDelPre as $k => $v) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" id="txtSueldo_<?= $k ?>">
													</div>
												</td>
											<?php endforeach; ?>
										</tr>
										<tr>
											<td>Incentivo</td>
											<?php foreach ($cargoDelPre as $k => $v) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" id="txtIncentivo_<?= $k ?>">
													</div>
												</td>
											<?php endforeach; ?>
										</tr>
										<tr>
											<td>Sueldo Total</label></td>
											<?php foreach ($cargoDelPre as $k => $v) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" id="txtSueldoCantidad_<?= $k ?>" readonly>
													</div>
													<label>/ &nbsp;</label>
													<label id="cantidadSueldo_<?= $k ?>"><?= $v['cantidad']; ?></label>
													<label> &nbsp; <i class="fa fa-sm fa-user"></i></label>
												</td>
											<?php endforeach; ?>
										</tr>
										<tr>
											<td>Incentivo Total</td>
											<?php foreach ($cargoDelPre as $k => $v) : ?>
												<td>
													<div class="ui transparent input">
														<input class="text-right" id="txtIncentivoCantidad_<?= $k ?>" readonly>
													</div>
													<label>/ &nbsp;</label>
													<label id="cantidadIncentivo_<?= $k ?>"><?= $v['cantidad']; ?></label>
													<label> &nbsp; <i class="fa fa-sm fa-user"></i></label>
												</td>
											<?php endforeach; ?>
										</tr>
										<?php if ($presupuesto['sctr'] !== NULL) : ?>
											<tr>
												<td>SCTR</td>
												<?php foreach ($cargoDelPre as $k => $v) : ?>
													<td>
														<div class="ui transparent input">
															<input class="text-right" id="txtSctr_<?= $k ?>" readonly>
														</div>
													</td>
												<?php endforeach; ?>
											</tr>
										<?php endif; ?>
										<tr>
											<td>Incentivo Adicional</td>
											<td colspan="<?= count($cargoDelPre); ?>">
												<div class="ui transparent input">
													<input class="text-right" id="txtIncentivoAdicionalTotal" value="0.00">
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="ui bottom attached tab segment" data-tab="<?= $vd['idTipoPresupuesto']; ?>/b">
							<div class="control-group child-divcenter col-md-8" style="width:100%">
								<a class="ui button floated green" onclick="OrdenServicio.addSueldoCargoAdicional();"><i class="icon plus"></i> Agregar</a>
								<table class="ui table" id="tablaSueldoAdicional">
									<thead>
										<tr>
											<th class="four wide column">
												<label class="ui left floated" style="font-size: 1em; vertical-align: middle; margin-bottom: 0px; padding: 11px 21px 11px 21px;">Cargo</label>
											</th>
											<th>Personal</th>
											<th>Monto</th>
											<th>Movilidad</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($presupuestoDetalleSueldoAdicional as $dsa) : ?>
											<tr>
												<td>
													<select class="ui dropdown clearable semantic-dropdown parentDependienteSemantic fluid" patron="requerido" name="cargoSueldoAdicional" data-childDependiente=".cboPersonal" data-closest="tr">
														<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cargos, 'id' => 'idCargoTrabajo', 'value' => 'cargo', 'selected' => $dsa['idCargo'], 'simple' => true, 'class' => 'text-titlecase']); ?>
													</select>
												</td>
												<td>
													<select class="ui dropdown clearable semantic-dropdown read-only childdependienteSemantic fluid cboPersonal" patron="requerido" name="empleadoSueldoAdicional">
														<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $empleados, 'selected' => $dsa['idEmpleado'], 'simple' => true, 'class' => 'text-titlecase']); ?>
													</select>
												</td>
												<td>
													<div class="ui input">
														<input class="onlyNumbers keyUpChange montoSueldoAdicional" type="text" value="<?= verificarEmpty($dsa['monto'], 2); ?>" patron="requerido" name="montoSueldoAdicional" onchange="$('#calculateTablaSueldo').click();">
													</div>
												</td>
												<td>
													<div class="ui input">
														<input class="onlyNumbers keyUpChange movilidadSueldoAdicional" type="text" value="<?= verificarEmpty($dsa['montoMovilidad'], 2); ?>" name="movilidadSueldoAdicional" onchange="OrdenServicio.calcularMovilidad();">
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php elseif ($vd['idTipoPresupuesto'] == COD_MOVILIDAD) : ?>
					
						<div style="display: flex;flex-direction: row-reverse;">
						<a class="ui whatsapp button"  onclick="OrdenServicio.listado_movilidad();">Listado</a>
						<a class="ui blue button"  onclick="OrdenServicio.agregar_movilidad();">Agregar</a></div>
						<div class="ui table">
						
							<table class="ui celled table" id="tablaMovilidad" data-personal="<?= count($cargoDelPre); ?>">
								<thead>
									<tr>
										<th class="two wide">Origen</th>
										<th class="two wide">Destino</th>
										<th class="two wide">Split</th>
										<th class="one wide">Días</th>
										<th class="one wide">Prec Bus</th>
										<th class="one wide">Prec Hospedaje</th>
										<th class="one wide">Prec Viaticos</th>
										<th class="one wide">Prec Movilidad Interna</th>
										<th class="one wide">Prec Taxi</th>
										<th class="one wide">SubTotal</th>
										<th class="one wide">Frecuencia</th>
										<th class="two wide">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tipoPresupuestoDetalleMovilidad as $km => $vm) : ?>
										<tr class="data">
											<td>
												<div class="ui input fluid">
													<input type="hidden" name="movIdTPDM" value="<?= $vm['idTipoPresupuestoDetalleMovilidad']; ?>">
													<input class="tbMov_origen" value="<?= $vm['origen']; ?>" name="movOrigen">
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_destino" value="<?= $vm['destino']; ?>" name="movDestino">
												</div>
											</td>
											<td>
												<select class="tbMov_freOpc ui compact fluid selection semantic-dropdown dropdown" name="movFrecuenciaOpc" onchange="OrdenServicio.calcularTotalesMovilidad();">
													<option <?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split']) ? ($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split'] == '1' ? 'selected' : '') : 'selected'; ?> value="1">1 vez por mes</option>
													<option <?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split']) ? ($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split'] == '2' ? 'selected' : '') : ''; ?> value="2">1 vez cada 2 meses</option>
													<option <?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split']) ? ($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['split'] == '3' ? 'selected' : '') : ''; ?> value="3">1 vez cada 3 meses</option>
												</select>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_dias text-right keyUpChange onlyNumbers" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['dias'] : '0'; ?>" name="movDias" onchange="OrdenServicio.calcularTotalesMovilidad();">
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_bus text-right" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['precioBus'] : '0'; ?>" name="movPrecBus" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_hosp text-right" data-costobase="<?= $vm['precioHospedaje']; ?>" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['precioHospedaje'] : '0'; ?>" name="movPrecHosp" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_viat text-right" data-costobase="<?= $vm['precioViaticos']; ?>" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['precioViaticos'] : '0'; ?>" name="movPrecViaticos" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_movInt text-right" data-costobase="<?= $vm['precioMovilidadInterna']; ?>" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['precioMovilidadInterna'] : '0'; ?>" name="movPrecMovInt" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_taxi text-right" data-costobase="<?= $vm['precioTaxi']; ?>" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['precioTaxi'] : '0'; ?>" name="movPrecTaxi" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_sbto text-right" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['subtotal'] : '0'; ?>" name="movSubTotal" readonly>
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_fre text-right keyUpChange onlyNumbers" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['frecuencia'] : '1'; ?>" name="movFrecuenciaCnt" onchange="OrdenServicio.calcularTotalesMovilidad();">
												</div>
											</td>
											<td>
												<div class="ui input fluid">
													<input class="tbMov_tot text-right" value="<?= isset($presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]) ? $presupuestoDetalleMovilidad[$vm['idTipoPresupuestoDetalleMovilidad']]['total'] : '0'; ?>" name="movTotal" readonly>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="11"></td>
										<td>
											<div class="ui transparent input fluid">
												<input class="text-right" type="text" id="totalTbMovilidad" value="0" readonly>
											</div>
										</td>
									</tr>
								</tfoot>
							</table>

							<table class="ui table" id="tbResumenMovilidad">
								<thead>
									<tr>
										<th></th>
										<?php foreach ($fechaDelPre as $key => $v) : ?>
											<th class="text-center"><?= strpos($v['fecha'], '-') ? date_change_format($v['fecha']) : $v['fecha']; ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					<?php elseif ($vd['idTipoPresupuesto'] == COD_ALMACEN) : ?>
						<div style="display: flex;flex-direction: row-reverse;">
							<a class="ui whatsapp button"  onclick="OrdenServicio.listado_almacen();">Listado</a>
							<a class="ui blue button"  onclick="OrdenServicio.agregar_almacen();">Agregar</a>
						</div>
						<div class="ui top attached tabular menu">
							<a class="item active" data-tab="<?= $vd['idTipoPresupuesto']; ?>/a">RECURSOS</a>
							<a class="item" data-tab="<?= $vd['idTipoPresupuesto']; ?>/b">MONTO</a>
						</div>
						
						
						<div class="ui bottom attached tab segment active" data-tab="<?= $vd['idTipoPresupuesto']; ?>/a">
							<div class="ui table">
								<table class="ui celled table" id="tablaAlmacen">
									<thead>
										<tr>
											<th class="one wide">Zona</th>
											<th class="one wide">Zona 2</th>
											<th class="two wide">Ciudad</th>
											<?php foreach ($fechaDelPre as $vtad) : ?>
												<th class="one wide"># Recursos <?= strpos($vtad['fecha'], '-') ? date_change_format($vtad['fecha']) : $vtad['fecha']; ?></th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($tipoPresupuestoDetalleAlmacen as $va) : ?>
											<tr class="data">
												<td>
													<input type="hidden" name="almIdTPDAR" value="<?= $va['idTipoPresupuestoDetalleAlmacen']; ?>" readonly>
													<?= $va['zona']; ?>
												</td>
												<td>
													<?= $va['zona2']; ?>
												</td>
												<td>
													<?= $va['ciudad']; ?>
												</td>
												<?php foreach ($fechaDelPre as $kF => $vF) : ?>
													<td>
														<div class="ui input fluid">
															<input class="tbAlm_recursos text-right keyUpChange onlyNumbers" value="<?= isset($dataTPDARecursos[$va['idTipoPresupuestoDetalleAlmacen']][$kF]['cantidad']) ? $dataTPDARecursos[$va['idTipoPresupuestoDetalleAlmacen']][$kF]['cantidad'] : 0; ?>" name="almRecursos[<?= $va['idTipoPresupuestoDetalleAlmacen']; ?>][<?= $kF ?>]">
														</div>
													</td>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="<?= 3 + count($fechaDelPre); ?>"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="ui bottom attached tab segment container" data-tab="<?= $vd['idTipoPresupuesto']; ?>/b">
							<div class="ui short scrolling table" style="overflow: auto;">
								<table class="ui first last head foot stuck unstackable celled table" id="tablaAlmacenMonto">
									<thead>
										<tr>
											<th>Zona</th>
											<th>Zona 2</th>
											<th>Ciudad</th>
											<th>Split</th>
											<th>Monto</th>
											<?php foreach ($fechaDelPre as $vFa) : ?>
												<th>Monto <br><?= strpos($vFa['fecha'], '-') ? date_change_format($vFa['fecha']) : $vFa['fecha']; ?></th>
											<?php endforeach; ?>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($tipoPresupuestoDetalleAlmacen as $va) : ?>
											<tr>
												<td>
													<input type="hidden" name="almIdTPDA" value="<?= $va['idTipoPresupuestoDetalleAlmacen']; ?>" readonly>
													<?= $va['zona']; ?>
												</td>
												<td><?= $va['zona2']; ?></td>
												<td><?= $va['ciudad']; ?></td>
												<td style="min-width: 200px;">
													<?php $existe = isset($dataTPDA[$va['idTipoPresupuestoDetalleAlmacen']]['split']); ?>
													<?php $valor =  $existe ? $dataTPDA[$va['idTipoPresupuestoDetalleAlmacen']]['split'] : null; ?>
													<select class="tbAlm_freOpc ui compact fluid selection semantic-dropdown dropdown" name="almFrecuenciaOpc" onchange="OrdenServicio.calcularMontoDeAlmacen(this);">
														<option value="1" <?= ($valor == '1' || $valor == null) ? 'selected' : '' ?>>1 vez por mes</option>
														<option value="2" <?= ($valor == '2') ? 'selected' : '' ?>>1 vez cada 2 meses</option>
														<option value="3" <?= ($valor == '3') ? 'selected' : '' ?>>1 vez cada 3 meses</option>
													</select>
												</td>
												<td style="min-width: 130px;">
													<div class="ui input fluid">
														<input class="tbAlm_monto text-right keyUpChange onlyNumbers" value="<?= isset($dataTPDA[$va['idTipoPresupuestoDetalleAlmacen']]['monto']) ? $dataTPDA[$va['idTipoPresupuestoDetalleAlmacen']]['monto'] : 0; ?>" name="almMonto" onchange="OrdenServicio.calcularMontoDeAlmacen(this);">
													</div>
												</td>
												<?php foreach ($fechaDelPre as $vFa) : ?>
													<td>
														<div class="ui input transparent fluid">
															<input class="tbAlm_MontoXFecha" type="hidden" value="0">
															<label>0</label>
														</div>
													</td>
												<?php endforeach; ?>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5"></td>
											<?php foreach ($fechaDelPre as $vFa) : ?>
												<td>
													<div class="ui input transparent fluid">
														<input class="tbAlm_TotalMontoXFecha" type="hidden" value="0">
														<label>0</label>
													</div>
												</td>
											<?php endforeach; ?>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					<?php else : ?>
						<div class="control-group child-divcenter col-md-11 divTipoDetalle" style="width:100%">
							<div class="field">
								<?php if ($vd['mostrarDetalle'] != '1') : ?>
									<a class="ui blue button" data-detalle="<?= $vd['idTipoPresupuesto']; ?>" onclick="OrdenServicio.addRow(this)">Agregar</a>
								<?php endif; ?>
								<!-- <a class="ui green button" onclick="$(this).closest('.divTipoDetalle').find('th.cantidadDeTabla').toggleClass('d-none'); $(this).closest('.divTipoDetalle').find('td.cantidadDeTabla').toggleClass('d-none');$(this).find('i').toggleClass('slash');"><i class="icon eye"></i>Cantidad por cargo</a> -->
							</div>
							<table class="ui table" id="tabla<?= $vd['idTipoPresupuesto'] ?>">
								<thead>
									<tr>
										<th>Descripción</th>
										<th>Split</th>
										<th>Precio Unitario</th>
										<th>GAP</th>
										<th class="cantidadDeTabla">Cantidad</th>
										<th>Total</th>
										<th>Frecuencia</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($presupuestoDetalleSub[$vd['idPresupuestoDetalle']])) : ?>
										<?php foreach ($presupuestoDetalleSub[$vd['idPresupuestoDetalle']] as $key => $value) : ?>
											<tr class="detalleTr_<?= $key ?>" data-nrofila="<?= $key ?>">
												<td>
													<div class="ui action input fluid">
														<select class="ui fluid search dropdown semantic-dropdown cboTPD" name="tipoPresupuestoDetalleSub[<?= $vd['idTipoPresupuesto'] ?>]">
															<option value="">Sueldo</option>
															<?php foreach ($tipoPresupuestoDetalle[$vd['idTipoPresupuesto']] as $vPD) : ?>
																<option value="<?= $vPD['idTipoPresupuestoDetalle']; ?>" <?= $vPD['idTipoPresupuestoDetalle'] == $value['idTipoPresupuestoDetalle'] ? 'selected' : ''; ?>><?= $vPD['nombre']; ?></option>
															<?php endforeach; ?>
														</select>
														<a class="ui button" onclick="$(this).closest('tbody').find('tr.cantidadElementos_<?= $key ?>').toggleClass('d-none'); $(this).find('i').toggleClass('open');"><i class="icon folder outline"></i></a>
													</div>
												</td>
												<td class="splitDetalle">
													<div class="ui input fluid">
														<input type="text" class="onlyNumbers keyUpChange" name="splitDS[<?= $vd['idTipoPresupuesto'] ?>]" value="<?= $value['split']; ?>" onchange="OrdenServicio.cantidadSplitCargo(this);">
													</div>
												</td>
												<td class="precioUnitarioDetalle">
													<div class="ui input fluid">
														<input type="text" class="text-right onlyNumbers keyUpChange" name="precioUnitarioDS[<?= $vd['idTipoPresupuesto'] ?>]" value="<?= $value['precioUnitario']; ?>" onchange="OrdenServicio.cantidadSplitCargo(this);">
													</div>
												</td>
												<td class="gapDetalle">
													<div class="ui right labeled input fluid">
														<input type="text" class="text-right onlyNumbers keyUpChange" name="gapDS[<?= $vd['idTipoPresupuesto'] ?>]" value="<?= $value['gap']; ?>" onchange="OrdenServicio.cantidadSplitCargo(this);">
														<div class="ui basic label"> % </div>
													</div>
												</td>
												<td class="cantidadDeTabla">
													<div class="ui action input fluid">
														<input type="text" value="<?= $value['cantidad'] ?>" readonly name="cantidadDS[<?= $vd['idTipoPresupuesto'] ?>]" onchange="OrdenServicio.calcularSTotal(this);" data-detallesub="<?= $key ?>" data-detalle="<?= $vd['idTipoPresupuesto'] ?>">
														<a class="ui button" onclick="$(this).closest('tbody').find('tr.cantidadCargo_<?= $key ?>').toggleClass('d-none'); $(this).find('i').toggleClass('slash');"><i class="icon user slash"></i></a>
													</div>
												</td>
												<td>
													<div class="ui input transparent totalCantidadSplit fluid">
														<input type="text" class="text-right" value="<?= $cantidadCargo * floatval($value['split']) * floatval($value['precioUnitario']); ?>" readonly name="montoDS[<?= $vd['idTipoPresupuesto'] ?>]">
													</div>
												</td>
												<td class="frecuenciaDetalle">
													<select class="ui fluid search dropdown toast semantic-dropdown frecuenciaID" onchange="OrdenServicio.cantidadSplitCargo(this);" name="frecuenciaDS[<?= $vd['idTipoPresupuesto'] ?>]">
														<?= htmlSelectOptionArray2(['title' => 'Frecuencia', 'query' => LIST_FRECUENCIA, 'class' => 'text-titlecase', 'selected' => $value['idFrecuencia']]); ?>
													</select>
												</td>
											</tr>
											<tr class="cantidadCargo_<?= $key ?> d-none">
												<td colspan="7">
													<h4 class="ui horizontal divider header" style="background: none; overflow: inherit;">
														<i class="bar chart icon"></i>
														Cantidad por Cargo
													</h4>
													<div class="ui grid centered">
														<div class="eight wide column">
															<table class="ui very compact celled table">
																<thead>
																	<tr>
																		<th class="two wide column"></th>
																		<th class="eight wide column">Cargo</th>
																		<th class="six wide column">Cantidad</th>
																	</tr>
																</thead>
																<tbody class="listCheck">
																	<?php foreach ($cargoDelPre as $i => $cargo) : ?>
																		<tr>
																			<td class="text-center">
																				<div class="fields">
																					<div class="ui checkbox">
																						<input type="checkbox" name="chkDS[<?= $cargo['idCargo']; ?>][<?= $vd['idTipoPresupuesto'] ?>][<?= $key ?>]" data-cargo="<?= $i; ?>" <?= $presupuestoDetalleSubCargo[$value['idPresupuestoDetalleSub']][$cargo['idCargo']]['checked'] ? 'checked' : ''; ?> onchange="$(this).closest('.cantidadCargo_<?= $key ?>').closest('tbody').find('tr.detalleTr_<?= $key ?>').find('.onlyNumbers').change();">
																						<label style="font-size: 1.5em;"></label>
																					</div>
																				</div>
																			</td>
																			<td><?= $cargo['cargo']; ?></td>
																			<td>
																				<div class="ui input">
																					<input class="onlyNumbers keyUpChange subCantDS cantCargoxItm_<?= $cargo['idCargo'] ?>" name="subCantDS[<?= $cargo['idCargo']; ?>][<?= $vd['idTipoPresupuesto'] ?>][<?= $key ?>]" data-max="<?= $cargo['cantidad']; ?>" type="number" value="<?= $presupuestoDetalleSubCargo[$value['idPresupuestoDetalleSub']][$cargo['idCargo']]['cantidad']; ?>" onchange="$(this).closest('.cantidadCargo_<?= $key ?>').closest('tbody').find('tr.detalleTr_<?= $key ?>').find('.onlyNumbers').change();">
																				</div>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														</div>
													</div>
												</td>
											</tr>
											<tr class="d-none cantidadElementos_<?= $key ?>">
												<td colspan="7">
													<h4 class="ui horizontal divider header" style="background: none; overflow: inherit;">
														<i class="bar chart icon"></i>
														Cantidad de Elementos
													</h4>
													<div class="ui grid centered">
														<div class="twelve wide column">
															<table class="ui very compact celled table">
																<thead>
																	<tr>
																		<th class="six wide column">
																			<label class="ui left floated" style="font-size: 1em; vertical-align:middle; margin-bottom: 0px; padding: 11px 21px 11px 21px;">Elemento</label>
																			<a class="ui button right floated green" data-detalle="<?= $vd['idTipoPresupuesto']; ?>" onclick="OrdenServicio.addElemento(this);" data-nrofila="<?= $key ?>"><i class="icon plus"></i> Agregar</a>
																		</th>
																		<th class="three wide column text-right">Cantidad</th>
																		<th class="three wide column text-right">Monto</th>
																		<th class="four wide column text-right">Sub Total</th>
																	</tr>
																</thead>
																<tbody data-nrofila="<?= $key ?>">
																	<?php foreach ($presupuestoDetalleSubElemento[$value['idPresupuestoDetalleSub']] as $sbElmK => $sbElmV) : ?>
																		<tr>
																			<td>
																				<div class="ui action input" style="min-width: 400px; max-width: 500px;">
																					<select class="ui fluid search dropdown semantic-dropdown" name="elementoPresupuesto[<?= $vd['idTipoPresupuesto']; ?>][<?= $key; ?>]" onchange="Fn.buscarParaReemplazar(this, 'tr', 'preciounitario', '.montoElemento');">
																						<option value=""></option>
																						<?php foreach ($items[$value['idTipoPresupuestoDetalle']] as $item) : ?>
																							<option <?= ($item['idItem'] == $sbElmV['idItem']) ? 'selected' : ''; ?> value="<?= $item['idItem']; ?>" data-preciounitario="<?= isset($itemPrecio[$item['idItem']]['costo']) ? $itemPrecio[$item['idItem']]['costo'] : '0'; ?>"><?= $item['nombre']; ?></option>
																						<?php endforeach; ?>
																					</select>
																				</div>
																			</td>
																			<td>
																				<div class="ui input">
																					<input type="text" class="text-right onlyNumbers cantidadElemento keyUpChange" name="cantidadElementos[<?= $vd['idTipoPresupuesto']; ?>][<?= $key; ?>]" value="<?= $sbElmV['cantidad']; ?>" onchange="Fn.buscarParaMultiplicar(this, 'tr', '.montoElemento', '.subTotalElemento');">
																				</div>
																			</td>
																			<td>
																				<div class="ui input">
																					<input type="text" class="text-right onlyNumbers montoElemento keyUpChange" name="montoElementos[<?= $vd['idTipoPresupuesto']; ?>][<?= $key; ?>]" value="<?= $sbElmV['monto']; ?>" onchange="Fn.buscarParaMultiplicar(this, 'tr', '.cantidadElemento', '.subTotalElemento');">
																				</div>
																			</td>
																			<td>
																				<div class="ui input">
																					<input type="text" class="text-right onlyNumbers subTotalElemento" name="subTotalElemento[<?= $vd['idTipoPresupuesto']; ?>][<?= $key; ?>]" value="<?= $sbElmV['subTotal']; ?>" readonly onchange="OrdenServicio.evaluarSubTotalElemento(this);">
																				</div>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
										<?php if ($vd['idTipoPresupuesto'] == COD_GASTOSADMINISTRATIVOS && $presupuesto['sctr'] !== NULL) :  ?>
											<tr>
												<td>
													<div class="ui input fluid">
														<input value="SCTR">
													</div>
												</td>
												<td colspan="2">
													<div class="ui right input fluid">
														<input class="text-right keyUpChange onlyNumbers" name="pesupuestoSctr" type="text" id="txtVSctr" value="<?= $presupuesto['sctr'] ?>" onchange="OrdenServicio.calcularTablaSueldo()">
														<!-- <div class="ui basic label">%</div> -->
													</div>
												</td>
												<td colspan="4"></td>
											</tr>
										<?php endif; ?>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="row py-4">
		<div id="divSueldo" class="control-group child-divcenter col-md-12" style="width:100%"></div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<div class="control-group child-divcenter row" style="width:85%">
				<label class="form-control col-md-2" style="border:0px;">Observación :</label>
				<textarea class="form-control col-md-10" name="observacion" rows="4"><?= $presupuesto['observacion']; ?></textarea>
			</div>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>