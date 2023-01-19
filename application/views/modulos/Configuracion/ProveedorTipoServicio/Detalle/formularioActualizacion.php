<form class="form px-5" role="form" id="formActualizacionProveedorTipoServicio" method="post">
	<div class="form-row">
		<div class="col-md-12 mb-3">
			<label>Tipo Servicio</label>
			<input type="hidden" class="form-control" name="id" patron="requerido" value="<?= $data['idProveedorTipoServicio'] ?>">
			<input class="form-control" name="nombre" patron="requerido" value="<?= $data['nombre'] ?>">
		</div>
	</div>

</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>