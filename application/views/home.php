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
				<div class="m-2">
					<span class="ui gris image label" >
					Importante: <i class="far fa-clock fa-lg"></i> TimeLine, aqui se mostraran las cotizaciones en proceso actualmente, ademas de agrupadas por la etapa en la que se encuentran. <i class="fas fa-tasks fa-lg"></i> Resumen de <?=strtoupper(strftime("%B del %Y"));?>, se toma en cuenta todas la solicitudes realizadas en el mes.
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-3">
		<div class="main-card mb-3 card col-md-12 px-0 " style="width: 100%;">
			<div class="card-header bg-trade-visual-grad-right text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="far fa-clock fa-lg"></i> TimeLine
				</h5>
			</div>
			<div class="card-body"  style="width: 100%;">
				<div class="linetime-timeline">
					<div class="linetime-timeline__group">
						<span class="linetime-timeline__year" style="text-align: center;">EN CURSO: <strong><?=$arr_total_estados?></strong>
						</span>
						<br>
						<?$ix = 1;foreach($arr_estados as $row){?>
							<div class="linetime-timeline__box">
								<div class="linetime-timeline__date <?=isset($row['cantidad'])? '' : 'linetime-timeline__date_disabled';?>"><!---->
									<span class="linetime-timeline__day">
										<sup>#</sup>
										<?if(isset($row['cantidad'])){?>
										<a href="javascript:;" class="ver-lista" data-tipo="global" data-estado="<?=$row['id']?>"><?=$row['cantidad'];?></a>
										<?} else {?>
										0
										<?}?>
									</span>
									<span class="linetime-timeline__month">Etapa <?=$ix++?></span>
								</div>
								<div class="linetime-timeline__post">
									<div class="linetime-timeline__content"> <?=$row['nombre']?> </div>
								</div>
							</div>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
			<div class="card-header bg-trade-visual-grad-left text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="fas fa-tasks fa-lg"></i> Resumen de <?=strtoupper(strftime("%B del %Y"));?>
				</h5>
			</div>
			<div class="card-body  vista-efectividad" style="width: 100%;">
				<div class="row">
					<div class="col-lg-3">
						<center><span>
								<h1><?=$arr_cotizaciones_total_pasado?></h1>
								<span>Solicitudes Resagadas</span>
								<span class="ui red image label"><a href="javascript:;" class="ver-lista-pasados" ><i class="fa fa-filter"></i></a></span>
						</span></center>
					</div>
					<div class="col-lg-3">
						<center><span>
								<h1><?=$arr_cotizaciones_total_actual?></h1>
								<span>Solicitudes</span>
								<span class="ui green image label"><a href="javascript:;" class="ver-lista-actuales" ><i class="fa fa-filter"></i></a></span>
						</span></center>
					</div>
					<div class="col-lg-3">
						<center>
							<span class="ui yellow image label">
								<h1><?=($arr_cotizaciones_total_actual > 0)? round($arr_cotizaciones_total_actual_efec/$arr_cotizaciones_total_actual,2): 0?>%</h1>
								<span>Porcentaje Efectivas</span>
							</span>
						</center>
					</div>
					<div class="col-lg-3">
						<center><span>
								<h1>S/. <?=number_format($arr_cotizaciones_total_monto_actual_efec, 2)?></h1>
								<span>Valorización Efectiva</span>
						</span></center>
					</div>
				</div>
				<br/><span class="ui gris image label float-right"><a href="javascript:;" class="ver-lista-todo" >Ver Todo</a></span><br/>
				<div id="dv-lista-solicitudes">
					
					<table class="table resposive tb-reporte ">
						<thead>
							<tr>
								<th>#</th>
								<th>COD COTIZACIÓN</th>
								<th>FECHA <br>REGISTRO </th>
								<th>FECHA <br>TERMINO </th>
								<th>CENTRO COSTO</th>
								<th>USUARIO</th>
								<th>DESCRIPCIÓN</th>
								<th>MONTO TOTAL</th>
								<th>DÍAS <br>TRANS. </th>
								<th>ETAPA <br>ACTUAL </th>
							</tr>
						</thead>
						<tbody>
							<?$ix = 1; foreach($arr_cotizaciones as $row){?>
								<tr data-estado="<?=$row['idEstado']?>" data-actual="<?=$row['pasado']?>" data-cod-req="<?=$row['idCotizacion']?>">
									<td class="text-center"><?=$ix++?></td>
									<td class="text-center">
										<a href="javascript:;" class="ver-coti" title="Ver Cotización" data-id="<?=$row['idCotizacion']?>"><?=$row['codCotizacion']?></a>
									</td>
									<td class="text-center"><?=date_format(new DateTime($row['fechaEmision']),"d/m/Y")?></td>
									<td class="text-center">
										<?=!empty($row['fechaTermino'])? date_format(new DateTime($row['fechaTermino']),"d/m/Y") : '-'?>
									</td>
									<td><?=$row['centro_costo']?></td>
									<td>Usuario, Demo TI</td>
									<td><?=$row['titulo']?></td>
									<td class="text-right"><?=number_format($row['total'], 2)?></td>
									<td class="text-center"><strong><?=$row['dias']?></strong></td>
									<td class="text-right">
										<?=$row['icono']?>
									</td>
								</tr>
							<?}?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div id="dv-cotizacion-detalle" class="d-none" >
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