<form class="form" role="form" id="formRegistroGR" method="post" autocomplete="off">
	<input type="hidden" name="idCotizacion" id="" value="<?= $datosCot[0]['idCotizacion'] ?>">
	<div class="ui form">
		<div class="fields">
			<div class="sixteen wide field">
				<table class="table table-bordered table-sm" id="tbnumeroGR">
					<thead>
						<tr>
							<th>Número de GR:</th>
							<th>Fecha GR: </th>
							<th>Agregar </th>
						</tr>
					</thead>
					<tbody>
					
					<?php foreach ($datosCotGR as $key => $value) { ?>
						<tr>
							<td class="text-center">

							<?= $value['numeroGR']; ?>
							

							</td>
							<td class="text-center" width="30%">
							<?= $value['fechaGR']; ?>
							</td>
							<td width="10%">
							</td>
						</tr>
					<?php } ?>
						<tr>
							<td class="text-center">
								<input type="text" class="form-control form-control-sm" name="numeroGR" value="">
							</td>
							<td class="text-center" width="30%">
								<div class="ui calendar date-semantic">
									<div class="ui input left icon">
										<i class="calendar icon"></i>
										<input type="text" placeholder="Fecha GR" value="">
									</div>
								</div>
								<input type="hidden" class="date-semantic-value" name="fechaGR" placeholder="Fecha GR" value="">
							</td>
							<td width="10%">
								<button id="btn-agregar-new-gr" class="btn btn-sm btn-success" title="GUARDAR"><i class="fas fa-plus"></i></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<label>Código OC:</label>
				<input type="text" name="codigo_oc" placeholder="Código Orden de compra" value="<?= $datosCot[0]['codOrdenCompra'] ?>">
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<label>Monto de OC:</label>
				<input type="text" name="monto_oc" placeholder="Monto de compra" value="<?= $datosCot[0]['montoOrdenCompra'] ?>">
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field" style="display: flex;flex-direction: row;align-items: center;">
				<div class="ui labeled button" tabindex="0">
					<div class="ui blue button" onclick="$('.file-lsck-capturas').click();">
						<i class="paperclip icon"></i> Adjuntar
					</div>
					<a class="ui basic blue left pointing label">
						Orden Compra
					</a>
				</div>
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<div class="ui sub header">FECHA OC</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="FECHA OC" value="<?= $datosCot[0]['fechaClienteOC'] ?>">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="fechaClienteOC" placeholder="FECHA OC" value="<?= $datosCot[0]['fechaClienteOC'] ?>">
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<div class="content-lsck-capturas">
					<input data-file-max="1" data-show-name="true" type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept=".pdf" multiple="">
					<div class="fields ">
						<div class="sixteen wide field">
							<div class="ui small images content-lsck-galeria">
							</div>
						</div>
					</div>
					<div class="fields ">
						<div class="sixteen wide field">
							<div class="ui small images content-lsck-files">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fields">
			<div class="sixteen wide field">
				<label>Descripción de OC:</label>
				<input id="motivo" name="motivo" patron="" placeholder="Descripción de Orden de Compra" value="<?= !empty($datosCot[0]['motivoAprobacion']) ? $datosCot[0]['motivoAprobacion'] : '' ?>">
			</div>
		</div>
	</div>
</form>