var ArticulosServicios = {

	frm: 'frm-articulosServicios',
	contentDetalle: 'idContentArticulosServicios',
	url: 'Confirmacion/ArticulosServicios/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlArticulosServicios: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarArticulosServicios').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarArticulosServicios').click();
		});

		$(document).on('click', '#btn-filtrarArticulosServicios', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ArticulosServicios.frm
				, 'url': ArticulosServicios.url + ruta
				, 'contentDetalle': ArticulosServicios.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarArticulosServicios', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': ArticulosServicios.url + 'formularioRegistroArticulosServicios', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					ArticulosServicios.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroArticulosServicios", fn: "ArticulosServicios.registrarArticulosServicios()", content: "¿Esta seguro de registrar este articulosServicios?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				ArticulosServicios.modalIdForm = modalId;

				ArticulosServicios.htmlG = $('#listaItemsArticulosServicios tbody tr').html();
				$('#listaItemsArticulosServicios tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-generarCotizacionProveedor', function () {
			++modalId;

			let id = $(this).data('id');
			let data = { 'idArticulosServicios': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ArticulosServicios.url + 'formularioRegistroCotizacionProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroArticulosServicios", fn: "ArticulosServicios.registrarArticulosServicios()", content: "¿Esta seguro de registrar este articulosServicios?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });
			});
		});

		$(document).on('click', '.btn-detalleArticulosServicios', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idArticulosServicios': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ArticulosServicios.url + 'formularioVisualizacionArticulosServicios', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Guardar', fn: fn[0] };
				btn[1] = { title: 'Enviar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				ArticulosServicios.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idarticulosServicios');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ArticulosServicios.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					ArticulosServicios.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "ArticulosServicios.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				ArticulosServicios.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoArticulosServicios', function () {
			++modalId;

			let idArticulosServicios = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idArticulosServicios': idArticulosServicios, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ArticulosServicios.url + 'actualizarEstadoArticulosServicios', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarArticulosServicios").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsArticulosServicios tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += ArticulosServicios.htmlG;
			$html += "</tr>";

			$('#listaItemsArticulosServicios tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			ArticulosServicios.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-add-row-articulosServicios', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsArticulosServicios tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += ArticulosServicios.htmlArticulosServicios;
			$html += "</tr>";

			$('#listaItemsArticulosServicios tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsArticulosServicios tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('click', '.btneliminarfilaArticulosServicios', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsArticulosServicios tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			ArticulosServicios.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-articulosServicios-pdf', function (e) {
			e.preventDefault();

			let $idArticulosServicios = $(this).parents('tr').data('id');

			ArticulosServicios.generarRequerimientoPDF($idArticulosServicios);
		});

		$(document).on('click', '.btn-generarArticulosServicios', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function(index, value){
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ArticulosServicios.url + 'formularioGenerarArticulosServicios', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "ArticulosServicios.registrarArticulosServicios()", content: "¿Esta seguro de registrar la articulosServicios? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				ArticulosServicios.actualizarAutocompleteItemsLogistica();
				ArticulosServicios.htmlArticulosServicios = $('#listaItemsArticulosServicios tbody tr').html();
				$('#listaItemsArticulosServicios tbody').html('');
				$(".btn-add-row-articulosServicios").click();
			});
		});
	},

	registrarArticulosServicios: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroArticulosServicios')) };
		let url = ArticulosServicios.url + "registrarArticulosServicios";
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
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de articulosServicios. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarArticulosServicios").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarArticulosServicios: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionArticulosServicioss')) };
		let config = { 'url': ArticulosServicios.url + 'actualizarArticulosServicios', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarArticulosServicios").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(ArticulosServicios.itemServicio[1], function (index, value) {
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
			appendTo: "#modal-page-" + ArticulosServicios.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: ArticulosServicios.itemsLogistica[1],
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
		var url = site_url + '/ArticulosServicios/generarArticulosServiciosPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idArticulosServicios) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
		formValues.idArticulosServicios = idArticulosServicios;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = ArticulosServicios.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idArticulosServicios + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarArticulosServicios: function () {
		let formValues = Fn.formSerializeObject('formRegistroArticulosServicios');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = ArticulosServicios.url + "registrarArticulosServicios";
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

ArticulosServicios.load();