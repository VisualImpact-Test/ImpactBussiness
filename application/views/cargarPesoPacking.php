<div>
	<div class="row child-divcenter">
		<img class="child-divcenter" src="assets\images\visualimpact\logo.png" width="350px">
	</div>
	<div class="mb-3 card child-divcenter" style="width:75%">
		<div class="card-head text-center" style="margin-top: 10px;">
			<h2>Cargar Peso</h2>
			<hr>
		</div>

		<div class="card-body">
			<form class="form" role="form" id="formRegistroCostoPacking" method="post" autocomplete="off">
				<div class="row">
					<div class="col-md-8 child-divcenter">
						<fieldset class="scheduler-border">
							<legend class="scheduler-border">Datos Indicados</legend>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-3" style="border:0px;">Cotización :</label>
								<input type="hidden" name="idCotizacion" value="<?= $cotizacion['idCotizacion'] ?>" patron="requerido">
								<input class="form-control col-md-9" value="<?= $cotizacion['nombre'] ?>">
							</div>
							<div class="control-group child-divcenter row" style="width:85%">
								<label class="form-control col-md-3" style="border:0px;">Fecha Emisión :</label>
								<input class="form-control col-md-9" value="<?= $cotizacion['fechaEmision'] ?>">
							</div>
						</fieldset>
					</div>
				</div>
				<?php foreach ($cotizacionDetalle as $kd => $vd) : ?>
					<input type="hidden" name="idCotizacionDetalle" value="<?= $vd['idCotizacionDetalle']; ?>">
					<div class="row">
						<div class="col-md-8 child-divcenter">
							<table class="ui celled table">
								<thead>
									<tr>
										<th>ITEM</th>
										<th>CANTIDAD</th>
										<th>ZONA</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($cotizacionDetalleSub[$vd['idCotizacionDetalle']] as $kds => $vds) : ?>
										<tr>
											<td><?= $vds['nombre']; ?></td>
											<td><?= $vds['cantidad']; ?></td>
											<td><?= $zona[$vds['idZona']]; ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 child-divcenter">
							<fieldset class="scheduler-border" style="padding: 1.4em 1.4em 1.4em 1.4em !important;" id="baseSelect">
								<div class="areaT">
									<div class="mb-2 input-group control-group child-divcenter row" style="width:85%">
										<label class="form-control col-md-3" style="border:0px;">Item :</label>
										<select class="form-control" name="item[<?= $vd['idCotizacionDetalle'] ?>]" patron="requerido">
											<?= htmlSelectOptionArray2(['query' => $itemPacking, 'value' => 'nombre', 'id' => 'idItem', 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
										</select>
										<!-- <input class="form-control col-md-2 costo" name="costo[<?= $vd['idCotizacionDetalle'] ?>]" patron="requerido" value="0" onchange="registroPesos.calcularTotal(this);"> -->
										<div class="input-group-append" id="button-addon4">
											<button class="btn btn-outline-success btnAddCorreo" type="button" onclick="registroPesos.addItem(this)"><i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="mb-2 child-divcenter row" style="width:85%">
										<label class="form-control col-md-3" style="border:0px;">Cantidad :</label>
										<input class="form-control col-md-2" name="cantidad[<?= $vd['idCotizacionDetalle'] ?>]" patron="requerido" value="0">
									</div>
									<div class="mb-2 child-divcenter row" style="width:85%">
										<label class="form-control col-md-3" style="border:0px;">Peso Costo :</label>
										<input class="form-control col-md-2 costo" name="costo[<?= $vd['idCotizacionDetalle'] ?>]" patron="requerido" value="0" onchange="registroPesos.calcularTotal(this);">
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 child-divcenter" style="text-align: left;">
							<fieldset class="scheduler-border" style="padding: 1.4em 1.4em 1.4em 1.4em !important;">
								<div class="mb-2 input-group control-group child-divcenter row" style="width:85%">
									<label class="form-control col-md-3" style="border:0px;">Total :</label>
									<input class="form-control col-md-9" id="valorTotal" name="costoTotal[<?= $vd['idCotizacionDetalle'] ?>]" patron="requerido" value="0">
								</div>
							</fieldset>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="row">
					<div class="col-md-8 child-divcenter" style="text-align: right;">
						<button class="btn btn-outline-primary" id="btnEnviar" style="width: 25%;" value="Enviar">
							<i class="fas fa-paper-plane"></i> Enviar
						</button>
					</div>
				</div>
			</form>
		</div>

	</div>