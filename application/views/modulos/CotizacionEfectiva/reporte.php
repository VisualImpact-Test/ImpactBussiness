<div class="card-datatable">
    <table id="tb-cotizacionEfectiva" class="mb-0 table table-bordered text-nowrap" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>FECHA EMISION</th>
                <th>NOMBRE</th>
                <th>CUENTA</th>
                <th>CENTRO COSTO</th>
                <th>NRO COTIZACION</th>
                <th>ESTADO DEL PROCESO</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($datos as $k => $row) { ?>
                <tr data-id="<?=$row['idCotizacion']?>" role="row" class="even">
                    <td class="td-center sorting_1"><?=($k + 1)?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacionEfectiva btn-dp-26"><i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion Efectiva"></i></a>
                        <?if($row['idCotizacionEstado'] != 7){?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-finalizarCotizacion btn-dp-26"><i class="check icon" title="Finalizar Cotizacion"></i></a>
                        <?}?>
                    </td>
                    <td class="td-center"><?= verificarEmpty($row['fechaEmision'],2)?></td>
                    <td class="td-left"><?= verificarEmpty($row['cotizacion'],2)?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuenta'],2)?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentaCentroCosto'],2)?></td>
                    <td class="td-left"><?= verificarEmpty($row['codCotizacion'],2)?></td>
                    <td class="text-center style-icons">
                        <span class="badge badge-warning" id="spanEstado-26"><?= verificarEmpty($row['cotizacionEstado'],2)?></span>
                    </td>
                </tr>
            <? } ?>
        </tbody>
    </table>
</div>