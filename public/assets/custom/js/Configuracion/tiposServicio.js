var TiposServicio = {

	frm: 'frm-tiposServicio',
	contentDetalle: 'idContentTiposServicio',
	url: 'Configuracion/TiposServicio/',
	tipo: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarTiposServicio').click();
		});

		$(document).on('click', '.card-body > ul > li > a', function (e) {
			TiposServicio.tipo = $(this).data('tipo');
			TiposServicio.contentDetalle = TiposServicio.contentDetalle + TiposServicio.tipo;
		});

		$(document).ready(function () {
			$('.card-body > ul > li > a.active').click();
			$('#btn-filtrarTiposServicio').click();
		});

		$(document).on('click', '#btn-filtrarTiposServicio', function () {
			var ruta = 'reporte' + TiposServicio.tipo;
			var config = {
				'idFrm': TiposServicio.frm
				, 'url': TiposServicio.url + ruta
				, 'contentDetalle': TiposServicio.contentDetalle
			};
			Fn.loadReporte_new(config);
		});
		$(document).on('click', '#btn-registrarTiposServicio', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': TiposServicio.url + 'formularioRegistroTiposServicio' + TiposServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTipos", fn: "TiposServicio.registrarTiposServicio()", content: "¿Esta seguro de registrar el tipo de Servicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
		$(document).on('click', '.btn-estadoTipo', function () {
			++modalId;

			let idTipo = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idTipoServicio': idTipo, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': TiposServicio.url + 'actualizarEstadoTipoServicio' + TiposServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarTiposServicio").click();
			});
		});
		$(document).on('click', '.btn-actualizarTiposServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idTipoServicio': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': TiposServicio.url + 'formularioActualizacionTipoServicio' + TiposServicio.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionTiposServicio", fn: "TiposServicio.actualizarTipoServicio()", content: "¿Esta seguro de actualizar el tipo servicio?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
	},
	registrarTiposServicio: function () {
		var costo = $('#costo').data('value');
		var costoVisual = $('#costoVisual').data('value');
		if (costo > 0) {
			$('#costo').val(costo);
		}
		if (costoVisual > 0) {
			$('#costoVisual').val(costoVisual);
		}
		$.when(Fn.validateForm({ id: 'formRegistroTiposServicio' })).then(function (a) {
			if (a === true) {
				let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroTiposServicio')) };
				let url = TiposServicio.url + "registrarTiposServicio" + TiposServicio.tipo;
				let config = { url: url, data: jsonString };

				$.when(Fn.ajax(config)).then(function (b) {
					++modalId;
					var btn = [];
					let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

					if (b.result == 1) {
						fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTiposServicio").click();';
					}

					btn[0] = { title: 'Continuar', fn: fn };
					Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
				});
			}
		});
	},
	actualizarTipoServicio: function () {
		var costo = $('#costo').data('value');
		var costoVisual = $('#costoVisual').data('value');
		if (costo > 0) {
			$('#costo').val(costo);
		}
		if (costoVisual > 0) {
			$('#costoVisual').val(costoVisual);
		}
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionTiposServicio')) };
		let config = { 'url': TiposServicio.url + 'actualizarTipoServicio' + TiposServicio.tipo, 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarTiposServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

TiposServicio.load();
