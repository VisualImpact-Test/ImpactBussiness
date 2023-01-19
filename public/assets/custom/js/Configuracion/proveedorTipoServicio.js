var ProveedorTipoServicio = {

	frm: 'frm-proveedorTipoServicio',
	contentDetalle: 'idContentProveedorTipoServicio',
	url: 'Configuracion/ProveedorTipoServicio/',
	tipo: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedorTipoServicio').click();
		});

		$(document).on('click', '.card-body > ul > li > a', function (e) {
			ProveedorTipoServicio.tipo = $(this).data('tipo');
			ProveedorTipoServicio.contentDetalle = ProveedorTipoServicio.contentDetalle + ProveedorTipoServicio.tipo;
		});

		$(document).ready(function () {
			$('.card-body > ul > li > a.active').click();
			$('#btn-filtrarProveedorTipoServicio').click();
		});

		$(document).on('click', '#btn-filtrarProveedorTipoServicio', function () {
			var ruta = 'reporte' + ProveedorTipoServicio.tipo;
			var config = {
				'idFrm': ProveedorTipoServicio.frm
				, 'url': ProveedorTipoServicio.url + ruta
				, 'contentDetalle': ProveedorTipoServicio.contentDetalle
			};
			Fn.loadReporte_new(config);
		});
		$(document).on('click', '#btn-registrarProveedorTipoServicio', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': ProveedorTipoServicio.url + 'formularioRegistroProveedorTipoServicio' + ProveedorTipoServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedorTipoServicio", fn: "ProveedorTipoServicio.registrarProveedorTipoServicio()", content: "¿Esta seguro de registrar el tipo de Servicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
		$(document).on('click', '.btn-estadoTipo', function () {
			++modalId;

			let idTipo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idProveedorTipoServicio': idTipo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ProveedorTipoServicio.url + 'actualizarEstadoTipoServicio' + ProveedorTipoServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarProveedorTipoServicio").click();
			});
		});
		$(document).on('click', '.btn-actualizarProveedorTipoServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTipoServicio': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ProveedorTipoServicio.url + 'formularioActualizacionTipoServicio' + ProveedorTipoServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionProveedorTipoServicio", fn: "ProveedorTipoServicio.actualizarTipoServicio()", content: "¿Esta seguro de actualizar el tipo servicio?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
	},
	registrarProveedorTipoServicio: function () {
		$.when(Fn.validateForm({ id: 'formRegistroProveedorTipoServicio' })).then(function (a) {
			if (a === true) {
				let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedorTipoServicio')) };
				let url = ProveedorTipoServicio.url + "registrarProveedorTipoServicio" + ProveedorTipoServicio.tipo;
				let config = { url: url, data: jsonString };

				$.when(Fn.ajax(config)).then(function (b) {
					++modalId;
					var btn = [];
					let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

					if (b.result == 1) {
						fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorTipoServicio").click();';
					}

					btn[0] = { title: 'Continuar', fn: fn };
					Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
				});
			}
		});
	},
	actualizarTipoServicio: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionProveedorTipoServicio')) };
		console.log(jsonString);
		let config = { 'url': ProveedorTipoServicio.url + 'actualizarTipoServicio' + ProveedorTipoServicio.tipo, 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorTipoServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

ProveedorTipoServicio.load();
