$.extend({
	isNull: function (val) {
		if (typeof val == 'undefined') return true;
		//else if( val.length==0) return true;
		else return false
	},

	disable: function (selector, boolean = true) {
		var control = $(selector);
		$.each(control, function (i, v) {
			if (v.tagName === "DIV") {
				if (boolean) {
					$(v).addClass('d-none');
					$(v).find(':input').attr('disabled', true);
					$(v).find('select').attr('disabled', true);
					$(v).find(':submit').attr('disabled', true);
				} else {
					$(v).removeClass('d-none');
					$(v).find(':input').attr('disabled', false);
					$(v).find('select').attr('disabled', false);
					$(v).find(':submit').attr('disabled', false);
				}
			} else {
				if (boolean) {
					$(v).addClass('d-none');
					$(v).attr('disabled', true);
				} else {
					$(v).removeClass('d-none');
					$(v).attr('disabled', false);
				}
			}
		});
	},

	mostrarValidaciones: function (formulario, validaciones) {
		var campo;
		var contador = 0;
		$.each(validaciones, function (idInput, mensajesDeValidacion) {
			campo = $('#' + formulario + ' [name="' + idInput + '"]');
			contenedorElementoValidacion = $('#' + formulario + ' #' + idInput).parent();

			campo.removeClass('is-valid');
			campo.removeClass('is-invalid');
			$('.invalid-feedback', contenedorElementoValidacion).remove();

			if (mensajesDeValidacion.length !== 0) {
				contador++;
				campo.addClass('is-invalid');
				var invalidFeedback;
				$.each(mensajesDeValidacion, function (indiceMensaje, mensaje) {
					invalidFeedback = '<div class="invalid-feedback d-block">' + mensaje + '</div>';
					contenedorElementoValidacion.append(invalidFeedback);
				});
			} else {
				campo.addClass('is-valid');
			}
		});
		if (contador > 0) $('.mostrarocultar').addClass('show');
	},

	fechaLimite: function (picker, thisFecha, fechaLimite) {
		var control = $(thisFecha);
		var fechaLimite = $(fechaLimite).val();
		var dateB = moment(picker.startDate.format('YYYY-MM-DD'));
		var dateC = moment(moment(fechaLimite, "DD/MM/YYYY").format('YYYY-MM-DD'));
		var diferencia = dateB.diff(dateC, 'days');
		if (diferencia >= 0) control.val(picker.startDate.format('DD/MM/YYYY'));
		else control.val('');
	},

	replaceAll: function (string, target, replacement) {
		return string.split(target).join(replacement);
	}
});

$.ajaxSetup({
	type: "POST",
	global: false,
	cache: false,
	timeout: 1 * 800 * 1000,/*1 minuto*/
});

const KB_MAXIMO_ARCHIVO = 7168;
const MAX_ARCHIVOS = 10;
const IGV_SYSTEM = 0.18;
const GAP_MONTO_MINIMO = 1500;
const GAP_MINIMO = 15;
const LIMITE_COMPRAS = 1000;

const RUTA_WIREFRAME = '../public/assets/images/wireframe/';
const COD_ARTICULO = {'id' : 1, 'nombre' : 'ARTICULO'};
const COD_SERVICIO = {'id' : 2, 'nombre' : 'SERVICIO'};
const COD_COMPUTO = {'id' : 3, 'nombre' : 'COMPUTO'};
const COD_MOVIL = {'id' : 4, 'nombre' : 'MOVIL'};
const COD_PERSONAL = {'id' : 5, 'nombre' : 'PERSONAL'};
const COD_EVENTO = {'id' : 6, 'nombre' : 'EVENTO'};
const COD_DISTRIBUCION = {'id' : 7, 'nombre' : 'DISTRIBUCION'};
const COD_TEXTILES = {'id' : 9, 'nombre' : 'TEXTILES'};
const COD_TARJETAS_VALES = {'id' : 10, 'nombre' : 'TARJETAS_VALES'};
const COD_TRANSPORTE = {'id' : 12, 'nombre' : 'TRANSPORTE'};

