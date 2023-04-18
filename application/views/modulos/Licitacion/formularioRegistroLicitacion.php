<form class="ui form" role="form" id="formRegistroLicitacion" method="post" autoComplete="off">
	<?php if (!empty($idLicitacion)) :  ?>
		<div class="fields d-none">
			<div class="five wide field">
				<div class="ui sub header">IdLicitacion</div>
				<div class="ui input">
					<input type="text" class="ui" name="idLicitacion" value="<?= $idLicitacion; ?>">
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="fields">
		<div class="eight wide field">
			<div class="ui sub header">Cliente</div>
			<select class="ui fluid search dropdown dropdownSingleAditions" id="clienteForm" name="clienteForm" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $licitacion['idCliente'], 'id' => 'idCliente', 'value' => 'nombre', 'query' => $cliente, 'simple' => true, 'class' => 'text-titlecase']); ?>
			</select>
		</div>
		<div class="eight wide field">
			<div class="ui sub header">Moneda</div>
			<select class="ui dropdown semantic-dropdown" name="moneda" patron="requerido">
				<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $licitacion['idMoneda'], 'query' => $moneda, 'id' => 'idMoneda', 'value' => 'nombreMoneda', 'class' => 'text-titlecase']); ?>
			</select>
		</div>
	</div>
	<div class="fields">
		<div class="six wide field">
			<div class="ui sub header">Region</div>
			<select class="ui dropdown" id="cboRegion" name="departamento">
				<option value="">Seleccione</option>
				<?php foreach ($departamento as $k => $v) : ?>
					<option value="<?= $v['id'] ?>" <?= !empty($licitacion) ? ($v['id'] == $licitacion['idDepartamento'] ? 'selected' : '') : ''; ?>><?= $v['nombre'] ?></option>;
				<?php endforeach; ?>
			</select>
		</div>
		<div class="five wide field">
			<div class="ui sub header">Provincia</div>
			<select class="ui dropdown" id="cboProvincia" name="provincia">
				<option value="">Seleccione</option>
				<?php if (!empty($licitacion['idProvincia'])) :  ?>
					<option value="<?= $licitacion['idProvincia']; ?>" selected><?= $licitacion['provincia']; ?></option>
				<?php endif; ?>
			</select>
		</div>
		<div class="five wide field">
			<div class="ui sub header">Distrito</div>
			<select class="ui dropdown" id="cboDistrito" name="distrito">
				<option value="">Seleccione</option>
				<?php if (!empty($licitacion['idDistrito'])) :  ?>
					<option value="<?= $licitacion['idDistrito']; ?>" selected><?= $licitacion['distrito']; ?></option>
				<?php endif; ?>
			</select>
		</div>
	</div>
	<div class="fields">
		<div class="two wide field">
			<div class="ui sub header">Periodo</div>
			<input type="text" class="ui onlyNumbers" name="cantidadMeses" value="<?= isset($licitacionFecha) ? count($licitacionFecha) : '12'; ?>" id="periodoFechas" placeholder="Cantidad de meses" onchange="Licitacion.addFechas()">
		</div>
		<div class="six wide field">
			<div class="ui sub header">Fecha Inicio</div>
			<div class="ui calendar date-semantic" id="fechaInicial">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input type="text" placeholder="Fecha Inicial" value="<?= isset($licitacion['fechaIni']) ? $licitacion['fechaIni'] : ''  ?>">
				</div>
			</div>
			<input type="hidden" class="date-semantic-value" name="fechaIni" value="<?= isset($licitacion['fechaIni']) ? $licitacion['fechaIni'] : '' ?>">
		</div>
		<div class="eight wide field">
			<div class="ui sub header">Observación</div>
			<textarea name="observacion" rows="3"><?= isset($licitacion['observacion']) ? verificarEmpty($licitacion['observacion']) : ''; ?></textarea>
		</div>
		<div class="eight wide field d-none">
			<div class="ui sub header">Fecha Final</div>
			<div class="ui calendar date-semantic" id="fechaFinal">
				<div class="ui input left icon">
					<i class="calendar icon"></i>
					<input type="text" placeholder="Fecha Final" value="<?= isset($licitacion['fechaFin']) ? $licitacion['fechaFin'] : '' ?>">
				</div>
			</div>
			<input type="hidden" class="date-semantic-value" name="fechaFin" value="<?= isset($licitacion['fechaFin']) ? $licitacion['fechaFin'] : '' ?>">
		</div>
	</div>
	<div class="fields">
		<div class="field">
			<div class="ui sub header">Cargo y cantidad</div>
			<a class="ui btn btn-trade-visual" onclick='Licitacion.addCargo()'>Agregar Cargo</a>
		</div>
	</div>
	<div id="divCargo">
		<?php foreach ($licitacionCargo as $kC => $vC) : ?>
			<div class="fields">
				<div class="eight wide field">
					<div class="ui sub header">Cargo</div>
					<select name="cargo" class="ui fluid dropdown semantic-dropdown" patron="requerido">
						<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'selected' => $kC, 'query' => $cargo, 'id' => 'idCargo', 'value' => 'nombre', 'class' => 'text-titlecase']); ?>
					</select>
				</div>
				<div class="seven wide field">
					<div class="ui sub header">Cantidad</div>
					<input type="text" class="ui onlyNumbers" name="cantidadCargo" placeholder="Cantidad" value="<?= $vC['cantidad']; ?>" patron="requerido">
				</div>
				<div class="one wide field">
					<div class="ui sub header text-white">.</div>
					<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
				</div>
			</div>
		<?php endforeach; ?>

	</div>
	<div class="fields">
		<div class="field">
			<div class="ui sub header">Documento requeridos</div>
			<a class="ui btn btn-trade-visual" onclick='Licitacion.addDocumento()'>Agregar Documento</a>
		</div>
	</div>
	<div id="divDocumentos">
		<?php if (!empty($licitacionDocumento)) :  ?>
			<?php foreach ($licitacionDocumento as $k => $v) : ?>
				<div class="fields">
					<div class="five wide field">
						<div class="ui sub header">Documento</div>
						<input type="text" class="ui" name="nroDocumento" placeholder="Descripción documento" value="<?= $v['documento']; ?>">
					</div>
					<div class="five wide field">
						<div class="ui sub header">Area</div>
						<select class="ui dropdown parentDependiente" id="areaForm<?= $k ?>" name="area" patron="requerido" data-childDependiente="personaForm<?= $k ?>">
							<?= htmlSelectOptionArray2(["title" => "Seleccione", "selected" => $v['idArea'], "id" => "idArea", "value" => "nombre", "query" => $area, "simple" => true, "class" => "text-titlecase"]); ?>
						</select>
					</div>
					<div class="five wide field">
						<div class="ui sub header">Persona</div>
						<select class="ui dropdown clearable semantic-dropdown" id="personaForm<?= $k ?>" name="persona">
							<?= htmlSelectOptionArray2(["title" => "Seleccione", "selected" => $v['idPersona'], "id" => "idPersonal", "value" => "nombre", "query" => $persona, "class" => "text-titlecase"]); ?>
						</select>
					</div>
					<div class="one wide field">
						<div class="ui sub header text-white">.</div>
						<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="fields">
		<div class="field">
			<a class="ui btn btn-trade-visual" onclick="$('#divDetalleLicitacion').toggleClass('d-none'); $('#iconDetalleTipo').toggleClass('slash');"><i id="iconDetalleTipo" class="icon eye slash"></i>Detalle de Tipos</a>
		</div>
	</div>
	<div id="divDetalleLicitacion" class="fields">
		<?php foreach ($tipoPresupuesto as $k => $v) : ?>
			<input type="hidden" name="chkContadorTipo" value="<?= $v['idTipoPresupuesto']; ?>">
			<div class="field">
				<div class="ui celled relaxed list">
					<div class="item">
						<div class="ui master checkbox mt-1">
							<input type="checkbox" name="chkTipoPresupuesto[<?= $v['idTipoPresupuesto']; ?>]" <?= isset($licitacionDetalle[$v['idTipoPresupuesto']]) ? 'checked' : '' ?>>
							<label style="font-size: 1.5em;"><?= $v['nombre'] ?></label>
						</div>
						<?php if (!empty($tipoPresupuestoDetalle[$v['idTipoPresupuesto']]) && $v['mostrarDetalle'] == '1') :  ?>
							<div class="list">
								<?php foreach ($tipoPresupuestoDetalle[$v['idTipoPresupuesto']] as $k1 => $v1) : ?>
									<input type="hidden" name="chkContadorTipoDetalle[<?= $v['idTipoPresupuesto'] ?>]" value="<?= $v1['idTipoPresupuestoDetalle']; ?>">
									<div class="item <?= !empty($v1['idTipoPresupuestoDetalleDependiente']) ? 'd-none idDependiente' . $v1['idTipoPresupuestoDetalleDependiente'] : '' ?>">
										<div class="ui child checkbox mt-1">
											<input type="checkbox" data-buscardependiente="<?= $v1['idTipoPresupuestoDetalle']; ?>" onchange="Licitacion.buscarCheckDependiente(this);" name="chkTipoPresupuestoDet[<?= $v['idTipoPresupuesto']; ?>][<?= $v1['idTipoPresupuestoDetalle']; ?>]" <?= (isset($licitacionDetalleSub[$v['idTipoPresupuesto']][$v1['idTipoPresupuestoDetalle']]) || $v1['chkDefault'] == '1') ? 'checked' : '' ?>>
											<label><?= $v1['nombre'] ?></label>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
	<?php if (!empty($licitacionDocumento)) :  ?>
		Licitacion.documentoCont = <?= count($licitacionDocumento) ?>
	<?php endif; ?>
</script>