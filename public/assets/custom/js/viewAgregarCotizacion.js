var Autorizacion = {

	frm: 'frm-autorizacion',
	contentDetalle: 'idContentAutorizaciones',
	btnFiltrar: '#btn-filtrarAutorizacion',
	url: 'Finanzas/Autorizacion/',

	actualizarAutorizacion: function () {
		let formValues = Fn.formSerializeObject('formActualizarAutorizacion');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Autorizacion.url + "actualizarAutorizacion";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + '); location.reload(); $("#btn-filtrarAutorizacion").click();';
			}

			btn[0] = { title: 'Aceptar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	}
}

var SolicitudCotizacion = {
	url: 'SolicitudCotizacion/',
	load: function () {
		$(document).ready(function () {
			$('.dimmer-file-detalle')
				.dimmer({
					on: 'click'
				});
			$('.ui.stickyProveedores').sticky();


			Cotizacion.actualizarTotal();
		});

		$(document).on('click', '.btn-preview-orden-compra', function () {
			++modalId;

			let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOrdenCompra')) };
			let config = { 'url': SolicitudCotizacion.url + 'formPreviewOrdenCompra', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				Fn.loadSemanticFunctions();

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroOperValidado", fn: "SolicitudCotizacion.registrarOrdenCompra()", content: "¿Esta seguro de generar ordenes de compra para cada proveedor seleccionado?" });';
				fn[2] = 'Fn.showConfirm({ idForm: "formRegistroOperValidado", fn: "SolicitudCotizacion.visualizarOrdenCompraPdf()", content: "Este reporte es solo una vista previa, no se actualizara la información hasta aceptar la operación." });';

				btn[0] = { title: 'Cerrar', fn: fn[0] };
				btn[1] = { title: 'Vizualizar OC', fn: fn[2] };
				btn[2] = { title: 'Aceptar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });

			});
		});
	},
	registrarCotizacion: function (tipoRegistro = 1) {
		let formValues = Fn.formSerializeObject('formRegistroCotizacion');
		formValues.tipoRegistro = tipoRegistro;
		formValues.archivoEliminado = Cotizacion.archivoEliminado;
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
				if (tipoRegistro == 1) {
					fn = 'Fn.closeModals(' + modalId + ');location.reload();';
				} else {
					fn = 'Fn.closeModals(' + modalId + ');Fn.loadPage(`SolicitudCotizacion/`);$("#btn-filtrarCotizacion").click();';
				}
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	registrarOrdenCompra: function () {
		let formValues = Fn.formSerializeObject('formRegistroOperValidado');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = SolicitudCotizacion.url + "registrarOrdenCompra";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');Fn.loadPage(`SolicitudCotizacion/`);$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.data.html, btn: btn, width: '40%' });
		});
	},
	visualizarOrdenCompraPdf: function () {
		let formValues = Fn.formSerializeObject('formRegistroOperValidado');
		let jsonString = { 'data': JSON.stringify(formValues) };
		// console.log(jsonString);
		// console.log(site_url + SolicitudCotizacion.url + 'descargarOrdenCompraPdf');
		Fn.download(site_url + SolicitudCotizacion.url + 'descargarOrdenCompraPdf', jsonString);
	},
	mostrarLugarEntrega: function (t) {
		control = $(t).parents('.fields');
		lugarEntrega = control.find('input.lugarEntrega');
		direccion = $(t).find('option:selected').data('direccion');
		lugarEntrega.val(direccion);
	}

}
var Cotizacion = {

	frm: 'frm-cotizacion',
	contentDetalle: 'idContentCotizacion',
	url: 'Cotizacion/',
	itemServicio: [],
	tachadoDistribucion: [], //items
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlBodySubItem: [],
	htmlCotizacion: '',
	nDetalle: 1,
	anexoEliminado: [],
	archivoEliminado: [],
	subItemEliminado: [],
	repetidoSubItem: [],
	repetidoSubItem2: [],
	detalleEliminado: [],
	gapEmpresas: [],
	controlesOC: [],
	nuevo_item: [],
	objetoParaAgregarImagen: null,
	// solicitanteData: [],

	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCotizacion').click();
		});

		$(document).ready(function () {
			// $('#btn-filtrarCotizacion').click();
			Fn.loadSemanticFunctions();

			$('.simpleDropdown').dropdown();
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			Cotizacion.itemServicio = $.parseJSON($('#itemsServicio').val());
			// Cotizacion.tachadoDistribucion = $.parseJSON($('#tachadoDistribucion').val());

			// Cotizacion.htmlG = $('.default-item').html();

			if ($('#gapEmpresas').val()) {
				Cotizacion.gapEmpresas = JSON.parse($('#gapEmpresas').val());
			}

			if ($('.body-item-vacio').length > 0) {
				Cotizacion.htmlG = $('.body-item-vacio').wrap('<p/>').parent().html();
				$('.body-item-vacio').unwrap();
				$('.body-item-vacio').remove();
			} else {
				Cotizacion.htmlG = $('.default-item').html();
			}


			$.each($('.content-body-sub-item'), (i, v) => {
				let control = $(v);
				let dvfeatures = control.closest('.div-features');
				let tipo = dvfeatures.data('tipo');
				let html = control.html();
				Cotizacion.htmlBodySubItem[tipo] = html;
			});

			Cotizacion.actualizarPopupsTitle();
			Cotizacion.actualizarAutocomplete();

			$.each($('.btnPopupCotizacionesProveedor'), function (i, v) {
				var id = $(v).data('id');
				$(v).popup({
					popup: $(`.custom-popup-${id}`),
					on: 'click'
				})
			});
			// $.each($('.btnPopupPropuestaItem'), function(i,v){    
			// 	var id = $(v).data('id');
			// 	$(v).popup({
			// 		popup : $(`.popup-propuesta-${id}`),
			// 		on    : 'click'
			// 	})
			// });
			// Cotizacion.solicitanteData = $.parseJSON($('#solicitantes').val());
			// Cotizacion.solicitanteInputComplete();
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

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '100%', large: true });

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
			childInserted.find('.title-n-detalle').text(Fn.generarCorrelativo(`${childInsertedNumber}`, 5));
			childInserted.find('.file-lsck-capturas').attr('data-row', childInserted.index());
			console.log(childInserted.find('.file-lsck-capturas'));
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
			$('.ui.checkbox').checkbox();

		});

		// centro costo
		// $(document).ready(function() {
		// 	let centro = $(".centro-ocultado .item").hide();

		// 	//console.log(centro);
		// });

		// $(".centro visible").click(function(){

		// 	console.log('hola mundo');
		//   });

		$(document).ready(function () {
			$(".centro-ocultado .menu").attr("id", "centroCosto_oculto");
			$(".centro-visible .menu").attr("id", "centroCosto_visible");

		});

		$(document).ready(function () {
			$("#centroCosto_oculto .item").hide();

		});




		// $("#centroCosto_visible .item").click(function(){
		// 	alert("go");
		// 	// $("#centroCosto_oculto .item").removeAttr("style", "display");
		//   });

		$(document).on('click', '#centroCosto_visible .item', function () {
			$("#centroCosto_oculto .item").removeAttr("style", "display");
			$("#centroCosto_oculto .seleccion").attr("style", "display").addClass("d-none");

		});

		$(document).ready(function () {

			let $elementoDiv = $('<div class="item text default active selected seleccion" data-value="1">Seleccione</div>')
			$elementoDiv.prependTo('#centroCosto_oculto');
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

			let cantItems = $('.body-item').length;

			if (cantItems <= 1) {


			}

			if (div_locked.find('i').hasClass('lock')) {
				$(this).parents('.body-item').find('.btn-bloquear-detalle').transition('shake');
				return false;
			}
			let idEliminado = body.find('.idCotizacionDetalle').val();
			Cotizacion.detalleEliminado.push(idEliminado);

			body.transition({
				animation: 'slide left',
				duration: '0.4s',
				onComplete: function () {
					body.remove();

					$.each($('.body-item'), function (i, v) {
						Cotizacion.actualizarOnAddRowCampos($(v));
					});
				}
			});
			if (cantItems <= 1) {
				$(".btn-add-row").click();
			}

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

			let idRepetido = parent.find("#monto");
			let buscado = idRepetido.find("#monto2");
			let elementoBuscado = buscado.data('id');

			let idRepetido2 = parent.find("#distribucion");
			let buscado2 = idRepetido2.find("#distribucion2");
			let elementoBuscado2 = buscado2.data('id');

			if (idTipo == COD_DISTRIBUCION.id) {
				let cotizacionInternaForm = parent.find('.cotizacionInternaForm');
				cotizacionInternaForm.val(0); //Sin cotizacion Interna
			} else {
				let codItem = parent.find('.codItems');

				if (codItem !== typeof undefined && codItem > 0) {
					parent.find('.cotizacionInternaForm').val(1);
				}
			}

			if (elementoBuscado) {
				Cotizacion.repetidoSubItem.push(elementoBuscado);

			}

			if (elementoBuscado2) {
				Cotizacion.repetidoSubItem2.push(elementoBuscado2);
			}

			allFeatures.addClass('d-none');
			divFeature.removeClass('d-none');
			$("input").remove("#identificador");

			Cotizacion.cleanDetalle(parent);
		});

		$(document).on('change', '#prioridadForm', function (e) {
			let prioridad = $(this).val();

			if (prioridad == 1) { //Si es prioridad ALTA
				$(motivoForm).attr("patron", 'requerido');
			}
			else {
				$(motivoForm).removeAttr("patron");
			}
		});

		$(document).on('click', '.btn-cotizacion-pdf', function (e) {
			e.preventDefault();

			let $idCotizacion = $(this).parents('tr').data('id');

			Cotizacion.generarRequerimientoPDF($idCotizacion);
		});

		$(document).on('change', '.flagRedondearForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let thisPrecioForm = thisControlParents.find('.precioForm');
			let thisCostoForm = thisControlParents.find('.costoForm');
			let thisSubTotalForm = thisControlParents.find('.subtotalForm');
			let thisCantidadForm = thisControlParents.find('.cantidadForm');

			let costoRedondeadoForm = thisControlParents.find('.costoRedondeadoForm'); // sirve Para el subtotal
			let costoNoRedondeadoForm = thisControlParents.find('.costoNoRedondeadoForm'); // Sirve para el subtotal
			let flagRedondearForm = thisControlParents.find('.flagRedondearForm');

			let costo = Number(thisSubTotalForm.val());
			let enteroSuperior = Math.ceil(costo);
			let flagRedondear = flagRedondearForm.val();

			if (costoRedondeadoForm.val() == 0 && costoNoRedondeadoForm.val() == 0) {
				costoRedondeadoForm.val(enteroSuperior);
				costoNoRedondeadoForm.val(costo);
			}

			let costoRedondeado = Number(costoRedondeadoForm.val());
			let costoNoRedondeado = Number(costoNoRedondeadoForm.val());
			thisCantidadForm.keyup();

		});


		$(document).on('keyup', '.cantidadForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let tipoItem = thisControlParents.find('#tipoItemForm');
			let cantPdvDist = thisControlParents.find('.cantidadPdvSubItemDistribucion');
			let costoForm = thisControlParents.find('.costoForm');
			let precioForm = thisControlParents.find('.precioForm');
			let gapForm = thisControlParents.find('.gapForm');
			let flagCuentaForm = thisControlParents.find('.flagCuentaForm');


			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');

			let cantidad = Number(thisControl.val());
			let costo = Number(costoForm.val());
			let subTotalSinGap = Fn.multiply(cantidad, costo);

			if (gapForm.val() == '' && subTotalSinGap >= GAP_MONTO_MINIMO && gapForm.val() < GAP_MINIMO && flagCuentaForm.val() == 0) {
				gapForm.val(GAP_MINIMO);
			}

			gapForm.keyup();

			// if (tipoItem.val() == COD_SERVICIO.id) {
			// 	gapForm.attr('readonly', false);
			// }
			let precio = Number(precioForm.val());
			let subTotal = Fn.multiply(cantidad, precio);
			let costoDistribucion = 0;
			let costoTotalDistribucionPDV = 0;
			let costoTachadoDistribucion = 0;
			if (tipoItem.val() == COD_DISTRIBUCION.id) {
				costoDistribucion = Number($("#costoDistribucion").val());
				cantPdv = (cantPdvDist.val() == 0 ? 1 : cantPdvDist.val());
				costoTotalDistribucionPDV = Fn.multiply(costoDistribucion, cantPdv);

				let trTachadoDistribucion = thisControlParents.find('.chkTachadoDistribucion:checked').closest('tr');
				if (trTachadoDistribucion.length !== 0) {
					costoTachadoDistribucion = trTachadoDistribucion.data('subtotal');
				}

				subTotal = Number(subTotal + costoTotalDistribucionPDV + costoTachadoDistribucion);
			}
			////////////
			let flagRedondearForm = thisControlParents.find('.flagRedondearForm');
			let enteroSuperior = Math.ceil(subTotal);
			let flagRedondear = flagRedondearForm.val();

			if (flagRedondear == 1) subTotal = enteroSuperior;
			////////////
			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));


			Cotizacion.actualizarTotal();
		});
		$(document).on('keyup', '.cantidadSubItemDistribucion', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let costoFormLabel = thisControlParents.find('.costoFormLabel');
			let cantidadForm = thisControlParents.find('.cantidadForm');
			let costoTipoServicioForm = thisControlParents.find('.costoTipoServicio');
			let cantidadTipoServicio = Number(thisControl.val());
			let costoTipoServicio = Number(costoTipoServicioForm.val());

			let subTotalTipoServicio = Fn.multiply(cantidadTipoServicio, costoTipoServicio);

			costoForm.val(subTotalTipoServicio);
			costoFormLabel.val(moneyFormatter.format(subTotalTipoServicio));

			cantidadForm.keyup();

		});

		$(document).on('keyup', '.costoTransporte', function (e) {
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			// input con funcion para el calculo del subtotal
			let cantidadForm = thisControlParents.find('.cantidadForm');
			// Los valores a sumarse
			let costosForm = thisControlParents.find('.costoTransporte');
			// Los campos donde se escribira la nueva información
			let costoForm = thisControlParents.find('.costoForm');
			let costoFormLabel = thisControlParents.find('.costoFormLabel');

			costoAcumulado = 0;
			calcularCosto = true;
			$.each(costosForm, (i, v) => {
				if (Number($(v).val()) == 0) calcularCosto = false;
				costoAcumulado += Number($(v).val());
			});

			if (calcularCosto) {
				costoForm.val(costoAcumulado);
				costoFormLabel.val(moneyFormatter.format(costoAcumulado));
				cantidadForm.keyup();
			}
		});

		$(document).on('keyup', '.cantidadSubItemAcumulativo', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let thisControlParentsSub = thisControl.parents('.content-body-sub-item');
			let cantidadForm = thisControlParents.find('.cantidadForm');

			let cantAcumulada = 0;
			$.each(thisControlParentsSub.find('.body-sub-item'), (i, v) => {
				cantAcumulada += Number($(v).find('.cantidadSubItemAcumulativo').val());

			});

			cantidadForm.val(cantAcumulada);
			cantidadForm.keyup();

		});

		$(document).on('focusout', '.costoFormLabelEditable', function (e) {
			e.preventDefault();

			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');
			let costoFormLabel = thisControlParents.find('.costoFormLabel');
			let precioForm = thisControlParents.find('.precioForm');
			let precioFormLabel = thisControlParents.find('.precioFormLabel');
			let fieldPrecioFormLabel = precioFormLabel.closest('.field');
			let cantidadForm = thisControlParents.find('.cantidadForm');
			let gapForm = thisControlParents.find('.gapForm');

			Cotizacion.controlesOC.gapForm = gapForm;
			Cotizacion.controlesOC.costoForm = costoForm;
			Cotizacion.controlesOC.costoFormLabel = thisControl;

			let costo = Number(thisControl.val());
			let precio = Number(precioForm.val());
			let costoAnterior = Number(costoForm.val());

			if (costo >= precio) {
				thisControl.val(costoAnterior);
				fieldPrecioFormLabel.transition('shake');
				$("#nagPrecioValidacion").nag({
					persist: true
				});
				return false;
			}
			let gapActual = (((precio - costo) * 100) / costo).toFixed(2);
			if (costo <= costoAnterior) {
				console.log('No hay problema');
				gapForm.val(gapActual);
				// costoForm.val(costo);
				return false;
			}


			let idCotizacionDetalle = thisControlParents.data('id');
			let config = {
				costo,
				gapActual,
				idCotizacionDetalle,
			}

			++modalId;
			let btn = [];
			let fn = [];
			let message = Fn.message(
				{
					type: 3,
					message: 'Este cambio de costo, requiere autorizacion. ¿Desea enviar la solicitud?'
				});
			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });Cotizacion.restaurarCosto(' + costoAnterior + ');';
			btn[0] = { title: 'Cerrar', fn: fn[0] };

			fn[1] = 'Fn.showModal({ id:' + modalId + ',show:false });Cotizacion.solicitarAutorizacion(' + JSON.stringify(config) + ')';
			btn[1] = { title: 'Aceptar', fn: fn[1] };

			Fn.showModal({ id: modalId, show: true, title: 'Alerta', frm: message, btn: btn, width: '40%' });
			// cantidadForm.keyup();

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

			let costoRedondeadoForm = thisControlParents.find('.costoRedondeadoForm');
			let costoNoRedondeadoForm = thisControlParents.find('.costoNoRedondeadoForm');
			let flagRedondearForm = thisControlParents.find('.flagRedondearForm');

			let precioForm = thisControlParents.find('.precioForm');
			let precioFormLabel = thisControlParents.find('.precioFormLabel');

			let costo = Number(costoForm.val());
			let cantidad = Number(cantidadForm.val());
			let subTotalSinGap = Fn.multiply(cantidad, costo);

			// let enteroSuperior = Math.ceil(costo);
			// let flagRedondear = flagRedondearForm.val();

			// if (flagRedondear == 1) costo = enteroSuperior;

			let gap = Number(thisControl.val());
			let precio = (costo + (costo * (gap / 100)));
			let subTotal = Fn.multiply(cantidad, precio);


			precioForm.val(precio);
			precioFormLabel.val(moneyFormatter.format(precio));

			let enteroSuperior = Math.ceil(subTotal);
			let flagRedondear = flagRedondearForm.val();

			if (flagRedondear == 1) subTotal = enteroSuperior;
			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));
			Cotizacion.actualizarTotal();
		});
		$(document).on('keyup', '.gapFormOperaciones', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let tipoItem = thisControlParents.find('#tipoItemForm');
			let cantPdvDist = thisControlParents.find('.cantidadPdvSubItemDistribucion');
			let costoForm = thisControlParents.find('.costoForm');
			let cantidadForm = thisControlParents.find('.cantidadForm');
			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');
			let flagCuentaForm = thisControlParents.find('.flagCuentaForm');
			let flagRedondearForm = thisControlParents.find('.flagRedondearForm');

			let precioForm = thisControlParents.find('.precioForm');
			let precioFormLabel = thisControlParents.find('.precioFormLabel');

			let gap = Number(thisControl.val());
			let costo = Number(costoForm.val());
			let cantidad = Number(cantidadForm.val());

			let subTotalSinGap = Fn.multiply(cantidad, costo);
			//Si el monto es mayor a 1500, el gap no puede ser menor al 15%
			if (subTotalSinGap >= GAP_MONTO_MINIMO && thisControl.val() < GAP_MINIMO && flagCuentaForm.val() == 0) {
				thisControl.val(GAP_MINIMO).trigger('keyup');
				$("#nagGapValidacion").nag({
					persist: true
				});
				return false;
			}
			let precio = (costo + (costo * (gap / 100)));
			let subTotal = Fn.multiply(cantidad, precio);

			let costoDistribucion = 0;
			let costoTotalDistribucionPDV = 0;
			let costoTachadoDistribucion = 0;
			if (tipoItem.val() == COD_DISTRIBUCION.id) {
				costoDistribucion = Number($("#costoDistribucion").val());
				cantPdv = (cantPdvDist.val() == 0 ? 1 : cantPdvDist.val());
				costoTotalDistribucionPDV = Fn.multiply(costoDistribucion, cantPdv);

				let trTachadoDistribucion = thisControlParents.find('.chkTachadoDistribucion:checked').closest('tr');
				if (trTachadoDistribucion.length !== 0) {
					costoTachadoDistribucion = trTachadoDistribucion.data('subtotal');
				}
				subTotal = Number(subTotal + costoTotalDistribucionPDV + costoTachadoDistribucion);
			}

			precioForm.val(precio);
			precioFormLabel.val(moneyFormatter.format(precio));

			let enteroSuperior = Math.ceil(subTotal);
			let flagRedondear = flagRedondearForm.val();

			if (flagRedondear == 1) subTotal = enteroSuperior;

			subTotalForm.val(subTotal);
			subTotalFormLabel.val(moneyFormatter.format(subTotal));
			Cotizacion.actualizarTotal();
		});

		$(document).on('change', 'input[name=upload_orden_compra]', function (e) {
			let idCotizacion = $(this).closest('tr').data('id');
			var archivos = document.getElementById("upload_orden_compra[" + idCotizacion + "]");

			//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
			var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
			//Creamos una instancia del Objeto FormDara.
			var archivos = new FormData();
			/* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
			Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como
			indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
			for (i = 0; i < archivo.length; i++) {
				archivos.append('archivo' + i, archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
			}
			$.ajax({
				url: site_url + Cotizacion.url + 'guardarArchivo/', //Url a donde la enviaremos
				type: 'POST', //Metodo que usaremos
				contentType: false, //Debe estar en false para que pase el objeto sin procesar
				data: archivos, //Le pasamos el objeto que creamos con los archivos
				processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
				cache: false, //Para que el formulario no guarde cache
				beforeSend: function () { Fn.showLoading(true) },
			}).done(function (a) {//Escuchamos la respuesta y continuamos
				Fn.showLoading(false);

				a = $.parseJSON(a);
				var data = {};
				data = a;
				data.idCotizacion = idCotizacion;

				var jsonString = { 'data': JSON.stringify(data) };
				var url = Cotizacion.url + 'guardarArchivoBD';
				var config = { url: url, data: jsonString };

				$.when(Fn.ajax(config)).then(function (a) {
					if (a.result != 2) {
						++modalId;
						var btn = [];
						var fn = [];

						if (a.result == 0) {
							fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.closeModals(' + modalId + ');';
							btn[0] = { title: 'Aceptar', fn: fn[0] };
						}
						else {
							fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
							btn[0] = { title: 'Aceptar', fn: fn[0] };
						}

						Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.data.html, btn: btn, width: a.data.width });
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

			if (codItem == '') return false;

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

		$(document).off('change', '.file-lsck-capturas').on('change', '.file-lsck-capturas', function (e) {
			var control = $(this);

			var data = control.data();
			// var frm = frmLiveAuditoria;

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
					let file = '';
					let imgFile = '';
					let contenedor = '';
					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {

							if (fileBase.type.split('/')[0] == 'image') {
								imgFile = fileBase.base64;
								contenedor = content;
							} else if (fileBase.type.split('/')[1] == 'pdf') {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							} else {
								imgFile = `${RUTA_WIREFRAME}file.png`;
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
							fileApp += '<input class="' + name + '" type="hidden" name="' + name + '[' + id + ']"  value="' + fileBase.base64 + '">';
							fileApp += '<input class="' + nameType + '" type="hidden" name="' + nameType + '[' + id + ']"  value="' + fileBase.type + '">';
							fileApp += '<input class="' + nameFile + '" type="hidden" name="' + nameFile + '[' + id + ']"  value="' + fileBase.name + '">';
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
		$(document).off('change', '.file-lsck-capturas-anexos').on('change', '.file-lsck-capturas-anexos', function (e) {
			var control = $(this);

			let name = 'anexo-file';
			let nameType = 'anexo-type';
			let nameFile = 'anexo-name';


			if (control.val()) {
				var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
				var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
				var num = control.get(0).files.length;

				list: {
					var total = $(`input[name=${name}]`).length;
					if ((num + total) > MAX_ARCHIVOS) {
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
					let file = '';
					let imgFile = '';
					let contenedor = '';
					for (var i = 0; i < num; ++i) {
						file = control.get(0).files[i];
						Fn.getBase64(file).then(function (fileBase) {

							if (fileBase.type.split('/')[0] == 'image') {
								imgFile = fileBase.base64;
								contenedor = content;
							} else if (fileBase.type.split('/')[1] == 'pdf') {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							} else {
								imgFile = `${RUTA_WIREFRAME}file.png`;
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
							fileApp += '<input type="hidden" name="' + name + '"  value="' + fileBase.base64 + '">';
							fileApp += '<input type="hidden" name="' + nameType + '"  value="' + fileBase.type + '">';
							fileApp += '<input type="hidden" name="' + nameFile + '"  value="' + fileBase.name + '">';
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

		$(document).off('click', '.img-lsck-capturas').on('click', '.img-lsck-capturas', function (e) {
			e.preventDefault();
		});

		$(document).off('click', '.img-lsck-capturas-delete').on('click', '.img-lsck-capturas-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			let parent = $(this).closest(".content-lsck-capturas");
			let idEliminado = parent.data('id');
			if (idEliminado) {
				Cotizacion.archivoEliminado.push(idEliminado);
			}

			control.parents('.content-lsck-capturas:first').remove();
		});
		$(document).off('click', '.img-lsck-anexos-delete').on('click', '.img-lsck-anexos-delete', function (e) {
			e.preventDefault();
			var control = $(this);
			let parent = $(this).closest(".content-lsck-capturas");
			let idEliminado = parent.data('id');

			if (idEliminado) {
				Cotizacion.anexoEliminado.push(idEliminado);
			}

			control.parents('.content-lsck-capturas:first').remove();
		});

		$(document).on('click', '.btnSolicitarCotizacion', function () {
			++modalId;

			if ($('.proveedorSolicitudForm').find('select').val().length <= 0) {
				$('.proveedorSolicitudForm').transition('shake')
				return false;
			}

			if (!$('.checkItem').is(' :checked')) {
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
			let imagenesExtraForm = $(this).parents('.nuevo').find('div.extraImages');
			let proveedoresForm = $(this).parents('.nuevo').find('.proveedoresForm');
			let diasEntregaItemForm = $(this).parents('.nuevo').find('.diasEntregaItemForm');
			let divItemServicio = $(this).parents('.nuevo').find('.divItemServicio');

			let precio = $(this).find('.txtCostoProveedor').val();
			let idCotizacionDetalleProveedorDetalle = $(this).find('.txtCodProveedorCotizacion').val();
			let proveedorElegido = $(this).find('.txtProveedorElegido').val();
			let diasEntregaElegido = $(this).find('.txtDiasEntregaItemProveedor').val();
			let proveedorElegidoName = $(this).find('.txtProveedorElegidoName').val();
			let imagenesDeProveedor = $(this).find('.elegirImagenes').html();
			let jsonProveedorSubCotizacion = $(this).find('.txtSubProveedorCotizacion').length >= 1 ? $(this).find('.txtSubProveedorCotizacion').val() : '';
			let proveedorSubCotizacion = jsonProveedorSubCotizacion != '' ? JSON.parse(jsonProveedorSubCotizacion) : [];

			let subDetalleServicio = $(this).find('.txtDetalleTipoServicio').length > 0 ? JSON.parse($(this).find('.txtDetalleTipoServicio').html()) : [];
			let idCotizacionDetalle = $(this).parents('.nuevo').find('.txtIdCotizacionDetalle').val();
			var html = '';
			$.each(subDetalleServicio, function (k, v) {
				html += `
				<div class="fields body-sub-item body-sub-item-servicio">
					<div class="fields field sixteen">
						<div class="four wide field">
							<div class="ui sub header">Sucursal</div>
							<input class="nombreSubItem" name="newSucursaleSubItemServicio[${idCotizacionDetalle}]" placeholder="Sucursal" value="${v.sucursal}">
						</div>
						<div class="four wide field">
							<div class="ui sub header">Razon Social</div>
							<input class="nombreSubItem" name="newRazonSocialSubItemServicio[${idCotizacionDetalle}]" placeholder="Razon Social" value="${v.razonSocial}">
						</div>
						<div class="four wide field">
							<div class="ui sub header">Tipo Elemento</div>
							<input class="nombreSubItem" name="newTipoElementoSubItemServicio[${idCotizacionDetalle}]" placeholder="Tipo Elemento" value="${v.tipoElemento}">
						</div>
						<div class="four wide field">
							<div class="ui sub header">Marca</div>
							<input class="nombreSubItem" name="newMarcaSubItemServicio[${idCotizacionDetalle}]" placeholder="Marca" value="${v.marca}">
						</div>
					</div>
					<div class="fields field sixteen">
						<div class="five wide field">
							<div class="ui sub header">Descripción</div>
							<input class="nombreSubItem" name="newNombreSubItemServicio[${idCotizacionDetalle}]" placeholder="Nombre" value="${v.descripcion}">
						</div>
						<div class="five wide field">
							<div class="ui sub header">Cantidad</div>
							<input readonly="readonly" class="onlyNumbers cantidadSubItem" name="newCantidadSubItemServicio[${idCotizacionDetalle}]" placeholder="0" value="${v.cantidad}" readonly>
						</div>
						<div class="three wide field">
							<div class="ui sub header">Costo</div>
							<input readonly="readonly" class="onlyNumbers cantidadSubItem" name="newCostoSubItemServicio[${idCotizacionDetalle}]" placeholder="0" value="${v.costo}" readonly>
						</div>
						<div class="three wide field">
							<div class="ui sub header">Subtotal</div>
							<input readonly="readonly" class="onlyNumbers cantidadSubItem" name="newSubtotalSubItemServicio[${idCotizacionDetalle}]" placeholder="0" value="${v.subTotal}" readonly>
						</div>
					</div>
				</div>
				`;
			});
			divItemServicio.html(html);

			let bodySubItem = $(this).parents('.nuevo').find('.body-sub-item');
			$.each(bodySubItem, function (k, v) {
				let idCotizacionDetalleSub = $(v).find('.idCotizacionDetalleSubForm');
				let costoSubItem = $(v).find('.costoSubItem');
				let subtotalSubItem = $(v).find('.subtotalSubItem');

				let detalleSubItem = proveedorSubCotizacion.find((detalle) => {
					return (detalle.idCotizacionDetalleSub == idCotizacionDetalleSub.val())
				})

				if (detalleSubItem !== void 0) { // !== undefined
					costoSubItem.val(detalleSubItem.costo);
					subtotalSubItem.val(detalleSubItem.subTotal);
				}

			});

			$.post(site_url + 'SolicitudCotizacion/cerrarCotizacionProveedor', {
				idCotizacionDetalleProveedorDetalle: idCotizacionDetalleProveedorDetalle
			}, function (data) {
				// console.log('cotizacion cerrada ');
				// console.log(data);
			});

			costoForm.val(precio);
			costoFormLabel.val(moneyFormatter.format(precio));
			diasEntregaItemForm.val(diasEntregaElegido);
			proveedorForm.val(proveedorElegido);
			proveedoresForm.val(proveedorElegidoName);
			imagenesExtraForm.html(imagenesDeProveedor);
			cantidadForm.keyup();
			gapForm.keyup();

			Cotizacion.actualizarTotal();
		});

		$(document).on('click', '.btnConsultarItemProveedor', function () {
			++modalId;
			let data = {
				'idCotizacionDetalle': $(this).data('cot'),
				'idProveedor': $(this).data('pro')
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SolicitudCotizacion.url + 'formularioVisualizacionCotizacionProveedorItems', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				SolicitudCotizacion.actualizarAutocomplete();
			});
		});
		$(document).on('click', '.btnCotizacionesProveedores', function () {
			++modalId;
			let data = {
				'idCotizacionDetalle': $(this).data('id')
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SolicitudCotizacion.url + 'formularioVisualizacionCotizacionProveedorItems', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				SolicitudCotizacion.actualizarAutocomplete();
			});
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

			let gen_nuevo = childInserted.find('#genero .item-4');
			// hola3 = hola.remove('option');
			gen_nuevo.before('<option class="item-5" value="">seleccione</option>');
			gen_nuevo.remove('option');

		});
		$(document).on('click', '.btn-eliminar-sub-item', function () {
			let control = $(this);
			let parent = control.closest('.content-body-sub-item');
			let element = control.closest('.body-sub-item');
			let idEliminado = element.data('id');

			// Validar si es transporte
			let thisControlParents = control.parents('.nuevo');
			let costosForm = thisControlParents.find('.costoTransporte');
			let costoForm = thisControlParents.find('.costoForm');
			let costoFormLabel = thisControlParents.find('.costoFormLabel');

			calcularCosto = true;
			esTransporte = false;
			if (costosForm.length > 0) {
				esTransporte = true;
			}
			// Fin: Validar si es transporte --> Continua: despues de eliminar la fila para volver a calcular el costo total.


			if (idEliminado) {
				Cotizacion.subItemEliminado.push(idEliminado);
			}

			if (parent.find('.body-sub-item').length <= 1) {

				element.find(':input').val('');
				let gen = element.find('#genero');

				let gen_nuevo_item = gen.find('.dropdown')
				gen_nuevo_item.html(
					'<option class="item-4" value="">seleccione</option>' +
					'<option class="item" value="1">Hombre</option>' +
					'<option class="item" value="2">Mujer</option>' +
					'<option class="item" value="3">Unisex</option>'
				);

				return false;
			}

			element.remove();
			// Validar Transporte
			if (esTransporte) {
				let newCostosForm = thisControlParents.find('.costoTransporte');
				let cantidadForm = thisControlParents.find('.cantidadForm');
				costoAcumulado = 0;

				$.each(newCostosForm, (i, v) => {
					console.log(Number($(v).val()));
					if (Number($(v).val()) == 0) calcularCosto = false;
					costoAcumulado += Number($(v).val());
				});

				if (calcularCosto) {
					costoForm.val(costoAcumulado);
					costoFormLabel.val(moneyFormatter.format(costoAcumulado));
				}
				cantidadForm.keyup();
			}

		});
		$(document).on('change', '.tipoServicioForm', function () {
			let control = $(this);
			let parent = control.closest('.div-features');
			let costo = control.find('option:selected').data('costo');
			let unidadMedida = control.find('option:selected').data('unidadmedida');
			let idUnidadMedida = control.find('option:selected').data('idunidadmedida');
			let idTipoServicioUbigeo = control.find('option:selected').data('idtiposervicioubigeo');

			let costoForm = parent.find('.costoTipoServicio');
			let unidadMedidaForm = parent.find('.unidadMedidaTipoServicio');
			let idUnidadMedidaForm = parent.find('.unidadMedidaSubItem');
			let cantidadFormSubItem = parent.find('.cantidadSubItemDistribucion');

			costoForm.val(costo);
			unidadMedidaForm.val(unidadMedida);
			idUnidadMedidaForm.val(idUnidadMedida);

			// Para el Check
			let check = parent.find('.checkForm');
			if (idTipoServicioUbigeo == '1') { // Si es Urbano
				if (check.is(":checked")) { // Si esta marcado
					check.click(); // Para desmarcar
				}
			} else { // Si es NO Urbano
				if (!check.is(":checked")) { // Si NO esta marcado
					check.click(); // Para marcar
				}
			}

			cantidadFormSubItem.keyup();


		});
		$(document).on('change', '.itemLogisticaForm', function () {
			let control = $(this);
			let controlParent = control.parents('.nuevo');
			let parent = control.closest('.div-features');
			let peso = control.find('option:selected').data('pesologistica');
			let cantidadForm = controlParent.find('.cantidadForm');

			let pesoCantidadForm = parent.find('.cantidadSubItemDistribucion');
			let pesoCantidadRealForm = parent.find('.cantidadRealSubItem');
			let cantidadFormSubItem = parent.find('.cantidadSubItemDistribucion');

			pesoCantidadForm.val(peso);
			pesoCantidadRealForm.val(peso);
			cantidadFormSubItem.keyup();

			let idItem = control.find('option:selected').val();

			//Llenamos la tabla de tachado
			htmlTachado = '';
			Cotizacion.tachadoDistribucion.filter((tachado) => {
				if (tachado.idItem == idItem) {
					let costoLabel = moneyFormatter.format(Number(tachado.costoDia));
					let subTotalTachado = (Number(tachado.dias) * Number(tachado.personas)) * Number(tachado.costoDia);
					let subTotalTachadoLabel = moneyFormatter.format(subTotalTachado);
					htmlTachado += `<tr data-id='${tachado.idDistribucionTachado}' data-subtotal='${subTotalTachado}' >`;

					htmlTachado += `
						<td> 
							<div class="ui radio checkbox dvTachadoDistribucion">
								<input value='${tachado.idDistribucionTachado}' class='chkTachadoDistribucion' type="radio" name="chkTachado">
								<label></label>
							</div>
						</td>`;
					htmlTachado += `<td> ${tachado.limiteInferior} - ${tachado.limiteSuperior}</td>`;
					htmlTachado += `<td> ${tachado.dias}</td>`;
					htmlTachado += `<td> ${tachado.personas}</td>`;
					htmlTachado += `<td> ${costoLabel}</td>`;
					htmlTachado += `<td> ${subTotalTachadoLabel}</td>`;
					htmlTachado += `</tr>`;
				}
			});

			parent.find('.tbDistribucionTachado').find('tbody').html(htmlTachado);
			if (htmlTachado != '') {
				parent.find('.tbDistribucionTachado').removeClass('d-none');
			} else {
				parent.find('.tbDistribucionTachado').addClass('d-none');
			}

			Cotizacion.actualizarOnAddRowCampos(controlParent);
			cantidadForm.keyup();

		});

		$(document).on('keyup', '.items', function () {
			let control = $(this);
			let val = control.val();
			let parent = control.closest('.nuevo');
			if (val.length == 0) {
				Cotizacion.cleanDetalle(parent);
			}
		});

		$(document).on('change', '#cuentaForm', function () {
			let control = $(this);

			let cod = control.val();

			let gap = 0;

			$.each(Cotizacion.gapEmpresas, (k, v) => {
				if (v.idEmpresa == cod) {
					gap = v.gap;
					return;
				}
			});

			if (gap) {
				$('.gapForm').val(gap);
				$('.cantidadForm').keyup();
			} else {
				$('.gapForm').val('');
			}

		});
		$(document).on('click', '.btnPopupPropuestaItem', function () {

			++modalId;
			let data = {
				'idCotizacionDetalle': $(this).data('id')
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SolicitudCotizacion.url + 'frmPropuestasItem', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

			});

		});
		$(document).on('click', '.btnAutorizarCosto', function () {

			++modalId;
			let id = $(this).parents('.nuevo').find('.idAutorizacion').val();
			let data = {
				id
			};
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Autorizacion.url + 'frmActualizarAutorizacion', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				if (a.flagUpdate) {
					fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionProveedores", fn: "Autorizacion.actualizarAutorizacion()", content: "¿Esta seguro de actualizar esta autorización?" });';
					btn[1] = { title: 'Actualizar', fn: fn[1] };
				}

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
			});

		});
		$(document).on('click', '.rowPropuesta', function () {

			++modalId;
			let data = {
				'idPropuestaItem': $(this).data('id'),
				'idCotizacionDetalle': $(this).data('idcotizaciondetalle'),
				'dataPropuesta': JSON.parse($(this).find('.jsonPropuesta').val()),
			};


			Fn.showConfirm({ fn: `Cotizacion.actualizarPropuestaItem(${JSON.stringify(data)})`, content: `¿Desea confirmar esta propuesta para reemplazar el item elegido?` });

		});
		$(document).on('click', '.dvTachadoDistribucion', function () {

			let control = $(this);
			let parent = control.closest('tr');
			let controlParents = control.parents('.nuevo');
			let cantidadForm = controlParents.find('.cantidadForm');

			cantidadForm.keyup();
		});
		$(document).on('click', '.checkValidarOC', function () {
			// Input Check
			let check = $(this).find('.checkForm');

			// Div donde se tiene los campos adicionales
			let div = $(this).parents('.div-features').find('.divAddParaOC');

			// Si(IF) el check esta activo mostrar los campos necesario. En caso contrario (ELSE) ocultarlo.
			// La clase "d-none" sirve para ocultar.
			if (check.is(":checked")) {
				div.removeClass('d-none');
			} else {
				div.addClass('d-none');
			}

		});




	},

	actualizarPropuestaItem: function (data) {
		let parent = $(`.idCotizacionDetalleForm-${data.idCotizacionDetalle}`).closest('.nuevo');

		let costoForm = parent.find('.costoForm')
		let costoFormLabel = parent.find('.costoFormLabel');
		let cantidadForm = parent.find('.cantidadForm');
		let gapForm = parent.find('.gapForm');
		let proveedorForm = parent.find('.idProveedor');
		let proveedoresForm = parent.find('.proveedoresForm');
		let nameItemForm = parent.find('.nameItemForm');

		let precio = Number(data.dataPropuesta.costo);
		let proveedorElegido = data.dataPropuesta.idProveedor;
		let proveedorElegidoName = data.dataPropuesta.proveedor;
		// let jsonProveedorSubCotizacion = $(this).find('.txtSubProveedorCotizacion').val();
		// let proveedorSubCotizacion = JSON.parse(jsonProveedorSubCotizacion);

		// let bodySubItem = parent.find('.body-sub-item');

		// $.each(bodySubItem,function(k,v){
		// 	let idCotizacionDetalleSub = $(v).find('.idCotizacionDetalleSubForm');
		// 	let costoSubItem = $(v).find('.costoSubItem');
		// 	let subtotalSubItem = $(v).find('.subtotalSubItem');

		// 	let detalleSubItem = proveedorSubCotizacion.find((detalle) => {
		// 		return (detalle.idCotizacionDetalleSub == idCotizacionDetalleSub.val())
		// 	})

		// 	costoSubItem.val(detalleSubItem.costo);
		// 	subtotalSubItem.val(detalleSubItem.subTotal);

		// });

		nameItemForm.val(data.dataPropuesta.nombre);

		costoForm.val(precio);
		costoFormLabel.val(moneyFormatter.format(precio));

		proveedorForm.val(proveedorElegido);
		proveedoresForm.val(proveedorElegidoName);

		cantidadForm.keyup();
		gapForm.keyup();

		Cotizacion.actualizarTotal();

		Fn.closeModals(modalId);
	},

	restaurarCosto: function (costoAnterior) {
		Cotizacion.controlesOC.costoForm.val(costoAnterior);
		Cotizacion.controlesOC.costoFormLabel.val(costoAnterior);
	},

	solicitarAutorizacion: function (configOC) {

		Cotizacion.controlesOC.gapForm.val(configOC.gapActual);
		Cotizacion.controlesOC.costoForm.val(configOC.costo);
		let idCotizacionDetalle = configOC.idCotizacionDetalle;

		++modalId;
		let data = Fn.formSerializeObject('formRegistroOrdenCompra');
		data.idCotizacionDetalle = idCotizacionDetalle;
		data.nuevoCosto = configOC.costo;
		data.nuevoGap = configOC.gapActual;
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Cotizacion.url + 'registrarSolicitudAutorizacion', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');location.reload();$("#btn-filtrarCotizacion").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	actualizarCotizacion: function () {
		++modalId;
		let data = Fn.formSerializeObject('formActualizacionCotizacions');
		data.archivoEliminado = Cotizacion.archivoEliminado;
		let jsonString = { 'data': JSON.stringify(data) };
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
				//Tipo de Item
				control.find(".idTipoItem").val(ui.item.tipo);
				// control.find(".idTipoItem").addClass('read-only');
				control.find(".idTipoItem").dropdown('set selected', ui.item.tipo);

				control.find(".caracteristicasCliente").val(ui.item.caracteristicas);
				control.find(".flagCuentaSelect").dropdown('set selected', ui.item.flagCuenta);
				control.find(`.div-feature-${ui.item.tipo}`).removeClass('d-none');
				//Llenamos los items con el nombre
				$(this).val(ui.item.label);
				//Llenamos una caja de texto invisible que contiene el ID del Artículo
				control.find(".codItems").val(ui.item.value);
				//Llenamos el precio actual
				if (ui.item.costo == null || ui.item.semaforoVigencia == "red") {
					ui.item.costo = 0;
				}
				if (ui.item.cantidadImagenes > 0) {
					Cotizacion.alertaParaAgregarItems(control, ui.item);
				}


				control.find(".costoForm").val(ui.item.costo == 0 ? '' : ui.item.costo);
				control.find(".costoFormLabel").text((ui.item.costo == 0) ? '' : ui.item.costo);

				//Llenar para poder redondear
				control.find('.costoRedondeadoForm').val(Math.ceil(ui.item.costo));
				control.find('.costoNoRedondeadoForm').val(ui.item.costo);
				control.find(".flagRedondearForm").change(); //evento para que se redondee
				//Llenamos el estado
				control.find(".estadoItemForm").removeClass('fa-sparkles');
				control.removeClass('nuevoItem');
				control.find(".idEstadoItemForm").val(1);
				control.find(".cotizacionInternaForm").val(`${ui.item.cotizacionInterna}`)

				//Llenamos el proveedor
				control.find(".proveedorForm").text(ui.item.proveedor);
				control.find(".idProveedor").val(ui.item.idProveedor);

				//LLenar semaforo

				control.find(".semaforoForm").addClass('semaforoForm-' + ui.item.semaforoVigencia);

				control.find('.semaforoForm').popup({ content: `Vigencia: ${ui.item.diasVigencia} días` });

				//Validar boton ver caracteristicas del articulo

				control.find(".verCaracteristicaArticulo").removeClass(`slash`);

				//Validacion ID
				control.find(".cantidadForm").attr('readonly', false);

				let $cod = ui.item.value;
				if ($cod != '') {
					// $(this).attr('readonly', 'readonly');
					control.find('.costoForm').attr('readonly', 'readonly');
					control.find("select[name=tipoItemForm]").closest('td').addClass('disabled');
				}
			},
			appendTo: "#modal-page-" + Cotizacion.modalIdForm,
			max: 5,
			minLength: 3,
		});
	},
	alertaParaAgregarItems: function (control, item) {
		console.log(item);

		++modalId;
		var btn = [];
		let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
		Cotizacion.objetoParaAgregarImagen = control;
		let fn1 = `Fn.showModal({ id: ${modalId} ,show:false }); Cotizacion.agregarImagenes(${item.value});`;

		btn[0] = { title: 'No en este momento', fn: fn, class: 'btn-outline-danger' };
		btn[1] = { title: 'Aceptar', fn: fn1 };
		Fn.showModal({ id: modalId, show: true, title: 'Agregar Imagenes del Item a la cotización', content: "Desea utilizar las imagenes del Item", btn: btn, width: '33%' });

		$('.simpleDropdown').dropdown();
		$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
	},
	agregarImagenes: function (id) {
		$.post(site_url + Cotizacion.url + 'getImagenes', {
			idItem: id
		}, function (data) {
			data = jQuery.parseJSON(data);
			divItem = Cotizacion.objetoParaAgregarImagen;
			control = divItem.find('.file-lsck-capturas');

			var content = control.parents('.content-lsck-capturas:first').find('.content-lsck-galeria');
			var content_files = control.parents('.content-lsck-capturas:first').find('.content-lsck-files');
			var num = data.length;
			var fileApp = '';
			for (var i in data) {
				fileApp += `
				<div class="ui fluid image content-lsck-capturas dimmable">
					<div class="ui dimmer dimmer-file-detalle">
						<div class="content">
							<p class="ui tiny inverted header">${data[i].nombre_inicial}</p>
						</div>
					</div>
					<input type="hidden" name="imagenDeItem[${data[i].idItem}]" value="${data[i].idItemImagen}">
					<a class="ui red right corner label img-lsck-capturas-delete"><i class="trash icon"></i></a>
					<img height="100" src="https://s3.us-central-1.wasabisys.com/impact.business/item/${data[i].nombre_archivo}" class="img-responsive img-thumbnail">
				</div>
				`;
				// control.parents('.nuevo').find('.dimmer-file-detalle')
				// 	.dimmer({
				// 		on: 'click'
				// 	});

			}
			content.html(fileApp);

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

				if (tipoRegistro == 3) fn = 'Fn.closeModals(' + modalId + ');Fn.loadPage(`SolicitudCotizacion/`); $("#btn-filtrarCotizacion").click();';
				if (tipoRegistro == 2) fn = 'Fn.closeModals(' + modalId + ');Fn.loadPage(`Cotizacion/`); $("#btn-filtrarCotizacion").click();';
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
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
		});
	},

	sendToCliente: function () {
		let formValues = Fn.formSerializeObject('formSendToCliente');
		formValues.formRegistro = Fn.formSerializeObject('formRegistroCotizacion');
		formValues.archivosEliminados = Cotizacion.archivoEliminado;
		formValues.anexosEliminados = Cotizacion.anexoEliminado;
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "sendToCliente";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');Fn.loadPage(`Cotizacion/`);$("#btn-filtrarCotizacion").click();';
			}

			btn[0] = { title: 'Aceptar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: b.data.width });
		});
	},

	actualizarTotal: function () {

		let total = 0;
		let totalDistribucion = 0;

		$.each($('.subtotalForm'), function (index, value) {
			// Distribucion no se le agrega al FEE
			if ($(value).closest('.nuevo').find('.idTipoItem').find('select').val() != COD_DISTRIBUCION.id) {
				total = Number(total) + Number($(value).val());
				// totalSinDistribucion = Number(totalSinDistribucion) + Number($(value).val());
			}
			if ($(value).closest('.nuevo').find('.idTipoItem').find('select').val() == COD_DISTRIBUCION.id) {
				totalDistribucion = Number(totalDistribucion) + Number($(value).val());
			}

		})

		let fee = Number($("#feeForm").val());
		let igvForm = $('.igvForm');

		let igv = 0;

		if (igvForm.is(":checked")) {
			igv = IGV_SYSTEM;
		}
		let totalFee = ((total) + (total * (fee / 100))) + totalDistribucion;
		let totalFeeIgv = (totalFee) + (totalFee * igv);

		$('.totalFormLabel').val(moneyFormatter.format(Number(totalFeeIgv)));

		$('.totalFormFeeIgv').val(totalFeeIgv);
		$('.totalFormFee').val(totalFee);
		$('.totalForm').val(total + totalDistribucion);
	},

	actualizarPopupsTitle: () => {
		//Boton enviar
		$('.btn-send')
			.popup({
				position: 'left center',
				target: $('.btn-send'),
				content: 'Enviar',
			});

		//Boton Guardar
		$('.btn-save')
			.popup({
				position: 'left center',
				target: $('.btn-save'),
				content: 'Guardar',
			});
		//Boton Guardar
		$('.btn-preview-orden-compra')
			.popup({
				position: 'left center',
				target: $('.btn-preview-orden-compra'),
				content: 'Visualizar OC',
			});

		//Boton Agregar Detalle
		$('.btn-add-detalle')
			.popup({
				position: 'left center',
				target: $('.btn-add-detalle'),
				content: 'Agregar Detalle',
			});
		//Boton Eliminar Detalle
		$('.btn-eliminar-detalle')
			.popup({
				position: 'top center',
				target: $('.btn-eliminar-detalle'),
				content: 'Eliminar Detalle',
			});
		//Boton Bloquear Detalle
		$('.btn-bloquear-detalle')
			.popup({
				position: 'top center',
				target: $('.btn-bloquear-detalle'),
				content: 'Bloquear Detalle',
			});

		//Boton Ver leyenda
		$('.btn-leyenda')
			.popup({
				popup: $('.popup.leyenda'),
				on: 'click'
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
					content: `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
				}
			);

		//Info archivo
		$('.btn-info-motivo')
			.popup(
				{
					content: `Si la prioridad es ALTA el motivo será obligatorio.`
				}
			);

		//Info dias validez
		$('.btn-info-validez')
			.popup(
				{
					title: `Días de validez`,
					content: `Se cuentan a partir de que la cotización es enviada al cliente.`
				}
			);

		$('.btn-info-cantidad')
			.popup(
				{
					title: `Si requiere más de ${LIMITE_COMPRAS}`,
					content: `Será necesario cotizar nuevamente con el proveedor`
				}
			);
		$('.btn-info-descripcion')
			.popup(
				{
					title: `Cantidad de elementos`,
					content: `Esta cantidad es referente al Item`
				}
			);
		$('.btn-info-gap')
			.popup(
				{
					title: `GAP`,
					content: `Solo podrá completar el GAP cuando se haya confirmado un costo`
				}
			);

	},

	actualizarOnAddRow: (childInserted) => {

		$('.btn-add-file').dimmer({ on: 'hover' });
		$('.btn-info-cantidad')
			.popup(
				{
					title: `Si requiere más de ${LIMITE_COMPRAS}`,
					content: `Será necesario cotizar nuevamente con el proveedor \n
					alsjkhfgkaljshgf
					`
				}
			);
		$('.btn-info-gap')
			.popup(
				{
					title: `GAP`,
					content: `Solo podrá completar el GAP cuando se haya confirmado un costo`
				}
			);
		$('.simpleDropdown').dropdown();


		//Boton info archivos
		childInserted.find('.btn-info-archivo')
			.popup(
				{
					title: `Puede subir como máximo ${MAX_ARCHIVOS}	archivos por detalle`,
					content: `Solo se permiten ${KB_MAXIMO_ARCHIVO / 1024} MB por archivo.`
				}
			);
	},

	actualizarOnAddRowCampos: (parent) => {
		let number = '';
		if (parent.data('id') !== undefined) {
			number = parent.data('id');
		}
		else {
			number = parent.index();
		}
		//Archivos
		let fileItem = parent.find('.file-item');
		let fileType = parent.find('.file-type');
		let fileName = parent.find('.file-name');

		//Textiles
		let tallaSubItem = parent.find('.tallaSubItem');
		let telaSubItem = parent.find('.telaSubItem');
		let colorSubItem = parent.find('.colorSubItem');
		let cantidadSubItemTextil = parent.find('.cantidadSubItemTextil');
		let generoSubItem = parent.find('.generoSubItem');
		//Tarjetas o vales
		let montoSubItem = parent.find('.montoSubItem');

		//Servicios y distribucion

		let nombreSubItem = parent.find('.nombreSubItem');
		let cantidadSubItem = parent.find('.cantidadSubItem');

		let cantidadSubItemDistribucion = parent.find('.cantidadSubItemDistribucion');
		let chkTachadoDistribucion = parent.find('.chkTachadoDistribucion');
		let cantidadPdvSubItemDistribucion = parent.find('.cantidadPdvSubItemDistribucion');
		let itemLogisticaForm = parent.find('.itemLogisticaForm').find('select');

		let tipoServicioSubItem = parent.find('.tipoServicioSubItem').find('select');
		let unidadMedidaSubItem = parent.find('.unidadMedidaSubItem');
		let generarOC = parent.find('.generarOCSubItem');
		let proveedorDistribucion = parent.find('.proveedorDistribucionSubItem').find('select');
		let cantidadReal = parent.find('.cantidadRealSubItem');
		let costoSubItem = parent.find('.costoSubItem');

		let idCotizacionDetalle = parent.find('.idCotizacionDetalleSubForm');

		fileItem.attr('name', `file-item[${number}]`);
		fileType.attr('name', `file-type[${number}]`);
		fileName.attr('name', `file-name[${number}]`);

		//
		tallaSubItem.attr('name', `tallaSubItem[${number}]`);
		telaSubItem.attr('name', `telaSubItem[${number}]`);
		colorSubItem.attr('name', `colorSubItem[${number}]`);
		generoSubItem.attr('name', `generoSubItem[${number}]`);
		cantidadSubItemTextil.attr('name', `cantidadTextil[${number}]`);

		montoSubItem.attr('name', `montoSubItem[${number}]`);

		nombreSubItem.attr('name', `nombreSubItemServicio[${number}]`);
		cantidadSubItem.attr('name', `cantidadSubItemServicio[${number}]`);

		cantidadSubItemDistribucion.attr('name', `cantidadSubItemDistribucion[${number}]`);
		chkTachadoDistribucion.attr('name', `chkTachado[${number}]`);
		cantidadPdvSubItemDistribucion.attr('name', `cantidadPdvSubItemDistribucion[${number}]`);
		itemLogisticaForm.attr('name', `itemLogisticaForm[${number}]`);

		tipoServicioSubItem.attr('name', `tipoServicioSubItem[${number}]`);
		unidadMedidaSubItem.attr('name', `unidadMedidaSubItem[${number}]`);
		generarOC.attr('name', `generarOCSubItem[${number}]`);
		proveedorDistribucion.attr('name', `proveedorDistribucionSubItem[${number}]`);
		cantidadReal.attr('name', `cantidadRealSubItem[${number}]`);
		costoSubItem.attr('name', `costoSubItem[${number}]`);
		idCotizacionDetalle.attr('name', `idCotizacionDetalleSub[${number}]`);

	},


	cleanDetalle: (parent) => {
		let tipoForm = parent.find('#tipoItemForm');
		let costoForm = parent.find('.costoForm');
		let costoFormLabel = parent.find('.costoFormLabel');
		let gapForm = parent.find('.gapForm');
		let cantidadForm = parent.find('.cantidadForm');
		let codItems = parent.find('.codItems');
		let idProveedor = parent.find('.idProveedor');
		let cotizacionInternaForm = parent.find('.cotizacionInternaForm');
		let semaforoForm = parent.find('.semaforoForm');
		let tachadoDistribucion = parent.find('.tbDistribucionTachado');

		codItems.val('');
		idProveedor.val('');
		if (tipoForm.val() == COD_DISTRIBUCION.id) {
			cotizacionInternaForm.val('0');
		} else {
			cotizacionInternaForm.val('1');
		}

		semaforoForm.removeClass('semaforoForm-green');
		semaforoForm.removeClass('semaforoForm-yellow');
		semaforoForm.removeClass('semaforoForm-red');
		semaforoForm.popup('destroy');

		costoForm.val('');
		costoFormLabel.val('');
		gapForm.val('');

		cantidadForm.val('');

		cantidadForm.keyup();

		// tachadoDistribucion.find('tbody').html('');
		// tachadoDistribucion.addClass('d-none');
	},

	actualizarCotizacionView: function (updateEstado) {
		let formValues = Fn.formSerializeObject('formActualizarCotizacion');
		formValues.archivosEliminados = Cotizacion.archivoEliminado;
		formValues.anexosEliminados = Cotizacion.anexoEliminado;
		formValues.subItemEliminado = Cotizacion.subItemEliminado;
		formValues.repetidoSubItem = Cotizacion.repetidoSubItem;
		formValues.repetidoSubItem2 = Cotizacion.repetidoSubItem2;
		formValues.detalleEliminado = Cotizacion.detalleEliminado;
		if(updateEstado == 2){
			formValues.actualizarEstado = 2;
		}

		console.log(formValues);

		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "actualizaCotizacionData";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				// fn = 'Fn.closeModals(' + modalId + ');$(".btn-dp-' + idCotizacion + '").click();';
				fn = `window.location.href = "${site_url}Cotizacion";`
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},

	duplicarCotizacionView: function (idCotizacion) {
		let formValues = Fn.formSerializeObject('formDuplicarCotizacion');

		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "duplicarCotizacion";
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

}

Cotizacion.load();
SolicitudCotizacion.load();
