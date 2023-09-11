<form class="form" role="form" id="formvisualizacionCotizacion" method="post">
	<?php foreach ($item as $keyItem => $dato) : ?>
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
			<h3><?= $dato['item']; ?></h3>
			<p class="lead"><?= verificarEmpty($dato['tipoItem'], 3) ?></p>
		</div>
		<div class="container">
			<?php $cp = (count($proveedor) > 4) ? 4 : count($proveedor); ?>
			<div class="row row-cols-1 row-cols-md-<?= $cp; ?> card-deck mb-3 text-center">
				<?php foreach ($proveedor as $keyProveedor => $datoProveedor) : ?>
					<div class="col">
						<div class="card mb-4 <?= $itemProveedor[$keyItem][$keyProveedor]['fechaValidez'] == '' ? 'border-danger' : ''; ?>" style="border-width: 2px;">
							<div class="card-header" style="display: unset; background-color: rgba(0,0,0,.03);">
								<div>
									<h4><?= verificarEmpty($datoProveedor['proveedor'], 3) ?></h4>
								</div>
								<?php if (!empty($itemProveedor[$keyItem][$keyProveedor]['fechaValidez'])) :  ?>
									<div>
										<a class="btn btn-success downloadFormatProv" data-proveedor="<?= $keyProveedor ?>" data-id="<?= $idCotizacionDetalle ?>">Descargar <i class="fa fa-file-excel"></i></a>
									</div>
								<?php endif; ?>
							</div>
							<div class="row card-body">
								<div class="col-md-12">
									<h1 class="card-title pricing-card-title">S/ <?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['costo'], 3) ?> <small class="text-muted">/ <?= verificarEmpty($dato['cantidad'], 3) ?> (<?= verificarEmpty($dato['unidadMedida'], 3) ?>)</small></h1>
									<ul class="list-group list-group-flush mt-3">
										<li class="list-group-item"><b>Costo Unitario</b>&nbsp&nbsp&nbsp <?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['costoUnitario'], 3) ?></li>
										<li class="list-group-item"><b>Fecha Validez</b>&nbsp&nbsp&nbsp <?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['fechaValidez'], 3) ?></li>
										<li class="list-group-item"><b>Días Entrega</b>&nbsp&nbsp&nbsp <?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['diasEntrega'], 3) ?></li>
										<li class="list-group-item"><b>Respuesta Proveedor</b>&nbsp&nbsp&nbsp <?= verificarEmpty($itemProveedor[$keyItem][$keyProveedor]['comentario'], 3) ?></li>
										<?php if (!empty($itemProveedor[$keyItem][$keyProveedor]['sucursal'])) :  ?>
											<li class="list-group-item"><b>Sucursal</b>&nbsp&nbsp&nbsp <?= $itemProveedor[$keyItem][$keyProveedor]['sucursal'] ?></li>
										<?php endif; ?>
										<?php if (!empty($itemProveedor[$keyItem][$keyProveedor]['razonSocial'])) :  ?>
											<li class="list-group-item"><b>Razón Social</b>&nbsp&nbsp&nbsp <?= $itemProveedor[$keyItem][$keyProveedor]['razonSocial'] ?></li>
										<?php endif; ?>
										<?php if (!empty($itemProveedor[$keyItem][$keyProveedor]['tipoElemento'])) :  ?>
											<li class="list-group-item"><b>Tipo Elemento</b>&nbsp&nbsp&nbsp <?= $itemProveedor[$keyItem][$keyProveedor]['tipoElemento'] ?></li>
										<?php endif; ?>
										<?php if (!empty($itemProveedor[$keyItem][$keyProveedor]['marca'])) :  ?>
											<li class="list-group-item"><b>Marca</b>&nbsp&nbsp&nbsp <?= $itemProveedor[$keyItem][$keyProveedor]['marca'] ?></li>
										<?php endif; ?>
										<?php if ($dato['idItemTipo'] != COD_SERVICIO['id']) :  ?>
											<?php foreach ($subItems[$keyItem][$keyProveedor] as $keySI => $valueSI) : ?>
												<li class="list-group-item">
													<?php if ($dato['tipoItem'] == 'Textiles') : ?>
														<b>Talla</b>&nbsp&nbsp&nbsp <?= verificarEmpty($valueSI['talla'], 3) ?>&nbsp&nbsp<br>
														<b>Tela</b>&nbsp&nbsp&nbsp <?= verificarEmpty($valueSI['tela'], 3) ?>&nbsp&nbsp<br>
														<b>Color</b>&nbsp&nbsp&nbsp <?= verificarEmpty($valueSI['color'], 3) ?>&nbsp&nbsp<br>
													<?php endif; ?>
													<b>Cantidad</b>&nbsp&nbsp&nbsp <?= verificarEmpty($valueSI['cantidad'], 3) ?><br>
													<b>Cost. Unit</b>&nbsp&nbsp&nbsp <?= verificarEmpty($valueSI['costo'], 3) ?><br>
												</li>
											<?php endforeach; ?>
											
										<?php endif; ?>

									</ul>
								</div>
							</div>
							<div class="card-footer">
								<?php if (!empty($images[$keyItem][$keyProveedor])) : ?>
									<div class="col-md-12 imgCotizacion">
										<div class="ui small images">
											<?php foreach ($images[$keyItem][$keyProveedor] as $key => $img) : ?>
												<div class="ui fluid image dimmable" data-id="<?= $key ?>">
													<div class="ui dimmer dimmer-file-detalle">
														<div class="content">
															<p class="ui tiny inverted header">322.png</p>
														</div>
													</div>
													<a target="_blank" href="<?= RUTA_WASABI . 'cotizacionProveedor/' . $img['nombre_archivo'] ?>" class="ui blue left corner label"><i class="eye icon"></i></a>
													<?php $src = RUTA_WIREFRAME . "file.png" ?>
													<?php $src = $img['idTipoArchivo'] == TIPO_PDF ? (RUTA_WIREFRAME . "pdf.png") : $src; ?>
													<?php $src = $img['idTipoArchivo'] == TIPO_EXCEL ? (RUTA_WIREFRAME . "xlsx.png") : $src; ?>
													<?php $src = $img['idTipoArchivo'] == TIPO_IMAGEN ? (RUTA_WASABI . 'cotizacionProveedor/' . $img['nombre_archivo']) : $src; ?>
													<img height="100" src="<?= $src ?>" class="img-responsive img-thumbnail">
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>


</form>