<div class="row child-divcenter">
    <img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
</div>
<div class="mb-3 card child-divcenter w-100">
    <div class="col-md-12">
        <div>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="mb-3 w-100" id="content-tb-requerimientos-solicitanteInterno" style="width:75%">
                    <div class="card-datatable">
                        <form id="frmRequerimientoInterno">
                            <input type="hidden" name="idProveedor" value="">
                            <table id="tb-requerimientosInternos" class="ui compact celled definition table">
                                <thead class="full-width">
                                    <tr>
                                        <th></th>
                                        <th>OPCIONES</th>
                                        <th>NOMBRE</th>
                                        <th>NRO REQUERIMIENTO</th>
                                        <th>FECHA EMISION</th>
                                        <th>CUENTA</th>
                                        <th>CENTRO COSTO</th>
                                        <th>ESTADO DEL PROCESO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? $ix = 1; ?>
                                    <? foreach ($requerimientoInterno as $k => $row) : ?>
                                        <tr data-id="<?= $row['idRequerimientoInterno'] ?>">
                                            <td class="td-center"><?= $ix; ?></td>
                                            <td>
                                                <? if ($row['reqIntEstado'] == 1) { ?>
                                                    <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleRequerimientoInterno btn-dp-<?= $row['idRequerimientoInterno']; ?>">
                                                        <i class="fa fa-lg fa-bars" title="Ver Detalle del Requerimiento Interno"></i>
                                                    </a>
                                                    <? if ($row['estado'] == 'Generado') { ?>
                                                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btnActualizarRequerimiento btn-dp-<?= $row['idRequerimientoInterno']; ?>">
                                                            <i class="fa fa-lg fa-edit" title="Actualizar Requerimiento Interno"></i>
                                                        </a>
                                                    <? } ?>
                                                    <a href="javascript:;" class="btn btn-outline-danger border-0 btnAnularRequerimientoInterno" data-id="<?= $row['idRequerimientoInterno'] ?>">
                                                        <i class="fas fa-trash" title="Anular Requerimiento Interno"></i>
                                                    </a>
                                                <? } ?>
                                            </td>
                                            <td class="td-left"><?= $row['nombreRequerimiento']; ?></td>
                                            <td class="td-left"><?= $row['codRequerimientoInterno']; ?></td>
                                            <td class="td-left"><?= $row['fechaEmision']; ?></td>
                                            <td class="td-left"><?= $row['cuenta']; ?></td>
                                            <td class="td-left"><?= $row['centroCosto']; ?></td>
                                            <td class="text-center style-icons">
                                                <?php $row['icono'] = str_replace("<a", "<span", $row['icono']); ?>
                                                <?= $row['icono']; ?></td>
                                        </tr>
                                        <? $ix++; ?>
                                    <? endforeach ?>
                                </tbody>
                                <tfoot class="full-width">
                                    <tr>
                                        <th></th>
                                        <th colspan="11">
                                            <div class="ui right floated small button btnRefreshRequerimientoInterno">
                                                <i class="sync icon"></i>
                                                Refresh
                                            </div>
                                            <div class="ui right floated small green button btnAgregarNuevoRequerimientoInterno">
                                                <i class="fas fa-plus"></i>
                                                Nuevo Requerimiento
                                            </div>
                                            <div class="ui right floated small red button btnLogout">
                                                <i class="power off icon"></i>
                                                <span class="">Salir</span>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>