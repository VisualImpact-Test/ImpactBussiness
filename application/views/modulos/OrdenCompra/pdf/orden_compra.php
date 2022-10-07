<div style="text-align:justify">
  <br><br><br>
    <table border="1" style="width: 100%; float: left;">
        <tr>
            <td class="text-left w-40">RUC: <?= RUC_VISUAL ?> </td>
            <td class="text-left w-20">N° DE RQ: </td>
            <td class="text-left w-20"><?= verificarEmpty($dataOc[0]['requerimiento'], 3) ?></td>
            <td class="text-left w-40">N° DE ORDEN: <span style="margin-left: 50px"> OC<?= generarCorrelativo($dataOc[0]['idOrdenCompra'], 6) ?></span></td>
        </tr>
        <tr>
            <td class="text-left w-20">Unidad de Negocio</td>
            <td class="text-left w-40"><?= verificarEmpty($dataOc[0]['cuenta'], 3) ?></td>
            <td class="text-left w-20">PO Cliente:</td>
            <td class="text-center w-20"><?= verificarEmpty($dataOc[0]['poCliente'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left" colspan="4">Datos del proveedor:</td>
        </tr>
        <tr>
            <td class="text-left w-20">Srs.</td>
            <td class="text-left w-40"><?= verificarEmpty($dataOc[0]['razonSocial'], 3) ?></td>
            <td class="text-left w-20">RUC:</td>
            <td class="text-left w-20"><?= verificarEmpty($dataOc[0]['rucProveedor'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Atención</td>
            <td class="text-left w-40"><?= verificarEmpty($dataOc[0]['nombreContacto'], 3) ?></td>
            <td class="text-left w-20">Telefono fijo</td>
            <td class="text-left w-20"><?= '-' ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Dirección</td>
            <td class="text-left w-40"><?= verificarEmpty($dataOc[0]['direccion'], 3) ?></td>
            <td class="text-left w-20">Celular</td>
            <td class="text-left w-20"><?= verificarEmpty($dataOc[0]['numeroContacto'], 3) ?></td>
        </tr>
        <tr>
            <td class="text-left w-20">Email</td>
            <td class="text-left w-40"><?= verificarEmpty($dataOc[0]['correoContacto'], 3) ?></td>
            <td class="text-left w-20">Fecha entrega</td>
            <td class="text-left w-20"><?= verificarEmpty($dataOc[0]['fechaEntrega'], 3) ?></td>
        </tr>
    </table>
    <table border="1" style="width: 100%;">
        <thead class="full-widtd" style="border:1px solid black;">
            <tr>
                <td class="text-center">Item</td>
                <td class="text-center">Cantidad</td>
                <td class="text-center" colspan="2">Descripción</td>
                <td class="text-center">Precio Unit.</td>
                <td class="text-center">Precio Total</td>
            </tr>
        </thead>
        <tbody style="border:1px solid black;">
          <?php foreach ($dataOc as $k => $row): ?>
            <?php $total = $row['cs_item'];
                  $igv_total = ($row['cs_item'] * (!empty($dataOc[0]['igvPorcentaje']) ? (($dataOc[0]['igvPorcentaje'] + 100) / 100) : IGV));
            ?>
            <tr style="border-bottom: none;">
              <td class="text-center"><?= ($k + 1) ?></td>
              <td class="text-center"><?= verificarEmpty($row['cantidad_item'], 2) ?></td>
              <td class="text-left" colspan="2"><?= verificarEmpty($row['item'], 3) ?></td>
              <td class="text-right">
                <?= !empty($row['costo']) ? monedaNew(['valor'=>$row['costo'],'simbolo'=>$dataOc['simboloMoneda']]) : 0 ?>
              </td>
              <td class="text-right">
                <?= !empty($row['subtotal']) ? monedaNew(['valor'=>$row['subtotal'],'simbolo' => $dataOc['simboloMoneda']]) : 0 ?>
              </td>
            </tr>

          <?php endforeach; ?>
          <tr>
              <td><?= generar_espacios(1, 1) ?></td>
              <td></td>
              <td colspan="2"></td>
              <td></td>
              <td></td>
          </tr>
          <tr>
              <td><?= generar_espacios(1, 1) ?></td>
              <td></td>
              <td colspan="2"></td>
              <td></td>
              <td></td>
          </tr>
          <? if (!empty($dataOc[0]['entrega'])) { ?>
              <tr>
                  <td><?= generar_espacios(1, 1) ?></td>
                  <td></td>
                  <td colspan="2" style="font-weight: bold;">
                      <?= !empty($dataOc[0]['entrega']) ? "Entrega: {$dataOc[0]['entrega']}" : '' ?>
                  </td>
                  <td></td>
                  <td></td>
              </tr>
          <? } ?>
          <? if (!empty($dataOc[0]['observacion'])) { ?>
              <tr>
                  <td><?= generar_espacios(1, 1) ?></td>
                  <td></td>
                  <td colspan="2" style="font-weight: bold;">
                      <?= !empty($dataOc[0]['observacion']) ? "Observación: {$dataOc[0]['observacion']}" : '' ?>
                  </td>
                  <td></td>
                  <td></td>
              </tr>
          <? } ?>

        </tbody>
        <tfoot class="full-widtd">
            <tr class="height:100px">
                <td colspan="4" class="text-right">
                    <p>Sub Total</p>
                    <p>IGV</p>
                    <p>TOTAL</p>
                </td>
                <td class="text-center">
                    <p><?= !empty($dataOc[0]['IGVPorcentaje']) ? $dataOc[0]['IGVPorcentaje'] : (IGV * 100) ?>%</p>
                </td>
                <td class="text-right">
                    <p><?= monedaNew(['valor'=>$total,'simbolo' => $dataOc[0]['simboloMoneda']]) ?></p>
                    <p><?= monedaNew(['valor'=> $igv_total,'simbolo' => $dataOc[0]['simboloMoneda']]) ?></p>
                    <p><?= monedaNew(['valor'=> $igv_total + $total,'simbolo' => $dataOc[0]['simboloMoneda']])  ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="text-left">
                    Son: <?= moneyToText(['numero' => ($igv_total + $total), 'moneda' => $dataOc[0]['monedaPlural']]) ?>
                </td>
            </tr>
            <?if(!empty($dataOc[0]['comentario'])):?>
            <tr>
                <td colspan="6" class="text-left">
                   <?= !empty($dataOc[0]['comentario']) ? $dataOc[0]['comentario'] : ''?>
                </td>
            </tr>
            <?endif;?>
            <tr style="border-bottom: none;">
                <td colspan="2">
                    <strong>Forma de Pago</strong>
                </td>
                <td>
                    <strong>
                        <?= !empty($dataOc[0]['metodoPago']) ? $dataOc[0]['metodoPago'] : '' ?>
                    </strong>
                </td>
                <td>
                    <strong>
                        Observaciones
                    </strong>
                </td>
                <td colspan="2">
                    <strong>
                        <?= !empty($dataOc[0]['poCliente']) ? $dataOc[0]['poCliente'] : '' ?>
                    </strong>
                </td>
            </tr>
            <tr style="border-top: none;">
                <td colspan="2" style="height: 50px;"></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<div style="border: 2px solid black; text-align:justify;height:100px">
    <table style="border:none !important;width: 100%; margin-top:30px">
        <tr>
            <td class="w-10">
            </td>
            <td class="w-30 text-center" style="padding-top:120px;">
                <div style="text-align:center;">
                    <hr style="height: 3px; color:black">
                    Área de logística
                </div>
            </td>
            <td class="w-20 text-center" style="padding-top:120px;">
            </td>
            <td class="w-30 text-center" style="padding-top:120px">
                <?php if (!empty($dataOc[0]['dirFirma'])) : ?>
                    <img id="imagenFirma" src="<?= empty($dataOc[0]['dirFirma']) ? '' : (RUTA_WASABI . 'usuarioFirma/' . $dataOc[0]['dirFirma']) ?>" style="padding-top: -120px; width: 200px; height: 120px;">
                <?php endif; ?>
                <div style="text-align:center">
                    <hr style="height: 3px; color:black; ">
                    Coordinador de compras
                </div>
            </td>
            <td class="w-10">
            </td>
        </tr>
    </table>
</div>
