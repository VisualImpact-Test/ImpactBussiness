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
	<form class="ui form" role="form" id="formRegistroUsuarioAprobar" method="post" autocomplete="off">
		<h4 class="ui dividing header">DATOS DEL USUARIO A APROBAR</h4>
		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Usuario</div>
				<select class="ui search clearable dropdown semantic-dropdown" id="usuario" name="usuario" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $usuario, 'simple' => true, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="eight wide field">
				<div class="ui sub header">Tipo Usuario</div>
				<input readonly id="nombre" name="nombre" placeholder="Tipo de usuario">
			</div>
		</div>
	</form>
</div>