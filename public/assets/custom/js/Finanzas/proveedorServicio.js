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
	
		HTCustom.load();
	}
}

ProveedorServicio.load();
