<style>
	.btn-info-custom {
		cursor: pointer;
		display: inline-block;
		line-height: 1;
	}

	input[type="color"] {
		padding: initial !important;
	}

	.floating-container {
		height: 275px !important;
	}

	.plomo {
		color: #d2d2d2 !important;
	}
</style>
<div class="ui form attached fluid segment p-4">
	<form class="ui form" role="form" id="formSeleccionProveedor" method="post" autocomplete="off">
		<div class="fields">
			<div class="eleven wide field">
				<select class="ui clearable dropdown semantic-dropdown" name="seleccion" patron="requerido">
					<option value="">Seleccione</option>	
					<option value="0">Listas Cotizaciones</option>
					<option value="1">Asignación de Precios</option>
				</select>
			</div>
		</div>
	</form>
</div>