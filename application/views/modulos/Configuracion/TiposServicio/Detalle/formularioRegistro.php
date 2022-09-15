<form class="form px-5" role="form" id="formRegistroTiposServicio" method="post">
  <div class="form-row">
    <div class="col-md-12 mb-3">
      <label>Ubigeo</label>
      <select class="form-control" name="tipoServicioUbigeo" patron="requerido"
              onchange="$('#extra').val( (this.value==1 ? 'Urbano' : 'No Urbano'));">
        <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $tipoServicioUbigeo,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idTipoServicioUbigeo'
          ]);
        ?>
      </select>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label>Tipo Servicio</label>
      <input class="form-control" name="nombre" patron="requerido">
    </div>
    <div class="col-md-6 mb-3">
      <label style="color: white"> Extra</label>
      <input id="extra" class="form-control" name="nombreExtra" patron="requerido" readonly>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label>Costo</label>
      <input class="form-control" name="costo" patron="requerido">
    </div>
    <div class="col-md-6 mb-3">
      <label>Unidad de Medida</label>
      <select class="form-control" name="unidadMedida" patron="requerido">
        <?=
          htmlSelectOptionArray2([
            'title' => 'Seleccione',
            'query' => $unidadMedida,
            'class' => 'text-titlecase',
            'value' => 'nombre',
            'id' => 'idUnidadMedida'
          ]);
        ?>
      </select>
    </div>
  </div>

</form>
<!--
<form class="form" role="form" id="formRegistroTipos" method="post">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido">
            </div>
        </div>
    </div>
</form>
-->
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>
