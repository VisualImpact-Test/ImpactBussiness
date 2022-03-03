var Marca = {

	frm: 'frm-marca',
	contentDetalle: 'idContentMarca',
	url: 'Configuracion/Marca/',
	marcasLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarMarca').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarMarca').click();
		});

		$(document).on('click', '#btn-filtrarMarca', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Marca.frm
				, 'url': Marca.url + ruta
				, 'contentDetalle': Marca.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarMarca', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Marca.url + 'formularioRegistroMarca', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroMarcas", fn: "Marca.registrarMarca()", content: "¿Esta seguro de registrar el marca?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizarMarca', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idMarcaArticulo': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Marca.url + 'formularioActualizacionMarca', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionMarcas", fn: "Marca.actualizarMarca()", content: "¿Esta seguro de actualizar el marca?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoMarca', function () {
			++modalId;

			let idMarcaArticulo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idMarcaArticulo': idMarcaArticulo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Marca.url + 'actualizarEstadoMarca', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarMarca").click();
			});
		});
	},

	registrarMarca: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroMarcas')) };
		let url = Marca.url + "registrarMarca";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarMarca").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarMarca: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionMarcas')) };
		let config = { 'url': Marca.url + 'actualizarMarca', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarMarca").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Marca.load();