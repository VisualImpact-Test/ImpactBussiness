var FormularioProveedores = {
	frm: 'frmCotizacionProveedorCabecera',
	contentDetalle: 'content-tb-cotizaciones-proveedor',
	url: 'FormularioProveedor/',
	divPropuesta: '',
	load: function () {

		$(document).ready(function(){
			if($('#idCotizacion').val()){
				FormularioProveedores.actualizarTable();
			}
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
		$(document).on("click",".btnContraoferta", function(){
			let id = $(this).data('id');
			++modalId;

			let jsonString = { 'data': JSON.stringify({'id' : id}) };
			$.when(Fn.ajax({'url': FormularioProveedores.url + 'validarPropuestaExistencia', 'data': jsonString})).then((rpta) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				if(rpta.continuar){
					let config = { 'url': FormularioProveedores.url + 'viewRegistroContraoferta', 'data': jsonString };
					$.when(Fn.ajax(config)).then((a) => {
						fn[1] = 'FormularioProveedores.agregarPropuesta('+id+');';
						btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
						fn[2] = 'Fn.showConfirm({ idForm: "formRegistroTipos", fn: "FormularioProveedores.registrarPropuesta('+modalId+')", content: "Su propuesta podra ser tratada por las personas encargadas." });';
						btn[2] = { title: 'Guardar', fn: fn[2] };
						Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
						FormularioProveedores.divPropuesta = $('#divBase'+id).html();
					});
				}else{
					let mensaje = Fn.message({type: 2, message: 'Ya se registro una Propuesta para articulo seleccionado.'});
					Fn.showModal({ id: modalId, show: true, title: 'Error', content: mensaje, btn: btn, width: '500px' });
				}
			});


		});
		$(document).on("click",".eliminarDatos", function(){
			$(this).parents('.contenido').remove();
		});

		$(document).off('change', '.file-lsck-capturas').on('change', '.file-lsck-capturas', function(e){
			var control = $(this);
			var data = control.data();
			console.log(data);
			var id = '';
			var nameImg = '';
			console.log(data['row']);
			if( data['row'] ){
				id = data['row'];
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			}else{
				id = 0;
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			}
			if( control.val() ){
				var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
				var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
				var num = control.get(0).files.length;

				list: {
					var total = $('input[name="' + name + '[' + id + ']"]').length;
					if( (num + total) > MAX_ARCHIVOS ){
						var message = Fn.message({ type: 2, message: `Solo se permiten ${MAX_ARCHIVOS} archivos como máximo` });
						Fn.showModal({
							'id': ++modalId,
							'show': true,
							'title': 'Alerta',
							'frm': message,
							'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
						});

						break list;
					}

					for(var i = 0; i < num; ++i){
						var size = control.get(0).files[i].size;
							size = Math.round((size / 1024));

						if( size > KB_MAXIMO_ARCHIVO ){
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO / 1024} MB por captura` });
							Fn.showModal({
								'id': ++modalId,
								'show': true,
								'title': 'Alerta',
								'frm': message,
								'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
							});

							break list;
						}
					}
          let file = '';
          let imgFile = '';
          let contenedor = '';
					for(var i = 0; i < num; ++i){
            file = control.get(0).files[i];
            Fn.getBase64(file).then(function(fileBase){

              if(fileBase.type.split('/')[0] == 'image'){
                  imgFile = fileBase.base64;
                  contenedor = content;
              }else{
                  imgFile = `${RUTA_WIREFRAME}pdf.png`;
                  contenedor = content_files;
              }

              var fileApp = '';
							fileApp += '<div class="col-md-2 text-center">';
              fileApp += `
                      		<div class="ui dimmer dimmer-file-detalle">
	                          <div class="content">
	                            <p class="ui tiny inverted header">${fileBase.name}</p>
	                          </div>
                      		</div>`;
              fileApp += '<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>';
              fileApp += '<input type="hidden" name="' + name +'[' + id + ']"  value="' + fileBase.base64 + '">';
              fileApp += '<input type="hidden" name="' + nameType +'[' + id + ']"  value="' + fileBase.type + '">';
              fileApp += '<input type="hidden" name="' + nameFile +'[' + id + ']"  value="' + fileBase.name + '">';
							fileApp += `<img src="${imgFile}" class="rounded img-lsck-capturas img-responsive img-thumbnail">`;
              fileApp += '</div>';
							console.log(fileApp);
              contenedor.append(fileApp);
              control.parents('.nuevo').find('.dimmer-file-detalle').dimmer({	on: 'click' });
            });
					}
				}
				control.val('');
			}

		});
		$(document).off('change', '.files-upload').on('change', '.files-upload', function(e){
			var control = $(this);
			var data = control.data();

			if( control.val() ){
				var num = control.get(0).files.length;

				list: {
					let div = control.parents('.divUploaded:first').find('.content_files');
					var total = div.find('.file_uploaded').length;
					if( (num + total) > MAX_ARCHIVOS ){
						var message = Fn.message({ type: 2, message: `Solo se permiten ${MAX_ARCHIVOS} archivos como máximo` });
						Fn.showModal({
							'id': ++modalId,
							'show': true,
							'title': 'Alerta',
							'frm': message,
							'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
						});

						break list;
					}
					//
					for(var i = 0; i < num; ++i){
						var size = control.get(0).files[i].size;
							size = Math.round((size / 1024));

						if( size > KB_MAXIMO_ARCHIVO ){
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO / 1024} MB por captura` });
							Fn.showModal({
								'id': ++modalId,
								'show': true,
								'title': 'Alerta',
								'frm': message,
								'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
							});

							break list;
						}
					}
					div.html(`<input type="hidden" class="form-control" name="cantidadImagenes" value="${num}">`);
          let file = '';

					for(var i = 0; i < num; ++i){
            file = control.get(0).files[i];
            Fn.getBase64(file).then(function(fileBase){

							let fileApp = '<div class="file_uploaded">'+
								              `<input type="hidden" class="form-control" name="f_base64" value="${fileBase.base64}">`+
								              `<input type="hidden" class="form-control" name="f_type" value="${fileBase.type}">`+
								              `<input type="text" class="form-control" name="f_name" value="${fileBase.name}">`+
								            '</div>';
              div.append(fileApp);
            });
					}
				}
				control.val('');
			}

		});
		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function(e){
			e.preventDefault();
			var control = $(this);
			control.parents('.content-lsck-capturas:first').remove();
		});
		$(document).on("click",".btnVolverProveedor", ()=>{
			Fn.goToUrl(site_url+'FormularioProveedor/cotizacionesLista');
		});
		$(document).on("click",".btnRefreshCotizaciones", ()=>{
			FormularioProveedores.actualizarTable();
		});

	},
	actualizarTable: function(){
		var ruta = 'cotizacionesRefresh';
		var config = {
			'idFrm': FormularioProveedores.frm
			, 'url': FormularioProveedores.url + ruta
			, 'contentDetalle': FormularioProveedores.contentDetalle
		};

		Fn.loadReporte_new(config);
	},
	calcularTotalSub: function(id){
		costo = $("[findCosto="+id+"]");
		cantidad = $("[findCantidad="+id+"]");
		suma = 0; canTot = 0;
		for (var i = 0; i < costo.length; i++) {
			suma += parseFloat((costo[i].value||0)) * parseFloat(cantidad[i].value);
			canTot += parseFloat(cantidad[i].value);
		}
		var promedio = suma / canTot;
		$('#costo_'+id).val(promedio);
		$('#costoredondo_'+id).val(promedio.toFixed(2));
		$('#costo_'+id).keyup();
		$('#msgCosto_'+id).removeClass('d-none');
		$('#costo_'+id).attr('readonly', true);
		$('#costoredondo_'+id).attr('readonly', true);
	},
	calcularTotal: function(i, cantidad, val){
		var tot = cantidad * val;
		var tot_ = tot.toFixed(2);
		$('#valorTotal'+i).val(tot_);
		$('#lb_valorTotal'+i).html('S/. '+tot_);
	},
	calcularDiasEntrega: function(i, t, fechaHoy){
		val = new Date(t.value);
		fechaHoy = new Date(fechaHoy);
		var dias = -1 * Fn.diasDesdeFecha(val, fechaHoy);
		$('#de_input'+i).val(dias);
	},
	calcularDiasValidez: function(i, t, fechaHoy){
		val = new Date(t.value);
		fechaHoy = new Date(fechaHoy);
		var dias = -1 * Fn.diasDesdeFecha(val, fechaHoy);
		$('#dv_input'+i).val(dias);
	},
	calcularFecha: function(i, val, fechaHoy){
		fechaHoy = new Date(fechaHoy);
		fechaHoy.setDate(fechaHoy.getDate() + parseInt(val));
		fecha = fechaHoy.toISOString().slice(0, 10);
		$('#fechaValidez'+i).val(fecha);

	},
	calcularFechaEntrega: function(i, val, fechaHoy){
		fechaHoy = new Date(fechaHoy);
		fechaHoy.setDate(fechaHoy.getDate() + parseInt(val));
		fecha = fechaHoy.toISOString().slice(0, 10);
		$('#fechaEntrega'+i).val(fecha);
	},
	registrarPropuesta: function(){
		$.when(Fn.validateForm({ id: 'formRegistroPropuesta' })).then(function (a) {
			if (a === true) {
				let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroPropuesta')) };
				let url = FormularioProveedores.url + "registrarPropuesta";
				let config = { url: url, data: jsonString };

				$.when(Fn.ajax(config)).then(function (b) {
					++modalId;
					var btn = [];
					let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

					if (b.result == 1) {
						fn = 'Fn.closeModals(' + modalId + ');';
					}

					btn[0] = { title: 'Continuar', fn: fn };
					Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
				});
			}
		});
	},
	agregarPropuesta: function(id){
		$('#divExtra'+id).append(FormularioProveedores.divPropuesta);
	},
	calcularTotalPropuesta: function(t){
		let cantidad = $(t).parents().find('.cantidad');
		let costo = $(t).parents().find('.costo');
		let total = $(t).parents().find('.total');
		for (var i = 0; i < cantidad.length; i++) {
			total[i].value = parseFloat(cantidad[i].value || 0) * parseFloat(costo[i].value || 0);
		}
	}

}
FormularioProveedores.load();
