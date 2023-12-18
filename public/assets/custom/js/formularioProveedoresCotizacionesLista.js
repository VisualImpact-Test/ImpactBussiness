var FormularioProveedores = {
	frm: 'frmCotizacionProveedorCabecera',
	contentDetalle: 'content-tb-cotizaciones-proveedor',
	url: 'FormularioProveedor/',
	base64: [],
	type: [],
	name: [],
	base64_g: [],
	type_g: [],
	name_g: [],
	base64_f: [],
	type_f: [],
	name_f: [],
	base64_x: [],
	type_x: [],
	name_x: [],
	base64_da: [],
	type_da: [],
	name_da: [],

	load: function () {

		$(document).ready(function () {
			FormularioProveedores.actualizarTable();
		});

		$(document).on("click", ".btnGuardarCotizacion", () => {
			let idForm = 'frmCotizacionesProveedor';
			$.when(Fn.validateForm({ id: idForm })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject(idForm)) };
					let url = "FormularioProveedor/actualizarCotizacionProveedor";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });FormularioProveedores.actualizarTable()';

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
		$(document).on('click', '.btn-detalleCotizacion', function () {
			let id = $(this).parents('tr:first').data('id');
			Fn.goToUrl(site_url + 'FormularioProveedor/cotizaciones/' + id);

		});
		$(document).on('click', '.btnCargarValidacion', function () {
			++modalId;

			var dataForm = {};
			dataForm.base64Adjunto = FormularioProveedores.base64;
			dataForm.typeAdjunto = FormularioProveedores.type;
			dataForm.nameAdjunto = FormularioProveedores.name;
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': FormularioProveedores.url + 'registrarValidacionArte', 'data': jsonString };
			$.when(Fn.ajax(config)).then(function (a) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				if (a.result == 1) {
					fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
				location.reload();
			});

		});

		$(document).on('click', '.formValArt', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.ordencompra = $(this).data('oc');
			dataForm.flagoclibre = $(this).data('flag');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioValidacionArte', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.registrarValidacionArte()", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.btn-descargarOc', function (e) {
			e.preventDefault();
			Fn.showLoading(true);
			id = $(this).data('id');
			data = { 'data': JSON.stringify({ 'id': id }) };
			var url = '../Cotizacion/' + 'descargarOrdenCompra';
			//alert(url);
			$.when(Fn.download(url, data)).then(function (a) {
				Fn.showLoading(false);
			});
		});
		$(document).on('click', '.formLisArts', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.ordencompra = $(this).data('oc');
			dataForm.flagoclibre = $(this).data('flag');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioListadoArtesCargados', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.enviarCorreoValidacionDeArtes()", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Enviar Correo', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formLisSustServ', function () {
			++modalId;
			// let id = 
			var dataForm = {};
			dataForm.id = $(this).data('idocdetpro');
			dataForm.idcot = $(this).data('idcot');
			dataForm.idpro = $(this).data('idpro');
			dataForm.flagoclibre = $(this).data('flag');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			// alert($(this).data('idcotdetpro'));
			let config = { 'url': FormularioProveedores.url + 'formularioListadoSustentoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// TODO : El actualizar cierra y vuelve a abrir el modal, buscar una forma de optimizar
				fn[1] = 'Fn.closeModals(' + modalId + '); $(".formLisSustServ.dicdp-' + dataForm.id + '").click();';
				btn[1] = { title: 'Actualizar', fn: fn[1], class: 'rstSustServ btn btn-trade-visual' };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		
		$(document).on('click', '.formLisSustComprobante', function () {
			++modalId;
			// let id = 
			var dataForm = {};
			dataForm.id = $(this).data('idoc');
			dataForm.idcot = $(this).data('idcot');
			dataForm.idpro = $(this).data('idpro');
			dataForm.flagoclibre = $(this).data('flag');
			dataForm.idformat = $(this).data('idformat');
			dataForm.seriado = $(this).data('seriado');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			// alert($(this).data('idcotdetpro'));
			let config = { 'url': FormularioProveedores.url + 'formularioListadoSustentoComprobante', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// TODO : El actualizar cierra y vuelve a abrir el modal, buscar una forma de optimizar
				fn[1] = 'Fn.closeModals(' + modalId + '); $(".formLisSustComprobante.dicdp-' + dataForm.id + '").click();';
				btn[1] = { title: 'Actualizar', fn: fn[1], class: 'rstSustServ btn btn-trade-visual' };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formEditArte', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioEditarArte', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.actualizarValidacionDeArtes(' + modalId + ')", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formEditSustentoServ', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioEditarSustentoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.actualizarSustentoServicio(' + modalId + ')", content: "¿Esta seguro de registrar el archivo cargado?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formEditSustentoComprobante', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioEditarSustentoComprobante', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.actualizarSustentoComprobante(' + modalId + ')", content: "¿Esta seguro de registrar el archivo cargado?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formFechaEje', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.ordencompra = $(this).data('oc');
			dataForm.flagoclibre = $(this).data('flag');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioFechaEjecucion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.registrarFechaEjecucion()", content: "¿Esta seguro de registrar la fecha indicada?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formFechaV', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.ordencompra = $(this).data('oc');
			dataForm.flagoclibre = $(this).data('flag');
			dataForm.mostrarOpcionesExt = '1';

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioListadoFechasCargados', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				//VERIFICAR DATA DE ARTES DENTRO DEL HTML
				let htmlContent = a.data.html;
				let $html = $(htmlContent);
				let nombreArchivo = $html.find('td:eq(1)').text();
				console.log('Nombre de archivo:', nombreArchivo);

				if (nombreArchivo === null || nombreArchivo.trim() === ""
					|| nombreArchivo.trim() === "-") {
					let fnF = '';
					++modalId;
					btn[0] = {
						title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' +
							fnF
					};
					var content = "<div class='alert alert-danger'><strong>No se ha encontrado ningún archivo registrado.</strong></div>";
					Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: content, btn: btn });
				} else {
					fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
					btn[0] = { title: 'Cerrar', fn: fn[0] };
					fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.enviarCorreoValidacionDeArtes()", content: "¿Esta seguro de registrar la validación de Arte?" });';

					Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });

				}
			});
		})
		$(document).on('click', '.formSustServ', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('idcotdetpro');
			dataForm.idcot = $(this).data('idcot');
			dataForm.idpro = $(this).data('idpro');
			dataForm.ordencompra = $(this).data('oc');
			dataForm.flagoclibre = $(this).data('flag');
			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioSustentoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "FormularioProveedores.registrarSustentoServicio()", content: "¿Esta seguro de registrar la información?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formSustento', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.ordencompra = $(this).data('idoc');
			dataForm.flagoclibre = $(this).data('flag');
			dataForm.requiereguia = $(this).data('requiereguia');
			dataForm.cotizacion = $(this).data('idcot');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': FormularioProveedores.url + 'formularioSustento', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroSustento", fn: "FormularioProveedores.registrarSustento()", content: "¿Esta seguro de registrar sustento indicado?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.btnGuardarFecha', function () {
			let pr = $(this).closest('td.tdFecha');
			++modalId;
			var dataForm = {};
			dataForm.fechaIni = pr.find('.fechaIni').val();
			dataForm.fechaFin = pr.find('.fechaFin').val();
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': FormularioProveedores.url + 'guardarFechaEjecucion', 'data': jsonString };

			$.when(Fn.ajax(config)).then(function (a) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				if (a.result == 1) {
					fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
				location.reload();
			});

		});

		$(document).off('change', '.file-uploadedd').on('change', '.file-uploadedd', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				console.log(control);
				control.closest('.tdFile').find('.lMsg').html(num + ' archivo(s) cargado(s).');
				list: {
					if ((num) > 10) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + 10 + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por archivo` });
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

						FormularioProveedores.base64 = [];
						FormularioProveedores.type = [];
						FormularioProveedores.name = [];

						Fn.getBase64(file).then(function (fileBase) {
							$('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							FormularioProveedores.base64.push(fileBase.base64);
							FormularioProveedores.type.push(fileBase.type);
							FormularioProveedores.name.push(fileBase.name);
						});
					}

				}
			}
		});
		$(document).off('change', '.file-upload_guia').on('change', '.file-upload_guia', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				control.closest('.tdFile').find('.lMsg').html(num + ' archivo(s) cargado(s).');
				list: {
					if ((num) > 10) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + 10 + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por archivo` });
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

						FormularioProveedores.base64_g = [];
						FormularioProveedores.type_g = [];
						FormularioProveedores.name_g = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							FormularioProveedores.base64_g.push(fileBase.base64);
							FormularioProveedores.type_g.push(fileBase.type);
							FormularioProveedores.name_g.push(fileBase.name);
						});
					}

				}
			}
		});
		$(document).off('change', '.file-upload_factura').on('change', '.file-upload_factura', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				console.log(control);
				control.closest('.tdFile').find('.lMsg').html(num + ' archivo(s) cargado(s).');
				list: {
					if ((num) > 10) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + 10 + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por archivo` });
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

						FormularioProveedores.base64_f = [];
						FormularioProveedores.type_f = [];
						FormularioProveedores.name_f = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							FormularioProveedores.base64_f.push(fileBase.base64);
							FormularioProveedores.type_f.push(fileBase.type);
							FormularioProveedores.name_f.push(fileBase.name);
						});
					}

				}
			}
		});
		$(document).off('change', '.file-upload_xml').on('change', '.file-upload_xml', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				console.log(control);
				control.closest('.tdFile').find('.lMsg').html(num + ' archivo(s) cargado(s).');
				list: {
					if ((num) > 10) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + 10 + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por archivo` });
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

						FormularioProveedores.base64_x = [];
						FormularioProveedores.type_x = [];
						FormularioProveedores.name_x = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							FormularioProveedores.base64_x.push(fileBase.base64);
							FormularioProveedores.type_x.push(fileBase.type);
							FormularioProveedores.name_x.push(fileBase.name);
						});
					}

				}
			}
		});
		$(document).off('change', '.file-upload_da').on('change', '.file-upload_da', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				control.closest('.tdFile').find('.lMsg').html(num + ' archivo(s) cargado(s).');
				list: {
					if ((num) > 50) {
						var message = Fn.message({ type: 2, message: 'Solo se permite ' + 50 + ' archivos como máximo' });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO}KB por archivo` });
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

						FormularioProveedores.base64_da = [];
						FormularioProveedores.type_da = [];
						FormularioProveedores.name_da = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							FormularioProveedores.base64_da.push(fileBase.base64);
							FormularioProveedores.type_da.push(fileBase.type);
							FormularioProveedores.name_da.push(fileBase.name);
						});
					}

				}
			}
		});
		$(document).on("click", ".btnPopupCotizacionesProveedor", () => {
			let idForm = 'frmCotizacionesProveedor';
			$.when(Fn.validateForm({ id: idForm })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject(idForm)) };
					let url = "FormularioProveedor/actualizarCotizacionProveedor";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });FormularioProveedores.actualizarTable()';

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
		$(document).on("click", ".btnLogoutProveedor", () => {

			let jsonString = {};
			let url = "FormularioProveedor/logout";
			let config = { url: url, data: jsonString };

			$.when(Fn.ajax(config)).then(function (b) {
				++modalId;
				var btn = [];
				let fn = 'Fn.showModal({ id: ' + modalId + ',show:false});Fn.goToUrl(`' + b.data.url + '`);';
				btn[0] = { title: 'Aceptar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
			});
		});
		$(document).on("click", ".btnRefreshCotizaciones", () => {
			FormularioProveedores.actualizarTable();
		});

	},
	actualizarTable: function () {
		var ruta = 'cotizacionesListaRefresh';
		var config = {
			'idFrm': FormularioProveedores.frm
			, 'url': FormularioProveedores.url + ruta
			, 'contentDetalle': FormularioProveedores.contentDetalle
		};

		Fn.loadReporte_new(config);
	},
	registrarValidacionArte: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroValidacionArte'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'registrarValidacionArte', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			location.reload();
		});
	},
	registrarFechaEjecucion: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroFechaEjecucion'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'guardarFechaEjecucion', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');location.reload();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarSustentoServicio: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroSustentoServicio'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'guardarSustentoServicio', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');location.reload();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarValidacionDeArtes: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionValidacionArte'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'editarValidacionArte', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			console.log(a);
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				// fn[0] = 'Fn.closeModals(' + modalId + ');';
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				Fn.showModal({ id: modal, show: false })
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarSustentoServicio: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionSustentoServicio'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'editarSustentoServicio', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				// fn[0] = 'Fn.closeModals(' + modalId + ');';
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false }); $(".rstSustServ").click();';
				Fn.showModal({ id: modal, show: false })
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarSustentoComprobante: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionSustentoComprobante'));
		dataForm.base64Adjunto = FormularioProveedores.base64;
		dataForm.typeAdjunto = FormularioProveedores.type;
		dataForm.nameAdjunto = FormularioProveedores.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'editarSustentoComprobante', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				// fn[0] = 'Fn.closeModals(' + modalId + ');';
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false }); $(".rstSustServ").click();';
				Fn.showModal({ id: modal, show: false })
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	enviarCorreoValidacionDeArtes: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formularioListadoDeArtes'));
		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'enviarCorreoValidacionDeArtes', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			location.reload();
		});
	},
	registrarSustento: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroSustento'));
		dataForm.base64Adjunto_g = FormularioProveedores.base64_g;
		dataForm.typeAdjunto_g = FormularioProveedores.type_g;
		dataForm.nameAdjunto_g = FormularioProveedores.name_g;

		dataForm.base64Adjunto_f = FormularioProveedores.base64_f;
		dataForm.typeAdjunto_f = FormularioProveedores.type_f;
		dataForm.nameAdjunto_f = FormularioProveedores.name_f;

		dataForm.base64Adjunto_x = FormularioProveedores.base64_x;
		dataForm.typeAdjunto_x = FormularioProveedores.type_x;
		dataForm.nameAdjunto_x = FormularioProveedores.name_x;

		dataForm.base64Adjunto_da = FormularioProveedores.base64_da;
		dataForm.typeAdjunto_da = FormularioProveedores.type_da;
		dataForm.nameAdjunto_da = FormularioProveedores.name_da;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': FormularioProveedores.url + 'guardarSustento', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');location.reload();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });

		});
	}

}
FormularioProveedores.load();
