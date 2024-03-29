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
						<a data-toggle="tab" href="#idContentRequerimientoInterno" class="active nav-link" data-value="1">Detalle</a>
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
		<form id="frm-cotizacion">
			<div class="card-header" style="margin-bottom: 14px;justify-content: center;">
				<h4>CONFIGURACIÓN</h4>
			</div>
			<div>
				<input type="hidden" id="idTipoFormato" name="tipoFormato" value="1">
			</div>
			<div class="customizer-content-button center-items">
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="reporte" id="btn-filtrarRequerimientoInterno" title="Consultar">
					<i class="fa fa-search"></i> <span class="txt_filtro"></span>
				</button>
			</div>
			<hr>
			<!--<div class="customizer-content-filter">
				<h5 class="text-bold-500"><i class="fal fa-filter"></i> <u>Filtros</u></h5>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Cuenta</span>
							<select class="form-control form-control-sm ui my_select2Full parentDependiente" name="cuenta" id="cuenta" data-childDependiente="cuentaCentroCosto">
								< ?= htmlSelectOptionArray2(['query' => $cuenta, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Centro Costo</span>
							<select class="form-control form-control-sm ui childDependiente" name="cuentaCentroCosto" id="cuentaCentroCosto">
								< ?= htmlSelectOptionArray2(['query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Usuario Reg</span>
							<select class="form-control form-control-sm ui childDependiente" name="idUsuarioReg">
								< ?= htmlSelectOptionArray2(['query' => $usuario, 'id' => 'idUsuario', 'value' => 'user_t', 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Cotizacion</span>
							<input class="form-control form-control-sm" name="cotizacion" id="cotizacion">
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Estado</span>
							<select class="form-control form-control-sm ui my_select2Full parentDependiente" name="estadoCotizacion">
								< ?= htmlSelectOptionArray2(['query' => $estado, 'id' => 'idCotizacionEstado', 'value' => 'nombre', 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Fecha Inicio</span>
							<div class="field">
								<div class="ui calendar date-semantic">
									<div class="ui input mini left icon fluid">
										<i class="calendar icon"></i>
										<input type="text" placeholder="Fecha Inicio" value="" patron="requerido">
									</div>
								</div>
								<input type="hidden" class="date-semantic-value" name="fechaDesde" value="" patron="requerido">
							</div>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Fecha Fin</span>
							<div class="ui calendar date-semantic">
								<div class="ui input mini left icon fluid">
									<i class="calendar icon"></i>
									<input type="text" placeholder="Fecha Fin" value="" patron="requerido">
								</div>
							</div>
							<input type="hidden" class="date-semantic-value" name="fechaHasta" value="" patron="requerido">
						</div>
					</div>
				</div>
			</div>-->
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
			<div class="tab-pane fade show active" id="idContentRequerimientoInterno" role="tabpanel">
				<?= getMensajeGestion('noResultados') ?>
			</div>
		</div>
	</div>
</div>