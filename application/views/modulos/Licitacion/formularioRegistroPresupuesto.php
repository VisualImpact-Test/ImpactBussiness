<?php $dataRow = 0; ?>
<form class="form" role="form" id="formRegistroPresupuesto" method="post" autoComplete="off">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<div class="control-group child-divcenter row" style="width:85%">
				<label class="form-control col-md-2" style="border:0px;">Observación :</label>
				<textarea class="form-control col-md-4" name="observacion" rows="4">- FEE Proyecto 9%&#13;&#10;- Se considera un Split básico de materiales&#13;&#10;- Se considera en comunicación 1GB DATA + RPE ILIMITADO&#13;&#10;- Se considera uniforme dos veces al año (1 Invierno + 1 Verano)&#13;&#10;- Se considera un Kits de bioseguridad + pruebas COVID (20% del personal)&#13;&#10;- No se considera provisión de feriados&#13;&#10;- No se consideran reuniones mensuales de integración&#13;&#10;- No se considera evento de fin de año&#13;&#10;- No se consideran rutas viajeras ni movilidades extraurbanas</textarea>
			</div>
		</div>
	</div>
	<div class="row pt-4">
		<?php $cantidadCargo = 0; ?>
		<?php foreach ($licitacionCargo as $k => $v) : ?>
			<?php $cantidadCargo += intval($v['cantidad']); ?>
		<?php endforeach; ?>
		<div class="col-md-11 child-divcenter" style="width: 100%">
			<input type="hidden" name="idLicitacion" value="<?= $licitacionDetalle[0]['idLicitacion']; ?>">
			<div class="ui top attached tabular menu">
				<a class="item active" data-tab="datos">Datos</a>
				<?php foreach ($licitacionDetalle as $kd => $vd) : ?>
					<a class="tabTiposPresupuestos item" data-tab="<?= $vd['idTipoPresupuesto']; ?>"><?= $vd['tipoPresupuesto']; ?></a>
				<?php endforeach; ?>
			</div>
			<div class="ui bottom attached tab segment active" data-tab="datos">
				<div id="divTabla" class="control-group child-divcenter col-md-11" style="width:100%">
					<table class="ui table" id="tablaFechaPersona">
						<thead>
							<tr>
								<th> <label class="text-white">________________</label> </th>
								<?php foreach ($licitacionFecha as $k => $v) : ?>
									<th>
										<label><?= strpos($v['fecha'], '-') ? date_change_format($v['fecha']) : $v['fecha']; ?></label>
										<input type="hidden" name="idLicitacionFecha" value="<?= $v['idLicitacionFecha']; ?>">
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($licitacionCargo as $kp => $vp) : ?>
								<tr>
									<td> <?= $vp['cargo']; ?> </td>
									<?php foreach ($licitacionFecha as $kf => $vf) : ?>
										<td>
											<div class="ui input transparent" style="width: 80px;">
												<input type="text" value="<?= $vp['cantidad']; ?>" readonly class="form-control text-center <?= $kf == 0 ? 'cloneAll' : ('cloned' . $kp) ?>" <?php if ($kf == 0) :  ?> id="cargoCantidad_<?= $kp ?>" <?php endif; ?> data-personal="<?= $kp ?>" patron="requerido">
											</div>
											<input type="hidden" name="idLicitacionCargo" value="<?= $vp['idLicitacionCargo']; ?>">
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php foreach ($licitacionDetalle as $kd => $vd) : ?>
					<input type="hidden" name="idLicitacionDetalle" value="<?= $vd['idLicitacionDetalle']; ?>">
					<div class="ui table">
						<table class="ui table">
							<thead>
								<tr>
									<th><?= $vd['tipoPresupuesto']; ?></th>
									<?php foreach ($licitacionFecha as $kf => $vf) : ?>
										<th>
											<div class="ui input transparent" style="width: 80px;">
												<input class="text-right" type="text" value="STotal <?= $kf ?>" readonly id="totalColumna_<?= $kd ?>_<?= $kf ?>">
											</div>
										</th>
									<?php endforeach; ?>
									<th>TOTAL</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($vd['idTipoPresupuesto'] == COD_SUELDO) :  ?>
									<?php foreach ($licitacionCargo as $k => $v) : ?>
										<tr>
											<td> <?= $v['cargo']; ?> </td>
											<?php foreach ($licitacionFecha as $kf => $vf) : ?>
												<td>
													<div class="ui input transparent" style="width: 80px;">
														<input class="text-right" type="text" value="0" readonly id="montoSueldo_<?= $k ?>_<?= $kf ?>">
													</div>
												</td>
											<?php endforeach; ?>
											<td>
												<div class="ui input transparent" style="width: 80px;">
													<input class="text-right" type="text" value="0" readonly id="totalLineaSueldo_<?= $k ?>">
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
									<tr>
										<td> INCENTIVO </td>
										<?php foreach ($licitacionFecha as $kf => $vf) : ?>
											<td>
												<div class="ui input transparent" style="width: 80px;">
													<input class="text-right" type="text" value="0" readonly id="montoIncentivo_<?= $kf ?>">
												</div>
											</td>
										<?php endforeach; ?>
										<td>
											<div class="ui input transparent" style="width: 80px;">
												<input class="text-right" type="text" value="0" readonly id="totalLineaIncentivo" data-detalle="<?= $kd ?>" onchange="Licitacion.calcularTotalColumnaSueldo(this);">
											</div>
										</td>
									</tr>
								<?php else : ?>
									<?php if (!empty($licitacionDetalleSub[$vd['idTipoPresupuesto']])) :  ?>
										<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $kLDS => $vLDS) : ?>
											<tr>
												<td> <?= $vLDS['nombre']; ?>
												</td>
												<?php foreach ($licitacionFecha as $kf => $vf) : ?>
													<td>
														<div class="ui input transparent" style="width: 80px;">
															<input class="text-right" type="text" value="0" readonly id="montoLDS_<?= $kd ?>_<?= $kLDS ?>_<?= $kf ?>">
														</div>
													</td>
												<?php endforeach; ?>
												<td>
													<div class="ui input transparent" style="width: 80px;">
														<input class="text-right" type="text" value="0" readonly id="totalLineaDS_<?= $kd ?>_<?= $kLDS ?>" data-detalle="<?= $kd ?>" onchange="Licitacion.calcularTotalColumna(this);">
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
			<?php foreach ($licitacionDetalle as $kd => $vd) : ?>
				<div class="ui bottom attached tab segment" data-tab="<?= $vd['idTipoPresupuesto']; ?>">
					<?php if ($vd['idTipoPresupuesto'] == COD_SUELDO) :  ?>
						<div class="control-group child-divcenter col-md-11" style="width:100%">
							<table class="ui table" id="tablaSueldo" data-personal="<?= count($licitacionCargo); ?>">
								<thead>
									<tr>
										<th class="d-none">Tipo</th>
										<th class="two wide">Sueldos</th>
										<th class="one wide">% CL</th>
										<?php foreach ($licitacionCargo as $kp => $vp) : ?>
											<th><?= $vp['cargo']; ?></th>
										<?php endforeach; ?>
										<th>
											<!-- <a class="ui button green"><i class="add icon"></i></a> -->
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $k1 => $v1) : ?>
										<?php if ($v1['tipo'] != 4) :  ?>
											<tr data-row="<?= $dataRow ?>">
												<td class="d-none"><input class="form-control tipoSueldo" value="<?= $v1['tipo'] ?>" id="rowTipo_Sueldo<?= $dataRow ?>"></td>
												<td>
													<select class="ui search dropdown disabled toast semantic-dropdown cboSueldo">
														<option value="">Sueldo</option>
														<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $k2 => $v2) : ?>
															<?php if ($v2['tipo'] != 4) :  ?>
																<option value="<?= $v2['idTipoPresupuestoDetalle']; ?>" data-tipo="<?= $v2['tipo']; ?>" data-cl="<?= $v2['porCl']; ?>" <?= $v2['idTipoPresupuestoDetalle'] == $v1['idTipoPresupuestoDetalle'] ? 'selected' : '' ?>><?= $v2['nombre']; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													</select>
												</td>
												<td>
													<div class="ui right labeled input d-none">
														<input class="porCL" value="<?= $v1['porCl']; ?>">
														<div class="ui basic label"> % </div>
													</div>
												</td>
												<?php foreach ($licitacionCargo as $kp => $vp) : ?>
													<td><input class="form-control text-right" data-persona="<?= $kp ?>" id="rowMonto_Sueldo<?= $dataRow ?>-<?= $kp ?>" value="<?= $v1['idTipoPresupuestoDetalle'] == COD_SUELDOMINIMO ? $sueldoMinimo : ($v1['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR ? floatval($sueldoMinimo) / 10 : '0'); ?>" onchange="Licitacion.calcularTablaSueldo()" <?= $v1['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR ? 'readonly' : ''; ?>></td>
												<?php endforeach; ?>
												<td>
													<!-- <a class="ui button red" onclick="$(this).parent('td').parent('tr').remove();"><i class="trash icon"></i></a> -->
												</td>
											</tr>
											<?php $dataRow++; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td class="d-none"></td>
										<td colspan="2"></td>
										<?php foreach ($licitacionCargo as $kp => $vp) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" type="text" id="sTotalSueldo_<?= $kp ?>" value="" readonly>
												</div>
											</td>
										<?php endforeach; ?>
										<td></td>
									</tr>
									<?php $totalPorcentaje = 0; ?>
									<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $k1 => $v1) : ?>
										<?php if ($v1['tipo'] == 4) :  ?>
											<?php $totalPorcentaje += floatval($v1['porCl']); ?>
											<tr data-row="<?= $dataRow; ?>">
												<td class="d-none" style="background: #fff"><input class="form-control beneficioSueldo" value="<?= $v1['tipo'] ?>" id="rowBeneficio_Sueldo<?= $dataRow ?>"></td>
												<td style="background: #fff">
													<select class="ui search dropdown semantic-dropdown disabled toast cboSueldo">
														<option value="">Sueldo</option>
														<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $k2 => $v2) : ?>
															<?php if ($v2['tipo'] == 4) :  ?>
																<option value="<?= $v2['idTipoPresupuestoDetalle']; ?>" data-tipo="<?= $v2['tipo']; ?>" data-cl="<?= $v2['porCl']; ?>" <?= $v2['idTipoPresupuestoDetalle'] == $v1['idTipoPresupuestoDetalle'] ? 'selected' : '' ?>><?= $v2['nombre']; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													</select>
												</td>
												<td style="background: #fff">
													<div class="ui right labeled input">
														<input class="porCL" value="<?= $v1['porCl']; ?>" id="rowPorCL_Sueldo<?= $dataRow ?>" readonly>
														<div class="ui basic label"> % </div>
													</div>
												</td>
												<?php foreach ($licitacionCargo as $kp => $vp) : ?>
													<td style="background: #fff">
														<div class="ui transparent input">
															<input class="form-control text-right" data-persona="<?= $kp ?>" id="rowMontoBeneficio_Sueldo<?= $dataRow ?>_<?= $kp ?>" value="0" readonly>
														</div>
													</td>
												<?php endforeach; ?>
												<td style="background: #fff">
													<!-- <a class="ui button red" onclick="$(this).parent('td').parent('tr').remove();"><i class="trash icon"></i></a> -->
												</td>
											</tr>
											<?php $dataRow++; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<tr>
										<td class="d-none"></td>
										<td></td>
										<td><label id="totalPorcentaje"><?= $totalPorcentaje; ?></label> %</td>
										<?php foreach ($licitacionCargo as $kp => $vp) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" type="text" id="totalSueldo_<?= $kp ?>" value="" readonly>
												</div>
											</td>
										<?php endforeach; ?>
										<td>
											<a class="ui button teal" onclick="Licitacion.calcularTablaSueldo();"><i class="refresh icon"></i></a>
										</td>
									</tr>
								</tfoot>
							</table>
							<table class="ui definition table">
								<thead>
									<tr>
										<th></th>
										<?php foreach ($licitacionCargo as $k => $v) : ?>
											<th>
												<?= $v['cargo']; ?>
											</th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Sueldo</td>
										<?php foreach ($licitacionCargo as $k => $v) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" id="txtSueldo_<?= $k ?>">
												</div>
											</td>
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Incentivo</td>
										<?php foreach ($licitacionCargo as $k => $v) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" id="txtIncentivo_<?= $k ?>">
												</div>
											</td>
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Sueldo Total</label></td>
										<?php foreach ($licitacionCargo as $k => $v) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" id="txtSueldoCantidad_<?= $k ?>">
												</div>
												<label>/ &nbsp;</label>
												<label id="cantidadSueldo_<?= $k ?>"><?= $v['cantidad']; ?></label>
												<label> &nbsp; <i class="fa fa-sm fa-user"></i> </label>
											</td>
										<?php endforeach; ?>
									</tr>
									<tr>
										<td>Incentivo Total</td>
										<?php foreach ($licitacionCargo as $k => $v) : ?>
											<td>
												<div class="ui transparent input">
													<input class="text-right" id="txtIncentivoCantidad_<?= $k ?>">
												</div>
												<label>/ &nbsp;</label>
												<label id="cantidadIncentivo_<?= $k ?>"><?= $v['cantidad']; ?></label>
												<label> &nbsp; <i class="fa fa-sm fa-user"></i></label>
											</td>
										<?php endforeach; ?>
									</tr>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<div class="control-group child-divcenter col-md-11 divTipoDetalle" style="width:100%">
							<div class="field">
								<?php if ($vd['mostrarDetalle'] != '1') :  ?>
									<a class="ui blue button" data-tipo="<?= $vd['idTipoPresupuesto']; ?>" onclick="Licitacion.addRow(this)">Agregar</a>
								<?php endif; ?>
								<a class="ui green button" onclick="$(this).closest('.divTipoDetalle').find('th.cantidadDeTabla').toggleClass('d-none'); $(this).closest('.divTipoDetalle').find('td.cantidadDeTabla').toggleClass('d-none');$(this).find('i').toggleClass('slash');"><i class="icon eye"></i>Cantidad por cargo</a>
							</div>
							<table class="ui table" id="tabla<?= $vd['idTipoPresupuesto'] ?>">
								<thead>
									<tr>
										<th>Descripción</th>
										<th>Split</th>
										<th>Precio Unitario</th>
										<th class="cantidadDeTabla">Cantidad</th>
										<th>Total</th>
										<th>Frecuencia</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($licitacionDetalleSub[$vd['idTipoPresupuesto']])) :  ?>
										<?php foreach ($licitacionDetalleSub[$vd['idTipoPresupuesto']] as $key => $value) : ?>
											<tr>
												<td>
													<select class="ui fluid search dropdown toast semantic-dropdown">
														<option value="">Sueldo</option>
														<?php foreach ($tipoPresupuestoDetalle[$vd['idTipoPresupuesto']] as $kTPD => $vPD) : ?>
															<option value="<?= $vPD['idTipoPresupuestoDetalle']; ?>" <?= $vPD['idTipoPresupuestoDetalle'] == $value['idTipoPresupuestoDetalle'] ? 'selected' : ''; ?>><?= $vPD['nombre']; ?></option>
														<?php endforeach; ?>
													</select>
												</td>
												<td class="splitDetalle">
													<div class="ui input" style="width: 80px;">
														<input type="text" class="onlyNumbers" name="splitDS" value="<?= $value['split']; ?>" onchange="Licitacion.cantidadSplitCargo(this);">
													</div>
												</td>
												<td class="precioUnitarioDetalle">
													<div class="ui input" style="width: 80px;">
														<input type="text" class="text-right" name="precioUnitarioDS" value="<?= $value['precUnitario']; ?>">
													</div>
												</td>
												<td class="cantidadDeTabla">
													<div class="ui action input" style="width: 80px;">
														<input type="text" value="<?= $cantidadCargo * floatval($value['split']); ?>" readonly name="cantidadDS" onchange="Licitacion.calcularSTotal(this);" data-detallesub="<?= $key ?>" data-detalle="<?= $kd ?>">
														<a class="ui button" onclick="$(this).closest('td.cantidadDeTabla').find('div.listCheck').toggleClass('d-none'); $(this).find('i').toggleClass('slash');"><i class="icon user slash"></i></a>
													</div>
													<div class="listCheck mt-3 d-none">
														<?php foreach ($licitacionCargo as $kLc => $vLc) : ?>
															<div class="fields">
																<div class="ui checkbox">
																	<input type="checkbox" name="chkPD[<?= $vLc['idLicitacionCargo']; ?>]" data-cargo="<?= $kLc ?>" checked onchange="Licitacion.cantidadSplitCargo(this);">
																	<label style="font-size: 1.5em;"><?= $vLc['cargo'] ?></label>
																</div>
															</div>
														<?php endforeach; ?>
													</div>
												</td>
												<td>
													<div class="ui input transparent totalCantidadSplit" style="width: 80px;">
														<input type="text" class="text-right" value="<?= $cantidadCargo * floatval($value['split']) * floatval($value['precUnitario']); ?>" readonly name="montoDS">
													</div>
												</td>
												<td class="frecuenciaDetalle">
													<select class="ui fluid search dropdown toast semantic-dropdown frecuenciaID" onchange="Licitacion.cantidadSplitCargo(this);">
														<option value="">Frecuencia</option>
														<option value="1" <?= $value['frecuencia'] == '1' ? 'selected' : ''; ?>>MENSUAL</option>
														<option value="2" <?= $value['frecuencia'] == '2' ? 'selected' : ''; ?>>BIMENSUAL</option>
														<option value="3" <?= $value['frecuencia'] == '3' ? 'selected' : ''; ?>>SEMESTRAL</option>
														<option value="4" <?= $value['frecuencia'] == '4' ? 'selected' : ''; ?>>ANUAL</option>
														<option value="5" <?= $value['frecuencia'] == '5' ? 'selected' : ''; ?>>UNICO</option>
													</select>
												</td>
											</tr>
										<?php endforeach; ?>
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
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>