<div class="card-datatable">
	<table id="tb-proveedorDocumento" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>Fecha</th>
				<th>Oper</th>
				<th>OC Visual</th>
				<th>Fecha Emision</th>
				<th>RUC</th>
				<th>Proveedor</th>
				<th>Cuenta</th>
				<th>Centro Costo</th>
				<th>Descripción Tracking</th>
				<th>Descripción Compras</th>
				<th>Importe Sin IGV</th>
				<th>Importe Inc. IGV</th>
				<th>Moneda</th>
				<th>PO Cliente</th>
				<th>GR</th>
				<th>N° Factura</th>
				<th>Estado</th>
				<th>Cuenta</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 0; ?>
			<?php foreach ($datos as $k => $row) : ?>
				<? $ix++; ?>
				<tr data-id="<?= $row['idOrdenCompra'] ?>" data-flag="<?= $row['flagOcLibre'] ?>" data-monto="<?= $row['monto'] * (1 + ($row['igv'] / 100)); ?>">
					<td class="td-center"><?= $ix; ?></td>
					<td class="td-left"><?= date_change_format($row['fechaRegOC']); ?></td>
					<td class="td-center">
						<?php if ($row['oper'] != '-') : ?>
							<a class="btn" href="<?= base_url(); ?>Cotizacion/descargarOperDirecto/<?= $row['idOper']; ?>" target="_blank">
								<?= $row['oper']; ?>
							</a>
						<? else : ?>
							<?= $row['oper']; ?>
						<? endif; ?>
					</td>
					<td class="td-center">
						<?php $rutaOc = $row['flagOcLibre'] ? '../OrdenCompra/visualizarPdfOCLibre/' . $row['idOrdenCompra'] . '?flag=' . $row['flagOcLibre'] :
							'../Cotizacion/descargarOCDirecto/' . $row['idOrdenCompra']; ?>
						<a class="btn" href="<?= $rutaOc; ?>" target="_blank">
							<?= $row['ordenCompra']; ?>
						</a>
					</td>
					<td class="td-center"><?= !empty($row['fechaEmision']) ? date_change_format($row['fechaEmision']) : '-' ?></td>
					<td class="td-center"><?= $row['rucProveedor']; ?></td>
					<td class="td-left"><?= $row['razonSocial'] ?></td>
					<td class="td-left"><?= $row['cuenta']; ?></td>
					<td class="td-left"><?= $row['centroCosto']; ?></td>
					<td class="td-left"><?= $row['desTracking']; ?></td>
					<td class="td-left"><?= $row['cotizacion']; ?></td>
					<td class="td-right"><?= moneda($row['monto']); ?></td>
					<td class="td-right"><?= moneda($row['monto'] * (1 + ($row['igv'] / 100))); ?></td>
					<td class="td-center"><?= $row['nombreMoneda']; ?></td>
					<td class="td-center"><?= $row['poCliente']; ?></td>
					<td class="td-center"><?= $row['numeroGR']; ?></td>
					<td class="td-center"><?= verificarEmpty($row['numeroDocumento'], 3); ?></td>
					<td>
						<?php if (empty($row['adjuntosCargados'])) : ?>
							<span class="ui grey large label">
								Enviado al proveedor
							</span>
						<?php else : ?>
							<a class="ui green large label btn-sustentosCargados" style="text-align: center;">
								Documentos enviados <br>
								(<?= $row['aprobados']; ?>/<?= $row['totalDocumentos']; ?>)
							</a>
						<?php endif; ?>
					</td>
					<td class="td-left">
					<?php if (preg_match('/\d/', $row['cuentas_bancos'])) : ?>
                            <?= verificarEmpty($row['cuentas_bancos'], 3); ?>
                            <style>
                                .td-left hr {
                                    border: none;
                                    border-top: 1px solid grey;
                                    margin: 5px 0;
                                }
                            </style>
                            <hr>
                            <?php if (preg_match('/\d/', $row['ccis_bancos'])) : ?>
                                <?= verificarEmpty($row['ccis_bancos'], 3); ?>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        <?php else : ?>
                            -
                        <?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>