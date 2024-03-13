var RequerimientoInterno = {
	url: 'SolicitudRequerimientoInterno/',
	frm: 'frmRequerimientoInterno',
	contentDetalle: 'idContentRequerimientoInterno',
	htmlG: '',
	nDetalle: 1,
	divItemData: '',
	modalIdForm: 0,
	objetoParaAgregarImagen: null,
	detalleEliminado: [],
	itemServicio: [],
	anexoEliminado: [],
	itemTarifario: [],
	load: function () {
		$(document).ready(function () {
			$('#btn-filtrarRequerimientoInterno').click();

			Fn.loadSemanticFunctions();
			Fn.loadDimmerHover();
			RequerimientoInterno.actualizarOnAddRow();
			$('.simpleDropdown').dropdown();
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
		});
		$(document).on("click", "#btn-filtrarRequerimientoInterno", () => {
			var ruta = 'reporte';
			var config = {
				'idFrm': RequerimientoInterno.frm
				, 'url': RequerimientoInterno.url + ruta
				, 'contentDetalle': RequerimientoInterno.contentDetalle
			};

			Fn.loadReporte_new(config);
		});
		$(document).on("click", ".btn-viewSolicitudRequerimientoInterno", function (e) {
			++modalId;
			let id = $(this).parents('tr:first').data('id');
			let data = { 'idRequerimientoInterno': id };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': RequerimientoInterno.url + 'formularioAprobacionRequerimientoInterno', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				let style = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });RequerimientoInterno.nDetalle=1;';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroRequerimientoInterno", fn: "RequerimientoInterno.actualizarRequerimientoInterno()", content: "¿Esta seguro de registrar el requerimiento?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.data.title, frm: a.data.html, btn: btn, width: '100%' });

				RequerimientoInterno.itemServicio = $.parseJSON($('#itemsServicio').val());
				RequerimientoInterno.modalIdForm = modalId;
				RequerimientoInterno.htmlG = $('.default-item').html();
				RequerimientoInterno.actualizarAutocomplete();
				RequerimientoInterno.actualizarOnAddRow();
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
				$('.simpleDropdown').dropdown();
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				RequerimientoInterno.itemTarifario = a.data.itemTarifario;
			});
		});
		$(document).on('click', '.btn-detalleRequerimientoInterno', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idRequerimientoInterno': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': RequerimientoInterno.url + 'formularioVisualizacionRequerimientoInterno', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				RequerimientoInterno.actualizarAutocomplete();
			});
		});
		$(document).on('click', '.btn-viewGenerarOC', function () {
			++modalId;
			let id = $(this).parents('tr:first').data('id');
			let data = { 'idRequerimientoInterno': id };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': RequerimientoInterno.url + 'formularioSeleccionProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formSeleccionProveedor", fn: "RequerimientoInterno.seleccionProveedor();", content: "Solo se tomara en cuenta los articulos del proveedor seleccionado" });';
				btn[1] = { title: 'Continuar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.data.title, frm: a.data.html, btn: btn, width: '40%' });
			});
		});
		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			let body = $(this).parents('.body-item');
			let div_locked = body.find('.btn-bloquear-detalle');

			let cantItems = $('.body-item').length;

			if (div_locked.find('i').hasClass('lock')) {
				$(this).parents('.body-item').find('.btn-bloquear-detalle').transition('shake');
				return false;
			}
			if (cantItems > 1) {
				body.transition({
					animation: 'slide left',
					duration: '0.4s',
					onComplete: function () {
						body.remove();

						$.each($('.body-item'), function (i, v) {
							RequerimientoInterno.actualizarOnAddRowCampos($(v));
							RequerimientoInterno.actualizarTotal();
						});
					}
				});
			}
			if (cantItems <= 1) {
				$(".btn-add-row").click();
			}
			RequerimientoInterno.actualizarTotal();
		});
		$(document).on("click", ".btnLogout", () => {

			let jsonString = {};
			let url = "RequerimientoInterno/logout";
			let config = { url: url, data: jsonString };

			$.when(Fn.ajax(config)).then(function (b) {
				++modalId;
				var btn = [];
				let fn = 'Fn.showModal({ id: ' + modalId + ',show:false});Fn.goToUrl(`' + b.data.url + '`);';
				btn[0] = { title: 'Aceptar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
			});
		});
		$(document).off('click', '.option-semantic-delete').on('click', '.option-semantic-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			let content = control.closest('.contentSemanticDiv');
			let parent = $(this).closest(".content-lsck-capturas");
			control.parents('.content-lsck-capturas:first').remove();

			// Inicio: Para mantener el conteo correcto despues de eliminar.
			// Si → tiene que estar al final.
			var data = content.find('.file-semantic-upload').data();
			let prefi_name = data.name;
			let name = prefi_name + 'File-item';
			var id = '';
			if (data.id) id = '[' + data.id + ']';
			var total = content.find('.file-semantic-upload').closest('.content-upload').find('input[name="' + name + id + '"]').length;
			total += control.closest('.content-upload').parent('div').find('input.file-considerarAdjunto').length;
			content.find('.' + prefi_name + 'Cantidad').val(total);
			// Fin
			content.find('.file-semantic-upload').change();
		});
		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			let parent = $(this).closest(".content-lsck-capturas");
			let idEliminado = parent.data('id');
			if (idEliminado) {
				RequerimientoInterno.archivoEliminado.push(idEliminado);
			}

			control.parents('.content-lsck-capturas:first').remove();
		});
		$(document).on('keyup', '.cantidadForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let tipoItem = thisControlParents.find('#tipoItemForm');
			let costoForm = thisControlParents.find('.costoForm');
			let gapForm = thisControlParents.find('.gapForm');
			let flagCuentaForm = thisControlParents.find('.flagCuentaForm');
			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');

			let cantidad = Number(thisControl.val());
			let costo = Number(costoForm.val());
			let subTotalSinGap = Fn.multiply(cantidad, costo);

			if ((gapForm.val() == '' || parseFloat(gapForm.val()) == 0) && subTotalSinGap >= GAP_MONTO_MINIMO && gapForm.val() < GAP_MINIMO && flagCuentaForm.val() == 0 && tipoItem.val() != COD_DISTRIBUCION.id && tipoItem.val() != COD_PERSONAL.id && tipoItem.val() != COD_TRANSPORTE.id) {
				gapForm.val(GAP_MINIMO);
			}

			gapForm.keyup();
			let precio = Number(costoForm.val());
			let subTotal = Fn.multiply(cantidad, precio);
			////////////
			let flagRedondearForm = thisControlParents.find('.flagRedondearForm');
			let enteroSuperior = Math.ceil(subTotal);
			let flagRedondear = flagRedondearForm.val();

			if (flagRedondear == 1) subTotal = enteroSuperior;
			////////////
			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));
			RequerimientoInterno.cantidadTotal();

			// PARA EL FEE DE PERSONAL
			let fee1 = thisControlParents.find('.fee1Form').val();
			let fee2 = thisControlParents.find('.fee2Form').val();
			let fee1Result = thisControlParents.find('.fee1FormTotal');
			let fee2Result = thisControlParents.find('.fee2FormTotal');

			totalParaElFee2 = thisControlParents.closest('.body-item').find('.total_adicionales').val();
			// Se le quita el total_adicional a personal para calcular el fee1 correctamente;
			if (tipoItem.val() == COD_PERSONAL.id) subTotal = subTotal - parseFloat(thisControl.closest('.body-item').find('.total_adicionales').val());
			fee1Result.val((subTotal * fee1 / 100).toFixed(4));
			fee2Result.val((totalParaElFee2 * fee2 / 100).toFixed(4));
		});
		$(document).on('keyup', '.items', function () {
			RequerimientoInterno.itemServicio = $.parseJSON($('#itemsServicio').val());
			RequerimientoInterno.actualizarAutocomplete();
			RequerimientoInterno.actualizarOnAddRow();
			let control = $(this);
			let val = control.val();
			let parent = control.closest('.nuevo');
			control.closest('.divItem').find('.content-img').html('');
			if (val.length == 0) {
				RequerimientoInterno.cleanDetalle(parent);
			}
		});
		$(document).off('change', '.file-lsck-capturas').on('change', '.file-lsck-capturas', function (e) {
			var control = $(this);
			var data = control.data();

			var id = '';
			var nameImg = '';
			if (data['row']) {
				id = data['row'];
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			} else {
				id = 0;
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			}

			if (control.val()) {
				var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
				var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
				var num = control.get(0).files.length;

				list: {
					var total = $('input[name="' + name + '[' + id + ']"]').length;
					if ((num + total) > MAX_ARCHIVOS) {
						var message = Fn.message({ type: 2, message: `Solo se permiten ${MAX_ARCHIVOS} capturas como máximo` });
						Fn.showModal({
							'id': ++modalId,
							'show': true,
							'title': 'Alerta',
							'frm': message,
							'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
						});

						break list;
					}

					for (var i = 0; i < num; ++i) {
						var size = control.get(0).files[i].size;
						size = Math.round((size / 1024));

						if (size > KB_MAXIMO_ARCHIVO) {
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO / 1024} MB por captura` });
							Fn.showModal({
								'id': ++modalId,
								'show': true,
								'title': 'Alerta',
								'frm': message,
								'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
							});

							break list;
						}
					}
					let file = '';
					let imgFile = '';
					let contenedor = '';
					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {

							if (fileBase.type.split('/')[0] == 'image') {
								imgFile = fileBase.base64;
								contenedor = content;
							} else if (fileBase.type.split('/')[1] == 'pdf') {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							} else {
								imgFile = `${RUTA_WIREFRAME}file.png`;
								contenedor = content_files;
							}

							var fileApp = '';
							fileApp += '<div class="ui fluid image content-lsck-capturas">';
							fileApp += `
												<div class="ui dimmer dimmer-file-detalle">
													<div class="content">
														<p class="ui tiny inverted header">${fileBase.name}</p>
													</div>
												</div>`;
							fileApp += '<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>';
							fileApp += '<input class="' + name + '" type="hidden" name="' + name + '[' + id + ']" value="' + fileBase.base64 + '">';
							fileApp += '<input class="' + nameType + '" type="hidden" name="' + nameType + '[' + id + ']" value="' + fileBase.type + '">';
							fileApp += '<input class="' + nameFile + '" type="hidden" name="' + nameFile + '[' + id + ']" value="' + fileBase.name + '">';
							fileApp += `<img height="100" src="${imgFile}" class="img-lsck-capturas img-responsive img-thumbnail">`;
							fileApp += '</div>';

							contenedor.append(fileApp);
							control.parents('.nuevo').find('.dimmer-file-detalle')
								.dimmer({
									on: 'click'
								});
						});

					}
				}

				control.val('');
			}
		});
		$(document).on('click', '.btnAnularRequerimientoInterno', function () {
			let id = $(this).data('id');
			Fn.showConfirm({ fn: "RequerimientoInterno.anularRequerimientoInterno(" + id + ")", content: " ¿Está seguro de anular el requerimiento?" });
		});
		$(document).on('click', '.btnSolicitarCostoProveedor', function () {
			++modalId;

			if ($('.proveedorSolicitudForm').find('select').val().length <= 0) {
				$('.proveedorSolicitudForm').transition('shake')
				return false;
			}

			if (!$('.checkItem').is(' :checked')) {
				$('.chk-item').transition('glow');
				return false;
			}

			let jsonString = Fn.formSerializeObject('formActualizarRequerimientoInterno');
			let config = { 'url': RequerimientoInterno.url + 'enviarSolicitudCostoProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Aceptar', fn: fn[0] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

			});
		});
		$(document).on('change', '#proveedor', function () {
			$("#metodoPago").empty();

			var idProveedor = $('#proveedor').val();

			var obj = {
				id: idProveedor
			}
			var jsonString = {
				'data': JSON.stringify(obj)
			};

			var config = {
				url: "OrdenCompra/metodoPago",
				data: jsonString
			};

			$.when(Fn.ajax(config)).then(function (a) {
				// Verifica si hay datos en a.data.metodo
				if (a.data.metodo && a.data.metodo.length > 0) {
					// Obtén la referencia al elemento select
					var selectElement = $('#metodoPago');

					// Limpiar opciones anteriores si es necesario
					selectElement.empty();

					// Itera sobre los datos y agrega opciones al select
					$.each(a.data.metodo, function (i, m) {
						// Agrega una opción al select por cada elemento en a.data.metodo
						selectElement.append($('<option>', {
							value: m.id, // Cambia 'valor' por el nombre del campo que contiene el valor deseado
							text: m.value // Cambia 'texto' por el nombre del campo que contiene el texto deseado
						}));
					});
				}
			});
		});
	},
	actualizarRequerimientoInterno() {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizarRequerimientoInterno')) };
		let url = RequerimientoInterno.url + "actualizarAprobacionCompras";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarRequerimientoInterno").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	tomarPrecio: function (t) {
		let _t = $(t);
		let div = _t.parents('.body-item');
		let inputPrecio = div.find('input.precioTarifarioForm');

		var idProveedor = div.find('.proveedorForm').dropdown('get value');
		var idItem = div.find('.codItems').val();

		inputPrecio.val(RequerimientoInterno.itemTarifario?.[idItem]?.[idProveedor]);
	},
	/*registrarRequerimientoInterno() {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroRequerimientoInterno')) };
		let url = RequerimientoInterno.url + "registrarRequerimientoInterno";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarRequerimientoInterno").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},*/
	seleccionProveedor() {
		++modalId;
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formSeleccionProveedor')) };
		let config = { 'url': RequerimientoInterno.url + 'formularioRegistroOC', 'data': jsonString };

		$.when(Fn.ajax(config)).then((a) => {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };
			fn[1] = 'RequerimientoInterno.agregarSubItem();';
			btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
			fn[2] = 'Fn.showConfirm({ idForm: "formRegistroOC", fn: "RequerimientoInterno.registrarOC()", content: "¿Esta seguro de registrar OC?" });';
			btn[2] = { title: 'Guardar', fn: fn[2] };

			Fn.showModal({ id: modalId, show: true, title: a.data.title, frm: a.data.html, btn: btn, width: '90%' });
			RequerimientoInterno.divItemData = '<div class="row itemData">' + $('#divItemData').html() + '</div>';
			RequerimientoInterno.itemServicio = a.data.itemsServicio;
			RequerimientoInterno.modalIdForm = modalId;
			RequerimientoInterno.cantidadTotal();
			Fn.loadSemanticFunctions();
			Fn.loadDimmerHover();
		});
	},
	SimboloMoneda: function (t) {
		var ts = $(t).val();

		if (ts == 1) {
			$('.monedaSimbolo').text('S/');
		} else {
			$('.monedaSimbolo').text('$');
		}

	},
	agregarSubItem() {
		$('.extraItem').append(RequerimientoInterno.divItemData).clone();
		tot = $('.items').length - 1;
		RequerimientoInterno.itemInputComplete(tot);
		RequerimientoInterno.actualizarOnAddRow();
		Fn.loadSemanticFunctions();
		Fn.loadDimmerHover();
	},
	itemInputComplete: function (ord) {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(RequerimientoInterno.itemServicio, function (index, value) {
			items[nro] = value;
			nro++;
		});

		if (ord == 'all') {
			i = 0;
			limit = $('.items').length;
		} else {
			i = ord;
			limit = ord + 1;
		}
		for (i; i < limit; i++) {
			let input = $(".items")[i];
			$(input).autocomplete({
				source: items,
				select: function (event, ui) {
					event.preventDefault();
					let control = $(this).parents(".itemData");
					//Llenamos los items con el nombre
					$(this).val(ui.item.label);
					//Llenamos una caja de texto invisible que contiene el ID del Artículo
					control.find(".codItems").val(ui.item.value);
					//Tipo Item
					control.find(".tipo").val(ui.item.tipo).trigger('change');
					let costo = Oc.itemTarifario?.[ui.item.value]?.[$('#proveedor').dropdown('get value')]?.costo;

					if (typeof costo === "undefined") costo = 0;
					control.find(".item_costo").val(costo).change();
					$(this).focusout();
					control.find('.content-img').html('');
					control.find('.file-semantic-upload').change();
					if (ui.item.cantidadImagenes > 0) {
						//RequerimientoInterno.alertaParaAgregarItems(control, ui.item);
					}
				},
				appendTo: "#modal-page-" + RequerimientoInterno.modalIdForm,
				max: 5,
				minLength: 3,
			});
		}
	},
	actualizarAutocomplete: function () {
		let items = [];
		let nro = 0;
		$.each(RequerimientoInterno.itemServicio[1], function (index, value) {
			items[nro] = value;
			nro++;
		});
		$(".items").autocomplete({
			source: items,
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();
				let control = $(this).parents(".itemData");
				//Llenamos los items con el nombre
				$(this).val(ui.item.label);
				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				control.find(".codItems").val(ui.item.value);
				//Tipo Item
				control.find(".tipo").val(ui.item.tipo).trigger('change');
				let costo = ui.item.costo == 0 ? '' : ui.item.costo;

				control.find(".item_costo").val(costo).change();
				$(this).focusout();
				control.find('.content-img').html('');
				control.find('.file-semantic-upload').change();
				if (ui.item.cantidadImagenes > 0) {
					RequerimientoInterno.alertaParaAgregarItems(control, ui.item);
				}
			},
			appendTo: "#modal-page-" + RequerimientoInterno.modalIdForm,
			max: 5,
			minLength: 3,
		});
	},
	actualizarOnAddRow: () => {
		$('.btn-add-file').dimmer({ on: 'hover' });
		$('.btn-info-cantidad')
			.popup(
				{
					title: `Si requiere más de ${LIMITE_COMPRAS}`,
					content: `Será necesario cotizar nuevamente con el proveedor`
				}
			);
		$('.btn-info-gap')
			.popup(
				{
					title: `GAP`,
					content: `Solo podrá completar el GAP cuando se haya confirmado un costo`
				}
			);

		//Boton info archivos
		$('.btn-info-archivo')
			.popup(
				{
					title: `Puede subir como máximo ${MAX_ARCHIVOS}	archivos por detalle`,
					content: `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
				}
			);

		$('.btn-info-descripcion')
			.popup(
				{
					title: `Cantidad de elementos`,
					content: `Esta cantidad es referente al Item`
				}
			);
		//Info dias validez
		$('.btn-info-validez')
			.popup(
				{
					title: `Días de validez`,
					content: `Se cuentan a partir de que la cotización es enviada al cliente.`
				}
			);
		$('.simpleDropdown').dropdown();
	},
	actualizarOnAddRowCampos: (parent) => {
		let number = '';
		if (parent.data('id') !== undefined) {
			number = parent.data('id');
		}
		else {
			number = parent.index();
		}
		//Archivos
		let fileItem = parent.find('.file-item');
		let fileType = parent.find('.file-type');
		let fileName = parent.find('.file-name');

		//Textiles
		let tallaSubItem = parent.find('.tallaSubItem');
		let telaSubItem = parent.find('.telaSubItem');
		let colorSubItem = parent.find('.colorSubItem');
		let cantidadSubItemTextil = parent.find('.cantidadSubItemTextil');
		let generoSubItem = parent.find('.generoSubItem');
		//Tarjetas o vales
		let razonSocialSubItemTarjVal = parent.find('.razonSocialSubItemTarjVal');
		let sucursalSubItemTarjVal = parent.find('.sucursalSubItemTarjVal');
		let montoSubItemTarjVal = parent.find('.montoSubItemTarjVal');

		//Servicios y distribucion

		let nombreSubItem = parent.find('.nombreSubItem');
		let cantidadSubItem = parent.find('.cantidadSubItem');

		let cantidadSubItemDistribucion = parent.find('.cantidadSubItemDistribucion');
		let chkTachadoDistribucion = parent.find('.chkTachadoDistribucion');
		let cantidadPdvSubItemDistribucion = parent.find('.cantidadPPDV');
		let itemLogisticaForm = parent.find('select.itemLogisticaForm');
		let cantidadIL = parent.find('.cantidadIL');
		let pesoTotalIL = parent.find('.pesoTotalIL');

		let tipoServicioSubItem = parent.find('.tipoServicioSubItem').find('select');
		let unidadMedidaSubItem = parent.find('.unidadMedidaSubItem');
		let generarOC = parent.find('.generarOCSubItem');
		let proveedorDistribucion = parent.find('.proveedorDistribucionSubItem').find('select');
		let cantidadReal = parent.find('.cantidadRealSubItem');
		let costoSubItem = parent.find('.costoSubItem');
		// let costoSubItemForm = parent.find('.costoSubItemForm');

		// TRANSPORTE
		let departamentoF = parent.find('.departamento_transporte').find('select');
		let provinciaF = parent.find('.provincia_transporte').find('select');
		let distritoF = parent.find('.distrito_transporte').find('select');
		let tipoTransporteF = parent.find('.tipoTransporte_transporte').find('select');
		let costoVisualF = parent.find('.costoVisual_transporte');
		let porcAd = parent.find('.inpPorcTransporte');
		let costoClienteF = parent.find('.costoCliente_transporte');
		let diasF = parent.find('.dias_transporte');
		let cantidadF = parent.find('.cantidad_transporte');
		// FIN: TRANSPORTE

		// TARJETAS Y VALES
		let descripcionTV = parent.find('.descripcionSubItemTarjVal');
		let cantidadTV = parent.find('.cantidadSubItemTarjVal');
		let montoTV = parent.find('.montoSubItemTarjVal');
		// FIN: TARJETAS Y VALES

		// CONCURSO
		let descripcionC = parent.find('.descripcionSubItemConcurso');
		let cantidadC = parent.find('.cantidadSubItemConcurso');
		let montoC = parent.find('.montoSubItemConcurso');
		let porcentajeC = parent.find('.porcentajeSubItemConcurso');
		// FIN: CONCURSO

		// PERSONAL
		let sueldo_personal = parent.find('.sueldo_personal');
		let asignacion_familiar_personal = parent.find('.asignacion_familiar_personal');
		// FIN: PERSONAL
		let idCotizacionDetalle = parent.find('.idCotizacionDetalleSubForm');

		fileItem.attr('name', `file-item[${number}]`);
		fileType.attr('name', `file-type[${number}]`);
		fileName.attr('name', `file-name[${number}]`);

		//
		tallaSubItem.attr('name', `tallaSubItem[${number}]`);
		telaSubItem.attr('name', `telaSubItem[${number}]`);
		colorSubItem.attr('name', `colorSubItem[${number}]`);
		generoSubItem.attr('name', `generoSubItem[${number}]`);
		cantidadSubItemTextil.attr('name', `cantidadTextil[${number}]`);

		razonSocialSubItemTarjVal.attr('name', `razonSocialSubItemTarjVal[${number}]`);
		sucursalSubItemTarjVal.attr('name', `sucursalSubItemTarjVal[${number}]`);
		montoSubItemTarjVal.attr('name', `montoSubItemTarjVal[${number}]`);

		nombreSubItem.attr('name', `nombreSubItemServicio[${number}]`);
		cantidadSubItem.attr('name', `cantidadSubItemServicio[${number}]`);

		cantidadSubItemDistribucion.attr('name', `cantidadSubItemDistribucion[${number}]`);
		chkTachadoDistribucion.attr('name', `chkTachado[${number}]`);
		cantidadPdvSubItemDistribucion.attr('name', `cantidadPdvSubItemDistribucion[${number}]`);
		itemLogisticaForm.attr('name', `itemLogisticaFormNew[${number}]`);
		cantidadIL.attr('name', `cantidadSubItemNro[${number}]`);
		pesoTotalIL.attr('name', `cantidadPesoTotal[${number}]`);

		tipoServicioSubItem.attr('name', `tipoServicioSubItem[${number}]`);
		unidadMedidaSubItem.attr('name', `unidadMedidaSubItem[${number}]`);
		generarOC.attr('name', `generarOCSubItem[${number}]`);
		proveedorDistribucion.attr('name', `proveedorDistribucionSubItem[${number}]`);
		cantidadReal.attr('name', `cantidadRealSubItem[${number}]`);
		costoSubItem.attr('name', `costoSubItem[${number}]`);
		idCotizacionDetalle.attr('name', `idCotizacionDetalleSub[${number}]`);

		// TRANSPORTE
		departamentoF.attr('name', `departamentoTransporte[${number}]`);
		provinciaF.attr('name', `provinciaTransporte[${number}]`);
		distritoF.attr('name', `distritoTransporte[${number}]`);
		tipoTransporteF.attr('name', `tipoTransporte[${number}]`);
		costoVisualF.attr('name', `costoVisualTransporte[${number}]`);
		porcAd.attr('name', `porcAdicionalTransporte[${number}]`);
		costoClienteF.attr('name', `costoClienteTransporte[${number}]`);
		diasF.attr('name', `diasTransporte[${number}]`);
		cantidadF.attr('name', `cantidadTransporte[${number}]`);
		// FIN: TRANSPORTE

		// TARJETAS Y VALES
		descripcionTV.attr('name', `descripcionSubItemTarjVal[${number}]`);
		cantidadTV.attr('name', `cantidadSubItemTarjVal[${number}]`);
		montoTV.attr('name', `montoSubItemTarjVal[${number}]`);
		// FIN: TARJETAS Y VALES

		// CONCURSO
		descripcionC.attr('name', `descripcionSubItemConcurso[${number}]`);
		cantidadC.attr('name', `cantidadSubItemConcurso[${number}]`);
		montoC.attr('name', `montoSubItemConcurso[${number}]`);
		porcentajeC.attr('name', `porcentajeSubItemConcurso[${number}]`);
	},
	actualizarTotal: function () {
		let total = 0;
		$.each($('.item_precio'), function (index, value) {
			total = Number(total) + Number($(value).val());
		})
		let igvForm = $('.igvForm');
		let igv = 0;

		if (igvForm.is(":checked")) {
			igv = IGV_SYSTEM;
		}
		let totalIgv = (total) + (total * igv);

		$('.totalFormLabel').val(moneyFormatter.format(Number(totalIgv)));

		$('.totalFormIgv').val(totalIgv);
		$('.totalForm').val(total);
	},
	alertaParaAgregarItems: function (control, item) {
		++modalId;
		var btn = [];
		let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
		RequerimientoInterno.objetoParaAgregarImagen = control;
		let fn1 = `Fn.showModal({ id: ${modalId} ,show:false }); RequerimientoInterno.agregarImagenes(${item.value});`;

		btn[0] = { title: 'No en este momento', fn: fn, class: 'btn-outline-danger' };
		btn[1] = { title: 'Aceptar', fn: fn1 };
		Fn.showModal({ id: modalId, show: true, title: 'Agregar Imagenes del Item al requerimiento', content: "Desea utilizar las imagenes del Item", btn: btn, width: '33%' });

		$('.simpleDropdown').dropdown();
		$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
	},
	agregarImagenes: function (id) {
		$.post(site_url + 'OrdenCompra/getImagenesItem', {
			idItem: id
		}, function (data) {
			data = jQuery.parseJSON(data);
			if (data.nombre_inicial) {
				divItem = RequerimientoInterno.objetoParaAgregarImagen;
				var content = divItem.find('.content-img');
				var fileApp = '';

				var control = divItem.find('.file-semantic-upload');
				let prefi_name = control.data('name');
				let name = prefi_name + 'File-item';
				let nameType = prefi_name + 'File-type';
				let nameFile = prefi_name + 'File-name';
				let nameEnlace = prefi_name + 'File-idOrigen';
				fileApp += `
				<div class="ui fluid image content-lsck-capturas dimmable">
					<div class="ui dimmer dimmer-file-detalle">
						<div class="content">
								<p class="ui tiny inverted header">${data.nombre_inicial}</p>
						</div>
					</div>
					<input type="hidden" name="${name}" value="../item/">
					<input type="hidden" name="${nameType}" value="idItemImagen">
					<input type="hidden" name="${nameFile}" value="compras.itemImagen">
					<input type="hidden" name="${nameEnlace}" value="${data.idItemImagen}">
					<a class="ui red right floating label option-semantic-delete"><i class="trash icon m-0"></i></a>
					<img height="100" src="https://s3.us-central-1.wasabisys.com/impact.business/item/${data.nombre_archivo}" class="img-responsive img-thumbnail">
				</div>`;
				content.html(fileApp);
				// divItem.find('.adjuntoItemCantidad').val(1);
				divItem.find('.file-semantic-upload').change();
			}

		});
	},
	cleanDetalle: (parent) => {
		let tipoForm = parent.find('#tipoItemForm');
		let costoForm = parent.find('.costoForm');
		let costoFormLabel = parent.find('.costoFormLabel');
		let gapForm = parent.find('.gapForm');
		let cantidadForm = parent.find('.cantidadForm');
		let codItems = parent.find('.codItems');
		let idProveedor = parent.find('.idProveedor');
		let cotizacionInternaForm = parent.find('.cotizacionInternaForm');
		let semaforoForm = parent.find('.semaforoForm');
		let tachadoDistribucion = parent.find('.tbDistribucionTachado');

		codItems.val('');
		idProveedor.val('');
		if (tipoForm.val() == COD_DISTRIBUCION.id) {
			cotizacionInternaForm.val('0');
		} else {
			cotizacionInternaForm.val('1');
		}

		semaforoForm.removeClass('semaforoForm-green');
		semaforoForm.removeClass('semaforoForm-yellow');
		semaforoForm.removeClass('semaforoForm-red');
		semaforoForm.popup('destroy');

		costoForm.val('');
		costoFormLabel.val('');
		gapForm.val('');

		cantidadForm.val('');

		cantidadForm.keyup();

		// tachadoDistribucion.find('tbody').html('');
		// tachadoDistribucion.addClass('d-none');
	},
	anularRequerimientoInterno: function (id) {
		var jsonString = { 'data': JSON.stringify(id) };
		var config = { url: RequerimientoInterno.url + 'anularRequerimientoInterno', data: jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result === 2) return false;
			++modalId;
			var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) fn += 'Fn.showModal({ id:' + modalId + ',show:false });$("#btn-filtrarRequerimientoInterno").click();';

			var btn = [];
			btn[0] = { title: 'Cerrar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
		});
	},
	actualizarOnAddRow: () => {
		$('.btn-add-file').dimmer({ on: 'hover' });
		$('.btn-info-cantidad')
			.popup(
				{
					title: `Si requiere más de ${LIMITE_COMPRAS}`,
					content: `Será necesario cotizar nuevamente con el proveedor`
				}
			);
		$('.btn-info-gap')
			.popup(
				{
					title: `GAP`,
					content: `Solo podrá completar el GAP cuando se haya confirmado un costo`
				}
			);

		//Boton info archivos
		$('.btn-info-archivo')
			.popup(
				{
					title: `Puede subir como máximo ${MAX_ARCHIVOS}	archivos por detalle`,
					content: `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
				}
			);

		$('.btn-info-descripcion')
			.popup(
				{
					title: `Cantidad de elementos`,
					content: `Esta cantidad es referente al Item`
				}
			);
		//Info dias validez
		$('.btn-info-validez')
			.popup(
				{
					title: `Días de validez`,
					content: `Se cuentan a partir de que la cotización es enviada al cliente.`
				}
			);
		//Info Proveedor
		$('.btn-info-proveedor')
			.popup(
				{
					title: `Si requiere agregar proveedores`,
					content: `Considerar en caso de agregar nuevos proveedores se deben de aprobar`
				}
			);
		$('.simpleDropdown').dropdown();
	},
	quitarItem: function (t, v) {
		div = t.closest('div.itemData');
		let cantItems = $(div).length;
		$(div).remove();
	},
	cantidadPorItem: function (t) {
		div = $(t).closest('.itemData').find('div.itemValor');
		cantidad = parseFloat($(div).find('input.item_cantidad').val() || '0');
		costo = parseFloat($(div).find('input.item_costo').val() || '0');
		gap = parseFloat($(div).find('input.item_GAP').val() || '0');
		cantPDV = 0;
		if ($(t).closest('.itemData').find('input.cantidadPDV').length > 0) {
			cantPDV = parseFloat($(t).closest('.itemData').find('input.cantidadPDV').val() || '0') * parseFloat($(div).find('input.item_cantidad').val() || '0');
		}
		let precio = (cantidad * costo) + (cantidad * costo * gap / 100) + cantPDV;
		$(div).find('input.item_precio').val(precio.toFixed(3));
		$(div).find('input.item_precio_real').val(precio);
		RequerimientoInterno.cantidadTotal();
	},
	cantidadTotal: function () {
		let dd = $('input.item_precio_real');
		let xd = $('.item_tipo');
		let total = 0;
		let totalNoFee = 0;
		for (var i = 0; i < dd.length; i++) {
			if (dd[i].value !== '') {
				total += parseFloat(dd[i].value);
			}
		};
		totalTotal = total + totalNoFee;
		$('.totalTotal').val(totalTotal.toFixed(3));
		$('#total_real').val(totalTotal);
		fee = 0; //parseFloat($('#fee').val()||'0');
		// $('#totalFee').val((totalNoFee + total + (total * fee / 100)).toFixed(2));
		igv = parseFloat($('#valorIGV').val()) / 100;
		totalFinal = (totalNoFee + total) * igv + (total * igv * fee / 100);
		$('#totalFinal').val(totalFinal.toFixed(3));
		$('#totalFinal_real').val(totalFinal);
	},
	registrarOC() {
		++modalId;
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formGenerarOC')) };
		let config = { 'url': RequerimientoInterno.url + 'regitrarOC', 'data': jsonString };

		$.when(Fn.ajax(config)).then((a) => {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarRequerimientoInterno").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn, width: '40%' });
		});
	},
	editItemValue: function (t) {
		control = $(t);
		control.closest('.divItem').find('.items').attr('readonly', false);
		control.closest('.divItem').find('.codItems').val('');
	},
}
RequerimientoInterno.load();
