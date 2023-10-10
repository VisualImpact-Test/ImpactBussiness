<div class="fields">
	<?php if ($documentosCargados) : ?>
		<div class="five wide field divDocumento">
			<div class="ui sub header">Documento</div>
			<select class="ui dropdown cboDocumento" name="idDocumento" patron="requerido">
				<?= htmlSelectOptionArray2(["title" => "Seleccione", "id" => "idDocumento", "data-option" => ['idArea', 'idPersonal', 'nombre_archivo'], "value" => "nombre", "query" => $documento, "simple" => true, "class" => "text-titlecase"]); ?>
			</select>
			<input type="hidden" name="nroDocumento">
		</div>
	<?php else : ?>
		<div class="five wide field">
			<div class="ui sub header">Documento</div>
			<input type="hidden" name="idDocumento" value="0">
			<input type="text" class="ui" name="nroDocumento" placeholder="Descripción documento">
		</div>
	<?php endif; ?>
	<div class="four wide field divArea">
		<div class="ui sub header">Area</div>
		<select class="ui dropdown parentDependiente" id="areaForm<?= $num ?>" name="area" patron="requerido" data-childDependiente="personaForm<?= $num ?>">
			<?= htmlSelectOptionArray2(["title" => "Seleccione", "id" => "idArea", "value" => "nombre", "query" => $area, "simple" => true, "class" => "text-titlecase"]); ?>
		</select>
	</div>
	<div class="five wide field divPersonal">
		<div class="ui sub header">Persona</div>
		<select class="ui dropdown clearable semantic-dropdown" id="personaForm<?= $num ?>" name="persona">
			<?= htmlSelectOptionArray2(["title" => "Seleccione", "id" => "idPersonal", "value" => "nombre", "query" => $persona, "class" => "text-titlecase", "idDependiente" => "idArea"]); ?>
		</select>
	</div>
	<?php if ($documentosCargados) : ?>
		<div class="one wide field">
			<div class="ui sub header text-white">.</div>
			<a class="ui button botonDescarga" target="_blank"><i class="download icon"></i></a>
		</div>
	<?php endif; ?>
	<div class="<?= $documentosCargados ? 'one' : 'two' ?> wide field">
		<div class="ui sub header text-white">.</div>
		<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
	</div>
</div>