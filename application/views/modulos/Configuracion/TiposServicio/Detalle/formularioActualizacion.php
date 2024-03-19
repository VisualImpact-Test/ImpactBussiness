<form class="form px-5" role="form" id="formActualizacionTiposServicio" method="post">
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label>Ubigeo</label>
      <select class="form-control" name="tipoServicioUbigeo" patron="requerido">
        <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $tipoServicioUbigeo,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idTipoServicioUbigeo',
            'selected' => $informacion['idTipoServicioUbigeo']
          ]);
        ?>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label>Tipo Transporte</label>
      <select class="form-control" name="tipoTransporte" patron="requerido">
        <?=
        htmlSelectOptionArray2([
          'title' => 'Seleccione',
          'query' => $tipoTransporte,
          'class' => 'text-titlecase',
          'value' => 'nombre',
          'id' => 'idTipoTransporte',
          'selected' => $informacion['idTipoTransporte']
        ]);
        ?>
      </select>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-12 mb-3">
      <label>Tipo Servicio</label>
      <input type="hidden" class="form-control" name="id" patron="requerido" value="<?= $informacion['idTipoServicio'] ?>">
      <input class="form-control" name="nombre" patron="requerido" value="<?= $informacion['nombre'] ?>">
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label>Costo Cuenta</label>
      <input class="form-control moneda" id="costo" name="costo" patron="requerido" value="<?= $informacion['costo']; ?>">
    </div>
    <div class="col-md-6 mb-3">
      <label>Costo</label>
      <input class="form-control moneda" id="costoVisual" name="costoVisual" patron="requerido" value="<?= $informacion['costoVisual']; ?>">
    </div>
  </div>
  <div class="form-row">
  <div class="col-md-12 mb-3">
      <label>Unidad de Medida</label>
      <select class="form-control" name="unidadMedida" patron="requerido">
        <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $unidadMedida,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idUnidadMedida',
            'selected' => $informacion['idUnidadMedida']
          ]);
        ?>
      </select>
    </div>
  </div>

</form>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>
