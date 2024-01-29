var ProveedorDocumento = {
	frm: 'frm-proveedorDocumento',
	contentDetalle: 'idProveedorDocumento',
	url: 'Finanzas/ProveedorDocumento/',
	dataTemporal: null,
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
		$(document).on('click', '.btn-sustentosCargados', function () {
			_this = $(this);
			let tr = _this.closest('tr');

			let id = tr.data('id');
			let flag = tr.data('flag');

			++modalId;
			let jsonString = { 'idOrdenCompra': id, 'flagOcLibre': flag };
			let config = { 'url': ProveedorDocumento.url + 'formularioSustentosCargados', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });
			});
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
		$(document).on('click', '.btn-estadoSustComprobante', function () {
			_this = $(this);
			let data = _this.data();
			ProveedorDocumento.dataTemporal = data;
			ProveedorDocumento.actualizarEstado();
		});
		HTCustom.load();
	},
	actualizarEstado: function (envioDeObservacion = 0) {

		if (envioDeObservacion) ProveedorDocumento.dataTemporal.observacion = $('#observacionDeRechazo').val();
		
		data = ProveedorDocumento.dataTemporal; // Lo declaro como variable y no lo envio a traves de la funciòn debido a que lo vuelvo a necesitar y no se puede enviar entre comillas.
		
		if (data.estado == '1') ProveedorDocumento.dataTemporal.observacion = null;

		++modalId;
		let jsonString = { 'idSustentoAdjunto': data.id, 'flagAprobadoFinanza': data.estado, 'observacionRechazoFinanza': data.observacion };
		let config = { 'url': ProveedorDocumento.url + 'actualizarEstadoSustentoFinanza', 'data': jsonString };

		$.when(Fn.ajax(config)).then((a) => {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };

			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');';
				btn[0] = { title: 'Continuar', fn: fn[0] };
				ProveedorDocumento.dataTemporal = null;
			}

			if (a.result == 2) {
				ProveedorDocumento.dataTemporal.observacion = $('#observacionDeRechazo').val();
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = `Fn.showConfirm({ idForm: "formObservacionSustentoFinanza", fn: "ProveedorDocumento.actualizarEstado(1)", content: "¿Esta seguro de enviar esta observación para el rechazo?" });`;
				btn[1] = { title: 'Registrar', fn: fn[1] };
			}
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '30%' });

		});
	}
}

ProveedorDocumento.load();
