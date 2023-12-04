<div class="card-datatable">
    <table id="tb-oper" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th style="text-align: center;">CONCEPTO</th>
                <th>REQUERIMIENTO</th>
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
                <td class="td-center"><?= verificarEmpty($row['concepto'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['requerimiento'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaRequerimiento'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaEntrega'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['total'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['feePorcentaje'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['totalFee'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['IGVPorcentaje'], 3); ?></td>
                <td class="text-center style-icons">
                    <button type="button" onclick="Oc.agregarOpersinCotizar(<?php echo $row['idOper']; ?>)" class="btn btn-outline-trade-visual border-0" id="btn-Agregar-Oper" title="Nuevo">
                        <i class="fas fa-plus"></i> <span class="txt_filtro"> Agregar</span>
                    </button>
                </td>
              </tr>
              <? $ix++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
