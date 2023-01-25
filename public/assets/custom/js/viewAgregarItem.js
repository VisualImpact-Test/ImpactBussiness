var Cotizacion = {

	frm: 'frm-item',
	contentDetalle: 'idContentItem',
	url: 'Item/',
	informacionItem: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlCotizacion: '',
    nDetalle: 1,
	itemsLogisticaData: [],

	load: function () {

		//parte del boton de agregar item	
		$(document).ready(function () {
			// $('#btn-filtrarCotizacion').click();
            Fn.loadSemanticFunctions();
            
            $('.simpleDropdown').dropdown();
            $('.dropdownSingleAditions').dropdown({allowAdditions: true	});
            Cotizacion.itemServicio =   $.parseJSON($('#itemsServicio').val());
            Cotizacion.htmlG = $('.default-item').html();

            Cotizacion.actualizarPopupsTitle();
            // Cotizacion.actualizarAutocomplete();

			$.each($('.btnPopupCotizacionesProveedor'), function(i,v){    var custom_popup = $(v).parents('.nuevo').find('custom.popup');
				var id = $(v).data('id');
				$(v).popup({
					popup : $(`.custom-popup-${id}`),
					on    : 'click'
				})
			});

			Cotizacion.itemsLogisticaData = JSON.parse($('#itemsLogistica').val());
			Cotizacion.itemLogisticaInputComplete();
        });

		//boton de agregar item
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

            // Cotizacion.actualizarAutocomplete();

            $('.btn-add-file').dimmer({on: 'hover'});
            $('.simpleDropdown').dropdown();
    
		});
		$(document).on('focusout', '.itemLogistica', function () {
			let control = $(this);
			let val = control.val();
			if(val != '' && val != undefined && val != null){
				control.attr('readonly', 'readonly');
			}
			id = control.closest('.itemLogisticaDiv').find('.codItemLogistica').val();
			if( id == '' || id == undefined || id == null){
				control.closest('.itemLogisticaDiv').find('.codItemLogistica').val('0');
			}
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
                }
              })
		
            // $(this).parents('.fila-existente').remove();

			// $.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
			// 	$(this).find('.nfila').text(Number(index) + 1);
			// });

			Cotizacion.actualizarTotal();
		});

		$(document).on('change', '#tipo', function (e) {
			// Cotizacion.actualizarAutocomplete();
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
					if( (num + total) > 10 ){
						var message = Fn.message({ type: 2, message: 'Solo se permiten 10 capturas como máximo' });
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

						if( size > 2048 ){
							var message = Fn.message({ type: 2, message: 'Solo se permite como máximo 1MB por captura' });
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
	itemLogisticaInputComplete: function(ord){
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(Cotizacion.itemsLogisticaData, function (index, value) {
			items[nro] = value;
			nro++;
		});

		i = 0;
		limit = $('.itemLogistica').length;

		for (i; i < limit; i++) {
			let input = $(".itemLogistica")[i];
			$(input).autocomplete({
				source: items,
				select: function (event, ui) {
					event.preventDefault();
					let control = $(this).parents(".itemLogisticaDiv");
					//Llenamos los items con el nombre
					$(this).val(ui.item.label);
					//Llenamos una caja de texto invisible que contiene el ID del Artículo
					control.find(".codItemLogistica").val(ui.item.value);
					//Tipo Item
					$(this).focusout();
				},
				// appendTo: "#modal-page-" + Oper.modalId,
				max: 5,
				minLength: 3,
			});
		}


	},
	editItemLogisticaValue: function(t){
		control = $(t);
		control.closest('.itemLogisticaDiv').find('.itemLogistica').attr('readonly',false);
		control.closest('.itemLogisticaDiv').find('.codItemLogistica').val('');
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

    actualizarPopupsTitle: () => {
        //Boton enviar
        $('.btn-send-item')
        .popup({
          position : 'left center',
          target   : $('.btn-send-item'),
          content    : 'Enviar',
        });

        //Boton Guardar
      

        //Boton Agregar Detalle
        $('.btn-add-detalle-item')
        .popup({
          position : 'left center',
          target   : $('.btn-add-detalle-item'),
          content  : 'Agregar Detalle',
        });
        //Boton Eliminar Detalle
        $('.btn-eliminar-detalle-item')
        .popup({
          position : 'top center',
          target   : $('.btn-eliminar-detalle-item'),
          content  : 'Eliminar Detalle',
        });
        //Boton Bloquear Detalle
        $('.btn-bloquear-detalle-item')
        .popup({
          position : 'top center',
          target   : $('.btn-bloquear-detalle-item'),
          content  : 'Bloquear Detalle',
        });
        
        //Boton Ver leyenda
        $('.btn-leyenda')
        .popup({
            popup : $('.popup.leyenda'),
            on    : 'click'
        });

        $('.btn-add-file')
        .dimmer({
            on: 'hover'
        });



    }
}

Cotizacion.load();
