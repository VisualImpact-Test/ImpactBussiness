<!--h5 class="card-title">COBERTURA </h5-->
				<div class="row" >
					<div class="col-6">
						Basemadre
					</div>
					<div class="col-6 text-right font-weight-bold">
						<?=$cobertura['totalCartera']?> PDV
					</div>
				</div>
				<div class="row" >
					<div class="col-6">
						Cobertura <sup>(Acumulado)</sup>
					</div>
					<div class="col-6 text-right ">
					<?=$cobertura['totalCobertura']?> PDV
					</div>
				</div>
				<div class="row" >
					<div class="col-12 ">
						<div class="mb-2 progress">
							<div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?=get_porcentaje($cobertura['totalCartera'],$cobertura['totalCobertura'])?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=get_porcentaje($cobertura['totalCartera'],$cobertura['totalCobertura'])?>%;"><?=get_porcentaje($cobertura['totalCartera'],$cobertura['totalCobertura'])?>%</div>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				<div class="row" >
					<div class="col-6">
						Programados <sup>(Hoy)</sup>
					</div>
					<div class="col-6 text-right font-weight-bold">
						<?=$cobertura['carteraHoy']?> PDV
					</div>
				</div>
				<div class="row" >
					<div class="col-6">
						Cobertura <sup>(Hoy)</sup>
					</div>
					<div class="col-6 text-right ">
					<?=$cobertura['coberturaHoy']?> PDV
					</div>
				</div>
				<div class="row" >
					<div class="col-12 ">
						<div class="mb-2 progress">
							<div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?=get_porcentaje($cobertura['carteraHoy'],$cobertura['coberturaHoy'])?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=get_porcentaje($cobertura['carteraHoy'],$cobertura['coberturaHoy'])?>%;"><?=get_porcentaje($cobertura['carteraHoy'],$cobertura['coberturaHoy'])?>%</div>
						</div>
					</div>
				</div>