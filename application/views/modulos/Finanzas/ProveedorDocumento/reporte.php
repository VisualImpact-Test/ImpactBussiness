<div class="card-datatable">
	<table id="tb-proveedorDocumento" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<!-- <th>Opciones</th> -->
				<th>Fecha</th>
				<th>Oper</th>
				<th>OC Visual</th>
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
				<th class="td-center">ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<? $ix = 0; ?>
			<?php foreach ($datos as $k => $row) : ?>
				<? $ix++; ?>
				<tr>
					<td class="td-center"><?= $ix; ?></td>
					<!-- <td class="td-center">btn</td> -->
					<td class="td-left"><?= date_change_format($row['fechaRegOC']); ?></td>
					<td class="td-center">
						<a class="btn" href="<?= base_url(); ?>Cotizacion/descargarOperDirecto/<?= $row['idOper']; ?>" target="_blank">
							<?= $row['oper']; ?>
						</a>
					</td>
					<td class="td-center">
						<a class="btn" href="<?= base_url(); ?>Cotizacion/descargarOCDirecto/<?= $row['idOrdenCompra']; ?>" target="_blank">
							<?= str_pad($row['idOrdenCompra'], 8, "0", STR_PAD_LEFT); ?>
						</a>
					</td>
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
					<td class="td-center"> PENDIENTE </td>
					<td>
						<?php if (empty($row['adjuntosCargados'])) : ?>
							<span class="ui grey large label">
								Enviado al proveedor
							</span>
						<?php else : ?>
							<a class="ui green large label">
								Documentos enviados
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>