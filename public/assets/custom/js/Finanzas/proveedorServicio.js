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

		HTCustom.load();
	}
}

ProveedorServicio.load();
