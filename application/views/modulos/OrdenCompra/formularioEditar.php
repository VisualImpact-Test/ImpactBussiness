<div class="row itemData d-none" id="divItemData">
	<div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
		<div class="form-group col-md-6">
			<label class="font-weight-bold mb-0">Item:</label>
			<div class="input-group mb-3">
				<input class="form-control items ui-autocomplete-input item-id" type="text" name="item" patron="requerido" placeholder="Buscar item">
				<div class="input-group-append divItemBlock">
					<button class="btn btn-outline-secondary" type="button" onclick="Oc.editItemValue(this);"><i class="fa fa-edit"></i></button>
				</div>
				<input class="codItems d-none" type='text' name='idItemForm'>
			</div>
		</div>
		<div class="form-group col-md-6">
			<label class="font-weight-bold mb-0">Tipo:</label>
			<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
				<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'tipo']); ?>
			</select>
		</div>
		<div class="form-row col-md-12 contentSemanticDiv divParaCarga">
			<input class="form-control idItemImagen" type="hidden" name="idItemImagen" value="">
			<input class="adjuntoItemCantidad" type="hidden" name="adjuntoItemCantidad" value="0">
			<?= htmlSemanticCargaDeArchivos([
				'classDivBase' => 'divParaCarga', 'maxFiles' => 1,
				'archivosPermitidos' => 'image/*', 'name' => 'adjuntoItem'
			], 2) ?>
		</div>
		<div class="form-row col-md-12 ui imagendivCarga"></div>
		<div class="form-row col-md-12 subItem"></div>
		<div class="form-row">
			<div class="form-group" onclick="Oc.generarSubItem(this, this.value);">
				<a class="form-control btn btn-info btnAdicionar" style="display:none;"><i class="fa fa-plus"></i> Adicionar</a>
			</div>
			<div class="form-group" onclick="Oc.quitarItem(this, this.value);">
				<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
			</div>
		</div>
	</div>
	<div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
		<div class="form-group">
			<label class="font-weight-bold mb-0">Cantidad:</label>
			<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
		</div>
		<div class="form-group d-none">
			<label class="font-weight-bold mb-0">CantidadSubItem:</label>
			<input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="0">
		</div>
		<div class="form-group">
			<label class="font-weight-bold mb-0">Costo:</label>
			<input class="form-control item_costo" name="costo" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="0">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label class="font-weight-bold mb-0">GAP:</label>
				<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="0">
			</div>
			<div class="form-group col-md-6">
				<label class="font-weight-bold mb-0">Sub Total:</label>
				<input class="form-control item_precio" name="precio" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
			</div>
			<div class="form-group col-md-6 d-none">
				<label class="font-weight-bold mb-0">Sub Total real:</label>
				<input class="form-control item_precio_real" name="precio_real" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
			</div>
		</div>
	</div>
