<div class="card-datatable">
    <form id="frmCotizacionesProveedor">
        <input type="hidden" name="idProveedor" value="<?=$idProveedor?>">
        <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $idCotizacion ?>">
        <table id="tb-cotizaciones" class="ui compact celled definition table">
            <thead class="full-width">
                <tr>
                    <th></th>
                    <th class="w-25">Tipo Item</th>
                    <th class="w-50">Item</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($datos as $k => $row) { ?>
                    <tr data-id="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
                        <td class="collapsing">
                            <?= ($k + 1) ?>
                            <input type="hidden" name="idCotizacionDetalleProveedorDetalle" value="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
                        </td>
                        <td><?= verificarEmpty($row['tipoItem'], 3) ?></td>
                        <td><?= verificarEmpty($row['item'], 3) ?></td>
                        <td>
                            <?= verificarEmpty($row['cantidad'], 2) ?>
                            <input type="hidden" name="cantidad" value="<?=$row['cantidad']?>" id="">
                        </td>
                        <td>
                            <div class="ui right labeled input">
                                <label for="costo" class="ui label">S/. </label>
                                <input type="text" placeholder="costo" name="costo" value="<?= verificarEmpty($row['costo'], 2) ?>" patron="requerido">
                            </div>
                        </td>
                    </tr>
                <? } ?>
            </tbody>
            <tfoot class="full-width">
                <tr>
                    <th></th>
                    <th colspan="4">
                        <div class="ui right floated small primary labeled icon button btnGuardarCotizacion">
                            <i class="save icon"></i> <span class="">Guardar</span>
                        </div>
                        <div class="ui small button btnRefreshCotizaciones">
                            <i class="sync icon"></i>
                            Refresh
                        </div>
                        <div class="ui small red button btnVolverProveedor">
                            <i class="fas fa-solid fa-caret-left icon"></i>
                            <span class="">Volver</span>
                        </div>

                    </th>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
