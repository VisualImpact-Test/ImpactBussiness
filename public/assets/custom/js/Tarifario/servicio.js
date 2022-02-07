var Servicio = {

	frm: 'frm-servicio',
	contentDetalle: 'idContentServicio',
	url: 'Servicio/',
	serviciosLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarServicio').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarServicio').click();
		});

		$(document).on('click', '#btn-filtrarServicio', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Servicio.frm
				, 'url': Servicio.url + ruta
				, 'contentDetalle': Servicio.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarServicio', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Servicio.url + 'formularioRegistroServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroServicios", fn: "Servicio.registrarServicio()", content: "¿Esta seguro de registrar el servicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizarServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idServicio': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Servicio.url + 'formularioActualizacionServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionServicios", fn: "Servicio.actualizarServicio()", content: "¿Esta seguro de actualizar el servicio?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoServicio', function () {
			++modalId;

			let idServicio = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idServicio': idServicio, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Servicio.url + 'actualizarEstadoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarServicio").click();
			});
		});
	},

	registrarServicio: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroServicios')) };
		let url = Servicio.url + "registrarServicio";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarServicio: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionServicios')) };
		let config = { 'url': Servicio.url + 'actualizarServicio', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Servicio.load();