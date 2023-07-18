var FormularioProveedores = {
	frm: 'frmCotizacionProveedorCabecera',
	contentDetalle: 'content-tb-cotizaciones-proveedor',
	url: 'FormularioProveedor/',
	base64: [],
	type: [],
	name: [],

	load: function () {

		$(document).ready(function () {
			// if($('#idCotizacion').val()){
			FormularioProveedores.actualizarTable();
			// }
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

			console.log(dataForm);
			let jsonString = { 'data': JSON.stringify(dataForm) };
			console.log(jsonString);
			let config = { 'url': FormularioProveedores.url + 'registrarValidacionArte', 'data': jsonString };
			console.log(config);
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
	}

}
FormularioProveedores.load();
