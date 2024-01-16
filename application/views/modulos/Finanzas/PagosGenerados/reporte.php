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
				
					<a href="javascript:;" class="btn btn-outline-secondary border-0 btn-pagoGenerado" data-id="<?= $row['idProveedorServicioGenerado']; ?>" title="Pago"><i class=" fas fa-dollar-sign"></i></a>
					<!-- <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editarPago" title="Editar"><i class="fa fa-lg fa-edit"></i></a> -->
				</td>
				<td><?= $row['razonSocial']; ?></td>
				<td><?= $row['ruc']; ?></td>
                <td><?= $row['descripcionServicio']; ?></td>
				<td><?= $row['monto']; ?></td>
			
				<td><?= $row['fechaProgramada']; ?></td>
				<td><?= $row['numeroComprobante']; ?></td>
				<td><?= $row['canal']; ?></td>
				<td><?= $row['porcentajeDetraccion']; ?></td>
				<td><?= $row['nombreEstado']; ?></td>
	

			</tr>
            <?php $n++;?> 
            <?php endforeach ?>
        </tbody>
	</table>
</div>