var PagosGenerados = {
	frm: 'frm-pagosGenerados',
	contentDetalle: 'idPagosGenerados',
	url: 'Finanzas/PagosGenerados/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarPagosGenerados').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarPagosGenerados').click();
		});
		$(document).on('click', '#btn-filtrarPagosGenerados', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': PagosGenerados.frm
				, 'url': PagosGenerados.url + ruta
				, 'contentDetalle': PagosGenerados.contentDetalle
			};
			Fn.loadReporte_new(config);
			//Fn.showLoading(false);
		});

		$(document).on('click', '#btn-nuevoPago', function () {
			// console.log("hola");
			let data = { 'idPagoGenerado': 1 };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'formularioRegistrarNuevoPago', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistrarNuevoPago", fn: "PagosGenerados.registrarPagoLibre()", content: "¿Esta seguro de registrar pago?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
			});
		});

		$(document).on('click', '.btn-registrarPagos', function () {
			var idPagoGenerado = $(this).data("id");

			let data = { 'idPagoGenerado': idPagoGenerado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'formularioRegistrarPago', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// fn[1] = 'Fn.showConfirm({ idForm: "formRegistrarPagoGenerado", fn: "PagosGenerados.registrarPagoGenerado()", content: "¿Esta seguro de registrar pago?" });';
				// btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
			});
		});

		$(document).on('click', '.btn-registrarFacturas', function () {
			var idPagoGenerado = $(this).data("id");

			let data = { 'idPagoGenerado': idPagoGenerado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'formularioRegistrarFactura', 'data': jsonString };
			// console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistrarFactura", fn: "PagosGenerados.registrarfacturas()", content: "¿Esta seguro de registrar factura?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
			});
		});

		$(document).off('click', '.option-semantic-delete').on('click', '.option-semantic-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			let content = control.closest('.contentSemanticDiv');
			let parent = $(this).closest(".content-lsck-capturas");
			let idAdjuntoEliminado = parent.data('idordencompraadjunto');
			if (idAdjuntoEliminado) {
				Oc.archivoEliminado.push(idAdjuntoEliminado);
			}
			control.parents('.content-lsck-capturas:first').remove();

		});

		$(document).on('click', '.btn-registrarNotaCredito', function () {
			var idPagoGenerado = $(this).data("id");

			let data = { 'idPagoGenerado': idPagoGenerado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'formularioRegistrarNotaCredito', 'data': jsonString };
			// console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// fn[1] = 'Fn.showConfirm({ idForm: "formRegistrarNotaCredito", fn: "PagosGenerados.registrarNotaCredito()", content: "¿Esta seguro de registrar factura?" });';
				// btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
			});
		});

		$(document).on('click', '#new-notaCredito', function () {
			var id = $(this).data("id");
			var montoSinFormato = $('#monto_' + id).data('value');
			if (montoSinFormato > 0) {
				$('#monto_' + id).val(montoSinFormato);
			}
			var fechaEmision = $('input[name="fechaEmision_' + id + '"]').val();
			var fechaRecepcion = $('input[name="fechaRecepcion_' + id + '"]').val();
			var tipoNota = $('select[name="tipoNota_' + id + '"]').val();
			var numNota = $('input[name="numNota_' + id + '"]').val();
			var monto = $('input[name="monto_' + id + '"]').val();
			var item = $('input[name="' + id + '_cuentaPrincipalFile-item"]').val();
			var type = $('input[name="' + id + '_cuentaPrincipalFile-type"]').val();
			var name = $('input[name="' + id + '_cuentaPrincipalFile-name"]').val();

			let data = {
				'idServicioPagoComprobante': id, 'fechaEmision': fechaEmision, 'fechaRecepcion': fechaRecepcion, 'tipoNota': tipoNota
				, 'numNota': numNota, 'monto': monto, 'item': item, 'type': type, 'name': name
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'guardarNotaCredito', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				modalId++;
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			});

		});
		$(document).on('change', '#cboCuenta', function () {
			$('#divCargo').find('.fields').remove();
			var id = $(this).data("cuentap");
			//console.log(id);
			if ($(this).val()) {
				$('#btn-addCargo').closest('').removeClass('disabled');
			} else {
				$('#btn-addCargo').addClass('disabled');
			}
		});


		$(document).on('click', '.elimnaRegistro', function () {
			$(this).closest('.registroNewFactura').remove();
		});



		$(document).on('click', '#new-factura', function () {

			let data = { 'idPagoGenerado': '1' };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'addNewFactura', 'data': jsonString };
			// console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				// console.log(a);
				$('#agregar-factura').append(a.data.html);
				Fn.loadSemanticFunctions();
			});
		});


		$(document).on('click', '#new-RegistrarPago', function () {
			var id = $(this).data("id");
			var montoSinFormato = $('#monto_P' + id).data('value');
			var montoDetraccionSinFormato = $('#montoDetraccion_P' + id).data('value');
			if (montoSinFormato > 0) {
				$('#monto_P' + id).val(montoSinFormato);
			}
			if (montoDetraccionSinFormato > 0) {
				$('#montoDetraccion_P' + id).val(montoDetraccionSinFormato);
			}
			var metodoPago = $('select[name="metodoPago_P' + id + '"]').val();
			var numeroComprobante = $('input[name="numeroComprobante_P' + id + '"]').val();
			var fechaPagoComprobante = $('input[name="fechaPagoComprobante_P' + id + '"]').val();
			var cuenta = $('[data-cuentap="cuenta_p' + id + '"]').val();
			var centro = $('[data-centrop="centro_p' + id + '"]').val();
			var montoDetraccion = $('input[name="montoDetraccion_P' + id + '"]').val();
			var porcentajeDetraccion = $('input[name="porcentajeDetraccion_P' + id + '"]').val();
			var monto = $('input[name="monto_P' + id + '"]').val();
			var item = $('input[name="' + id + '_cuentaPrincipalPagoFile-item"]').val();
			var type = $('input[name="' + id + '_cuentaPrincipalPagoFile-type"]').val();
			var name = $('input[name="' + id + '_cuentaPrincipalPagoFile-name"]').val();
			let data = {
				'idServicioPagoComprobante': id,
				'idMetodoPago': metodoPago,
				'numeroComprobante': numeroComprobante,
				'fechaPagoComprobante': fechaPagoComprobante,
				'cuenta': cuenta,
				'centro': centro,
				'montoDetraccion': montoDetraccion,
				'porcentajeDetraccion': porcentajeDetraccion,
				'monto': monto,
				'item': item,
				'type': type,
				'name': name
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'RegistrarPagoNew', 'data': jsonString };
			//console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				modalId++;
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			});
		});


		$(document).on('keyup', '.monto', function () {
			var monto = $('#monto_M').data('value');
			var porcentajeDetraccion = $('#porcentajeDetraccion_M').val();
			var montFinal = (monto * porcentajeDetraccion) / 100;
			$('#montoDetraccion_M').val(montFinal);
		});
		$(document).on('keyup', '.porcentaje', function () {
			var monto = $('#monto_M').data('value');
			var porcentajeDetraccion = $('#porcentajeDetraccion_M').val();
			var montFinal = (monto * porcentajeDetraccion) / 100;
			$('#montoDetraccion_M').val(montFinal);
		});

		//porcentajeDetraccion
		$(document).on('keyup', '.monto', function () {
			var id = $(this).data("id");
			var monto = $('#monto_P' + id).data('value');
			var porcentajeDetraccion = $('#porcentajeDetraccion_P' + id).val();
			var montFinal = (monto * porcentajeDetraccion) / 100;
			if (montFinal != "") {
				var monto = parseFloat(montFinal)/* / 100*/;
				$('#montoDetraccion_P' + id).data('value', monto);
				$('#montoDetraccion_P' + id).val(monto.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			}
			//$('#montoDetraccion_P' + id).val(montFinal);
		});
		$(document).on('keyup', '.porcentaje', function () {
			var id = $(this).data("id");
			var monto = $('#monto_P' + id).data('value');
			var porcentajeDetraccion = $('#porcentajeDetraccion_P' + id).val();
			var montFinal = (monto * porcentajeDetraccion) / 100;
			if (montFinal != "") {
				var monto = parseFloat(montFinal)/* / 100*/;
				$('#montoDetraccion_P' + id).data('value', monto);
				$('#montoDetraccion_P' + id).val(monto.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"));
			}
			//$('#montoDetraccion_P' + id).val(montFinal);
		});
		HTCustom.load();
	},


	registrarPagoGenerado: function () {
		++modalId;
		var dataFn = Fn.formSerializeObject('formRegistrarPagoGenerado');
		let jsonString = { 'data': JSON.stringify(dataFn) };
		let config = { 'url': PagosGenerados.url + 'registrarPagoGenerado', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.closeModals(10);';
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			$('#btn-filtrarPagosGenerados').click();
		});
	},
	registrarfacturas: function () {
		var montoTotal = $('#montoTotal').data('value');
		if (montoTotal > 0) {
			$('#montoTotal').val(montoTotal);
		}
		var monto_reg = $('#monto_reg').data('value');
		if (monto_reg > 0) {
			$('#monto_reg').val(monto_reg);
		}
		++modalId;
		var dataFn = Fn.formSerializeObject('formRegistrarFactura');
		let jsonString = { 'data': JSON.stringify(dataFn) };
		let config = { 'url': PagosGenerados.url + 'formRegistrarFactura', 'data': jsonString };
		// console.log(config);
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.closeModals(10);';
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			$('#btn-filtrarPagosGenerados').click();
		});
	},
	registrarNotaCredito: function () {
		++modalId;
		var dataFn = Fn.formSerializeObject('formRegistrarNotaCredito');
		let jsonString = { 'data': JSON.stringify(dataFn) };
		let config = { 'url': PagosGenerados.url + 'formRegistrarNotaCredito', 'data': jsonString };
		// console.log(config);
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.closeModals(10);';
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			$('#btn-filtrarPagosGenerados').click();
		});
	},

	registrarPagoLibre: function () {
		var monto = $('#monto_M').data('value');
		var montoDetraccion = $('#montoDetraccion_M').data('value');
		if(monto > 0) {
			$('#monto_M').val(monto);
		}
		if(montoDetraccion > 0) {
			$('#montoDetraccion_M').val(montoDetraccion);
		}
		++modalId;
		var dataFn = Fn.formSerializeObject('formRegistrarNuevoPago');
		let jsonString = { 'data': JSON.stringify(dataFn) };
		let config = { 'url': PagosGenerados.url + 'registrarPagoLibre', 'data': jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.closeModals(10);';
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			$('#btn-filtrarPagosGenerados').click();
		});
	},

}

PagosGenerados.load();
