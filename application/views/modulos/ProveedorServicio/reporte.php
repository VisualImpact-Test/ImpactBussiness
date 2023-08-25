<style>

</style>
<div class="card-datatable">
	<table id="tb-proveedorServicio" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th></th>
				<th>Cuenta</th>
				<th>Centro Costo</th>
				<th>Proveedor</th>
				<th>Fecha de Cotización Interna</th>
				<th>Cotización</th>
				<th>Ver Cotización</th>
				<th>Estado</th>
				<th>Descripción PO</th>
				<th>PO</th>
				<th>Oper</th>
				<th>OC Visual</th>
				<th>Validación de Artes</th>
				<th>Fecha de Ejecución</th>
				<th>Sustento de Servicio</th>
				<th>Carga de Comprobantes</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 1; ?>
			<?php foreach ($data as $k => $row) : ?>
				<tr data-id="<?= $row['idCotizacion'] ?>">
					<td class="collapsing">
						<?= ($k + 1) ?>
						<input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $row['idCotizacionDetalleProveedor'] ?>">
					</td>
					<td><?= verificarEmpty($row['cuenta'], 3) ?></td>
					<td><?= verificarEmpty($row['cuentaCentroCosto'], 3) ?></td>
					<td><?= verificarEmpty($row['proveedor'], 3) ?></td>
					<td><?= verificarEmpty($row['fechaEmision'], 3) ?></td>
					<td><?= verificarEmpty($row['title'], 3) ?></td>
					<td>
						<a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarCotizacion" data-id="<?= $row['idCotizacion']; ?>"><i class="icon eye" title="Generar PDF cotizacion"></i></a>
					</td>
					<td><?= verificarEmpty($row['status'], 3) ?></td>
					<td><?= verificarEmpty($row['motivoAprobacion'], 3) ?></td>
					<td><?= verificarEmpty($row['codOrdenCompra'], 3) ?></td>
					<td><?= $row['operData']['requerimiento']; ?></td>
					<td>
						<?php if ($row['status'] == 'Aprobado' || $row['status'] == 'Vencido') :  ?>
							<a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOCdelProveedor" data-id="<?= $row['idCotizacion'] ?>" data-proveedor="<?= $row['idProveedor'] ?>"><i class="fa fa-lg fa-file-excel" title="Descargar Cotización"></i></a>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row['status'] == 'Aprobado') :  ?>
							<?php if ($row['mostrarValidacion'] == '1') :  ?>
								<div class="ui">
									<a class="ui basic button formValArt" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>">
										<i class="icon upload"></i>
										Subir artes
									</a>
								</div>
							<?php elseif ($row['mostrarValidacion'] == '2') : ?>
								-
								<!-- No requiere Arte -->
							<?php else : ?>
								<a class="ui basic button formLisArts" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>">
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
											<a class="ui basic button formFechaEje" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>">
												<i class="icon calendar"></i>
												Indicar Fecha Ejecución
											</a>
										</div>
									<?php else : ?>
										<?php if (!empty($row['adjuntoFechaEjecucion'])) :  ?>
											<a class="ui button" href="<?= RUTA_WASABI . 'fechaEjecucion/' . $row['adjuntoFechaEjecucion'][0]['nombre_archivo']; ?>" target="_blank">
											<?php endif; ?>
											<?php if ($row['fechaInicio'] == '1900-01-01') :  ?>
												Se adjunto archivo
											<?php else : ?>
												Del <?= date_change_format($row['fechaInicio']) ?> al <?= date_change_format($row['fechaFinal']) ?>
											<?php endif; ?>
											<?php if (!empty($row['adjuntoFechaEjecucion'])) :  ?>
											</a>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</td>

					<td>
						<?php if ($row['status'] == 'Aprobado' && $row['solicitarFecha'] == '1' && $row['flagFechaRegistro'] == '1') :  ?>
							<?php if (empty($row['sustentoComp'][$row['idCotizacionDetalleProveedor']])) :  ?>
								<a class="ui basic button formSustServ" data-idcotdetpro="<?= $row['idCotizacionDetalleProveedor'] ?>">
									<i class="icon upload"></i>
									Indicar Sustento
								</a>
							<?php else : ?>
								<a class="ui basic button formLisSustServ dicdp-<?= $row['idCotizacionDetalleProveedor'] ?>" data-idcotdetpro="<?= $row['idCotizacionDetalleProveedor'] ?>">
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
												<a class="ui basic button formSustento" data-idcoti="<?= $row['idCotizacion'] ?>" data-prov="<?= $row['idProveedor'] ?>" data-requiereguia="<?= $row['requiereGuia'] ?>">
													<i class="icon archive"></i>
													Indicar Sustento
												</a>
											</div>
										<?php else : ?>
											<a class="ui basic button formLisSustComprobante dicdp-<?= $row['idCotizacionDetalleProveedor'] ?>" data-idcotdetpro="<?= $row['idCotizacionDetalleProveedor'] ?>">
												<i class="icon search"></i>
												Sustento enviado correctamente
											</a>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<!-- <td>
						<?php if ($row['status'] == 'Aprobado') :  ?>
							<?php if ($row['solicitarFecha'] == '1') :  ?>
								<?php if ($row['flagFechaRegistro'] == '1') :  ?>
									<?php if ($row['flagSustentoServicio'] == '1') :  ?>
										<?php if (empty($row['sustentoC'])) :  ?>
											En proceso
										<?php else : ?>
											<?php if ($row['sustentoC'][$row['idCotizacion']][$row['idProveedor']]['flagIncidencia'] == '1') :  ?>
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
					<td>
						<?php if (!empty($row['ocGen'])) :  ?>
							<?php foreach ($row['ocGen'] as $koc => $voc) : ?>
								<a href="<?= index_page() . '../FormularioProveedor/viewOrdenCompra/' . $voc['idOrdenCompra'] . $row['link'] ?>" class="btn btn-outline-secondary border-0 btn-OC btn-dp-<?= $row['idCotizacion']; ?>">
									<i class="icon file alternate outline" title="Validar OC"></i>
								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</td> -->
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>