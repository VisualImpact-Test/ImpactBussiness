var SolicitudCotizacion = {
	url: 'SolicitudCotizacion/',
	load: function () {
		$(document).ready(function(){
			$('.dimmer-file-detalle')
			.dimmer({
				on: 'click'
			});

			Cotizacion.actualizarTotal();
		});
	},
	registrarCotizacion: function (tipoRegistro = 1) {
		let formValues = Fn.formSerializeObject('formRegistroCotizacion');
			formValues.tipoRegistro = tipoRegistro;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = SolicitudCotizacion.url + "actualizarCotizacion";
		let config = { url: url, data: jsonString };
		let diferencias = 0;

		$.each($('.idTipoItem'), function (index, value) {
			if ($(value).val() != '' && $('#tipo').val() != 3) {
				if ($(value).val() != $('#tipo').val()) {
					$(value).parents('.nuevo').find('.ui-widget').addClass('has-error');

					diferencias++;
				}
			}
		});

		if (diferencias > 0) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: '<div class="alert alert-danger">Se encontraron items que no corresponden al tipo de SolicitudCotizacion. <strong>Verifique el formulario.</strong></div>', btn: btn, width: '40%' });

			return false;
		}

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
	registrarOrdenCompra: function(tipoRegistro){
		let formValues = Fn.formSerializeObject('formRegistroOrdenCompra');
			formValues.tipoRegistro = tipoRegistro;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = SolicitudCotizacion.url + "registrarOrdenCompra";
		let config = { url: url, data: jsonString };
		
		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.data.html, btn: btn, width: '40%' });
		});
	}
}
var Cotizacion = {

	frm: 'frm-cotizacion',
	contentDetalle: 'idContentCotizacion',
	url: 'Cotizacion/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlBodySubItem: [],
	htmlCotizacion: '',
    nDetalle: 1,

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCotizacion').click();
		});

		$(document).ready(function () {
			// $('#btn-filtrarCotizacion').click();
            Fn.loadSemanticFunctions();
            
            $('.simpleDropdown').dropdown();
            $('.dropdownSingleAditions').dropdown({allowAdditions: true	});
            Cotizacion.itemServicio =   $.parseJSON($('#itemsServicio').val());
            Cotizacion.htmlG = $('.default-item').html();

			$.each($('.content-body-sub-item'),(i,v) => {
				let control = $(v);
				let dvfeatures = control.closest('.div-features');
				let tipo = dvfeatures.data('tipo');
				let html = control.html();
				Cotizacion.htmlBodySubItem[tipo] = html;
			});

            Cotizacion.actualizarPopupsTitle();
            Cotizacion.actualizarAutocomplete();

			$.each($('.btnPopupCotizacionesProveedor'), function(i,v){    var custom_popup = $(v).parents('.nuevo').find('custom.popup');
				var id = $(v).data('id');
				$(v).popup({
					popup : $(`.custom-popup-${id}`),
					on    : 'click'
				})
			});
        });

		$(document).on('click', '#btn-filtrarCotizacion', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Cotizacion.frm
				, 'url': Cotizacion.url + ruta
				, 'contentDetalle': Cotizacion.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '#btn-registrarCotizacion', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Cotizacion.url + 'formularioRegistroCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				// fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(1)", content: "¿Esta seguro de registrar esta cotizacion?" });';
				// btn[1] = { title: 'Guardar <i class="fas fa-save"></i>', fn: fn[1] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(2)", content: "¿Esta seguro de registrar y enviar esta cotizacion?" });';
				btn[1] = { title: 'Enviar <i class="fas fa-paper-plane"></i>', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '100%',large: true });

				Cotizacion.modalIdForm = modalId;

				Cotizacion.htmlG = $('#listaItemsCotizacion tbody tr').html();
				$('#listaItemsCotizacion tbody').html('');
				$(".btn-add-row").click();

				$('.dropdownSingleAditions')
				.dropdown({
					allowAdditions: true
				})
				;

			});
		});

		$(document).on('click', '.btn-detalleCotizacion', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCotizacion': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioVisualizacionCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Cotizacion.actualizarAutocomplete();
			});
		});
	

		$(document).on('click', '.btn-agregarItem', function () {
			++modalId;

			let nombre = $(this).data('nombreitem');
			let idPesupuesto = $(this).data('idcotizacion');
			let data = { 'nombre': nombre };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioRegistroItem', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemsLogistica = a.data.itemsLogistica;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Cotizacion.registrarItem(' + idPesupuesto + ')", content: "¿Esta seguro de registrar el item ? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				Cotizacion.actualizarAutocompleteItemsLogistica();
			});
		});

		$(document).on('click', '.btn-estadoCotizacion', function () {
			++modalId;

			let idCotizacion = $(this).parents('tr:first').data('id');
			let estado = $(this).data('estado');
			let data = { 'idCotizacion': idCotizacion, 'estado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'actualizarEstadoCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarCotizacion").click();
			});
		});

		$(document).on('click', '.btn-add-row', function (e) {
			e.preventDefault();
            let defaultItem = $('.default-item');
            defaultItem.append(Cotizacion.htmlG);

            let childInserted = defaultItem.children().last();
            let childInsertedNumber = (++Cotizacion.nDetalle);
            childInserted.find('.title-n-detalle').text(Fn.generarCorrelativo(`${childInsertedNumber}`,5))
            childInserted.find('.file-lsck-capturas').attr('data-row',childInserted.index())
			// let $filas = $('#listaItemsCotizacion tbody tr').length;
			// $filas = $filas + 1;
			// let $html = "<tr class='nuevo nuevoItem'><td class='n_fila' ><label class='nfila'>" + $filas + "</label><i class='estadoItemForm fa fa-sparkles' style='color: teal;'></i></td>";
			// $html += Cotizacion.htmlG;
			// $html += "</tr>";
            
			
			//Para ordenar los select2 que se descuadran
			$("html").animate({ scrollTop: defaultItem.height() }, 500);
            childInserted.transition('glow');

            Cotizacion.actualizarAutocomplete();
			Cotizacion.actualizarOnAddRow(childInserted);
			Cotizacion.actualizarOnAddRowCampos(childInserted);
    
		});

		$(document).on('click', '.editFeatures', function () {
			++modalId;
			let control = $(this).closest("tr");
			let row = control.index();
			let idTipoItem = control.find("#tipoItemForm").val();
			let data = { row, idTipoItem };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formFeatures', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Aceptar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });
			});
		});
		$(document).on('click', '.btn-add-row-cotizacion', function (e) {
			e.preventDefault();

			let $filas = $('#listaItemsCotizacion tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo'><td class='n_fila' >" + $filas + "</td>";
			$html += Cotizacion.htmlCotizacion;
			$html += "</tr>";

			$('#listaItemsCotizacion tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
		});

		$(document).on('click', '.btneliminarfila', function (e) {
			e.preventDefault();
            let body = $(this).parents('.body-item');
            let div_locked = body.find('.btn-bloquear-detalle');

            if(div_locked.find('i').hasClass('lock')){
                $(this).parents('.body-item').find('.btn-bloquear-detalle').transition('shake');
                return false;
            }
            body.transition({
                animation  : 'slide left',
                duration   : '0.4s',
                onComplete : function() {
                    body.remove();

					$.each($('.body-item'),function(i,v){
						Cotizacion.actualizarOnAddRowCampos($(v));
					});
                }
            });
		
            // $(this).parents('.fila-existente').remove();

			// $.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
			// 	$(this).find('.nfila').text(Number(index) + 1);
			// });

			Cotizacion.actualizarTotal();
		});

		$(document).on('click', '.btneliminarfilaCotizacion', function (e) {
			e.preventDefault();
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
				$(this).text(Number(index) + 1);
			});
		});

		$(document).on('change', '#tipo', function (e) {
			Cotizacion.actualizarAutocomplete();
		});

		$(document).on('change', '#tipoItemForm', function (e) {

			let control = $(this);
			let parent = control.closest('.body-item');
			let idTipo = control.val();

			let allFeatures = parent.find(`.div-features`);
			let divFeature = parent.find(`.div-feature-${idTipo}`);

			allFeatures.addClass('d-none');
			divFeature.removeClass('d-none');

			Cotizacion.cleanDetalle(parent);
		});

		$(document).on('change', '#prioridadForm', function (e) {
			let prioridad = $(this).val();

			if(prioridad == 1 ){ //Si es prioridad ALTA 
				$(motivoForm).attr("patron",'requerido');
			}
			else{
				$(motivoForm).removeAttr("patron");
			}
		});

		$(document).on('click', '.btn-cotizacion-pdf', function (e) {
			e.preventDefault();

			let $idCotizacion = $(this).parents('tr').data('id');

			Cotizacion.generarRequerimientoPDF($idCotizacion);
		});

		$(document).on('keyup', '.cantidadForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let precioForm = thisControlParents.find('.precioForm');
			let gapForm = thisControlParents.find('.gapForm');

			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');

			gapForm.keyup();
			let cantidad = Number(thisControl.val());
			let costo = Number(costoForm.val());
			let precio = Number(precioForm.val());

			let subTotal = Fn.multiply(cantidad, precio);

			
			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));

			
			Cotizacion.actualizarTotal();
		});
		$(document).on('keyup', '.cantidadSubItemDistribucion', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let cantidadForm = thisControlParents.find('.cantidadForm');
			let costoTipoServicioForm = thisControlParents.find('.costoTipoServicio');
			
			let cantidadTipoServicio = Number(thisControl.val());
			let costoTipoServicio = Number(costoTipoServicioForm.val());

			let subTotalTipoServicio = Fn.multiply(cantidadTipoServicio,costoTipoServicio);

			costoForm.val(subTotalTipoServicio);

			cantidadForm.keyup();
			
		});

		$(document).on('focusout', '.costoFormLabel', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let costoFormLabel = thisControlParents.find('.costoFormLabel');
			let precioForm = thisControlParents.find('.precioForm');
			let precioFormLabel = thisControlParents.find('.precioFormLabel');
			let fieldPrecioFormLabel = precioFormLabel.closest('.field');
			let cantidadForm = thisControlParents.find('.cantidadForm');

			let costo = Number(thisControl.val());
			let precio = Number(precioForm.val());
			let costoAnterior = Number(costoForm.val());
			if(costo > precio) {
				thisControl.val(costoAnterior);
				fieldPrecioFormLabel.transition('shake');
				$("#nagPrecioValidacion").nag({
					persist:true
				});
				return false;
			}
			costoForm.val(thisControl.val());
			cantidadForm.keyup();
			
			
			Cotizacion.actualizarTotal();
		});

		$(document).on('keyup', '.gapForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let cantidadForm = thisControlParents.find('.cantidadForm');
			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');
			
			let precioForm = thisControlParents.find('.precioForm');
			let precioFormLabel = thisControlParents.find('.precioFormLabel');

			let gap = Number(thisControl.val());
			let costo = Number(costoForm.val());

			let cantidad = Number(cantidadForm.val());
			let precio = (costo + (costo * (gap/100)));
			let subTotal = Fn.multiply(cantidad, precio);

			precioForm.val(precio);
			precioFormLabel.val(moneyFormatter.format(precio));

			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));
			Cotizacion.actualizarTotal();
		});

		$(document).on('change','input[name=upload_orden_compra]', function(e){
			let idCotizacion =  $(this).closest('tr').data('id');
			var archivos = document.getElementById("upload_orden_compra["+idCotizacion+"]");
			
			//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
			var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
			//Creamos una instancia del Objeto FormDara.
			var archivos = new FormData();
			/* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
			Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como 
			indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
			for(i=0; i<archivo.length; i++){
			archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
			}
			$.ajax({
				url:site_url+Cotizacion.url + 'guardarArchivo/', //Url a donde la enviaremos
				type:'POST', //Metodo que usaremos
				contentType:false, //Debe estar en false para que pase el objeto sin procesar
				data:archivos, //Le pasamos el objeto que creamos con los archivos
				processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
				cache:false, //Para que el formulario no guarde cache
				beforeSend: function(){ Fn.showLoading(true) },
			}).done(function(a){//Escuchamos la respuesta y continuamos
				Fn.showLoading( false );

				a = $.parseJSON(a);
				var data= {};
					data= a ;
					data.idCotizacion = idCotizacion;

				var jsonString={ 'data':JSON.stringify(data) };
				var url=Cotizacion.url+'guardarArchivoBD';
				var config={ url:url,data:jsonString };
	
				$.when( Fn.ajax(config) ).then(function(a){
					if( a.result!=2 ){
						++modalId;
						var btn=[];
						var fn=[];
	
						if(a.result==0){
							fn[0]='Fn.showModal({ id:'+modalId+',show:false });Fn.closeModals('+modalId+');';
							btn[0]={title:'Aceptar',fn:fn[0]};
						}
						else{
							fn[0]='Fn.showModal({ id:'+modalId+',show:false });Fn.closeModals('+modalId+');$("#btn-filtrarCotizacion").click();';
							btn[0]={title:'Aceptar',fn:fn[0]};
						}
	
						Fn.showModal({ id:modalId,show:true,title:a.msg.title,content:a.data.html,btn:btn,width:a.data.width });
					}
				});
			});
	
		});

		$(document).on('click', '.btn-frmCotizacionConfirmada', function () {
			++modalId;
			let data = {};
				data.id = $(this).closest("tr").data("id");
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioSolicitudCotizacion', 'data': jsonString };


			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(4)", content: "¿Esta seguro de enviar esta cotizacion?" });';
				btn[1] = { title: 'Enviar Respuesta <i class="fas fa-paper-plane"></i>', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Cotizacion.modalIdForm = modalId;
			
				
			});
		});

		$(document).on('click', '.btn-generar-cotizacionEfectivaSinOc', function () {
			++modalId;
			let data = {};
				data.id = $(this).closest("tr").data("id");
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioProcesarSinOc', 'data': jsonString };


			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(5)", content: "¿Esta seguro de enviar esta cotizacion?" });';
				btn[1] = { title: 'Enviar Respuesta <i class="fas fa-paper-plane"></i>', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				Cotizacion.modalIdForm = modalId;
				
			});
		});
		$(document).on('click', '.verCaracteristicaArticulo', function () {
			++modalId;
			let control = $(this).closest("tr");
			let codItem = control.find('.codItems').val();

			if(codItem == '') return false;

			let data = { codItem };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'viewItemDetalle', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
		
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });

			});
		});

        $(document).off('change', '.file-lsck-capturas').on('change', '.file-lsck-capturas', function(e){
			var control = $(this);

			var data = control.data();
			// var frm = frmLiveAuditoria;

			var id = '';
			var nameImg = '';
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
						var message = Fn.message({ type: 2, message: `Solo se permiten ${MAX_ARCHIVOS} capturas como máximo` });
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
                                    fileApp += '<div class="ui fluid image content-lsck-capturas">';
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
                                        fileApp += `<img height="100" src="${imgFile}" class="img-lsck-capturas img-responsive img-thumbnail">`;
                                    fileApp += '</div>';
                                    
                                    contenedor.append(fileApp);
                                    control.parents('.nuevo').find('.dimmer-file-detalle')
                                    .dimmer({
                                        on: 'click'
                                    });
                            });

					}
				}

				control.val('');
			}
		});

		$(document).off('click', '.img-lsck-capturas').on('click', '.img-lsck-capturas', function(e){
			e.preventDefault();
		});

		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function(e){
			e.preventDefault();
			var control = $(this);
			control.parents('.content-lsck-capturas:first').remove();
		});

		$(document).on('click', '.btnSolicitarCotizacion', function () {
			++modalId;

			if($('.proveedorSolicitudForm').find('select').val().length <= 0){
				$('.proveedorSolicitudForm').transition('shake')
				return false;
			}

			if(!$('input[name=checkItem]').is(' :checked')){
				$('.chk-item').transition('glow');
				return false;

			}

			let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCotizacion')) };
			let config = { 'url': SolicitudCotizacion.url + 'enviarSolicitudProveedor', 'data': jsonString };
			
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Aceptar', fn: fn[0] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

			});
		});
		$(document).on('click', '.btnElegirProveedor', function () {
			++modalId;
			let costoForm = $(this).parents('.nuevo').find('.costoForm')
			let costoFormLabel = $(this).parents('.nuevo').find('.costoFormLabel');
			let cantidadForm = $(this).parents('.nuevo').find('.cantidadForm');
			let gapForm = $(this).parents('.nuevo').find('.gapForm');
			let proveedorForm = $(this).parents('.nuevo').find('.idProveedor');
			let proveedoresForm = $(this).parents('.nuevo').find('.proveedoresForm');
			
			let precio = $(this).find('.txtCostoProveedor').val();
			let proveedorElegido = $(this).find('.txtProveedorElegido').val();
			let proveedorElegidoName = $(this).find('.txtProveedorElegidoName').val();

			costoForm.val(precio);
			costoFormLabel.val(moneyFormatter.format(precio));

			proveedorForm.val(proveedorElegido);
			proveedoresForm.val(proveedorElegidoName);
			
			cantidadForm.keyup();
			gapForm.keyup();

			Cotizacion.actualizarTotal();
		});
		
		$(document).on('click', '.btn-add-sub-item', function () {
			let control = $(this);
			let parent = control.closest('.div-features');
			let tipo = parent.data('tipo');

            let contenedor = parent.find('.content-body-sub-item');
			
			let bodyHtmlSubItem = contenedor.find('.body-sub-item').first().wrap('<p/>').parent();

            // contenedor.append(Cotizacion.htmlBodySubItem[tipo]);
            contenedor.append(bodyHtmlSubItem.html());

            let childInserted = contenedor.children().last();

			// $("html").animate({ scrollTop: contenedor.height() }, 500);
            childInserted.transition('glow');
			childInserted.find(':input').val('');
		});
		$(document).on('click', '.btn-eliminar-sub-item', function () {
			let control = $(this);
			let parent = control.closest('.content-body-sub-item');
			let element = control.closest('.body-sub-item');
			
			if(parent.find('.body-sub-item').length <= 1){
				element.find(':input').val('');
				// element.transition('glow')
				return false;
			}

			element.remove();
			
		});
		$(document).on('change', '.tipoServicioForm', function () {
			let control = $(this);
			let parent = control.closest('.div-features');
			let costo = control.find('option:selected').data('costo');
			let unidadMedida = control.find('option:selected').data('unidadmedida');
			let idUnidadMedida = control.find('option:selected').data('idunidadmedida');

			let costoForm = parent.find('.costoTipoServicio');
			let unidadMedidaForm = parent.find('.unidadMedidaTipoServicio');
			let idUnidadMedidaForm = parent.find('.unidadMedidaSubItem');
			let cantidadFormSubItem = parent.find('.cantidadSubItemDistribucion');

			costoForm.val(costo);
			unidadMedidaForm.val(unidadMedida);
			idUnidadMedidaForm.val(idUnidadMedida);

			cantidadFormSubItem.keyup();
				
			
		});

		
	},

	actualizarCotizacion: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionCotizacions')) };
		let config = { 'url': Cotizacion.url + 'actualizarCotizacion', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},

	actualizarAutocomplete: function () {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(Cotizacion.itemServicio[1], function (index, value) {
			// if (tipo == value.tipo || tipo == 3) {
				items[nro] = value;
				nro++;
			// }
		});
		$(".items").autocomplete({
			source: items,
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();
				let control = $(this).parents(".nuevo");
				//Llenamos los items con el nombre 
				$(this).val(ui.item.label);
				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".ui-widget").find(".codItems").val(ui.item.value);

				//Llenamos el precio actual
				if (ui.item.costo == null || ui.item.semaforoVigencia == "red" ) {
					ui.item.costo = 0;
				}
				control.find(".costoForm").val(ui.item.costo);
				control.find(".costoFormLabel").text(ui.item.costo);
				//Llenamos el estado
				control.find(".estadoItemForm").removeClass('fa-sparkles');
				control.removeClass('nuevoItem');
				control.find(".idEstadoItemForm").val(1);
				control.find(".cotizacionInternaForm").val(`${ui.item.cotizacionInterna}`)

				//Tipo de Item 
				control.find(".idTipoItem").val(ui.item.tipo);
				control.find(".idTipoItem").addClass('read-only');
				control.find(".idTipoItem").dropdown('set selected',ui.item.tipo);
				control.find(`.div-feature-${ui.item.tipo}`).removeClass('d-none');
				//Llenamos el proveedor
				control.find(".proveedorForm").text(ui.item.proveedor);
				control.find(".idProveedor").val(ui.item.idProveedor);

				//LLenar semaforo

				control.find(".semaforoForm").addClass('semaforoForm-' + ui.item.semaforoVigencia);
				
				control.find('.semaforoForm').popup({content : `Vigencia: ${ui.item.diasVigencia} días`});
				
				//Validar boton ver caracteristicas del articulo
				
				control.find(".verCaracteristicaArticulo").removeClass(`slash`);

				//Validacion ID

				let $cod = $(this).parents(".ui-widget").find(".codItems").val();
				if ($cod != '') {
					$(this).attr('readonly', 'readonly');
					control.find('.costoForm').attr('readonly', 'readonly');
					control.find(".cantidadForm").attr('readonly',false);
					control.find("select[name=tipoItemForm]").closest('td').addClass('disabled');					
				}
			},
			appendTo: "#modal-page-" + Cotizacion.modalIdForm,
			max: 5,
			minLength: 3,
		});
	},

	actualizarAutocompleteItemsLogistica: function () {
		$("#equivalente").autocomplete({
			source: Cotizacion.itemsLogistica[1],
			minLength: 0,
			select: function (event, ui) {
				event.preventDefault();

				//Llenamos los items con el nombre 
				$(this).val(ui.item.label);

				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				$(this).parents(".control-group").find("#idItemLogistica").val(ui.item.value);
			},
			appendTo: "#modal-page-" + modalId,
			max: 5,
			minLength: 3,
		});
	},

	generarRequerimientoPDF: function (id) {
		var url = site_url + '/Cotizacion/generarCotizacionPDF/' + id;
		window.open(url, '_blank');
	},

	registrarItem: function (idCotizacion) {
		let formValues = Fn.formSerializeObject('formRegistroItems');
			formValues.idCotizacion = idCotizacion;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "registrarItem";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idCotizacion + '").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	registrarCotizacion: function (tipoRegistro = 1) {
		let formValues = Fn.formSerializeObject('formRegistroCotizacion');
			formValues.tipoRegistro = tipoRegistro;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "registrarCotizacion";
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

	frmSendToCliente: function () {
		let formValues = Fn.formSerializeObject('formRegistroCotizacion');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "getFormSendToCliente";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			let fn1 = `Fn.showConfirm({ idForm: "formSendToCliente", fn: "Cotizacion.sendToCliente()", content: "¿Esta seguro de enviar esta cotizacion?" });`;

			btn[0] = { title: 'Cerrar', fn: fn };
			btn[1] = { title: 'Aceptar', fn: fn1 };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.data.html, btn: btn, width: b.data.width });

			$('.simpleDropdown').dropdown();
            $('.dropdownSingleAditions').dropdown({allowAdditions: true	});
		});
	},

	sendToCliente: function(){
		let formValues = Fn.formSerializeObject('formSendToCliente');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "sendToCliente";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Aceptar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: b.data.width });
		});
	},

	actualizarTotal: function () {
		let total = 0;
		$.each($('.subtotalForm'), function (index, value) {
			total = Number(total) + Number($(value).val());
		})

		let fee = Number($("#feeForm").val());
		let igvForm = $('.igvForm');

		let igv = 0;

		if(igvForm.is(":checked")){
			igv = IGV_SYSTEM;
		}
		let totalFee = (total) + (total*(fee/100))
		let totalFeeIgv = (totalFee) + (totalFee * igv);

		$('.totalFormLabel').val(moneyFormatter.format(Number(totalFeeIgv)));

		$('.totalFormFeeIgv').val(totalFeeIgv);
		$('.totalFormFee').val(totalFee);
		$('.totalForm').val(total);
	},

    actualizarPopupsTitle: () => {
        //Boton enviar
        $('.btn-send')
        .popup({
          position : 'left center',
          target   : $('.btn-send'),
          content    : 'Enviar',
        });

        //Boton Guardar
        $('.btn-save')
        .popup({
          position : 'left center',
          target   : $('.btn-save'),
          content    : 'Guardar',
        });

        //Boton Agregar Detalle
        $('.btn-add-detalle')
        .popup({
          position : 'left center',
          target   : $('.btn-add-detalle'),
          content  : 'Agregar Detalle',
        });
        //Boton Eliminar Detalle
        $('.btn-eliminar-detalle')
        .popup({
          position : 'top center',
          target   : $('.btn-eliminar-detalle'),
          content  : 'Eliminar Detalle',
        });
        //Boton Bloquear Detalle
        $('.btn-bloquear-detalle')
        .popup({
          position : 'top center',
          target   : $('.btn-bloquear-detalle'),
          content  : 'Bloquear Detalle',
        });
        
        //Boton Ver leyenda
        $('.btn-leyenda')
        .popup({
            popup : $('.popup.leyenda'),
            on    : 'click'
        });

		//Dimmer add file
        $('.btn-add-file')
        .dimmer({
            on: 'hover'
        });
		
		//Info archivo
		$('.btn-info-archivo')
		.popup(
			{
				title: `Puede subir como máximo ${MAX_ARCHIVOS}	archivos por detalle`,
				content : `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
			}
		);

		//Info archivo
		$('.btn-info-motivo')
		.popup(
			{
				content : `Si la prioridad es ALTA el motivo será obligatorio.`
			}
		);

		//Info dias validez
		$('.btn-info-validez')
		.popup(
			{
				title: `Días de validez`,
				content : `Se cuentan a partir de que la cotización es enviada al cliente.`
			}
		);

    },

	actualizarOnAddRow: (childInserted) => {

		$('.btn-add-file').dimmer({on: 'hover'});
		$('.simpleDropdown').dropdown();

		//Boton info archivos
		childInserted.find('.btn-info-archivo')
		.popup(
			{
				title: `Puede subir como máximo ${MAX_ARCHIVOS}	archivos por detalle`,
				content : `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
			}
		);
   },

   actualizarOnAddRowCampos:(parent) =>{

		let number = parent.index();
		//Textiles 
		let tallaSubItem = parent.find('.tallaSubItem');
		let telaSubItem = parent.find('.telaSubItem');
		let colorSubItem = parent.find('.colorSubItem');

		//Tarjetas o vales 
		let montoSubItem = parent.find('.montoSubItem');

		//Servicios y distribucion 

		let nombreSubItem = parent.find('.nombreSubItem');
		let cantidadSubItem = parent.find('.cantidadSubItem');
		let cantidadSubItemDistribucion = parent.find('.cantidadSubItemDistribucion');

		let tipoServicioSubItem = parent.find('.tipoServicioSubItem').find('select');
		let unidadMedidaSubItem = parent.find('.unidadMedidaSubItem');
		let costoSubItem = parent.find('.costoSubItem');


		// 
		tallaSubItem.attr('name',`tallaSubItem[${number}]`);
		telaSubItem.attr('name',`telaSubItem[${number}]`);
		colorSubItem.attr('name',`colorSubItem[${number}]`);

		montoSubItem.attr('name',`montoSubItem[${number}]`);

		nombreSubItem.attr('name',`nombreSubItemServicio[${number}]`);
		cantidadSubItem.attr('name',`cantidadSubItemServicio[${number}]`);
		cantidadSubItemDistribucion.attr('name',`cantidadSubItemDistribucion[${number}]`);

		tipoServicioSubItem.attr('name',`tipoServicioSubItem[${number}]`);
		unidadMedidaSubItem.attr('name',`unidadMedidaSubItem[${number}]`);
		costoSubItem.attr('name',`costoSubItem[${number}]`);

   },

  

   cleanDetalle:(parent)=>{
	let costoForm = parent.find('.costoForm');
	let gapForm = parent.find('.gapForm');
	let cantidadForm = parent.find('.cantidadForm');

	costoForm.val('');
	gapForm.val('');

	cantidadForm.val();
	
	cantidadForm.keyup();
   },
}

Cotizacion.load();
SolicitudCotizacion.load();