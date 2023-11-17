<form class="form" role="form" id="formRegistroGR" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" style="border:0px;">Número de GR :</label>
				<input class="form-control col-md-7" id="nombre" name="numero_gr" patron="requerido" value="">
				<input type="hidden" name="idCotizacion" value="<?= $idCotizacion ?>" patron="requerido">
			</div>
		</div>
	</div>
</form>