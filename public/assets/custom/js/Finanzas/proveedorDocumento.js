var ProveedorDocumento = {
	frm: 'frm-proveedorDocumento',
	contentDetalle: 'idProveedorDocumento',
	url: 'Finanzas/ProveedorDocumento/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedorDocumento').click();
		});
		$(document).ready(function () {
			$('#btn-filtrarProveedorDocumento').click();
		});
		$(document).on('click', '#btn-filtrarProveedorDocumento', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ProveedorDocumento.frm
				, 'url': ProveedorDocumento.url + ruta
				, 'contentDetalle': ProveedorDocumento.contentDetalle
			};
			Fn.loadReporte_new(config);
		});
		$(document).on('click', '#btn-descargarExcelProveedorDocumento', function () {
			_this = $(this);
			let ruta = _this.data('ruta');
			let data = Fn.formSerializeObject(ProveedorDocumento.frm);
			var url = '../' + ProveedorDocumento.url + ruta;

			$.when(Fn.download(url, data)).then(function (a) {
				Fn.showLoading(false);
			});
		});
		HTCustom.load();
	}
}

ProveedorDocumento.load();
