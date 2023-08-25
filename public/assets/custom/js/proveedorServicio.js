var ProveedorServicio = {

	frm: 'frm-proveedorServicio',
	contentDetalle: 'idContentProveedorServicio',
	url: 'ProveedorServicio/',
	url_FormularioProveedor: 'FormularioProveedor/',
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

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedorServicio').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarProveedorServicio').click();
		});

		$(document).on('click', '#btn-filtrarProveedorServicio', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ProveedorServicio.frm
				, 'url': ProveedorServicio.url + ruta
				, 'contentDetalle': ProveedorServicio.contentDetalle
			};
			Fn.loadReporte_new(config);
		});
		$(document).on('click', '.btn-estadoArte', function () {
			control = $(this);
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');
			dataForm.estado = $(this).data('estado');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarValidacionArteEstado', 'data': jsonString };
			$.when(Fn.ajax(config)).then(function (a) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				if (a.result == 1) {
					console.log(control.closest('tr.default'));
					console.log(control.closest('tr.default').find('.tdEstado'));
					if (control.data('estado') == '1') {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui green basic label large">Aprobado</label>');
					} else {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui red basic label large">Rechazado</label>');
					}
					// fn[0] = 'Fn.closeModals(' + modalId + ');';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			});

		});
		$(document).on('click', '.btn-estadoSustServicio', function () {
			control = $(this);
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');
			dataForm.estado = $(this).data('estado');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarSustentoServicioEstado', 'data': jsonString };
			$.when(Fn.ajax(config)).then(function (a) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false }); $(".rstSustServ").click();';
				if (a.result == 1) {
					if (control.data('estado') == '1') {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui green basic label large">Aprobado</label>');
					} else {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui red basic label large">Rechazado</label>');
					}
					// fn[0] = 'Fn.closeModals(' + modalId + ');';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			});

		});
		$(document).on('click', '.btn-estadoSustComprobante', function () {
			control = $(this);
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');
			dataForm.estado = $(this).data('estado');

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarSustentoComprobanteEstado', 'data': jsonString };
			$.when(Fn.ajax(config)).then(function (a) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false }); $(".rstSustServ").click();';
				if (a.result == 1) {
					if (control.data('estado') == '1') {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui green basic label large">Aprobado</label>');
					} else {
						control.closest('tr.default').find('.tdEstado').html('<label class="ui red basic label large">Rechazado</label>');
					}
					// fn[0] = 'Fn.closeModals(' + modalId + ');';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			});

		});
		$(document).on('click', '.btn-descargarCotizacion', function () {
			let id = $(this).data('id');
			let data = { id };
			let jsonString = { 'data': JSON.stringify(data) };

			Fn.download(site_url + 'Cotizacion/' + 'generarCotizacionPDF', jsonString);
		});
		$(document).on('click', '.btn-descargarOCdelProveedor', function (e) {
			e.preventDefault();
			idCotizacionDetalle = $(this).data('id');
			idProveedor = $(this).data('proveedor');
			data = { 'idCotizacionDetalle': idCotizacionDetalle, 'idProveedor': idProveedor, 'idCotizacion' : idCotizacionDetalle };
			var url = 'SolicitudCotizacion/' + 'descargarExcel';
			$.when(Fn.download(url, data)).then(function (a) {
				Fn.showLoading(false);
			});
		});
		$(document).on('click', '.formValArt', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioValidacionArte', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.registrarValidacionArte()", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
		$(document).on('click', '.formLisArts', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.mostrarOpcionesExt = '1';

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioListadoArtesCargados', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.enviarCorreoValidacionDeArtes()", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Enviar Correo', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
			});
		})
		$(document).on('click', '.formEditArte', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioEditarArte', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.actualizarValidacionDeArtes(' + modalId + ')", content: "¿Esta seguro de registrar la validación de Arte?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formFechaEje', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioFechaEjecucion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.registrarFechaEjecucion()", content: "¿Esta seguro de registrar la fecha indicada?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formSustServ', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('idcotdetpro');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioSustentoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.registrarSustentoServicio()", content: "¿Esta seguro de registrar la información?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formLisSustServ', function () {
			++modalId;
			// let id = 
			var dataForm = {};
			dataForm.id = $(this).data('idcotdetpro');
			dataForm.mostrarOpcionesExt = '1';

			let jsonString = { 'data': JSON.stringify(dataForm) };
			// alert($(this).data('idcotdetpro'));
			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioListadoSustentoServicio', 'data': jsonString };

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
		$(document).on('click', '.formEditSustentoServ', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioEditarSustentoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.actualizarSustentoServicio(' + modalId + ')", content: "¿Esta seguro de registrar el archivo cargado?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formSustento', function () {
			++modalId;
			var dataForm = {};
			dataForm.proveedor = $(this).data('prov');
			dataForm.cotizacion = $(this).data('idcoti');
			dataForm.requiereguia = $(this).data('requiereguia');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioSustento', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroSustento", fn: "ProveedorServicio.registrarSustento()", content: "¿Esta seguro de registrar sustento indicado?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		$(document).on('click', '.formLisSustComprobante', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('idcotdetpro');
			dataForm.mostrarOpcionesExt = '1';

			let jsonString = { 'data': JSON.stringify(dataForm) };
			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioListadoSustentoComprobante', 'data': jsonString };

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
		$(document).on('click', '.formEditSustentoComprobante', function () {
			++modalId;
			var dataForm = {};
			dataForm.id = $(this).data('id');

			let jsonString = { 'data': JSON.stringify(dataForm) };

			let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'formularioEditarSustentoComprobante', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "ProveedorServicio.actualizarSustentoComprobante(' + modalId + ')", content: "¿Esta seguro de registrar el archivo cargado?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		})
		// Inicio: File Uploaded
		$(document).off('change', '.file-uploadedd').on('change', '.file-uploadedd', function (e) {
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

						ProveedorServicio.base64 = [];
						ProveedorServicio.type = [];
						ProveedorServicio.name = [];

						Fn.getBase64(file).then(function (fileBase) {
							$('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							ProveedorServicio.base64.push(fileBase.base64);
							ProveedorServicio.type.push(fileBase.type);
							ProveedorServicio.name.push(fileBase.name);
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

						ProveedorServicio.base64_g = [];
						ProveedorServicio.type_g = [];
						ProveedorServicio.name_g = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							ProveedorServicio.base64_g.push(fileBase.base64);
							ProveedorServicio.type_g.push(fileBase.type);
							ProveedorServicio.name_g.push(fileBase.name);
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

						ProveedorServicio.base64_f = [];
						ProveedorServicio.type_f = [];
						ProveedorServicio.name_f = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							ProveedorServicio.base64_f.push(fileBase.base64);
							ProveedorServicio.type_f.push(fileBase.type);
							ProveedorServicio.name_f.push(fileBase.name);
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

						ProveedorServicio.base64_x = [];
						ProveedorServicio.type_x = [];
						ProveedorServicio.name_x = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							ProveedorServicio.base64_x.push(fileBase.base64);
							ProveedorServicio.type_x.push(fileBase.type);
							ProveedorServicio.name_x.push(fileBase.name);
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

						ProveedorServicio.base64_da = [];
						ProveedorServicio.type_da = [];
						ProveedorServicio.name_da = [];

						Fn.getBase64(file).then(function (fileBase) {
							control.closest('.custom-file').find('.labelImagen').html(num + ' Archivo(s) cargado(s)');

							ProveedorServicio.base64_da.push(fileBase.base64);
							ProveedorServicio.type_da.push(fileBase.type);
							ProveedorServicio.name_da.push(fileBase.name);
						});
					}

				}
			}
		});
		// Fin: File uploaded

		HTCustom.load();

	},

	registrarValidacionArte: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroValidacionArte'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'registrarValidacionArte', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
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
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'enviarCorreoValidacionDeArtes', 'data': jsonString };
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
	actualizarValidacionDeArtes: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionValidacionArte'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarValidacionArte', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			console.log(a);
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarFechaEjecucion: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroFechaEjecucion'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'guardarFechaEjecucion', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarSustentoServicio: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroSustentoServicio'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'guardarSustentoServicio', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarSustentoServicio: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionSustentoServicio'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarSustentoServicio', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false }); $(".rstSustServ").click();';
				Fn.showModal({ id: modal, show: false })
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarSustento: function () {
		++modalId;

		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formRegistroSustento'));
		dataForm.base64Adjunto_g = ProveedorServicio.base64_g;
		dataForm.typeAdjunto_g = ProveedorServicio.type_g;
		dataForm.nameAdjunto_g = ProveedorServicio.name_g;

		dataForm.base64Adjunto_f = ProveedorServicio.base64_f;
		dataForm.typeAdjunto_f = ProveedorServicio.type_f;
		dataForm.nameAdjunto_f = ProveedorServicio.name_f;

		dataForm.base64Adjunto_x = ProveedorServicio.base64_x;
		dataForm.typeAdjunto_x = ProveedorServicio.type_x;
		dataForm.nameAdjunto_x = ProveedorServicio.name_x;

		dataForm.base64Adjunto_da = ProveedorServicio.base64_da;
		dataForm.typeAdjunto_da = ProveedorServicio.type_da;
		dataForm.nameAdjunto_da = ProveedorServicio.name_da;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'guardarSustento', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });

		});
	},
	actualizarSustentoComprobante: function (modal) {
		++modalId;
		var dataForm = {};
		dataForm.data = JSON.stringify(Fn.formSerializeObject('formEdicionSustentoComprobante'));
		dataForm.base64Adjunto = ProveedorServicio.base64;
		dataForm.typeAdjunto = ProveedorServicio.type;
		dataForm.nameAdjunto = ProveedorServicio.name;

		let jsonString = { 'data': JSON.stringify(dataForm) };
		let config = { 'url': ProveedorServicio.url_FormularioProveedor + 'editarSustentoComprobante', 'data': jsonString };
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
}

ProveedorServicio.load();
