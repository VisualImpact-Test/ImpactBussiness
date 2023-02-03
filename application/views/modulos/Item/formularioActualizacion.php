<form class="form" role="form" id="formActualizacionItems" method="post">
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
				<input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $informacionItem['item'] ?>">
				<input class="d-none" id="idItem" name="idItem" patron="requerido" value="<?= $informacionItem['idItem'] ?>">
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="nombre" style="border:0px;">Caracteristicas :</label>
				<input class="form-control col-md-7" id="caracteristicas" name="caracteristicas" patron="requerido" value="<?= $informacionItem['caracteristicas'] ?>">
			</div>
			<div class="form-group child-divcenter row w-100 pb-2 divItemLogistica">
				<label class="form-control col-md-5" for="equivalente" style="border:0px;">Equivalente en Logistica :</label>
				<input class="d-none codItemLogistica" id="idItemLogistica" name="idItemLogistica" value="<?= $informacionItem['idItemLogistica'] ?>">
				<div class="input-group col-md-7 px-0">
					<!-- <input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off" style="height: 40.5px;"> -->
					<input class="form-control itemLogistica ui-autocomplete-input" id="equivalente" name="equivalente" value="<?= $informacionItem['equivalenteLogistica'] ?>" placeholder="Buscar" style="height: 40.5px;" autocomplete="off" readonly>
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" onclick="Item.editItemLogisticaValue(this);"><i class="fa fa-edit"></i></button>
					</div>
				</div>
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="tipo" style="border:0px;">Tipo :</label>
				<select class="form-control col-md-7" id="tipo" name="tipo" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemTipo']]); ?>
				</select>
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="marca" style="border:0px;">Marca :</label>
				<select class="form-control col-md-7" id="marca" name="marca" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $marcaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemMarca']]); ?>
				</select>
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="categoria" style="border:0px;">Categoria :</label>
				<select class="form-control col-md-7" id="categoria" name="categoria" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $categoriaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemCategoria']]); ?>
				</select>
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="subcategoria" style="border:0px;">SubCategoria :</label>
				<select class="form-control col-md-7" id="subcategoria" name="subcategoria" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $subcategoriaItem, 'class' => 'text-titlecase', 'selected' => $informacionItem['idItemSubCategoria']]); ?>
				</select>
			</div>
			<div class="control-group child-divcenter row w-100">
				<label class="form-control col-md-5" for="unidadMedida" style="border:0px;">Unidad Medida :</label>
				<select class="form-control col-md-7" id="unidadMedida" name="unidadMedida" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $unidadMedida, 'class' => 'text-titlecase', 'selected' => $informacionItem['idUnidadMedida']]); ?>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 child-divcenter">
			<h2>
				<p class="text-center">Archivos</p>
			</h2>
			<?php if (!empty($imagenItem)) :  ?>
				<div class="text-center">
					<?php foreach ($imagenItem as $key => $value) : ?>
						<figure class="figure">
							<img src="<?= RUTA_WASABI . 'item/' . $value['nombre_archivo'] ?>" class="rounded img-thumbnail" width="100" height="100">
							<figcaption class="figure-caption pt-1">
								<button class="btn btn-outline-danger text-center" type="button" onclick="Item.anularImagen(<?= $value['idItemImagen'] ?>, this)"><i class="fa fa-trash"></i></button>
							</figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="alert alert-info" role="alert">
					<p class="text-center">No se encontro documentos adjuntos.</p>
				</div>
			<?php endif; ?>
		</div>
		<div class="control-group child-divcenter row w-100">
			<div class="col-md-10 child-divcenter pt-4">
				<label class="form-control col-md-5" style="border:0px;">Adicionar Imagenes :</label>
				<div class="form-control custom-file">
					<input type="file" class="custom-file-input files-upload file-upload" lang="es" accept="image/png, image/jpeg" multiple>
					<label class="custom-file-label labelImagen" lang="es">Agregar Imagen</label>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>