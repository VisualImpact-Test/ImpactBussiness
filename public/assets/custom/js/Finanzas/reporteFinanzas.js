var ReporteFinanzas = {
	frm: 'frm-reporteFinanzas',
	contentDetalle: 'idReporteFinanzas',
	url: 'Finanzas/ReporteFinanzas/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarReporteFinanzas').click();
		});
		$(document).ready(function () {
			$('#btn-filtrarReporteFinanzas').click();
		});
		$(document).on('click', '#btn-filtrarReporteFinanzas', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ReporteFinanzas.frm
				, 'url': ReporteFinanzas.url + ruta
				, 'contentDetalle': ReporteFinanzas.contentDetalle
			};
            console.log(config);
			Fn.loadReporte_new(config);
			//Fn.showLoading(false);
		});

		HTCustom.load();
	},




}

ReporteFinanzas.load();
