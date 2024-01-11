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
				<th>Cant. Días</th>
				<th>Fecha de Cotización Interna</th>
				<th>Fecha de Vencimiento Orden</th>
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
				<tr data-id="< ?= $row['idCotizacion'] ?>">
					<td class="collapsing">
						<?= ($k + 1) ?>
						<input type="hidden" name="idCotizacion" value="<?= $row['idCotizacion'] ?>">
					</td>
					<td><?= verificarEmpty($row['cuenta'], 3) ?></td>
					<td><?= verificarEmpty($row['cuentaCentroCosto'], 3) ?></td>
					<td><?= verificarEmpty($row['proveedor'], 3) ?></td>
					<td><?= verificarEmpty($row['cantDias'], 3) ?></td>
					<td><?= verificarEmpty($row['fechaReg'], 3) ?></td>
					<td>
						<a class="ui basic button formFechaVencimiento" data-fechareg="<?= $row['fechaReg'] ?>" data-cantdias="<?= $row['cantDias'] ?>">
							<?= verificarEmpty($row['fechaVencimiento'], 3) ?>
						</a>
					</td>
					<td><?= verificarEmpty($row['title'], 3) ?></td>
					<td>
						<?php if (!$row['flagOcLibre'] == 0) : ?>
							<a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOCdelProveedor" data-id="<?= $row['idOrdenCompra'] ?>" data-proveedor="<?= $row['idProveedor'] ?>" data-flag="<?= $row['flagOcLibre'] ?>" data-cotizacion="<?= $row['idCotizacion'] ?>"><i class="fa fa-lg fa-file-excel" title="Descargar Cotización"></i></a>
						<?php elseif (!empty($row['flagMostrarExcel'])) : ?>
							<a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOCdelProveedor" data-id="<?= $row['idOrdenCompra'] ?>" data-proveedor="<?= $row['idProveedor'] ?>" data-flag="<?= $row['flagOcLibre'] ?>" data-cotizacion="<?= $row['idCotizacion'] ?>"><i class="fa fa-lg fa-file-excel" title="Descargar Cotización"></i></a>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
					<td><?= verificarEmpty($row['status'], 3) ?></td>
					<td>
						<?php if ($row['status'] == 'Aprobado') : ?>
							<?= verificarEmpty($row['motivoAprobacion'], 3) ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row['status'] == 'Aprobado') : ?>
							<?= verificarEmpty($row['codOrdenCompra'], 3) ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row['status'] == 'Aprobado') : ?>
							<?php if ($row['operData']['idOper'] != null) : ?>
								<a href="<?= base_url() . 'Cotizacion/descargarOperDirecto/' . $row['operData']['idOper']; ?>" class="ui button" target="_blank">
									<?= $row['operData']['requerimiento']; ?>
								</a>
							<?php else : ?>
								-
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row['status'] == 'Aprobado') :  ?>
							<?php if ($row['flagOcLibre'] == '1') : ?>
								<a href="<?= base_url() . 'OrdenCompra/visualizarPdfOCLibre/' . $row['idOrdenCompra']; ?>?flag=<?= $row['flagOcLibre'] ?>" class="ui button" data-id="<?= $row['idOrdenCompra'] ?>" target="_blank">
									<?= $row['seriado'] ?>
								</a>
							<?php else : ?>
								<a href="<?= base_url() . 'Cotizacion/descargarOCDirectoProvServ/' . $row['idOrdenCompra']; ?>?flag=<?= $row['flagOcLibre'] ?>" class="ui button" data-id="<?= $row['idOrdenCompra'] ?>" target="_blank">
									<?= $row['seriado'] ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</td>
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
						<?php $row['sustentoComp'] ?>
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
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>