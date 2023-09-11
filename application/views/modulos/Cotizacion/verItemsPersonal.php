<table class="ui celled table no-footer">
    <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Cantidad</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? $i=1;foreach($data as $row){ ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$row['nombre']?></td>
                <td><?=$row['cantidad_personal']?></td>
                <td><button class=" btn btn-outline-secondary border-0 generar-requerimiento-rrhh" data-id="<?=$row['idCotizacionDetalle']?>"><i class="fas fa-plus"></i></button></td>
            </tr>
        <? $i++; } ?>
    </tbody>
</table>