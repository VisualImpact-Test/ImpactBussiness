var Sincerado = {
	frm: 'frm-sincerado',
	contentDetalle: 'idContentSincerado',
	url: 'Sincerado/',
	flagMontoFijado: 0,
	load: function () {
		$(document).ready(function () {
			$('#btn-filtrarSincerado').click();
		});
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarSincerado').click();
		});
		$(document).on('click', '#btn-filtrarSincerado', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Sincerado.frm
				, 'url': Sincerado.url + ruta
				, 'contentDetalle': Sincerado.contentDetalle
			};
			Fn.loadReporte_new(config);
		});
		$(document).on('click', '#btn-registrarSincerado', function () {
			++modalId;
			let jsonString = { 'data': '' };
			let config = { 'url': Sincerado.url + 'formularioListaParaSincerar', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// fn[1] = 'Fn.showConfirm({ idForm: "formRegistroSincerado", fn: "Sincerado.registrarSincerado()", fnFin: "Sincerado.validarCheckbox()", content: "¿Esta seguro de registrar la Orden de Servicio?" });';
				// btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				Fn.loadSemanticFunctions();
			});
		});
		$(document).on('click', '.btn-sincerar', function () {
			let _this = $(this);
			let idPresupuestoValido = _this.closest('tr').data('id');

			++modalId;
			let jsonString = { 'idPresupuestoValido': idPresupuestoValido };
			let config = { 'url': Sincerado.url + 'formularioFechasSincerado', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formFechaSincerado", fn: "Sincerado.buscarFechaSincerado(' + idPresupuestoValido + ')" ,content: "¿Esta seguro de ver esa fecha?" });';
				// fn[1] = 'Sincerado.buscarFechaSincerado(' + idPresupuestoValido + ');';
				btn[1] = { title: 'Consultar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '20%' });
			});

		});

		$(document).on('click', '.btn-valoresFijosSincerado', function () {
			$('.copyFijarMonto').each(function () {
				let v = $(this).val();
				let tr = $(this).closest('tr');
				tr.find('.pasteFijarMonto').val(v);
			});
		});

		$(document).on('click', '#btn-descargarExcelSincerado', function () {
			_this = $(this);
			let idSincerado = _this.closest('tr').data('id');
			let ruta = _this.data('ruta');
			let data = { 'data': idSincerado};
			var url = Sincerado.url + ruta;
			
			// console.log(idSincerado);
			// console.log(data);
			// console.log(url);
			$.when(Fn.download(url, data)).then(function (a) {
				Fn.showLoading(false);
			});
		});

	},
	buscarFechaSincerado: function (idPresupuestoValido) {

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formFechaSincerado')) };
		let config = { 'url': Sincerado.url + 'formularioRegistrarSincerado', 'data': jsonString };
		$.when(Fn.ajax(config)).then((a) => {
			let btn = [];
			let fn = [];
			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };
			fn[1] = 'Fn.showConfirm({ idForm: "formRegistroSincerado", fn: "Sincerado.registrarSincerado()" ,content: "¿Esta seguro de ver esa fecha?" });';
			btn[1] = { title: 'Registrar', fn: fn[1] };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '95%' });
			// Lo que traje del JS de Orden de servicio
			OrdenServicio.arrayFechas = a.data.fechas;
			OrdenServicio.arrayTipoPresupuestoDetalle = a.data.tipoPresupuestoDetalle;
			OrdenServicio.arrayCargo = a.data.cargo;
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			$('.tabular.menu .item').tab();
			Fn.loadSemanticFunctions();

			td = $('td.cantidadDeTabla');
			for (let i = 0; i < td.length; i++) {
				$(td[i]).find('.ui.action.input').find('input').trigger('change');
			}
			$('.tabTiposPresupuestos').removeClass('disabled');
			$("#calculateTablaSueldo").click();
			OrdenServicio.calcularTotalesMovilidad();
			$('#tablaSueldoAdicional tbody tr:first').find('.movilidadSueldoAdicional').change();
			$('#tablaAlmacenMonto tbody tr').find('select').change();
			Sincerado.flagMontoFijado = 0;
			setTimeout(function () {
				$('.btn-valoresFijosSincerado').click();
			}, 2000);
		});
	},
	registrarSincerado: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroSincerado')) };
		let config = { 'url': Sincerado.url + 'registrarSincerado', 'data': jsonString };
		//console.log(config);
		$.when(Fn.ajax(config)).then((b) => {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarSincerado").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	}
}
Sincerado.load();
