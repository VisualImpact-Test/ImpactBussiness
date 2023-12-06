<div class="card-datatable">
    <table id="tb-ordenesCompra" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th></th>
                <th>N° DE ORDEN</th>
                <th>CODIGO OPER</th>
                <th>N° DE RQ</th>
                <th>USUARIO REGISTRO</th>
                <th>FECHA REGISTRO</th>
                <th>COTIZACIONES</th>
                <!-- <th class="td-center">ESTADO</th> -->
            </tr>
        </thead>
        <tbody>
            <? foreach ($data as $k => $row) : ?>
                <tr data-id="<?= $row['idOrdenCompra'] ?>">
                    <td class="text-center"> <?= ($k + 1) ?></td>
                    <td class="text-center">
                        <a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarOrdenCompra"><i class="fa fa-lg fa-file-pdf" title="Descargar pdf"></i></a>
                    </td>
                    <td class="text-left"> <?= $row['seriado'] ?></td>
                    <td class="text-left"><?= $row['idOrdenCompra'] . substr(implode(',', $cotizaciones[$row['idOrdenCompra']]), 3, 4) ?></td>
                    <td class="text-left"> <?= !empty($row['requerimiento']) ? $row['requerimiento'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($row['usuario']) ? $row['usuario'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($row['fechaReg']) ? $row['fechaReg'] : '-' ?></td>
                    <td class="text-left"> <?= !empty($cotizaciones[$row['idOrdenCompra']]) ? implode(',', $cotizaciones[$row['idOrdenCompra']]) : '-' ?></td>

                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        $('#tb-ordenesCompra').DataTable();
    });
</script>