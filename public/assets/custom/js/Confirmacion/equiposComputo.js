var EquiposComputo = {

	frm: 'frm-equiposComputo',
	contentDetalle: 'idContentEquiposComputo',
	url: 'Confirmacion/EquiposComputo/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlEquiposComputo: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarEquiposComputo').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarEquiposComputo').click();
		});

		$(document).on('click', '#btn-filtrarEquiposComputo', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': EquiposComputo.frm
				, 'url': EquiposComputo.url + ruta
				, 'contentDetalle': EquiposComputo.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarEquiposComputo', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': EquiposComputo.url + 'formularioRegistroEquiposComputo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					EquiposComputo.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroEquiposComputo", fn: "EquiposComputo.registrarEquiposComputo()", content: "¿Esta seguro de registrar este equiposComputo?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				EquiposComputo.modalIdForm = modalId;

				EquiposComputo.htmlG = $('#listaItemsEquiposComputo tbody tr').html();
				$('#listaItemsEquiposComputo tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-detalleEquiposComputo', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idEquiposComputo': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposComputo.url + 'formularioVisualizacionEquiposComputo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Guardar', fn: fn[0] };
				btn[1] = { title: 'Enviar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				EquiposComputo.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idequiposComputo');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposComputo.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					EquiposComputo.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "EquiposComputo.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				EquiposComputo.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoEquiposComputo', function () {
			++modalId;

			let idEquiposComputo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idEquiposComputo': idEquiposComputo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposComputo.url + 'actualizarEstadoEquiposComputo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarEquiposComputo").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsEquiposComputo tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += EquiposComputo.htmlG;
			$html += "</tr>";

			$('#listaItemsEquiposComputo tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			EquiposComputo.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-add-row-equiposComputo', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsEquiposComputo tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += EquiposComputo.htmlEquiposComputo;
			$html += "</tr>";

			$('#listaItemsEquiposComputo tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsEquiposComputo tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('click', '.btneliminarfilaEquiposComputo', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsEquiposComputo tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			EquiposComputo.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-equiposComputo-pdf', function (e) {
			e.preventDefault();

			let $idEquiposComputo = $(this).parents('tr').data('id');

			EquiposComputo.generarRequerimientoPDF($idEquiposComputo);
		});

		$(document).on('click', '.btn-generarEquiposComputo', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function(index, value){
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposComputo.url + 'formularioGenerarEquiposComputo', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "EquiposComputo.registrarEquiposComputo()", content: "¿Esta seguro de registrar la equiposComputo? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				EquiposComputo.actualizarAutocompleteItemsLogistica();
				EquiposComputo.htmlEquiposComputo = $('#listaItemsEquiposComputo tbody tr').html();
				$('#listaItemsEquiposComputo tbody').html('');
				$(".btn-add-row-equiposComputo").click();
			});
		});
	},

	registrarEquiposComputo: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroEquiposComputo')) };
		let url = EquiposComputo.url + "registrarEquiposComputo";
		let config = { url: url, data: jsonString };
		let diferencias = 0;

		$.each($('.idTipoItem'), function (index, value) {
			if ($(value).val() != '' && $('#tipo').val() != 3) {
				if ($(value).val() != $('#tipo').val()) {
					$(value).parents('.nuevo').find('.ui-widget').addClass('has-error');

					diferencias++;
				}
			}
		});

		if (diferencias > 0) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de equiposComputo. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarEquiposComputo").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarEquiposComputo: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionEquiposComputos')) };
		let config = { 'url': EquiposComputo.url + 'actualizarEquiposComputo', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarEquiposComputo").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(EquiposComputo.itemServicio[1], function (index, value) {
			if (tipo == value.tipo || tipo == 3) {
				items[nro] = value;
				nro++;
			}
		});
		$(".items").autocomplete({
			source: items,
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los items con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".ui-widget").find(".codItems").val(ui.item.value);

				//Llenamos el precio actual
				if (ui.item.costo == null) {
					ui.item.costo = 0;
				}
				$(this).parents(".nuevo").find(".costoForm").val(ui.item.costo);

				//Llenamos el estado
				$(this).parents(".nuevo").find(".estadoItemForm").text('EN SISTEMA');
				$(this).parents(".nuevo").find(".idEstadoItemForm").val(1);
				$(this).parents(".nuevo").find(".idTipoItem").val(ui.item.tipo);

				//Llenamos el proveedor
				$(this).parents(".nuevo").find(".proveedorForm").text(ui.item.proveedor);
				$(this).parents(".nuevo").find(".idProveedor").val(ui.item.idProveedor);

				//Validacion ID

				let $cod = $(this).parents(".ui-widget").find(".codItems").val();
				if ($cod != '') {
					$(this).attr('readonly', 'readonly');
					$(this).parents('.nuevo').find('.costoForm').attr('readonly', 'readonly');
				}
			},
			appendTo: "#modal-page-" + EquiposComputo.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: EquiposComputo.itemsLogistica[1],
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

	generarRequerimientoPDF: function (id) {
		var url = site_url + '/EquiposComputo/generarEquiposComputoPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idEquiposComputo) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
		formValues.idEquiposComputo = idEquiposComputo;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = EquiposComputo.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idEquiposComputo + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarEquiposComputo: function () {
		let formValues = Fn.formSerializeObject('formRegistroEquiposComputo');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = EquiposComputo.url + "registrarEquiposComputo";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
}

EquiposComputo.load();