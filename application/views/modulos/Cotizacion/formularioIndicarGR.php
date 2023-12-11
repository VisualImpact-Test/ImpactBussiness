<form class="form" role="form" id="formRegistroGR" method="post" autocomplete="off">
	<input type="hidden" name="idCotizacion" id="" value="<?= $datosCot[0]['idCotizacion'] ?>">
	<div class="ui form">
		<?php if (
			$datosCot[0]['numeroGR'] == NULL || $datosCot[0]['numeroGR'] == ''
			|| $datosCot[0]['numeroGR'] == 'NULL'
		) { ?>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Número de GR:</label>
					<input id="nombre" name="numero_gr" value="">
				</div>
			</div>
		<?php } ?>


		<div class="fields">
			<div class="sixteen wide field">
				<?php if (
					$datosCot[0]['fechaGR'] == NULL || $datosCot[0]['fechaGR'] == ''
					|| $datosCot[0]['fechaGR'] == '1900-01-01' || $datosCot[0]['fechaGR'] == 'NULL') { ?>
					<label>Fecha de GR:</label>
					<div class="ui calendar date-semantic">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input type="text" placeholder="Fecha GR" value="">
						</div>
					</div>
				<?php } ?>
				<input type="hidden" class="date-semantic-value" name="fechaGR" placeholder="Fecha GR" value="">
			</div>
		</div>



		<?php if (
			$datosCot[0]['codOrdenCompra'] == NULL || $datosCot[0]['codOrdenCompra'] == ''
			|| $datosCot[0]['codOrdenCompra'] == 'NULL') { ?>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Código OC:</label>
					
			<input type="text" name="codigo_oc" placeholder="Código Orden de compra">
			
				</div>
			</div>
		<?php } ?>




		<?php if (
			$datosCot[0]['montoOrdenCompra'] == NULL || $datosCot[0]['montoOrdenCompra'] == ''
			|| $datosCot[0]['montoOrdenCompra'] == 'NULL') { ?>
			<div class="fields">
				<div class="sixteen wide field">
					<label>Monto de OC:</label>
					
				<input type="text" name="monto_oc" placeholder="Monto de compra">
		
				</div>
			</div>
		<? } ?>

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
					<?php if (
							$datosCot[0]['fechaClienteOC'] == NULL || $datosCot[0]['fechaClienteOC'] == ''
								|| $datosCot[0]['fechaClienteOC'] == '1900-01-01'
								|| $datosCot[0]['fechaClienteOC'] == 'NULL') { ?>
						<div class="ui sub header">FECHA OC</div>
						<div class="ui calendar date-semantic">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input type="text" placeholder="FECHA OC" value="">
							</div>
						</div>
			<? }?>		
			<input type="hidden" class="date-semantic-value" name="fechaClienteOC" placeholder="FECHA OC">
		
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