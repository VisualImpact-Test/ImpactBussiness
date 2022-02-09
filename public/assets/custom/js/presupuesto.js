var Presupuesto = {

	frm: 'frm-articulo',
	contentDetalle: 'idContentArticulo',
	url: 'Presupuesto/',
	articulosLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarArticulo').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarArticulo').click();
		});

		$(document).on('click', '#btn-filtrarArticulo', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Presupuesto.frm
				, 'url': Presupuesto.url + ruta
				, 'contentDetalle': Presupuesto.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarArticulo', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Presupuesto.url + 'formularioRegistroArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Presupuesto.articulosLogistica = a.data.articulosLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroArticulos", fn: "Presupuesto.registrarArticulo()", content: "¿Esta seguro de registrar el articulo?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Presupuesto.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-actualizarArticulo', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idArticulo': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Presupuesto.url + 'formularioActualizacionArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Presupuesto.articulosLogistica = a.data.articulosLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionArticulos", fn: "Presupuesto.actualizarArticulo()", content: "¿Esta seguro de actualizar el articulo?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Presupuesto.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-estadoArticulo', function () {
			++modalId;

			let idArticulo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idArticulo': idArticulo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Presupuesto.url + 'actualizarEstadoArticulo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarArticulo").click();
			});
		});
	},

	registrarArticulo: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroArticulos')) };
		let url = Presupuesto.url + "registrarArticulo";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarArticulo").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarArticulo: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionArticulos')) };
		let config = { 'url': Presupuesto.url + 'actualizarArticulo', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarArticulo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		$("#equivalente").autocomplete({
			source: Presupuesto.articulosLogistica[1],
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los articulos con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idArticuloLogistica").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 5,
		});
	},
}

Presupuesto.load();