</div>
<form class="form" role="form" id="formRegistroOC" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Generales</legend>
				<div class="form-row pt-3">
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Requerimiento:</label>
						<input class="form-control" name="requerimiento" patron="requerido" value="<?= $oc[0]['requerimiento'] ?>">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Concepto:</label>
						<input class="form-control" name="concepto" value="<?= $oc[0]['concepto'] ?>">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Descripcion Compras:</label>
						<input class="form-control" name="descripcionCompras" value="<?= isset($oc[0]['descripcionCompras']) ? $oc[0]['descripcionCompras'] : '' ?>" patron="requerido">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<input type="hidden" name="idOper" value="">
						<label class="font-weight-bold mb-0">Proveedor:</label>
						<select id="proveedor" name="proveedor" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'value' => 'razonSocial', 'id' => 'idProveedor', 'selected' => (isset($oc[0]['idProveedor']) ? $oc[0]['idProveedor'] : null)]); ?>
						</select>
						<input type="hidden" name="idOc" value="<?= isset($oc[0]['idOrdenCompra']) ? $oc[0]['idOrdenCompra'] : '' ?>">
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Cuenta:</label>
						<select class="form-control ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => $oc[0]['idCuenta']]); ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Centro Costo:</label>
						<select class="form-control ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $centroCosto, 'class' => 'text-titlecase', 'selected' => $oc[0]['idCentroCosto']]); ?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Metodo Pago:</label>
						<select id="metodoPago" name="metodoPago" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $metodoPago, 'class' => 'text-titlecase', 'selected' => (isset($oc[0]['idMetodoPago']) ? $oc[0]['idMetodoPago'] : null)]); ?>
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Moneda:</label>
						<!-- Revisar https://fomantic-ui.com/modules/dropdown.html -->
						<div class="ui fluid search selection dropdown simpleDropdown semantic-dropdown ">
							<input type="hidden" name="moneda" value="<?= isset($oc[0]['idMoneda']) ? $oc[0]['idMoneda'] : $moneda[0]['idMoneda'] ?>" patron="requerido">
							<i class="dropdown icon"></i>
							<div class="default text">Moneda</div>
							<div class="menu">
								<?php foreach ($moneda as $value) : ?>
									<div class="item" data-value="<?= $value['idMoneda'] ?>">
										<i class="<?= $value['icono'] ?>"></i><?= $value['nombre'] ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<!-- Fin Revisar -->
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">IGV 18%</label>
						<div class="custom-control custom-switch custom-switch-lg">
							<input type="checkbox" class="custom-control-input" id="incluyeIgv" name="incluyeIgv" onchange="$(this).prop('checked') ? $('#valorIGV').val('118').change() : $('#valorIGV').val('100').change();">
							<label class="custom-control-label" for="incluyeIgv"></label>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">Fecha Entrega:</label>
						<input type="date" class="form-control" name="fechaEntrega" patron="requerido" value="<?= $oc[0]['fechaEntrega'] ?>">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">PO Cliente:</label>
						<input class="form-control" name="poCliente" patron="requerido" value="<?= $oc[0]['poCliente'] ?>">
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold mb-0">Lugar de entrega:</label>
						<select class="form-control ui fluid search dropdown semantic-dropdown" name="idAlmacen" onchange="$('.inpEntrega').val($(this).find('option:selected').data('direccion'));">
							<option>-</option>
							<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $almacenes, 'id' => 'idAlmacen', 'value' => 'nombre', 'class' => 'text-titlecase', 'data-option' => ['direccion'], 'selected' => (isset($oc[0]['idAlmacen']) ? $oc[0]['idAlmacen'] : null)]); ?>
						</select>
					</div>
					<div class="form-group col-md-5">
						<label class="font-weight-bold mb-0">Lugar de Entrega:</label>
						<input class="form-control inpEntrega" name="entrega" value="<?= isset($oc[0]['entrega']) ? $oc[0]['entrega'] : '' ?>">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-10">
						<label class="font-weight-bold mb-0">Observación:</label>
						<?php if (isset($oc[0]['idOrdenCompraDetalle'])) : ?>
							<?php $deOper = false; ?>
						<?php else : ?>
							<?php $deOper = true; ?>
						<?php endif; ?>
						<input class="form-control" name="observacion" value="<?= !$deOper ? $oc[0]['observacion'] : 'En caso de incumplimiento en fecha de entrega, se estará ejecutando penalidad del 1% por cada día de retraso.' ?>">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold mb-0">Mostrar Observación</label>
						<div class="custom-control custom-switch custom-switch-lg">
							<?php $chk = !isset($oc[0]['mostrar_observacion']) || $oc[0]['mostrar_observacion'] == 1 ? 'checked' : ''; ?>
							<input type="checkbox" class="custom-control-input" id="mostrar_observacion" name="mostrar_observacion" <?= $chk ?>>
							<label class="custom-control-label" for="mostrar_observacion"></label>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label class="font-weight-bold mb-0">Comentario:</label>
						<input class="form-control" name="comentario" value="<?= isset($oc[0]['comentario']) ? $oc[0]['comentario'] : '' ?>">
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos de Item<small>(s)</small></legend>
				<div class="extraItem">
					<?php foreach ($oc as $key => $value) : ?>
						<?php if (isset($value['idOrdenCompraDetalle'])) : ?>
							<?php $deOper = false; ?>
						<?php else : ?>
							<?php $deOper = true; ?>
						<?php endif; ?>
						<div class="row itemData">
							<div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
								<div class="form-group col-md-6">
									<label class="font-weight-bold mb-0">Item:</label>
									<div class="input-group mb-3">
										<input class="form-control items ui-autocomplete-input item-id" type="text" name="item" patron="requerido" placeholder="Buscar item" value="<?= $value['item'] ?>" readonly>
										<div class="input-group-append divItemBlock">
											<button class="btn btn-outline-secondary" type="button" onclick="Oc.editItemValue(this);"><i class="fa fa-edit"></i></button>
										</div>
									</div>
									<input class="codItems d-none" type='text' name='idItemForm' value="<?= $value['idItem'] ?>">
								</div>
								<div class="form-group col-md-6">
									<label class="font-weight-bold mb-0">Tipo:</label>
									<select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
										<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'tipo', 'selected' => $value['idTipo']]); ?>
									</select>
								</div>
								<div class="form-row col-md-12 subItem">
									<?php if ($deOper) : ?>
										<?php if (!empty($ocSubItem[$value['idOperDetalle']])) : ?>
											<?php foreach ($ocSubItem[$value['idOperDetalle']] as $si_k => $si_v) : ?>
												<?php if ($value['idTipo'] == '2') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Descripción Serv.:</label>
															<input class="form-control" name="subItem_nombre" patron="requerido" value="<?= $si_v['nombre'] ?>">
														</div>
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Cantidad:</label>
															<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="Oc.cantidadServicio(this);" onkeyup="Oc.cantidadServicio(this);" value="<?= $si_v['cantidad'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_costo" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '7') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Item Logistica:</label>
															<select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
																<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label', 'selected' => $si_v['idItemLogistica']]); ?>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Peso:</label>
															<input class="form-control cantidadSI" name="subItem_cantidad" patron="requerido" onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" value="<?= $si_v['cantidad'] ?>">
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Cantidad PDV:</label>
															<input class="form-control cantidadPDV" name="subItem_cantidadPdv" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $si_v['cantidadPDV'] ?>">
														</div>
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Tipo Servicio:</label>
															<select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
																<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida'], 'selected' => $si_v['idTipoServicio']]); ?>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Unidad Medida:</label>
															<input class="form-control umSubItem" name="subItem_um" patron="requerido" readonly value="<?= $si_v['unidadMedida'] ?>">
															<input type="hidden" class="form-control idUmSubItem" name="subItem_idUm" patron="requerido" value="<?= $si_v['idUnidadMedida'] ?>">
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Costo:</label>
															<input class="form-control costoSubItem" name="subItem_costo" patron="requerido" readonly onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" value="<?= $si_v['costo'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '9') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-1">
															<label class="font-weight-bold mb-0">Talla:</label>
															<input class="form-control" name="subItem_talla" patron="requerido" value="<?= $si_v['talla'] ?>">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Genero:</label>
															<select class="form-control" name="subItem_genero">
																<option class="item" value="" <?= empty($si_v['idGenero']) ? 'selected' : ''; ?>>SELECCIONE</option>
																<option class="item" value="1" <?= $si_v['idGenero'] == '1' ? 'selected' : ''; ?>>VARON</option>
																<option class="item" value="2" <?= $si_v['idGenero'] == '2' ? 'selected' : ''; ?>>DAMA</option>
																<option class="item" value="3" <?= $si_v['idGenero'] == '3' ? 'selected' : ''; ?>>UNISEX</option>
															</select>
														</div>
														<div class=" col-md-3" style="display: flex;">
															<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
																<label class="font-weight-bold mb-0">Tela:</label>
																<input class="form-control" name="subItem_tela" value="<?= $si_v['tela'] ?>">
															</div>
															<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
																<label class="font-weight-bold mb-0">Color:</label>
																<input class="form-control" name="subItem_color" value="<?= $si_v['color'] ?>">
															</div>
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Cantidad:</label>
															<input class="form-control SbItCantidad keyUpChange" name="subItem_cantidad" value="<?= $si_v['cantidad'] ?>" patron="requerido" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Costo:</label>
															<input class="form-control SbItCosto keyUpChange" name="subItem_costo" patron="requerido" value="<?= $si_v['costo'] ?>" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Sb Tot:</label>
															<input class="form-control SbItSubTotal" name="subItem_st" patron="requerido" readonly onchange="Oc.calcularTextilPrecio(this);" value="<?= $si_v['cantidad'] * $si_v['costo'] ?>">
														</div>
														<div class="form-group col-md-1">
															<label class="font-weight-bold mb-0" style="color: white;">:</label>
															<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '10') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-12">
															<label class="font-weight-bold mb-0">Monto:</label>
															<input class="form-control" name="subItem_monto" patron="requerido" value="<?= $si_v['monto'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_costo" value="">
															<input type="hidden" name="subItem_cantidad" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
														</div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									<?php else : ?>
										<?php if (!empty($ocSubItem[$value['idOrdenCompraDetalle']])) : ?>
											<?php foreach ($ocSubItem[$value['idOrdenCompraDetalle']] as $si_k => $si_v) : ?>
												<?php if ($value['idTipo'] == '2') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Descripción Serv.:</label>
															<input class="form-control" name="subItem_nombre" patron="requerido" value="<?= $si_v['nombre'] ?>">
														</div>
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Cantidad:</label>
															<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="Oc.cantidadServicio(this);" onkeyup="Oc.cantidadServicio(this);" value="<?= $si_v['cantidad'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_costo" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '7') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Item Logistica:</label>
															<select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
																<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label', 'selected' => $si_v['idItemLogistica']]); ?>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Peso:</label>
															<input class="form-control cantidadSI" name="subItem_cantidad" patron="requerido" onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" value="<?= $si_v['cantidad'] ?>">
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Cantidad PDV:</label>
															<input class="form-control cantidadPDV" name="subItem_cantidadPdv" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $si_v['cantidadPDV'] ?>">
														</div>
														<div class="form-group col-md-6">
															<label class="font-weight-bold mb-0">Tipo Servicio:</label>
															<select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
																<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida'], 'selected' => $si_v['idTipoServicio']]); ?>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Unidad Medida:</label>
															<input class="form-control umSubItem" name="subItem_um" patron="requerido" readonly value="<?= $si_v['unidadMedida'] ?>">
															<input type="hidden" class="form-control idUmSubItem" name="subItem_idUm" patron="requerido" value="<?= $si_v['idUnidadMedida'] ?>">
														</div>
														<div class="form-group col-md-3">
															<label class="font-weight-bold mb-0">Costo:</label>
															<input class="form-control costoSubItem" name="subItem_costo" patron="requerido" readonly onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')" value="<?= $si_v['costo'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '9') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-1">
															<label class="font-weight-bold mb-0">Talla:</label>
															<input class="form-control" name="subItem_talla" patron="requerido" value="<?= $si_v['talla'] ?>">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Genero:</label>
															<select class="form-control" name="subItem_genero">
																<option class="item" value="" <?= empty($si_v['idGenero']) ? 'selected' : ''; ?>>SELECCIONE</option>
																<option class="item" value="1" <?= $si_v['idGenero'] == '1' ? 'selected' : ''; ?>>VARON</option>
																<option class="item" value="2" <?= $si_v['idGenero'] == '2' ? 'selected' : ''; ?>>DAMA</option>
																<option class="item" value="3" <?= $si_v['idGenero'] == '3' ? 'selected' : ''; ?>>UNISEX</option>
															</select>
														</div>
														<div class=" col-md-3" style="display: flex;">
															<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
																<label class="font-weight-bold mb-0">Tela:</label>
																<input class="form-control" name="subItem_tela" value="<?= $si_v['tela'] ?>">
															</div>
															<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
																<label class="font-weight-bold mb-0">Color:</label>
																<input class="form-control" name="subItem_color" patron="requerido" value="<?= $si_v['color'] ?>">
															</div>
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Cantidad:</label>
															<input class="form-control SbItCantidad keyUpChange" name="subItem_cantidad" value="<?= $si_v['cantidad'] ?>" patron="requerido" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Costo:</label>
															<input class="form-control SbItCosto keyUpChange" name="subItem_costo" patron="requerido" value="<?= $si_v['costo'] ?>" onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');">
														</div>
														<div class="form-group col-md-2">
															<label class="font-weight-bold mb-0">Sb Tot:</label>
															<input class="form-control SbItSubTotal" name="subItem_st" patron="requerido" readonly onchange="Oc.calcularTextilPrecio(this);" value="<?= $si_v['cantidad'] * $si_v['costo'] ?>">
														</div>
														<div class="form-group col-md-1">
															<label class="font-weight-bold mb-0" style="color: white;">:</label>
															<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
															<input type="hidden" name="subItem_monto" value="">
														</div>
													</div>
												<?php endif; ?>
												<?php if ($value['idTipo'] == '10') : ?>
													<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
														<div class="form-group col-md-12">
															<label class="font-weight-bold mb-0">Monto:</label>
															<input class="form-control" name="subItem_monto" patron="requerido" value="<?= $si_v['monto'] ?>">
														</div>
														<div class="d-none">
															<input type="hidden" name="subItem_tipoServ" value="">
															<input type="hidden" name="subItem_idUm" value="">
															<input type="hidden" name="subItem_itemLog" value="">
															<input type="hidden" name="subItem_nombre" value="">
															<input type="hidden" name="subItem_talla" value="">
															<input type="hidden" name="subItem_genero" value="">
															<input type="hidden" name="subItem_tela" value="">
															<input type="hidden" name="subItem_color" value="">
															<input type="hidden" name="subItem_costo" value="">
															<input type="hidden" name="subItem_cantidad" value="">
															<input type="hidden" name="subItem_cantidadPdv" value="">
														</div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class="form-row col-md-12 contentSemanticDiv divParaCarga">
									<?php if (!$deOper) : ?>
										<?php $count = isset($ocAdjunto[$value['idOrdenCompraDetalle']]) ? count($ocAdjunto[$value['idOrdenCompraDetalle']]) : 0; ?>
										<input class="adjuntoItemCantidad" type="hidden" name="adjuntoItemCantidad" value="<?= $count ?>">
										<?= htmlSemanticCargaDeArchivos([
											'classDivBase' => 'divParaCarga', 'maxFiles' => 1, 'archivosPermitidos' => 'image/*',
											'name' => 'adjuntoItem',
											'data' => $ocAdjunto[$value['idOrdenCompraDetalle']]
										], 2) ?>
									<?php else : ?>
										<input class="adjuntoItemCantidad" type="hidden" name="adjuntoItemCantidad" value="0">
									<?php endif; ?>
								</div>
								<div class="form-row">
									<div class="form-group" onclick="Oc.generarSubItem(this, this.value);">
										<?php if ($value['idTipo'] == '2' || $value['idTipo'] == '9' || $value['idTipo'] == '10') : ?>
											<?php $display = ''; ?>
										<?php else : ?>
											<?php $display = 'style="display:none;"'; ?>
										<?php endif; ?>
										<a class="form-control btn btn-info btnAdicionar" <?= $display ?>><i class="fa fa-plus"></i> Adicionar</a>
									</div>
									<div class="form-group" onclick="Oc.quitarItem(this, this.value);">
										<a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
									</div>
								</div>
							</div>
							<div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
								<div class="form-group">
									<label class="font-weight-bold mb-0">Cantidad:</label>
									<input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['cantidad_item'] ?>">
								</div>
								<div class="form-group d-none">
									<label class="font-weight-bold mb-0">CantidadSubItem:</label>
									<input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="<?= count($ocSubItem[isset($value['idOrdenCompraDetalle']) ? $value['idOrdenCompraDetalle'] : $value['idOperDetalle']]) ?>">
								</div>
								<div class="form-group">
									<label class="font-weight-bold mb-0">Costo:</label>
									<input class="form-control item_costo" name="costo" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['costo_item'] ?>">
								</div>
								<div class="form-row">
									<div class="form-group col-md-6 d-none">
										<label class="font-weight-bold mb-0">GAP:</label>
										<input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['gap_item'] ?>">
									</div>
									<div class="form-group col-md-12">
										<label class="font-weight-bold mb-0">Sub Total:</label>
										<input class="form-control item_precio" name="precio" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= round($value['csg_item'], 3); ?>">
									</div>
									<div class="form-group col-md-12 d-none">
										<label class="font-weight-bold mb-0">Sub Total real:</label>
										<input class="form-control item_precio_real" name="precio_real" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['csg_item'] ?>">
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<legend class="scheduler-border">Datos Consolidados</legend>
				<div class="form-row pt-3">
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">IGV:</label>
						<select name="igvPorcentaje" patron="requerido" class="form-control" id="valorIGV" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();">
							<option <?= $value['IGVPorcentaje'] == '0' ? 'selected' : ''; ?> value="100">No incluir IGV</option>
							<option <?= $value['IGVPorcentaje'] == '18' ? 'selected' : ''; ?> value="118">Incluir IGV</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total:</label>
						<input class="form-control" name="total" patron="requerido" id="total" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();" value="<?= round($value['total'], 3); ?>">
						<input type="hidden" class="form-control" name="total_real" patron="requerido" id="total_real" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();" value="<?= $value['total'] ?>">

					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold mb-0">Total:</label>
						<input class="form-control" name="totalIGV" patron="requerido" id="totalFinal" readOnly value="<?= round((isset($value['totalIGV']) ? $value['totalIGV'] : $value['total']), 3);  ?>">
						<input type="hidden" class="form-control" name="totalIGV_real" patron="requerido" id="totalFinal_real" readOnly value="<?= (isset($value['totalIGV']) ? $value['totalIGV'] : $value['total']) ?>">

					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>
<div class="d-none" id="divItemLogistica">
	<select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
		<?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label']); ?>
	</select>
</div>
<div class="d-none" id="divTipoServicio">
	<select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
		<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
	</select>
</div>
<script>
	setTimeout(function() {
		$('.my_select2').select2();
	}, 500);
</script>