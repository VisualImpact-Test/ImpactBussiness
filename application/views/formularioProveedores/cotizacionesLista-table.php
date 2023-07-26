<div class="card-datatable">
	<form id="frmCotizacionesProveedor">
		<input type="hidden" name="idProveedor" value="<?= $idProveedor ?>">
		<table id="tb-cotizaciones" class="ui compact celled definition table">
			<thead class="full-width">
				<tr>
					<th></th>
					<th>Opciones</th>
					<th>Fecha Emisión</th>
					<th>Cotización</th>
					<th>Cuenta</th>
					<th>Centro Costo</th>
					<th>Estado</th>
					<th>Validación de Artes</th>
					<th>Fecha de Ejecución</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($datos as $k => $row) { ?>
					<tr data-id="<?= $row['idCotizacion'] ?>">
						<td class="collapsing">
							<?= ($k + 1) ?>
							<input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $row['idCotizacionDetalleProveedor'] ?>">
						</td>
						<td>
							<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>">
								<i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i>
							</a>
						</td>
						<td><?= verificarEmpty($row['fechaEmision'], 3) ?></td>
						<td><?= verificarEmpty($row['title'], 3) ?></td>
						<td><?= verificarEmpty($row['cuenta'], 3) ?></td>
						<td><?= verificarEmpty($row['cuentaCentroCosto'], 3) ?></td>
						<td><?= verificarEmpty($row['status'], 3) ?></td>
						<td>
							<?php if ($row['status'] == 'Aprobado') :  ?>
								<?php if ($row['mostrarValidacion'] == '1') :  ?>
									<div class="ui buttons">
										<input id="invisibleupload1" type="file" class="ui invisible file input file-uploadedd d-none" lang="es" multiple>
										<label for="invisibleupload1" class="ui blue icon button">
											<i class="file icon"></i>
											Indicar Archivos
										</label>
										<div class="ui center floated small green button btnCargarValidacion" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>">
											<i class="save icon"></i>
										</div>
									</div>
								<?php else : ?>
									Arte enviado Correctamente
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td class="tdFecha">
							<div class="ui form">
								<?php if ($row['status'] == 'Aprobado') :  ?>
									<?php if ($row['solicitarFecha'] == '1') :  ?>
										<?php if (empty($row['fechaFinal'])) :  ?>
											<div class="field">
												<label>Fecha Inicial</label>
												<input type="date" class="fechaIni px-0" name="fechaIni">
											</div>
											<div class="field">
												<label>Fecha Final</label>
												<input type="date" class="fechaFin px-0" name="fechaFin">
											</div>
											<div class="ui center floated small green button btnGuardarFecha" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>">
												<i class="save icon"></i>
											</div>
											<?php  else : ?>
												Del <?= date_change_format($row['fechaInicio']) ?> al <?= date_change_format($row['fechaFinal']) ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<? } ?>
			</tbody>
			<tfoot class="full-width">
				<tr>
					<th></th>
					<th colspan="8">
						<div class="ui right floated small button btnRefreshCotizaciones">
							<i class="sync icon"></i>
							Refresh
						</div>
						<div class="ui right floated small red button btnLogoutProveedor">
							<i class="power off icon"></i>
							<span class="">Salir</span>
						</div>

					</th>
				</tr>
			</tfoot>
		</table>
	</form>
</div>