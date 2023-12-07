<style>
	.img-lsck-capturas {
		height: 150px !important;
	}

	.btn-info-custom {
		cursor: pointer;
		display: inline-block;
		line-height: 1;
	}

	input[type="color"] {
		padding: initial !important;
	}

	.floating-container {
		height: 275px !important;
	}

	.plomo {
		color: #d2d2d2 !important;
	}
</style>
<div class="ui form attached fluid segment p-4">
	<form class="ui form" role="form" id="formRegistroCotizacion" method="post" autocomplete="off">
		<h4 class="ui dividing header">DATOS DE LA COTIZACIÓN</h4>
		<input type="hidden" id="gapEmpresas" value='<?= !empty($gapEmpresas) ? json_encode($gapEmpresas) : '' ?>'>
		<input type="hidden" name="costoDistribucion" id="costoDistribucion" value="<?= !empty($costoDistribucion) ? $costoDistribucion['costo'] : 0 ?>">
		<div class="fields">
			<div class="six wide field">
				<div class="ui sub header">Título</div>
				<input id="nombre" name="nombre" patron="requerido" placeholder="Título de la cotizacion">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Deadline compras</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Deadline compras" value="">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="deadline" placeholder="Deadline compras" value="">
			</div>
			<div class="four wide field">
				<div class="ui sub header">Fecha requerida</div>
				<div class="ui calendar date-semantic">
					<div class="ui input left icon">
						<i class="calendar icon"></i>
						<input type="text" placeholder="Fecha Requerida" value="">
					</div>
				</div>
				<input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de Requerimiento" value="">
			</div>
			<div class="two wide field">
				<div class="ui sub header">
					Validez <div class="ui btn-info-validez btn-info-custom text-primary"><i class="info circle icon"></i></div>
				</div>
				<input class="onlyNumbers" id="diasValidez" name="diasValidez" patron="requerido" placeholder="Días de validez">
			</div>
		</div>
		<div class="fields">
			<div class="five wide field">
				<div class="ui sub header">Solicitante</div>
				<select name="solicitante" class="ui fluid search clearable dropdown dropdownSingleAditions" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $solicitantes, 'class' => 'text-titlecase', 'selected' => !empty($cotizacion['idSolicitante']) ? $cotizacion['idSolicitante'] : '']); ?>
				</select>
			</div>
			<div class="five wide field">
				<div class="ui sub header">Cuenta</div>
				<select class="ui dropdown parentDependiente centro-visible" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm" >
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'simple' => true, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="six wide field">
				<div class="ui sub header">Centro de costo</div>
				<select class="ui dropdown clearable semantic-dropdown centro-ocultado" id="cuentaCentroCostoForm" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
		</div>
		<div class="fields">
			<div class="five wide field">
				<div class="ui sub header">Prioridad</div>
				<select class="ui dropdown semantic-dropdown" id="prioridadForm" name="prioridadForm" patron="requerido">
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $prioridadCotizacion, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="eleven wide field">
				<div class="ui sub header">Motivo</div>
				<input id="motivoForm" name="motivoForm" placeholder="Motivo" value="<?= !empty($cotizacion['motivo']) ? $cotizacion['motivo'] : '' ?>">
			</div>

		</div>
		<div class="fields">
			<div class="five d-none wide field">
				<div class="ui sub header">Presupuestos</div>
				<select id="ordenServicioSelect" name="ordenServicioSelect" class="ui ead-only dropdown clearable semantic-dropdown centro-ocultado"  >
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $ordenServicio, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
			<div class="three wide field">
				<div class="ui sub header">Tipo Servicio</div>
				<select class="ui dropdown semantic-dropdown" id="tipoServicioCotizacion" name="tipoServicioCotizacion" patron="requerido">
						var_dump($tipoServicioCotizacion);
					<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicioCotizacion, 'class' => 'text-titlecase']); ?>
				</select>
			</div>
		</div>

		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Comentario</div>
				<textarea name="comentarioForm" id="comentarioForm" cols="30" rows="6"><?= !empty($cotizacion['comentario']) ? $cotizacion['comentario'] : '' ?></textarea>
			</div>
			<div class="eight wide field anexos">
				<div class="ui sub header">Anexos</div>
				<div class="ui small images content-lsck-capturas">
					<div class="content-lsck-galeria content-lsck-files">
						<div class="ui small image text-center btn-add-file">
							<div class="ui dimmer">
								<div class="content">
									<div class="ui small primary button" onclick="$(this).parents('.anexos').find('.file-lsck-capturas-anexos').click();">
										Agregar
									</div>
								</div>
							</div>
							<img class="ui image" src="<?= IMG_WIREFRAME ?>">
						</div>
						<input type="file" name="capturas" class="file-lsck-capturas-anexos form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="image/*, .xlsx, .pdf, .xlsm" multiple="">
					</div>
				</div>
			</div>
		</div>
		<div class="fields">
			<div class="eight wide field">
				<div class="ui sub header">Ver precio PDF</div>
				<select class="ui simpleDropdown" name="flagMostrarPrecio" patron="requerido">
					<option value="1" selected>Ver Precio</option>
					<option value="0">Ocultar Precio</option>
				</select>
			</div>
		</div>
		<h4 class="ui dividing header">DETALLE DE LA COTIZACIÓN <div class="ui blue horizontal label link button btn-leyenda">Leyenda</div>
		</h4>
		<div class="default-item">
			<div class="ui segment body-item nuevo">
				<div class="ui right floated header">
					<div class="ui icon menu">
						<a class="item chk-itemTextoPdf" onclick="$(this).find('i').toggleClass('check square');$(this).find('i').toggleClass('square outline'); $(this).find('i').hasClass('check square') ? $(this).find('input').prop('checked', true) : $(this).find('input').prop('checked', false); $(this).find('i').hasClass('check square') ? $(this).closest('.body-item').find('.itemTextoPdf').removeClass('d-none') : $(this).closest('.body-item').find('.itemTextoPdf').addClass('d-none');">
							<i class="icon square outline"></i>
							<input type="checkbox" name="chkItemTextoPdf" class="checkItemTextoPdf d-none">
						</a>
						<a class="item btn-bloquear-detalle" onclick="$(this).find('i').toggleClass('unlock');$(this).find('i').toggleClass('lock')">
							<i class="unlock icon"></i>
						</a>
						<a class="item btn-eliminar-detalle btneliminarfila">
							<i class="trash icon"></i>
						</a>
					</div>
				</div>
				<div class="ui left floated header">
					<span class="ui medium text ">Detalle N. <span class="title-n-detalle">00001</span></span>
				</div>
				<div class="ui clearing divider"></div>
				<div class="ui grid">
					<div class="columna_itemss sixteen wide tablet twelve wide computer column itemDet_1">
						<div class="fields">
							<div class="eight wide field">
								<div class="ui sub header">Item</div>
								<div class="ui-widget">
									<div class="ui right action left icon input w-100">
										<i class="semaforoForm flag link icon"></i>
										<input class="items" type='text' name='nameItem' patron="requerido" placeholder="Buscar item">
										<div class="ui basic floating flagCuentaSelect dropdown button simpleDropdown read-only">
											<input type="hidden" class="flagCuentaForm" name="flagCuenta" value="0" patron="requerido">
											<div class="text">Cuenta</div>
											<i class="dropdown icon"></i>
											<div class="menu">
												<div class="item" data-value="1">De la cuenta</div>
												<div class="item" data-value="0">Externo</div>
											</div>
										</div>
									</div>
									<input class="codItems" type='hidden' name='idItemForm'>
									<input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
									<input class="idProveedor" type='hidden' name='idProveedorForm' value="">
									<input class="cotizacionInternaForm" type="hidden" name="cotizacionInternaForm" value="1">
								</div>
								<div class="ui-widget">
									<input class="itemTextoPdf d-none" type='text' name='itemTextoPdf' placeholder="Descripción de Item para Cotización">
								</div>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Tipo Item</div>
								<select class="ui dropdown simpleDropdown idTipoItem" id="tipoItemForm" name="tipoItemForm" patron="requerido" data-correlativo="1">
									<?= htmlSelectOptionArray2(['query' => $itemTipo, 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione']); ?>
								</select>
							</div>
							<div class="four wide field no-personal">
								<div class="ui sub header">Unidad Medida</div>
								<select class="ui fluid search clearable dropdown unidadMed" name="unidadMedida">
									<?= htmlSelectOptionArray2(['query' => $unidadMedida, 'id' => 'idUnidadMedida', 'value' => 'nombre', 'class' => 'text-titlecase ', 'simple' => true, 'title' => 'Seleccione']); ?>
								</select>
							</div>
						</div>
						<div class="fields">
							<div class="five wide field no-personal">
								<div class="ui sub header">Características para el cliente</div>
								<div class="ui labeled input w-100">
									<input class="caracteristicasCliente" type='text' id="caracteristicasItem" name='caracteristicasItem' placeholder="Características del item">
								</div>
							</div>
							<div class="six wide field cCompras no-personal">
								<div class="ui sub header">Características para compras</div>
								<input name="caracteristicasCompras" placeholder="Características">
							</div>
							<div class="five wide field no-personal">
								<div class="ui sub header">Características para proveedor</div>
								<input name="caracteristicasProveedor" placeholder="Características">
							</div>
						</div>
						<div class="fields cantPDV d-none">
							<div class="three wide field">
								<div class="ui sub header">Cant. PDV</div>
								<input class="cantidadPDV" name="cantidadPDV" onkeyup="$(this).closest('.nuevo').find('.cantidadForm').keyup()">
							</div>
							<div class="three wide field">
								<div class="ui sub header">¿ Requiere OC ?</div>
								<select class="ui basic floating dropdown button simpleDropdown" name="flagGenerarOC">
									<option value="0" selected>NO generar</option>
									<option value="1">Generar OC</option>
								</select>
							</div>
							<div class="three wide field">
								<div class="ui sub header">Tabla Detalle</div>
								<select class="ui basic floating dropdown button simpleDropdown" name="flagMostrarDetalle">
									<option value="0" selected>Ocultar</option>
									<option value="1">Mostrar</option>
								</select>
							</div>
							<div class="four wide field">
								<div class="ui sub header">Almacén</div>
								<select class="ui basic floating dropdown button simpleDropdown flagOtrosPuntos" name="flagOtrosPuntos">
									<option value="0" selected>Almacén Visual</option>
									<option value="1">Otros Puntos</option>
								</select>
							</div>
						</div>
						<div class="fields cantPDV d-none">
							<div class="three wide field">
								<div class="ui sub header">Costo Packing</div>
								<select name="flagPackingSolicitado" onchange="$(this).closest('.body-item').find('.divCostoPacking').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
									<option value="0" selected>No requerido</option>
									<option value="1">Requerido</option>
								</select>
							</div>
							<div class="two wide field divCostoPacking d-none">
								<div class="ui sub header">.</div>
								<input class="onlyNumbers costoPacking keyUpChange" name="costoPacking" value="0">
							</div>
							<div class="three wide field">
								<div class="ui sub header">Costo Movilidad</div>
								<select name="flagMovilidadSolicitado" onchange="$(this).closest('.body-item').find('.divCostoMovilidad').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
									<option value="0" selected>No requerido</option>
									<option value="1">Requerido</option>
								</select>
							</div>
							<div class="two wide field divCostoMovilidad d-none">
								<div class="ui sub header">.</div>
								<input class="onlyNumbers costoMovilidad keyUpChange" name="costoMovilidad" value="0">
							</div>
							<div class="three wide field">
								<div class="ui sub header">Costo Personal</div>
								<select name="flagPersonalSolicitado" onchange="$(this).closest('.body-item').find('.divCostoPersonal').toggleClass('d-none');" class="ui basic floating dropdown button simpleDropdown">
									<option value="0" selected>No requerido</option>
									<option value="1">Requerido</option>
								</select>
							</div>
							<div class="three wide field divCostoPersonal d-none">
								<div class="ui sub header">.</div>
								<input class="onlyNumbers costoPersonal keyUpChange" name="costoPersonal" value="0">
							</div>
						</div>
						<div class="baseUbigeoSelects cantidadPDVDetallado d-none">
							<div class="fields" style="width:100%;">
								<div class="four wide field">
									<div class="ui sub header">Departamento</div>
									<select class="ui departamentoPDV" name="departamentoPDV" onchange="Cotizacion.cargarProvincia(this);">
										<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'id' => 'cod_departamento', 'value' => 'departamento', 'query' => $departamento, 'class' => 'text-titlecase']); ?>
									</select>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Provincia</div>
									<select class="ui provinciaPDV" name="provinciaPDV" onchange="Cotizacion.cargarDistrito(this);">
										<option>Seleccionar</option>
									</select>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Distrito</div>
									<select class="ui distritoPDV" name="distritoPDV">
										<option>Seleccionar</option>
									</select>
								</div>
								<div class="four wide field">
									<div class="ui sub header">Paradas</div>
									<input type="text" class="cantidadParadas" name="paradasPDV" value="0" onchange="Cotizacion.calcularParadas(this)">
								</div>
							</div>
						</div>
						<!-- Textiles -->
						<div class="ui form attached fluid segment my-3 d-none div-features div-feature-<?= COD_TEXTILES['id'] ?>">
							<h4 class="ui dividing header">SUB ITEMs</h4>
							<div class="content-body-sub-item">
								<div class="fields body-sub-item ">
									<div class="three wide field">
										<div class="ui sub header">Talla</div>
										<input class="tallaSubItem camposTextil" name="tallaSubItem[0]" placeholder="Talla" value="<?= !empty($data['talla']) ? $data['talla'] : '' ?>">
									</div>
									<div class="three wide field">
										<div class="ui sub header">Tela</div>
										<input class="telaSubItem camposTextil" name="telaSubItem[0]" placeholder="Tela" value="<?= !empty($data['tela']) ? $data['tela'] : '' ?>">
									</div>
									<div class="three wide field">
										<div class="ui sub header">Color</div>
										<input class="colorSubItem " name="colorSubItem[0]" placeholder="Color" value="<?= !empty($data['color']) ? $data['color'] : '' ?>">
									</div>
									<div class="three wide field">
										<div class="ui sub header">Cantidad</div>
										<input class="onlyNumbers cantidadSubItemAcumulativo cantidadSubItemTextil" name="cantidadTextil[0]" placeholder="Cantidad" value="<?= !empty($data['cantidadSubItem']) ? $data['cantidadSubItem'] : '' ?>">
									</div>
									<div class="three wide field">
										<div class="ui sub header">Genero</div>
										<select class="ui dropdown generoSubItem" name="generoSubItem[0]">
											<option class="item-4" value="">Seleccione</option>
											<option class="item" value="1">VARON</option>
											<option class="item" value="2">DAMA</option>
											<option class="item" value="3">UNISEX</option>
										</select>
									</div>
									<div class="one wide field">
										<div class="ui sub header">Eliminar</div>
										<button type="button" class="ui button btn-eliminar-sub-item red">
											<i class="trash icon"></i>
										</button>
									</div>
								</div>
							</div>
							<button type="button" class="ui btn btn-trade-visual btn-add-sub-item">
								<i class="plus icon"></i>
								Agregar Sub Item
							</button>
						</div>
						<!-- Tarjetas y Vales -->
						<div class="fields d-none div-features div-feature-<?= COD_TARJETAS_VALES['id'] ?>">
							<div class="six wide field">
								<div class="ui sub header">Razón Social</div>
								<input class="razonSocialSubItemTarjVal" name="razonSocialSubItemTarjVal[0]" placeholder="Razón Social">
							</div>
							<div class="five wide field">
								<div class="ui sub header">Sucursal</div>
								<input class="sucursalSubItemTarjVal" name="sucursalSubItemTarjVal[0]" placeholder="Sucursal">
							</div>
							<div class="five wide field">
								<div class="ui sub header">Monto S/</div>
								<input class="montoSubItemTarjVal" name="montoSubItemTarjVal[0]" placeholder="Monto">
							</div>
						</div>
						<!-- Personal -->
						<div class="d-none div-features div-feature-<?= COD_PERSONAL['id'] ?> personal_detalle personal_1" style="border: 1px solid;padding: 15px;">
							<table style="width: 100%;">
								<tr>
									<td style="width:18%;">
										<div style="padding:15px;">
											<div class="ui sub header">Cantidad</div>
											<input name="cantidad_personal" class="cantidad_personal" id="cantidad_personal" data-cantidad="1" value="0">
										</div>
									</td>
									<td style="width:18%;">
										<div style="padding:15px;">
											<div class="ui sub header">Cargo</div>
											<div class="cargo_rrhh" style="width:100%;">
												<select class="ui clearable dropdown simpleDropdown change_data cargo_personal" id="cargo_personal" name="cargo_personal" data-obligatorio="1"></select>
											</div>
										</div>
									</td>
									<td style="width:18%;">
										<div style="padding:15px;">
											<div class="ui sub header">Tipo de Contrato</div>
											<select class="tipo_contrato_personal" name="tipo_contrato_personal" data-obligatorio="1">
												<option value="0">Seleccione</option>
												<option value="1">Part Time</option>
												<option value="2">Full Time</option>
											</select>
										</div>
									</td>
									<td style="width:18%;">
										<div style="padding:15px;">
											<div class="ui sub header">Tipo Jornada</div>
											<select class="tipo_jornada_personal" name="tipo_jornada_personal">
												<option value="0">Seleccione</option>
												<option value="1">Amanecida</option>
												<option value="2">Horario Regular</option>
											</select>
										</div>
									</td>
									<td style="width:18%;"></td>
									<td style="width:10%;"></td>
								</tr>
								<tr>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Periodo de Contrato</div>
											<select class="periodo_contrato_personal" id="periodo_contrato_personal" name="periodo_contrato_personal" data-obligatorio="1">
												<option value="0">Seleccione</option>
												<option value="1">Diario</option>
												<option value="2">Mensual</option>
											</select>
										</div>
									</td>
									<td>
										<div style="padding:15px;display:none;" class="cantidad_dias">
											<div class="ui sub header">Cantidad de días</div>
											<input value="0" data-dias="1" class="cantidad_dias_personal" id="cantidad_dias_personal" name="cantidad_dias_personal">
										</div>
									</td>
									<td>
										<div style="padding:15px;display:none;" class="pago_diario">
											<div class="ui sub header">Pago diario</div>
											<input value="0" data-pago="1" class="pago_diario_personal" id="pago_diario_personal" name="pago_diario_personal">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Pago mensual</div>
											<input value="0" class="pago_mensual_personal" id="pago_mensual_personal" name="pago_mensual_personal" readonly>
										</div>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="6">
										<div style="padding:15px;display:none;" class="periodo">
											<div class="ui sub header">Periodo</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div style="padding:15px;display:none;" class="mes_inicio">
											<div class="ui sub header">Mes Inicio</div>
											<select class="mes_inicio_personal" id="mes_inicio_personal" name="mes_inicio_personal" data-obligatorio="1">
												<?php foreach ($periodo as $row_p) : ?>
													<option value="<?= $row_p['periodo'] ?>"><?= $row_p['periodo'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</td>
									<td>
										<div style="padding:15px;display:none;" class="mes_fin">
											<div class="ui sub header">Mes Fin</div>
											<select class="mes_fin_personal" id="mes_fin_personal" name="mes_fin_personal" data-obligatorio="1">
												<?php foreach ($periodo as $row_p) : ?>
													<option value="<?= $row_p['periodo'] ?>"><?= $row_p['periodo'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="6">
										<div style="padding:15px;">
											<div class="ui sub header">Estructura Salarial</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Sueldo</div>
											<input class="sueldo_personal" name="sueldo_personal" id="sueldo_personal" data-sueldo="1" value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Asignacion Familiar</div>
											<input class="asignacion_familiar_personal" name="asignacion_familiar_personal" id="asignacion_familiar_personal" value="0" readonly>
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Movilidad</div>
											<input class="movilidad_personal keyUpChange onlyNumbers" onchange="$(this).closest('tr').find('.sueldo_personal').change();" name="movilidad_personal" id="movilidad_personal" data-sueldo="1" value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Refrigerio</div>
											<input class="refrigerio_personal keyUpChange onlyNumbers" onchange="$(this).closest('tr').find('.sueldo_personal').change();" name="refrigerio_personal" id="refrigerio_personal" data-sueldo="1" value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Incentivo</div>
											<input class="incentivo_personal keyUpChange onlyNumbers" onchange="$(this).closest('tr').find('.sueldo_personal').change();" name="incentivo_personal" id="incentivo_personal" value="0" data-sueldo="1">
										</div>
									</td>
									<td>
										<div style="padding:0px;">
											<div class="ui sub header">Total</div>
											<input class="total1Personal keyUpChange onlyNumbers" value="0">
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="6">
										<div style="padding:15px;">
											<div class="ui sub header">Cargas Sociales</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Essalud 9% </div>
											<input value="" class="essalud_personal" name="essalud_personal" id="essalud_personal" readonly value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">CTS 9.7%</div>
											<input value="" class="cts_personal" name="cts_personal" id="cts_personal" readonly value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Vacaciones 9.1%</div>
											<input value="" class="vacaciones_personal" name="vacaciones_personal" id="vacaciones_personal" readonly value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Gratificacion 18.20%</div>
											<input value="" class="gratificacion_personal" name="gratificacion_personal" id="gratificacion_personal" readonly value="0">
										</div>
									</td>
									<td>
										<div style="padding:15px;">
											<div class="ui sub header">Seguro Vida Ley 0.26%</div>
											<input value="" class="seguro_vida_personal" name="seguro_vida_personal" id="seguro_vida_personal" readonly value="0">
										</div>
									</td>
									<td>
										<div style="padding:0px;">
											<div class="ui sub header">Total</div>
											<input class="total2Personal keyUpChange onlyNumbers" value="0">
										</div>
									</td>
								</tr>
							</table>
							<input class="total_sueldo" type="hidden" value="0">
							<input class="total_adicionales" type="hidden" value="0" name="total_adicionales">
							<div class="campos_adicionales">
							</div>
						</div>
						<!-- Transporte -->
						<div class="ui form attached fluid segment my-3 d-none div-features div-feature-<?= COD_TRANSPORTE['id'] ?>" data-tipo="<?= COD_TRANSPORTE['id'] ?>">
							<h4 class="ui dividing header">SUB ITEMS</h4>
							<div class="content-body-sub-item">
								<div class="body-sub-item body-sub-item-servicio">
									<div class="fields">
										<div class="four wide field">
											<div class="ui sub header">Departamento</div>
											<select class="ui simpleDropdown depT formTransporte departamento_transporte" name="departamentoTransporte[0]" onchange="Cotizacion.buscarProvincias(this);">
												<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'id' => 'cod_departamento', 'value' => 'departamento', 'query' => $departamento, 'class' => 'text-titlecase']); ?>
											</select>
										</div>
										<div class="four wide field">
											<div class="ui sub header">Provincia</div>
											<select class="ui simpleDropdown provT formTransporte provincia_transporte" name="provinciaTransporte[0]" onchange="Cotizacion.buscarDistritos(this);">
												<option value>Seleccione</option>
											</select>
										</div>
										<div class="four wide field">
											<div class="ui sub header">Distrito</div>
											<select class="ui simpleDropdown disT formTransporte distrito_transporte" name="distritoTransporte[0]" onchange="Cotizacion.buscarTipoTransporte(this);">
												<option value>Seleccione</option>
											</select>
										</div>
										<div class="four wide field">
											<div class="ui sub header">Tipo</div>
											<select class="ui simpleDropdown tipoT formTransporte tipoTransporte_transporte" name="tipoTransporte[0]" onchange="Cotizacion.buscarCosto(this);">
												<option value>Seleccione</option>
											</select>
										</div>
									</div>
									<div class="fields">
										<div class="three wide field">
											<div class="ui sub header">Csto Visual</div>
											<input class="inpCostoVisual formTransporte costoVisual_transporte onlyNumbers" name="costoVisualTransporte[0]" placeholder="0" value="" readonly>
										</div>
										<div class="two wide field">
											<div class="ui sub header">% Adic.</div>
											<div class="ui right labeled input">
												<input class="inpPorcTransporte keyUpChange formTransporte onlyNumbers" name="porcAdicionalTransporte[0]" placeholder="0" value="0" onchange="Cotizacion.calcularValorTransporte(this);">
												<div class="ui basic label">
													%
												</div>
											</div>
										</div>
										<div class="three wide field">
											<div class="ui sub header">Csto Cliente</div>
											<input class="inpCosto formTransporte costoCliente_transporte onlyNumbers" name="costoClienteTransporte[0]" placeholder="0" value="" onchange="Cotizacion.calcularValorTransporte(this);" readonly>
										</div>
										<div class="two wide field">
											<div class="ui sub header">Días</div>
											<input class="formTransporte dias_transporte keyUpChange onlyNumbers" name="diasTransporte[0]" placeholder="0" value="" onchange="Cotizacion.calcularValorTransporte(this);">
										</div>
										<div class="two wide field">
											<div class="ui sub header">Moviles</div>
											<input class="formTransporte cantidad_transporte keyUpChange onlyNumbers" name="cantidadTransporte[0]" placeholder="0" value="" onchange="Cotizacion.calcularValorTransporte(this);">
										</div>
										<div class="two wide field">
											<div class="ui sub header">Eliminar</div>
											<button type="button" class="ui button btn-eliminar-sub-item red">
												<i class="trash icon"></i>
											</button>
										</div>
									</div>
									<div class="ui divider"></div>
								</div>
							</div>
							<button type="button" class="ui button btn-add-sub-item teal">
								<i class="plus icon"></i>
								Agregar
							</button>
						</div>
						<!-- Distribucion -->
						<div class="d-none div-features div-feature-<?= COD_DISTRIBUCION['id'] ?>" data-tipo="<?= COD_DISTRIBUCION['id'] ?>">
							<div class="d-none fields divAddParaOC">
								<div class="eight wide field">
									<div class="ui sub header">Proveedor</div>
									<select class="ui clearable dropdown simpleDropdown proveedorDistribucionSubItem" onchange="$(this).closest('.body-item').find('.idProveedor').val($(this).val())">
										<?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedorDistribucion, 'id' => 'idProveedor', 'value' => 'razonSocial', 'class' => 'text-titlecase' /*, 'data-option' => ['columnaAdicionalSegunLoRequerido']*/]); ?>
									</select>
								</div>
							</div>
							<div class="content-body-sub-item" id="divIL">
								<div class="fields body-sub-item"></div>
							</div>
							<button type="button" class="ui button btn-add-subItemDist-Masivo mb-4 blue">
								<i class="box open icon"></i> Indicar Items
							</button>
							<button type="button" class="ui button btn-add-subDetalleDistribucion mb-4 teal">
								<i class="hand holding usd icon"></i> Detalle Cantidad y Costos
							</button>
							<div class="datosTable"></div>
							<div class="arrayDatosItems d-none"></div>
							<div class="arrayDatos d-none"></div>
							<div class="tbDistribucionTachado d-none">
								<h4 class="ui dividing header">TACHADO</h4>
								<!-- El input de tipo radio al ser duplicado se pierde el valor original de la funcion serializeArray de js -->
								<input value='0' class='chkTachadoDistribucion d-none' name="chkTachado[0]">
								<table class="ui single line table">
									<thead>
										<tr>
											<th></th>
											<th class="thCustomNameItem"></th>
											<th>Tiempo tachado (días)</th>
											<th>Personas para tachado</th>
											<th>Costo por día</th>
											<th>Total de costo</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
						<div class="fields pt-5">
							<div class="four wide field">
								<div class="ui sub header">Archivos <div class="ui btn-info-custom text-primary btn-info-archivo"><i class="info circle icon"></i></div>
								</div>
								<div class="ui small image btn-add-file text-center">
									<div class="ui dimmer">
										<div class="content">
											<div class="ui small primary button" onclick="$(this).parents('.nuevo').find('.file-lsck-capturas').click();">
												Agregar
											</div>
										</div>
									</div>
									<img class="ui image" src="<?= IMG_WIREFRAME ?>">
								</div>
							</div>
							<div class="twelve wide field">
								<div class="ui sub header">Links</div>
								<div class="ui left corner labeled input">
									<div class="ui left corner label">
										<i class="linkify icon"></i>
									</div>
									<textarea name="linkForm" placeholder="Ingrese los enlaces aquí " rows="6" class="w-100"></textarea>
								</div>
							</div>
						</div>
						<div class="content-lsck-capturas">
							<input type="file" name="capturas" class="file-lsck-capturas form-control input-sm d-none" placeholder="Cargar Imagen" data-row="0" accept="<?= ARCHIVOS_PERMITIDOS ?>" multiple="">
							<div class="fields ">
								<div class="sixteen wide field">
									<div class="ui small images content-lsck-galeria">

									</div>
								</div>
							</div>
							<div class="fields ">
								<div class="sixteen wide field">
									<div class="ui small images content-lsck-files">

									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="sixteen wide tablet four wide computer column">
						<div class="fields ">
							<div class="sixteen wide field">
								<div class="ui sub header">
									Cantidad de Elementos <div class="ui btn-info-custom text-primary btn-info-cantidad"><i class="info circle icon"></i></div>
									<div class="ui btn-info-custom text-primary btn-info-descripcion"><i class="info circle icon"></i></div>
								</div>
								<div class="ui-widget">
									<div class="ui right action input w-100">
										<input class="cantidadForm onlyNumbers" data-min="1" type="number" name="cantidadForm" placeholder="0" patron="requerido,numerico" min="1" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
									</div>
								</div>
							</div>
						</div>
						<div class="fields">
							<div class="sixteen wide field">
								<div class="ui sub header">Costo</div>
								<div class="ui right action right labeled input">
									<label for="amount" class="ui label">S/</label>
									<input class="costoForm" type="text" name="costoForm" placeholder="0.00" readonly>
								</div>
							</div>
						</div>
						<div class="fields">
							<div class="eight wide field">
								<div class="ui sub header">
									GAP <div class="ui btn-info-custom text-primary btn-info-gap"><i class="info circle icon"></i></div>
								</div>
								<div class="ui right labeled input">
									<input onkeypress="$(this).closest('.nuevo').find('.costoForm').val() == 0 ? $(this).attr('readonly','readonly') : $(this).removeAttr('readonly') " data-min='0' id="gapForm" class="onlyNumbers gapForm gapFormOperaciones" name="gapForm" placeholder="Gap" value="<?= GAP ?>">
									<div class="ui basic label">
										%
									</div>
								</div>
							</div>
							<div class="eight wide field">
								<div class="ui sub header">Precio</div>
								<div class="ui right labeled input">
									<label for="amount" class="ui label">S/</label>
									<input class=" precioFormLabel" type="text" placeholder="0.00" readonly>
									<input class=" precioForm" type="hidden" name="precioForm" placeholder="0.00" readonly>
								</div>
							</div>
						</div>
						<div class="fields">
							<div class="sixteen wide field">
								<div class="ui sub header">Subtotal</div>
								<div class="ui right labeled input">
									<label for="amount" class="ui label teal">S/</label>
									<input class="subtotalFormLabel" type="text" placeholder="0.00" readonly>
									<input class="subtotalForm" type="hidden" name="subtotalForm" placeholder="0.00" readonly>
									<input type="hidden" class="costoRedondeadoForm" name="costoRedondeadoForm" placeholder="0" value="0">
									<input type="hidden" class="costoNoRedondeadoForm" name="costoNoRedondeadoForm" placeholder="0" value="0">
									<div class="ui basic floating dropdown button simpleDropdown">
										<input type="hidden" class="flagRedondearForm" name="flagRedondearForm" value="0" patron="requerido">
										<div class="text">Redondear</div>
										<i class="dropdown icon"></i>
										<div class="menu">
											<div class="item" data-value="1">Redondear arriba</div>
											<div class="item" data-value="0">No redondear</div>

										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="fields fieldPersonal d-none">
							<div class="eight wide field">
								<div class="ui sub header">FEE SUELDO</div>
								<div class="ui right labeled input">
									<input data-min='0' class="onlyNumbers fee1Form keyUpChange" name="fee1" value="0" onchange="$(this).closest('.body-item').find('.cantidadForm').keyup();">
									<div class="ui basic label">%</div>
								</div>
							</div>
							<div class="eight wide field">
								<div class="ui sub header">TOTAL FEE SUELDO</div>
								<div class="ui right labeled input">
									<input class="onlyNumbers fee1FormTotal" name="fee1Item" value="0" readonly>
								</div>
							</div>
						</div>
						<div class="fields fieldPersonal d-none">
							<div class="eight wide field">
								<div class="ui sub header">FEE OPERACION</div>
								<div class="ui right labeled input">
									<input data-min='0' class="onlyNumbers fee2Form keyUpChange" name="fee2" value="0" onchange="$(this).closest('.body-item').find('.tipoItemForm').keyup();">
									<div class="ui basic label">%</div>
								</div>
							</div>
							<div class="eight wide field">
								<div class="ui sub header">TOTAL FEE OPERACION</div>
								<div class="ui right labeled input">
									<input data-min='0' class="onlyNumbers fee2FormTotal" name="fee2Item" value="0">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ui black three column center aligned stackable divided grid segment">
			<div class="column">
				<div class="ui test toggle checkbox">
					<input class="igvForm" name="igv" type="checkbox" onchange="Cotizacion.actualizarTotal();">
					<label>Incluir IGV</label>
				</div>
			</div>
			<div class="column">
				<div class="ui right labeled input">
					<label for="feeForm" class="ui label">Fee: </label>
					<input data-min='0' type="number" id="feeForm" class="feeForm onlyNumbers" name="feeForm" placeholder="Fee" onkeyup="Cotizacion.actualizarTotal();">
					<div class="ui basic label">
						%
					</div>
				</div>
			</div>
			<div class="column">
				<div class="ui right labeled input">
					<label for="totalForm" class="ui label green">Total: </label>
					<input class=" totalFormLabel" type="text" placeholder="0.00" readonly="">
					<input class=" totalFormFeeIgv" type="hidden" name="totalFormFeeIgv" placeholder="0.00" readonly="">
					<input class=" totalFormFee" type="hidden" name="totalFormFee" placeholder="0.00" readonly="">
					<input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" readonly="">
				</div>
			</div>
		</div>
	</form>
