<form class="form px-5" role="form" id="formRegistroPropuesta" method="post" autocomplete="off">
	<div id="divBase<?= $id ?>">
		<?php if (isset($propuestaItem)) :  ?>
			<?php $bucle = $propuestaItem; ?>
		<?php else: ?>
			<?php $bucle = [ 0 => 1]; ?>
		<?php endif; ?>
		
		<?php foreach ($bucle as $key => $value): ?>
			<div class="contenido">
				<?php if (!isset($propuestaItem)) :  ?>
					<button type="button" class="close eliminarDatos" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				<?php endif; ?>
				<div class="form-row pt-4">
					<div class="col-md-12 mb-3">
						<label>Descripción</label>
						<input class="form-control" name="nombre" patron="requerido" value="<?= verificarEmpty($value['nombre']) ?>">
						<input type="hidden" class="form-control" name="idCotizacionDetalleProveedorDetalle" patron="requerido" value="<?= $id ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6 mb-3">
						<label>Categoria</label>
						<select class="form-control" name="categoria">
							<?=
							htmlSelectOptionArray2([
								'title' => 'Seleccione',
								'query' => $categoria,
								'class' => 'text-titlecase',
								'value' => 'nombre',
								'id' => 'idItemCategoria',
								'selected' =>  verificarEmpty($value['idItemCategoria'])
							]);
							?>
						</select>
					</div>
					<div class="col-md-6 mb-3">
						<label>Marca</label>
						<select class="form-control" name="marca">
							<?=
							htmlSelectOptionArray2([
								'title' => 'Seleccione',
								'query' => $marca,
								'class' => 'text-titlecase',
								'value' => 'nombre',
								'id' => 'idItemMarca',
								'selected' =>  verificarEmpty($value['idItemMarca'])
							]);
							?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label>Cantidad</label>
						<input class="form-control cantidad" name="cantidad" patron="requerido" onkeyup="FormularioProveedores.calcularTotalPropuesta(this);" onchange="FormularioProveedores.calcularTotalPropuesta(this);" value="<?= verificarEmpty($value['cantidad']) ?>">
					</div>
					<div class="col-md-4 mb-3">
						<label>Costo</label>
						<input class="form-control costo" name="costo" patron="requerido" onkeyup="FormularioProveedores.calcularTotalPropuesta(this);" onchange="FormularioProveedores.calcularTotalPropuesta(this);" value="<?= verificarEmpty($value['costo']) ?>">
					</div>
					<div class="col-md-4 mb-3">
						<label>Total</label>
						<input class="form-control total" name="total" patron="requerido" readonly value="<?= verificarEmpty(floatval($value['cantidad']) * floatval($value['costo'])) ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-12 mb-3">
						<label>Motivo</label>
						<select class="form-control" name="motivo" patron="requerido">
							<?=
							htmlSelectOptionArray2([
								'title' => 'Seleccione',
								'query' => $motivo,
								'class' => 'text-titlecase',
								'value' => 'nombre',
								'id' => 'idPropuestaMotivo',
								'selected' =>  verificarEmpty($value['idPropuestaMotivo'])
							]);
							?>
						</select>
					</div>
				</div>
				
				<?php if (isset($propuestaItem)) :  ?>
					<?php foreach ($propuestaItemArchivo[$value['idPropuestaItem']] as $k => $v): ?>
						<a target="_blank" href="<?= RUTA_WASABI.'itemPropuesta/'.$v['nombre_archivo'] ?>">
							<img class="" height="200" width="200"
							src="<?=
							$v['idTipoArchivo'] == TIPO_OTROS ?
							// (RUTA_WIREFRAME . "file.png") :  
							(RUTA_WIREFRAME . "pdf.png") :  
							($v['extension'] == 'pdf' ? (RUTA_WIREFRAME . "pdf.png") : (RUTA_WASABI.'itemPropuesta/'.$v['nombre_archivo']))
							?>">
						</a>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="form-row border-bottom">
						<div class="col-md-12 mb-3">
							<label>Adjunto</label>
							<div class="custom-file divUploaded">
								<input type="file" class="custom-file-input files-upload" multiple lang="es" accept=".jpeg,.png,.pdf,.jpg" >
								<label class="custom-file-label" lang="es">Agregar Archivo(s)</label>
								<div class="content_files">
									<input type="hidden" class="form-control" name="cantidadImagenes" value="0">
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>

	</div>
	<div id="divExtra<?= $id ?>"></div>
</form>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>