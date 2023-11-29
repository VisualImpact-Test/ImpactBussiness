<form class="ui form" role="form" id="formCompletarDatos" method="post" autoComplete="off">
	<div id="accordion">
		<div class="ui form attached fluid segment p-4">
			<button type="button" class="btn px-0 py-2" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				<h4 class="ui dividing header text-uppercase">Datos Generales</h4>
			</button>
			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
				
				<div class="fields">
					<div class="ten wide field">
						<div class="ui sub header">Nombre :</div>
						<input type="text" class="ui" name="cotizacion" placeholder="Nombre" value="<?= verificarEmpty($cabecera['cotizacion'], 3) ?>">
						<input type="hidden" class="ui" name="idCotizacion" placeholder="Nombre" value="<?= verificarEmpty($cabecera['idCotizacion'], 3) ?>">

					</div>
					<div class="three wide field">
						<div class="ui sub header">Cuenta :</div>
						<input type="text" class="ui" name="cuenta" placeholder="Nombre" value="<?= verificarEmpty($cabecera['cuenta'], 3) ?>">
					</div>
					<div class="three wide field">
						<div class="ui sub header">Cod. Cotizacion :</div>
						<input type="text" class="ui" name="codCotizacion" placeholder="Nombre" value="<?= verificarEmpty($cabecera['codCotizacion'], 3) ?>">
					</div>
				
				</div>
				<div class="fields">	
					<div class="nine wide field">
						<div class="ui sub header">Centro de Costo :</div>
						<input type="text" class="ui" name="cuentaCentroCosto" placeholder="Nombre" value="<?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?>">
					</div>
					<div class="five wide field">
							<div class="ui sub header">Progreso de la Cotizacion :</div>
							<input type="text" class="ui" name="cotizacionEstado" placeholder="Nombre" value="<?= verificarEmpty($cabecera['cotizacionEstado'], 3) ?>">
					</div>
					<div class="three wide field">
							<div class="ui sub header">Fecha de Emision :</div>
							<input type="text" class="ui" name="fechaEmision" placeholder="Nombre" value="<?= verificarEmpty($cabecera['fechaEmision'], 3) ?>">
					</div>
				</div>
				<div class="fields">	
					<div class="four wide field">
						<div class="ui sub header">Fecha Sustento :</div>
						<div class="ui calendar date-semantic">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input type="text" placeholder="Fecha Sustento" value="<?= verificarEmpty($cabecera['fechaSustento'], 3) ?>">
							</div>
						</div>
						<input type="hidden" class="date-semantic-value" name="fechaSustento" placeholder="Fecha Sustento" value="">		
					</div>
					<div class="four wide field">
						<div class="ui sub header">Fecha Envio Finanzas :</div>
						<div class="ui calendar date-semantic">
							<div class="ui input left icon">
								<i class="calendar icon"></i>
								<input type="text" placeholder="Fecha Envio Finanzas" value="<?= verificarEmpty($cabecera['fechaEnvioFinanzas'], 3) ?>">
							</div>
						</div>
						<input type="hidden" class="date-semantic-value" name="fechaEnvioFinanzas" placeholder="Fecha Envio Finanzas" value="">	
					</div>
					<div class="nine wide field">
						<div class="ui sub header">Aprovador :</div>
						<div class="ui input">
							<input class="" type="text" name="aprovador" value="<?= verificarEmpty($cabecera['aprovador'], 3) ?>" >
						</div>
					</div>
					
				</div>

				<div class="fields">	
					<div class="five wide field">
						<div class="ui sub header">Monto Sincerado :</div>
						<div class="ui input">
							<input class="onlyNumbers" type="text" name="montoSincerado" placeholder="0.00"  value="<?= verificarEmpty($cabecera['montoSincerado'], 3) ?>">
						</div>
					</div>
					<div class="three wide field">
						<div class="ui sub header">Linea :</div>
						<div class="ui input">
							<input class="onlyNumbers" type="text" name="agregarLineaNum" placeholder="0.00" >
						</div>
					</div>
					<div class="one wide field" style="display: flex;justify-content: center;align-items: flex-end;">
						<button id="btn-añadir-linea" class="btn btn-xl btn-success" title="linea"><i class="fas fa-plus"></i></button>
					</div>
				</div>
				
				<div class="fields" style="display: flex;justify-content: center;">
					<div class="five wide field">
					<table class="table table-bordered table-sm" id="tbLineaNum">
						<thead><tr><th>Nombre</th><th>Cantidad</th></tr></thead>
						<tbody>
						
<? foreach($linea as $row){ ?>
			
		<tr>
		<td>Linea</td>
		<td><?=$row['cantidad']?></td>
		</tr>
	<?php }  ?>
						</tbody>
					</table>
					</div>
				</div>
		</div>
	</div>
	
</form>