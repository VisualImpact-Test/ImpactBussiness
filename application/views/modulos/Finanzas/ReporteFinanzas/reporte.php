<style> 
.claseEstado{
    font-size: 12px!important; 
    width: 70px;
    text-align: center;
}
.tdtext{
    display: flex;
    justify-content: center;
}
</style>
<div class="card-datatable">
	<table id="tb-reporteFinanzas" class="ui celled table" width="100%">
		<thead>
			<tr>
				<th class="td-center">#</th>
                <th>CUENTA</th>
				<th>CENTRO DE COSTO</th>
				<th>RAZON SOCIAL</th>
				<th>RUC</th>
                <th>DESCRIPCIÓN SERVICIO</th>
                
				<th>MONEDA</th>
                <th>MONTO</th>
                <th>DETRACCION</th>
                <th>MONTO DETRAC.</th>
                <th>FECHA PROGRAMADA</th>
				<th>FECHA DE PAGO</th>
				<th>NUM COMPROBANTE</th>
				<th>ESTADO PAGO</th>
			</tr>
		</thead>
        <tbody>
        <? $n = 1; ?>
			<?php foreach ($pagosGenerados as $k => $row) : ?>
              
                <tr>
				<td class="td-center"><?= $n; ?></td>
                <td><?= $row['cuenta']; ?></td>
                <td><?= $row['canal']; ?></td>
				<td class=""><?= $row['razonSocial']; ?></td>
				<td><?= $row['ruc']; ?></td>
                <td><?= $row['descripcionServicio']; ?></td>
                
                <td class="text-center"><?= $row['moneda']; ?></td>
				<td class="text-right"><?= ($row['idEstadoPago']== 1 ) ? '-' :  numeroVista($row['monto']); ?></td>
                <td class="text-right"><?= $row['porcentajeDetraccion']; ?> %</td>
                <td class="text-right"><?= ($row['idEstadoPago']== 1 ) ? '-' :  numeroVista($row['montoDetraccion']); ?></td>
                <td class="text-center"><?= getFechaDias($row['fechaProgramada']); ?></td>
				<td class="text-center"><?= ($row['idEstadoPago']== 1 ) ? '-' :  getFechaDias($row['fechaPagoComprobante']);   ?></td>
				<td class="text-center"><?= $row['numeroComprobante']; ?></td>
                <?php $estado = ($row['idEstadoPago']== 1 ) ? 'red' : 'green' ; ?>
				<td><span class="ui <?= $estado ?> large label claseEstado"><?= $row['nombreEstado']; ?></span></td>
	

			</tr>
            <?php $n++;?> 
            <?php endforeach ?>
        </tbody>
	</table>
</div>