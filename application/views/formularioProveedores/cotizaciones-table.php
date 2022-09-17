<div class="card-datatable">
  <!-- ///////////////////////////////////// -->
  <form id="frmCotizacionesProveedor">
    <input type="hidden" name="idProveedor" value="<?=$idProveedor?>">
    <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $idCotizacion ?>">
    <p class="font-weight-bold">Datos de la Cotizacion</p>
    <?php if (!empty($datos[0]['fechaValidez']) ): ?>
      <?php  $fechaRegistro = getFechaDias($datos[0]['fechaValidez'], (-1*intval($datos[0]['diasValidez']))); ?>
      <small>Fecha de Registro: <?= $fechaRegistro ?></small>
    <?php endif; ?>
    <hr class="featurette-divider">
    <div class="container mx-0 col-12">
      <?php $validator = $datos[0]['fechaValidez']; ?>
      <?php foreach ($datos as $k => $row): ?>
        <?php  $i = 0; ?>
        <input type="hidden" name="idCotizacionDetalleProveedorDetalle" value="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>">
        <div class="row">
          <div class="col-md-10 row justify-content-start">
            <div class="col-md-10">
              <div class="form-group">
                <h4 class="mb-1">ITEM</h4>
                <input class="form-control" readonly value="<?= verificarEmpty($row['item'], 3).empty($row['unidadMedida']?'':(' ( '.$row['unidadMedida'].' )')) ?>">
              </div>
            </div>
            <div class="col-md-2">
              <h4 class="mb-1" style="color:white;">IMG</h4>
              <div class="btn-group" role="group" aria-label="Basic example">
                <button class="form-control imgShow btnContraoferta" type="button" name="button" data-id="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>"><i class="handshake outline icon"></i></button>
                <button class="form-control imgShow" type="button" name="button" onclick="$('.imgCotizacion').removeClass('d-none');  $('.imgShow').addClass('d-none')"><i class="folder open outline icon"></i></button>
                <button class="form-control imgCotizacion d-none" type="button" name="button" onclick="$('.imgCotizacion').addClass('d-none'); $('.imgShow').removeClass('d-none');"><i class="folder closed outline icon"></i></button>
                <!-- <button type="button" class="btn btn-secondary">Left</button>
                <button type="button" class="btn btn-secondary">Middle</button>
                <button type="button" class="btn btn-secondary">Right</button> -->
              </div>
            </div>
            <div class="col-md-12 imgCotizacion d-none">
                <?php if (empty($cotizacionIMG[$row['idCotizacionDetalle']])): ?>
                  <div class="alert alert-info" role="alert">
                    <b>No se encontro documentos adjuntos.</b>
                  </div>
                <?php else: ?>
                  <div class="ui small images">
                  <?php foreach ($cotizacionIMG[$row['idCotizacionDetalle']] as $key => $img): ?>
                    <div class="ui fluid image dimmable" data-id="<?= $key?>">
                      <div class="ui dimmer dimmer-file-detalle">
                        <div class="content">
                          <p class="ui tiny inverted header">322.png</p>
                        </div>
                      </div>
                      <a target="_blank" href="<?= RUTA_WASABI.'cotizacion/'.$img['nombre_archivo']?>" class="ui blue left corner label"><i class="eye icon"></i></a>
                      <img height="100" src="<?= $img['idTipoArchivo'] == TIPO_OTROS ? (RUTA_WIREFRAME . "file.png") :  ($img['extension'] == 'pdf' ? (RUTA_WIREFRAME . "pdf.png") : (RUTA_WASABI.'cotizacion/'.$img['nombre_archivo'])) ?>" class="img-responsive img-thumbnail">
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <h4 class="mb-1">TIPO ITEM</h4>
                <input type="hidden" class="form-control" name="idItem" readonly value="<?= verificarEmpty($row['idItem'], 3) ?>">
                <input class="form-control" readonly value="<?= verificarEmpty($row['tipoItem'], 3) ?>">
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <div class="form-group">
                  <h4 class="mb-1">DIAS DE VALIDEZ</h4>
                  <input  class="form-control" placeholder="días" name="diasValidez" patron="requerido,numerico"
                          id="dv_input<?=($k + 1) ?>"
                          onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                          value="<?= !isset($row['diasValidez'])?'10': $row['diasValidez']; ?>"
                          onkeyup="FormularioProveedores.calcularFecha(<?=($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');"
                          onchange="FormularioProveedores.calcularFecha(<?=($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');"
                  >
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <h4 class="mb-1">FECHA VALIDEZ</h4>
                <input  type="date" class="form-control" name="fechaValidez"
                        value="<?= date_change_format_bd(!isset($row['diasValidez'])?getFechaActual(10): $row['fechaValidez'])  ?>" id="fechaValidez<?=($k + 1) ?>"
                        onkeyup="FormularioProveedores.calcularDiasValidez(<?=($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')"
                        onchange="FormularioProveedores.calcularDiasValidez(<?=($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')"
                >
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <div class="form-group">
                  <h4 class="mb-1">DIAS DE ENTREGA</h4>
                  <input  class="form-control" placeholder="días" name="diasEntrega" patron="requerido,numerico"
                          id="de_input<?=($k + 1) ?>"
                          onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                          value="<?= !isset($row['diasEntrega'])?'10': $row['diasEntrega']; ?>"
                          onkeyup="FormularioProveedores.calcularFechaEntrega(<?=($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');"
                          onchange="FormularioProveedores.calcularFechaEntrega(<?=($k + 1) ?>,this.value, '<?= date_change_format_bd(getFechaActual(0)); ?>');"
                  >
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <h4 class="mb-1">FECHA ENTREGA</h4>
                <input  type="date" class="form-control" name="fechaEntrega"
                        value="<?= empty($row['fechaEntrega'])?date_change_format_bd(getFechaActual(10)):$row['fechaEntrega'] ?>" id="fechaEntrega<?=($k + 1) ?>"
                        onkeyup="FormularioProveedores.calcularDiasEntrega(<?=($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')"
                        onchange="FormularioProveedores.calcularDiasEntrega(<?=($k + 1) ?>, this, '<?= date_change_format_bd(getFechaActual(0)); ?>')"
                >
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <h4 class="mb-1">COMENTARIO</h4>
                <input class="form-control" name="comentario" value="<?= $row['comentario'] ?>" id="comentario<?=($k + 1) ?>">
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <h4 class="mb-1">Cantidad</h4>
              <input class="form-control" name="cantidad" value="<?=$row['cantidad']?>" readonly>
            </div>
            <div class="form-group">
              <h4 class="mb-1">Costo Unitario (S/)</h4>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">S/ </span>
                </div>
                <input class="form-control onlyNumbers d-none" placeholder="costo" id="costo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" name="costoUnitario" value="<?= verificarEmpty($row['costoUnitario'], 2) ?>" onkeyup="FormularioProveedores.calcularTotal(<?=($k + 1) ?>,<?=$row['cantidad'] ?>,value);" patron="requerido">
                <input class="form-control onlyNumbers" placeholder="costo" id="costoredondo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" value="<?= number_format(verificarEmpty($row['costoUnitario'], 2),2,'.','') ?>" onkeyup="FormularioProveedores.calcularTotal(<?=($k + 1) ?>,<?=$row['cantidad'] ?>,value); $('#costo_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>').val(value);">
              </div>
              <small id="msgCosto_<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" class="form-text text-muted d-none">
                Costo promedio calculado del detalle
                Valor redondeado
              </small>

            </div>
            <div class="form-group">
              <h4 class="mb-1">Total</h4>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">S/ </span>
                </div>
                <input class="form-control" name="costo" value="<?= number_format(verificarEmpty($row['cantidad'], 2) * verificarEmpty($row['costoUnitario'], 2),2,'.','') ?>" id="valorTotal<?=($k + 1) ?>" readonly>
              </div>
            </div>
          </div>
        </div>
        <?php foreach ($subdatos[$row['idCotizacionDetalleProveedorDetalle']] as $key => $value): ?>
          <div class="container">
            <div class="row justify-content-start">
              <? if($row['tipoItem'] == 'Textiles'){ ?>
                <div class="col-sm-auto">
                  <div class="form-group">
                    <label>Talla</label>
                    <input type="hidden" class="form-control" name="idCDPDS" value="<?= $value['idCotizacionDetalleProveedorDetalleSub'] ?>" readonly>
                    <input type="text" class="form-control" value="<?= $value['talla'] ?>" readonly>
                  </div>
                </div>
                <div class="col-sm-auto">
                  <div class="form-group">
                    <label>Tela</label>
                    <input type="text" class="form-control" value="<?= $value['tela'] ?>" readonly>
                  </div>
                </div>
                <div class="col-sm-auto">
                  <div class="form-group">
                    <label>Color</label>
                    <input type="text" class="form-control" value="<?= $value['color'] ?>" readonly>
                  </div>
                </div>
              <? } ?>
              <? if($row['tipoItem'] == 'Servicio'){ ?>
              <div class="col-sm-auto">
                <div class="form-group">
                  <label>Nombre</label>
                  <input type="text" class="form-control" value="<?= $value['nombre'] ?>" readonly>
                </div>
              </div>
              <? } ?>
              <div class="col-sm-auto">
                <div class="form-group">
                  <label>Cantidad</label>
                  <input type="text" class="form-control" name="cantidadSubItem" value="<?= $value['cantidad'] ?>" findCantidad="<?= $value['idCotizacionDetalleProveedorDetalle']?>" readonly>
                </div>
              </div>
              <? if($row['tipoItem'] == 'Textiles'){ ?>
              <div class="col-sm-auto">
                <div class="form-group">
                  <label>Costo Unit.</label>
                  <input type="text" class="form-control onlyNumbers" name="costoSubItem" value="<?= $value['costo'] ?>" findCosto="<?= $value['idCotizacionDetalleProveedorDetalle']?>" patron="requerido" onkeyup="FormularioProveedores.calcularTotalSub(<?=$value['idCotizacionDetalleProveedorDetalle'] ?>);">
                </div>
              </div>
              <? } ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="col-md-12">
          <div class="form-group nuevo">
            <a href="javascript:;" class="btn btn-lg btn-outline-secondary col-md-2" title="Agregar Captura" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();">
              Agregar Imágen <i class="fa fa-lg fa-camera-retro"></i>
            </a>
            <div class="content-lsck-capturas pt-2">
              <input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen"
                      data-row="<?= $row['idCotizacionDetalleProveedorDetalle'] ?>" accept="image/*,.pdf" multiple="">
              <div class="fields ">
                <div class="container sixteen wide field">
                  <div class="row content-lsck-galeria content-lsck-capturas">
                    <?php if (!empty($archivos)): ?>
                      <?php foreach ($archivos as $k => $archivo): ?>
                        <?php if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']): ?>
                            <div class="col-md-2 text-center">
                              <div class="ui dimmer dimmer-file-detalle">
                                <div class="content">
                                  <p class="ui tiny inverted header"> <?= $archivo['nombre_inicial'] ?> </p>
                                </div>
                              </div>
                              <a class="ui red right corner label img-lsck-capturas-delete">
                                <i class="trash icon"></i>
                              </a>
                              <a target="_blank"
                                  href="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>"
                                  class="ui blue left corner label">
                                <i class="eye icon"></i>
                              </a>
                              <input type="hidden" name="file-item[0]" value="">
                              <input type="hidden" name="file-type[0]" value="image/<?= $archivo['extension'] ?>">
                              <input type="hidden" name="file-name[0]" value="<?= $archivo['nombre_inicial'] ?>">
                              <img src="<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="rounded img-lsck-capturas img-responsive img-thumbnail">
                            </div>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="fields ">
                <div class="sixteen wide field">
                  <div class="ui images content-lsck-files"> <? if (!empty($archivos)) { ?> <? foreach ($archivos as $archivo) {
                        if ($archivo['idCotizacionDetalleProveedorDetalle'] == $row['idCotizacionDetalleProveedorDetalle']) {
                        if ($archivo['idTipoArchivo'] == TIPO_PDF) { ?> <div class="content-lsck-capturas">
                      <div class="ui dimmer dimmer-file-detalle">
                        <div class="content">
                          <p class="ui tiny inverted header"> <?= $archivo['nombre_inicial'] ?> </p>
                        </div>
                      </div>
                      <a class="ui red right corner label img-lsck-capturas-delete">
                        <i class="trash icon"></i>
                      </a>
                      <a target="_blank" href="
        														<?= RUTA_WASABI . "cotizacionProveedor/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label">
                        <i class="eye icon"></i>
                      </a>
                      <input type="hidden" name="file-item[
        														<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="">
                      <input type="hidden" name="file-type[
        															<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="application/
        															<?= $archivo['extension'] ?>">
                      <input type="hidden" name="file-name[
        																<?= $row['idCotizacionDetalleProveedorDetalle'] ?>]" value="
        																<?= $archivo['nombre_inicial'] ?>">
                      <img height="100" src="
        																	<?= RUTA_WIREFRAME . "pdf.png" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
                    </div> <? }
                        }
                        } ?> <? } ?> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="featurette-divider">
      <?php endforeach; ?>
    </div>
    <div class="container">
      <?php if (empty($datos[0]['fechaValidez']) ): ?>
        <div class="ui right floated small primary labeled icon button btnGuardarCotizacion">
          <i class="save icon"></i> <span class="">Guardar</span>
        </div>
      <?php endif; ?>
      <div class="ui small button btnRefreshCotizaciones">
        <i class="sync icon"></i>
        Refresh
      </div>
      <div class="ui small red button btnVolverProveedor">
        <i class="fas fa-solid fa-caret-left icon"></i>
        <span class="">Volver</span>
      </div>
    </div>
    <hr class="featurette-divider">
    <div class="ui bottom attached warning message">
      <i class="icon warning"></i>Los costos indicados NO incluyen el IGV.
    </div>

  </form>
</div>
