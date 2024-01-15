var ProveedorServicio = {
	frm: 'frm-proveedorServicio',
	contentDetalle: 'idProveedorServicio',
	url: 'Finanzas/ProveedorServicio/',
	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedorServicio').click();
		});
		$(document).ready(function () {
			$('#btn-filtrarProveedorServicio').click();
		});
		$(document).on('click', '#btn-filtrarProveedorServicio', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': ProveedorServicio.frm
				, 'url': ProveedorServicio.url + ruta
				, 'contentDetalle': ProveedorServicio.contentDetalle
			};
           // console.log(config);
			Fn.loadReporte_new(config);
			Fn.showLoading(false);
		});
	
		$(document).on('click', '.btn-actualizar-estado', function () {
			++modalId;

			let idProveedorServicio = $(this).data('id');
			let estado = $(this).data('estado');
			let data = { 'idProveedorServicio': idProveedorServicio, 'estado': estado };

			console.log(JSON.stringify(data));
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': ProveedorServicio.url + 'actualizarEstadoProveedorServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarProveedorServicio").click();
			});
		});

		$(document).on('click', '#btn-proveedor', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': ProveedorServicio.url + 'formularioRegistroProveedorServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedorServicio", fn: "ProveedorServicio.registrarProveedorServicio()", content: "¿Esta seguro de registrar ProveedorServicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '70%' });
				ProveedorServicio.modalId = modalId;
			});

		});

		$(document).on('change', '#region', function (e) {
			e.preventDefault();
			var idDepartamento = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			$('#distrito').html(html);

			if (typeof (provincia[idDepartamento]) == 'object') {
				$.each(provincia[idDepartamento], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#provincia').html(html);
			Fn.selectOrderOption('provincia');
		});

		$(document).on('change', '#provincia', function (e) {
			e.preventDefault();
			var idDepartamento = $("#region").val();
			var idProvincia = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			if (typeof (distrito_ubigeo[idDepartamento]) == 'object' &&
				typeof (distrito_ubigeo[idDepartamento][idProvincia]) == 'object'
			) {
				$.each(distrito_ubigeo[idDepartamento][idProvincia], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#distrito').html(html);
			Fn.selectOrderOption('distrito');
		});

		HTCustom.load();
	},
	registrarProveedorServicio: function () {
		++modalId;
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedorServicio')) };
		let url = ProveedorServicio.url + "registrarProveedorServicio";
		let config = { url: url, data: jsonString };
		let jsonData = JSON.parse(jsonString.data);
		let ruc = jsonData.ruc;
		let correo = jsonData.correoContacto;
		let numero = jsonData.numeroContacto;
		let titulo = 'Alerta!!';

		if (!ruc.match(/^\d{11}$/)) {
    
			var contenidoRuc = 'El RUC debe contener exactamente 11 dígitos numéricos.';
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			
			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
            return false;
        }

		if (!numero.match(/^\d{9}$/)) {
           
			var contenidoNumero = 'El número de contacto debe contener exactamente 9 dígitos numéricos.';
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			
			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoNumero, btn: btn, width: '20%' });
            return false;
        }

		var regexCorreo = /^[a-zA-Z0-9._-]+@(gmail\.com|hotmail\.com|outlook\.com)$/;

        if (!regexCorreo.test(correo)) {
          
			var contenidoCorreo = 'Por favor, ingrese una dirección de correo válida.';
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			
			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoCorreo, btn: btn, width: '20%' });
            return false;
        }

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedorServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
}

ProveedorServicio.load();
