var adjuntDoc = {

	load: function () {
		$(document).off('change', '.file-upload').on('change', '.file-upload', function (e) {
			var control = $(this);
			if (control.val()) {
				var num = control.get(0).files.length;
				console.log('num: ' + num);

				list: {
					if ((num) > 1 /*MAX_ARCHIVOS*/) {
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

					for (var i = 0; i < num; ++i) {
						var size = control.get(0).files[i].size;
						size = Math.round((size / 1024));

						if (size > 1024 /*KB_MAXIMO_ARCHIVO*/) {
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
					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {
							$('#f_item').val(fileBase.base64);
							$('#f_type').val(fileBase.type);
							$('#f_name').val(fileBase.name);
							$('#imagenFirma').attr('src', fileBase.base64);
							$('#imagenFirma').removeClass('d-none');
						});
					}

				}
			}
		});

		$(document).on('click', '#btnEnviar', function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: 'formRegistrarDocumento' })).then(function (a) {
				if (a === true) {
					let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistrarDocumento')) };
					let url = "OrdenServicio/guardarDocumento";
					let config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {
						++modalId;
						var btn = [];
						let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						if (b.result == 1) {
							fn = 'Fn.showModal({ id:' + modalId + ',show:false }); location.reload();';
						}

						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });
					});
				}
			});
		});
	}



}
adjuntDoc.load();
