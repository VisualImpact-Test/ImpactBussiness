var FormularioProveedores = {
	frm: 'frmCotizacionProveedorCabecera',
	contentDetalle: 'content-tb-cotizaciones-proveedor',
	url: 'FormularioProveedor/',
	divPropuesta: '',
	load: function () {
		$(document).ready(function () {
			if ($('#idCotizacion').val()) {
				FormularioProveedores.actualizarTable();
			}
		});

		$(document).on("click", ".btnGuardarCotizacion", () => {
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
		$(document).on("click", ".btnPopupCotizacionesProveedor", () => {
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
		$(document).on("click", ".btnContraoferta", function () {
			let id = $(this).data('id');
			++modalId;

			let jsonString = { 'data': JSON.stringify({ 'id': id }) };
			$.when(Fn.ajax({ 'url': FormularioProveedores.url + 'validarPropuestaExistencia', 'data': jsonString })).then((rpta) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				if (rpta.continuar) {
					let config = { 'url': FormularioProveedores.url + 'viewRegistroContraoferta', 'data': jsonString };
					$.when(Fn.ajax(config)).then((a) => {
						// fn[1] = 'FormularioProveedores.agregarPropuesta(' + id + ');';
						// btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
						// fn[2] = 'Fn.showConfirm({ idForm: "formRegistroTipos", fn: "FormularioProveedores.registrarPropuesta(' + modalId + ')", content: "Su propuesta podra ser tratada por las personas encargadas." });';
						// btn[2] = { title: 'Guardar', fn: fn[2] };
						fn[1] = 'Fn.showConfirm({ idForm: "formRegistroTipos", fn: "FormularioProveedores.registrarPropuesta(' + modalId + ')", content: "Su propuesta podra ser tratada por las personas encargadas." });';
						btn[1] = { title: 'Guardar', fn: fn[1] };
						Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
						FormularioProveedores.divPropuesta = $('#divBase' + id).html();
						$('.dropdownSingleAditions').dropdown({
							allowAdditions: true
						});
					});
				} else {
					let config = { 'url': FormularioProveedores.url + 'contraofertaRegistrado', 'data': jsonString };
					$.when(Fn.ajax(config)).then((a) => {
						console.log(a);
						Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
						FormularioProveedores.divPropuesta = $('#divBase' + id).html();

					});
				}
			});
		});
		$(document).on("click", ".eliminarDatos", function () {
			$(this).parents('.contenido').remove();
		});

		$(document).off('change', '.file-lsck-capturas').on('change', '.file-lsck-capturas', function (e) {
			var control = $(this);
			var data = control.data();
			var id = '';
			var nameImg = '';

			if (data['row']) {
				id = data['row'];
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			} else {
				id = 0;
				name = 'file-item';
				nameType = 'file-type';
				nameFile = 'file-name';
			}
			if (control.val()) {
				var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
				var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
				var num = control.get(0).files.length;

				list: {
					var total = $('input[name="' + name + '[' + id + ']"]').length;
					if ((num + total) > MAX_ARCHIVOS) {
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

					for (var i = 0; i < num; ++i) {
						console.log(control.get(0).files);
						var size = control.get(0).files[i].size;
						size = Math.round((size / 1024));

						if (size > KB_MAXIMO_ARCHIVO) {
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
					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {

							if (fileBase.type.split('/')[0] == 'image') {
								imgFile = fileBase.base64;
								contenedor = content;
							} else if (fileBase.type.split('/')[0] == 'pdf') {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							} else {
								imgFile = `${RUTA_WIREFRAME}file.png`;
								contenedor = content_files;
							}

							var fileApp = '';
							fileApp += '<div class="col-md-2 text-center contentImagen">';
							fileApp += `
									<div class="ui dimmer dimmer-file-detalle">
										<div class="content">
											<p class="ui tiny inverted header">${fileBase.name}</p>
										</div>
									</div>`;
							fileApp += '<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>';
							fileApp += '<input type="hidden" name="' + name + '[' + id + ']" value="' + fileBase.base64 + '">';
							fileApp += '<input type="hidden" name="' + nameType + '[' + id + ']" value="' + fileBase.type + '">';
							fileApp += '<input type="hidden" name="' + nameFile + '[' + id + ']" value="' + fileBase.name + '">';
							fileApp += `<img src="${imgFile}" class="rounded img-lsck-capturas img-responsive img-thumbnail">`;
							fileApp += '</div>';
							contenedor.append(fileApp);
							control.parents('.nuevo').find('.dimmer-file-detalle').dimmer({ on: 'click' });
						});
					}
				}
				control.val('');
			}

		});
		$(document).off('change', '.files-upload').on('change', '.files-upload', function (e) {
			var control = $(this);
			var data = control.data();

			if (control.val()) {
				var num = control.get(0).files.length;

				list: {
					let div = control.parents('.divUploaded:first').find('.content_files');
					var total = div.find('.file_uploaded').length;
					if ((num + total) > MAX_ARCHIVOS) {
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
					for (var i = 0; i < num; ++i) {
						var size = control.get(0).files[i].size;
						size = Math.round((size / 1024));

						if (size > KB_MAXIMO_ARCHIVO) {
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

					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {

							let fileApp = '<div class="file_uploaded">' +
								`<input type="hidden" class="form-control" name="f_base64" value="${fileBase.base64}">` +
								`<input type="hidden" class="form-control" name="f_type" value="${fileBase.type}">` +
								`<input type="text" class="form-control" name="f_name" value="${fileBase.name}">` +
								'</div>';
							div.append(fileApp);
						});
					}
				}
				control.val('');
			}

		});
		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			control.parents('.contentImagen:first').remove();
		});
		$(document).on("click", ".btnVolverProveedor", () => {
			Fn.goToUrl(site_url + 'FormularioProveedor/cotizacionesLista');
		});
		$(document).on("click", ".btnRefreshCotizaciones", () => {
			FormularioProveedores.actualizarTable();
		});

	},
	actualizarTable: function () {
		var ruta = 'cotizacionesRefresh';
		var config = {
			'idFrm': FormularioProveedores.frm
			, 'url': FormularioProveedores.url + ruta
			, 'contentDetalle': FormularioProveedores.contentDetalle
		};

		Fn.loadReporte_new(config);
	},
	calcularTotalSub: function (id) {
		costo = $("[findCosto=" + id + "]");
		cantidad = $("[findCantidad=" + id + "]");
		suma = 0; canTot = 0;
		for (var i = 0; i < costo.length; i++) {
			suma += parseFloat((costo[i].value || 0)) * parseFloat(cantidad[i].value);
			canTot += parseFloat(cantidad[i].value);
		}
		var promedio = suma / canTot;
		$('#costo_' + id).val(promedio);
		$('#costoredondo_' + id).val(promedio.toFixed(2));
		$('#costo_' + id).keyup();
		$('#msgCosto_' + id).removeClass('d-none');
		$('#costo_' + id).attr('readonly', true);
		$('#costoredondo_' + id).attr('readonly', true);
	},
	agregarDetalleServicio: function (t, idCDPD) {
		control = $(t).parents('.divDetalle').find('.dataDetalle');

		$(control).append(`
			<div class="col-md-12 row filaDetalle">
				<div class="col-sm-2">
					<div class="form-group">
						<div class="form-group">
							<h4 class="mb-1">SUCURSAL</h4>
							<input class="form-control" placeholder="Sucursal" name="sucursal[0]">
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<div class="form-group">
							<h4 class="mb-1">RAZON SOCIAL</h4>
							<input class="form-control" placeholder="Razón Social" name="razonSocial[0]">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<div class="form-group">
							<h4 class="mb-1">TIPO DE ELEMENTO</h4>
							<input class="form-control" placeholder="Tipo de elemento" name="tipoElemento[0]">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<div class="form-group">
							<h4 class="mb-1">MARCA</h4>
							<input class="form-control" placeholder="Marca" name="marca[0]">
						</div>
					</div>
				</div>
				<div class="col-md-8 pr-0">
					<div class="form-group">
						<h4 class="mb-1">Descripción</h4>
						<input type="hidden" class="form-control" name="idCDPD[0]" value="${idCDPD}">
						<input type="hidden" class="form-control" name="idCDPDS[0]" value="0">
						<input class="form-control" name="descripcion[0]">
					</div>
				</div>
				
				<div class="col-md-1 px-0">
					<div class="form-group">
						<h4 class="mb-1">Cant</h4>
						<input class="form-control cantidad" name="cantidad[0]" onkeyup="FormularioProveedores.calcularSubItemTotal(this)">
					</div>
				</div>
				<div class="col-md-1 px-0">
					<div class="form-group">
						<h4 class="mb-1">P.U.</h4>
						<input class="form-control costo" name="costo[0]" onkeyup="FormularioProveedores.calcularSubItemTotal(this)">
					</div>
				</div>
				<div class="col-md-2 pl-0">
					<div class="form-group">
						<h4 class="mb-1">STot</h4>
						<input class="form-control subtotal" name="subtotal[0]" data-tiposervicio="Servicio" readonly onchange="FormularioProveedores.calcularSubTotal(${idCDPD}, this)">
					</div>
				</div>
			</div>
		`);
	},
	calcularSubItemTotal: function (t) {
		control = $(t).parents('.filaDetalle');
		cantidad = $(control).find('input.cantidad').val();
		costo = $(control).find('input.costo').val();
		var tot = (cantidad * costo).toFixed(2);
		$(control).find('input.subtotal').val(tot).trigger('change');
	},
	calcularSubTotal: function (idCDPD, t) {
		var subSubtotal = 0;
		var control = $(t).parents('.dataDetalle');
		for (let index = 0; index < $(control).find('.subtotal').length; index++) {
			input = $(control).find('.subtotal')[index];
			valor = $(input).val();
			subSubtotal += parseFloat(valor);
		}
		cantidad = $('#cantidad_' + idCDPD).val();
		newST = subSubtotal / cantidad;

		$('#costo_' + idCDPD).val(newST).trigger('change');
		$('#costoredondo_' + idCDPD).val(newST.toFixed(2)).trigger('keyup');
		// }
	},
	calcularTotal: function (i, cantidad, val, t = null) {
		var tot = cantidad * val;
		var tot_ = tot.toFixed(2);
		$('#valorTotal' + i).val(tot_);
		$('#lb_valorTotal' + i).html('S/. ' + tot_);

		if (t != null) {
			let inCosto = $(t).closest('.row').closest('.cotiDet').find('.filaDetalle').find('input.costo');
			if ($(inCosto[0]).val() == '') {
				for (let i = 0; i < inCosto.length; i++) {
					cost = inCosto[i];
					$(cost).val(val);
				}
				// Se pone en otro Form para que primero actualise los input y luego el stotal
				for (let i = 0; i < inCosto.length; i++) {
					cost = inCosto[i];
					$(cost).trigger('keyup');
				}
			}
		}
	},
	calcularDiasEntrega: function (i, t, fechaHoy) {
		// Tratar de poner esta funcion en Function.js
		Fn.showLoading(true);
		post = $.post(site_url + FormularioProveedores.url + 'contarDiasHabiles', {
			'fechaFin': t.value,
			'diasHabiles': false //Quitar el false si solo son dias habiles
		});
		post.done(function (dias) {
			$('#de_input' + i).val(dias);
			$('#de_input' + i).keyup();
			Fn.showLoading(false);
		});
	},
	calcularDiasValidez: function (i, t, fechaHoy) {
		// Tratar de poner esta funcion en Function.js
		Fn.showLoading(true);
		post = $.post(site_url + FormularioProveedores.url + 'contarDiasHabiles', {
			'fechaFin': t.value,
			'diasHabiles': false //Quitar el false si solo son dias habiles
		});
		post.done(function (dias) {
			$('#dv_input' + i).val(dias);
			$('#dv_input' + i).keyup();
			Fn.showLoading(false);
		});
	},
	calcularFecha: function (i, val, fechaHoy) {
		// Tratar de poner esta funcion en Function.js
		if (parseInt(val) <= 0) {
			$('#dv_input' + i).val('1');
			$('#dv_input' + i).keyup();
		} else {
			Fn.showLoading(true);
			post = $.post(site_url + FormularioProveedores.url + 'calcularFechaDiasHabiles', {
				'dias': val,
				'diasHabiles': false
			});
			post.done(function (fecha) {
				$('#fechaValidez' + i).val(fecha);
				$('#dv_input' + i).focus();
				Fn.showLoading(false);
			});

		}
	},
	calcularFechaEntrega: function (i, val, fechaHoy) {
		// Tratar de poner esta funcion en Function.js
		if (parseInt(val) <= 0) {
			$('#de_input' + i).val('1');
			$('#de_input' + i).keyup();
		} else {
			// Fn.showLoading(true);
			post = $.post(site_url + FormularioProveedores.url + 'calcularFechaDiasHabiles', {
				'dias': val,
				'diasHabiles': false
			});
			post.done(function (fecha) {
				$('#fechaEntrega' + i).val(fecha);
				$('#de_input' + i).focus();
				// Fn.showLoading(false);
			});
		}
	},
	registrarPropuesta: function () {
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
	agregarPropuesta: function (id) {
		$('#divExtra' + id).append(FormularioProveedores.divPropuesta);
	},
	calcularTotalPropuesta: function (t) {
		let cantidad = $(t).parents().find('.cantidad');
		let costo = $(t).parents().find('.costo');
		let total = $(t).parents().find('.total');
		for (var i = 0; i < cantidad.length; i++) {
			total[i].value = parseFloat(cantidad[i].value || 0) * parseFloat(costo[i].value || 0);
		}
	}

}
FormularioProveedores.load();
