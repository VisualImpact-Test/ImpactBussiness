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
		$(document).on('click', '.btn-pagoGenerado', function () {
			var idPagoGenerado = $(this).data("id");

			let data = { 'idPagoGenerado': idPagoGenerado};

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'formularioRegistrarPago', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistrarPagoGenerado", fn: "PagosGenerados.registrarPagoGenerado()", content: "¿Esta seguro de registrar pago?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
		
			});
		});

		$(document).on('click', '.btn-registrarFacturas', function () {
			var idPagoGenerado = $(this).data("id");

			let data = { 'idPagoGenerado': idPagoGenerado};

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
		
		$(document).on('change', '#cboCuenta', function () {
			$('#divCargo').find('.fields').remove();
			if ($(this).val()) {
				$('#btn-addCargo').removeClass('disabled');
			} else {
				$('#btn-addCargo').addClass('disabled');
			}
		});


		$(document).on('click', '#new-factura', function () {

			let data = { 'idPagoGenerado': '1'};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': PagosGenerados.url + 'addNewFactura', 'data': jsonString };
			// console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				// console.log(a);
				 $('#agregar-factura').append(a.data.html);
				 Fn.loadSemanticFunctions();
			});
		});	


		//porcentajeDetraccion
		$(document).on('keyup', '#monto', function () {
			var monto =$('#monto').val();
			var porcentajeDetraccion =$('#porcentajeDetraccion').val();
			montFinal = ( monto * porcentajeDetraccion ) /100
			//console.log(montFinal);
			$('#montoDetraccion').val(montFinal);
		});
		$(document).on('keyup', '#porcentajeDetraccion', function () {
			var monto =$('#monto').val();
			var porcentajeDetraccion =$('#porcentajeDetraccion').val();
			montFinal = ( monto * porcentajeDetraccion ) /100
			//console.log(montFinal);
			$('#montoDetraccion').val(montFinal);
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

}

PagosGenerados.load();
