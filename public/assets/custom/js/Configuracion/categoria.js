var Categoria = {

	frm: 'frm-categoria',
	contentDetalle: 'idContentCategoria',
	url: 'Configuracion/Categoria/',
	categoriasLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCategoria').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarCategoria').click();
		});

		$(document).on('click', '#btn-filtrarCategoria', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Categoria.frm
				, 'url': Categoria.url + ruta
				, 'contentDetalle': Categoria.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarCategoria', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Categoria.url + 'formularioRegistroCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCategorias", fn: "Categoria.registrarCategoria()", content: "¿Esta seguro de registrar el categoria?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizarCategoria', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCategoriaArticulo': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Categoria.url + 'formularioActualizacionCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionCategorias", fn: "Categoria.actualizarCategoria()", content: "¿Esta seguro de actualizar el categoria?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-estadoCategoria', function () {
			++modalId;

			let idCategoriaArticulo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idCategoriaArticulo': idCategoriaArticulo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Categoria.url + 'actualizarEstadoCategoria', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarCategoria").click();
			});
		});
	},

	registrarCategoria: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCategorias')) };
		let url = Categoria.url + "registrarCategoria";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCategoria").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarCategoria: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionCategorias')) };
		let config = { 'url': Categoria.url + 'actualizarCategoria', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCategoria").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Categoria.load();