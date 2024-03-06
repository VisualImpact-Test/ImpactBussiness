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
	<form class="ui form" role="form" id="formActualizarRequerimientoInterno" method="post" autocomplete="off">
		<input type="hidden" name="idRequerimientoInterno" value="<?= !empty($requerimientoInterno['idRequerimientoInterno']) ? $requerimientoInterno['idRequerimientoInterno'] : '' ?>">
		<h4 class="ui dividing header">DATOS DEL REQUERIMIENTO INTERNO</h4>
		<div class="fields disabled disabled-visible">
			<div class="three wide field">
				<div class="ui sub header">Título</div>
				<input class="read-only" id="nombre" name="nombre" patron="requerido" placeholder="Título del Requerimiento" patron="requerido" value="<?= !empty($requerimientoInterno['requerimientoInterno']) ? $requerimientoInterno['requerimientoInterno'] : '' ?>">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Cuenta</div>
				<select class="ui dropdown semantic-dropdown centro-visible parentDependienteSemantic read-only" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="#cuentaCentroCostoForm">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase', 'selected' => '1']); ?>
				</select>
			</div>
			<div class="five wide field">
				<div class="ui sub header">Centro de costo</div>
				<select class="ui dropdown clearable semantic-dropdown centro-ocultado childdependienteSemantic read-only" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selected' => '40']); ?>
				</select>
			</div>
			<div class="three wide field">
				<div class="ui sub header">Aprobación</div>
				<select class="ui search clearable dropdown semantic-dropdown centro-visible" id="aprobacionForm" name="aprobacionForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $usuarioAprobar, 'simple' => true, 'class' => 'text-titlecase', 'selected' => !empty($requerimientoInterno['idUsuarioAprobacion']) ? $requerimientoInterno['idUsuarioAprobacion'] : '']); ?>
				</select>
			</div>
			<div class="two wide field">
				<div class="ui sub header">Tipo Moneda</div>
				<select class="ui dropdown semantic-dropdown" id="tipoMoneda" name="tipoMoneda" patron="requerido" onchange="RequerimientoInterno.SimboloMoneda(this)">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoMoneda, 'class' => 'text-titlecase', 'selected' => !empty($requerimientoInterno['idTipoMoneda']) ? $requerimientoInterno['idTipoMoneda'] : '']); ?>
				</select>
			</div>
		</div>
		<div class="fields disabled disabled-visible">
			<div class="eight wide field">
				<div class="ui sub header">Comentario</div>
				<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($requerimientoInterno['comentario']) ? $requerimientoInterno['comentario'] : '' ?></textarea>
			</div>
		</div>
		<h4 class="ui dividing header">DETALLE DEL REQUERIMIENTO <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
		</h4>
		<div class="default-item">
			<? foreach ($requerimientoTarifario as $key => $row) : ?>
				<div class="ui segment body-item nuevo" data-id="<?= $row['idRequerimientoInternoDetalle'] ?>">
					<input type="hidden" class="idRequerimientoInternoDetalle" name="idRequerimientoInternoDetalle" value="<?= $row['idRequerimientoInternoDetalle'] ?>">
					<div class="ui left floated header">
						<span class="ui medium text"><?= $row['item'] ?></span>
						<br>
					</div>
					<div class="ui clearing divider"></div>
					<div class="ui grid">
						<div class="sixteen wide tablet twelve wide computer column itemDet_1">
							<div class="fields">
								<div class="seven wide field">
									<div class="ui sub header">Item</div>
									<div class="ui-widget">
										<div class="ui left icon input w-100">
											<input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item" value="<?= $row['item'] ?>" readonly>
											<input type='hidden' name='nameItemOriginal' placeholder="Buscar item" value="<?= verificarEmpty($row['item']) ?>">
											<i class="semaforoForm flag link icon"></i>
										</div>
										<input class="codItems" type='hidden' name='idItemForm' value="<?= $row['idItem'] ?>">
										<input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
										<input class="idProveedor" type='hidden' name='idProveedorForm' value="<?= !empty($row['idProveedor']) ? $row['idProveedor'] : ""; ?>">

									</div>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Tipo Item</div>
									<select class="ui dropdown simpleDropdown idTipoItem read-only" id="tipoItemForm" name="tipoItemForm" patron="requerido">
										<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idItemTipo']]); ?>
									</select>
								</div>
								<div class="five wide field">
									<div class="ui sub header">Proveedor
										<div class="ui btn-info-custom text-primary btn-info-proveedor"><i class="info circle icon"></i></div>
									</div>
									<select class="ui dropdown simpleDropdown search clearable read-only" id="proveedorForm" name="proveedorForm" patron="requerido" data-correlativo="1">
										<?= htmlSelectOptionArray2(['query' => $proveedor, 'class' => 'text-titlecase ', 'simple' => true, 'selected' => $row['idProveedor']]); ?>
									</select>
								</div>
							</div>
							<div class="content-lsck-capturas">
								<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="<?= $row['idRequerimientoInternoDetalle'] ?>" accept="<?= ARCHIVOS_PERMITIDOS ?>" multiple="">
								<div class="fields">
									<div class="sixteen wide field">
										<div class="ui small images content-lsck-galeria">
											<?php if (!empty($requerimientoInternoDetalleArchivos[$row['idRequerimientoInternoDetalle']])) : ?>
												<?php foreach ($requerimientoInternoDetalleArchivos[$row['idRequerimientoInternoDetalle']] as $archivo) : ?>
													<?php if ($archivo['idTipoArchivo'] == TIPO_IMAGEN) : ?>
														<div class="ui fluid image content-lsck-capturas" data-id="<?= $archivo['idRequerimientoInternoDetalleArchivo']; ?>">
															<div class="ui dimmer dimmer-file-detalle">
																<div class="content">
																	<p class="ui tiny inverted header"><?= $archivo['nombre_inicial'] ?></p>
																</div>
															</div>
															<a target="_blank" href="<?= RUTA_WASABI . "requerimientoInterno/{$archivo['nombre_archivo']}" ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
															<input type="hidden" name="file-item[<?= $row['idRequerimientoInternoDetalle'] ?>]" value="">
															<input type="hidden" name="file-type[<?= $row['idRequerimientoInternoDetalle'] ?>]" value="image/<?= $archivo['extension'] ?>">
															<input type="hidden" name="file-name[<?= $row['idRequerimientoInternoDetalle'] ?>]" value="<?= $archivo['nombre_inicial'] ?>">
															<img height="100" src="<?= RUTA_WASABI . "requerimientoInterno/{$archivo['nombre_archivo']}" ?>" class="img-lsck-capturas img-responsive img-thumbnail">
														</div>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="sixteen wide tablet four wide computer column">
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Cantidad de Elementos</div>
									<input class="form-control cantidadForm read-only" type="number" value="<?= !empty($row['cantidad']) ? $row['cantidad'] : '' ?>" name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' readonly>
								</div>
							</div>
							<div class="fields">
								<div class="sixteen wide field">
									<div class="ui sub header">Costo Referencial</div>
									<div class="ui right action right labeled input">
										<label for="amount" class="ui label monedaSimbolo">S/</label>
										<input class="costoForm read-only" type="text" name="costoReferencialForm" placeholder="0.00" value="<?= verificarEmpty($row['costoReferencial']); ?>" readonly>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</form>
</div>
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>