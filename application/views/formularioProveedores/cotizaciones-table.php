<div class="card-datatable">
    <form id="frmCotizacionesProveedor">
        <input type="hidden" name="idProveedor" value="<?=$idProveedor?>">
        <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $idCotizacion ?>">
        <table id="tb-cotizaciones" class="ui compact celled definition table">
            <thead class="full-width">
                <tr>
                    <th>#</th>
                    <th>Opciones</th>
                    <th>Tipo Item</th>
                    <th style="width: 30%;">Item</th>
                    <th>Días de Validez</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th class="center aligned">Total</th>
                    <th class="center aligned">Fecha de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($datos as $k => $row) { $i = 0;?>
                    <tr data-id="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" class="nuevo">
                      <td class="collapsing">
                          <?= ($k + 1) ?>
                          <input type="hidden" name="idCotizacionDetalleProveedorDetalle" value="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
                      </td>
                      <td class="style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="FormularioProveedores.mostrarComentario(<?=($k + 1) ?>);" title="Agregar Comentario"><i class="fa fa-lg fa-comment"></i></a>
                        <input type="hidden" name="comentario" value="<?= $row['comentario'] ?>" id="comentario<?=($k + 1) ?>">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0" title="Agregar Captura" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();"><i class="fa fa-lg fa-camera-retro"></i></a>
                        <div class="content-lsck-capturas">
                          <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" accept="image/*,.pdf" multiple="">
                          <div class="fields ">
                              <div class="sixteen wide field">
                                <div class="ui fluid image content-lsck-galeria">
                                    <? if (!empty($archivos)) { ?>
                                        <? foreach ($archivos as $k => $archivo) { ?>
                                          <?if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']) {
                                            // if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) { ?>
                                                <div class="ui fluid image content-lsck-capturas" style="width: 120px;">
                                                    <div class="ui dimmer dimmer-file-detalle">
                                                        <div class="content">
                                                            <p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
                                                        </div>
                                                    </div>
                                                    <a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
                                                    <a target="_blank" href="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                                    <input type="hidden" name="file-item[0]" value="">
                                                    <input type="hidden" name="file-type[0]" value="image/<?= $archivo['extension'] ?>">
                                                    <input type="hidden" name="file-name[0]" value="<?= $archivo['nombre_inicial'] ?>">
                                                    <img height="100" src="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                                                </div>
                                        <? //}
                                        }
                                        } ?>
                                    <? } ?>
                                </div>
                              </div>
                          </div>
                          <div class="fields ">
                              <div class="sixteen wide field">
                                  <div class="ui small images content-lsck-files">
                                      <? if (!empty($archivos)) { ?>
                                          <? foreach ($archivos as $archivo) {
                                            if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']) {
                                              if ($archivo['idTipoArchivo'] == TIPO_PDF) { ?>
                                                  <div class="ui fluid image content-lsck-capturas">
                                                      <div class="ui dimmer dimmer-file-detalle">
                                                          <div class="content">
                                                              <p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
                                                          </div>
                                                      </div>
                                                      <a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
                                                      <a target="_blank" href="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                                                      <input type="hidden" name="file-item[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="">
                                                      <input type="hidden" name="file-type[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="application/<?= $archivo['extension'] ?>">
                                                      <input type="hidden" name="file-name[<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
                                                      <img height="100" src="<?= RUTA_WIREFRAME . "pdf.png" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                                                  </div>
                                          <? }
                                          }
                                          } ?>
                                      <? } ?>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </td>
                      <td><?= verificarEmpty($row['tipoItem'], 3) ?></td>
                      <td><?= verificarEmpty($row['item'], 3).empty($row['unidadMedida']?'':(' ( '.$row['unidadMedida'].' )')) ?></td>
                      <td>
                        <div class="ui labeled input">
                          <input type="text" placeholder="días" name="diasValidez" patron="requerido" style="width: 20%;"
                            value="<?= empty($row['diasValidez'])?'10': $row['diasValidez']; ?>"
                            onkeyup="FormularioProveedores.calcularFecha(<?=($k + 1) ?>,value);">
                          <label class="ui label" id="lb_fechaValidez<?=($k + 1) ?>">
                            <?= empty($row['diasValidez'])?getFechaActual(10): $row['fechaValidez'] ?>
                          </label>
                          <input type="hidden" name="fechaValidez" value="<?= empty($row['diasValidez'])?getFechaActual(10): $row['fechaValidez']  ?>" id="fechaValidez<?=($k + 1) ?>" readonly>
                        </div>
                      </td>
                      <td class="center aligned">
                          <?= verificarEmpty($row['cantidad'], 2) ?>
                          <input type="hidden" name="cantidad" value="<?=$row['cantidad']?>">
                      </td>
                      <td>
                          <div class="ui labeled input">
                              <label for="costo" class="ui label">S/. </label>
                              <input type="text" placeholder="costo" name="costoUnitario" value="<?= verificarEmpty($row['costoUnitario'], 2) ?>" onkeyup="FormularioProveedores.calcularTotal(<?=($k + 1) ?>,<?=$row['cantidad'] ?>,value);" patron="requerido"  style="width: 30%;">
                          </div>
                      </td>
                      <td style="width: 10%;" class="center aligned">
                        <label id="lb_valorTotal<?=($k + 1) ?>">S/. <?= verificarEmpty($row['cantidad'], 2) * verificarEmpty($row['costoUnitario'], 2) ?></label>
                        <input type="hidden" name="costo" value="<?= verificarEmpty($row['cantidad'], 2) * verificarEmpty($row['costoUnitario'], 2) ?>" id="valorTotal<?=($k + 1) ?>" readonly>
                      </td>
                      <td class="center aligned">
                        <div class="ui input">
                          <input type="date" name="fechaEntrega" value="<?= empty($row['fechaEntrega'])?date_change_format_bd(getFechaActual(5)):$row['fechaEntrega'] ?>">
                        </div>
                      </td>
                    </tr>
                    <? foreach ($subdatos[$row['idCotizacionDetalle']] as $key => $value) {
                      $i++; ?>
                        <tr>
                          <td><?= ($k+1).'.'.$i ?></td>
                          <td colspan="8">
                            <div class="container">
                              <div class="row">
                                <? if($row['tipoItem'] == 'Textiles'){ ?>
                                  <div class="col-sm">
                                    <div class="form-group">
                                      <label>Talla</label>
                                      <input type="text" class="form-control" value="<?= $value['talla'] ?>">
                                    </div>
                                  </div>
                                  <div class="col-sm">
                                    <div class="form-group">
                                      <label>Tela</label>
                                      <input type="text" class="form-control" value="<?= $value['tela'] ?>">
                                    </div>
                                  </div>
                                  <div class="col-sm">
                                    <div class="form-group">
                                      <label>Color</label>
                                      <input type="text" class="form-control" value="<?= $value['color'] ?>">
                                    </div>
                                  </div>
                                <? } ?>
                                <? if($row['tipoItem'] == 'Servicio'){ ?>
                                <div class="col-sm">
                                  <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" value="<?= $value['nombre'] ?>">
                                  </div>
                                </div>
                                <? } ?>
                                <div class="col-sm">
                                  <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="text" class="form-control" value="<?= $value['cantidad'] ?>">
                                  </div>
                                </div>
                                <? if($row['tipoItem'] == 'Textiles'){ ?>
                                <div class="col-sm">
                                  <div class="form-group">
                                    <label>Costo Unit.</label>
                                    <input type="text" class="form-control" value="<?= $value['costo'] ?>">
                                  </div>
                                </div>
                                <? } ?>
                              </div>
                            </div>
                          </td>
                        </tr>
                    <? } ?>
                <? } ?>
            </tbody>
            <tfoot class="full-width">
                <tr>
                    <th></th>
                    <th colspan="8">
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
    <div class="ui bottom attached warning message">
      <i class="icon warning"></i>Los costos indicados NO incluyen el IGV.
    </div>
</div>
