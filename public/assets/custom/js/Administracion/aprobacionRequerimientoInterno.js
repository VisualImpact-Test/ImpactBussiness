var AprobacionRequerimientoInterno = {
	url: 'Administracion/AprobacionRequerimientoInterno/',
	frm: 'frmRequerimientoInterno',
	contentDetalle: 'idContentRequerimientoInterno',
	htmlG: '',
	nDetalle: 1,
	modalIdForm: 0,
	objetoParaAgregarImagen: null,
	detalleEliminado: [],
	itemServicio: [],
	anexoEliminado: [],

	load: function () {
		$(document).ready(function () {
			$('#btn-filtrarRequerimientoInterno').click();

			Fn.loadSemanticFunctions();
			Fn.loadDimmerHover();
			$('.simpleDropdown').dropdown();
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
		});
		$(document).on("click", "#btn-filtrarRequerimientoInterno", () => {
			var ruta = 'reporte';
			var config = {
				'idFrm': AprobacionRequerimientoInterno.frm
				, 'url': AprobacionRequerimientoInterno.url + ruta
				, 'contentDetalle': AprobacionRequerimientoInterno.contentDetalle
			};

			Fn.loadReporte_new(config);
		});
		$(document).on("click", ".btn-viewSolicitudRequerimientoInterno", function (e) {
			++modalId;
			let id = $(this).parents('tr:first').data('id');
			let estado = $(this).parents('tr:first').data('estado');
			alert(estado);
			let data = { 'idRequerimientoInterno': id };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': AprobacionRequerimientoInterno.url + 'formularioActualizacionRequerimientoInterno', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				let style = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });RequerimientoInterno.nDetalle=1;';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				if(estado !== 2 && estado !== 3 && estado !== 5) {
					style[1] = 'background-color: #FF0000;';
					fn[1] = 'AprobacionRequerimientoInterno.rechazarRequerimientoInterno();';
					btn[1] = { title: 'Rechazar', fn: fn[1], style: style[1] };
					style[2] = 'background-color: #26CC2E;';
					fn[2] = 'AprobacionRequerimientoInterno.aprobarRequerimientoInterno();';
					btn[2] = { title: 'Aprobar', fn: fn[2], style: style[2] };
				}

				Fn.showModal({ id: modalId, show: true, title: a.data.title, frm: a.data.html, btn: btn, width: '100%' });

				AprobacionRequerimientoInterno.itemServicio = $.parseJSON($('#itemsServicio').val());
				AprobacionRequerimientoInterno.modalIdForm = modalId;
				AprobacionRequerimientoInterno.htmlG = $('.default-item').html();
				AprobacionRequerimientoInterno.actualizarAutocomplete();
				AprobacionRequerimientoInterno.actualizarOnAddRow();
				Fn.loadSemanticFunctions();
				Fn.loadDimmerHover();
				$('.simpleDropdown').dropdown();
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			});
		});
		$(document).on('click', '.btn-detalleRequerimientoInterno', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idRequerimientoInterno': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': AprobacionRequerimientoInterno.url + 'formularioVisualizacionRequerimientoInterno', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				AprobacionRequerimientoInterno.actualizarAutocomplete();
			});
		});
		$(document).on('click', '.btnAnularRequerimientoInterno', function () {
			let id = $(this).data('id');
			Fn.showConfirm({ fn: "AprobacionRequerimientoInterno.rechazarRequerimientoInterno(" + id + ")", content: " ¿Está seguro de rechazar el requerimiento?" });
		});
		$(document).on('click', '.btnAprobarRequerimientoInterno', function () {
			let id = $(this).data('id');
			Fn.showConfirm({ fn: "AprobacionRequerimientoInterno.aprobarRequerimientoInterno(" + id + ")", content: " ¿Está seguro de aprobar el requerimiento?" });
		});
	},
	SimboloMoneda: function (t) {
		var ts = $(t).val();

		if (ts == 1) {
			$('.monedaSimbolo').text('S/');
		} else {
			$('.monedaSimbolo').text('$');
		}

	},
	aprobarRequerimientoInterno: function (id) {
		var jsonString = { 'data': JSON.stringify(id) };
		var config = { url: AprobacionRequerimientoInterno.url + 'aprobarRequerimientoInterno', data: jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result === 2) return false;
			++modalId;
			var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) fn += 'Fn.showModal({ id:' + modalId + ',show:false });$("#btn-filtrarRequerimientoInterno").click();';

			var btn = [];
			btn[0] = { title: 'Cerrar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
		});
	},
	rechazarRequerimientoInterno: function (id) {
		var jsonString = { 'data': JSON.stringify(id) };
		var config = { url: AprobacionRequerimientoInterno.url + 'rechazarRequerimientoInterno', data: jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result === 2) return false;
			++modalId;
			var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) fn += 'Fn.showModal({ id:' + modalId + ',show:false });$("#btn-filtrarRequerimientoInterno").click();';

			var btn = [];
			btn[0] = { title: 'Cerrar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
		});
	},
}
AprobacionRequerimientoInterno.load();
