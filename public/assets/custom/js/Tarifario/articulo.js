var Articulo = {

	frm: 'frm-articulo',
	contentDetalle: 'idContentArticulo',
	url: 'Tarifario/Articulo/',
	articulos: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarTarifarioArticulo').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarTarifarioArticulo').click();
		});

		$(document).on('click', '#btn-filtrarTarifarioArticulo', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Articulo.frm
				, 'url': Articulo.url + ruta
				, 'contentDetalle': Articulo.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarTarifarioArticulo', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Articulo.url + 'formularioRegistroTarifarioArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Articulo.articulos = a.data.articulos;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTarifarioArticulos", fn: "Articulo.registrarTarifarioArticulo()", content: "¿Esta seguro de registrar el articulo?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Articulo.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-actualizarTarifarioArticulo', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTarifarioArticulo': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Articulo.url + 'formularioActualizacionTarifarioArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Articulo.articulos = a.data.articulos;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionTarifarioArticulos", fn: "Articulo.actualizarTarifarioArticulo()", content: "¿Esta seguro de actualizar el tarifario del articulo?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Articulo.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-historialTarifarioArticulo', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTarifarioArticulo': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Articulo.url + 'formularioHistorialTarifarioArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Articulo.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-estadoArticulo', function () {
			++modalId;

			let idTarifarioArticulo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idTarifarioArticulo': idTarifarioArticulo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Articulo.url + 'actualizarEstadoTarifarioArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarTarifarioArticulo").click();
			});
		});

		$(document).on('blur', '#nombre', function (e) {
			$cod = $(this).parent().find("#idArticulo").val();
			if ($cod == '') {
				$(this).val('');
			} else {
				$(this).attr('readonly', 'readonly');
			}
		});
	},

	registrarTarifarioArticulo: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroTarifarioArticulos')) };
		let url = Articulo.url + "registrarTarifarioArticulo";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			++modalId;
			var btn = [];
			let fn = []
			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			if (a.result == 2) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();';
				fn[1] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();Articulo.actualizarActualTarifarioArticulo(' + a.data.idTarifarioArticulo + ', ' + a.data.idArticulo + ')';
				btn[0] = { title: 'No', fn: fn[0] };
				btn[1] = { title: 'Si', fn: fn[1] };
			}

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarActualTarifarioArticulo: function (idTarifarioArticulo, idArticulo) {
		++modalId;

		let data = { 'idTarifarioArticulo': idTarifarioArticulo, 'idArticulo': idArticulo }
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Articulo.url + 'actualizarActualTarifarioArticulo', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarTarifarioArticulo: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionTarifarioArticulos')) };
		let config = { 'url': Articulo.url + 'actualizarTarifarioArticulo', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			if (a.result == 2) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();';
				fn[1] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTarifarioArticulo").click();Articulo.actualizarActualTarifarioArticulo(' + a.data.idTarifarioArticulo + ', ' + a.data.idArticulo + ')';
				btn[0] = { title: 'No', fn: fn[0] };
				btn[1] = { title: 'Si', fn: fn[1] };
			}

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		$("#nombre").autocomplete({
			source: Articulo.articulos[1],
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los articulos con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idArticulo").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 5,
		});
	},
}

Articulo.load();