var FormularioProveedores = {

	load: function () {


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

		$(document).on('change', '.regionCobertura', function (e) {
			e.preventDefault();
			let idDepartamento = $(this).val();
			let html = '<option value="">Seleccionar</option>';
			let distritoCobertura = $(this).closest("tr").find(".distritoCobertura");
			distritoCobertura.html(html);

			// $.each(idDepartamento, function (i_departamento, v_departamento) {
				if (typeof (provincia[idDepartamento]) == 'object') {
					$.each(provincia[idDepartamento], function (i_provincia, v_provincia) {
						// html += '<option value="' + idDepartamento + '-' + i_provincia + '" data-departamento="' + idDepartamento + '" data-provincia="' + i_provincia + '">' + v_provincia['nombre'] + '</option>';
						html += '<option value="' + i_provincia + '" data-departamento="' + idDepartamento + '" data-provincia="' + i_provincia + '">' + v_provincia['nombre'] + '</option>';
					});
				}
			// });
			let provinciaCobertura = $(this).closest("tr").find(".provinciaCobertura");
			provinciaCobertura.html(html);
			// Fn.selectOrderOption('provinciaCobertura');
		});

		$(document).on('change', '.provinciaCobertura', function (e) {
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
						// html += '<option value="' + departamento + '-' + provincia + '-' + i_distrito + '">' + v_distrito['nombre'] + '</option>';
						html += '<option value="' + i_distrito + '">' + v_distrito['nombre'] + '</option>';
					});
				}
			});
			let distritoCobertura = $(this).closest("tr").find(".distritoCobertura");
			distritoCobertura.html(html);
			// Fn.selectOrderOption('distritoCobertura');
		});

		$(document).on('click', '.btn-agregar-zona', function (e) {
			let tbody = $(".tb-zona-cobertura > tbody");
			let trParent = tbody.find(".trParent");

			let combosZona = trParent.find("select").prop("disabled",false);
			tbody.append(`<tr class="trChildren">${trParent.html()}</tr>`);
			trParent.find("select").prop("disabled",true);

		});
		$(document).on('click', '.btn-eliminar-zona', function (e) {
			let tr = $(this).closest("tr");

			if($(".trChildren").length <= 1){
				// $(".trChildren").first().find(".regionCobertura").css("border","solid 1px red");
				// setTimeout($(".trChildren").first().find(".regionCobertura").css("border","solid 1px black"), 5000);
				return false
			}
			tr.remove();
		});



		$(document).on('click', '#btnEnviar', function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: 'formRegistroProveedores' })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedores')) };
					let url = "FormularioProveedor/registrarProveedor";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						if (b.result == 1) {
							fn = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.goToUrl("https://ww7.visualimpact.com.pe/public/site/");';
						}

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});

		$(document).on("click",".btnLoginProveedor", ()=>{
			let idForm = 'frmLoginProveedor';
			$.when(Fn.validateForm({ id: idForm })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject(idForm)) };
					let url = "FormularioProveedor/login";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						if (b.result == 1) {
							fn = 'Fn.showModal({ id: ' + modalId + ',show:false});Fn.goToUrl(`' + b.data.url + '`);';
						}

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
	}

}
FormularioProveedores.load();
