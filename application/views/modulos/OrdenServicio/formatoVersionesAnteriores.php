<div class="ui table">
	<table class="ui celled table" id="tablaMovilidad">
		<thead>
			<tr>
				<th class="one wide">Versión</th>
				<th class="five wide">Nombre</th>
				<th class="four wide">Cuenta & CC / Cliente</th>
				<th class="two wide">Fecha</th>
				<th class="two wide">Total</th>
				<th class="two wide">Opciones</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($versionesAnteriores as $key => $row) : ?>
				<tr class="data <?php if (isset($aprobado)) :
						if ($row['idPresupuestoHistorico'] == $aprobado) :?>green<?php endif; ?><?php endif; ?>"
						data-id="<?= $row['idPresupuesto']; ?>" data-version="<?= $row['idPresupuestoHistorico']; ?>">
					<td class="center aligned"><?= verificarEmpty($row['versionPresupuesto'], 3); ?></td>
					<td><?= verificarEmpty($row['nombreOrdenServicio'], 3); ?></td>
					<?php if ($row['chkUtilizarCliente'] == 1) : ?>
						<td><?= verificarEmpty($row['nombreCliente'], 3); ?> </td>
					<?php else : ?>
						<td><?= verificarEmpty($row['nombreCuenta'], 3); ?> - <?= verificarEmpty($row['centroCosto'], 3); ?></td>
					<?php endif; ?>
					<td><?= date_change_format(verificarEmpty($row['Fecha'], 4)); ?></td>
					<td><?= verificarEmpty($row['total'], 3); ?></td>
					<td class="center aligned">
						<button class="btn btn-outline-secondary border-0 btn-download" data-ruta="OrdenServicio/generarPdf/<?= $row['idPresupuesto'] ?>/<?= $row['idPresupuestoHistorico'] ?>" title="Imprimir">
							<i class="icon file pdf"></i>
						</button>
						<?php if ($idOrdenServicioEstado < 3) : ?>
							<button class="btn btn-outline-secondary border-0 btn-aprobarPresupuesto" title="Aprobar">
								<i class="icon check"></i>
							</button>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>