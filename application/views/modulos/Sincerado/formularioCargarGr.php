<form class="ui form" role="form" id="formRegistroGrSincerado" method="post" autocomplete="off">
	<div class="divForm">
		<div class="ui steps">
			<a class="active step 1step">
				<i class="project diagram icon"></i>
				<div class="content">
					<div class="title">Tipo de Facturación</div>
					<input type="hidden" id="montoSincerado" value="<?= $sincerado['totalSincerado'] ?>">
					<input type="hidden" value="<?= $sincerado['idSincerado'] ?>" name="idSincerado">
					<!-- <div class="description">Choose your shipping options</div> -->
				</div>
			</a>
			<a class="step 2step">
				<i class="file alternate outline icon"></i>
				<div class="content">
					<div class="title">Cargar GR</div>
					<!-- <div class="description">Enter billing information</div> -->
				</div>
			</a>
		</div>
		<div class="ui equal width left aligned padded grid divTipoFacturacion">
			<div class="row">
				<div class="column">
					<div class="sixteen wide field">
						<div class="ui sub header">Tipo de Facturación</div>
						<select class="ui dropdown semantic-dropdown fluid cbo_tipoFacturacion" name="tipoFacturacion">
							<option value="0" selected>Simple</option>
							<option value="1">Multiple</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="column">
					<div class="sixteen wide field">
						<button class="ui teal button" type="button" onclick="Sincerado.mostrarOpcionesDeGR(this);">
							<i class="arrow right icon"></i> Continuar
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="ui equal width left aligned padded grid divCargaGr d-none">
			<div class="row opc_mult">
				<div class="column">
					<button class="ui blue button fnBtn_AddFila" type="button" data-divprincipal=".divCargaGr" data-contenido=".detBase">
						<i class="plus icon"></i> Agregar
					</button>
					<button class="ui red button fnBtn_DeleteFila" type="button" data-divprincipal=".divCargaGr" data-contenido=".detBase">
						<i class="trash icon"></i> Borrar
					</button>
				</div>
				<div class="column">
					<h2 class="ui right floated header">
						<i class="money bill icon"></i>
						<div class="content">
							<?= $moneda['simbolo'] . ' ' . numeroVista($sincerado['totalSincerado']); ?>
							<div class="sub header">Monto Total del Sincerado</div>
						</div>
					</h2>
				</div>
			</div>
			<div class="row column detBase">
				<div class="column">
					<div class="wide field">
						<div class="ui sub header">Usuario</div>
						<div class="ui input fluid">
							<input name="usuario" patron="requerido">
						</div>
					</div>
				</div>
				<div class="column">
					<div class="wide field">
						<div class="ui sub header">Descripción</div>
						<div class="ui input fluid">
							<input name="descripcion" patron="requerido">
						</div>
					</div>
				</div>
				<div class="column">
					<div class="wide field">
						<div class="ui sub header">Fecha</div>
						<div class="ui calendar date-semantic">
							<div class="ui input left icon fluid">
								<i class="calendar icon"></i>
								<input type="text" value="" patron="requerido">
							</div>
						</div>
						<input type="hidden" class="date-semantic-value" name="fecha" value="">
					</div>
				</div>
				<div class="column">
					<div class="sixteen wide field">
						<div class="ui sub header">Concepto</div>
						<select class="form-control col-md-12" id="conceptoTracking" name="conceptoTracking" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'id' => 'idConceptoTracking', 'value' => 'nombre', 'query' => $conceptoTracking, 'class' => 'text-titlecase']); ?>
						</select>
					</div>
				</div>
				<div class="column opc_mult">
					<div class="wide field">
						<div class="ui sub header">Porcentaje</div>
						<div class="ui input right labeled fluid">
							<input name="porcentaje" class="onlyNumbers keyUpChange cargaGr_por" value="100" onchange="Sincerado.calcularMontoDeGR(this);">
							<div class="ui basic label"> % </div>
						</div>
					</div>
				</div>
				<div class="column opc_mult">
					<div class="wide field">
						<div class="ui sub header">Monto</div>
						<div class="ui input fluid">
							<input name="monto" class="onlyNumbers cargaGr_mon" value="<?= $sincerado['totalSincerado'] ?>" readonly>
						</div>
					</div>
				</div>
				<div class="column opc_mult">
					<div class="wide field">
						<div class="ui sub header">% Sincerado</div>
						<div class="ui input right labeled fluid">
							<input name="porcentajeSincerado" class="onlyNumbers keyUpChange cargaGr_porSin" value="100" onchange="Sincerado.calcularMontoDeGR(this);">
							<div class="ui basic label"> % </div>
						</div>
					</div>
				</div>
				<div class="column opc_mult" style="width: 100px;">
					<div class="wide field">
						<div class="ui sub header">Presupuesto Sinc.</div>
						<div class="ui input fluid">
							<input name="presupuestoSincerado" class="onlyNumbers cargaGr_preSin" value="<?= $sincerado['totalSincerado'] ?>" readonly>
						</div>
					</div>
				</div>
				<div class="column opc_mult">
					<div class="wide field">
						<div class="ui sub header">Diferencia</div>
						<div class="ui input fluid">
							<input name="diferenciaSincerado" class="onlyNumbers cargaGr_dif" value="0" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>