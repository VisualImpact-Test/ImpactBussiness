<div class="card-datatable">
    <table id="tb-oper" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>OPCIONES</th>
                <th>CONCEPTO</th>
                <th>REQUERIMIENTO</th>
                <th>FECHAREQUERIMIENTO</th>
                <th>FECHAENTREGA</th>
                <th>TOTAL</th>
                <th>FEEPORCENTAJE</th>
                <th>TOTALFEE</th>
                <th>IGVPORCENTAJE</th>
                <!-- <th>TOTALFEEIGV</th> -->
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?php foreach ($datos as $key => $row): ?>
              <?php
                $mensajeEstado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';
                $badge = $row['estado'] == 1 ? 'badge-success' : 'badge-danger';
                $toggle = $row['estado'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
              ?>
              <tr data-id="<?= $key ?>">
                <td class="td-center"><?= $ix; ?></td>
                <td class="td-center style-icons">
                  <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Editar OPER"><i class="fa fa-lg fa-edit"></i></a>
                  <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-descargarOper" title="Imprimir OPER"><i class="fa fa-lg fa-file-pdf"></i></a>
                  <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-duplicar" title="Duplicar OPER"><i class="fa fa-lg fa-copy"></i></a>
                </td>
                <td class="td-center"><?= verificarEmpty($row['concepto'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['requerimiento'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaRequerimiento'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['fechaEntrega'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['total'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['feePorcentaje'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['totalFee'], 3); ?></td>
                <td class="td-center"><?= verificarEmpty($row['IGVPorcentaje'], 3); ?></td>
                <td class="text-center style-icons">
                  <span class="badge <?= $badge ?>" id="spanEstado-<?= $row['estado']; ?>"><?= $mensajeEstado; ?></span>
                </td>
              </tr>
              <? $ix++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
