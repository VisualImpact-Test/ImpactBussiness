var Presupuesto = {

	frm: 'frm-presupuesto',
	contentDetalle: 'idContentPresupuesto',
	url: 'Presupuesto/',
	articuloServicio: [],
	modalIdForm: 0,

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarPresupuesto').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarPresupuesto').click();
		});

		$(document).on('click', '#btn-filtrarPresupuesto', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Presupuesto.frm
				, 'url': Presupuesto.url + ruta
				, 'contentDetalle': Presupuesto.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarPresupuesto', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Presupuesto.url + 'formularioRegistroPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Presupuesto.articuloServicio = a.data.articuloServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroPresupuesto", fn: "Presupuesto.registrarPresupuesto()", content: "¿Esta seguro de registrar este presupuesto?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Presupuesto.modalIdForm = modalId;

				Presupuesto.htmlG = $('#listaItemsPresupuesto tbody tr').html();
				$('#listaItemsPresupuesto tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-detallePresupuesto', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idPresupuesto': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Presupuesto.url + 'formularioVisualizacionPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Presupuesto.articuloServicio = a.data.articuloServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Presupuesto.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-estadoPresupuesto', function () {
			++modalId;

			let idPresupuesto = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idPresupuesto': idPresupuesto, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Presupuesto.url + 'actualizarEstadoPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarPresupuesto").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsPresupuesto tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += Presupuesto.htmlG;
			$html += "</tr>";

			$('#listaItemsPresupuesto tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			Presupuesto.actualizarAutocomplete();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsPresupuesto tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			Presupuesto.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-presupuesto-pdf', function (e) {
			e.preventDefault();

			let $idPresupuesto = $(this).parents('tr').data('id');

			Presupuesto.generarRequerimientoPDF($idPresupuesto);
		});
	},

	registrarPresupuesto: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroPresupuesto')) };
		let url = Presupuesto.url + "registrarPresupuesto";
		let config = { url: url, data: jsonString };
		let diferencias = 0;

		$.each($('.idTipoArticulo'), function (index, value) {
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
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de presupuesto. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarPresupuesto").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarPresupuesto: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionPresupuestos')) };
		let config = { 'url': Presupuesto.url + 'actualizarPresupuesto', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarPresupuesto").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = $('#tipo').val();
		let articulos = [];
		let nro = 0;
		$.each(Presupuesto.articuloServicio[1], function (index, value) {
			if (tipo == value.tipo || tipo == 3) {
				articulos[nro] = value;
				nro++;
			}
		});
		$(".items").autocomplete({
			source: articulos,
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los articulos con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".ui-widget").find(".codArticulos").val(ui.item.value);

				//Llenamos el precio actual
				if (ui.item.costo == null) {
					ui.item.costo = 0;
				}
				$(this).parents(".nuevo").find(".costoForm").val(ui.item.costo);

				//Llenamos el estado
				$(this).parents(".nuevo").find(".estadoItemForm").text('EN SISTEMA');
				$(this).parents(".nuevo").find(".idEstadoItemForm").val(1);
				$(this).parents(".nuevo").find(".idTipoArticulo").val(ui.item.tipo);

				//Validacion ID

				let $cod = $(this).parents(".ui-widget").find(".codArticulos").val();
				if ($cod != '') {
					$(this).attr('readonly', 'readonly');
					$(this).parents('.nuevo').find('.costoForm').attr('readonly', 'readonly');
				}
			},
			appendTo: "#modal-page-" + Presupuesto.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	generarRequerimientoPDF: function (id) {
		var url = site_url + '/Presupuesto/generarPresupuestoPDF/' + id;
		window.open(url, '_blank');
	},
}

Presupuesto.load();