var Autorizacion = {

	frm: 'frm-autorizacion',
	contentDetalle: 'idContentAutorizaciones',
    btnFiltrar : '#btn-filtrarAutorizacion',
	url: 'Finanzas/Autorizacion/',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$(Autorizacion.btnFiltrar).click();
		});

		$(document).ready(function () {
			$(Autorizacion.btnFiltrar).click();
		});

		$(document).on('click', '#btn-filtrarAutorizacion', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': Autorizacion.frm
				, 'url': Autorizacion.url + ruta
				, 'contentDetalle': Autorizacion.contentDetalle
			};

			Fn.loadReporte_new(config);
		});

		$(document).on('click', '.btn-editar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Autorizacion.url + 'frmActualizarAutorizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

                if(a.flagUpdate){
                    fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionProveedores", fn: "Autorizacion.actualizarAutorizacion()", content: "¿Esta seguro de actualizar esta autorización?" });';
                    btn[1] = { title: 'Actualizar', fn: fn[1] };
                }

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
        $(document).off('change', '.file-lsck-capturas-anexos').on('change', '.file-lsck-capturas-anexos', function(e){
			var control = $(this);

			let name = 'anexo-file';
			let	nameType = 'anexo-type';
			let	nameFile = 'anexo-name';
			
			
			if( control.val() ){
				var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
				var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
				var num = control.get(0).files.length;

				list: {
					var total = $(`input[name=${name}]`).length;
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
                                        fileApp += '<a class="ui red right corner label img-lsck-anexos-delete"><i class="trash icon"></i></a>';
                                        fileApp += '<input type="hidden" name="' + name +'"  value="' + fileBase.base64 + '">';
                                        fileApp += '<input type="hidden" name="' + nameType +'"  value="' + fileBase.type + '">';
                                        fileApp += '<input type="hidden" name="' + nameFile +'"  value="' + fileBase.name + '">';
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

        $(document).off('click', '.img-lsck-anexos-delete').on('click', '.img-lsck-anexos-delete', function(e){
			e.preventDefault();
			var control = $(this);
			let parent = $(this).closest(".content-lsck-capturas");

			control.parents('.content-lsck-capturas:first').remove();
		});

	},

    actualizarAutorizacion: function(){
    let formValues = Fn.formSerializeObject('formActualizarAutorizacion');
    let jsonString = { 'data': JSON.stringify(formValues) };
    let url = Autorizacion.url + "actualizarAutorizacion";
    let config = { url: url, data: jsonString };

    $.when(Fn.ajax(config)).then(function (b) {
        ++modalId;
        var btn = [];
        let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        if (b.result == 1) {
            fn = 'Fn.closeModals(' + modalId + '); $("#btn-filtrarAutorizacion").click();';
        }

        btn[0] = { title: 'Aceptar', fn: fn };
        Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
    });
    }

}

Autorizacion.load();
