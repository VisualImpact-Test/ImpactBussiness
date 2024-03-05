var Tracking = {

	frm: 'frm-tracking',
	contentDetalle: 'idContentTracking',
	url: 'Finanzas/Tracking/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarTracking').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarTracking').click();
		});

		$(document).on('change', '#estadoSustento', function () {
			var _this = $('#estadoSustento').val();
			console.log(_this);
			if (_this == 0) {
				$('#fechSusten').hide();
				$('#fechaSustento').removeAttr("patron");
				$('#elimSusten').show();
				$('#comentarioForm').attr("patron", "requerido");
				
			  } else {
				$('#fechSusten').show();
				$('#comentarioForm').removeAttr("patron");
				$('#elimSusten').hide();
				$('#fechaSustento').attr("patron", "requerido");
			  }

		});

		$(document).on('click', '#btn-filtrarTracking', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Tracking.frm
				, 'url': Tracking.url + ruta
				, 'contentDetalle': Tracking.contentDetalle
			};
			Fn.loadReporte_new(config);
		});

		function exportTableToExcel(filename) {
			var wb = XLSX.utils.table_to_book(document.getElementById('dataTable'), { sheet: "SheetJS" });
			var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
			var blob = new Blob([wbout], { type: 'application/octet-stream' });
			var url = URL.createObjectURL(blob);

			var link = document.createElement('a');
			link.href = url;
			link.download = filename + ".xlsx";
			link.click();
			URL.revokeObjectURL(url);
		}

		$(document).on('click', '#btn-descargarTracking', function () {
			var wb = XLSX.utils.table_to_book(document.getElementById('tb-tracking'), { sheet: "SheetJS" });
			var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
			var blob = new Blob([wbout], { type: 'application/octet-stream' });
			var url = URL.createObjectURL(blob);

			var link = document.createElement('a');
			link.href = url;
			link.download = 'excel' + ".xlsx";
			link.click();
			URL.revokeObjectURL(url);
		});

		$(document).on('click', '.btn-trackingDatosAdicionales', function () {
			let _this = $(this);
			let data = _this.data();
			let idGr = data.idgr;
			let id = data.id;

			let jsonString = { 'idSinceradoGr': idGr, 'idOrdenServicio': id };
			let config = { 'url': Tracking.url + 'formularioTrackingDatosAdicionales', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				++modalId;
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTrackingDatosAdicionales", fn: "Tracking.registrarDatosAdicionales()", content: "¿Esta seguro de registrar los datos indicados?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });
			});
		})
		HTCustom.load();

		$(document).on('click', '.btn-trackingFechaSustento', function () {
			let _this = $(this);
			let data = _this.data();
			let idGr = data.idgr;
			let id = data.id;

			let jsonString = { 'idSinceradoGr': idGr, 'idOrdenServicio': id };
			let config = { 'url': Tracking.url + 'formulariotrackingFechaSustento', 'data': jsonString };
			//console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				++modalId;
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTrackingFechaSustento", fn: "Tracking.registrarFechaSustento()", content: "¿Esta seguro de registrar Fecha de Sustento?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '30%' });
				$('#fechSusten').show();
				$('#comentarioForm').removeAttr("patron");
				$('#elimSusten').hide();
				$('#fechaSustento').attr("patron", "requerido");
			});
		});

	},
	registrarDatosAdicionales: function () {
		let jsonString = Fn.formSerializeObject('formRegistroTrackingDatosAdicionales');
		let url = Tracking.url + "registrarTrackingDatosAdicionales";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTracking").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarFechaSustento: function () {
		let jsonString = Fn.formSerializeObject('formRegistroTrackingFechaSustento');
		let url = Tracking.url + "registrarTrackingFechaSustento";
		let config = { url: url, data: jsonString };
		//console.log(config);
		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTracking").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '30%' });
		});
	}
}

Tracking.load();
