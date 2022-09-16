<form class="form" role="form" id="formvisualizacionCotizacion" method="post">
    <div class="row">
        <div class="col-md-12 ">
            <div id="accordion">
                <div class="">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button type="button" class="btn " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <p class="font-weight-bolder"><i class="fas fa-solid fa-caret-right"></i> Datos de Cotización</p>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="row pt-3">
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="nombre" style="border:0px;">Nombre :</label>
                                    <label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['cotizacion'], 3) ?></label>

                                </div>
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="cuentaForm" style="border:0px;">Cuenta :</label>
                                    <label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
                                </div>
                                <div class="control-group child-divcenter row w-100">
                                </div>
                            </div>
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="tipo" style="border:0px;">Cod. Cotizacion :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['codCotizacion'], 3) ?></label>
                                </div>
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                                    <label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
                                </div>
                            </div>
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="tipo" style="border:0px;">Progreso de la Cotizacion :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['cotizacionEstado'], 3) ?></label>
                                </div>
                            </div>
                            <div class="col-md-5 child-divcenter">
                                <div class="control-group child-divcenter row w-100">
                                    <label class="form-control form-control-sm col-md-5 font-weight-bolder" for="tipo" style="border:0px;">Fecha de Emision :</label>
                                    <label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['fechaEmision'], 3) ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11 ">
            <div id="accordionDos">
                <div class="">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button type="button" class="btn " data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                <p class="font-weight-bolder"><i class="fas fa-solid fa-caret-right"></i> Datos de Items</p>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionDos">
                      <? foreach ($item as $keyItem => $dato) { ?>
                        <div class="row pt-3">
                          <div class="col-md-11 ">
                            <div id="accordion<?=   $dato['idItem']; ?>">
                              <div class="">
                                <div class="card-header" id="heading<?=   $dato['idItem']; ?>">
                                  <h5 class="mb-0">
                                    <button type="button" class="btn " data-toggle="collapse" data-target="#collapse<?=   $dato['idItem']; ?>" aria-expanded="true" aria-controls="collapse<?=   $dato['idItem']; ?>">
                                      <p class="font-weight-bolder"><i class="fas fa-solid fa-caret-right"></i> <?=   $dato['item']; ?></p>
                                    </button>
                                  </h5>
                                </div>
                                <div id="collapse<?=   $dato['idItem']; ?>" class="collapse show" aria-labelledby="heading<?=   $dato['idItem']; ?>" data-parent="#accordion<?=   $dato['idItem']; ?>">
                                  <div class="table-responsive pl-5" style="text-align:center">
                                      <table class="mb-0 table table-bordered text-nowrap">
                                          <thead class="thead-default">
                                              <tr>
                                                  <th style="width: 5%;" class="text-center">#</th>
                                                  <th style="width: 15%;">Proveedor</th>
                                                  <th style="width: 50%;">TipoItem</th>
                                                  <th style="width: 15%;" class="text-center">Cantidad</th>
                                                  <th style="width: 7%;">Costo Unitario</th>
                                                  <th style="width: 7%;">Unidad Medida</th>
                                                  <th style="width: 7%;">Fecha de Validez</th>
                                                  <th style="width: 8%;">Fecha de Entrega</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?
                                              $i = 0;
                                              foreach ($proveedor as $keyProveedor => $datoProveedor) {
                                                $i++;
                                              ?>
                                                  <tr class="default">
                                                      <td><?= $i ?></td>
                                                      <td><?= verificarEmpty($datoProveedor['proveedor'], 3) ?></td>
                                                      <td><?= verificarEmpty($dato['tipoItem'], 3) ?></td>
                                                      <td><?= verificarEmpty($dato['cantidad'], 3) ?></td>
                                                      <td><?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['costoUnitario'], 3) ?></td>
                                                      <td><?= verificarEmpty($dato['unidadMedida'], 3) ?></td>
                                                      <td><?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['fechaValidez'], 3) ?></td>
                                                      <td><?= verificarEmpty(date_change_format($itemProveedor[$keyItem][$keyProveedor]['fechaEntrega']),3) ?></td>
                                                  </tr>

                                                  <?php if (!empty($images[$keyItem][$keyProveedor])): ?>
                                                  <tr>
                                                    <td colspan="8">
                                                      <div class="col-md-12 imgCotizacion">
                                                        <div class="ui small images">
                                                          <?php foreach ($images[$keyItem][$keyProveedor] as $key => $img): ?>
                                                            <div class="ui fluid image dimmable" data-id="<?= $key?>">
                                                              <div class="ui dimmer dimmer-file-detalle">
                                                                <div class="content">
                                                                  <p class="ui tiny inverted header">322.png</p>
                                                                </div>
                                                              </div>
                                                              <a target="_blank" href="<?= RUTA_WASABI.'cotizacionProveedor/'.$img['nombre_archivo']?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                                              <img height="100" src="<?= $img['extension'] == 'pdf' ? (RUTA_WIREFRAME . "pdf.png") : (RUTA_WASABI.'cotizacionProveedor/'.$img['nombre_archivo']) ?>" class="img-responsive img-thumbnail">
                                                            </div>
                                                          <?php endforeach; ?>
                                                        </div>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                <?php endif; ?>
                                              <?
                                              }
                                              ?>
                                          </tbody>
                                      </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <? }?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</form>
