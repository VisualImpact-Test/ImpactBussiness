
var SolicitudCotizacion = {
	frm: 'frm-cotizacion',
	contentDetalle: 'idContentCotizacion',
	url: 'SolicitudCotizacion/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlCotizacion: '',

	load: function () {
		//reportefiltro
		$(document).on('click', '#filtrarReporteOper', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': SolicitudCotizacion.url + 'filtroOper', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
			});
		});

		//reportefiltro
		$(document).on('click', '.btn-generarCotizacion', function () {
			++modalId;

			let items = [];
			$.each($(this).parents('.row').find('.item'), function (index, value) {
				items.push($(value).val());
			});
			let data = { 'items': items };
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': SolicitudCotizacion.url + 'formularioGenerarCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "SolicitudCotizacion.registrarCotizacion()", content: "¿Esta seguro de registrar la cotizacion? " });';
				btn[1] = { title: 'Registrar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

				SolicitudCotizacion.actualizarAutocompleteItemsLogistica();
				SolicitudCotizacion.htmlCotizacion = $('#listaItemsCotizacion tbody tr').html();
				$('#listaItemsCotizacion tbody').html('');
				$(".btn-add-row-cotizacion").click();
			});
		});

		$(document).on('click', '.btnSolicitarCotizacion', function () {
			++modalId;

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

		$(document).on('click', '.btnVerCotizaciones', function () {
			++modalId;
			let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroCotizacion')) };
			let config = { 'url': SolicitudCotizacion.url + 'verCotizacionesProveedor', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[1] = { title: 'Guardar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });
				SolicitudCotizacion.modalIdForm = modalId;
			});
		});
	},
}

SolicitudCotizacion.load();

