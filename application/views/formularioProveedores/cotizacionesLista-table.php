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
					<th>Sustento de Servicio</th>
					<th>Carga de Comprobantes</th>
					<th>Comentario</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($datos as $k => $row) { ?>
					<tr data-id="<?= $row['idCotizacion'] ?>">
						<td class="collapsing">
							<?= ($k + 1) ?>
							<input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $row['idCotizacion'] ?>">
						</td>
						<td>
							<?php if (!empty($row['idCotizacionDetalleProveedor'])) : ?>
								<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>">
									<i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i>
								</a>
							<?php endif; ?>
							<?php if (!empty($row['ocGen']) && !empty($row['flagOcLibre']) == 0) :  ?>
									<a href="<?= index_page() . '../FormularioProveedor/viewOrdenCompra/' . $row['idOrdenCompra'] . $row['link'] ?>" class="btn btn-outline-secondary border-0 btn-OC btn-dp-<?= $row['idCotizacion']; ?>">
										<i class="icon file alternate outline" title="Validar OC"></i>
									</a>
							<?php endif; ?>
						</td>
						<td><?= verificarEmpty($row['fechaEmision'], 3) ?></td>
						<td><?= verificarEmpty($row['title'], 3) ?></td>
						<td><?= verificarEmpty($row['cuenta'], 3) ?></td>
						<td><?= verificarEmpty($row['cuentaCentroCosto'], 3) ?></td>
						<td><?= verificarEmpty($row['status'], 3) ?></td>
						<td>
							<?php if ($row['status'] == 'Aprobado') :  ?>
								<?php if ($row['mostrarValidacion'] == '1') :  ?>
									<div class="ui">
										<a class="ui basic button formValArt" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
											<i class="icon upload"></i>
											Subir artes
										</a>
									</div>
								<?php elseif ($row['mostrarValidacion'] == '2') : ?>
									-
									<!-- No requiere Arte -->
								<?php else : ?>
									<a class="ui basic button formLisArts" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
										<i class="icon search"></i>
										Arte enviado
									</a>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td class="tdFecha">
							<div class="ui form">
								<?php if ($row['status'] == 'Aprobado') :  ?>
									<?php if ($row['solicitarFecha'] == '1') :  ?>
										<?php if ($row['flagFechaRegistro'] == '0') :  ?>
											<div class="ui">
												<a class="ui basic button formFechaEje" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
													<i class="icon calendar"></i>
													Indicar Fecha Ejecución
												</a>
											</div>
										<?php else : ?>
											<?php if (!empty($row['adjuntoFechaEjecucion'][0]['nombre_archivo'])) : ?>
												<a class="ui button" href="<?= RUTA_WASABI . 'fechaEjecucion/' . $row['adjuntoFechaEjecucion'][0]['nombre_archivo']; ?>" target="_blank">
												<?php endif; ?>
												<?php if ($row['fechaInicio'] == '1900-01-01') : ?>
													<a class="ui basic button formFechaV" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
														Se adjunto archivo
													</a>
												<?php else : ?>
													<a class="ui basic button formFechaV" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
														Del <?= date_change_format($row['fechaInicio']) ?> al <?= date_change_format($row['fechaFinal']) ?>
													</a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
							</div>
						</td>
						<td>
							<?php if (
								$row['status'] == 'Aprobado' && $row['solicitarFecha'] == '1' &&
								$row['flagFechaRegistro'] == '1'
							) :  ?>
								<?php if (empty([$row['sustentoComp']]) || $row['sustentoComp'] == NULL) :  ?>
									<a class="ui basic button formSustServ" data-idcotdetpro="<?= $row['idCotizacion'] ?>" data-idcot="<?= $row['idCotizacion'] ?>" data-idpro="<?= $row['idProveedor'] ?>" data-oc="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
										<i class="icon upload"></i>
										Indicar Sustento
									</a>
								<?php else : ?>
									<a class="ui basic button formLisSustServ dicdp-<?= $row['idOrdenCompra'] ?>" data-idocdetpro="<?= $row['idOrdenCompra'] ?>" data-idcot="<?= $row['idCotizacion'] ?>" data-idpro="<?= $row['idProveedor'] ?>" data-flag="<?= $row['flagOcLibre'] ?>">
										<i class="icon search"></i>
										Sustento Enviado
									</a>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td>
						<?php if ($row['status'] == 'Aprobado') :  ?>
							<?php if ($row['solicitarFecha'] == '1') :  ?>
								<?php if ($row['flagFechaRegistro'] == '1') :  ?>
									<?php if ($row['flagSustentoServicio'] == '1') :  ?>
										<?php if (empty($row['sustentoC'])) :  ?>
											<div class="ui">
												<a class="ui basic button formSustento" data-idoc="<?= $row['idOrdenCompra'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-requiereguia="<?= $row['requiereGuia'] ?>" data-flag="<?= $row['flagOcLibre'] ?>" data-idcot="<?= $row['idCotizacion'] ?>">
													<i class="icon archive"></i>
													Indicar Sustento
												</a>
											</div>
										<?php else : ?>
											<a class="ui basic button formLisSustComprobante dicdp-<?= $row['idOrdenCompra'] ?>" data-idoc="<?= $row['idOrdenCompra'] ?>" data-idcot="<?= $row['idCotizacion'] ?>" data-idpro="<?= $row['idProveedor'] ?>" data-flag="<?= $row['flagOcLibre'] ?>" data-seriado="<?= $row['seriado'] ?>">
												<i class="icon search"></i>
												Sustento enviado correctamente
											</a>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
						</td>
						<td>
							<?php if ($row['status'] == 'Aprobado') :  ?>
								<?php if ($row['solicitarFecha'] == '1') :  ?>
									<?php if ($row['flagFechaRegistro'] == '1') :  ?>
										<?php if ($row['flagSustentoServicio'] == '1') :  ?>
											<?php if (empty($row['sustentoC'])) :  ?>
												En proceso
											<?php else : ?>
												<?php if ($row['sustentoC'][$row['idOrdenCompra']][$row['idProveedor']]['flagIncidencia'] == '1') :  ?>
													Finalizado con incidencia.
												<?php else : ?>
													Finalizado al 100%
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					</tr>
				<? } ?>
			</tbody>
			<tfoot class="full-width">
				<tr>
					<th></th>
					<th colspan="11">
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