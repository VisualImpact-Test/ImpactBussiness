<!--h5 class="card-title">EFECTIVIDAD <sup>Visitas</sup></h5-->
				<div class="row" >
					<div class="col-5 ">
						<h6 class="text-success">ACUMULADO</h6>
						<div class="row" >
							<div class="col-6">
								<strong>Programadas</strong>
							</div>
							<div class="col-6 text-right">
								<?=$efectividad['totalProg']?> 
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>Efectivas</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-success" >
									<?=get_porcentaje($efectividad['totalProg'],$efectividad['totalEfectiva'])?>%</div> 
									<?=generar_espacios($efectividad['totalProg'],$efectividad['totalEfectiva'])?><?=$efectividad['totalEfectiva']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>En proceso</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-info" >
									<?=get_porcentaje($efectividad['totalProg'],$efectividad['totalProcesos'])?>%</div>
									<?=generar_espacios($efectividad['totalProg'],$efectividad['totalProcesos'])?><?=$efectividad['totalProcesos']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>Incidencias</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-warning" >
									<?=get_porcentaje($efectividad['totalProg'],$efectividad['totalIncidencia'])?>%</div>
									<?=generar_espacios($efectividad['totalProg'],$efectividad['totalIncidencia'])?><?=$efectividad['totalIncidencia']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>No realizadas</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-danger" >
								<?=get_porcentaje($efectividad['totalProg'],$efectividad['totalNoVisitados'])?>%</div>
								<?=generar_espacios($efectividad['totalProg'],$efectividad['totalNoVisitados'])?><?=$efectividad['totalNoVisitados']?>
							</div>
						</div>
					</div>
					<div class="col-7 ">
						<h6 class="text-success text-uppercase">FECHA <h7 style="font-size: 11px;">( <?=get_fecha_larga(date_change_format_bd($fecha)) ?> )</h7></h6>
						<div class="row" >
							<div class="col-6">
								<strong>Programadas</strong>
							</div>
							<div class="col-6 text-right">
							<?=$efectividad['totalProgHoy']?> 
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>Efectivas</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-success" >
								<?=get_porcentaje($efectividad['totalProgHoy'],$efectividad['totalEfectivaHoy'])?>%</div>
								<?=generar_espacios($efectividad['totalProgHoy'],$efectividad['totalEfectivaHoy'])?><?=$efectividad['totalEfectivaHoy']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>En proceso</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-info" >
								<?=get_porcentaje($efectividad['totalProgHoy'],$efectividad['totalProcesoHoy'])?>%</div> 
								<?=generar_espacios($efectividad['totalProgHoy'],$efectividad['totalProcesoHoy'])?><?=$efectividad['totalProcesoHoy']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>Incidencias</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-warning" >
								<?=get_porcentaje($efectividad['totalProgHoy'],$efectividad['totalIncidenciaHoy'])?>%</div>
								<?=generar_espacios($efectividad['totalProgHoy'],$efectividad['totalIncidenciaHoy'])?><?=$efectividad['totalIncidenciaHoy']?>
							</div>
						</div>
						<div class="row" >
							<div class="col-6">
								<strong>No realizadas</strong>
							</div>
							<div class="col-6 text-right">
								<div class="badge badge-pill badge-danger" >
								<?=get_porcentaje($efectividad['totalProgHoy'],$efectividad['totalNoVisitadosHoy'])?>%</div> 
								<?=generar_espacios($efectividad['totalProgHoy'],$efectividad['totalNoVisitadosHoy'])?><?=$efectividad['totalNoVisitadosHoy']?>
							</div>
						</div>
					</div>
				</div>