const moneyFormatter = new Intl.NumberFormat('en-US', {
	style: 'currency',
	currency: 'PEN',
  
	// These options are needed to round to whole numbers if that's what you want.
	//minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
	//maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

var site_name = 'ImpactBussiness';
var site_url = $('base').attr('site_url');
//var fotos_url='http://movil.visualimpact.com.pe/fotos/impactTrade_Android/';
var fotos_url = `${site_url}ControlFoto/obtener_carpeta_foto/`;
var modalId = 0;
var toastId = 0;
var global_masivo = [];

var spanishDateRangePicker = {
	"format": "DD/MM/YYYY",
	"separator": " - ",
	"applyLabel": "Aplicar",
	"cancelLabel": "Cancelar",
	"fromLabel": "De",
	"toLabel": "A",
	"customRangeLabel": "Custom",
	"weekLabel": "S",
	"daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
	"monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
	"firstDay": 1

}

var singleDatePickerModal = {
	"locale": spanishDateRangePicker,
	"singleDatePicker": true,
	"showWeekNumbers": true,
	"showDropdowns": false,
	"parentEl": "div.modal-content",
}

var _aSelectAll = {
	'cuenta': [
		'proyecto',
		'grupoCanal',
		'canal',
		'subCanal',
		'zona',
		'plaza',
		'distribuidora',
		'distribuidoraSucursal',
		'cadena',
		'banner'
	],
	'proyecto': [
		'grupoCanal',
		'canal',
		'subCanal',
		'zona',
		'plaza',
		'distribuidora',
		'distribuidoraSucursal',
		'cadena',
		'banner',
		'encargado',
		'colaborador'
	],
	'grupoCanal': [
		'canal',
		'subCanal',
		'encargado',
		'colaborador'
	],
	'canal': [
		'subCanal',
		'encargado',
		'colaborador',
		'tipoCliente'
	],
	'distribuidora': [
		'distribuidoraSucursal',
		'encargado',
		'colaborador'
	],
	'cadena': [
		'banner',
		'encargado',
		'colaborador'
	],
	'tipoUsuario': [
		'usuario',
		'encargado',
	],
	'encargado': [
		'colaborador'
	]

};
var _aSelectGrupoCanal = {
	'all': [
		'zona',
		'plaza',
		'distribuidora',
		'distribuidoraSucursal',
		'cadena',
		'banner'
	],
	1: ['zona', 'distribuidora', 'distribuidoraSucursal'],
	4: ['zona', 'distribuidora', 'distribuidoraSucursal'],
	5: ['plaza'],
	2: ['cadena', 'banner'],
	8: ['cadena', 'banner'],
};

var intervalPeticionActualizacion = null;
var intervalEstadoPeticionActualizarVisita = null;
var View = {
	idModal: 0,
	load: function () {
		$(window).resize(function () {
			// Fn.dataTableAdjust();
		});

		$('.hide-parent').parent().hide();


		$("#btn-toggle-menu").click(function (e) {
			e.preventDefault();
			var control = $(this);

			$('#sidebarMenu').toggleClass('d-none w-0 d-nonone');
			$('#main').toggleClass('main-none main-nonone');

			var show = control.attr('data-show');
			if (show == 'true') {
				control.attr('data-show', 'false');
				control.html('<i class="fas fa-lg fa-bars"></i>');
			}
			else {
				control.attr('data-show', 'true');
				control.html('<i class="fad fa-lg fa-bars"></i>');
			}

		});
		$(document).on('keyup paste', '.soloNumeros', function (e) {
			let puntos = 0;
			let cadenaAnalizar = $(this).val();
			for (var i = 0; i < cadenaAnalizar.length; i++) {
				var caracter = cadenaAnalizar.charAt(i);
				if (caracter == '.') {
					puntos++;
				}
			}

			if (!$.isNumeric(e.key) && e.key != '.' && e.key != 'Backspace') {
				e.preventDefault();
				$(this).val('');
			} else {
				if (puntos > 1) {
					e.preventDefault();
					$(this).val('');
				}
			}
		});

		$(document).on('keyup paste', '.onlyNumbers', function (e) {
			let puntos = 0;
			let cadenaAnalizar = $(this).val();
			let control = $(this);
			for (var i = 0; i < cadenaAnalizar.length; i++) {
				var caracter = cadenaAnalizar.charAt(i);
				if (caracter == '.') {
					puntos++;
				}
			}
			let nmax = Number(control.data('max'));
			if(nmax > 0){
				if(control.val() > nmax){
					$(this).val(nmax);
				}
			}
			
			let nmin = Number(control.data('min'));
			if(nmin !== typeof undefined){
				if(control.val() < nmin){
					// $(this).val(nmin); // Comentado para que no escriba automaticamente el valor minimo.
					$(this).val('');
				}
			}


			if(Fn.validators['numeros']['expr'].test(control.val())){
				e.preventDefault();
			}
		});

		$(document).on('keypress', '.onlyNumbers', function (e) {
			let control = $(this);
			if (!$.isNumeric(e.key) && e.key != '.') {
				e.preventDefault();
			}
		});

		$('.navbar-toggler').click(function (e) {
			e.preventDefault();
			// $('#sidebarMenu').toggleClass("d-none");
			$('#sidebarMenu').toggleClass('d-none w-0');

		});

		$(".arrowLogo").removeClass("arrowLogoHorizontal");
		$(".arrowLogo").addClass("arrowLogoVertical");
		$(".arrowLogo").attr("src", "images/visual-logo-vertical.png");

		$(document).on('DOMNodeInserted', '.my_select2', function () {
			$(this).select2();
		});

		$('.my_select2').select2();

		$(document).on('DOMNodeInserted', '.my_select2Full', function () {
			$(this).select2({
				width: '100%',
				// dir: "rtl",
			});
		});
		$(document).on('DOMNodeInserted', '.ui.my_dropdown', function () {
			$(this).dropdown();
		});

		$('.ui.my_dropdown').dropdown({

		});
		// $('.ui.my_calendar').calendar({

		// });


		$('.my_select2Full').select2({
			width: '100%',
			// dir: "rtl",
		});

		$(document).on('show.bs.modal', '.modal', function (e) {
			var zIndex = 1040 + (10 * $('.modal:visible').length);
			$(this).css('z-index', zIndex);
			setTimeout(function () {
				$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
			}, 0);

			Fn.loadSemanticFunctions();
		});

		// $(document).on('show.bs.modal','.modal',function(e){
		// var zIndex = 1040+(10*$('.modal:visible').length);
		// $(this).css('z-index',zIndex);
		// setTimeout(function(){
		// $('.modal-backdrop').not('.modal-stack').css('z-index',zIndex-1).addClass('modal-stack');
		// },0);
		// });

		$(document).on('hidden.bs.modal', '.modal', function () {
			var modal = $(this);
			var backdrop = modal.next('.modal-backdrop');

			if (modal.hasClass('.modal-temp')) {
				backdrop.remove();
				modal.remove();
			}

			Fn.modalVisible();
		});

		$(document).on('click', '.card-header-tab>ul>li>a', function (e) {
			$(".card-header-tab>ul>li").removeClass("active")
			$(this).parent().addClass("active");
		});

		$(document).on('click', '.a-show-body', function (e) {
			var show = $(this).attr("data-show");
			if (show == 'false') {
				$(this).parent().parent().parent().parent().find('tbody').removeClass('hide');
				$(this).html('<i class="fa fa-minus-circle" ></i>');
				$(this).attr("data-show", true);
			} else {
				$(this).parent().parent().parent().parent().find('tbody').addClass('hide');
				$(this).html('<i class="fa fa-plus-circle" ></i>');
				$(this).attr("data-show", false);
			}
		});

		$(document).on('click', '.a-href', function () {
			var page = $(this).attr('page');
			if (page.length > 0) Fn.loadPage(page);
			else return false;
		});

		$(document).on('click', '#a-logout', function () {
			++modalId;
			alert();
			var btn = new Array();
			btn[0] = { title: 'Si', fn: 'Fn.showModal({ id:' + modalId + ',show:false });Fn.logOut();' };
			btn[1] = { title: 'No', fn: 'Fn.showModal({ id:' + modalId + ',show:false });' };
			Fn.showModal({ id: modalId, show: true, title: 'Cerrar Sesión', content: '¿Desea salir del sistema?', btn: btn });
		});

		$(document).on('click', '#a-changelock', function () {
			++modalId;
			var btn = new Array();
			btn[0] = { title: 'Aceptar', fn: 'Fn.showConfirm({ idForm:"frm-clave",fn:"Fn.clave(' + modalId + ')",content:"¿Desea registrar los datos?" });' };
			btn[1] = { title: 'Cerrar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' };
			Fn.showModal({ id: modalId, show: true, title: 'Cambiar Clave', frm: View.frmClave(), btn: btn });
		});

		$(document).on('click', '#a-cambiarcuenta', function () {
			$.when(Fn.ajax({ url: 'control/get_cuenta' })).then(function (a) {
				if (a.result == 2) return false;

				var html = '';
				html += '<form id="frm-cambiarcuenta" class="py-3 px-4">';
				html += '<div class="row">';
				html += '<div class="col-md-8 offset-md-2">';
				html += '<div class="form-group row">';
				html += '<label class="col-md-3 col-form-label pt-0 text-left">Cuenta:</label>';
				html += '<div class="col-md-9">';
				$.each(a.data.cuenta, function (icuenta, vcuenta) {
					var checked = '';
					if (a.data.cuenta.length == 1 ||
						vcuenta['id'] == a.data.idCuenta) checked = 'checked';

					html += '<div class="form-check mb-1">';
					html += '<input type="radio" id="idCuenta' + vcuenta['id'] + '" class="form-check-input rd-cambiarcuenta-cuenta" name="idCuenta" value="' + vcuenta['id'] + '" ' + checked + ' />';
					html += '<label class="form-check-label cursor-pointer" for="idCuenta' + vcuenta['id'] + '">';
					html += vcuenta['nombre'];
					html += '</label>';
					html += '</div>';
				});
				html += '</div>';
				html += '</div>';
				html += '<div class="form-group row">';
				html += '<label class="col-md-3 col-form-label pt-0 text-left">Proyecto:</label>';
				html += '<div id="dv-cambiarcuenta-proyecto" class="col-md-9">';
				if (typeof (a.data.proyecto) != 'undefined') {
					$.each(a.data.proyecto, function (iproyecto, vproyecto) {
						var checked = '';
						if (vproyecto['id'] == a.data.idProyecto) checked = 'checked';

						html += '<div class="form-check mb-1">';
						html += '<input class="form-check-input" type="radio" name="idProyecto" id="idProyecto-' + vproyecto['id'] + '" value="' + vproyecto['id'] + '" ' + checked + ' />';
						html += '<label class="form-check-label cursor-pointer" for="idProyecto-' + vproyecto['id'] + '">';
						html += vproyecto['nombre'];
						html += '</label>';
						html += '</div>';
					});
				}
				else {
					html += '<small class="text-muted">* Selecciona una Cuenta</small>';
				}
				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '</form>';

				++modalId;
				var btn = [{ title: 'Cambiar', id: 'btn-cambiarcuenta-confirm', class: 'btn-trade-visual' }];
				if (
					a.data.idCuenta != null &&
					a.data.idProyecto != null &&
					String(a.data.idCuenta).length > 0 &&
					String(a.data.idProyecto).length > 0
				) {
					btn.unshift({ title: 'Cerrar', fn: 'Fn.showModal({ id: ' + modalId + ', show: false });' });
				}

				Fn.showModal({
					id: modalId,
					show: true,
					title: 'Cambio de Cuenta / Proyecto',
					frm: html,
					btn: btn
				});

				View.idModal = modalId;
			});
		});

		$(document).on('click', '.progress-circle', () => {
			$("#a-actualizarVisitas").click();
		});

		$(document).on('change', '.rd-cambiarcuenta-cuenta', function () {
			var idCuenta = $(this).val();
			var data = { data: JSON.stringify({ idCuenta: idCuenta }) };
			$.when(Fn.ajax({ url: 'control/get_cuentaProyecto', data: data })).then(function (a) {
				if (a.result == 2) return false;

				var html = '';
				if (typeof (a.data.proyecto) != 'undefined') {
					$.each(a.data.proyecto, function (iproyecto, vproyecto) {
						var checked = '';
						if (vproyecto['id'] == a.data.idProyecto) checked = 'checked';

						html += '<div class="form-check mb-1">';
						html += '<input class="form-check-input" type="radio" name="idProyecto" id="idProyecto-' + vproyecto['id'] + '" value="' + vproyecto['id'] + '" ' + checked + ' />';
						html += '<label class="form-check-label cursor-pointer" for="idProyecto-' + vproyecto['id'] + '">';
						html += vproyecto['nombre'];
						html += '</label>';
						html += '</div>';
					});
				}

				$('#dv-cambiarcuenta-proyecto').html(html);
			});
		});

		$(document).on('click', '#btn-cambiarcuenta-confirm', function () {
			++modalId;
			if (
				$('#frm-cambiarcuenta input[name=idCuenta]:checked').val() == undefined ||
				$('#frm-cambiarcuenta input[name=idProyecto]:checked').val() == undefined
			) {
				Fn.showModal({
					id: modalId,
					show: true,
					title: 'Alerta',
					frm: Fn.message({ type: 2, message: 'Debe seleccionar Cuenta y Proyecto' }),
					btn: [{ title: 'Cerrar', fn: 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
				});
			}
			else {
				Fn.showModal({
					id: modalId,
					show: true,
					title: 'Confirmar',
					frm: Fn.message({ type: 3, message: '¿Desea guardar la configuración selecionada?' }),
					btn: [
						{ title: 'Atras', fn: 'Fn.showModal({ id: ' + modalId + ', show: false });' },
						{ title: 'Confirmar', fn: 'View.guardarCambioCuenta(); Fn.showModal({ id: ' + modalId + ', show: false });' }
					]
				});
			}
		});

		$(document).on('click', '.lk-export-excel', function () {
			var content = $(this).attr("data-content");
			var title = $(this).attr("data-title");
			if (content != '') {
				var datos = ExportarExcel.getData(content);
				var reporte = title;
				if (datos) {
					var contenido = ExportarExcel.generateExcel(datos);
					if (contenido) { ExportarExcel.downloadExcel(contenido, reporte); }
				}
			}
		});

		$(document).on('click', '.lk-export-excel-old', function () {
			var id = $(this).attr("data-content");
			$('#' + id).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				//filename: id + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				filename: id + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true,
				preserveColors: true,
			});

			/* var title = $(this).attr("data-title");
			if( content != '' ){
				var datos = ExportarExcel.getData( content );
				var reporte = title;
				if(datos) {
					var contenido = ExportarExcel.generateExcel(datos);
					if(contenido) { ExportarExcel.downloadExcel(contenido, reporte); }
				}	
			} */
		});

		$(document).on('click', 'span#img-close', function (e) {
			e.preventDefault();

			var span = $(this);
			var view = $('#popover-img');
			var img = $('div.img').find('img');

			if (img.attr('src') || span.addClass('alert-danger')) {
				if (view) {
					view.popover("hide");
					view.removeClass('alert-info pointer').addClass('alert-default');
				}
				if (img) img.removeAttr('src');

				span.removeClass('alert-danger pointer').addClass('alert-default');
			}
		});

		$('body').on('click', '.lk-show-foto', function () {
			var foto = $(this).attr('data-foto');
			var modulo = $(this).attr('data-modulo');
			var comentario = $(this).attr('data-comentario');

			let dataBody;

			Fn.showLoading(true);

			$.when($.post(site_url + "control/mostrarFoto", { foto: foto, modulo: modulo, comentario: comentario }, function (data) {
				dataBody = data;
			})).then(() => {
				++modalId;
				var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
				var btn = [];
				btn[0] = { title: 'Cerrar', fn: fn };

				Fn.showLoading(false);
				Fn.showModal({ id: modalId, show: true, title: "FOTOS", content: dataBody, btn: btn });
			});
		});

		$('body').on('click', '.lk-show-gps', function () {
			var lati_1 = $(this).attr('data-lati-1');
			var long_1 = $(this).attr('data-long-1'); var lati_2 = $(this).attr('data-lati-2');
			var long_2 = $(this).attr('data-long-2'); var modulo = $(this).attr('data-modulo');
			var data_ = $(this).attr('data-info');

			let dataBody;

			Fn.showLoading(true);

			$.when($.post(site_url + "control/mostrarMaps", { lati_1: lati_1, long_1: long_1, lati_2: lati_2, long_2: long_2, modulo: modulo, data: data_ }, function (data) {
				dataBody = data;
			})).then(() => {
				++modalId;
				var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
				var btn = [];
				btn[0] = { title: 'Cerrar', fn: fn };

				Fn.showLoading(false);
				Fn.showModal({ id: modalId, show: true, title: "GOOGLE MAPS", content: dataBody, btn: btn, width: "90%" });
			});

		});

		$(document).on('change', 'select[name="sl-zona"]', function () {
			var idZona = $(this).val();
			$('select[name="sl-ciudad"]').html(Fn.selectOption('ciudad', [idZona])).selectpicker('refresh');

		});

		$(document).on('change', 'select[name="sl-tipoUsuario"]', function () {
			var idTipoUsuario = $(this).val();
			$('select[name="sl-usuario"]').html(Fn.selectOption('usuarios', [idTipoUsuario])).selectpicker('refresh');
		});

		$(document).on('change', 'select[name="sl-cadena"]', function () {
			var idCadena = $(this).val();
			$('select[name="sl-banner"]').html(Fn.selectOption('banner', [idCadena])).selectpicker('refresh');
		});


		$('input[name="txt-fechas"]').daterangepicker({
			locale: {
				"format": "DD/MM/YYYY",
				"applyLabel": "Aplicar",
				"cancelLabel": "Cerrar",
				"daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
				"monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
				"firstDay": 1
			},
			singleDatePicker: false,
			showDropdowns: false,
			autoApply: true,
		});



		$('.rango_fechas').daterangepicker({
			locale: {
				"format": "DD/MM/YYYY",
				"applyLabel": "Aplicar",
				"cancelLabel": "Cerrar",
				"daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
				"monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
				"firstDay": 1
			},
			singleDatePicker: false,
			showDropdowns: false,
			autoApply: true,
		});

		$('input[name="txt-fechas_simple"]').daterangepicker({
			locale: {
				"format": "DD/MM/YYYY",
				"applyLabel": "Aplicar",
				"cancelLabel": "Cerrar",
				"daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
				"monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
				"firstDay": 1
			},
			singleDatePicker: true,
			showDropdowns: false,
			autoApply: true,
		});


		/***********EVENTOS ADICIONALES*********/
		if ($('.btnCollapse').length > 0) {
			$('.btnCollapse').on('click', function () {
				var icon = $(this).find('i');
				var title = $(this).attr("title");
				icon.toggleClass('fa-caret-up fa-caret-down');

				if (title === "Desplegar filtros") {
					$(this).attr("title", "Plegar filtros");
				} else {
					$(this).attr("title", "Desplegar filtros");
				}
			});
		}

		//Evento buscado en internet para hacer que un radio con la clase uncheckableRadio se pueda deseleccionar
		$(document).on("click mousedown", ".uncheckableRadio", (function () {
			//Capture radio button status within its handler scope,
			//so we do not use any global vars and every radio button keeps its own status.
			//This required to uncheck them later.
			//We need to store status separately as browser updates checked status before click handler called,
			//so radio button will always be checked.
			var isChecked;
			return function (event) {
				if (event.type == 'click') {
					if (isChecked) {
						//Uncheck and update status
						isChecked = this.checked = false;
					} else {
						//Update status
						//Browser will check the button by itself
						isChecked = true;
					}
				} else {
					//Get the right status before browser sets it
					//We need to use onmousedown event here, as it is the only cross-browser compatible event for radio buttons
					isChecked = this.checked;
				}
			}
		})());

		$(document).on("click", "table thead th .btn-AgregarElemento", function (e) {
			e.preventDefault();

			var tabla = $(this).closest('table');
			var tbody = $(tabla).find('tbody');
			var lastFila = $(tabla).find('tbody tr.trHijo:last').data('fila');
			var nextFila = (typeof lastFila !== 'undefined') ? lastFila + 1 : 1;
			var trPadre = $(tabla).find('tbody .trPadre').clone(true);
			var select2Clase = $(trPadre).data('select2');
			var modalClase = $(trPadre).data('classmodal');
			$(trPadre).addClass('trHijo');
			$(trPadre).removeClass('trPadre');
			$(trPadre).removeClass('d-none');
			$(trPadre).data('fila', nextFila);

			$(trPadre).find('select').removeAttr('disabled');

			var tdsInputs = $(trPadre).find('td[data-name]');

			$.each(tdsInputs, function (i, v) {
				var tdName = $(this).data('name');
				var inputText = $(this).find('input[type="text"]');
				var select = $(this).find('select');
				var checkBox = $(this).find('input[type="checkbox"]');
				var radio = $(this).find('input[type="radio"]');

				if (inputText.length !== 0) {
					$(inputText[0]).attr('name', tdName + '-' + nextFila);
					$(inputText[0]).attr('id', tdName + '-' + nextFila);
				}

				if (select.length !== 0) {
					$(select[0]).attr('name', tdName + '-' + nextFila);
					$(select[0]).attr('id', tdName + '-' + nextFila);
					$(select[0]).addClass(select2Clase);
				}

				if (checkBox.length !== 0) {
					$.each(checkBox, function (i, v) {
						$(this).attr('name', tdName + '-' + nextFila);
					});
					// $(checkBox[0].parent()).attr('id', tdName + '-' + nextFila);
					$(checkBox[0]).parent().attr('id', tdName + '-' + nextFila);
				}

				if (radio.length !== 0) {
					$.each(radio, function (i, v) {
						$(this).attr('name', tdName + '-' + nextFila);
					});
					// $(radio[0].parent()).attr('id', tdName + '-' + nextFila);
					$(radio[0]).parent().attr('id', tdName + '-' + nextFila);

				}
			});

			$(tbody).append(trPadre);

			$('.' + select2Clase).select2({
				dropdownParent: $("div.modal-content-" + modalClase),
				width: '100%'
			});
		});

		$(document).on("click", ".btn-MostrarClave", function (e) {
			e.preventDefault();
			var claveInput = $(this).parents('.input-group').find('input:first');
			var tipo = $(claveInput).attr('type');

			if (tipo === "password") {
				$(claveInput).attr("type", "text");
			} else {
				$(claveInput).attr("type", "password");
			}
		});

		$(document).on("click", ".btn-GenerarClave", function (e) {
			e.preventDefault();
			var claveInput = $(this).parents('.input-group').find('input:first');
			var length = 8,
				charset = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789",
				retVal = "";
			for (var i = 0, n = charset.length; i < length; ++i) {
				retVal += charset.charAt(Math.floor(Math.random() * n));
			}
			claveInput.val(retVal);
		});

		$(document).on("click", "table .trHijo .btn-BorrarElemento", function (e) {
			e.preventDefault();
			var tr = $(this).closest('tr');
			var table = $(this).closest('table');
			var lastTh = $(table).find('thead tr:first th:last');

			var idEliminado = $(tr).find("input[name|='id']").val();
			if (typeof idEliminado !== 'undefined') {
				inputHtml = "<input class='d-none' type='text' name='elementosEliminados' value='" + idEliminado + "'>";
				lastTh.append(inputHtml);
			}
			tr.remove();
		});

		$(document).on("change", "table .trHijo .chk-ActualizarElemento", function (e) {
			e.preventDefault();

			var valorCheck = $(this).prop('checked');
			var tr = $(this).closest('tr');
			var inputs = $(tr).find(':input');
			var chkActualizarElemento = $(tr).find('.chk-ActualizarElemento');

			$.each(inputs, function (i, v) {
				$(this).attr('disabled', !valorCheck);
			});

			valorCheck ? $(tr).removeClass('table-secondary') : $(tr).addClass('table-secondary');

			$(chkActualizarElemento).attr('disabled', false);
		});

		$(document).on("change", "input[type='checkbox'][class*='checkPadre']", function () {
			var dataCheckHijo = $(this).data('checkhijo');
			var checkHijos = $("input[type='checkbox'][class*='checkHijo'][data-checkhijo='" + dataCheckHijo + "']");
			if (this.checked) {
				$.each(checkHijos, function (i, v) {
					$(this).prop('checked', true);
				});
			} else {
				$.each(checkHijos, function (i, v) {
					$(this).prop('checked', false);
				});
			}
		});

		// COMBOS AUTOMATICOS
		$(document).on('change', '.flt_cuenta', function (e) {
			var control = $(this);
			var cbx_proyecto = $('.flt_proyecto');

			var aCombos = _aSelectAll['cuenta'].slice(0);
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();
				}
			});

			var idCuenta = control.val();
			if (idCuenta.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idCuenta': idCuenta }) };
			var url = 'control/get_proyecto';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(a['data'], function (i, v) {
					var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
					cbx_proyecto.append(options);
				});
			});
		});

		$(document).on('change', '.flt_proyecto', function (e) {
			var control = $(this);

			var aCombos = _aSelectAll['proyecto'].slice(0);
			var aCombosHead = ['grupoCanal', 'zona', 'plaza', 'distribuidora', 'cadena', 'encargado'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = control.val();
			if (idProyecto.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});

		});

		$(document).on('change', '.flt_grupoCanal', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['grupoCanal'].slice(0);

			var aCombosHead = ['canal', 'encargado'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idGrupoCanal = control.val();

			View.filtrosGrupoCanal();

			if (idGrupoCanal.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, 'idGrupoCanal': idGrupoCanal, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$('.filtros_gc').addClass('d-none');
				$('.filtros_gc').find('select').attr('disabled', true);

				if (typeof a.data.grupoCanal !== 'undefined') {
					$('.filtros_' + a.data.grupoCanal).removeClass('d-none');
					$('.filtros_' + a.data.grupoCanal).find('select').attr('disabled', false);
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});
		});

		$(document).on('change', '.flt_canal', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['canal'];

			var aCombosHead = ['subCanal', 'encargado', 'tipoCliente'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idCanal = control.val();

			if (idCanal.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, 'idCanal': idCanal, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});
		});

		$(document).on('change', '.flt_distribuidora', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['distribuidora'];

			var aCombosHead = ['distribuidoraSucursal', 'encargado'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idDistribuidora = control.val();

			if (idDistribuidora.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, 'idDistribuidora': idDistribuidora, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});

		});

		$(document).on('change', '.flt_cadena', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['cadena'];

			var aCombosHead = ['banner', 'encargado'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idCadena = control.val();

			if (idCadena.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, 'idCadena': idCadena, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});

		});

		$(document).on('change', '.flt_tipoUsuario', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['tipoUsuario'];

			var aCombosHead = ['usuario', 'encargado'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idTipousuario = control.val();

			if (idTipousuario.length == 0) {
				return false;
			}

			var data = { 'data': JSON.stringify({ 'idProyecto': idProyecto, idTipousuario, 'combos': aCombosExist }) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}
				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});

		});

		$(document).on('change', '.flt_encargado', function (e) {
			var control = $(this);
			var aCombos = _aSelectAll['encargado'];

			var aCombosHead = ['colaborador'];
			var aCombosExist = {};
			$.each(aCombos, function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).find('option').not(':first').remove();
					$('.flt_' + v).val('').change();

					if ($.inArray(v, aCombosHead) != -1) {
						aCombosExist[v] = 1;
					}
				}
			});

			var idProyecto = $('.flt_proyecto').val() ? $('.flt_proyecto').val() : 0;
			var idEncargado = $('.flt_encargado').val() ? $('.flt_encargado').val() : 0;
			if (idProyecto == 0 || idEncargado == 0) {
				return false;
			}

			var filtros = {
				'idProyecto': idProyecto,
				'idGrupoCanal': $('.flt_grupoCanal').val() ? $('.flt_grupoCanal').val() : 0,
				'idCanal': $('.flt_canal').val() ? $('.flt_canal').val() : 0,
				'idSubCanal': $('.flt_subCanal').val() ? $('.flt_subCanal').val() : 0,
				'idZona': $('.flt_zona').val() ? $('.flt_zona').val() : 0,
				'idPlaza': $('.flt_plaza').val() ? $('.flt_plaza').val() : 0,
				'idDistribuidora': $('.flt_distribuidora').val() ? $('.flt_distribuidora').val() : 0,
				'idDistribuidoraSucursal': $('.flt_distribuidoraSucursal').val() ? $('.flt_distribuidoraSucursal').val() : 0,
				'idCadena': $('.flt_cadena').val() ? $('.flt_cadena').val() : 0,
				'idBanner': $('.flt_banner').val() ? $('.flt_banner').val() : 0,
				'idEncargado': idEncargado,
				'combos': aCombosExist
			}

			var data = { 'data': JSON.stringify(filtros) };
			var url = 'control/get_combos';

			$.when(Fn.ajax_filtros({ 'data': data, 'url': url, 'control': control })).then(function (a) {
				if (a['result'] == null) {
					return false;
				}

				$.each(aCombosExist, function (i_cbx, v_cbx) {
					if (typeof (a['data'][i_cbx]) == 'object') {
						$.each(a['data'][i_cbx], function (i, v) {
							var options = '<option value="' + v['id'] + '">' + v['nombre'] + '</option>';
							$('.flt_' + i_cbx).append(options);
						});
					}
				})
			});
		});

		//MOSTRAR FOTO EN UN MODAL
		$(document).on("click", ".lk-foto-1", function () {
			var control = $(this);
			var imgid = control.data('content');
			var fotoUrl = $('#' + imgid).attr("src");
			var img = '<img src="' + fotoUrl + '" class="img-responsive center-block img-thumbnail" />';
			var html = img;

			++modalId;
			var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
			var btn = new Array();
			btn[0] = { title: 'Cerrar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: "ZOOM FOTO", content: html, btn: btn });
		});

		$(document).on("click", '#btn-anuncioVisto', function (e) {
			e.preventDefault();
			var config = { 'url': 'control/' + 'actualizarAnuncion' };

			$.when(Fn.ajax(config)).then(function (a) {
				// if( $cambiarCuenta ) $('#a-cambiarcuenta').click();
			});
		});

		$(document).on("click", '.customizer-content-button > button', function (e) {
			e.preventDefault();
			$('.customizer.border-left-blue-grey.border-left-lighten-4.d-xl-block').removeClass('open');
		});

		$(document).on("click", '#btn-anuncios', function (e) {
			e.preventDefault();
			var config = { 'url': 'control/' + 'getAnuncios' };

			$.when(Fn.ajaxNoLoad(config)).then(function (a) {
				if (a.result == 1) {
					++modalId;
					Fn.showModalOnlyBody({ id: modalId, show: true, title: "Aviso", frm: a.data.html, width: 'auto', maxwidth: '600px', padding: 0 });
				}
			});
		});

		$(document).on("click", '.dropdown-item.btn-anuncios', function (e) {
			e.preventDefault();
			var config = { 'url': 'control/' + 'getAnuncios' };

			$.when(Fn.ajax(config)).then(function (a) {
				++modalId;
				Fn.showModalOnlyBody({ id: modalId, show: true, title: "Aviso", frm: a.data.html, width: 'auto', maxwidth: '600px', padding: 0 });
			});
		});

		$("#usuario_filtro").select2({
			width: '100%',
			ajax: {
				url: site_url + 'control/' + "json_usuarios/",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						input: params.term,
						page: params.page
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'COD - NOMBRE - DOCUMENTO',
			minimumInputLength: 3,
		});
		$("#pdv_filtro").select2({
			width: '100%',
			ajax: {
				url: site_url + 'control/' + "json_pdv/",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						input: params.term,
						page: params.page
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'COD VISUAL - RAZÓN SOCIAL',
			minimumInputLength: 3,
			dropdownParent: $(".customizer-content"),
		});
		$(".clean_pdv_filtro").on('click', function () {
			$("#pdv_filtro").val(null).trigger("change");
		});
		$(".clean_usuario_filtro").on('click', function () {
			$("#usuario_filtro").val(null).trigger("change");
		});
		// MOSTRANDO FILTROS POR CANAL GRUPO
		View.filtrosGrupoCanal();

		//BARRA DE BUSQUEDA		
		if ($('div.ui.search').length > 0) {
			$('div.ui.search').search({
				type: 'category',
				source: (JSON.parse(menu_opciones)),
				error: {
					noResults: 'No se han encontrado resultados'
				}
			});
		}

		$(document).ready(function () {
			let esmovil = Fn.mobileCheck();
			if (esmovil == true) {
				$('div.customizer.border-left-blue-grey.border-left-lighten-4').removeClass('d-none d-xl-block');
				$('div.customizer.border-left-blue-grey.border-left-lighten-4').addClass('d-sm-block customizerGraphics');
			}
		});

		$(document).on("click", '.btn-datatable-excel', function (e) {
			e.preventDefault();
			let idTabla = $(this).attr('aria-controls');
			Fn.exportarExcelDataTable(idTabla);
		});

		$(document).on("change", '.parentDependiente', function (e) {
			e.preventDefault();
			let childDependiente = $(this).data('childdependiente');
			let idParent = $(this).val();

			if (idParent.length > 0) {
				$("#" + childDependiente).children('option').hide();
				$("#" + childDependiente).children("option[data-parentdependiente^=" + idParent + "]").show()

				$('#' + childDependiente).select2({
					templateResult: function (option) {
						var myOption = $('#' + childDependiente).find('option[value="' + option.id + '"');
						if (myOption.data('parentdependiente') == idParent) {
							return option.text;
						}
						return false;
					}
				});
			} else {
				$("#" + childDependiente).children('option').hide();
				$('#' + childDependiente).find('option[value=""').show();
				$("#" + childDependiente).select2('destroy');
				$("#" + childDependiente).val('');
			}
		});
	},
	toast: (config = {}) => {
		var defaults = {
			'type': 0,
			'message': '',
			'title': 'ImpactBussiness',
			'mins': 0,
			'time': 2000,
			'titleClass': 'bg-primary',
		};
		var config = $.extend({}, defaults, config);

		let icon = '';
		let iconSize = ' fa-2x';
		let message = '';
		let mins = 0;

		switch (Number(config['type'])) {
			case 0:
				icon += 'fas fa-times-circle' + iconSize + ' text-danger';
				message += 'Error! ' + config['message'] + '.';
				break;
			case 1:
				icon += 'fas fa-check-circle' + iconSize + ' text-success';
				message += 'Ok! ' + config['message'] + '.';
				break;
			case 2:
				icon += 'fas fa-exclamation-circle' + iconSize + ' text-warning';
				message += 'Alerta! ' + config['message'] + '.';
				break;
			case 3:
				icon += 'fas fa-question-circle' + iconSize + ' text-primary';
				message += config['message'];
				break;
			default:
				icon += 'far fa-comment-alt fa-3x';
				message += config['message'];
				break;
		}

		var html = '';
		html += `<div class="toast toast-${toastId}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000" data-delay="${config['time']}">`;
		html += `<div class="toast-header ${config['titleClass']}  text-white">`;
		html += `<img src="../public/assets/images/icono.png" class="rounded mr-1" alt="..." style= "height: 20px !important">`;
		html += `<strong class="mr-auto">${config['title']}</strong>`;
		html += `<small>Hace un momento</small>`;
		html += '</div>';
		html += '<div class="toast-body">';
		html += `${message}`;
		html += '</div>';
		html += '</div>';

		$(".toastTopRight").append(html);

		return true;
	},

	filtrosGrupoCanal: function () {
		if ($('.flt_grupoCanal ').length == 0) {
			return false;
		}

		var idGrupoCanal = $('.flt_grupoCanal ').val();
		if (idGrupoCanal.length == 0) {
			$.each(_aSelectGrupoCanal['all'], function (i, v) {
				if ($('.flt_' + v).length > 0) {
					$('.flt_' + v).hide();
				}
			});
		}
		else {
			var aSelectAll = _aSelectGrupoCanal['all'].slice(0);
			if (typeof (_aSelectGrupoCanal[idGrupoCanal]) == 'object') {
				$.each(_aSelectGrupoCanal[idGrupoCanal], function (i, v) {
					if ($('.flt_' + v).length > 0) {
						$('.flt_' + v).show();
					}

					var idx = $.inArray(v, aSelectAll);
					if (idx > -1) {
						aSelectAll.splice(idx, 1);
					}
				});

				$.each(aSelectAll, function (i, v) {
					if ($('.flt_' + v).length > 0) {
						$('.flt_' + v).hide();
					}
				});
			}
		}
	},

	guardarCambioCuenta: function () {
		var data = { data: JSON.stringify(Fn.formSerializeObject('frm-cambiarcuenta')) };
		var url = 'control/guardarCambioCuenta';

		$.when(Fn.ajax({ url: url, data: data })).then(function (a) {
			if (a.result == 2) return false;

			Fn.showModal({ id: View.idModal, show: false });

			Fn.showModal({
				id: ++modalId,
				show: true,
				title: 'Cambiar Cuenta / Proyecto',
				frm: Fn.message({ type: 1, message: 'Datos guardados Correctamente' }),
				btn: [{ title: 'Aceptar', fn: 'location.reload(true);' }]
			});

			localStorage.setItem('modalCuentaProyecto', 1);
		});
	},

	frmClave: function () {
		var html = '';
		html += "<form id='frm-clave' class='form-horizontal' role='form' action='control/clave'>";
		html += "<div class='row'>";
		html += "<div class='col-sm-12 col-md-10  col-md-offset-1'>";
		html += "<div class='form-group'>";
		html += "<label class='col-lg-5 control-label'>Clave Actual</label>";
		html += "<div class='input-group col-lg-5'>";
		html += "<span class='input-group-addon'>";
		html += "<i class='glyphicon glyphicon-lock'></i>";
		html += "</span>";
		html += "<input type='password' name='clave_old' class='form-control' patron='requerido'>";
		html += "</div>";
		html += "</div>";
		html += "<div class='form-group'>";
		html += "<label class='col-lg-5 control-label'>Clave Nueva</label>";
		html += "<div class='input-group col-lg-5'>";
		html += "<span class='input-group-addon'>";
		html += "<i class='glyphicon glyphicon-lock'></i>";
		html += "</span>";
		html += "<input type='password' name='clave_new' class='form-control' patron='requerido'>";
		html += "</div>";
		html += "</div>";
		html += "<div class='form-group'>";
		html += "<label class='col-lg-5 control-label'>*Confirmar Clave Nueva</label>";
		html += "<div class='input-group col-lg-5'>";
		html += "<span class='input-group-addon'>";
		html += "<i class='glyphicon glyphicon-lock'></i>";
		html += "</span>";
		html += "<input type='password' name='clave_repeat' class='form-control' patron='requerido,identico[clave_new]'>";
		html += "</div>";
		html += "</div>";
		html += "</div>";
		html += "</div>";
		html += "</form>";

		return html;
	},

	showTable: function () {
		if ($(".table").height() >= 500) { $(".table-content").css("overflow-y", "scroll"); }
		$("#lb-num-rows").html('Resultados: ' + $('.table >tbody >tr').length);
	},



}
View.load()

var Global = {


	fechaHoraString: function () {
		var dt = new Date();
		var day = dt.getDate();
		var month = dt.getMonth() + 1;
		var year = dt.getFullYear();
		var hour = dt.getHours();
		var minute = dt.getMinutes();
		var second = dt.getSeconds();

		var day = day.toString();
		var month = month.toString();
		var year = year.toString();
		var hour = hour.toString();
		var minute = minute.toString();
		var second = second.toString();

		if (day.length == 1) var day = "0" + day;
		if (month.length == 1) var month = "0" + month;
		if (hour.length == 1) var hour = "0" + hour;
		if (minute.length == 1) var minute = "0" + minute;
		if (second.length == 1) var second = "0" + second;

		return year + month + day + "_" + hour + minute + second;
	},

	dateTime: function () {
		var dt = new Date();
		var day = dt.getDate();
		var month = dt.getMonth() + 1;
		var year = dt.getFullYear();
		var hour = dt.getHours();
		var minute = dt.getMinutes();
		var second = dt.getSeconds();

		var day = day.toString();
		var month = month.toString();
		var year = year.toString();
		var hour = hour.toString();
		var minute = minute.toString();
		var second = second.toString();

		if (day.length == 1) var day = "0" + day;
		if (month.length == 1) var month = "0" + month;
		if (hour.length == 1) var hour = "0" + hour;
		if (minute.length == 1) var minute = "0" + minute;
		if (second.length == 1) var second = "0" + second;

		return year + '/' + month + '/' + day + " " + hour + ':' + minute + ':' + second;
	},

	fechaActual: function () {
		var d = new Date();
		var mes = ((d.getMonth() + 1) > 9) ? (d.getMonth() + 1) : '0' + (d.getMonth() + 1);
		var dia = (d.getDate() > 9) ? d.getDate() : '0' + d.getDate();
		return dia + '/' + mes + '/' + d.getFullYear();
	},

	fechaActual_: function () {
		var d = new Date();
		var mes = ((d.getMonth() + 1) > 9) ? (d.getMonth() + 1) : '0' + (d.getMonth() + 1);
		var dia = (d.getDate() > 9) ? d.getDate() : '0' + d.getDate();
		return d.getFullYear() + '-' + mes + '-' + dia;
	},

	horaActual: function () {
		var d = new Date();
		var hora = (d.getHours() > 9) ? d.getHours() : '0' + d.getHours();
		var minuto = (d.getMinutes() > 9) ? d.getMinutes() : '0' + d.getMinutes();
		var segundo = (d.getSeconds() > 9) ? d.getSeconds() : '0' + d.getSeconds();
		return hora + ':' + minuto + ':' + segundo;
	},

	formatDate: function (date) {
		var arr_date = date.split("-");
		return arr_date[2] + '/' + arr_date[1] + '/' + arr_date[0];
	}
}


var ExportarExcel = {

	getData: function (contenedor) {
		var html = "";
		var css = $("#css-excel").html();
		var contenido = $("#" + contenedor).html();
		html += '<html>';
		html += '<head>';
		html += '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		html += '<style type="text/css">';
		html += css;
		html += '</style>';
		html += '</head>';
		html += '<body>';
		html += contenido;
		html += '</body>';
		html += '</html>';

		html = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
		html = html.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		html = html.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
		html = html.replace(/<IMG[^>]*>/gi, ""); // remove if u want images in your table
		html = html.replace(/<input[^>]*>|<\/input>/gi, ""); // remove input params
		html = html.replace(/<INPUT[^>]*>|<\/INPUT>/gi, ""); // remove input params

		return { html: html };
	},

	generateExcel: function (datos) {
		var contenidoArchivo = [];
		contenidoArchivo.push(datos.html);
		return new Blob(contenidoArchivo, {
			type: 'application/vnd.ms-excel'
		});
	},

	downloadExcel: function (contenidoEnBlob, nombreArchivo) {
		//Compatibilidad
		window.URL = window.URL || window.webkitURL;
		//Usaremos un link para iniciar la descarga
		var save = document.createElement('a');
		save.target = '_blank';
		save.download = nombreArchivo;
		//Identifica el navegador
		var nav = navigator.userAgent.toLowerCase();
		//Internet Explorer
		if ((nav.indexOf("msie") != -1) || (nav.indexOf(".net4") != -1)) {
			window.navigator.msSaveBlob(contenidoEnBlob, nombreArchivo);
		}
		//Chrome
		if (nav.indexOf("chrome") != -1) {
			var url = window.URL.createObjectURL(contenidoEnBlob);
			save.href = url;
			if (document.createEvent) {
				var event = document.createEvent("MouseEvents");
				event.initEvent("click", true, true);
				save.dispatchEvent(event);
			} else if (save.click) {
				save.click();
			}
			window.URL.revokeObjectURL(save.href);
		}
		//Firefox
		if (nav.indexOf("firefox") != -1) {
			var reader = new FileReader();
			reader.onload = function (event) {
				save.href = event.target.result;
				if (document.createEvent) {
					var event = document.createEvent("MouseEvents");
					event.initEvent("click", true, true);
					save.dispatchEvent(event);
				} else if (save.click) {
					save.click();
				}
				window.URL.revokeObjectURL(save.href);
			};
			reader.readAsDataURL(contenidoEnBlob);
		}
	}
}

var Imagen = {

	show: function (e, content, input, flControl) {
		var files = e.target.files || e.dataTransfer.files;
		file = files[0];
		var content = $("#" + content);
		var reader = new FileReader();
		reader.onload = function (e) {
			content.attr("src", e.target.result)
			$("#" + input).val(content.attr("src"));
			$("#" + input + '_show').val($(flControl).val());
		};
		reader.readAsDataURL(file);

	}

}

var File = {

	data: [],

	encode_utf8: function (s) {
		return unescape(encodeURIComponent(s));
	},

	decode_utf8: function (s) {
		return decodeURIComponent(escape(s));
	},

	format_col: function (value, format) {
		var msg = '';
		if (format == 'entero') {
			expr = /^\d+$/;
			msg = 'Solo números.';
		}
		if (format == 'decimal') {
			expr = /^-?[0-9]+([.])?([0-9]+)?$/;
			msg = 'Solo números.';
		}
		if (format == 'porcentaje') {
			expr = /^([0]{1}.[0-9]{1}|[0]{1}.[0-9]{2}|[1]{1})$/;
			msg = 'Solo porcentajes (0.00 a 1).';
		}
		if (format == 'texto') {
			expr = /([^\s])/;
			msg = 'Mínimo una palabra.';
		}
		if (format == 'bit') {
			expr = /[SI-NO]/;
			value = value.toUpperCase();
			msg = 'Solo SI/NO.';
		}
		var array_result = { result: true, msg: '' };
		var result = !expr.test(value);
		if (result) {
			array_result = { result: false, msg: '(' + value + ')' + msg };
		}
		return array_result;
	},

	load: function (e, content, input, flControl, valFormat) {
		var files = e.target.files || e.dataTransfer.files;
		Fn.showLoading(true, 'Procesando...');
		$("#data-content-grid").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size: 1em" aria-hidden="true"></i> Procesando...');
		file = files[0];
		var content = $("#" + content);
		var reader = new FileReader();
		File.data = [];
		reader.onload = function (e) {
			content.attr("src", e.target.result)
			//$("#"+input).val( content.attr("src") );
			$("#" + input + '_show').val($(flControl).val());

			//
			Papa.parse(file, {
				delimiter: "",	// auto-detect
				newline: "",	// auto-detect
				quoteChar: '"',
				escapeChar: '"',
				header: false,
				trimHeaders: false,
				//dynamicTyping: false,
				preview: 0,
				dynamicTyping: true,
				encoding: "",
				worker: true,
				step: undefined,
				error: function () {
					Fn.showLoading(false);
					++modalId;
					var btn = [];
					var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

					btn[0] = { title: 'Continuar', fn: fn };
					Fn.showModal({ id: modalId, show: true, title: 'Archivo', content: "Error al procesar el archivo intentelo nuevamente.", btn: btn });
					$("#data-content-grid").html('');
				},
				download: false,
				skipEmptyLines: false,
				chunk: undefined,
				fastMode: undefined,
				beforeFirstChunk: undefined,
				withCredentials: undefined,
				transform: undefined,
				complete: function (results) {

					File.data = results.data;
					//
					var idEncuesta = $('#' + Procesar.idFormEdit + ' select[name=idEncuesta_]').val();
					var select_ = '<option  value="" >-- Ninguno --</option>';
					if (typeof (pregunta_select[idEncuesta]) == 'object') {
						$.each(pregunta_select[idEncuesta], function (i, v) {
							select_ += '<option value="' + i + '">' + v + '</option>';
						});
					}
					//
					var html = '';
					var arrayVal = [];

					var array_error = [], array_ = [];
					var array_head = [];
					if (File.data.length < 2) html = '<div class="alert alert-danger" ><i class="fa fa-exclamation-circle" ></i> No se encontraron filas en el archivo procesado</div>';
					else {
						//
						html = '';
						var numReg = 0;
						var numError = 0;
						var html = '<table class="table" >'
						$.each(File.data, function (ix, value) {
							if (ix == 0) {
								html += '<thead>';
								html += '<tr>';
								var head = value[0].split(',');
								$.each(head, function (ix_, value_) {
									html += '<td><select class="form-control input-xs" name="sl_pregunta_' + ix_ + '" title="-- Ninguno --" data-actions-box="true" data-live-search="true" patron="requerido"  >' + select_ + '</select ></td>';
								});
								html += '</tr>';
								html += '<tr>';
								var head = value[0].split(',');
								$.each(head, function (ix_, value_) {
									html += '<th>' + value_ + '</th>';
								});
								html += '</tr>';
								html += '</thead><tbody>';
							} else {
								html += '<tr>';
								if ($.isArray(value)) {
									var row = value;
									if (value.length == 1) {
										var json = JSON.stringify(value[0]);
										var string = json.substr(1, json.length - 2)
										if (value[0] != 'null' && value[0] != null && value[0] != 'NULL') var row = value[0].split(',');
										else row = [];
									}
								}

								$.each(row, function (ix_, value_) {
									html += '<td>' + value_ + '</td>';
								});
								html += '</tr>';
							}
						});
						html += '</tbody></table>'
						$("#btn-procesar").removeClass("disabled");
					}

					$("#dv-preview").html(html);
					$(".selectpicker").selectpicker('refresh');
					Fn.showLoading(false);
				}
			});

		};
		reader.readAsDataURL(file);

	}
}