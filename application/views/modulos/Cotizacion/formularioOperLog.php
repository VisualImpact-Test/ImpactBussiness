<form class="form" role="form" id="formOperLog" method="post" autocomplete="off">
	<input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $cabOperLog[0]['idCotizacion']?>">
	<div class="ui form">

		<div class="fields">
			<div class="five wide field">
				<label>Cuenta:</label>
				<input type="text" name="Cuenta" placeholder="Código Orden de compra" value="<?= $cabOperLog[0]['cuenta']?>">
			</div>
            <div class="five wide field">
				<label>Centro Costo:</label>
				<input type="text" name="centroCosto" placeholder="Monto de compra" value="<?= $cabOperLog[0]['cuentaCentroCosto']?>">
			</div>
            <div class="five wide field">
				<label>Usuario:</label>
				<select class="ui dropdown simpleDropdown search" id="CuentaUsuario" name="CuentaUsuario" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $CuentaUsuario, 'class' => 'text-titlecase']); ?>
                </select>			
			</div>
		</div>
		
		<div class="fields">
			<div class="five wide field">
				<label>Origen:</label>
				<select class="ui dropdown simpleDropdown search" id="AlmacenOrigen" name="AlmacenOrigen" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $Almacen, 'class' => 'text-titlecase', 'selected' => 1]); ?>
                </select>			
			</div>
			<div class="five wide field">
				<label>Cotizacion:</label>  
				<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'cotizacion', 'visible' => false , 'tipo' => 2]) ?>
			</div>
			<div class="five wide field">
				<label>Orden de compra:</label>  
				<?= htmlSemanticCargaDeArchivos(['classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*,.pdf', 'name' => 'ordenCompra', 'visible' => false , 'tipo' => 2]) ?>
			</div>
		</div>
	
	</div>
</form>