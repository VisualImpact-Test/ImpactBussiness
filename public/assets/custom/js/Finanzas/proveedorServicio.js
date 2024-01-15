var ProveedorServicio = {
	frm: 'frm-proveedorServicio',
	contentDetalle: 'idProveedorServicio',
	url: 'Finanzas/ProveedorServicio/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedorServicio').click();
		});
		$(document).ready(function () {
			$('#btn-filtrarProveedorServicio').click();
		});
		$(document).on('click', '#btn-filtrarProveedorServicio', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ProveedorServicio.frm
				, 'url': ProveedorServicio.url + ruta
				, 'contentDetalle': ProveedorServicio.contentDetalle
			};
			// console.log(config);
			Fn.loadReporte_new(config);
			Fn.showLoading(false);
		});

		$(document).on('click', '.btn-actualizar-estado', function () {
			++modalId;

			let idProveedorServicio = $(this).data('id');
			let estado = $(this).data('estado');
			let data = { 'idProveedorServicio': idProveedorServicio, 'estado': estado };

			console.log(JSON.stringify(data));
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ProveedorServicio.url + 'actualizarEstadoProveedorServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarProveedorServicio").click();
			});
		});

		$(document).on("change", ".chkMontoFijo", function () {
			let this_ = $(this);
			let check = this_.is(':checked');
			if (check) {
				$('.fijo').removeClass('d-none');
				$('.onlyNumbers').attr('patron', 'requerido')
			} else {
				$('.fijo').addClass('d-none');
				$('.onlyNumbers').removeAttr('patron')
			}

		});

		$(document).on('click', '#btn-registrarProveedorServicioPago', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': ProveedorServicio.url + 'formularioRegistroProveedorServicioPago', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedorServicioPago", fn: "ProveedorServicio.registrarProveedorServicioPago()", content: "¿Esta seguro de registrar el pago?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
			});
		});

		$(document).on('click', '.btn-editar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idProveedorServicioPago': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ProveedorServicio.url + 'formularioActualizacionProveedorServicioPago', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionProveedores", fn: "Proveedor.actualizarProveedor()", content: "¿Esta seguro de actualizar el proveedor?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
				Proveedor.bancos = a.data.bancos;
				Proveedor.tiposCuentaBanco = a.data.tiposCuentaBanco;
				Proveedor.divInfoBancData = '<div class="row InfoBancData">' + $('#divInfoBancData').html() + '</div>';
			});

		});

		$(document).on("keypress", "#diaPago", function (event) {
			var value = $(this).val();
			var key = event.which || event.keyCode;
			var dia = value + String.fromCharCode(key);
			if (isNaN(dia) === true || dia < 1 || dia > 31) {
				return false;
			}
			return true;
		});

		HTCustom.load();
	},

	registrarProveedorServicioPago: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedorServicioPago')) };
		let url = ProveedorServicio.url + "registrarProveedorServicioPago";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
}

ProveedorServicio.load();
