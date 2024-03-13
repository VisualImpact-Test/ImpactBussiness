<form class="form" role="form" id="formvisualizacionCotizacion" method="post">
	<div class="row">
		<div class="col-md-12 ">
			<div id="accordion">
				<div class="">
					<div class="card-header" id="headingOne">
						<h5 class="mb-0">
							<button type="button" class="btn " data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
								<i class="fas fa-solid fa-caret-right"></i> <?= verificarEmpty($cabecera['codRequerimientoInterno'], 3) ?>
							</button>
						</h5>
					</div>
					<div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
						<div class="row">
							<div class="col-md-5 child-divcenter">
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="nombre" style="border:0px;">Nombre :</label>
									<label class="form-control form-control-sm col-md-7" for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['requerimientoInterno'], 3) ?></label>

								</div>
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="cuentaForm" style="border:0px;">Cuenta :</label>
									<label class="form-control form-control-sm col-md-7" for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
								</div>
								<div class="control-group child-divcenter row w-100">
								</div>
							</div>
							<div class="col-md-5 child-divcenter">
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Cod. Requerimiento :</label>
									<label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['codRequerimientoInterno'], 3) ?></label>
								</div>
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
									<label class="form-control form-control-sm col-md-7" for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5 child-divcenter">
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Progreso del Requerimiento :</label>
									<label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['requerimientoIEstado'], 3) ?></label>
								</div>
							</div>
							<div class="col-md-5 child-divcenter">
								<div class="control-group child-divcenter row w-100">
									<label class="form-control form-control-sm col-md-5" for="tipo" style="border:0px;">Fecha de Emisión :</label>
									<label class="form-control form-control-sm col-md-7" for="tipo" style="border:0px;"><?= verificarEmpty($cabecera['fechaEmision'], 3) ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top: 15px;">
		<div class="col-md-11 child-divcenter">
			<div class="justify-content-center py-2 ">
				<div class="ui ordered steps w-100">
					<?
					$class = 'completed';
					$btnCot = false;
					$btnOper = false;
					$btnOC = false;
					foreach ($estados as $value) {

						if ($class == 'active') $class = "disabled";
						if ($value['nombre'] == $cabecera['requerimientoIEstado']) $class = "active";
						if ($cabecera['requerimientoIEstado'] == "Finalizado") $class = 'completed';

						// Visibilidad de los botones
						if ($value['nombre'] == 'OC Generada' && $class == 'completed') {
							$btnOC = true;
						}

					?>
						<div class="<?= $class ?> step">
							<div class="content">
								<div class="title"><?= !empty($value['nombre']) ? $value['nombre'] : ' - ' ?></div>
								<div class="description"><?= ($class != "completed") ? $value['descripcionPendiente'] : $value['descripcion']  ?></div>
							</div>
						</div>
					<? }
					?>

				</div>
			</div>
			<!-- <button type="button" class="btn btn-outline-secondary btn-generarCotizacion" style="margin-bottom: 15px;">Generar Cotizacion</button> -->
			<div class="pt-2">
				<?php if ($btnOC) :  ?>
					<button type="button" class="btn btn-trade-visual btn-descargarOrdenCompra" data-id="< ?= verificarEmpty($cabecera['idOC'], 3) ?>" style="margin-bottom: 15px;">Reporte Orden Compra
					</button>
				<?php endif; ?>
			</div>
			<div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
				<table class="mb-0 table table-bordered text-nowrap" id="listaItemsCotizacion">
					<thead class="thead-default">
						<tr>
							<th style="width: 5%;" class="text-center">#</th>
							<th style="width: 15%;">Tipo de Item</th>
							<th style="width: 50%;">Item</th>
							<th style="width: 15%;" class="text-center">Cantidad</th>
							<th style="width: 7%;">Costo Referencial</th>
							<th style="width: 7%;">Proveedor</th>
							<th style="width: 7%;">Fecha de Proceso</th>
							<th style="width: 8%;">Estado</th>
							<!-- <th style="width: 8%;">Opciones</th> -->
						</tr>
					</thead>
					<tbody>
						<?
						foreach ($detalle as $key => $row) {
						?>
							<tr class="default">
								<td><?= $key + 1 ?></td>
								<td><?= verificarEmpty($row['itemTipo'], 3) ?></td>
								<td><?= verificarEmpty($row['item'], 3) ?></td>
								<td><?= verificarEmpty($row['cantidad'], 3) ?></td>
								<td><?= empty($row['costoReferencial']) ? "-" : moneda($row['costoReferencial']); ?></td>
								<td><?= verificarEmpty($row['proveedor'], 3) ?></td>
								<td><?= verificarEmpty($row['fecha'], 3) ?></td>
								<td><?= verificarEmpty($row['requerimientoInternoDetalleEstado'], 3) ?></td>
							</tr>
						<?
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


</form>