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
						<a data-toggle="tab" href="#idContentReporteFinanzas" class="active nav-link" data-value="1">Detalle</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block">
	<a href="javascript:;" class="customizer-close"><i class="fal fa-times"></i></a>
	<a href="javascript:;" class="customizer-toggle box-shadow-3 bg-trade-visual-grad-left text-white">
		<i class="fal fa-cog fa-lg fa-spin"></i>
	</a>
	<div class="customizer-content p-2 ps-container ps-theme-dark" data-ps-id="aca1f25c-4ed9-a04b-d154-95a5d6494748">
		<form id="frm-reporteFinanzas">
			<div class="card-header" style="margin-bottom: 14px;justify-content: center;">
				<h4>CONFIGURACIÓN</h4>
			</div>
			<div>
				<input type="hidden" id="idTipoFormato" name="tipoFormato" value="1">
			</div>
			<div class="customizer-content-button center-items">
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="reporte" id="btn-filtrarReporteFinanzas" title="Consultar">
					<i class="fa fa-search"></i>
				</button>
				
			</div>
			<hr>
			<div class="customizer-content-filter">
				<h5 class="text-bold-500"><i class="fal fa-filter"></i> <u>Filtros</u></h5>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Proveedor</span>
							<select class="form-control form-control-sm ui my_select2Full" name="idProveedor">
								<?= htmlSelectOptionArray2(['query' => $proveedor, 'class' => 'text-titlecase', 'title' => 'Todos los proveedores', 'id' => 'idProveedor', 'value' => 'razonSocial']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Cuenta</span>
							<select class="form-control form-control-sm ui my_select2Full" name="idCuenta">
								<?= htmlSelectOptionArray2(['query' => $cuenta, 'class' => 'text-titlecase', 'title' => 'Todas las cuentas']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Fecha Inicio</span>
							<div class="ui fluid calendar date-semantic" data-fechainicio="#fechaFinFiltro" id="fechaInicioFiltro">
								<div class="ui mini input left fluid">
									<input type="text" placeholder="Fecha Inicio" value="">
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaInicio" value="">
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Fecha Fin</span>
							<div class="ui fluid calendar date-semantic" data-fechafinal="#fechaInicioFiltro" id="fechaFinFiltro">
								<div class="ui mini input left fluid">
									<input type="text" placeholder="Fecha Fin" value="">
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaFinal" value="">
						</div>

						
					</div>
				</div>
			</div>
			<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
				<div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
			</div>
			<div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;">
				<div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
			</div>
		</form>
	</div>
</div>

<div class="main-card mb-3 card">
	<div class="card-body p-0">
		<div class="tab-content" id="content-auditoria">
			<div class="tab-pane fade show active" id="reporteFinanzas" role="tabpanel">
				<?= getMensajeGestion('noResultados') ?>
			</div>
		</div>
	</div>
</div>