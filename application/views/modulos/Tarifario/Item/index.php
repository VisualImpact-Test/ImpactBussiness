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
						<a data-toggle="tab" href="#idContentItem" class="active nav-link" data-value="1">Detalle</a>
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
		<form id="frm-item">
			<div class="card-header" style="margin-bottom: 14px;justify-content: center;">
				<h4>CONFIGURACIÓN</h4>
			</div>
			<div>
				<input type="hidden" id="idTipoFormato" name="tipoFormato" value="1">
			</div>
			<div class="customizer-content-button center-items">
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="reporte" id="btn-filtrarItemTarifario" title="Consultar">
					<i class="fa fa-search"></i> <span class="txt_filtro"></span>
				</button>
				<button type="button" class="btn btn-outline-trade-visual border-0" data-url="registrar" id="btn-registrarItemTarifario" title="Nuevo">
					<i class="fas fa-plus"></i> <span class="txt_filtro"></span>
				</button>
				<button data-form="Tarifario/Item/getFormCargaMasivaTarifario" data-save="Tarifario/Item/guardarCargaMasivaTarifario" type="button" class="btn btn-outline-trade-visual border-0 btn-CustomCargaMasiva" id="" title="Carga Masiva Tarifario">
                    <i class="fas fa-file-upload"></i> <span class="txt_filtro"></span>
                </button>
			</div>
			<hr>
			<div class="customizer-content-filter">
				<h5 class="text-bold-500"><i class="fal fa-filter"></i> <u>Filtros</u></h5>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Tipo Item</span>
							<select class="form-control form-control-sm ui my_select2Full" name="tipoItem" id="tipoItem">
								<?= htmlSelectOptionArray2(['query' => $tipoItem, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Marca Item</span>
							<select class="form-control form-control-sm ui my_select2Full" name="marcaItem" id="marcaItem">
								<?= htmlSelectOptionArray2(['query' => $itemMarca, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Categoria Item</span>
							<select class="form-control form-control-sm ui my_select2Full" name="categoriaItem" id="categoriaItem">
								<?= htmlSelectOptionArray2(['query' => $itemCategoria, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">SubCategoria Item</span>
							<select class="form-control form-control-sm ui my_select2Full" name="subCategoriaItem" id="subCategoriaItem">
								<?= htmlSelectOptionArray2(['query' => $subCategoriaItem, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
							</select>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<span class="tooltiptext">Item</span>
							<input class="form-control form-control-sm" name="item" id="item">
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
				<h3 class="text-bold-500" style="margin: 0;margin-bottom: 15px;"><i class="far fa-map-signs"></i></i> <b>Leyenda</b></h3>
				<div class="form-row">
					<div class="col-md-12">
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<h4><i class="fas fa-lg fa-circle" style="color: red;">  <span style="font-family:Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;font-size:initial;"> Vencido</span></i></h4>
						</div>
						<div class="mb-2 mr-sm-2 position-relative form-group custom_tooltip">
							<h4><i class="fa fa-lg fa-circle" style="color: green;">  <span style="font-family:Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;font-size:initial;"> Actual</span></i></h4>
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
			<div class="tab-pane fade show active" id="idContentItem" role="tabpanel">
				<?= getMensajeGestion('noResultados') ?>
			</div>
		</div>
	</div>
</div>