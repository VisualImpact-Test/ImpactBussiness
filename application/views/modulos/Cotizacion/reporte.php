<div class="card-datatable">
    <table id="tb-cotizacion" class="ui celled table" width="100%">
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
                <tr data-id="<?= $row['idCotizacion'] ?>"  data-idoper="<?= $row['idOper'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <? if ($row['estado'] == 1) {?>
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>"><i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i></a>
                        <!--
                        <a href="../Cotizacion/viewFormularioDuplicar/<?= $row['idCotizacion'] ?>" target="_blank" class="btn btn-outline-secondary border-0">
                            <i class="fa fa-lg  fa-copy"></i> <span class="txt_filtro"></span>
                        </a>
                        -->
                        <div class="<?= (!$row['cotizacionValidaCliente']) ? 'disabled' : '' ?>">

                            <a href="../Cotizacion/viewFormularioActualizar/<?= $row['idCotizacion'] ?>" target="_blank" class="btn btn-outline-secondary border-0">
                                <i class="fa fa-lg fa-edit"></i> <span class="txt_filtro"></span>
                            </a>
                            <? if ($row['idCotizacionEstado'] >= ESTADO_ENVIADO_CLIENTE) { ?>
                                <a href="javascript:;" download class="btn btn-outline-secondary border-0 btn-descargarCotizacion"><i class="fa fa-lg fa-file-import" title="Generar PDF cotizacion"></i></a>
                            <? } ?>

                            <? if ($row['idCotizacionEstado'] == ESTADO_ENVIADO_CLIENTE) { ?>
                                <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-aprobar-cotizacion"><i class="fa fa-lg fa-check" title="Procesar"></i></a>
                            <? } ?>

                            <? if ($row['idCotizacionEstado'] == ESTADO_CONFIRMADO_COMPRAS) { ?>

                                <a href="../Cotizacion/viewSolicitudCotizacionInterna/<?= $row['idCotizacion'] ?>" class="btn btn-outline-secondary border-0 "><i class="send icon" title="Enviar Cotizacion"></i></a>
                            <? } ?>
                            <? if ($row['idCotizacionEstado'] == ESTADO_OC_CONFIRMADA) { ?>
                                <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-finalizarCotizacion btn-dp-26"><i class="check icon" title="Finalizar Cotizacion"></i></a>
                            <? } ?>
                        </div>
                        <? if ($row['idCotizacionEstado'] == 1|| $row['idCotizacionEstado'] == 2 ||$row['idCotizacionEstado'] == 3) {?>
                        <button class=" btn btn-outline-danger border-0 btnAnularCotizacion"  data-id="<?= $row['idCotizacion'] ?>"><i class="fas fa-trash" title="Anular Cotizacion"></i></button>

                        <?}}?>


                    </td>
                    <td class="td-center"><?= verificarEmpty($row['fechaEmision'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cotizacion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuenta'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentaCentroCosto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['codCotizacion'], 3); ?></td>
                    <td class="text-center style-icons">


                        <?php $row['icono'] = str_replace("<a", "<span", $row['icono']); ?>
                        <?php $row['icono'] = str_replace("/a", "/span", $row['icono']); ?>
                        <?php $row['icono'] = str_replace(" tag ", " ", $row['icono']); ?>
                        <? if ($row['estado'] == 0) {?>
                            <button class="btn btn-link " data-id="<?= $row['idCotizacion'] ?>" ><?= $row['icono']; ?></button>
                        <?}else{?>
                            <?= $row['icono']; ?>
                        <?}?>

                        <!-- <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idCotizacion']; ?>">
                            <?= $row['cotizacionEstado']; ?>
                        </span> -->
                        <? if (!$row['cotizacionValidaCliente']) { ?>
                            <br>
                            <div class="ui pointing red basic label">
                                Cotizacion no válida
                            </div>
                        <? } ?>

                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>