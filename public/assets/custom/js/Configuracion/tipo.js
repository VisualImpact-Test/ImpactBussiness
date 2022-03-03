var Tipo = {

	frm: 'frm-tipo',
	contentDetalle: 'idContentTipo',
	url: 'Configuracion/Tipo/',
	tipo: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarTipo').click();
		});

		$(document).on('click', '.card-body > ul > li > a', function (e) {
			Tipo.tipo = $(this).data('tipo');
			Tipo.contentDetalle = Tipo.contentDetalle + Tipo.tipo;
		});

		$(document).ready(function () {
			$('.card-body > ul > li > a.active').click();
			$('#btn-filtrarTipo').click();
		});

		$(document).on('click', '#btn-filtrarTipo', function () {
			var ruta = 'reporte' + Tipo.tipo;
			var config = {
				'idFrm': Tipo.frm
				, 'url': Tipo.url + ruta
				, 'contentDetalle': Tipo.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarTipo', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Tipo.url + 'formularioRegistroTipo' + Tipo.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTipos", fn: "Tipo.registrarTipo()", content: "¿Esta seguro de registrar el tipo?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizarTipo', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTipo': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Tipo.url + 'formularioActualizacionTipo' + Tipo.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionTipos", fn: "Tipo.actualizarTipo()", content: "¿Esta seguro de actualizar el tipo?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoTipo', function () {
			++modalId;

			let idTipo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idTipo': idTipo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Tipo.url + 'actualizarEstadoTipo' + Tipo.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarTipo").click();
			});
		});
	},

	registrarTipo: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroTipos')) };
		let url = Tipo.url + "registrarTipo" + Tipo.tipo;
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTipo").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarTipo: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionTipos')) };
		let config = { 'url': Tipo.url + 'actualizarTipo' + Tipo.tipo, 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTipo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Tipo.load();