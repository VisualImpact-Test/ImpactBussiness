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
						<a data-toggle="tab" href="#idContentArticulo" class="active nav-link" data-value="1">Detalle</a>
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
		<form id="frm-articulo">
			<div class="card-header" style="margin-bottom: 14px;justify-content: center;">
				<h4>CONFIGURACIÓN</h4>
			</div>
			<div>
				<input type="hidden" id="idTipoFormato" name="tipoFormato" value="1">
			</div>
			<div class="customizer-content-button center-items">
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="reporte" id="btn-filtrarTarifarioArticulo" title="Consultar">
					<i class="fa fa-search"></i> <span class="txt_filtro"></span>
				</button>
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="registrar" id="btn-registrarTarifarioArticulo" title="Nuevo">
					<i class="fas fa-plus"></i> <span class="txt_filtro"></span>
				</button>
			</div>
			<hr>
			<div class="customizer-content-filter">
				<h5 class="text-bold-500"><i class="fal fa-filter"></i> <u>Filtros</u></h5>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Tipo Articulo</span>
							<select class="form-control form-control-sm ui my_select2Full" name="tipoArticulo" id="tipoArticulo">
								<?= htmlSelectOptionArray2(['query' => $tipoArticulo, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Marca Articulo</span>
							<select class="form-control form-control-sm ui my_select2Full" name="marcaArticulo" id="marcaArticulo">
								<?= htmlSelectOptionArray2(['query' => $marcaArticulo, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Categoria Articulo</span>
							<select class="form-control form-control-sm ui my_select2Full" name="categoriaArticulo" id="categoriaArticulo">
								<?= htmlSelectOptionArray2(['query' => $categoriaArticulo, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Articulo</span>
							<input class="form-control form-control-sm" name="articulo" id="articulo">
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Proveedor</span>
							<select class="form-control form-control-sm ui my_select2Full" name="proveedor" id="proveedor">
								<?= htmlSelectOptionArray2(['query' => $proveedor, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="">
							<fieldset class="scheduler-border" style="text-align-last: center;">
								<legend class="scheduler-border" style="font-size: 15px;margin-bottom: 0;">Precio</legend>
								<div class="control-group" style="width:45%; display: inline-block;">
									<label class="form-control" for="precioMinimo" style="border:0px;">Mínimo</label>
									<input class="form-control soloNumeros" id="precioMinimo" name="precioMinimo">
								</div>
								<div class="control-group" style="width:45%; display: inline-block;">
									<label class="form-control" for="precioMaximo" style="border:0px;">Máximo</label>
									<input class="form-control soloNumeros" id="precioMaximo" name="precioMaximo">
								</div>
							</fieldset>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group chk_quiebres">
							<div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
								<label class="btn btn-outline-secondary custom_tooltip">
									<span class="tooltiptextButton">Mostrar solo los de uso actual</span>
									<input type="radio" name="chMostrar" class="chMostrar" value="1"> Actual </i>
								</label>
								<label class="btn btn-outline-secondary  custom_tooltip">
									<span class="tooltiptextButton">Mostrar todos</span>
									<input type="radio" name="chMostrar" class="chMostrar" checked="checked" value="0"> Todos </i>
								</label>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<h5 class="text-bold-500" style="margin: 0;margin-bottom: 15px;"><i class="far fa-map-signs"></i></i> <u>Leyenda</u></h5>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<i class="fas fa-lg fa-circle" style="color: royalblue;"> <span style="font-family:Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;font-size:initial;"> De uso actual</span></i>
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
			<div class="tab-pane fade show active" id="idContentArticulo" role="tabpanel">
				<?= getMensajeGestion('noResultados') ?>
			</div>
		</div>
	</div>
</div>