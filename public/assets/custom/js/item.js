var Item = {

	frm: 'frm-item',
	contentDetalle: 'idContentItem',
	url: 'Item/',
	itemsLogistica: [],
	idImagenesAnular: [],
	base64: [],
	type: [],
	name: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarItem').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarItem').click();
			//Gestion.urlActivo = Item.url;
		});

		$(document).on('click', '#btn-filtrarItem', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Item.frm
				, 'url': Item.url + ruta
				, 'contentDetalle': Item.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarItem', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Item.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Item.registrarItem()", content: "¿Esta seguro de registrar el item?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});
		$(document).on('focusout', '.itemLogistica', function () {
			let control = $(this);
			let val = control.val();
			if (val != '' && val != undefined && val != null) {
				control.attr('readonly', 'readonly');
			}
			id = control.closest('.divItemLogistica').find('.codItemLogistica').val();
			if (id == '' || id == undefined || id == null) {
				control.closest('.divItemLogistica').find('.codItemLogistica').val('0');
			}
		});
		$(document).on('click', '.btn-descargarTarifario', function () {
			let jsonString = { 'data': JSON.stringify({}) };
			Fn.download(site_url + Item.url + 'descargarTarifarioPDF', jsonString);
		});
		$(document).on('click', '.btn-descargarListaDeItem', function () {
			let jsonString = { 'data': JSON.stringify({}) };
			Fn.download(site_url + Item.url + 'descargarListaDeItem', jsonString);
		});
		$(document).on('click', '.btn-actualizarItem', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idItem': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioActualizacionItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionItems", fn: "Item.actualizarItem()", content: "¿Esta seguro de actualizar el item?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '#btn-adicionarIL', function () {
			++modalId;

			let id = '';
			let data = { 'idItem': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioRegistroItemLogistica', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItemLogistica", fn: "Item.registrarItemLogistica()", content: "¿Esta seguro de registrar el item?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-fotosItem', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idItem': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioFotosItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.items = a.data.items;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '30%' });

			});
		});

		$(document).on('click', '.btn-estadoItem', function () {
			++modalId;

			let idItem = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idItem': idItem, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'actualizarEstadoItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarItem").click();
			});
		});
		$(document).on('click', '#btn-listaItemLogistica', function () {
			++modalId;
			let jsonString = { 'data': '' };
			let config = { 'url': Item.url + 'listItemLogistica', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('change', '#tipo', function () {
			let idtipo = $(this).val();
			let control = $(this).closest(".body-item");
			control.find('.campos_dinamicos').addClass('d-none');
			control.find(`.div-feature-${idtipo}`).removeClass('d-none');
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
					if ((num + total) > control.data('fileMax')) {
						var message = Fn.message({ type: 2, message: `Solo se permiten ${control.data('fileMax')} archivo como máximo` });
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

						if (size > 2048) {
							var message = Fn.message({ type: 2, message: 'Solo se permite como máximo 1MB por archivo' });
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
							} else {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							}

							var fileApp = '';
							fileApp += '<div class="ui fluid image content-lsck-capturas">';
							fileApp += `<div class="ui sub header">${fileBase.name}</div>`;
							fileApp += `
											<div class="ui dimmer dimmer-file-detalle">
												<div class="content">
													<p class="ui tiny inverted header">${fileBase.name}</p>
												</div>
											</div>`;
							fileApp += '<a class="ui red right ribbon label img-lsck-capturas-delete"><i class="trash icon"></i></a>';
							fileApp += '<input type="hidden" name="' + name + '[' + id + ']" value="' + fileBase.base64 + '">';
							fileApp += '<input type="hidden" name="' + nameType + '[' + id + ']" value="' + fileBase.type + '">';
							fileApp += '<input type="hidden" name="' + nameFile + '[' + id + ']" value="' + fileBase.name + '">';
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

		$(document).off('click', '.img-lsck-capturas').on('click', '.img-lsck-capturas', function (e) {
			e.preventDefault();
		});

		$(document).off('change', '.file-upload').on('change', '.file-upload', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;

				list: {
					if ((num) > MAX_ARCHIVOS) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + MAX_ARCHIVOS + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por captura` });
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

						Item.base64 = [];
						Item.type = [];
						Item.name = [];

						Fn.getBase64(file).then(function (fileBase) {
							$('.labelImagen').html(num + ' Imagen(es) cargada(s)');

							Item.base64.push(fileBase.base64);
							Item.type.push(fileBase.type);
							Item.name.push(fileBase.name);

							// $('#f_item').val(fileBase.base64);
							// $('#f_type').val(fileBase.type);
							// $('#f_name').val(fileBase.name);
							// $('#imagenFirma').attr('src', fileBase.base64);
							// $('#imagenFirma').removeClass('d-none');
						});
					}

				}
			}
		});

		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			control.parents('.content-lsck-capturas:first').remove();
		});

		//de la imagen

		//$(document).on('change', '.tipoArticulo', function () {
		//++modalId;

		//	let idItem = $(this).parents('tr:first').data('id');
		//	let estado = $(this).data('estado');
		//	let data = { 'idItem': idItem, 'estado': estado };

		//	let jsonString = { 'data': JSON.stringify(data) };
		//	let config = { 'url': Item.url + 'actualizarEstadoItem', 'data': jsonString };

		//	$.when(Fn.ajax(config)).then((a) => {
		//		$("#btn-filtrarItem").click();
		//	});
		//	});
	},

	registrarItem: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroItems')) };
		let url = Item.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarItemLogistica: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroItemLogistica')) };
		let url = Item.url + "registrarItemLogistica";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarItem: function () {
		++modalId;
		// Almacenamos los datos del formulario en una variable por que vamos a incluir:
		// * Las imagenes eliminadas almacenadas en la variable Item.idImagenesAnular.
		// * Los archivos adjuntados en Item.base64, Item.type, Item.name.

		var dataForm = Fn.formSerializeObject('formActualizacionItems');
		dataForm.idImagenAnularItem = Item.idImagenesAnular;
		dataForm.base64Adjunto = Item.base64;
		dataForm.typeAdjunto = Item.type;
		dataForm.nameAdjunto = Item.name;
		let jsonString = { 'data': JSON.stringify(dataForm), 'idImagenesAnular': Item.idImagenesAnular };
		let config = { 'url': Item.url + 'actualizarItem', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	editItemLogisticaValue: function (t) {
		control = $(t);
		control.closest('.divItemLogistica').find('.itemLogistica').attr('readonly', false);
		control.closest('.divItemLogistica').find('.codItemLogistica').val('');
	},
	anularImagen: function (id, t) {
		Item.idImagenesAnular.push(id);
		var control = $(t).parents('.figure');
		control.remove();
	},
	actualizarAutocomplete: function () {

		itemsLogisticaData = Item.itemsLogistica[1];
		let items = [];
		let nro = 0;
		$.each(itemsLogisticaData, function (index, value) {
			items[nro] = value;
			// items[nro].label = value.item;
			// items[nro].value = value.idItem;
			nro++;
		});
		let input = $("#equivalente")[0];
		$(input).autocomplete({
			source: items,
			select: function (event, ui) {
				event.preventDefault();
				let control = $(this).parents(".divItemLogistica");
				//Llenamos los items con el nombre
				$(this).val(ui.item.label);
				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				control.find(".codItemLogistica").val(ui.item.value);

				$(this).focusout();
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 3,
		});
	},
}

Item.load();