var Cotizacion = {
	frm: 'frm-cotizacion',
	contentDetalle: 'idContentCotizacion',
	url: 'Cotizacion/',
	itemServicio: [],
	modalIdForm: 0,
	itemsLogistica: [],
	htmlG: '',
	htmlCotizacion: '',
	tablaCotizacionesOper: '',

	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarCotizacion').click();
		});

		//checkbox del datatable

		$(document).ready(function () {
			$('#btn-filtrarCotizacion').click();
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

		//filtroCotizacion
		$(document).on('click', '#filtrarReporte', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Cotizacion.url + 'filtroCotizacion', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.generarOPER()", content: "¿Esta seguro de continuar?" });';
				btn[1] = { title: 'Continuar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });

			});
		});

		$(document).on('click', '.btnAsignarGR', function () {
			let this_ = $(this);
			let idCotizacion = $(this).parents('tr:first').data('id');

			++modalId;
			let jsonString = { 'idCotizacion': idCotizacion };
			let config = { 'url': Cotizacion.url + 'formularioIndicarGR', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {	
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				if (a.result == 1) {
					fn[1] = 'Fn.showConfirm({ idForm: "formRegistroGR", fn: "Cotizacion.registrarGR()", content: "¿Esta seguro de registrar los datos ingresados?" });';
					btn[1] = { title: 'Guardar <i class="fas fa-save"></i>', fn: fn[1] };
				}

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
			});
		});

		$(document).on('click', '#btn-agregar-new-gr', function (e) {
			
			e.preventDefault();
			var html="";
			
			html+='<tr><td class="text-center">';
			html+='<input type="text" class="form-control form-control-sm" name="numeroGR" value="" >';
			html+='</td><td class="text-center" width = "30%">';
			html+='	<div class="ui calendar date-semantic"><div class="ui input left icon"><i class="calendar icon"></i>';
			html+='	<input type="text" placeholder="Fecha GR" value="">';
			html+='	</div></div>	';
			html+='<input type="hidden" class="date-semantic-value" name="fechaGR" placeholder="Fecha GR" value="">';
			html+='</td><td  width = "10%">';
			html+='<button id="btn-agregar-new-gr" class="btn btn-sm btn-success" title="GUARDAR"><i class="fas fa-plus"></i></button>';
			html+='</td></tr>';
			
			
			$('#tbnumeroGR tbody ').append(html);
			Fn.loadSemanticFunctions();
			
			
		});

		$(document).on('click', '.btn-verOrdenesCompra', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': Cotizacion.url + 'getOrdenesCompra', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
			});
		});

		$(document).on('click', '#btn-tablaCotizacion', function () {
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

		$(document).on('click', '.btn-generarRequerimiento', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCotizacion': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioItemsPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				//Cotizacion.actualizarAutocomplete();
			});
		});

		$(document).on('click', '.generar-requerimiento-rrhh', function () {
			++modalId;

			let id = $(this).data('id');
			let data = { 'idCotizacionDetalle': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioRequerimientoPersonal', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });

				//Cotizacion.actualizarAutocomplete();
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

			let $filas = $('#listaItemsCotizacion tbody tr').length;
			$filas = $filas + 1;
			let $html = "<tr class='nuevo nuevoItem'><td class='n_fila' ><label class='nfila'>" + $filas + "</label><i class='estadoItemForm fa fa-sparkles' style='color: teal;'></i></td>";
			$html += Cotizacion.htmlG;
			$html += "</tr>";

			$('#listaItemsCotizacion tbody').append($html);

			//Para ordenar los select2 que se descuadran
			$('.my_select2').select2();
			Cotizacion.actualizarAutocomplete();
			$("#div-ajax-detalle").animate({ scrollTop: $("#listaItemsCotizacion").height() }, 500);
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
			$(this).parents('.nuevo').remove();
			$(this).parents('.fila-existente').remove();

			$.each($('#listaItemsCotizacion tbody tr .n_fila'), function (index, value) {
				$(this).find('.nfila').text(Number(index) + 1);
			});

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

		$(document).on('click', '.btn-cotizacion-pdf', function (e) {
			e.preventDefault();

			let $idCotizacion = $(this).parents('tr').data('id');

			Cotizacion.generarRequerimientoPDF($idCotizacion);
		});

		// $(document).on('click', '.btn-generarCotizacion', function () {
		// 	++modalId;

		// 	let items = [];
		// 	$.each($(this).parents('.row').find('.item'), function (index, value) {
		// 		items.push($(value).val());
		// 	});
		// 	let data = { 'items': items };
		// 	let jsonString = { 'data': JSON.stringify(data) };
		// 	let config = { 'url': Cotizacion.url + 'formularioGenerarCotizacion', 'data': jsonString };

		// 	$.when(Fn.ajax(config)).then((a) => {
		// 		let btn = [];
		// 		let fn = [];

		// 		fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
		// 		btn[0] = { title: 'Cerrar', fn: fn[0] };
		// 		fn[1] = 'Fn.showConfirm({ idForm: "formRegistroItems", fn: "Cotizacion.registrarCotizacion()", content: "¿Esta seguro de registrar la cotizacion? " });';
		// 		btn[1] = { title: 'Registrar', fn: fn[1] };

		// 		Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });

		// 		Cotizacion.actualizarAutocompleteItemsLogistica();
		// 		Cotizacion.htmlCotizacion = $('#listaItemsCotizacion tbody tr').html();
		// 		$('#listaItemsCotizacion tbody').html('');
		// 		$(".btn-add-row-cotizacion").click();
		// 	});
		// });

		$(document).on('keyup', '.cantidadForm', function (e) {
			e.preventDefault();
			let thisControl = $(this);
			let thisControlParents = thisControl.parents('.nuevo');
			let costoForm = thisControlParents.find('.costoForm');

			let subTotalForm = thisControlParents.find('.subtotalForm');
			let subTotalFormLabel = thisControlParents.find('.subtotalFormLabel');

			let cantidad = Number(thisControl.val());
			let costo = Number(costoForm.val());

			let subTotal = Fn.multiply(cantidad, costo);
			var formatter = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'PEN',

				// These options are needed to round to whole numbers if that's what you want.
				//minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
				//maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
			});
			subTotalForm.val(subTotal);
			subTotalFormLabel.val(formatter.format(subTotal));
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

		$(document).on('click', '.btn-aprobar-cotizacion', function () {
			++modalId;
			let data = {};
			data.id = $(this).closest("tr").data("id");
			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioAprobar', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				if (a.data.existe == 0) {
					Cotizacion.itemServicio = a.data.itemServicio;
				}

				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(5)", content: "¿Esta seguro de enviar esta cotizacion?" });';
				btn[1] = { title: 'Aprobar <i class="fas fa-paper-plane"></i>', fn: fn[1] };

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
					if ((num + total) > control.data('fileMax')) {
						var message = Fn.message({ type: 2, message: `Solo se permiten ${control.data('fileMax')} archivo como máximo` });
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
							var message = Fn.message({ type: 2, message: `Solo se permite como máximo ${KB_MAXIMO_ARCHIVO / 1024} por archivo` });
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
							} else {
								imgFile = `${RUTA_WIREFRAME}pdf.png`;
								contenedor = content_files;
							}

							var fileApp = '';
							fileApp += '<div class="ui fluid image content-lsck-capturas">';
							fileApp += `<div class="ui sub header">${fileBase.name}</div>`;
							fileApp += `
											<div class="ui dimmer dimmer-file-detalle">
												<div class="content">
													<p class="ui tiny inverted header">${fileBase.name}</p>
												</div>
											</div>`;
							fileApp += '<a class="ui red right ribbon label img-lsck-capturas-delete"><i class="trash icon"></i></a>';
							fileApp += '<input type="hidden" name="' + name + '[' + id + ']" value="' + fileBase.base64 + '">';
							fileApp += '<input type="hidden" name="' + nameType + '[' + id + ']" value="' + fileBase.type + '">';
							fileApp += '<input type="hidden" name="' + nameFile + '[' + id + ']" value="' + fileBase.name + '">';
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
			control.parents('.content-lsck-capturas:first').remove();
		});
		
		$(document).on('click', '.btn-finalizarCotizacion', function () {
			let idCotizacion = $(this).closest('tr').data('id');
			Fn.showConfirm({ idForm: "formRegistroItems", fn: "Cotizacion.finalizarCotizacion(" + idCotizacion + ")", content: "¿Esta seguro que quiere finalizar la cotizacion? " });
		});

		$(document).on('click', '.btn-completarDatos', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idCotizacion': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': Cotizacion.url + 'formularioCompletarDatos', 'data': jsonString };
			//console.log(config);
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formCompletarDatos", fn: "Cotizacion.guardarCompletarDatos()", content: "¿Esta seguro de guardar datos?" });';
				btn[1] = { title: 'Guardar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '55%' });

				Cotizacion.actualizarAutocomplete();
			});


		});

		$(document).on('click', '#btn-añadir-linea', function (e) {
			e.preventDefault();
			let lineaNum = $('input[name="agregarLineaNum"]').val();
			//console.log(lineaNum);
			if(lineaNum > 0){
				var html="";
				html+='<tr>';
				html+='<td class="text-center">';
				html+='<input type="text" class="form-control form-control-sm read-only" name="mail_enviar" value="LINEA" >';
				html+='</td>';
				html+='<td width="5%">';
				html+='<input type="text" class="form-control form-control-sm read-only" name="lineaNum" value="'+lineaNum+'" >';
				html+='</td>';
				html+='</tr>';
			$('#tbLineaNum tr:last').after(html);
			}
			$('input[name="agregarLineaNum"]').val("");	
		});


		$(document).on('click', '.btn-descargarOper', function () {
			let idOper = $(this).closest('tr').data('idoper');
			if (idOper == undefined) {
				idOper = $(this).data('idoper');
			}
			let data = { idOper };
			let jsonString = { 'data': JSON.stringify(data) };
			Fn.download(site_url + Cotizacion.url + 'descargarOper', jsonString);
		});
		$(document).on('click', '.btn-descargarOrdenCompra', function () {
			let id = $(this).closest('tr').data('id');
			if (id == undefined) {
				id = $(this).data('id');
			}
			let data = { id };
			let jsonString = { 'data': JSON.stringify(data) };
			Fn.download(site_url + Cotizacion.url + 'descargarOrdenCompra', jsonString);
		});
		$(document).on('click', '.btn-descargarCotizacion', function () {
			let id = $(this).closest('tr').data('id');
			if (id == undefined) {
				id = $(this).data('id');
			}
			let data = { id };
			let jsonString = { 'data': JSON.stringify(data) };

			Fn.download(site_url + Cotizacion.url + 'generarCotizacionPDF', jsonString);
		});

		$(document).on('click', '.btnAnularCotizacion', function () {
			let id = $(this).data('id');
			Fn.showConfirm({ fn: "Cotizacion.anularCotizacion(" + id + ")", content: " ¿Está seguro de anular esta cotización?" });
		});
		$(document).on('click', '.btnI', function () {
			let id = $(this).data('id');
			var jsonString = { 'data': JSON.stringify(id) };
			var config = { url: Cotizacion.url + 'anulacionInfo', data: jsonString };
			$.when(Fn.ajax(config)).then(function (a) {
				if (a.result === 2) return false;
				++modalId;
				var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

				if (a.result == 1) fn += 'Fn.showModal({ id:' + modalId + ',show:false });$("#btn-filtrarCotizacion").click();';

				var btn = [];
				btn[0] = { title: 'Cerrar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content, escape: true });
			});
		});

	},
	anularCotizacion: function (id) {
		var jsonString = { 'data': JSON.stringify(id) };
		var config = { url: Cotizacion.url + 'anularCotizacion', data: jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result === 2) return false;
			++modalId;
			var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (a.result == 1) fn += 'Fn.showModal({ id:' + modalId + ',show:false });$("#btn-filtrarCotizacion").click();';

			var btn = [];
			btn[0] = { title: 'Cerrar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
		});
	},
	actualizarCotizacion: function () {
		++modalId;
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizacionCotizacions')) };
		let config = { 'url': Cotizacion.url + 'actualizarCotizacion2', 'data': jsonString };

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

	generarOPER: function () {
		var ids = [];

		if (typeof Cotizacion.tablaCotizacionesOper !== 'undefined') {
			$.map(Cotizacion.tablaCotizacionesOper.rows('.selected').nodes(), function (item) {
				ids.push($(item).data("id"));
			});
		}

		if (ids.length === 0) {
			btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' };
			var content = "No ha seleccionado ningún registro.</strong>";
			Fn.showModal({ id: modalId, show: true, title: titulo, content: content, btn: btn });
			return false;
		}

		let data = { ids: ids };
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Cotizacion.url + 'frmGenerarOper', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };
			fn[1] = 'Fn.showConfirm({ idForm: "formRegistroOper", fn: "Cotizacion.generarOPER_vistaPrevia()", content: "¿Mostrar vista previa?" });';
			btn[1] = { title: 'Vista Previa', fn: fn[1] };
			fn[2] = 'Fn.showConfirm({ idForm: "formRegistroOper", fn: "Cotizacion.generarOPER_guardar()", content: "¿Esta seguro de guardar y enviar el OPER ?" });';
			btn[2] = { title: 'Enviar', fn: fn[2] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });

			$('.simpleDropdown').dropdown();
		});

	},
	finalizarCotizacion: function (idCotizacion) {
		let data = { idCotizacion };
		let jsonString = { 'data': JSON.stringify(data) };
		let url = Cotizacion.url + "finalizarCotizacion";
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
	generarOPER_guardar: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOper')) };
		let config = { 'url': Cotizacion.url + 'registrarOper', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarCotizacion").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '40%' });
		});
	},
	generarOPER_vistaPrevia: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOper')) };
		let config = { 'url': Cotizacion.url + 'registrarOperTemp', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			// let btn = [];
			// let fn = [];
			// console.log(a);
			idOper = a.data.idOper;
			let data = { idOper };
			let jsonString = { 'data': JSON.stringify(data) };
			Fn.download(site_url + Cotizacion.url + 'descargarOper', jsonString);
			// var url = site_url + '/Cotizacion/generarCotizacionPDF/' + a.data.idOper;
			// window.open(url, '_blank');

		});
	},
	registrarGR: function () {
		let formValues = Fn.formSerializeObject('formRegistroGR');
		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "registrarGR";
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
			if (tipo == value.tipo || tipo == 3) {
				items[nro] = value;
				nro++;
			}
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
				if (ui.item.costo == null || ui.item.semaforoVigencia == "red") {
					ui.item.costo = 0;
				}
				control.find(".costoForm").val(ui.item.costo);
				control.find(".costoFormLabel").text(ui.item.costo);
				//Llenamos el estado
				control.find(".estadoItemForm").removeClass('fa-sparkles');
				control.removeClass('nuevoItem');
				control.find(".idEstadoItemForm").val(1);
				control.find(".idTipoItem").val(ui.item.tipo);
				control.find(".cotizacionInternaForm").val(ui.item.cotizacionInterna)

				//Llenamos el proveedor
				control.find(".proveedorForm").text(ui.item.proveedor);
				control.find(".idProveedor").val(ui.item.idProveedor);

				//LLenar semaforo

				control.find(".semaforoForm").addClass('semaforoForm-' + ui.item.semaforoVigencia);

				control.find('.semaforoForm').popup({ content: `Vigencia: ${ui.item.diasVigencia} días` });

				//Validar boton ver caracteristicas del articulo

				control.find(".verCaracteristicaArticulo").removeClass(`slash`);

				//Validacion ID

				let $cod = $(this).parents(".ui-widget").find(".codItems").val();
				if ($cod != '') {
					$(this).attr('readonly', 'readonly');
					control.find('.costoForm').attr('readonly', 'readonly');
					control.find(".cantidadForm").attr('readonly', false);
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

		//Fn.download
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

	actualizarTotal: function () {
		let total = 0;
		$.each($('.subtotalForm'), function (index, value) {
			total = Number(total) + Number($(value).val());
		})

		var formatter = new Intl.NumberFormat('en-US', {
			style: 'currency',
			currency: 'PEN',
		});
		$('.totalForm').val(total);
		$('.totalFormLabel').val(formatter.format(Number(total)));
	},

	guardarCompletarDatos: function () {
		let formValues = Fn.formSerializeObject('formCompletarDatos');

		let jsonString = { 'data': JSON.stringify(formValues) };
		let url = Cotizacion.url + "guardarCompletarDatos";
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
	}
}

Cotizacion.load();