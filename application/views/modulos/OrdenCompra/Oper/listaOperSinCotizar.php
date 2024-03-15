<div class="card-datatable">
    <table id="tb-oper" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th style="text-align: center;">CONCEPTO</th>
                <th>REQUERIMIENTO</th>
                <th>PROVEEDOR</th>
                <th>FECHAREQUERIMIENTO</th>
                <th>FECHAENTREGA</th>
                <th>TOTAL</th>
                <th>FEEPORCENTAJE</th>
                <th>TOTALFEE</th>
                <th>IGVPORCENTAJE</th>
                <th>SELECCIONAR</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?php foreach ($datos as $key => $row): ?>
              
              <tr data-id="<?= $key ?>">
                <td class="td-center"><?= $ix; ?></td>
                <td class="td-left"><?= verificarEmpty($row['concepto'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['requerimiento'], 3); ?></td>
                <td class="td-left"><?= verificarEmpty($row['razonSocial'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaRequerimiento'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaEntrega'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['total'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['feePorcentaje'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['totalFee'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['IGVPorcentaje'], 3); ?></td>
                <td class="text-center style-icons">
                    <button type="button" onclick="Oc.agregarOpersinCotizar(<?= $row['idOper']; ?>, <?= $row['idProveedor']; ?>)" class="btn btn-outline-trade-visual border-0" id="btn-Agregar-Oper" title="Nuevo">
                        <i class="fas fa-plus"></i> <span class="txt_filtro"> Agregar</span>
                    </button>
                    <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-descargarOper" title="Imprimir OPER">
                        <i class="fa fa-lg fa-file-pdf"></i>
                    </a>
                </td>
              </tr>
              <? $ix++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    	$(document).ready(function() {
			$('#tb-oper').DataTable( );
		} );
</script>
