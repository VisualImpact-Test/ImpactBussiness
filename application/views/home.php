<?
// $aUsuario = [1,4,5,6,171,148,466,482,483,485,394];
$col_1 = 4;
$col_2 = 8;
$col_3 = 0;

// if( in_array($this->idUsuario, $aUsuario) ){
// 	$col_1 = 3;
// 	$col_2 = 6;
// 	$col_3 = 3;
// }

if (empty($idCuenta) || $idCuenta != 2) {
	$col_1 = 4;
	$col_2 = 8;
} else {
	$col_1 = 3;
	$col_2 = 6;
	$col_3 = 3;
}

?>
<style>
	.control-w-sm {
		height: calc(1.5em + 0.75rem + 2px) !important;
		font-size: 1rem !important;
	}
</style>
<div class="row mt-4">
	<div class="col-lg-2 d-flex justify-content-center align-items-center">
		<h3 class="card-title mb-3">
			<i class="<?= $icon ?>"></i>
			<?= $title ?>
		</h3>
	</div>
	<div class="col-lg-10 d-flex">
		<div class="card w-100 mb-3 p-0">
			<div class="card-body p-0">
				<ul class="nav nav-tabs nav-justified">
					<li class="nav-item btnReporte" id="tipoReporte" name="tipoReporte" url="visibilidad">
						<input type="hidden" id="txtcuenta" value="<?= $this->sessIdCuenta ?>">
						<a data-toggle="tab" href="javascript:;" class="active nav-link aFechaHome" data-value="1">
							<i class="fad fa-calendar-alt fa-lg" style="margin-right:5px;"></i>
							<input class="form-control input-sm txt-fecha fechaHome" type="text" name="fechaHome" patron="requerido" value="<?= date('d/m/Y') ?>">
							<i class="fad fa-road fa-lg pl-3" style="margin-right:5px;"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3 col-md-12">
		<div class="main-card mb-3 card main-cobertura col-md-12 px-0">
			<div class="card-header bg-trade-visual-grad-right text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="fas fa-store-alt fa-lg"></i> TimeLine
				</h5>
			</div>
			<div class="card-body vista-cobertura" style="width: 100%;height:auto;">
				<div class="linetime-timeline">
					<div class="linetime-timeline__group">
						<span class="linetime-timeline__year" style="text-align: center;">TOTAL COTIZACIONES EN CURSO: <strong>3</strong>
						</span>
						<br>
						<br>
						<div class="linetime-timeline__box">
							<div class="linetime-timeline__date ">
								<span class="linetime-timeline__day">
									<sup>#</sup> 
									<a href="javascript:;" class="ver-lista" data-tipo="global" data-estado="1">1</a>
								</span>
								<span class="linetime-timeline__month">Etapa 1</span>
							</div>
							<div class="linetime-timeline__post">
								<div class="linetime-timeline__content"> Registrado </div>
							</div>
						</div>
						<br>
						<div class="linetime-timeline__box">
							<div class="linetime-timeline__date linetime-timeline__date_disabled">
								<span class="linetime-timeline__day">
									<sup>#</sup> 0 </span>
								<span class="linetime-timeline__month">Etapa 2</span>
							</div>
							<div class="linetime-timeline__post">
								<div class="linetime-timeline__content"> Enviado </div>
							</div>
						</div>
						<br>
						<div class="linetime-timeline__box">
							<div class="linetime-timeline__date ">
								<span class="linetime-timeline__day">
									<sup>#</sup> 
									<a href="javascript:;" class="ver-lista" data-tipo="global" data-estado="3">2</a>
								</span>
								<span class="linetime-timeline__month">Etapa 3</span>
							</div>
							<div class="linetime-timeline__post">
								<div class="linetime-timeline__content"> Confirmado </div>
							</div>
						</div>
						<br>
						<div class="linetime-timeline__box">
							<div class="linetime-timeline__date linetime-timeline__date_disabled">
								<span class="linetime-timeline__day">
									<sup>#</sup> 0 </span>
								<span class="linetime-timeline__month">Etapa 4</span>
							</div>
							<div class="linetime-timeline__post">
								<div class="linetime-timeline__content"> OC Pendiente </div>
							</div>
						</div>
						<br>
						<div class="linetime-timeline__box">
							<div class="linetime-timeline__date linetime-timeline__date_disabled">
								<span class="linetime-timeline__day">
									<sup>#</sup> 0 </span>
								<span class="linetime-timeline__month">Etapa 5</span>
							</div>
							<div class="linetime-timeline__post">
								<div class="linetime-timeline__content"> Finalizado </div>
							</div>
						</div>
						<br>
						<!-- -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 col-md-12">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
			<div class="card-header bg-trade-visual-grad-left text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="fas fa-tasks fa-lg"></i> Análisis Mensual de Cotizaciones
				</h5>
			</div>
			<div class="card-body  vista-efectividad" style="width: 100%;">
				<div id="dv-indicador-req">
					<table class="table resposive tb-reporte ">
						<thead>
							<tr>
								<th>#</th>
								<th>ver-cotiZACION</th>
								<th>FECHA <br>EMISION </th>
								<th>FECHA <br>ENVIO </th>
								<th>FECHA <br>TERMINO </th>
								<th>CUENTA</th>
								<th>ÁREA</th>
								<th>SOLICITANTE</th>
								<th>COTIZACION</th>
								<th>DÍAS <br>TRANS. </th>
								<th>ETAPA <br>ACTUAL </th>
							</tr>
						</thead>
						<tbody>
							<tr data-estado="3" data-cod-req='23'>
								<td class="text-center">1</td>
								<td>
									<a href="javascript:;" class="ver-coti" title="Ver Requerimiento" data-id="2762">COTI-0000023</a>
								</td>
								<td class="text-center">02/03/2022</td><td class="text-center">02/03/2022</td>
								<td class="text-center">
									<strong>02/03/2022</strong>
								</td>
								<td>NESTLE</td>
								<td>TRADE</td>
								<td>BAILON PEREZ MARGARITA</td>
								
								<td>1 MAQUILADOR</td>
								
								<td class="text-center bg-ok">
									<strong>-</strong>
								</td>
								<td>
									<i class="fas fa-square td-enproceso"></i> Confirmado
								</td>
							</tr>
							<tr data-estado="1" data-cod-req='24'>
								<td class="text-center">2</td>
								<td>
									<a href="javascript:;" class="ver-coti" title="Ver Requerimiento" data-id="2763">COTI-0000024</a>
								</td>
								<td class="text-center">02/03/2022</td><td class="text-center">02/03/2022</td>
								<td class="text-center">
									<strong>02/03/2022</strong>
								</td>
								<td>NESTLE</td>
								<td>TRADE</td>
								<td>BAILON PEREZ MARGARITA</td>
								
								<td>1 MAQUILADOR</td>
								
								<td class="text-center bg-ok">
									<strong>-</strong>
								</td>
								<td>
									<i class="fas fa-square td-enproceso"></i> Registrado
								</td>
							</tr>
							<tr data-estado="3" data-cod-req='25'>
								<td class="text-center">3</td>
								<td>
									<a href="javascript:;" class="ver-coti" title="Ver Requerimiento" data-id="2766">COTI-0000025</a>
								</td>
								<td class="text-center">02/03/2022</td><td class="text-center">02/03/2022</td>
								<td class="text-center">
									<strong>02/03/2022</strong>
								</td>
								<td>NESTLE</td>
								<td>TRADE</td>
								<td>BAILON PEREZ MARGARITA</td>
								
								<td>1 PROMOTOR</td>
								
								<td class="text-center bg-ok">
									<strong>-</strong>
								</td>
								<td>
									<i class="fas fa-square td-enproceso"></i> Confirmado
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 d-none dvDetalleReq" data-cod-req="23">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
			<div class="card-header " style="width: 100%;">
				<h5 class="card-title">
					COD COTIZACION: COTI-0000023
				</h5>
			</div>
			<div class="card-body  vista-efectividad" style="width: 100%;">
				<div class="row">
					<div class="col-md-12">
						<div id="dv-requerimiento">
							<div class="row">
								
								<div class="col-md-4">
									<div class="req-content">
										<!--<span class="title-req" >COD REQ: <strong>I-P&G-001273</strong></span>--> Fecha Generado: <strong>02/03/2022</strong>
										<br> Cuenta: <strong>PROCTER &amp; GAMBLE</strong>
										<br> Área: <strong>HFS</strong>
										<br> Solicitante: <strong>BAILON PEREZ MARGARITA</strong>
										<br> Cotizacion: <strong>1 GESTOR DE TRADE MARKETING</strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content"> 
										<br> Fecha Término: <strong>02/03/2022</strong>
										<br> Hora Término: <strong>12:22:47</strong>
										<br> Días Transcurridos: <span class="badge bg-ok" style="font-size: 12px;">
											<strong>
												<i class="far fa-calendar-check"></i> 1 </strong>
										</span>
										<br> Etapa Actual: <strong>
											<i class="fas fa-square td-enproceso"></i> Confirmado </strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content">
										<strong style="text-decoration: underline;">Datos Complementarios</strong>
										<br>
										Progreso de Costo: <strong>S/. 3000</strong> <br>
										Cantidad de Items: <strong>10</strong> <br>
									</div>
								</div>
								
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<ul class="line-timeline" id="timeline">
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Registrado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Enviado a RRHH - Usuario: GARGUREVICH FERNANDEZ MILENKA - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">7-GARGUREVICH FERNANDEZ MILENKA</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>12:22:47</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Enviado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Confirmado - Usuario: BALDOCEDA LANZA EVER - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">8-BALDOCEDA LANZA EVER</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>15:10:44</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Confirmado</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>OC Pendiente</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Finalizado</h4>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<hr>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 d-none dvDetalleReq" data-cod-req="24">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
			<div class="card-header " style="width: 100%;">
				<h5 class="card-title">
					COD COTIZACION: COTI-0000024
				</h5>
			</div>
			<div class="card-body  vista-efectividad" style="width: 100%;">
				<div class="row">
					<div class="col-md-12">
						<div id="dv-requerimiento">
							<div class="row">
								
								<div class="col-md-4">
									<div class="req-content">
										<!--<span class="title-req" >COD REQ: <strong>I-P&G-001273</strong></span>--> Fecha Generado: <strong>02/03/2022</strong>
										<br> Cuenta: <strong>PROCTER &amp; GAMBLE</strong>
										<br> Área: <strong>HFS</strong>
										<br> Solicitante: <strong>BAILON PEREZ MARGARITA</strong>
										<br> Cotizacion: <strong>1 GESTOR DE TRADE MARKETING</strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content"> 
										<br> Fecha Término: <strong>02/03/2022</strong>
										<br> Hora Término: <strong>12:22:47</strong>
										<br> Días Transcurridos: <span class="badge bg-ok" style="font-size: 12px;">
											<strong>
												<i class="far fa-calendar-check"></i> 1 </strong>
										</span>
										<br> Etapa Actual: <strong>
											<i class="fas fa-square td-enproceso"></i> Confirmado </strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content">
										<strong style="text-decoration: underline;">Datos Complementarios</strong>
										<br>
										Progreso de Costo: <strong>S/. 3000</strong> <br>
										Cantidad de Items: <strong>10</strong> <br>
									</div>
								</div>
								
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<ul class="line-timeline" id="timeline">
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Registrado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Enviado a RRHH - Usuario: GARGUREVICH FERNANDEZ MILENKA - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">7-GARGUREVICH FERNANDEZ MILENKA</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>12:22:47</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Enviado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Confirmado - Usuario: BALDOCEDA LANZA EVER - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">8-BALDOCEDA LANZA EVER</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>15:10:44</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Confirmado</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>OC Pendiente</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Finalizado</h4>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<hr>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 d-none dvDetalleReq" data-cod-req="25">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
			<div class="card-header " style="width: 100%;">
				<h5 class="card-title">
					COD COTIZACION: COTI-0000025
				</h5>
			</div>
			<div class="card-body  vista-efectividad" style="width: 100%;">
				<div class="row">
					<div class="col-md-12">
						<div id="dv-requerimiento">
							<div class="row">
								
								<div class="col-md-4">
									<div class="req-content">
										<!--<span class="title-req" >COD REQ: <strong>I-P&G-001273</strong></span>--> Fecha Generado: <strong>02/03/2022</strong>
										<br> Cuenta: <strong>PROCTER &amp; GAMBLE</strong>
										<br> Área: <strong>HFS</strong>
										<br> Solicitante: <strong>BAILON PEREZ MARGARITA</strong>
										<br> Cotizacion: <strong>1 GESTOR DE TRADE MARKETING</strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content"> 
										<br> Fecha Término: <strong>02/03/2022</strong>
										<br> Hora Término: <strong>12:22:47</strong>
										<br> Días Transcurridos: <span class="badge bg-ok" style="font-size: 12px;">
											<strong>
												<i class="far fa-calendar-check"></i> 1 </strong>
										</span>
										<br> Etapa Actual: <strong>
											<i class="fas fa-square td-enproceso"></i> Confirmado </strong>
									</div>
								</div>
								<div class="col-md-4">
									<div class="req-content">
										<strong style="text-decoration: underline;">Datos Complementarios</strong>
										<br>
										Progreso de Costo: <strong>S/. 3000</strong> <br>
										Cantidad de Items: <strong>10</strong> <br>
									</div>
								</div>
								
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<ul class="line-timeline" id="timeline">
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Registrado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Enviado a RRHH - Usuario: GARGUREVICH FERNANDEZ MILENKA - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">7-GARGUREVICH FERNANDEZ MILENKA</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>12:22:47</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Enviado</h4>
											</div>
										</li>
										<li class="line-li line-complete" title="Confirmado - Usuario: BALDOCEDA LANZA EVER - Fecha: 01/03/2022">
											<div class="line-timestamp">
												<span class="author">8-BALDOCEDA LANZA EVER</span>
												<span class="date">
													<center>01/03/2022</center>
												</span>
												<span class="date">
													<center>15:10:44</center>
												</span>
											</div>
											<div class="line-status">
												<h4>Confirmado</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>OC Pendiente</h4>
											</div>
										</li>
										<li class="line-li ">
											<div class="line-timestamp">
												<span class="author">&nbsp;</span>
												<span class="date">&nbsp;</span>
												<span class="date">&nbsp;</span>
											</div>
											<div class="line-status">
												<h4>Finalizado</h4>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<hr>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcH2xfbm8z-5iSE4knkRJiNKRhKQrhH6E&callback=initMap"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
	<script type="text/javascript" src="assets/custom/js/core/anyChartCustom"></script>
	<script type="text/javascript">
		var $usuario = <?= json_encode($usuario) ?>;
	</script>