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
							<!-- <input class="form-control input-sm txt-fecha fechaHome" type="text" name="fechaHome" patron="requerido" value="<?= date('d/m/Y') ?>"> -->
							<i class="fad fa-road fa-lg pl-3" style="margin-right:5px;"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- <div class="row">
	<div class="col-lg-<?= $col_1 ?> col-md-12 d-flex">
		<div class="main-card mb-3 card main-cobertura col-md-12 px-0" style="height: 250px;">
			<div class="card-header bg-trade-visual-grad-right text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="fas fa-store-alt fa-lg"></i> COBERTURA
				</h5>
			</div>
			<div class="card-body centrarContenidoDiv vista-cobertura" style="width: 100%;height:250px;">
				<i class="fas fa-spinner-third fa-spin icon-load"></i>
			</div>
		</div>
	</div>
	<div class="col-lg-<?= $col_2 ?> col-md-12 d-flex">
		<div class="main-card mb-3 card main-efectividad col-md-12 px-0" style="height: 250px;">
			<div class="card-header bg-trade-visual-grad-left text-white" style="width: 100%;">
				<h5 class="card-title">
					<i class="fas fa-tasks fa-lg"></i> EFECTIVIDAD<sup>Visitas</sup>
				</h5>
			</div>
			<div class="card-body centrarContenidoDiv vista-efectividad" style="width: 100%;height:250px;">
				<i class="fas fa-spinner-third fa-spin icon-load"></i>
			</div>
		</div>
	</div>
</div> -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcH2xfbm8z-5iSE4knkRJiNKRhKQrhH6E&callback=initMap"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script type="text/javascript" src="assets/custom/js/core/anyChartCustom"></script>
<script type="text/javascript">
	var $usuario = <?= json_encode($usuario) ?>;
</script>