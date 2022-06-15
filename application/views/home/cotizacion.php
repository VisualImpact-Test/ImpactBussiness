<style>
/**/
	.line-timeline {
		font-family: Montserrat-Regular, sans-serif !important;
		font-size: 10px;
		color: #000;
		list-style-type: none;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0px;
	}

	.line-li {
	  transition: all 200ms ease-in;
	}

	.line-timestamp {
	  margin-bottom: 20px;
	  padding: 0px 30px;
	  display: flex;
	  flex-direction: column;
	  align-items: center;
	  font-weight: 100;
	}

	.line-status {
	  padding: 10px 10px;
	  display: flex;
	  justify-content: center;
	  border-top: 2px solid #5ab7ac;
	  position: relative;
	  transition: all 200ms ease-in;
	}
	.line-status h4 {
	  font-weight: 600;
	  font-size: 10px;
	  width: 50px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
	}
	
	.line-timestamp span {
	  width: 50px;
	  font-size: 9px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
	}
	
	.line-status:before {
	  content: "";
	  width: 25px;
	  height: 25px;
	  background-color: white;
	  border-radius: 25px;
	  border: 1px solid #ddd;
	  position: absolute;
	  top: -15px;
	  left: 42%;
	  transition: all 200ms ease-in;
	}

	.line-li.line-complete .line-status {
	  border-top: 2px solid #5ab7ac;
	}
	.line-li.line-complete .line-status:before {
	  background-color: #5ab7ac;
	  border: none;
	  transition: all 200ms ease-in;
	}
	.line-li.line-complete .line-status h4 {
	  color: #5ab7ac;
	}
</style>

<div class="main-card mb-3 card main-efectividad col-md-12 px-0">
	<div class="card-header " style="width: 100%;">
		<h5 class="card-title">
			COD COTIZACION: <?=$cotizacion['codCotizacion']?>
		</h5>
	</div>
	<div class="card-body  vista-efectividad" style="width: 100%;">
		<div class="row">
			<div class="col-md-12">
				<div id="dv-requerimiento">
					<div class="row">
						<div class="col-md-3">
							<div class="req-content">
								Fecha Registro: <strong><?=date_format(new DateTime($cotizacion['fechaEmision']),"d/m/Y")?></strong>
								<br> Hora Registro: <strong><?=date_format(new DateTime($cotizacion['fechaEmision']),"H:i:s")?></strong>
								<br> Centro Costo: <strong><?=$cotizacion['centro_costo']?></strong>
								<br> Usuario: <strong>Usuario, Demo TI</strong>
								<br> Cotizacion: <strong><?=$cotizacion['titulo']?></strong>
							</div>
						</div>
						<div class="col-md-3">
							<div class="req-content">
								<br> Fecha Término: <strong>
									<?=!empty($cotizacion['fechaTermino'])? date_format(new DateTime($cotizacion['fechaTermino']),"d/m/Y") : '-'?>
								</strong>
								<br> Hora Termino: <strong>
									<?=!empty($cotizacion['fechaTermino'])? date_format(new DateTime($cotizacion['fechaTermino']),"H:i:s") : '-'?>
								</strong>
								<br> Días Transcurridos: <span class="badge bg-ok" style="font-size: 12px;">
									<strong>
										<i class="far fa-calendar-check"></i> <?=$cotizacion['dias']?> </strong>
								</span>
								<br> Etapa Actual: <strong>
									<?=$cotizacion['icono']?>
								</strong>
							</div>
						</div>
						<div class="col-md-3">
							<div class="req-content">
								<strong style="text-decoration: underline;">Datos Complementarios</strong>
								<br>
								Monto: <strong>S/ <?=number_format($cotizacion['total'], 2)?></strong> <br>
								Observación: <strong><?!empty($cotizacion['comentario'])? $cotizacion['comentario']: '-'?></strong> <br>
							</div>
						</div>
						<div class="col-md-3">
							<div class="req-content">
								Cantidad de Items: <strong><?=$cotizacion['total_item']?></strong> <br>
								<ul>
									<?foreach($detalle as $row){?>
										<li><?=$row['nombre']?> <strong>(Cant. <?=$row['cantidad']?>)</strong></li>
									<?}?>
								</ul>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
		<div class="row">
		<div class="col-md-12">
			<ul class="line-timeline" id="timeline">
			<?foreach($estados as $row_e){?>
				
					<?
						$complete = ($row_e['idCotizacionEstado'] <= $cotizacion['idCotizacionEstado'])? 'line-complete' : '';
						$title= '';
						if(!empty($complete)){
							$title = 'title = "'.$row_e['nombre'].' - Usuario: Usuario, Demo TI - Fecha: '.date_format(new DateTime($cotizacion['fechaEmision']),"d/m/Y").'"';
						}
					?>
				<li class="line-li <?=$complete?>" <?=$title?> >
					<div class="line-timestamp">
						<?if(!empty($complete)){?>
							<span class="author">Usuario, Demo TI</span>
							<span class="date"><center><?=date_format(new DateTime($cotizacion['fechaEmision']),"d/m/Y")?></center></span>
							<span class="date"><center><?=date_format(new DateTime($cotizacion['fechaEmision']),"H:i:s")?></center></span>
							
						<?} else { ?>
							<span class="author">&nbsp;</span>
							<span class="date">&nbsp;</span>
							<span class="date">&nbsp;</span>
						<?}?>
					</div>
					<div class="line-status">
						<h4><?=$row_e['nombre']?></h4><br />
						<?if(!empty($complete) && $row_e['idCotizacionEstado'] == 3){?>
							<center><a href="javascript:;" class="ver-segmentos" style="color: red"><i class="fa fa-solid fa-bell-on"></i></a></center>
						<?}?>
					</div>
				</li>
			<?}?>
			</ul> 
			<div id="dv-segmentos" class="row d-none">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					
					<div class="row">
						<?foreach($areas as $row_a){?>
							<div class="col-sm-3">
								<?if($row_a['completado'] == 1){?>
									<center><span class="ui blue image label"><?=$row_a['nombre']?></span><br><span style="font-size: 9px"><?=date_format(new DateTime($row_a['fecha']),"d/m/Y")?><br />Usuario, TI Demo</span></center>
								<?} else {?>
									<center><span class="ui gris image label"><?=$row_a['nombre']?></span><br><span style="font-size: 9px">Pendiente</span></center>
								<?}?>
							</div>
						<?}?>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</div>
	</div>
</div>