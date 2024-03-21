<?php foreach ($ordenServicioFecha as $k => $v) :
	$anio = date('Y', strtotime($v['fecha']));
	$mes = date('n', strtotime($v['fecha']));
	if (!isset($contadorMeses[$anio])) {
		$contadorMeses[$anio] = array_fill(1, 12, 0);
	}
	$contadorMeses[$anio][$mes]++;
endforeach;  ?>
<form class="form" role="form" id="formDatosDeZona" method="post" autoComplete="off">
	<input type="hidden" name="idCargo" value="<?= $idCargo ?>">
	<input type="hidden" name="cantidadFechas" value="<?= count($ordenServicioFecha) ?>">
	<div class="row pt-4 px-4">
		<div id="divTabla" class="ui table">
			<table class="ui sortable table" id="tablaFechaPersona">
				<thead>
					<tr>
						<th rowspan="2" class="three wide p-0 "><label class="text-white">________________</label></th>
						<?php foreach ($contadorMeses as $anio => $meses) : $totalMeses = array_sum($meses); ?>
							<th class="one wide p-0 " colspan="<?php echo $totalMeses ?>" style="text-align: center; "><?php echo $anio ?></th>
						<?php endforeach; ?>
						<th rowspan="2" class="one wide p-0 align-center">
							<button class="btn btn-success" type="button" onclick="OrdenServicio.agregarZona(this);" data-idcargo="<?= $idCargo ?>" data-meses="<?= count($ordenServicioFecha) ?>"><i class="icon plus"></i></button>
						</th>
					</tr>
					<tr>
						<?php foreach ($ordenServicioFecha as $k => $v) : ?>
							<?php $numeroMes = date('n', strtotime($v['fecha'])); ?>
							<th class="one wide p-0 ">
								<div class="ui input transparent">
									<input type="hidden" value="<?= strpos($v['fecha'], '-') ? date_change_format($v['fecha']) : $v['fecha']; ?>" class="form-control text-center" patron="requerido" readonly>
									<input type="text" class="form-control text-center" value="<?= NOMBRE_MES_REDU[$numeroMes]; ?>">

								</div>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($dataPrevia)) : ?>
						<?php foreach ($dataPrevia as $k => $v) : ?>
							<tr>
								<td>
									<div class="fields">
										<div class="sixteen wide field d-none divRegion">
											<div class="ui sub header">Region</div>
											<select class="ui dropdown cboRegion" name="departamento">
												<option value="<?= $v['departamento'] ?>"><?= $v['nombreDepartamento'] ?></option>
											</select>
										</div>
										<div class="sixteen wide field d-none divProvincia">
											<div class="ui sub header">Provincia</div>
											<select class="ui dropdown cboProvincia" name="provincia" onchange="OrdenServicio.buscarDistrito(this,'tr');" patron="requerido">
												<option value="<?= $v['provincia'] ?>"><?= $v['nombreProvincia'] ?></option>
											</select>
										</div>
										<div class="sixteen wide field divDistrito">
											<div class="ui sub header">Distrito</div>
											<select class="ui dropdown cboDistrito" name="distrito" patron="requerido">
												<option value="<?= $v['distrito'] ?>"><?= $v['nombreDistrito'] ?></option>
											</select>
										</div>
									</div>
								</td>
								<?php for ($i = 1; $i <= $cantidadDeMeses; $i++) : ?>
									<td>
										<div class="ui input fluid">
											<input name="cantidadCargoFecha[<?= $idCargo ?>][<?= ($i - 1) ?>]" class="text-center keyUpChange mesNro<?= $i ?>" value="<?= $v['cantidadCargoFecha[' . $v['idCargo'] . '][' . ($i - 1) . ']'] ?>" onchange="OrdenServicio.calcularMontoZonaMes(this)" data-nromes="<?= $i ?>">
										</div>
									</td>
								<?php endfor; ?>
								<td>
									<button class="ui red button" type="button" onclick="$(this).closest('tr').find('input').val('0').change(); $(this).closest('tr').remove();"><i class="icon trash"></i></button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td><?= $nameCargo ?></td>
						<?php $nroMes = 0 ?>
						<?php foreach ($ordenServicioFecha as $kf => $vf) : ?>
							<td>
								<div class="ui input fluid">
									<input type="text" name="cantidadCargoFechaTotal[<?= $idCargo ?>][<?= $kf ?>]" value="<?= isset($totalCantidad[$nroMes]) ? $totalCantidad[$nroMes] : 0  ?>" class="form-control onlyNumbers text-center mesNro<?= ++$nroMes ?>" patron="requerido" readonly>
								</div>
							</td>
						<?php endforeach; ?>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>