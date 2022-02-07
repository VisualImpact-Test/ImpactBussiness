var Proveedor = {

	frm: 'frm-proveedor',
	contentDetalle: 'idContentProveedor',
	url: 'Proveedor/',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarProveedor').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarProveedor').click();
		});

		$(document).on('click', '#btn-filtrarProveedor', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Proveedor.frm
				, 'url': Proveedor.url + ruta
				, 'contentDetalle': Proveedor.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarProveedor', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Proveedor.url + 'formularioRegistroProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedores", fn: "Proveedor.registrarProveedor()", content: "¿Esta seguro de registrar el proveedor?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
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

		$(document).on('change', '#regionCobertura', function (e) {
			e.preventDefault();
			let idDepartamento = $(this).val();
			let html = '<option value="">Seleccionar</option>';

			$('#distritoCobertura').html(html);

			$.each(idDepartamento, function (i_departamento, v_departamento) {
				if (typeof (provincia[v_departamento]) == 'object') {
					$.each(provincia[v_departamento], function (i_provincia, v_provincia) {
						html += '<option value="' + v_departamento + '-' + i_provincia + '" data-departamento="' + v_departamento + '" data-provincia="' + i_provincia + '">' + v_provincia['nombre'] + '</option>';
					});
				}
			});

			$('#provinciaCobertura').html(html);
			Fn.selectOrderOption('provinciaCobertura');
		});

		$(document).on('change', '#provinciaCobertura', function (e) {
			e.preventDefault();

			let htmlSelectedProvincia = $(this).find(":selected");
			let html = '<option value="">Seleccionar</option>';

			$.each(htmlSelectedProvincia, function (i_provincia, v_provincia) {
				let departamento = $(v_provincia).data('departamento');
				let provincia = $(v_provincia).data('provincia');
				if (typeof (distrito[departamento]) == 'object' &&
					typeof (distrito[departamento][provincia]) == 'object'
				) {
					$.each(distrito[departamento][provincia], function (i_distrito, v_distrito) {
						html += '<option value="' + departamento + '-' + provincia + '-' + i_distrito + '">' + v_distrito['nombre'] + '</option>';
					});
				}
			});

			$('#distritoCobertura').html(html);
			Fn.selectOrderOption('distritoCobertura');
		});

		$(document).on('click', '.btn-editar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idProveedor': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Proveedor.url + 'formularioActualizacionProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionProveedores", fn: "Proveedor.actualizarProveedor()", content: "¿Esta seguro de actualizar el proveedor?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-validar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idProveedor': id, 'formularioValidar': true };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Proveedor.url + 'formularioActualizacionProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ fn: "Proveedor.validarProveedor()", content: "¿Esta seguro de validar el proveedor?" });';
				btn[1] = { title: 'Validar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});

		$(document).on('click', '.btn-actualizar-estado', function () {
			++modalId;

			let idProveedor = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idProveedor': idProveedor, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Proveedor.url + 'actualizarEstadoProveedor', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarProveedor").click();
			});
		});

		$(document).on("click", "#btn-masivoCarteraObjetivo", function () {
			++modalId;

			let data = {};

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Proveedor.url + 'carga_masiva', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Proveedor.guardarCargaMasiva();';
				btn[1] = { title: 'Guardar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: "Configuración", frm: a.data.html, btn: btn, width: '80%', class: "modalCargaMasivaObjetivos" });

				HTCustom.llenarHTObjectsFeatures(a.data.ht);
			});
		});

		$(document).on('shown.bs.modal', '.modalCargaMasivaObjetivos', function () {
			HTCustom.crearHTObjects(HTCustom.HTObjectsFeatures);
		});

		HTCustom.load();
		$('#btn-filtrarCarteraObjetivo').click();
	},

	registrarProveedor: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedores')) };
		let url = Proveedor.url + "registrarProveedor";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedor").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarProveedor: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionProveedores')) };
		let config = { 'url': Proveedor.url + 'actualizarProveedor', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedor").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	validarProveedor: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionProveedores')) };
		let config = { 'url': Proveedor.url + 'validarProveedor', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarProveedor").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Proveedor.load();