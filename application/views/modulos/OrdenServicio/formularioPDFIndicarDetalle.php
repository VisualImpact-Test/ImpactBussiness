<style>
	.container {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.form-container {
		width: 400px;
		/* Ajusta el ancho según sea necesario */
	}

	.center-align {
		text-align: center;
	}

	.fields {
		text-align: center;
		/* Centra los elementos hijos */
	}

	.btn-info-custom {
		cursor: pointer;
		display: inline-block;
		line-height: 1;
	}

	input[type="color"] {
		padding: initial !important;
	}

	.plomo {
		color: #d2d2d2 !important;
	}
</style>

<div class="container">
	<div class="form-container">
		<div class="ui form attached fluid segment p-4">
			<form class="ui form" role="form" id="formPDFIndicarDetalle" method="post" autocomplete="off">
				<div class="center-align">
					<h4 class="ui dividing header">REQUIERE DETALLE?</h4>
					<input readonly id="id" name="id" value="<?= $id ?>" hidden>
					<select class="ui dropdown semantic-dropdown" id="detalle" name="detalle" patron="requerido">
						<option value="1">SI</option>
						<option value="2">NO</option>
					</select>
				</div>
			</form>
		</div>
	</div>
</div>