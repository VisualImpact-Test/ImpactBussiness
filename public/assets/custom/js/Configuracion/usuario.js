var Usuario = {

	frm: 'frm-usuario',
	contentDetalle: 'idContentUsuarios',
	url: 'Configuracion/Usuario/',
	tipo: '',

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarUsuario').click();
		});

		$(document).on('click', '.card-body > ul > li > a', function (e) {
			Usuario.tipo = $(this).data('tipo');
			Usuario.contentDetalle = Usuario.contentDetalle + Usuario.tipo;
		});

		$(document).ready(function () {
			$('.card-body > ul > li > a.active').click();
			$('#btn-filtrarUsuario').click();
		});

		$(document).on('click', '#btn-filtrarUsuario', function () {
			var ruta = 'reporte' + Usuario.tipo;
			var config = {
				'idFrm': Usuario.frm
				, 'url': Usuario.url + ruta
				, 'contentDetalle': Usuario.contentDetalle
			};
			Fn.loadReporte_new(config);
		});

		$(document).off('change', '.file-upload').on('change', '.file-upload', function(e){
			var control = $(this);
			if( control.val() ){
				var num = control.get(0).files.length;
				console.log('num: ' + num);

				list: {
					if( (num) > 1 /*MAX_ARCHIVOS*/ ){
						var message = Fn.message({ type: 2, message: 'Solo se permite 1 archivo como máximo' });
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

						if( size > 1024 /*KB_MAXIMO_ARCHIVO*/ ){
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo 1 MB por captura` });
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
							$('#f_item').val(fileBase.base64);
							$('#f_type').val(fileBase.type);
							$('#f_name').val(fileBase.name);
							$('#imagenFirma').attr('src',fileBase.base64);
							$('#imagenFirma').removeClass('d-none');
            });
					}

				}
			}
		});

		$(document).on('click', '.btn-actualizarFirma', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idUsuario': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Usuario.url + 'formularioUsuarioFirmaRegistro' + Usuario.tipo, 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formUsuarioFirmaRegistro", fn: "Usuario.firmaRegistro()", content: "¿Esta seguro de actualizar la firma?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});
		});
	},

	firmaRegistro: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formUsuarioFirmaRegistro')) };
		let config = { 'url': Usuario.url + 'registrarFirma' + Usuario.tipo, 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarUsuario").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
}

Usuario.load();
