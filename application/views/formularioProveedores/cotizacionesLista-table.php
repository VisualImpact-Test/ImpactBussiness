<div class="card-datatable">
    <form id="frmCotizacionesProveedor">
        <input type="hidden" name="idProveedor" value="<?=$idProveedor?>">
        <table id="tb-cotizaciones" class="ui compact celled definition table">
            <thead class="full-width">
                <tr>
                    <th></th>
                    <th>Opciones</th>
                    <th>Fecha Emisión</th>
                    <th>Cotización</th>
                    <th>Cuenta</th>
                    <th>Centro Costo</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($datos as $k => $row) { ?>
                    <tr data-id="<?= $row['idCotizacion'] ?>">
                        <td class="collapsing">
                            <?= ($k + 1) ?>
                            <input type="hidden" name="idCotizacionDetalleProveedor" value="<?= $row['idCotizacionDetalleProveedor'] ?>">
                        </td>
                        <td><a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>"><i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i></a></td>
                        <td><?= verificarEmpty($row['fechaEmision'], 3) ?></td>
                        <td><?= verificarEmpty($row['nombre'], 3) ?></td>
                        <td><?= verificarEmpty($row['cuenta'], 3) ?></td>
                        <td><?= verificarEmpty($row['cuentaCentroCosto'], 3) ?></td>
                    </tr>
                <? } ?>
            </tbody>
            <tfoot class="full-width">
                <tr>
                    <th></th>
                    <th colspan="5">
                        <!-- <div class="ui right floated small primary labeled icon button btnGuardarCotizacion">
                            <i class="save icon"></i> <span class="">Guardar</span>
                        </div> -->
                        <div class="ui right floated small button btnRefreshCotizaciones">
                            <i class="sync icon"></i>
                            Refresh
                        </div>
                        <div class="ui right floated small red button btnLogoutProveedor">
                            <i class="power off icon"></i>
                            <span class="">Salir</span>
                        </div>

                    </th>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
