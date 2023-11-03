<div class="card-datatable">
    <table id="tb-cotizacion" class="ui celled table " width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>FECHA EMISION</th>
                <th>NOMBRE</th>
                <th>USUARIO REG</th>
                <th>CUENTA</th>
                <th>CENTRO COSTO</th>
                <th>NRO COTIZACION</th>
                <th>ESTADO DEL PROCESO</th>
                <!-- <th class="td-center">ESTADO</th> -->
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($datos as $key => $row) {
                $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
                $badge = $row['cotizacionEstado'] == 'Enviado' ? 'badge-info' : ($row['cotizacionEstado'] == 'Confirmado' ? 'badge-primary' : ($row['cotizacionEstado'] == 'Finalizado' ? 'badge-success' : 'badge-success'));
                // $badge = $row['cotizacionEstado'] == 'Confirmado' ? 'badge-primary' : 'badge-success';
                // $badge = $row['cotizacionEstado'] == 'Finalizado' ? 'badge-success' : 'badge-success';
                $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            ?>
                <tr data-id="<?= $row['idCotizacion'] ?>" data-idoper="<?= $row['idOper'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <? if ($row['estado'] == 1) { ?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>"><i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i></a>
                            <? if ($row['idCotizacionEstado'] == ESTADO_ENVIADO_COMPRAS) { ?>
                                <a href="../SolicitudCotizacion/viewSolicitudCotizacionInterna/<?= $row['idCotizacion'] ?>" class="btn btn-outline-secondary border-0 btn-dp-<?= $row['idCotizacion']; ?> <?= $row['nuevos'] <= 0 ? "disabled" : "" ?>"><i class="fa fa-lg fa-badge-dollar" title="Cotizar items sin precio"></i></a>
                            <? } ?>
                            <?php if (($row['idCotizacionEstado'] == ESTADO_CONFIRMADO_COMPRAS || $row['idCotizacionEstado'] == ESTADO_ENVIADO_COMPRAS) && intval($row['cantidadTransporte']) > 0) :  ?>
                                <button class=" btn btn-outline-secondary border-0 btnSinceradoTransporte" data-id="<?= $row['idCotizacion'] ?>"><i class="icon truck" title="Cotizar items sin precio"></i></button>
                            <?php endif; ?>
                            <? if ($row['idCotizacionEstado'] == ESTADO_OPER_ENVIADO || $row['idCotizacionEstado'] == ESTADO_OC_GENERADA) { ?>
                                <a href="../SolicitudCotizacion/viewUpdateOper/<?= $row['idOper'] ?>" class="btn btn-outline-secondary border-0 btn-update-oper"><i class="file invoice dollar icon large" title="Procesar OC"></i></a>
                                <!-- <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacionProveedor"><i class="fa fa-lg fa-question" title="Mostrar Detalle Cotizaciones"></i></a> -->
                            <? } ?>

                            <? if ($row['idCotizacionEstado'] == ESTADO_OC_GENERADA || $row['idCotizacionEstado'] == ESTADO_OC_ENVIADA) { ?>
                                <!-- <a href="/ImpactBussiness/formato_orden_compra.pdf" download class="btn btn-outline-secondary border-0"><i class="fa fa-lg fa-file-import" title="Generar PDF"></i></a> -->
                            <? } ?>
                            <? if ($row['idCotizacionEstado'] == ESTADO_OPER_ENVIADO || $row['idCotizacionEstado'] == ESTADO_OPER_GENERADO) { ?>
                                <!-- <a href="/ImpactBussiness/formato_oper.pdf" download class="btn btn-outline-secondary border-0"><i class="fa fa-lg fa-file-import" title="Generar PDF"></i></a> -->
                            <? } ?>

                            <!--Cambio temporal-->

                            <? if ($row['idCotizacionEstado'] == 5 || $row['idCotizacionEstado'] == 6) { ?>
                                <!-- <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-demofechacierre btn-dp-<?= $row['idCotizacion']; ?> <?= $row['nuevos'] <= 0 ? "disabled" : "" ?>"><i class="fa fa-briefcase"></i></a> -->
                            <? } ?>
                            <!---->
                            <? if ($row['idCotizacionEstado'] == 1 || $row['idCotizacionEstado'] == 2 || $row['idCotizacionEstado'] == 3) { ?>
                                <button class=" btn btn-outline-danger border-0 btnAnularCotizacion" data-id="<?= $row['idCotizacion'] ?>"><i class="fas fa-trash" title="Anular Cotizacion"></i></button>

                        <? }
                        } ?>
                    </td>
                    <td class="td-center"><?= verificarEmpty($row['fechaEmision'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cotizacion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['usuario'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuenta'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentaCentroCosto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['codCotizacion'], 3); ?></td>
                    <!-- <td class="td-left"><?= verificarEmpty($row['cotizacionEstado'], 3); ?></td> -->
                    <td class="text-center style-icons">

                        <?php $row['icono'] = str_replace("<a", "<span", $row['icono']); ?>
                        <?php $row['icono'] = str_replace("/a", "/span", $row['icono']); ?>
                        <?php $row['icono'] = str_replace(" tag ", " ", $row['icono']); ?>
                        <? if ($row['estado'] == 0) { ?>
                            <button class="btn btn-link " data-id="<?= $row['idCotizacion'] ?>"><?= $row['icono']; ?></button>
                        <? } else { ?>
                            <?= $row['icono']; ?>
                        <? } ?>
                        <!-- <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idCotizacion']; ?>"><?= $row['cotizacionEstado']; ?></span> -->

                    </td>
                    <!-- <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idCotizacion']; ?>"><?= $mensajeEstado; ?></span>
                    </td> -->
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>