var AprobacionUsuario = {
	url: 'AprobacionUsuario/',
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
			$('#btn-filtrarUsuarioAprobar').click();
		});
		$(document).on("click", "#btn-filtrarUsuarioAprobar", () => {
			var ruta = 'reporte';
			var config = {
				'idFrm': AprobacionUsuario.frm
				, 'url': AprobacionUsuario.url + ruta
				, 'contentDetalle': AprobacionUsuario.contentDetalle
			};

			Fn.loadReporte_new(config);
		});
		$(document).on("click", "#btn-AgregarNuevoUsuarioAprobacion", function (e) {
			++modalId;
			let jsonString = { 'data': '' };
			let config = { 'url': AprobacionUsuario.url + 'formularioRegistroUsuarioAprobar', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				let style = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroUsuarioAprobar", fn: "AprobacionUsuario.registrarAprobacionUsuario()", content: "¿Esta seguro de registrar al usuario?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.data.title, frm: a.data.html, btn: btn, width: '60%' });
			});
		});
		$(document).on('change', '#usuario', function (e) {
			var usuario = $('#usuario').val();

			var jsonString = {
				'data': JSON.stringify(usuario)
			};

			var config = {
				url: AprobacionUsuario.url + "obtenerTipoUsuario",
				data: jsonString
			};

			$.when(Fn.ajax(config)).then(function (a) {
				if (a.usuarioTipo.nombre && a.usuarioTipo.nombre.length > 0) {
					var value = $('#nombre');
					value.empty();
					value.val(a.usuarioTipo.nombre);
				}
			});
		});
	},
	registrarAprobacionUsuario(){
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroUsuarioAprobar')) };
		let url = AprobacionUsuario.url + "registrarUsuarioAprobar";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarUsuarioAprobar").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
}
AprobacionUsuario.load();
