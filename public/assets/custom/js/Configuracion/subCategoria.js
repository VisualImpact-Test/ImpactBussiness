var SubCategoria = {

	frm: 'frm-SubCategoria',
	contentDetalle: 'idContentSubCategoria',
	url: 'Configuracion/SubCategoria/',
	categoriasLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarSubCategoria').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarSubCategoria').click();
		});

		$(document).on('click', '#btn-filtrarSubCategoria', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': SubCategoria.frm
				, 'url': SubCategoria.url + ruta
				, 'contentDetalle': SubCategoria.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarSubCategoria', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': SubCategoria.url + 'formularioRegistroSubCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroSubCategorias", fn: "SubCategoria.registrarSubCategoria()", content: "¿Esta seguro de registrar la SubCategoria?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizarSubCategoria', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idSubCategoria': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SubCategoria.url + 'formularioActualizacionSubCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionSubCategorias", fn: "SubCategoria.actualizarSubCategoria()", content: "¿Esta seguro de actualizar la SubCategoria?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoSubCategoria', function () {
			++modalId;

			let idSubCategoria = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idItemSubCategoria': idSubCategoria, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SubCategoria.url + 'actualizarEstadoSubCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarSubCategoria").click();
			});
		});
	},

	registrarSubCategoria: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroSubCategorias')) };
		let url = SubCategoria.url + "registrarSubCategoria";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarSubCategoria").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarSubCategoria: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionSubCategorias')) };
		let config = { 'url': SubCategoria.url + 'actualizarSubCategoria', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarSubCategoria").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

SubCategoria.load();