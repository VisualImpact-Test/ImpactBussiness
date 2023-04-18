<div class="fields">
	<div class="five wide field">
		<div class="ui sub header">Documento</div>
		<input type="text" class="ui" name="nroDocumento" placeholder="Descripción documento">
	</div>
	<div class="five wide field">
		<div class="ui sub header">Area</div>
		<select class="ui dropdown parentDependiente" id="areaForm<?= $num ?>" name="area" patron="requerido" data-childDependiente="personaForm<?= $num ?>">
			<?= htmlSelectOptionArray2(["title" => "Seleccione", "id" => "idArea", "value" => "nombre", "query" => $area, "simple" => true, "class" => "text-titlecase"]); ?>
		</select>
	</div>
	<div class="five wide field">
		<div class="ui sub header">Persona</div>
		<select class="ui dropdown clearable semantic-dropdown" id="personaForm<?= $num ?>" name="persona">
			<?= htmlSelectOptionArray2(["title" => "Seleccione", "id" => "idPersonal", "value" => "nombre", "query" => $persona, "class" => "text-titlecase", "idDependiente" => "idArea"]); ?>
		</select>
	</div>
	<div class="one wide field">
		<div class="ui sub header text-white">.</div>
		<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
	</div>
</div>