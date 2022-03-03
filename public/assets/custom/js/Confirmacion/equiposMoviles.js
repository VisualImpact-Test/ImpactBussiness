var EquiposMoviles = {

	frm: 'frm-equiposMoviles',
	contentDetalle: 'idContentEquiposMoviles',
	url: 'Confirmacion/EquiposMoviles/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlEquiposMoviles: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarEquiposMoviles').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarEquiposMoviles').click();
		});

		$(document).on('click', '#btn-filtrarEquiposMoviles', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': EquiposMoviles.frm
				, 'url': EquiposMoviles.url + ruta
				, 'contentDetalle': EquiposMoviles.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarEquiposMoviles', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': EquiposMoviles.url + 'formularioRegistroEquiposMoviles', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					EquiposMoviles.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroEquiposMoviles", fn: "EquiposMoviles.registrarEquiposMoviles()", content: "¿Esta seguro de registrar este equiposMoviles?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				EquiposMoviles.modalIdForm = modalId;

				EquiposMoviles.htmlG = $('#listaItemsEquiposMoviles tbody tr').html();
				$('#listaItemsEquiposMoviles tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-detalleEquiposMoviles', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idEquiposMoviles': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposMoviles.url + 'formularioVisualizacionEquiposMoviles', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Guardar', fn: fn[0] };
				btn[1] = { title: 'Enviar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				EquiposMoviles.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idequiposMoviles');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposMoviles.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					EquiposMoviles.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "EquiposMoviles.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				EquiposMoviles.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoEquiposMoviles', function () {
			++modalId;

			let idEquiposMoviles = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idEquiposMoviles': idEquiposMoviles, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposMoviles.url + 'actualizarEstadoEquiposMoviles', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarEquiposMoviles").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsEquiposMoviles tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += EquiposMoviles.htmlG;
			$html += "</tr>";

			$('#listaItemsEquiposMoviles tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			EquiposMoviles.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-add-row-equiposMoviles', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsEquiposMoviles tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += EquiposMoviles.htmlEquiposMoviles;
			$html += "</tr>";

			$('#listaItemsEquiposMoviles tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsEquiposMoviles tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('click', '.btneliminarfilaEquiposMoviles', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsEquiposMoviles tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			EquiposMoviles.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-equiposMoviles-pdf', function (e) {
			e.preventDefault();

			let $idEquiposMoviles = $(this).parents('tr').data('id');

			EquiposMoviles.generarRequerimientoPDF($idEquiposMoviles);
		});

		$(document).on('click', '.btn-generarEquiposMoviles', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function(index, value){
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': EquiposMoviles.url + 'formularioGenerarEquiposMoviles', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "EquiposMoviles.registrarEquiposMoviles()", content: "¿Esta seguro de registrar la equiposMoviles? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				EquiposMoviles.actualizarAutocompleteItemsLogistica();
				EquiposMoviles.htmlEquiposMoviles = $('#listaItemsEquiposMoviles tbody tr').html();
				$('#listaItemsEquiposMoviles tbody').html('');
				$(".btn-add-row-equiposMoviles").click();
			});
		});
	},

	registrarEquiposMoviles: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroEquiposMoviles')) };
		let url = EquiposMoviles.url + "registrarEquiposMoviles";
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
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de equiposMoviles. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarEquiposMoviles").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarEquiposMoviles: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionEquiposMoviless')) };
		let config = { 'url': EquiposMoviles.url + 'actualizarEquiposMoviles', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarEquiposMoviles").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(EquiposMoviles.itemServicio[1], function (index, value) {
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
			appendTo: "#modal-page-" + EquiposMoviles.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: EquiposMoviles.itemsLogistica[1],
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
		var url = site_url + '/EquiposMoviles/generarEquiposMovilesPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idEquiposMoviles) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
		formValues.idEquiposMoviles = idEquiposMoviles;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = EquiposMoviles.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idEquiposMoviles + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarEquiposMoviles: function () {
		let formValues = Fn.formSerializeObject('formRegistroEquiposMoviles');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = EquiposMoviles.url + "registrarEquiposMoviles";
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

EquiposMoviles.load();