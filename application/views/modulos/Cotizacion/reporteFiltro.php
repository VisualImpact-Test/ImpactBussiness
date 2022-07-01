<div class="card-datatable">
    <table id="filtroCotizacion" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th ></th>
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

            
                <tr data-id="<?= $row['idCotizacion'] ?>">
                    <td></td>
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-detalleCotizacion btn-dp-<?= $row['idCotizacion']; ?>"><i class="fa fa-lg fa-bars" title="Ver Detalle de Cotizacion"></i></a>
                        <!-- <a id="hrefEstado-<?= $row['idCotizacion']; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-estadoCotizacion" data-id="<?= $row['idCotizacion']; ?>" data-estado="<?= $row['estado']; ?>">
                            <i class="fal fa-lg <?= $toggle ?>"></i>
                        </a> -->
                        <? if ($row['idCotizacionEstado'] == 4) { ?>
                            <label for="upload_orden_compra[<?= $row['idCotizacion'] ?>]" class="btn btn-outline-secondary border-0 mt-1 btn-subir-oc" data-toggle="tooltip" data-placement="top" title="Adjuntar OC "><i class="fa fa-lg fa-paperclip"></i></label>
                            <input class="form-control upload_orden_compra d-none" type="file" id="upload_orden_compra[<?= $row['idCotizacion'] ?>]" name="upload_orden_compra" accept=".pdf">
                            <!-- <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-subir-oc"><i class="fa fa-lg fa-paperclip" title="Adjuntar OC"></i></a> -->
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-generar-cotizacionEfectivaSinOc"><i class="fa fa-lg fa-paste" title="Procesar Sin Orden de Compra"></i></a>
                           
                        <? } ?>
                        
                        <? if ($row['idCotizacionEstado'] == 3) { ?>
                            <? if ($ix == 1) {?>
                             <a href="/ImpactBussiness/formato_cotización.pdf" download class="btn btn-outline-secondary border-0"><i class="fa fa-lg fa-file-import" title="Generar PDF"></i></a> 
                            <? } else {?>  
                             <a href="/ImpactBussiness/Cotización.pdf" download class="btn btn-outline-secondary border-0"><i class="fa fa-lg fa-file-import" title="Generar PDF"></i></a> 
                            <? } ?>
                            
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-frmCotizacionConfirmada btn-dp-<?= $row['idCotizacion']; ?> "><i class="send icon" title="Enviar Cotizacion"></i></a>
                        <? } ?>
                    </td>
                    <td class="td-center"><?= verificarEmpty($row['fechaEmision'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cotizacion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuenta'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentaCentroCosto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['codCotizacion'], 3); ?></td>
                    <!-- <td class="td-left"><?= verificarEmpty($row['cotizacionEstado'], 3); ?></td> -->
                    <td class="text-center style-icons">
                        <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['idCotizacion']; ?>"><?= $row['cotizacionEstado']; ?></span>
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

<script>
    	$(document).ready(function() {
			$('#filtroCotizacion').DataTable( {
				columnDefs: [ {
					orderable: false,
					className: 'select-checkbox',
					targets:   0
				} ],
				select: {
					style:    'os',
					selector: 'td:first-child'
				},
				order: [[ 1, 'asc' ]]
			} );
		} );
</script>