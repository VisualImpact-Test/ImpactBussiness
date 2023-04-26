var FormularioProveedoresOC = {
	frm: 'frmOrdenCompraProveedorCabecera',
	url: 'FormularioProveedor/',

	load: function () {

		$(document).ready(function () {
			Fn.loadSemanticFunctions();
		});
	},

	confirmarOrdenCompra: function () {
		let formValues = Fn.formSerializeObject(FormularioProveedoresOC.frm);
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = FormularioProveedoresOC.url + "confirmarOrdenCompra";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	descargarOC: function (id) {
		let data = { id };
		let jsonString = { 'data': JSON.stringify(data) };
		Fn.download(site_url + 'Cotizacion/descargarOrdenCompra', jsonString);
	}

}
FormularioProveedoresOC.load();