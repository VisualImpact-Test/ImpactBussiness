var CotizacionEfectiva = {

	frm: 'frm-cotizacionEfectiva',
	contentDetalle: 'idContentCotizacionEfectiva',
	url: 'CotizacionEfectiva/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlCotizacionEfectiva: '',
	tablaCotizacionesOper : '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCotizacionEfectiva').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarCotizacionEfectiva').click();
		});

		$(document).on('click', '#btn-filtrarCotizacionEfectiva', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': CotizacionEfectiva.frm
				, 'url': CotizacionEfectiva.url + ruta
				, 'contentDetalle': CotizacionEfectiva.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarCotizacionEfectiva', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': CotizacionEfectiva.url + 'formularioRegistroCotizacionEfectiva', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					CotizacionEfectiva.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacionEfectiva", fn: "CotizacionEfectiva.registrarCotizacionEfectiva()", content: "¿Esta seguro de registrar este cotizacionEfectiva?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				CotizacionEfectiva.modalIdForm = modalId;

				CotizacionEfectiva.htmlG = $('#listaItemsCotizacionEfectiva tbody tr').html();
				$('#listaItemsCotizacionEfectiva tbody').html('');
				$(".btn-add-row").click();
			});
		});

	

		$(document).on('click', '.btn-detalleCotizacionEfectiva', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCotizacionEfectiva': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': CotizacionEfectiva.url + 'formularioVisualizacionCotizacionEfectiva', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				CotizacionEfectiva.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idcotizacionEfectiva');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': CotizacionEfectiva.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					CotizacionEfectiva.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "CotizacionEfectiva.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				CotizacionEfectiva.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoCotizacionEfectiva', function () {
			++modalId;

			let idCotizacionEfectiva = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idCotizacionEfectiva': idCotizacionEfectiva, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': CotizacionEfectiva.url + 'actualizarEstadoCotizacionEfectiva', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarCotizacionEfectiva").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsCotizacionEfectiva tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += CotizacionEfectiva.htmlG;
			$html += "</tr>";

			$('#listaItemsCotizacionEfectiva tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			CotizacionEfectiva.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-add-row-cotizacionEfectiva', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsCotizacionEfectiva tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += CotizacionEfectiva.htmlCotizacionEfectiva;
			$html += "</tr>";

			$('#listaItemsCotizacionEfectiva tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacionEfectiva tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('click', '.btneliminarfilaCotizacionEfectiva', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacionEfectiva tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			CotizacionEfectiva.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-cotizacionEfectiva-pdf', function (e) {
			e.preventDefault();

			let $idCotizacionEfectiva = $(this).parents('tr').data('id');

			CotizacionEfectiva.generarRequerimientoPDF($idCotizacionEfectiva);
		});

		$(document).on('click', '.btn-generarCotizacionEfectiva', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function(index, value){
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': CotizacionEfectiva.url + 'formularioGenerarCotizacionEfectiva', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "CotizacionEfectiva.registrarCotizacionEfectiva()", content: "¿Esta seguro de registrar la cotizacionEfectiva? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				CotizacionEfectiva.actualizarAutocompleteItemsLogistica();
				CotizacionEfectiva.htmlCotizacionEfectiva = $('#listaItemsCotizacionEfectiva tbody tr').html();
				$('#listaItemsCotizacionEfectiva tbody').html('');
				$(".btn-add-row-cotizacionEfectiva").click();
			});
		});
		$(document).on('click', '.btn-finalizarCotizacion', function () {
			let idCotizacion = $(this).closest('tr').data('id');
			Fn.showConfirm({ idForm: "formRegistroItems", fn: "CotizacionEfectiva.finalizarCotizacion("+idCotizacion+")", content: "¿Esta seguro que quiere finalizar la cotizacion? " });
		});
	},

	finalizarCotizacion: function (idCotizacion) {
		let data = {idCotizacion};
		let jsonString = { 'data': JSON.stringify(data) };
		let url = CotizacionEfectiva.url + "finalizarCotizacion";
		let config = { url: url, data: jsonString };
		
		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacionEfectiva").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarCotizacionEfectiva: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCotizacionEfectiva')) };
		let url = CotizacionEfectiva.url + "registrarCotizacionEfectiva";
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
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de cotizacionEfectiva. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacionEfectiva").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarCotizacionEfectiva: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionCotizacionEfectivas')) };
		let config = { 'url': CotizacionEfectiva.url + 'actualizarCotizacionEfectiva', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacionEfectiva").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(CotizacionEfectiva.itemServicio[1], function (index, value) {
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
			appendTo: "#modal-page-" + CotizacionEfectiva.modalIdForm,
			max: 5,
			minLength: 5,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: CotizacionEfectiva.itemsLogistica[1],
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
		var url = site_url + '/CotizacionEfectiva/generarCotizacionEfectivaPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idCotizacionEfectiva) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
		formValues.idCotizacionEfectiva = idCotizacionEfectiva;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = CotizacionEfectiva.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idCotizacionEfectiva + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarCotizacionEfectiva: function () {
		let formValues = Fn.formSerializeObject('formRegistroCotizacionEfectiva');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = CotizacionEfectiva.url + "registrarCotizacionEfectiva";
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

CotizacionEfectiva.load();