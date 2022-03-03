var Item = {

	frm: 'frm-item',
	contentDetalle: 'idContentItem',
	url: 'Item/',
	itemsLogistica: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarItem').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarItem').click();
		});

		$(document).on('click', '#btn-filtrarItem', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Item.frm
				, 'url': Item.url + ruta
				, 'contentDetalle': Item.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarItem', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Item.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Item.registrarItem()", content: "¿Esta seguro de registrar el item?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-actualizarItem', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idItem': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'formularioActualizacionItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Item.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionItems", fn: "Item.actualizarItem()", content: "¿Esta seguro de actualizar el item?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Item.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-estadoItem', function () {
			++modalId;

			let idItem = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idItem': idItem, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Item.url + 'actualizarEstadoItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarItem").click();
			});
		});
	},

	registrarItem: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroItems')) };
		let url = Item.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarItem: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionItems')) };
		let config = { 'url': Item.url + 'actualizarItem', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarItem").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		$("#equivalente").autocomplete({
			source: Item.itemsLogistica[1],
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los items con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idItemLogistica").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 5,
		});
	},
}

Item.load();