<div class="card-datatable">
	<table id="tb-proveedorServicio" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
				<th>OPCIONES</th>
				<th>RAZON SOCIAL</th>
				<th>RUC</th>
                <th>DESCRIPCIÓN SERVICIO</th>
				<th>MONTO</th>
			
				<th>FECHA DE PAGO</th>
				<th>NUM COMPROBANTE</th>
				<th>CENTRO DE COSTO</th>
				<th>DETRACCION</th>
				<th>ESTADO PAGO</th>
	

			</tr>
		</thead>
        <tbody>
        <? $n = 1; ?>
			<?php foreach ($pagosGenerados as $k => $row) : ?>
              
                <tr>
				<td class="td-center"><?= $n; ?></td>
				<td>
					<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-registrarFacturas" data-id="<?= $row['idProveedorServicioGenerado']; ?>" title="Pago">  <i class=" fas fa-money-bill"></i></a>
					<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-pagoGenerado" data-id="<?= $row['idProveedorServicioGenerado']; ?>" title="Pago"><i class=" fas fa-dollar-sign"></i></a>
				</td>
				<td><?= $row['datosProveedor']; ?></td>
				<td><?= $row['numDocumento']; ?></td>
                <td><?= $row['descripcionServicio']; ?></td>
				<td class="text-right"><?= $row['monto']; ?></td>
			
				<td class="text-center"><?= getFechaDias($row['fechaProgramada']);  ?></td>
				<td class="text-center"><?= $row['numeroComprobante']; ?></td>
				<td class="text-center"><?= $row['canal']; ?></td>
				<td class="text-right"><?= $row['porcentajeDetraccion']; ?> %</td>
				<?php $estado = ($row['idEstadoPago']== 1 ) ? 'red' : 'green' ; ?>
				<td><span class="ui <?= $estado ?> large label claseEstado"><?= $row['nombreEstado']; ?></span></td>
	
	

			</tr>
            <?php $n++;?> 
            <?php endforeach ?>
        </tbody>
	</table>
</div>