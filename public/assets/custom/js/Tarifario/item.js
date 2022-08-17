var Item = {

	frm: 'frm-item',
	contentDetalle: 'idContentItem',
	url: 'Tarifario/Item/',
	items: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarItemTarifario').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarItemTarifario').click();
			//Gestion.urlActivo = Item.url
		});

		$(document).on('click', '#btn-filtrarItemTarifario', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Item.frm
				, 'url': Item.url + ruta
				, 'contentDetalle': Item.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarItemTarifario', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Item.url + 'formularioRegistroItemTarifario', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.items = a.data.items;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItemTarifarios", fn: "Item.registrarItemTarifario()", content: "¿Esta seguro de registrar el item?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-actualizarItemTarifario', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idItemTarifario': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioActualizacionItemTarifario', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.items = a.data.items;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionItemTarifarios", fn: "Item.actualizarItemTarifario()", content: "¿Esta seguro de actualizar el tarifario del item?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-historialItemTarifario', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idItemTarifario': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioHistorialItemTarifario', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-estadoItem', function () {
			++modalId;

			let idItemTarifario = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idItemTarifario': idItemTarifario, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'actualizarEstadoItemTarifario', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarItemTarifario").click();
			});
		});

		$(document).on('blur', '#nombre', function (e) {
			$cod = $(this).parent().find("#idItem").val();
			if ($cod == '') {
				$(this).val('');
			} else {
				$(this).attr('readonly', 'readonly');
			}
		});
	},

	registrarItemTarifario: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroItemTarifarios')) };
		let url = Item.url + "registrarItemTarifario";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			++modalId;
			var btn = [];
			let fn = []
			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			if (a.result == 2) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();';
				fn[1] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();Item.actualizarActualItemTarifario(' + a.data.idItemTarifario + ', ' + a.data.idItem + ')';
				btn[0] = { title: 'No', fn: fn[0] };
				btn[1] = { title: 'Si', fn: fn[1] };
			}

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarActualItemTarifario: function (idItemTarifario, idItem) {
		++modalId;

		let data = { 'idItemTarifario': idItemTarifario, 'idItem': idItem }
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Item.url + 'actualizarActualItemTarifario', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarItemTarifario: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionItemTarifarios')) };
		let config = { 'url': Item.url + 'actualizarItemTarifario', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			if (a.result == 2) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();';
				fn[1] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItemTarifario").click();Item.actualizarActualItemTarifario(' + a.data.idItemTarifario + ', ' + a.data.idItem + ')';
				btn[0] = { title: 'No', fn: fn[0] };
				btn[1] = { title: 'Si', fn: fn[1] };
			}

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		$("#nombre").autocomplete({
			source: Item.items[1],
			search: function( event, ui ) {

			},
			response: function( event, ui ) {
				
			},
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los items con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idItem").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 10,
			minLength: 2,
		});
	},
}

Item.load();