var OrdenServicio = {

	frm: 'frm-ordenServicio',
	contentDetalle: 'idContentOrdenServicio',
	url: 'OrdenServicio/',
	sueldoConteo: 0,
	arrayPersona: {},
	arrayCargo: {},
	arrayFechas: {},
	arrayTipoPresupuestoDetalle: {},
	provincia: {},
	distrito: {},
	documentoCont: 0,
	pruebaConteo: 0,
	load: function () {

		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarOrdenServicio').click();
		});

		$(document).ready(function () {
			$('#btn-filtrarOrdenServicio').click();
		});

		$(document).on('click', '#btn-filtrarOrdenServicio', function () {
			var ruta = 'reporte';
			var config = {
				'idFrm': OrdenServicio.frm
				, 'url': OrdenServicio.url + ruta
				, 'contentDetalle': OrdenServicio.contentDetalle
			};
			Fn.loadReporte_new(config);
		});

		$(document).on('change', '.cboDocumento', function () {
			control = $(this);
			let idArea = control.find('option:selected').data('idarea');
			let idPersonal = control.find('option:selected').data('idpersonal');
			let direccion = control.find('option:selected').data('nombre_archivo');
			let text = control.find('option:selected').text();

			txtDocumento = control.closest('.fields').find('div.divDocumento').find('input');
			$(txtDocumento).val(text).trigger('change');

			cboArea = control.closest('.fields').find('div.divArea').find('select');
			$(cboArea).val(idArea).trigger('change');

			cboPersonal = control.closest('.fields').find('div.divPersonal').find('select');
			$(cboPersonal).val(idPersonal).trigger('change');

			a = control.closest('.fields').find('.botonDescarga');
			$(a).attr('href', 'https://s3.us-central-1.wasabisys.com/impact.business/documentos/' + direccion);
		})
		$(document).on('click', '#btn-registrarOrdenServicio', function () {
			++modalId;

			let jsonString = { 'data': '' };
			let config = { 'url': OrdenServicio.url + 'formularioRegistroOrdenServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroOrdenServicio", fn: "OrdenServicio.registrarOrdenServicio()", fnFin: "OrdenServicio.validarCheckbox()", content: "¿Esta seguro de registrar la Orden de Servicio?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });

				OrdenServicio.provincia = a.data.provincia;
				OrdenServicio.distrito = a.data.distrito;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				OrdenServicio.addFechas();
				Fn.loadSemanticFunctions();
				OrdenServicio.validarCheckbox();
			});
		});
		$(document).on('change', '.cloneAll', function () {
			id = $(this).data('personal');
			valor = $(this).val();
			$('.cloned' + id).val(valor);
			$('#cantidadSueldo_' + id).html(valor);
			$('#cantidadIncentivo_' + id).html(valor);

			cantidad = 0;
			cantidadPrincipal = $('.cloneAll');
			continuar = true;
			for (let i = 0; i < cantidadPrincipal.length; i++) {
				temp = $(cantidadPrincipal[i]).val();
				if (temp != '') {
					cantidad += parseInt(temp);
				} else {
					continuar = false;
				}
			}

			if (continuar) {
				td = $('td.cantidadDeTabla');
				for (let i = 0; i < td.length; i++) {
					split = $(td[i]).closest('tr').find('td.splitDetalle').find('input').val();
					// valorCalc = cantidad * parseFloat(split);
					// valorCalc = Math.ceil(valorCalc);
					$(td[i]).find('.ui.action.input').find('input').trigger('change');
				}
				$('.tabTiposPresupuestos').removeClass('disabled');
			} else {
				$('.tabTiposPresupuestos').addClass('disabled');
			}
		})

		$(document).on('change', '.cntColmFC', function () {
			td = $('td.cantidadDeTabla');
			for (let i = 0; i < td.length; i++) {
				split = $(td[i]).closest('tr').find('td.splitDetalle').find('input').val();
				$(td[i]).find('.ui.action.input').find('input').trigger('change');
			}
			$('#tablaSueldo tbody tr input.keyUpChange:first').change();
		})

		$(document).on('click', '.btn-editar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formularioActualizacionOrdenServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formActualizacionOrdenServicio", fn: "OrdenServicio.actualizarOrdenServicio()", content: "¿Esta seguro de actualizar la Orden de Servicio?" });';
				btn[1] = { title: 'Actualizar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });
				OrdenServicio.provincia = a.data.provincia;
				OrdenServicio.distrito = a.data.distrito;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				Fn.loadSemanticFunctions();
				OrdenServicio.validarCheckbox();
			});
		});

		$(document).on('click', '.btn-aprobarPresupuesto', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let version = $(this).parents('tr:first').data('version');
			let data = { 'idPresupuesto': id, 'idPresupuestoHistorico': version };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'aprobarVersion', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.closeModals(2); $("#btn-filtrarOrdenServicio").click()';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn, width: '40%' });
			});
		});
		$(document).on('click', '.btn-version-presupuesto', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let estado = $(this).parents('tr:first').data('estado');
			//console.log(estado);
			let data = { 'idOrdenServicio': id, 'idOrdenServicioEstado': estado };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formatoVersionesAnteriores', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });
			});
		});

		$(document).on('click', '.btn-copyOrdenServicio', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id, 'formato': 'duplicar' };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formularioActualizacionOrdenServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formDuplicarOrdenServicio", fn: "OrdenServicio.registrarOrdenServicio()", content: "¿Esta seguro de registrar la Orden de Servicio?" });';
				btn[1] = { title: 'Guardar', fn: fn[1] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });

				OrdenServicio.provincia = a.data.provincia;
				OrdenServicio.distrito = a.data.distrito;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				OrdenServicio.addFechas();
				Fn.loadSemanticFunctions();
				OrdenServicio.validarCheckbox();
			});
		});

		$(document).on('change', '.cboTPD', function () {
			let control = $(this);
			// Validar que no se repite el valor
			let cantidadEncontrado = 0;
			let datoARevisar = $(this).dropdown('get value');
			control.closest('table').find('.cboTPD').each(function () {
				if ($(this).dropdown('get value') == datoARevisar) {
					cantidadEncontrado++;
					if (cantidadEncontrado > 1) {
						Fn.showLoading(true);
						message = Fn.message({ type: 3, message: 'Se ha repetido la opción indicada' });
						Fn.showModal({
							'id': ++modalId,
							'show': true,
							'title': 'Alerta',
							'frm': message,
							'btn': [{ 'title': 'Cerrar', 'fn': 'Fn.showModal({ id: ' + modalId + ', show: false });' }]
						});

						setTimeout(function () {
							control.dropdown('clear');
							Fn.showLoading(false);
						}, 500);
						return false;
					}
				}
			});

			// Fin: Validar que no se repite el valor

			let precio = control.find('option:selected').data('preciounitario') || 0;
			let split = control.find('option:selected').data('split') || 1;
			let frecuencia = control.find('option:selected').data('frecuencia');

			txtPrecio = control.closest('tr').find('td.precioUnitarioDetalle').find('input');
			txtSplit = control.closest('tr').find('td.splitDetalle').find('input');
			cboFrecuencia = control.closest('tr').find('td.frecuenciaDetalle').find('select');

			$(txtPrecio).val(precio).trigger('change');
			$(txtSplit).val(split).trigger('change');
			$(cboFrecuencia).val(frecuencia).trigger('change');

			let nrofila = control.closest('tr').data('nrofila');
			control.closest('tbody').find('tr.cantidadElementos_' + nrofila).find('table').find('tbody').html('');
		})
		$(document).on('click', '.btnPresupuesto', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formularioRegistroPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroPresupuesto", fn: "OrdenServicio.registrarPresupuesto()", content: "¿Esta seguro de registrar el resupuesto?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '98%' });

				OrdenServicio.arrayFechas = a.data.fechas;
				OrdenServicio.arrayTipoPresupuestoDetalle = a.data.tipoPresupuestoDetalle;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				$('.menu .item').tab();
				$('.submenu .item').tab({
					context: 'submenu'
				});
				Fn.loadSemanticFunctions();

				td = $('td.cantidadDeTabla');
				for (let i = 0; i < td.length; i++) {
					$(td[i]).find('.ui.action.input').find('input').trigger('change');
				}
				$('.tabTiposPresupuestos').removeClass('disabled');
				$("#calculateTablaSueldo").click();
			});
		});
		$(document).on('click', '.btnPresupuestoEdit', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('presupuesto');
			console.log(id);
			let data = { 'idPresupuesto': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formularioEditarPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formEditarPresupuesto", fn: "OrdenServicio.editarPresupuesto()", content: "¿Esta seguro de modificar el resupuesto?" });';
				btn[1] = { title: 'Modificar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '95%' });

				OrdenServicio.arrayFechas = a.data.fechas;
				OrdenServicio.arrayTipoPresupuestoDetalle = a.data.tipoPresupuestoDetalle;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				$('.tabular.menu .item').tab();
				Fn.loadSemanticFunctions();

				td = $('td.cantidadDeTabla');
				for (let i = 0; i < td.length; i++) {
					$(td[i]).find('.ui.action.input').find('input').trigger('change');
				}
				$('.tabTiposPresupuestos').removeClass('disabled');
				$("#calculateTablaSueldo").click();
				OrdenServicio.calcularTotalesMovilidad();
				$('#tablaSueldoAdicional tbody tr:first').find('.movilidadSueldoAdicional').change();
				$('#tablaAlmacenMonto tbody tr').find('select').change();
			});
		});
		$(document).on('click', '.btnAprobar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'aprobarOrdenServicio', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				$("#btn-filtrarOrdenServicio").click();
			});
		});
		$(document).on('change', '.date-semantic-value', function () {
			let text = $(this).val();
			const myArray = text.split("-");
			let control = $(this).parent('th').find('label');
			control.html(myArray[2] + '/' + myArray[1] + '/' + myArray[0]);
		});
		$(document).on('change', '#cboRegion', function (e) {
			e.preventDefault();
			var idDepartamento = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			$('#cboDistrito').html(html);

			if (typeof (OrdenServicio.provincia[idDepartamento]) == 'object') {
				$.each(OrdenServicio.provincia[idDepartamento], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#cboProvincia').html(html);
			Fn.selectOrderOption('cboProvincia');
		});
		$(document).on('change', '#cboProvincia', function (e) {
			e.preventDefault();
			var idDepartamento = $('#cboRegion').val();
			var idProvincia = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			if (typeof (OrdenServicio.distrito[idDepartamento][idProvincia]) == 'object') {
				$.each(OrdenServicio.distrito[idDepartamento][idProvincia], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#cboDistrito').html(html);
			Fn.selectOrderOption('cboDistrito');
		});
		// $(document).on('click', '#btnCrearTabla', function () {
		// 	let jsonString = { 'nroFecha': $('#nroFecha').val(), 'nroPersona': $('#nroPersona').val() };
		// 	let config = { 'url': OrdenServicio.url + 'formTablaParaLlenado', 'data': jsonString };
		// 	$.when(Fn.ajax(config)).then((a) => {
		// 		$('#divTabla').html(a.data.html);
		// 		$('#divSueldo').html(a.data.htmlSueldo);
		// 		$('.menu .item').tab();
		// 		Fn.loadSemanticFunctions();
		// 		OrdenServicio.sueldoConteo = 0;
		// 		OrdenServicio.arrayPersona = a.data.personal;
		// 	});
		// })
		$(document).on('click', '#btnSueldo', function () {
			var rowCount = $('#tablaFechaPersona >tbody >tr').length;
			OrdenServicio.sueldoConteo++;
			let html = `
			<tr>
				<td><input class="form-control tipoSueldo" value=""></td>
				<td>
					<select class="ui search dropdown semantic-dropdown cboSueldo">
						<option value="">Sueldo</option>
						<option value="1" data-tipo="1">Salario</option>
						<option value="2" data-tipo="1">Asignación Familiar</option>
						<option value="3" data-tipo="2">Movilidad</option>
						<option value="4" data-tipo="3">Comision Variable</option>
					</select>
				</td>
				<td><input class="form-control tipoSueldo" value="0"></td>
				`;

			for (let i = 0; i < rowCount; i++) {
				html += '<td><input class="form-control dSueldo" data-persona="' + i + '" data-sueldo="' + OrdenServicio.sueldoConteo + '" value="0"></td>';
			}
			html += '</tr>';
			$('#bodySueldo').append(html);
			Fn.loadSemanticFunctions();
		})
		// $(document).on('click', '#btnBeneficio', function () {
		// 	var rowCount = $('#tablaFechaPersona >tbody >tr').length;
		// 	OrdenServicio.sueldoConteo++;
		// 	let html = `
		// 	<tr>
		// 		<td><input class="form-control tipoSueldo" value="4"></td>
		// 		<td>
		// 			<select class="ui search dropdown semantic-dropdown cboBeneficio">
		// 				<option value="">-</option>
		// 				<option value="1" data-porcentaje="9">EsSalud</option>
		// 				<option value="2" data-porcentaje="9.7">CTS</option>
		// 				<option value="3" data-porcentaje="9.1">Vacaciones</option>
		// 				<option value="4" data-porcentaje="18.2">Gratificación</option>
		// 				<option value="5" data-porcentaje="0.26">Seguro vida ley</option>
		// 			</select>
		// 		</td>
		// 		<td><input class="form-control porcentajeSueldo" value=""></td>
		// 		`;

		// 	for (let i = 0; i < rowCount; i++) {
		// 		html += '<td><input class="form-control dSueldo" data-persona="' + i + '" data-sueldo="' + OrdenServicio.sueldoConteo + '" value="0"></td>';
		// 	}
		// 	html += '</tr>';
		// 	$('#bodyBeneficio').append(html);
		// 	Fn.loadSemanticFunctions();
		// })
		$(document).on('change', '.cboSueldo', function () {
			let tipo = $(this).find(':selected').data('tipo');
			let tipoSueldo = $(this).parent('td').parent('tr').find('input.tipoSueldo');
			tipoSueldo.val(tipo);

			let cl = $(this).find(':selected').data('cl');
			let porCL = $(this).parent('td').parent('tr').find('input.porCL');
			porCL.val(cl);
		})
		$(document).on('change', '.cboBeneficio', function () {
			let tipo = $(this).find(':selected').data('porcentaje');
			let control = $(this).parent('td').parent('tr').find('input.porcentajeSueldo');
			control.val(tipo);
		})
		$(document).on('change', '#cboCuenta', function () {
			$('#divCargo').find('.fields').remove();
			if ($(this).val()) {
				$('#btn-addCargo').removeClass('disabled');
			} else {
				$('#btn-addCargo').addClass('disabled');
			}
		})
		HTCustom.load();

	},

	registrarOrdenServicio: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOrdenServicio')) };
		// let jsonString = { 'data': Fn.formSerializeObject('formRegistroOrdenServicio') };
		let url = OrdenServicio.url + "registrarOrdenServicio";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOrdenServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
			OrdenServicio.validarCheckbox();
		});
	},
	registrarPresupuesto: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroPresupuesto')) };
		let url = OrdenServicio.url + "registrarPresupuesto";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOrdenServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	editarPresupuesto: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formEditarPresupuesto')) };
		let url = OrdenServicio.url + "editarPresupuesto";
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOrdenServicio").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	addRow: function (t) {
		let detalle = $(t).data('detalle');
		var contador = $("#tb_LD" + detalle + " tbody tr.dataItem").length;
		Fn.showLoading(true);
		let post1 = $.post(
			site_url + OrdenServicio.url + 'generarRowParaPresupuesto_1', {
			'detalle': detalle,
			'contador': contador,
			'cargos': OrdenServicio.arrayCargo,
		});

		post1.done(function (data) {
			$('#tabla' + detalle + ' > tbody').append(data);
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			Fn.loadSemanticFunctions();
		}).always(function () {
			let post2 = $.post(
				site_url + OrdenServicio.url + 'generarRowParaPresupuesto_2', {
				'detalle': detalle,
				'contador': contador,
				'fechas': OrdenServicio.arrayFechas
			});

			post2.done(function (data) {
				$('#tb_LD' + detalle + ' > tbody').append(data);
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				Fn.loadSemanticFunctions();
			}).always(function () {
				Fn.showLoading(false);
			});
		});

	},
	evaluarSubTotalElemento: function (t) {
		let this_ = $(t);
		let control = this_.closest('tbody');

		let nroFila = control.data('nrofila');

		let cantMax = control.closest('table').closest('tbody').find('tr.detalleTr_' + nroFila).find('.cantidadDeTabla').find('input').val();
		let inpSubTotal = control.closest('table').closest('tbody').find('tr.detalleTr_' + nroFila).find('.precioUnitarioDetalle').find('input');
		let cantTot = 0;
		let subTot = 0;
		control.find('.cantidadElemento').each(function () {
			cantTot += parseFloat($(this).val());
		});

		if (parseFloat(cantMax).toFixed(2) == parseFloat(cantTot).toFixed(2)) {
			control.find('.subTotalElemento').each(function () {
				subTot += parseFloat($(this).val());
			});
			inpSubTotal.val((subTot / parseFloat(cantTot)).toFixed(4)).change();
		} else {
			inpSubTotal.val('0.00').change();
		}
	},
	addDocumento: function (tipo = 1) {
		Fn.showLoading(true);
		documentoGenerado = tipo == 1 ? 0 : 1;
		post = $.post(site_url + OrdenServicio.url + 'addDocumento', {
			'id': OrdenServicio.documentoCont,
			'documentoGenerado': documentoGenerado
		});
		post.done(function (html) {
			$('#divDocumentos').append(html);
			OrdenServicio.documentoCont++;
			Fn.loadSemanticFunctions();
			Fn.showLoading(false);
			// Por algún motivo se desactivan la funcionalidad de los check asi que se necesita la siguiente linea:
			OrdenServicio.validarCheckbox();
		});
	},
	buscarCheckDependiente: function (t) {
		tpdDpendiente = $(t).data('buscardependiente');
		if ($(t).prop('checked')) {
			$(t).closest('.list').find('.idDependiente' + tpdDpendiente).removeClass('d-none');
		} else {
			$(t).closest('.list').find('.idDependiente' + tpdDpendiente).addClass('d-none');
		}

		let chk = $(t).closest('.list').closest('.item').find('.master.checkbox').checkbox('is checked');
		let $checkBoxDflt = $(t).closest('.list').find('.chkDefault .child.checkbox');
		$checkBoxDflt.each(function () {
			if (chk || $(t).prop('checked')) {
				$(this).checkbox('set checked');
			} else {
				$(this).checkbox('set unchecked');
			}
		});
	},
	validarSiClienteOCuenta: function (t) {
		let this_ = $(t);
		this_.closest('.fields').find('.divCl, .divCu').toggleClass('d-none');

		this_.toggleClass('blue');

		$('#cboCuenta').dropdown('clear');
		$('#cboCentroCosto').dropdown('clear');
		$('#divCargo').find('.fields').remove();

		if (this_.closest('.fields').find('.divCl').hasClass('d-none')) {
			this_.closest('.fields').find('input.chkUtilizarCliente').val('0');
			this_.closest('.fields').find('.divCu').find('select').attr('patron', 'requerido');
			this_.closest('.fields').find('.divCl').find('select').removeAttr('patron');
			$('#btn-addCargo').addClass('disabled');
		} else {
			this_.closest('.fields').find('input.chkUtilizarCliente').val('1');
			this_.closest('.fields').find('.divCl').find('select').attr('patron', 'requerido');
			this_.closest('.fields').find('.divCu').find('select').removeAttr('patron');
			$('#btn-addCargo').removeClass('disabled');
		}
	},
	addSueldoCargoAdicional: function () {
		let bodyTable = $('#tablaSueldoAdicional').find('tbody');
		Fn.showLoading(true);
		post = $.post(
			site_url + OrdenServicio.url + 'generarRowAdicionalSueldo', {
			'idCuenta': $('#idCuenta').val()
		});

		post.done(function (data) {
			bodyTable.append(data);
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			Fn.loadSemanticFunctions();
		}).always(function () {
			Fn.showLoading(false);
		});
	},

	agregar_movilidad: function () {
		var jsonString = { 'ruta': 'modulos/OrdenServicio/Elements/FormAgregarMovilidad' };
		var configAjax = { 'url': '../Control/get_HTML', 'data': jsonString };

		$.when(Fn.ajax2(configAjax)).then(function (a) {
			if (a.result === 1) {
				++modalId;
				let btn = [], fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				fn[1] = 'Fn.showConfirm({ idForm: "form_movil_save", fn: "OrdenServicio.save_movilidad()", content: "¿Esta seguro de registrar esta movilidad?" });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				btn[1] = { title: 'Agregar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: "Movilidad", frm: a.msg.content, btn: btn, width: '40%' });
			}
		});
	},
	agregar_almacen: function () {
		var jsonString = { 'ruta': 'modulos/OrdenServicio/Elements/FormAgregarAlmacen' };
		var configAjax = { 'url': '../Control/get_HTML', 'data': jsonString };

		$.when(Fn.ajax2(configAjax)).then(function (a) {
			if (a.result === 1) {
				++modalId;
				let btn = [], fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				fn[1] = 'Fn.showConfirm({ idForm: "form_almacen_save", fn: "OrdenServicio.save_almacen()", content: "¿Esta seguro de registrar un nuevo Almacen?" });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				btn[1] = { title: 'Agregar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: "Almacen", frm: a.msg.content, btn: btn, width: '70%' });
			}
		});
	},

	listado_almacen: function () {

		++modalId;
		let data = {};
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': OrdenServicio.url + 'listadoAlmacenes', 'data': jsonString };

		$.when(Fn.ajax(config)).then((a) => {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };
			// fn[1] = 'Fn.showConfirm({ idForm: "formRegistroCotizacion", fn: "Cotizacion.registrarCotizacion(5)", content: "¿Esta seguro de enviar esta cotizacion?" });';
			// btn[1] = { title: 'Aprobar <i class="fas fa-paper-plane"></i>', fn: fn[1] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '60%' });


		});

	},
	listado_movilidad: function () {

		++modalId;
		let data = {};
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': OrdenServicio.url + 'listadoMovilidad', 'data': jsonString };

		$.when(Fn.ajax(config)).then((a) => {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			btn[0] = { title: 'Cerrar', fn: fn[0] };
			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '80%' });
		});

	},
	save_movilidad: function () {

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('form_movil_save')) };
		let url = OrdenServicio.url + "registrarNuevaMovilidad";
		let config = { url: url, data: jsonString };
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				if (a.result == 1) {
					fn[0] = 'Fn.closeModals(' + modalId + ');';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });

			}
		});
	},
	save_almacen: function () {

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('form_almacen_save')) };
		let url = OrdenServicio.url + "registrarNuevoAlmacen";
		let config = { url: url, data: jsonString };
		//console.log(config);
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				console.log("todo bien");
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				if (a.result == 1) {
					fn[0] = 'Fn.closeModals(' + modalId + ');';
				}
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });

			}
		});
	},
	addCargo: function () {
		html = '';
		html +=
			`<div class="fields">
				<div class="six wide field">
					<div class="ui sub header">Cargo</div>
					<select name="cargo" class="ui fluid dropdown semantic-dropdown" patron="requerido" onchange="$(this).closest('.fields').find('.inSueldo').val($(this).find('option:selected').data('sueldobase'))">`;
		sueldoPrimero = null;
		first = true;
		for (let i = 0; i < OrdenServicio.arrayCargo.length; i++) {
			let cargo = OrdenServicio.arrayCargo[i];
			if ($('#cboCuenta').dropdown('get value') == '' || $('#cboCuenta').dropdown('get value') == cargo.idEmpresa) {
				if (sueldoPrimero == null) sueldoPrimero = cargo.sueldo;
				textS = (first ? 'selected' : ''); first = false;
				html += `<option value="${cargo.idCargoTrabajo}" data-sueldobase="${cargo.sueldo}" ${textS}>${cargo.cargo}</option>`;
			}
		}
		html +=
			`		</select>
				</div>
				<div class="six wide field">
					<div class="ui sub header">Cantidad</div>
					<input type="text" class="ui onlyNumbers" name="cantidadCargo" placeholder="Cantidad" value="" patron="requerido">
				</div>
				<div class="three wide field">
					<div class="ui sub header">Sueldo</div>
					<input type="text" class="ui onlyNumbers inSueldo" name="sueldoCargo" placeholder="Sueldo" value="${sueldoPrimero}" patron="requerido">
				</div>
				<div class="one wide field">
					<div class="ui sub header text-white">.</div>
					<a class="ui button red" onclick="$(this).parent('.field').parent('.fields').remove();"><i class="trash icon"></i></a>
				</div>
			</div > `;

		$('#divCargo').append(html);
		Fn.loadSemanticFunctions();
		OrdenServicio.validarCheckbox();
	},
	addFechas: function () {
		let cantidad = $('#periodoFechas').val();
		let fechaHoy = new Date();
		$('#divFechas').html('');
		let html = '';
		for (let i = 0; i < cantidad; i++) {
			fechaHoy.setMonth(fechaHoy.getMonth() + 1);
			fechaValor = fechaHoy.toISOString().slice(0, 10);
			if (i == 0 || i == 8 || i == 16 || i == 24 || i == 32) {
				if (i != 0) html += `</div > `;
				html += `< div class="fields" > `
			}
			html += `
	< div class="two wide field" >
					<div class="ui sub header">Fecha ${(i + 1)}</div>
					<input type="date" class="ui" name="fechaCalc" value="${fechaValor}">
				</div>
`;
		}
		html += `</div > `;
		$('#divFechas').html(html);
	},
	actualizarOrdenServicio: function () {
		++modalId;

		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOrdenServicio')) };
		let config = { 'url': OrdenServicio.url + 'actualizarOrdenServicio', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			let btn = [];
			let fn = [];

			fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
			if (a.result == 1) {
				fn[0] = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOrdenServicio").click();';
			}
			btn[0] = { title: 'Continuar', fn: fn[0] };

			Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
		});
	},
	calcularMontoDeAlmacen: function (t) {
		let tr = $(t).closest('tr')

		let monto = tr.find('.tbAlm_monto').val();
		let frecuenciaOpcion = tr.find('.tbAlm_freOpc').dropdown('get value');
		let nro = 0;

		$.each(OrdenServicio.arrayFechas, function (k, v) {
			if (frecuenciaOpcion == '1') {
				rpta = monto;

			} else if (frecuenciaOpcion == '2') {
				nro += 0.5;
				if (nro >= 1 || k + 1 == (OrdenServicio.arrayFechas).length) {
					rpta = monto;
					nro = 0;
				} else {
					rpta = 0;
				}
			} else if (frecuenciaOpcion == '3') {
				nro += 0.34;
				if (nro >= 1 || k + 1 == (OrdenServicio.arrayFechas).length) {
					rpta = monto;
					nro = 0;
				} else {
					rpta = 0;
				}
			}
			$(tr.find('.tbAlm_MontoXFecha')[k]).val(rpta);
			$(tr.find('.tbAlm_MontoXFecha')[k]).closest('div').find('label').html(rpta);
		});

		OrdenServicio.calcularMontoTotalDeAlmacen();
	},
	calcularMontoTotalDeAlmacen: function () {
		let tot = [];
		$('#tablaAlmacenMonto tbody tr').each(function () {
			let tr = $(this);
			$.each(OrdenServicio.arrayFechas, function (k, v) {
				if (typeof tot[k] === 'undefined') tot[k] = 0;
				let m = $(tr.find('.tbAlm_MontoXFecha')[k]).val();
				tot[k] += parseFloat(m);
			});
		})

		let tfoot = $('#tablaAlmacenMonto tfoot tr');
		$.each(OrdenServicio.arrayFechas, function (k, v) {
			$(tfoot.find('.tbAlm_TotalMontoXFecha')[k]).val(tot[k]);
			$(tfoot.find('.tbAlm_TotalMontoXFecha')[k]).closest('div').find('label').html(tot[k]);
			$('#totalColumna_9_' + k).val((tot[k] * 1.348).toFixed(2));
		});
		OrdenServicio.calcularTotalFinal();

	},
	validarCheckbox: function () {
		$('.list .master.checkbox').checkbox({
			onChecked: function () {
				var $childCheckbox = $(this).closest('.checkbox').siblings('.list').find('.checkbox');
				$childCheckbox.checkbox('check');
			},
			onUnchecked: function () {
				var $childCheckbox = $(this).closest('.checkbox').siblings('.list').find('.checkbox');
				$childCheckbox.checkbox('uncheck');
			}
		});

		$('.list .child.checkbox').checkbox({
			fireOnInit: true,
			onChange: function () {
				var
					$listGroup = $(this).closest('.list'),
					$parentCheckbox = $listGroup.closest('.item').children('.checkbox'),
					$checkbox = $listGroup.find('.checkbox'),
					allChecked = true,
					allUnchecked = true;
				$checkbox.each(function () {
					if ($(this).checkbox('is checked')) {
						allUnchecked = false;
					}
					else {
						allChecked = false;
					}
				});
				if (allChecked) {
					$parentCheckbox.checkbox('set checked');
				}
				else if (allUnchecked) {
					$parentCheckbox.checkbox('set unchecked');
				}
				else {
					$parentCheckbox.checkbox('set checked');
				}
			}
		});
	},
	cantidadSplitCargo: function (t) {
		var control = $(t).closest('tr').find('td.cantidadDeTabla');
		let trChecks = $(t).closest('tr').data('nrofila');
		var divChecks = control.closest('tbody').find('tr.cantidadCargo_' + trChecks).find('.listCheck').find('.fields');
		var split = control.closest('tr').find('.splitDetalle').find('input').val();

		cantidad = 0;
		for (let i = 0; i < divChecks.length; i++) {
			var check = $(divChecks[i]).find('.ui.checkbox').checkbox('is checked');
			var cantCheck = $(divChecks[i]).closest('tr').find('.subCantDS').val();
			if (check) {
				cantidad += parseInt(cantCheck); //parseInt($('#cargoCantidad_' + i).val());
			}
		}
		valorCalc = cantidad * parseFloat(split);
		valorCalc = Math.ceil(valorCalc);
		control.find('.ui.action.input').find('input').val(valorCalc).trigger('change');
	},
	calcularSTotal: function (t) {
		var control = $(t).closest('td.cantidadDeTabla');
		var detalle = $(t).data('detalle');
		var detalleSub = $(t).data('detallesub');
		let split = control.closest('tr').find('td.splitDetalle').find('input').val();
		var precioUnitario = parseFloat(control.closest('tr').find('.precioUnitarioDetalle').find('input').val()) * parseFloat(split);
		var gapT = control.closest('tr').find('.gapDetalle').find('input').val();
		var gap = 1 + (parseFloat(gapT) / 100);

		// Inicio: Calcular valor por columna
		let nrofila = $(t).closest('tr').data('nrofila');
		let trCantCarg = $(t).closest('tbody').find('.cantidadCargo_' + nrofila);

		let valorPorColumna = [];
		trCantCarg.find('table > tbody.listCheck > tr').each(function () {
			indexCargo = $(this).find('.ui.checkbox').find('input').data('cargo');
			validarCheck = $(this).find('.ui.checkbox').checkbox('is checked');
			cantidadAsigChk = $(this).find('.subCantDS ').val();

			trHome = $('#tablaFechaPersona > tbody > tr');

			$(trHome[indexCargo]).find('input').each(function (idx) {
				if (validarCheck) {
					cnC = $(this).val();
					if (parseFloat(cnC) > parseFloat(cantidadAsigChk)) cnC = cantidadAsigChk;
				} else {
					cnC = 0;
				}

				if (typeof valorPorColumna[idx] === 'undefined') {
					valorPorColumna[idx] = parseFloat(cnC);
				} else {
					valorPorColumna[idx] += parseFloat(cnC);
				}

			});
		});
		// Fin: Calcular el valor por columna

		valorCalc = parseFloat($(t).val());
		totalFinal = (valorCalc * gap * parseFloat(precioUnitario) / split).toFixed(2);
		control.closest('tr').find('.totalCantidadSplit').find('input').val(totalFinal);
		frecuencia = control.closest('tr').find('td.frecuenciaDetalle').find('.frecuenciaID').dropdown('get value');
		totalFinalAcumulado = 0;
		let firstUnico = true;
		if (frecuencia == 5) { // UNICO
			let entregado = 0;
			let maxColV = 0;

			for (let id = 0; id < valorPorColumna.length; id++) {
				valCol = valorPorColumna[id];
				if (valCol > maxColV) maxColV = valCol;
			}

			for (let ix = 0; ix < valorPorColumna.length; ix++) {
				cM = valorPorColumna[ix];
				if (cM > maxColV) parseFloat(cM) = parseFloat(maxColV);

				rs = parseFloat(cM) - parseFloat(entregado);
				if (rs < 0) rs = 0;

				entregado += parseFloat(rs);
				if (entregado > maxColV) entregado = maxColV;

				totalFinalClm = (rs * gap * parseFloat(precioUnitario)).toFixed(2);

				totalFinalAcumulado += parseFloat(totalFinalClm);

				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + ix).val(totalFinalClm).change();
			}
		} else if (frecuencia == 6) { // FRACCIONADO
			let maxColV = 0;

			for (let id = 0; id < valorPorColumna.length; id++) {
				valCol = valorPorColumna[id];
				if (valCol > maxColV) maxColV = valCol;
			}

			for (let ix = 0; ix < valorPorColumna.length; ix++) {
				totalFinalClm = (totalFinal / valorPorColumna.length).toFixed(2);
				totalFinalAcumulado += parseFloat(totalFinalClm);
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + ix).val(totalFinalClm).change();
			}
		} else {
			for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
				if (frecuencia == 1) { // MENSUAL
					totalFinalClm = (valorPorColumna[f] * gap * parseFloat(precioUnitario)).toFixed(2);
					totalFinalAcumulado += parseFloat(totalFinalClm);
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinalClm).trigger('change');
					f = f + 0;
				} else if (frecuencia == 2) { // BIMENSUAL
					// Inicio: Calcular el valor máximo dentro del rango de tiempo.
					maxFor = 2;
					if (valorPorColumna.length - f < maxFor) maxFor = valorPorColumna.length - f;
					valMaxCol = 0;
					for (let id = 0; id < valorPorColumna.length; id++) {
						valCol = valorPorColumna[id + f];
						if (valCol > valMaxCol) valMaxCol = valCol;
					}
					// Fin: Calcular el valor máximo dentro del rango de tiempo.
					totalFinalClm = (valMaxCol * gap * parseFloat(precioUnitario)).toFixed(2);
					totalFinalAcumulado += parseFloat(totalFinalClm);
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinalClm).trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 1)).val('0.00').trigger('change');
					f = f + 1;
				} else if (frecuencia == 3) { // SEMESTRAL
					// Inicio: Calcular el valor máximo dentro del rango de tiempo.
					maxFor = 6;
					if (valorPorColumna.length - f < maxFor) maxFor = valorPorColumna.length - f;
					valMaxCol = 0;
					for (let id = 0; id < valorPorColumna.length; id++) {
						valCol = valorPorColumna[id + f];
						if (valCol > valMaxCol) valMaxCol = valCol;
					}
					// Fin: Calcular el valor máximo dentro del rango de tiempo.
					totalFinalClm = (valMaxCol * gap * parseFloat(precioUnitario)).toFixed(2);
					totalFinalAcumulado += parseFloat(totalFinalClm);
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinalClm).trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 1)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 2)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 3)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 4)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 5)).val('0.00').trigger('change');
					f = f + 5;
				} else if (frecuencia == 4) { // ANUAL
					// Inicio: Calcular el valor máximo dentro del rango de tiempo.
					maxFor = 12;
					if (valorPorColumna.length - f < maxFor) maxFor = valorPorColumna.length - f;
					valMaxCol = 0;
					for (let id = 0; id < valorPorColumna.length; id++) {
						valCol = valorPorColumna[id + f];
						if (valCol > valMaxCol) valMaxCol = valCol;
					}
					// Fin: Calcular el valor máximo dentro del rango de tiempo.
					totalFinalClm = (valMaxCol * gap * parseFloat(precioUnitario)).toFixed(2);
					totalFinalAcumulado += parseFloat(totalFinalClm);
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinalClm).trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 1)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 2)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 3)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 4)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 5)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 6)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 7)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 8)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 9)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 10)).val('0.00').trigger('change');
					$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 11)).val('0.00').trigger('change');
					f = f + 11;
				}
			}
		}
		$('#totalLineaDS_' + detalle + '_' + detalleSub).val(totalFinalAcumulado.toFixed(2)).trigger('change');

	},
	calcularTotalColumna: function (t) {
		let control = $(t).closest('table').find('tbody').find('tr');
		let detalle = $(t).data('detalle');
		for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
			let cn = 0;
			for (let i = 0; i < control.length; i++) {
				cn += parseFloat($('#montoLDS_' + detalle + '_' + i + '_' + f).val());
			}
			$('#totalColumna_' + detalle + '_' + f).val(cn.toFixed(2));
		}
		OrdenServicio.calcularTotalFinal();
	},
	calcularTotalColumnaSueldo: function (t) {
		let control = $(t).closest('table').find('tbody').find('tr');
		let detalle = $(t).data('detalle');
		for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
			let cn = 0;
			for (let i = 0; i < control.length; i++) {
				let td = $(control[i]).find('td')[f + 1];
				cn += parseFloat($(td).find('input').val())
			}
			$('#totalColumna_' + detalle + '_' + f).val(cn.toFixed(2));
		}
		OrdenServicio.calcularTotalFinal();
	},
	calcularTotalColumnaMovilidad: function () {
		$.each(OrdenServicio.arrayFechas, function (k, v) {
			viaje = parseFloat($('#movilidadViajes_' + k).val());
			adicional = parseFloat($('#movilidadAdicionales_' + k).val());
			tot = parseFloat(viaje) + parseFloat(adicional);
			$('#totalColumna_8_' + k).val(tot.toFixed(2));
		});
		OrdenServicio.calcularTotalFinal();
	},
	calcularMovilidad: function () {
		let rpta = 0;
		$('#tablaSueldoAdicional').find('tbody').find('.movilidadSueldoAdicional').each(function () {
			rpta += parseFloat($(this).val());
		});
		let total = 0;
		$.each(OrdenServicio.arrayFechas, function (k, v) {
			$('#movilidadAdicionales_' + k).val(rpta);
			total += rpta;
		})
		$('#totalMovilidadAdicional').val(total);
		OrdenServicio.calcularTotalColumnaMovilidad();
	},
	calcularTablaSueldo: function () {
		// Inicio Calcular Incentivo Adicional
		let trAd = $('#tablaSueldoAdicional > tbody > tr');
		let totalIncentivoAdicional = 0;
		trAd.each(function (i) {
			totalIncentivoAdicional += parseFloat($(this).find('.montoSueldoAdicional').val());
		});
		$('#txtIncentivoAdicionalTotal').val(totalIncentivoAdicional.toFixed(2));
		// Fin: Calcular Incentivo Adicional

		let tr = $('#tablaSueldo > tbody > tr');
		let nroPersonal = $('#tablaSueldo').data('personal');
		let montoPersonal = [];
		let montoParaBono = []; // tipo 1 y 3
		let montoIncentivo = []; // tipo 3

		for (let p = 0; p < nroPersonal; p++) {
			montoPersonal[p] = [];
			montoParaBono[p] = [];
			montoIncentivo[p] = [];
		}

		for (let r = 0; r < tr.length; r++) {
			let row = $(tr[r]).data('row');

			let tipo = $('#rowTipo_Sueldo' + row).val();

			for (let p = 0; p < nroPersonal; p++) {
				montoPersonal[p].push($('#rowMonto_Sueldo' + row + '-' + p).val());
				if (tipo == 1 || tipo == 3) {
					montoParaBono[p].push($('#rowMonto_Sueldo' + row + '-' + p).val());
				}
				if (tipo == 3) {
					montoIncentivo[p].push($('#rowMonto_Sueldo' + row + '-' + p).val());
				}
			}
		}
		totSctr = [];
		for (let i = 0; i < montoPersonal.length; i++) {
			calc = 0;
			for (let n = 0; n < montoPersonal[i].length; n++) {
				calc += parseFloat(montoPersonal[i][n]);
			}
			$('#sTotalSueldo_' + i).val(calc);

			valorSCTR = 0;
			if ($('#txtVSctr').length == 1) {
				valorSCTR = ((calc + parseFloat($('#restoSueldoMinimo').val())) * parseFloat($('#txtVSctr').val()) / 100);
				$('#txtSctr_' + i).val(valorSCTR.toFixed(4));
			}

			$('#tablaFechaPersona > tbody > tr').each(function () {
				if ($(this).find('input:first').data('personal') == i) {
					tr = $(this);

					$.each(OrdenServicio.arrayFechas, function (k, v) {
						if (typeof totSctr[k] === 'undefined') totSctr[k] = 0;
						valorCant = $(tr.find('input.cntColmFC')[k]).val();
						totSctr[k] += parseFloat(valorCant) * valorSCTR;
					})
				}
			});
		}
		tot = 0;
		let fila = $('.inputSctr:first').data('sctr');
		$.each(OrdenServicio.arrayFechas, function (k, v) {
			$('#montoLDS_7_' + fila + '_' + k).val(totSctr[k].toFixed(2));
			tot += totSctr[k];
		})
		$('#totalLineaDS_7_' + fila).val(tot.toFixed(2)).change();

		let tf = $('#tablaSueldo > tfoot > tr');
		for (let i = 0; i < montoParaBono.length; i++) {
			calc = 0;
			acumulado = 0;
			for (let n = 0; n < montoParaBono[i].length; n++) {
				calc += parseFloat(montoParaBono[i][n]);
			}

			for (let i_ = 0; i_ < tf.length; i_++) {
				let fRow = $(tf[i_]).data('row');
				let cl = $('#rowPorCL_Sueldo' + fRow).val();

				if (fRow != undefined) {
					nuevoCalc = (calc * parseFloat(cl) / 100).toFixed(4);
					$('#rowMontoBeneficio_Sueldo' + fRow + '_' + i).val(nuevoCalc);
					acumulado += parseFloat($('#rowMontoBeneficio_Sueldo' + fRow + '_' + i).val());
				}
			}
			totalTotal = (parseFloat($('#sTotalSueldo_' + i).val()) + acumulado).toFixed(4);
			$('#totalSueldo_' + i).val(totalTotal);
		}

		let totalTotalSueldo = 0;
		let totalTotalIncentivo = 0;
		let cantInc = [];
		for (let i = 0; i < montoIncentivo.length; i++) {
			calc = 0;
			for (let n = 0; n < montoIncentivo[i].length; n++) {
				calc += parseFloat(montoIncentivo[i][n]);
			}
			incentivo = (calc * (parseFloat($('#totalPorcentaje').html()) + 100) / 100).toFixed(4);
			$('#txtIncentivo_' + i).val(incentivo);

			sueldo = ($('#totalSueldo_' + i).val() - parseFloat(incentivo)).toFixed(4);
			$('#txtSueldo_' + i).val(sueldo);

			totalTotalSueldo = 0;
			totalTotalIncentivo = 0;

			for (let f = 0; f < (OrdenServicio.arrayFechas).length; f++) {
				cntSuel = $(($(($('#tablaFechaPersona > tbody').find('tr'))[i]).find('td'))[f + 1]).find('input').val();
				sueldoPorCantidad = (parseFloat(sueldo) * parseFloat(cntSuel)).toFixed(2);

				$('#txtSueldoCantidad_' + i).val(sueldoPorCantidad);

				$("#montoSueldo_" + i + "_" + f).val(sueldoPorCantidad).trigger('change');
				totalTotalSueldo += parseFloat(sueldoPorCantidad);

				if (typeof cantInc[f] === 'undefined') cantInc[f] = 0;
				cantInc[f] += (parseFloat(incentivo) * parseFloat(cntSuel));
				incentivoPorCantidad = (parseFloat(incentivo) * parseFloat(cntSuel)).toFixed(2);
				$('#txtIncentivoCantidad_' + i).val(incentivoPorCantidad);

				$('#montoIncentivo_' + f).val((parseFloat(cantInc[f]) + parseFloat(totalIncentivoAdicional)).toFixed(2));

				totalTotalIncentivo += parseFloat(cantInc[f]) + parseFloat(totalIncentivoAdicional);
			}

			$('#totalLineaSueldo_' + i).val(totalTotalSueldo.toFixed(2));
			$('#totalLineaIncentivo').val(totalTotalIncentivo.toFixed(2)).trigger('change');
		}

	},
	addElemento: function (t) {
		let control = $(t);
		let bodyTable = control.closest('table').find('tbody');
		let nroFila = control.data('nrofila');
		let tpd = $(t).closest('table').closest('tr').closest('table').find('.detalleTr_' + nroFila).find('.cboTPD').dropdown('get value');
		let detalle = $(t).data('detalle');
		Fn.showLoading(true);

		let post = $.post(
			site_url + OrdenServicio.url + 'generarRowParaPresupuesto_3', {
			'idTipoPresupuesto': detalle,
			'idTipoPresupuestoDetalle': tpd,
			'idCuenta': $('#idCuenta').val(),
			'nroFila': nroFila
		});

		post.done(function (data) {
			bodyTable.append(data);
			$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
			Fn.loadSemanticFunctions();
		}).always(function () {
			Fn.showLoading(false);
		});

	},
	calcularTotalesMovilidad: function () {
		let totAcumulado = 0;
		let html = '';
		let arT = [];
		$('#tablaMovilidad tbody tr.data').each(function (i) {
			dias = parseFloat($(this).find('.tbMov_dias').val());
			frecuencia = parseFloat($(this).find('.tbMov_fre').val());
			frecuenciaOpcion = $(this).find('.tbMov_freOpc').dropdown('get value');

			inpOrigen = $(this).find('.tbMov_origen');
			inpDestin = $(this).find('.tbMov_destino');
			inpPreBus = $(this).find('.tbMov_bus');
			inpPreHsp = $(this).find('.tbMov_hosp');
			inpPreVia = $(this).find('.tbMov_viat');
			inpPreInt = $(this).find('.tbMov_movInt');
			inpPreTxi = $(this).find('.tbMov_taxi');
			inpPreSbT = $(this).find('.tbMov_sbto');
			inpPreTot = $(this).find('.tbMov_tot');

			sbt = dias == 0 ? 0 : parseFloat(inpPreBus.val());

			cal = parseFloat(inpPreHsp.data('costobase')) * dias; sbt += cal;
			inpPreHsp.val(cal.toFixed(2));
			cal = parseFloat(inpPreVia.data('costobase')) * dias; sbt += cal;
			inpPreVia.val(cal.toFixed(2));
			cal = parseFloat(inpPreInt.data('costobase')) * dias; sbt += cal;
			inpPreInt.val(cal.toFixed(2));
			cal = parseFloat(inpPreTxi.data('costobase')) * dias; sbt += cal;
			inpPreTxi.val(cal.toFixed(2));

			inpPreSbT.val(sbt.toFixed(2));

			tot = sbt * frecuencia;
			inpPreTot.val(tot.toFixed(2));

			if (tot > 0) {
				html += `<tr>
							<td>${inpOrigen.val()} → ${inpDestin.val()}</td>`;

				nro = 0;

				console.log(OrdenServicio.arrayFechas);
				$.each(OrdenServicio.arrayFechas, function (k, v) {
					if (typeof arT[k] === 'undefined') arT[k] = 0;

					if (frecuenciaOpcion == '1') {
						rpta = tot;

					} else if (frecuenciaOpcion == '2') {
						nro += 0.5;
						if (nro >= 1 || k + 1 == (OrdenServicio.arrayFechas).length) {
							rpta = tot;
							nro = 0;
						} else {
							rpta = 0;
						}
					} else if (frecuenciaOpcion == '3') {
						nro += 0.34;
						if (nro >= 1 || k + 1 == (OrdenServicio.arrayFechas).length) {
							rpta = tot;
							nro = 0;
						} else {
							rpta = 0;
						}
					}

					arT[k] += rpta;
					html += `<td class="text-center">${rpta}</td>`;


				});
				html += `</tr>`;
			}

			totAcumulado += tot;
		});

		$('#totalTbMovilidad').val(totAcumulado.toFixed(2));
		$('#tbResumenMovilidad tbody').html(html);

		totV = 0;
		$.each(OrdenServicio.arrayFechas, function (k, v) {
			if (typeof arT[k] === 'undefined') arT[k] = 0;
			$('#movilidadViajes_' + k).val(arT[k]);
			totV += arT[k];
		});
		$('#totalMovilidadViajes').val(totV);
		OrdenServicio.calcularTotalColumnaMovilidad();
	},
	calcularTotalFinal: function () {
		totF = [];
		$('.idTP').each(function () {
			valor = $(this).val();
			$.each(OrdenServicio.arrayFechas, function (k, v) {
				tc = $('#totalColumna_' + valor + '_' + k).val();
				if (isNaN(tc)) tc = 0;
				if (typeof totF[k] === 'undefined') totF[k] = 0;
				totF[k] += parseFloat(tc);
			});
		});

		let sumSubTotal = 0;
		let sumFee1 = 0;
		let sumFee2 = 0;
		let sumFee3 = 0;
		// let toFin = [];
		let sumTotal = 0;

		$.each(totF, function (k, v) {
			$('#subtotalFinal_' + k).val(v.toFixed(2));
			sumSubTotal += v;

			tS = parseFloat($('#totalColumna_1_' + k).val());
			fee1 = parseFloat($('.fee1V').val());
			if (isNaN(fee1)) fee1 = 0;
			$('#fee1_' + k).val((tS * fee1 / 100).toFixed(2));
			sumFee1 += (tS * fee1 / 100);

			fee2 = parseFloat($('.fee2V').val());
			if (isNaN(fee2)) fee2 = 0;
			$('#fee2_' + k).val((tS * fee2 / 100).toFixed(2));
			sumFee2 += (tS * fee2 / 100);

			tF = v - tS;
			fee3 = parseFloat($('.fee3V').val());
			if (isNaN(fee3)) fee3 = 0;
			$('#fee3_' + k).val((tF * fee3 / 100).toFixed(2));
			sumFee3 += (tF * fee3 / 100);

			// if (typeof toFin[k] === 'undefined') toFin[k] = 0;
			tt = (v + (tS * fee1 / 100) + (tS * fee2 / 100) + (tF * fee3 / 100));
			$('#totalFinal_' + k).val(tt.toFixed(2));
			sumTotal += tt;
		});

		$('#sumaSubtotalFinal').val(sumSubTotal.toFixed(2));
		$('#sumaFee1Final').val(sumFee1.toFixed(2));
		$('#sumaFee2Final').val(sumFee2.toFixed(2));
		$('#sumaFee3Final').val(sumFee3.toFixed(2));
		$('#sumaTotalFinal').val(sumTotal.toFixed(2));
	},

	uptEstado_almacenDetalle: function (id, est) {

		var jsonData = { idTipoPresupuestoDetalleAlmacen: id, estado: est };
		++modalId;
		let jsonString = { 'data': JSON.stringify(jsonData) };
		let config = { 'url': OrdenServicio.url + 'uptEstado_almacenDetalle', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			}
			var html = '';
			var html2 = '<a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.uptEstado_almacenDetalle(\'' + id + '\',\'' + a.estado + '\');">';

			if (a.estado == 1) {
				html = ' <span class="badge badge-success">Activo</span>';
				html2 += '<i class="fal fa-lg fa-toggle-on"></i>';
			} else {
				html = ' <span class="badge badge-danger">Inactivo</span>';
				html2 += '<i class="fal fa-lg fa-toggle-off"></i>';
			}
			html2 += '</a>';
			console.log(html2);
			$('#est_almacen_' + id).html(html);
			$('#upt_almacen_' + id).html(html2);
		});
	},
	uptEstado_movilidad: function (id, est) {

		var jsonData = { idTipoPresupuestoDetalleMovilidad: id, estado: est };
		++modalId;
		let jsonString = { 'data': JSON.stringify(jsonData) };
		let config = { 'url': OrdenServicio.url + 'uptEstado_movilidad', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			}
			var html = '';
			var html2 = '<a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.uptEstado_movilidad(\'' + id + '\',\'' + a.estado + '\');">';

			if (a.estado == 1) {
				html = ' <span class="badge badge-success">Activo</span>';
				html2 += '<i class="fal fa-lg fa-toggle-on"></i>';
			} else {
				html = ' <span class="badge badge-danger">Inactivo</span>';
				html2 += '<i class="fal fa-lg fa-toggle-off"></i>';
			}
			html2 += '</a>';
			console.log(html2);
			$('#est_movili_' + id).html(html);
			$('#upt_mov_' + id).html(html2);
		});
	},
	save_almacenDetalle: function (id) {
		var zona = $('#up_zona_' + id).val();
		var zona2 = $('#up_zona2_' + id).val();
		var ciudad = $('#up_ciudad_' + id).val();
		var jsonData = {
			zona: zona,
			zona2: zona2,
			ciudad: ciudad,
			idTipoPresupuestoDetalleAlmacen: id
		};
		++modalId;
		let jsonString = { 'data': JSON.stringify(jsonData) };
		let config = { 'url': OrdenServicio.url + 'save_almacenDetalle', 'data': jsonString };
		// console.log(config);
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			}
		});
	},
	save_udtMovilidadDetalle: function (id) {
		var origen = $('#up_origen_' + id).val();
		var destino = $('#up_destino_' + id).val();
		//var split = $('#up_split_'+id).val();
		var split = $('select[name="up_split_' + id + '"]').val();

		//console.log(split);
		var prebus = $('#up_preBus_' + id).val();
		var prehosp = $('#up_preHosp_' + id).val();
		var previa = $('#up_preVia_' + id).val();
		var premov = $('#up_preMov_' + id).val();
		var pretaxi = $('#up_preTaxi_' + id).val();

		var jsonData = {
			origen: origen,
			destino: destino,
			split: split,
			precioBus: prebus,
			precioHospedaje: prehosp,
			precioViaticos: previa,
			precioMovilidadInterna: premov,
			precioTaxi: pretaxi,
			idTipoPresupuestoDetalleMovilidad: id
		};
		++modalId;
		let jsonString = { 'data': JSON.stringify(jsonData) };
		let config = { 'url': OrdenServicio.url + 'save_udtMovilidadDetalle', 'data': jsonString };
		console.log(config);
		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result == 1) {
				let btn = [];
				let fn = [];
				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Continuar', fn: fn[0] };

				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.msg.content, btn: btn, width: '40%' });
			}
		});
	},
}

OrdenServicio.load();
