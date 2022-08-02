var FormularioProveedores = {
	frm: 'frmCotizacionProveedorCabecera',
	contentDetalle: 'content-tb-cotizaciones-proveedor',
	url: 'FormularioProveedor/',

	load: function () {

		$(document).ready(function(){
			// if($('#idCotizacion').val()){
				FormularioProveedores.actualizarTable();
			// }
		});

		$(document).on("click",".btnGuardarCotizacion", ()=>{
			let idForm = 'frmCotizacionesProveedor';
			$.when(Fn.validateForm({ id: idForm })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject(idForm)) };
					let url = "FormularioProveedor/actualizarCotizacionProveedor";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });FormularioProveedores.actualizarTable()';

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
		$(document).on('click', '.btn-detalleCotizacion', function () {
			let id = $(this).parents('tr:first').data('id');
			Fn.goToUrl(site_url+'FormularioProveedor/cotizaciones/' + id);

		});
		$(document).on("click",".btnPopupCotizacionesProveedor", ()=>{
			let idForm = 'frmCotizacionesProveedor';
			$.when(Fn.validateForm({ id: idForm })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject(idForm)) };
					let url = "FormularioProveedor/actualizarCotizacionProveedor";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });FormularioProveedores.actualizarTable()';

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
		$(document).on("click",".btnLogoutProveedor", ()=>{

			let jsonString = {};
			let url = "FormularioProveedor/logout";
			let config = { url: url, data: jsonString };

			$.when(Fn.ajax(config)).then(function (b) {
				++modalId;
				var btn = [];
				let fn = 'Fn.showModal({ id: ' + modalId + ',show:false});Fn.goToUrl(`' + b.data.url + '`);';
				btn[0] = { title: 'Aceptar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
			});
		});
		$(document).on("click",".btnRefreshCotizaciones", ()=>{
			FormularioProveedores.actualizarTable();
		});

	},
	actualizarTable: function(){
		var ruta = 'cotizacionesListaRefresh';
		var config = {
			'idFrm': FormularioProveedores.frm
			, 'url': FormularioProveedores.url + ruta
			, 'contentDetalle': FormularioProveedores.contentDetalle
		};

		Fn.loadReporte_new(config);
	}

}
FormularioProveedores.load();
