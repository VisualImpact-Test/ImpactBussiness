<form class="ui form" role="form" id="formRegistroOrdenServicio" method="post" autoComplete="off">
	<?php if (!empty($idOrdenServicio)) : ?>
		<div class="fields d-none">
			<div class="five wide field">
				<div class="ui sub header">IdOrdenServicio</div>
				<div class="ui input">
					<input type="text" class="ui" name="idOrdenServicio" value="<?= $idOrdenServicio; ?>">
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div id="accordion">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				<h4 class="ui dividing header text-uppercase">Datos Generales</h4>
			</button>
			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
				<div class="fields">
					<div class="sixteen wide field">
						<div class="ui sub header">Título</div>
						<input type="text" class="ui" name="nombre" value="<?= isset($ordenServicio['nombre']) ? verificarEmpty($ordenServicio['nombre']) : ''; ?>" placeholder="Título" patron="requerido">
					</div>
				</div>
				<div class="fields">
					<?php $utilizaCli = false; ?>
					<?php if (isset($ordenServicio['chkUtilizarCliente'])) {
						if ($ordenServicio['chkUtilizarCliente']) $utilizaCli = true;
					} ?>
					<div class="ten wide field divCl <?= $utilizaCli ? '' : 'd-none'; ?>">
						<div class="ui sub header">Cliente</div>
						<select class="ui fluid search dropdown dropdownSingleAditions" id="cboCliente" name="clienteForm">
							<?php $selected = isset($ordenServicio['idCliente']) ? verificarEmpty($ordenServicio['idCliente']) : NULL; ?>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'id' => 'idCliente', 'value' => 'nombre', 'query' => $cliente, 'simple' => true, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="five wide field divCu <?= $utilizaCli ? 'd-none' : ''; ?>">
						<div class="ui sub header">Cuenta</div>
						<select class="ui dropdown clearable semantic-dropdown parentDependienteSemantic" id="cboCuenta" name="cuentaForm" patron="requerido" data-childDependiente="cboCentroCosto">
							<?php $selected = isset($ordenServicio['idCuenta']) ? verificarEmpty($ordenServicio['idCuenta']) : NULL; ?>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<div class="five wide field divCu <?= $utilizaCli ? 'd-none' : ''; ?>">
						<div class="ui sub header">Centro Costo</div>
						<select class="ui dropdown clearable semantic-dropdown read-only childdependienteSemantic" id="cboCentroCosto" name="centroCostoForm" patron="requerido">
							<?php $selected = isset($ordenServicio['idCentroCosto']) ? verificarEmpty($ordenServicio['idCentroCosto']) : NULL; ?>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'query' => $centroCosto, 'simple' => true, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
					<input type="hidden" class="chkUtilizarCliente" name="chkUtilizarCliente" value="<?= $utilizaCli ? '1' : '0'; ?>">
					<div class="one wide field">
						<div class="ui sub header">.</div>
						<a class="ui icon button blue" onclick="OrdenServicio.validarSiClienteOCuenta(this);" title="Cambiar opción para indicar Cliente o Cuenta / Centro Costo">
							<i class="exchange alternate icon"></i>
						</a>
					</div>
					<div class="five wide field">
						<div class="ui sub header">Moneda</div>
						<select class="ui dropdown clearable semantic-dropdown" name="moneda">
							<?php $selected = isset($ordenServicio['idMoneda']) ? verificarEmpty($ordenServicio['idMoneda']) : NULL; ?>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $selected, 'query' => $moneda, 'id' => 'idMoneda', 'value' => 'nombreMoneda', 'class' => 'text-titlecase']); ?>
						</select>
					</div>
				</div>
				<div class="fields">
					<div class="two wide field">
						<div class="ui sub header">Periodo</div>
						<input type="text" class="ui onlyNumbers" name="cantidadMeses" value="<?= isset($ordenServicioFecha) ? count($ordenServicioFecha) : '12'; ?>" id="periodoFechas" placeholder="Cantidad de meses" onchange="OrdenServicio.addFechas()">
					</div>
					<div class="six wide field">
						<div class="ui sub header">Fecha Inicio</div>
						<div class="ui calendar date-semantic" id="fechaInicial">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input type="text" placeholder="Fecha Inicial" value="<?= isset($ordenServicio['fechaIni']) ? $ordenServicio['fechaIni'] : '' ?>">
							</div>
						</div>
						<input type="hidden" class="date-semantic-value" name="fechaIni" value="<?= isset($ordenServicio['fechaIni']) ? $ordenServicio['fechaIni'] : '' ?>">
					</div>
					<div class="eight wide field">
						<div class="ui sub header">Observación</div>
						<textarea name="observacion" rows="3"><?= isset($ordenServicio['observacion']) ? verificarEmpty($ordenServicio['observacion']) : ''; ?></textarea>
					</div>
					<div class="eight wide field d-none">
						<div class="ui sub header">Fecha Final</div>
						<div class="ui calendar date-semantic" id="fechaFinal">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input type="text" placeholder="Fecha Final" value="<?= isset($ordenServicio['fechaFin']) ? $ordenServicio['fechaFin'] : '' ?>">
							</div>
						</div>
						<input type="hidden" class="date-semantic-value" name="fechaFin" value="<?= isset($ordenServicio['fechaFin']) ? $ordenServicio['fechaFin'] : '' ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="datosAd">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#colDatosAd" aria-expanded="false" aria-controls="colDatosAd">
				<h4 class="ui dividing header text-uppercase">Datos Adicionales</h4>
			</button>
			<div id="colDatosAd" class="collapse" aria-labelledby="headingOne" data-parent="#datosAd">
				<div class="fields">
					<div class="six wide field">
						<div class="ui sub header">Region</div>
						<select class="ui dropdown" id="cboRegion" name="departamento">
							<option value="">Seleccione</option>
							<?php foreach ($departamento as $k => $v) : ?>
								<option value="<?= $v['id'] ?>" <?= !empty($ordenServicio) ? ($v['id'] == $ordenServicio['idDepartamento'] ? 'selected' : '') : ''; ?>><?= $v['nombre'] ?></option>;
							<?php endforeach; ?>
						</select>
					</div>
					<div class="five wide field">
						<div class="ui sub header">Provincia</div>
						<select class="ui dropdown" id="cboProvincia" name="provincia">
							<option value="">Seleccione</option>
							<?php if (!empty($ordenServicio['idProvincia'])) : ?>
								<option value="<?= $ordenServicio['idProvincia']; ?>" selected><?= $ordenServicio['provincia']; ?></option>
							<?php endif; ?>
						</select>
					</div>
					<div class="five wide field">
						<div class="ui sub header">Distrito</div>
						<select class="ui dropdown" id="cboDistrito" name="distrito">
							<option value="">Seleccione</option>
							<?php if (!empty($ordenServicio['idDistrito'])) : ?>
								<option value="<?= $ordenServicio['idDistrito']; ?>" selected><?= $ordenServicio['distrito']; ?></option>
							<?php endif; ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="datosCargo">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#colDatosCargo" aria-expanded="true" aria-controls="colDatosCargo">
				<h4 class="ui dividing header text-uppercase">Cargo: Cantidad & Sueldo</h4>
			</button>
			<div id="colDatosCargo" class="collapse show" aria-labelledby="headingOne" data-parent="#datosCargo">
				<div class="fields">
					<div class="field">
						<a class="ui btn btn-trade-visual <?= empty($ordenServicioCargo) ? 'disabled' : ''; ?>" id="btn-addCargo" onclick='OrdenServicio.addCargo()'>Agregar Cargo</a>
					</div>
				</div>
				<div id="divCargo">
					<?php foreach ($ordenServicioCargo as $kC => $vC) : ?>
						<div class="fields">
							<div class="six wide field">
								<div class="ui sub header">Cargo</div>
								<select name="cargo" class="ui fluid dropdown semantic-dropdown" patron="requerido" onchange="$(this).closest('.fields').find('.inSueldo').val($(this).find('option:selected').data('sueldobase'))">
									<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $kC, 'query' => $cargo, 'id' => 'idCargoTrabajo', 'value' => 'cargo', 'class' => 'text-titlecase', 'data-option' => ['sueldo']]); ?>
								</select>
							</div>
							<div class="six wide field">
								<div class="ui sub header">Cantidad</div>
								<input type="text" class="ui onlyNumbers" name="cantidadCargo" placeholder="Cantidad" value="<?= $vC['cantidad']; ?>" patron="requerido">
							</div>
							<div class="three wide field">
								<div class="ui sub header">Sueldo</div>
								<input type="text" class="ui onlyNumbers inSueldo" name="sueldoCargo" placeholder="Cantidad" value="<?= $vC['sueldo']; ?>" patron="requerido">
							</div>
							<div class="one wide field">
								<div class="ui sub header text-white">.</div>
								<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
							</div>
						</div>
					<?php endforeach; ?>

				</div>
			</div>
		</div>
	</div>
	<div id="datosDocs">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#colDatosDocs" aria-expanded="true" aria-controls="colDatosDocs">
				<h4 class="ui dividing header text-uppercase">Documentos</h4>
			</button>
			<div id="colDatosDocs" class="collapse show" aria-labelledby="headingOne" data-parent="#datosDocs">
				<div class="fields">
					<div class="field">
						<a class="ui btn btn-trade-visual" onclick='OrdenServicio.addDocumento()'>Solicitar Documento</a>
					</div>
					<div class="field">
						<a class="ui btn btn-primary" onclick='OrdenServicio.addDocumento(2)'>Seleccionar Documento</a>
					</div>
				</div>
				<div id="divDocumentos">
					<?php if (!empty($ordenServicioDocumento)) : ?>
						<?php foreach ($ordenServicioDocumento as $k => $v) : ?>
							<div class="fields">
								<div class="five wide field">
									<div class="ui sub header">Documento</div>
									<input type="hidden" name="idDocumento" value="<?= $v['idDocumento'] ?>">
									<input type="text" class="ui" name="nroDocumento" placeholder="Descripción documento" value="<?= $v['documento']; ?>">
								</div>
								<div class="four wide field">
									<div class="ui sub header">Area</div>
									<select class="ui dropdown parentDependiente" id="areaForm<?= $k ?>" name="area" patron="requerido" data-childDependiente="personaForm<?= $k ?>">
										<?= htmlSelectOptionArray2(["title" => "Seleccione", "selected" => $v['idArea'], "id" => "idArea", "value" => "nombre", "query" => $area, "simple" => true, "class" => "text-titlecase"]); ?>
									</select>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Persona</div>
									<select class="ui dropdown clearable semantic-dropdown" id="personaForm<?= $k ?>" name="persona">
										<?= htmlSelectOptionArray2(["title" => "Seleccione", "selected" => $v['idPersonal'], "id" => "idPersonal", "value" => "nombre", "query" => $persona, "class" => "text-titlecase"]); ?>
									</select>
								</div>
								<?php if (!empty($v['nombre_archivo'])) : ?>
									<div class="one wide field">
										<div class="ui sub header text-white">.</div>
										<a class="ui button" href="https://s3.us-central-1.wasabisys.com/impact.business/documentos/<?= $v['nombre_archivo'] ?>" target="_blank"><i class="download icon"></i></a>
									</div>
								<?php endif; ?>
								<div class="one wide field">
									<div class="ui sub header text-white">.</div>
									<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div id="datosDetalle">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#colDatosDetalle" aria-expanded="true" aria-controls="colDatosDetalle">
				<h4 class="ui dividing header text-uppercase">Detalle</h4>
			</button>
			<div id="colDatosDetalle" class="collapse show" aria-labelledby="headingOne" data-parent="#datosDetalle">
				<div id="divDetalleOrdenServicio">
					<?php foreach ($tipoPresupuesto as $k => $v) : ?>
						<input type="hidden" name="chkContadorTipo" value="<?= $v['idTipoPresupuesto']; ?>">
						<?php if ($k % 4 == 0) : ?>
							<div class="fields">
							<?php endif; ?>
							<div class="field">
								<div class="ui celled relaxed list">
									<div class="item">
										<div class="ui master checkbox mt-1" <?= ($v['idTipoPresupuesto'] == COD_SUELDO) ? "id='chkAsgFam'" : "" ?>>
											<input type="checkbox" name="chkTipoPresupuesto[<?= $v['idTipoPresupuesto']; ?>]" <?= isset($ordenServicioDetalle[$v['idTipoPresupuesto']]) ? 'checked' : '' ?>>
											<label style="font-size: 1.5em;"><?= $v['nombre'] ?></label>
										</div>
										<?php if (!empty($tipoPresupuestoDetalle[$v['idTipoPresupuesto']]) && $v['mostrarDetalle'] == '1') : ?>
											<div class="list">
												<?php foreach ($tipoPresupuestoDetalle[$v['idTipoPresupuesto']] as $k1 => $v1) : ?>
													<?php if ($v1['tipo'] != '4') : ?>
														<input type="hidden" name="chkContadorTipoDetalle[<?= $v['idTipoPresupuesto'] ?>]" value="<?= $v1['idTipoPresupuestoDetalle']; ?>">
														<div class="item <?= $v1['chkDefault'] == '1' ? 'disabled chkDefault' : '' ?> <?= !empty($v1['idTipoPresupuestoDetalleDependiente']) ? 'd-none idDependiente' . $v1['idTipoPresupuestoDetalleDependiente'] : '' ?>">
															<div class="ui child checkbox mt-1">
																<input class="" type="checkbox" data-buscardependiente="<?= $v1['idTipoPresupuestoDetalle']; ?>" onchange="OrdenServicio.buscarCheckDependiente(this);" name="chkTipoPresupuestoDet[<?= $v['idTipoPresupuesto']; ?>][<?= $v1['idTipoPresupuestoDetalle']; ?>]" <?= (isset($ordenServicioDetalleSub[$v['idTipoPresupuesto']][$v1['idTipoPresupuestoDetalle']]) || $v1['chkDefault'] == '1') ? 'checked' : '' ?>>
																<label><?= $v1['nombre'] ?></label>
															</div>
														</div>
													<?php endif; ?>
													<?php if ($v1['idTipoPresupuestoDetalle'] == COD_ASIGNACIONFAMILIAR) : ?>
														<div id='asgFam' class="idDependiente<?= COD_ASIGNACIONFAMILIAR ?>'">
															<input name="asignacionFamiliar" value="<?= isset($ordenServicioDetalleSub[COD_SUELDO][COD_ASIGNACIONFAMILIAR]) ? $ordenServicioDetalleSub[COD_SUELDO][COD_ASIGNACIONFAMILIAR]['valorPorcentual'] : '100' ?>">
														</div>
													<?php endif; ?>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php if ($k % 4 == 3) : ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
	<?php if (!empty($ordenServicioDocumento)) : ?>
		OrdenServicio.documentoCont = <?= count($ordenServicioDocumento) ?>
	<?php endif; ?>
</script>