</div>
<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
<div class="floating-container">
	<div class="floating-button">
		<i class="cog icon"></i>
	</div>
	<div class="element-container">
		<a href="javascript:;">
			<span class="float-element tooltip-left btn-send" data-message="Enviar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(2)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });'>
				<i class="send icon"></i>
			</span>
			<span class="float-element btn-save" data-message="Guardar" onclick='Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(1)", content: "¿Esta seguro de guardar esta cotizacion?" });'>
				<i class="save icon"></i>
			</span>
			<span class="float-element btn-add-detalle btn-add-row" onclick="" data-message="Agregar detalle">
				<i class="plus icon"></i>
			</span>
		</a>
	</div>
</div>

<!-- Popup Leyenda -->
<div class="ui leyenda popup top left transition hidden">
	<div class="ui sub header">Semáforo tarifario</div>
	<div class="ui list">
		<div class="item">
			<i class="flag icon teal"></i>
			<div class="content">
				+ 2 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon yellow"></i>
			<div class="content">
				1 a 2 días
			</div>
		</div>
		<div class="item">
			<i class="flag icon red"></i>
			<div class="content">
				Tarifario expiró.
			</div>
		</div>
	</div>
	<div class="ui clearing divider"></div>
	<div class="ui sub header">Otros</div>
	<div class="ui list">
		<div class="item">
			<i class="square icon teal"></i>
			<div class="content">
				Subtotal
			</div>
		</div>
		<div class="item">
			<i class="square icon green"></i>
			<div class="content">
				Total
			</div>
		</div>
	</div>
</div>
<div class="ui red fixed nag" id="nagGapValidacion">
	<div class="title">SI EL SUBTOTAL ES MAYOR A <?= MONTOGAP ?> EL GAP NO PUEDE SER MENOR A <?= GAP ?>% </div>
	<i class="close icon"></i>
</div>
<!-- Items -->
<input id="itemsServicio" type="hidden" value='<?= json_encode($itemServicio) ?>'>
<input id="tachadoDistribucion" type="hidden" value='<?= json_encode($tachadoDistribucion) ?>'>
<input id="solicitantes" type="hidden" value='<?= json_encode($solicitantes) ?>'>
<input id="ordenServicioDatos" type="hidden" value='<?= json_encode($ordenServicio) ?>'>