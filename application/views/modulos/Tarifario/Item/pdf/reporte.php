<div>
	<table class="tb-detalle">
		<thead class="square">
			<tr>
				<th>ID</th>
				<th>MARCA</th>
				<th>CATEGORIA</th>
				<th>ITEM</th>
				<th>CARACTERISTICAS</th>
				<?php foreach ($dataProveedor as $row) : ?>
					<th nowrap class="text-center"><?= (strlen($row['nproveedor']) > 16) ? substr($row['nproveedor'], 0, 16) : $row['nproveedor']; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody class="square">
			<?php $i = 0; ?>
			<?php foreach ($dataItem as $itm) : ?>
				<tr>
					<td><?= ++$i; ?></td>
					<td><?= $itm['itemMarca']; ?></td>
					<td><?= strtoupper($itm['itemCategoria']) . verificarEmpty($itm['itemSubCategoria'], 1, ' /'); ?></td>
					<td><?= $itm['item']; ?></td>
					<td><?= $itm['caracteristicas']; ?></td>
					<?php foreach ($dataProveedor as $pro) : ?>
						<?= $style = 'rojo'; ?>
						<?php if (!empty($dataItemProveedor[$itm['idItem']][$pro['idProveedor']]['flag_actual'])) :  ?>
							<?= $style = 'verde'; ?>
						<?php endif; ?>
						<td class="text-center <?= $style; ?>">
							<?php if (!empty($dataItemProveedor[$itm['idItem']][$pro['idProveedor']]['costo'])) :  ?>
								<?= moneda($dataItemProveedor[$itm['idItem']][$pro['idProveedor']]['costo'], false, 2); ?><br>
								<?= date_change_format($dataItemProveedor[$itm['idItem']][$pro['idProveedor']]['fechaVigencia']); ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>