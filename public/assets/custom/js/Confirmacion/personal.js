var Personal = {

	frm: 'frm-personal',
	contentDetalle: 'idContentPersonal',
	url: 'Confirmacion/Personal/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlPersonal: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarPersonal').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarPersonal').click();
		});

		$(document).on('click', '#btn-filtrarPersonal', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Personal.frm
				, 'url': Personal.url + ruta
				, 'contentDetalle': Personal.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarPersonal', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Personal.url + 'formularioRegistroPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Personal.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroPersonal", fn: "Personal.registrarPersonal()", content: "¿Esta seguro de registrar este personal?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Personal.modalIdForm = modalId;

				Personal.htmlG = $('#listaItemsPersonal tbody tr').html();
				$('#listaItemsPersonal tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-detallePersonal', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idPersonal': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Personal.url + 'formularioVisualizacionPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Guardar', fn: fn[0] };
				btn[1] = { title: 'Enviar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Personal.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idpersonal');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Personal.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Personal.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Personal.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Personal.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoPersonal', function () {
			++modalId;

			let idPersonal = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idPersonal': idPersonal, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Personal.url + 'actualizarEstadoPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarPersonal").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsPersonal tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += Personal.htmlG;
			$html += "</tr>";

			$('#listaItemsPersonal tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			Personal.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-add-row-personal', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsPersonal tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += Personal.htmlPersonal;
			$html += "</tr>";

			$('#listaItemsPersonal tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsPersonal tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('click', '.btneliminarfilaPersonal', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsPersonal tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			Personal.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-personal-pdf', function (e) {
			e.preventDefault();

			let $idPersonal = $(this).parents('tr').data('id');

			Personal.generarRequerimientoPDF($idPersonal);
		});

		$(document).on('click', '.btn-generarPersonal', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function(index, value){
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Personal.url + 'formularioGenerarPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Personal.registrarPersonal()", content: "¿Esta seguro de registrar la personal? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Personal.actualizarAutocompleteItemsLogistica();
				Personal.htmlPersonal = $('#listaItemsPersonal tbody tr').html();
				$('#listaItemsPersonal tbody').html('');
				$(".btn-add-row-personal").click();
			});
		});
	},

	registrarPersonal: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroPersonal')) };
		let url = Personal.url + "registrarPersonal";
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
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de personal. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarPersonal").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarPersonal: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionPersonals')) };
		let config = { 'url': Personal.url + 'actualizarPersonal', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarPersonal").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(Personal.itemServicio[1], function (index, value) {
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
			appendTo: "#modal-page-" + Personal.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: Personal.itemsLogistica[1],
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
		var url = site_url + '/Personal/generarPersonalPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idPersonal) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
		formValues.idPersonal = idPersonal;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Personal.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idPersonal + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarPersonal: function () {
		let formValues = Fn.formSerializeObject('formRegistroPersonal');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Personal.url + "registrarPersonal";
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

Personal.load();