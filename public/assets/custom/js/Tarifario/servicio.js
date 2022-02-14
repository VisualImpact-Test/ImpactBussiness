var Servicio = {

	frm: 'frm-servicio',
	contentDetalle: 'idContentServicio',
	url: 'Tarifario/Servicio/',
	servicios: [],

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

		$(document).on('click', '.btn-historialTarifarioServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTarifarioServicio': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Servicio.url + 'formularioHistorialTarifarioServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Fn.actualizarAutocomplete();
			});
		});

		$(document).on('click', '#btn-registrarServicio', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Servicio.url + 'formularioRegistroTarifarioServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Servicio.servicios = a.data.servicios;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroServicios", fn: "Servicio.registrarServicio()", content: "¿Esta seguro de registrar el servicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Servicio.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-actualizarTarifarioServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTarifarioServicio': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Servicio.url + 'formularioActualizacionTarifarioServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionTarifarioServicios", fn: "Servicio.actualizarTarifarioServicio()", content: "¿Esta seguro de actualizar el servicio?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoServicio', function () {
			++modalId;

			let idServicio = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idTarifarioServicio': idServicio, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Servicio.url + 'actualizarEstadoServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarServicio").click();
			});
		});
	},

	registrarServicio: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroServicios')) };
		let url = Servicio.url + "registrarTarifarioServicio";
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

	actualizarTarifarioServicio: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionTarifarioServicios')) };
		let config = { 'url': Servicio.url + 'actualizarTarifarioServicio', 'data': jsonString };

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

	actualizarAutocomplete: function () {
		console.log(Servicio.servicios[1])
		$("#nombre").autocomplete({
			source: Servicio.servicios[1],
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();
				
				//Llenamos los articulos con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idServicio").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 5,
		});
	},
}

Servicio.load();