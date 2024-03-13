<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

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
		<h4 class="ui dividing header">DATOS DE PROVEEDORES</h4>
		<div class="fields">
			<div class="eleven wide field">
				<div class="ui sub header">Proveedor</div>
				<input type="hidden" name="idRequerimientoInterno" value="<?= $requerimientoInterno['idRequerimientoInterno'] ?>">
				<select class="ui search clearable dropdown semantic-dropdown" id="proveedor" name="proveedor" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'simple' => true, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
		</div>
	</form>
</div>