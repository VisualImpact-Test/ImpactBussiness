<h3>Detalle de Cotizacion</h3>
<ul>
    <li><label class="font-weight-bold">Cotización: </label> <?= $data['nombreCotizacion']?> </li>
    <li><label class="font-weight-bold">Código:</label> <?= $data['codigoCotizacion']?> </li>
    <li><label class="font-weight-bold">Creado:</label> <?= get_fecha_larga($data['fechaCreacion'])?> </li>
    <li><label class="font-weight-bold">Estado:</label> <label class="text-danger font-weight-bold"> <?= $data['nombreEstado']?></label> </li>
</ul><br>
<label class="font-weight-bold">Esta Cotización ha sido anulada por: </label>
<ul>
    <li><label class="font-weight-bold">Nombre de usuario:</label> <?= $data['nombreUsuario']?>  <?= $data['apellidoUsuario']?> </li>
    <li><label class="font-weight-bold">Fecha de anulación:</label> <?= get_fecha_larga($data['fechaRegistro'])?>  <?= horaFormato($data['horaRegistro'])?></li>
</ul>