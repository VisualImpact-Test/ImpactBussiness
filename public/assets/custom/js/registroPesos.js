var registroPesos = {
	selectDefault: '',
	load: function () {
		$(document).ready(function () {
			registroPesos.selectDefault = $('#baseSelect').html();
		});
		$(document).on('click', '#btnEnviar', function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: 'formRegistroCostoPacking' })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCostoPacking')) };
					let url = "Cotizacion/guardarPesoPacking";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						if (b.result == 1) {
							fn = 'Fn.showModal({ id:' + modalId + ',show:false }); location.reload();';
						}

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
		$(document).on('click', '#btn-registroPeso', function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: 'formRegistroPeso' })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroPeso')) };
					let url = "Item/guardarPesoItemLogistica";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						if (b.result == 1) {
							fn = 'Fn.showModal({ id:' + modalId + ',show:false }); location.reload();';
						}

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
	},
	addItem: function (t) { 
		tt = $(t).closest('.areaT').html();
		$(t).closest('.scheduler-border').append(tt);
	},
	calcularTotal: function (t) {
		tt = $(t).closest('.scheduler-border').find('.costo');
		total = 0;
		for (let i = 0; i < tt.length; i++) {
			el = tt[i];
			total += parseFloat($(el).val());
		}
		$('#valorTotal').val((total).toFixed(2));
	}



}
registroPesos.load();
