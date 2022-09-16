<form class="form px-5" role="form" id="formRegistroPropuesta" method="post">
  <div id="divBase<?= $id?>">
    <div class="form-row pt-4">
      <div class="col-md-12 mb-3">
        <label>Descripción</label>
        <input class="form-control" name="nombre" patron="requerido">
        <input type="hidden" class="form-control" name="idCotizacionDetalleProveedorDetalle" patron="requerido" value="<?= $id?>">
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-6 mb-3">
        <label>Categoria</label>
        <select class="form-control" name="categoria" patron="requerido">
          <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $categoria,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idItemCategoria'
          ]);
          ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label>Marca</label>
        <select class="form-control" name="marca" patron="requerido">
          <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $marca,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idItemMarca'
          ]);
          ?>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-4 mb-3">
        <label>Cantidad</label>
        <input class="form-control cantidad" name="cantidad" patron="requerido" onkeyup="FormularioProveedores.calcularTotalPropuesta(this);" onchange="FormularioProveedores.calcularTotalPropuesta(this);">
      </div>
      <div class="col-md-4 mb-3">
        <label>Costo</label>
        <input class="form-control costo" name="costo" patron="requerido" onkeyup="FormularioProveedores.calcularTotalPropuesta(this);" onchange="FormularioProveedores.calcularTotalPropuesta(this);">
      </div>
      <div class="col-md-4 mb-3">
        <label>Total</label>
        <input class="form-control total" name="total" patron="requerido" readonly>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label>Motivo</label>
        <select class="form-control" name="motivo" patron="requerido">
          <?=
          htmlSelectOptionArray2([
          'title' => 'Seleccione',
          'query' => $motivo,
          'class' => 'text-titlecase',
          'value' => 'nombre',
          'id' => 'idPropuestaMotivo'
          ]);
          ?>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label>Observacion</label>
        <input class="form-control" name="observacion">
      </div>
    </div>
    <div class="form-row border-bottom">
      <div class="col-md-12 mb-3">
        <label>Adjunto</label>
        <div class="custom-file divUploaded">
          <input type="file" class="custom-file-input files-upload" multiple lang="es">
          <label class="custom-file-label" lang="es">Agregar Archivo(s)</label>
          <div class="content_files">
            <input type="hidden" class="form-control" name="cantidadImagenes" value="0">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="divExtra<?= $id?>"></div>
</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>
