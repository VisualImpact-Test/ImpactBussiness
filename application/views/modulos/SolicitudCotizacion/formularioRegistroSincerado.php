<style>
	.detail {
		background: none !important;
	}
</style>
<form class="form" role="form" id="formRegistroSincerado" method="post" autocomplete="off">
	<div class="row" style="margin-top: 15px;">
		<div class="child-divcenter col-md-11">
			<h4 class="ui dividing header">Información de la Cotización</h4>
			<div class="ui form">
				<div class="fields">
					<div class="five wide field">
						<label>Titulo de la Cotización:</label>
						<input type="hidden" value="<?= $cotizacion['idCotizacion']; ?>" name="idCotizacion">
						<h3 class="mt-0"><?= verificarEmpty($cotizacion['nombre']); ?></h3>
					</div>
					<div class="four wide field">
						<label>Cuenta:</label>
						<h3 class="mt-0"><?= verificarEmpty($cotizacion['cuenta']); ?></h3>
					</div>
					<div class="four wide field">
						<label>Centro de Costo:</label>
						<h3 class="mt-0"><?= verificarEmpty($cotizacion['centroCosto']); ?></h3>
					</div>
					<div class="three wide field">
						<label>Fecha Emisión:</label>
						<h3 class="mt-0"><?= date_change_format(verificarEmpty($cotizacion['fechaEmision'])); ?></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top: 15px;">
		<div class="col-md-11 child-divcenter">
			<h4 class="ui dividing header">Detalle de la Cotización</h4>
			<div class="ui form">
				<?php foreach ($cotizacionDetalle as $k => $v) : ?>
					<div class="fields">
						<div class="sixteen wide field">
							<label>Item:</label>
							<h3 class="mt-0"><?= verificarEmpty($v['nombre']); ?></h3>
						</div>
					</div>

					<div id="div-ajax-detalle" class="table-responsive" style="text-align:center;max-height:400px;overflow:auto;">
						<table class="ui celled padded table" id="listaItemsCotizacion">
							<thead class="thead-default ui">
								<tr>
									<th class="text-center">#</th>
									<th>Departamento</th>
									<th>Provincia</th>
									<th>Tipo de Transporte</th>
									<th class="text-center">Costo Cliente</th>
									<th class="text-center">Cantidad Días</th>
									<th class="text-center">Cantidad Moviles</th>
									<th class="text-center">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($cotizacionDetalleSub[$v['idCotizacionDetalle']] as $ks => $vs) : ?>
									<tr>
										<td>
											<?= $ks + 1; ?>
											<input type="hidden" value="<?= $vs['idCotizacionDetalleSub']; ?>" name="cotizacionDetalleSub">
										</td>
										<td><?= $depPro[$vs['cod_departamento']][$vs['cod_provincia']]['departamento']; ?></td>
										<td><?= $depPro[$vs['cod_departamento']][$vs['cod_provincia']]['provincia']; ?></td>
										<td><?= $tipoServicioUbigeo[$vs['idTipoServicioUbigeo']]['nombreAlternativo']; ?></td>
										<td><input class="costoTransporte keyUpChange" name="costo[<?= $vs['idCotizacionDetalleSub']; ?>]" value="<?= $vs['costo']; ?>" onchange="SolicitudCotizacion.calcularSubTotalTransporte(this);"></td>
										<td><input class="diasTransporte keyUpChange" name="dias[<?= $vs['idCotizacionDetalleSub']; ?>]" value="<?= $vs['dias']; ?>" onchange="SolicitudCotizacion.calcularSubTotalTransporte(this);"></td>
										<td><input class="cantidadTransporte keyUpChange" name="cantidad[<?= $vs['idCotizacionDetalleSub']; ?>]" value="<?= $vs['cantidad']; ?>" onchange="SolicitudCotizacion.calcularSubTotalTransporte(this);"></td>
										<td class="subtotalTransporte"><?= floatval($vs['costo']) * floatval($vs['dias']) * floatval($vs['cantidad']); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tfoot class="full-width">
							</tfoot>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</form>