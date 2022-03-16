var Cotizacion = {

	frm: 'frm-cotizacion',
	contentDetalle: 'idContentCotizacion',
	url: 'Cotizacion/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlCotizacion: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCotizacion').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarCotizacion').click();
		});

		$(document).on('click', '#btn-filtrarCotizacion', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Cotizacion.frm
				, 'url': Cotizacion.url + ruta
				, 'contentDetalle': Cotizacion.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarCotizacion', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Cotizacion.url + 'formularioRegistroCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(1)", content: "¿Esta seguro de registrar esta cotizacion?" });';
				btn[1] = { title: 'Guardar <i class="fas fa-save"></i>', fn: fn[1] };
				fn[2] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(2)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });';
				btn[2] = { title: 'Enviar <i class="fas fa-paper-plane"></i>', fn: fn[2] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Cotizacion.modalIdForm = modalId;

				Cotizacion.htmlG = $('#listaItemsCotizacion tbody tr').html();
				$('#listaItemsCotizacion tbody').html('');
				$(".btn-add-row").click();
			});
		});

		$(document).on('click', '.btn-detalleCotizacion', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCotizacion': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioVisualizacionCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Cotizacion.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idcotizacion');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Cotizacion.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Cotizacion.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoCotizacion', function () {
			++modalId;

			let idCotizacion = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idCotizacion': idCotizacion, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'actualizarEstadoCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarCotizacion").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsCotizacion tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo nuevoItem'><td class='n_fila' ><label class='nfila'>" + $filas + "</label><i class='estadoItemForm fa fa-sparkles' style='color: teal;'></i></td>";
			$html += Cotizacion.htmlG;
			$html += "</tr>";

			$('#listaItemsCotizacion tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			Cotizacion.actualizarAutocomplete();
			$("#div-ajax-detalle").animate({ scrollTop: $("#listaItemsCotizacion").height() }, 500);
		});

		$(document).on('click', '.btn-add-row-cotizacion', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsCotizacion tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += Cotizacion.htmlCotizacion;
			$html += "</tr>";

			$('#listaItemsCotizacion tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
				$(this).find('.nfila').text(Number(index) + 1);
			});

			Cotizacion.actualizarTotal();
		});

		$(document).on('click', '.btneliminarfilaCotizacion', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			Cotizacion.actualizarAutocomplete();
		});

		$(document).on('click', '.btn-cotizacion-pdf', function (e) {
			e.preventDefault();

			let $idCotizacion = $(this).parents('tr').data('id');

			Cotizacion.generarRequerimientoPDF($idCotizacion);
		});

		// $(document).on('click', '.btn-generarCotizacion', function () {
		// 	++modalId;

		// 	let items = [];
		// 	$.each($(this).parents('.row').find('.item'), function (index, value) {
		// 		items.push($(value).val());
		// 	});
		// 	let data = { 'items': items };
		// 	let jsonString = { 'data': JSON.stringify(data) };
		// 	let config = { 'url': Cotizacion.url + 'formularioGenerarCotizacion', 'data': jsonString };

		// 	$.when(Fn.ajax(config)).then((a) => {
		// 		let btn = [];
		// 		let fn = [];

		// 		fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
		// 		btn[0] = { title: 'Cerrar', fn: fn[0] };
		// 		fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Cotizacion.registrarCotizacion()", content: "¿Esta seguro de registrar la cotizacion? " });';
		// 		btn[1] = { title: 'Registrar', fn: fn[1] };

		// 		Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

		// 		Cotizacion.actualizarAutocompleteItemsLogistica();
		// 		Cotizacion.htmlCotizacion = $('#listaItemsCotizacion tbody tr').html();
		// 		$('#listaItemsCotizacion tbody').html('');
		// 		$(".btn-add-row-cotizacion").click();
		// 	});
		// });

		$(document).on('keyup', '.cantidadForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');

			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');

			let cantidad = Number(thisControl.val());
			let costo = Number(costoForm.val());

			let subTotal = Fn.multiply(cantidad, costo);

			subTotalForm.val(subTotal);
			subTotalFormLabel.text(subTotal);

			Cotizacion.actualizarTotal();
		});
	},

	// registrarCotizacion: function () {
	// 	let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCotizacion')) };
	// 	let url = Cotizacion.url + "registrarCotizacion";
	// 	let config = { url: url, data: jsonString };
	// 	let diferencias = 0;

	// 	$.each($('.idTipoItem'), function (index, value) {
	// 		if ($(value).val() != '' && $('#tipo').val() != 3) {
	// 			if ($(value).val() != $('#tipo').val()) {
	// 				$(value).parents('.nuevo').find('.ui-widget').addClass('has-error');

	// 				diferencias++;
	// 			}
	// 		}
	// 	});

	// 	if (diferencias > 0) {
	// 		++modalId;
	// 		var btn = [];
	// 		let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
	// 		btn[0] = { title: 'Continuar', fn: fn };
	// 		Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de cotizacion. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

	// 		return false;
	// 	}

	// 	$.when(Fn.ajax(config)).then(function (b) {
	// 		++modalId;
	// 		var btn = [];
	// 		let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

	// 		if (b.result == 1) {
	// 			fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
	// 		}

	// 		btn[0] = { title: 'Continuar', fn: fn };
	// 		Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
	// 	});
	// },

	actualizarCotizacion: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionCotizacions')) };
		let config = { 'url': Cotizacion.url + 'actualizarCotizacion', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(Cotizacion.itemServicio[1], function (index, value) {
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
				$(this).parents(".nuevo").find(".costoFormLabel").text(ui.item.costo);

				//Llenamos el estado
				$(this).parents(".nuevo").find(".estadoItemForm").removeClass('fa-sparkles');
				$(this).parents(".nuevo").removeClass('nuevoItem');
				$(this).parents(".nuevo").find(".idEstadoItemForm").val(1);
				$(this).parents(".nuevo").find(".idTipoItem").val(ui.item.tipo);

				//Llenamos el proveedor
				$(this).parents(".nuevo").find(".proveedorForm").text(ui.item.proveedor);
				$(this).parents(".nuevo").find(".idProveedor").val(ui.item.idProveedor);

				//LLenar semaforo

				$(this).parents(".nuevo").find(".semaforoForm").addClass('semaforoForm-' + ui.item.semaforoVigencia);

				//Validacion ID

				let $cod = $(this).parents(".ui-widget").find(".codItems").val();
				if ($cod != '') {
					$(this).attr('readonly', 'readonly');
					$(this).parents('.nuevo').find('.costoForm').attr('readonly', 'readonly');
				}
			},
			appendTo: "#modal-page-" + Cotizacion.modalIdForm,
			max: 5,
			minLength: 3,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: Cotizacion.itemsLogistica[1],
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
			minLength: 3,
		});
	},

	generarRequerimientoPDF: function (id) {
		var url = site_url + '/Cotizacion/generarCotizacionPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idCotizacion) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
			formValues.idCotizacion = idCotizacion;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idCotizacion + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarCotizacion: function (tipoRegistro = 1) {
		let formValues = Fn.formSerializeObject('formRegistroCotizacion');
			formValues.tipoRegistro = tipoRegistro;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "registrarCotizacion";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarTotal: function () {
		let total = 0;
		$.each($('.subtotalForm'), function (index, value) {
			total = Number(total) + Number($(value).val());
		})

		$('.totalForm').val(total);
		$('.totalFormLabel').text(total);
	}
}

Cotizacion.load();