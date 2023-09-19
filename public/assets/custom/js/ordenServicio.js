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
					valorCalc = cantidad * parseFloat(split);
					valorCalc = Math.ceil(valorCalc);
					$(td[i]).find('.ui.action.input').find('input').val(valorCalc).trigger('change');
				}
				$('.tabTiposPresupuestos').removeClass('disabled');
			} else {
				$('.tabTiposPresupuestos').addClass('disabled');
			}
		})
		$(document).on('click', '.btn-editar', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id, 'formularioValidar': false };

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
		$(document).on('change', '.cboTPD', function () {
			let control = $(this);
			let precio = control.find('option:selected').data('preciounitario') || 0;
			let split = control.find('option:selected').data('split') || 1;
			let frecuencia = control.find('option:selected').data('frecuencia');

			txtPrecio = control.closest('tr').find('td.precioUnitarioDetalle').find('input');
			txtSplit = control.closest('tr').find('td.splitDetalle').find('input');
			cboFrecuencia = control.closest('tr').find('td.frecuenciaDetalle').find('select');

			$(txtPrecio).val(precio).trigger('change');
			$(txtSplit).val(split).trigger('change');
			$(cboFrecuencia).val(frecuencia).trigger('change');
		})
		$(document).on('click', '.btnPresupuesto', function () {
			++modalId;

			let id = $(this).parents('tr:first').data('id');
			let data = { 'idOrdenServicio': id, 'formularioValidar': false };

			let jsonString = { 'data': JSON.stringify(data) };
			let config = { 'url': OrdenServicio.url + 'formularioRegistroPresupuesto', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Fn.showConfirm({ idForm: "formRegistroPresupuesto", fn: "OrdenServicio.registrarPresupuesto()", content: "¿Esta seguro de registrar el resupuesto?" });';
				btn[1] = { title: 'Registrar', fn: fn[1] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '95%' });

				OrdenServicio.arrayFechas = a.data.fechas;
				OrdenServicio.arrayTipoPresupuestoDetalle = a.data.tipoPresupuestoDetalle;
				OrdenServicio.arrayCargo = a.data.cargo;
				$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
				$('.menu .item').tab();
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
			let data = { 'idPresupuesto': id, 'formularioValidar': false };

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
				$('.menu .item').tab();
				Fn.loadSemanticFunctions();

				td = $('td.cantidadDeTabla');
				for (let i = 0; i < td.length; i++) {
					$(td[i]).find('.ui.action.input').find('input').trigger('change');
				}
				$('.tabTiposPresupuestos').removeClass('disabled');
				$("#calculateTablaSueldo").click();
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
		$(document).on('click', '#btnCrearTabla', function () {
			let jsonString = { 'nroFecha': $('#nroFecha').val(), 'nroPersona': $('#nroPersona').val() };
			let config = { 'url': OrdenServicio.url + 'formTablaParaLlenado', 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				$('#divTabla').html(a.data.html);
				$('#divSueldo').html(a.data.htmlSueldo);
				$('.menu .item').tab();
				Fn.loadSemanticFunctions();
				OrdenServicio.sueldoConteo = 0;
				OrdenServicio.arrayPersona = a.data.personal;
			});
		})
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
		$(document).on('click', '#btnBeneficio', function () {
			var rowCount = $('#tablaFechaPersona >tbody >tr').length;
			OrdenServicio.sueldoConteo++;
			let html = `
			<tr>
				<td><input class="form-control tipoSueldo" value="4"></td>
				<td>
					<select class="ui search dropdown semantic-dropdown cboBeneficio">
						<option value="">-</option>
						<option value="1" data-porcentaje="9">EsSalud</option>
						<option value="2" data-porcentaje="9.7">CTS</option>
						<option value="3" data-porcentaje="9.1">Vacaciones</option>
						<option value="4" data-porcentaje="18.2">Gratificación</option>
						<option value="5" data-porcentaje="0.26">Seguro vida ley</option>
					</select>
				</td>
				<td><input class="form-control porcentajeSueldo" value=""></td>
				`;

			for (let i = 0; i < rowCount; i++) {
				html += '<td><input class="form-control dSueldo" data-persona="' + i + '" data-sueldo="' + OrdenServicio.sueldoConteo + '" value="0"></td>';
			}
			html += '</tr>';
			$('#bodyBeneficio').append(html);
			Fn.loadSemanticFunctions();
		})
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
		let contador = $("#tb_LD" + detalle + "> tbody > tr").length;
		let html = '';
		html += `
		<tr>
			<td>
				<select class="ui fluid search dropdown dropdownSingleAditions cboTPD keyUpChange" name="tipoPresupuestoDetalleSub[${detalle}]" onchange="$('#textDescripcionDetalle_${detalle}_${contador}').html(this.options[this.selectedIndex].text);">
					<option value=""></option>`;
		for (let i = 0; i < OrdenServicio.arrayTipoPresupuestoDetalle[detalle].length; i++) {
			let tpd = OrdenServicio.arrayTipoPresupuestoDetalle[detalle][i];
			html += `<option value="${tpd.idTipoPresupuestoDetalle}" data-preciounitario="${tpd.costo}" data-split="${tpd.split}" data-frecuencia="${tpd.frecuencia}">${tpd.nombre}</option>`;

		}
		let totalCargo = 0;
		for (let i = 0; i < OrdenServicio.arrayCargo.length; i++) {
			let lCx = OrdenServicio.arrayCargo[i];
			totalCargo += parseInt(lCx.cantidad);
		}
		html += `
				</select>
			</td>
			<td class="splitDetalle">
				<div class="ui input" style="width: 80px;">
					<input type="text" class="onlyNumbers keyUpChange" name="splitDS[${detalle}]" value="1" onchange="OrdenServicio.cantidadSplitCargo(this);">
				</div>
			</td>
			<td class="precioUnitarioDetalle">
				<div class="ui input" style="width: 80px;">
					<input type="text" class="text-right keyUpChange" name="precioUnitarioDS[${detalle}]" value="0"  onchange="OrdenServicio.cantidadSplitCargo(this);">
				</div>
			</td>
			<td class="gapDetalle">
				<div class="ui input" style="width: 80px;">
					<input type="text" class="text-right keyUpChange" name="gapDS[${detalle}]" value="15" onchange="OrdenServicio.cantidadSplitCargo(this);">
				</div>
			</td>
			<td class="cantidadDeTabla">
				<div class="ui action input" style="width: 80px;">
					<input type="text" value="${totalCargo}" readonly name="cantidadDS[${detalle}]" onchange="OrdenServicio.calcularSTotal(this);" data-detallesub="${contador}" data-detalle="${detalle}">
					<a class="ui button" onclick="$(this).closest('td.cantidadDeTabla').find('div.listCheck').toggleClass('d-none'); $(this).find('i').toggleClass('slash');"><i class="icon user slash"></i></a>
				</div>
				<div class="listCheck mt-3 d-none">`

		for (let i = 0; i < OrdenServicio.arrayCargo.length; i++) {
			let lC = OrdenServicio.arrayCargo[i];
			html += `
					<div class="fields">
						<div class="ui checkbox">
							<input type="checkbox" name="chkDS[${lC.idCargo}][${detalle}][${contador}]" data-cargo="${i}" checked onchange="OrdenServicio.cantidadSplitCargo(this);">
							<label style="font-size: 1.5em;">${lC.cargo}</label>
						</div>
					</div>
			`;
		}
		html += `
				</div>
			</td >
			<td>
				<div class="ui input transparent totalCantidadSplit" style="width: 80px;">
					<input type="text" class="text-right" value="0" readonly name="montoDS[${detalle}]">
				</div>
			</td>
			<td class="frecuenciaDetalle">
				<select class="ui fluid search dropdown toast semantic-dropdown frecuenciaID" onchange="OrdenServicio.cantidadSplitCargo(this);" name="frecuenciaDS[${detalle}]" patron="requerido">
					<option value="">Frecuencia</option>
					<option value="1">MENSUAL</option>
					<option value="2">BIMENSUAL</option>
					<option value="3">SEMESTRAL</option>
					<option value="4">ANUAL</option>
					<option value="5">UNICO</option>
				</select>
			</td>
		</tr > `;
		$('#tabla' + detalle).find('tbody').append(html);

		let nhtml = '';
		nhtml += `
		<tr>
			<td id="textDescripcionDetalle_${detalle}_${contador}"></td>`;
		for (let i = 0; i < OrdenServicio.arrayFechas.length; i++) {
			let lF = OrdenServicio.arrayFechas[i];
			nhtml += `
			<td>
				<div class="ui input transparent" style="width: 80px;">
					<input class="text-right" type="text" value="0" readonly id="montoLDS_${detalle}_${contador}_${i}">
				</div>
			</td>
			`;
		}
		nhtml += `
			<td>
				<div class="ui input transparent" style="width: 80px;">
					<input class="text-right keyUpChange" type="text" value="0" readonly id="totalLineaDS_${detalle}_${contador}" data-detalle="${detalle}" onchange="OrdenServicio.calcularTotalColumna(this);">
				</div>
			</td>
		</tr>`;

		$('#tb_LD' + detalle).find('tbody').append(nhtml);

		$('.dropdownSingleAditions').dropdown({ allowAdditions: true });
		Fn.loadSemanticFunctions();
	},
	addDocumento: function (tipo = 1) {
		Fn.showLoading(true);
		documentoGenerado = tipo == 1 ? 0 : 1;
		console.log(documentoGenerado);
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
		var divChecks = control.find('div.listCheck').find('.fields');
		var split = control.closest('tr').find('.splitDetalle').find('input').val();
		// var precioUnitario = control.closest('tr').find('.precioUnitarioDetalle').html();

		cantidad = 0;
		for (let i = 0; i < divChecks.length; i++) {
			var check = $(divChecks[i]).find('.ui.checkbox').checkbox('is checked');
			if (check) {
				cantidad += parseInt($('#cargoCantidad_' + i).val());
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
		var precioUnitario = control.closest('tr').find('.precioUnitarioDetalle').find('input').val();
		var gapT = control.closest('tr').find('.gapDetalle').find('input').val();
		var gap = 1 + (parseFloat(gapT) / 100);
		valorCalc = parseFloat($(t).val());
		totalFinal = (valorCalc * gap * parseFloat(precioUnitario)).toFixed(2);
		control.closest('tr').find('.totalCantidadSplit').find('input').val(totalFinal);
		frecuencia = control.closest('tr').find('td.frecuenciaDetalle').find('.frecuenciaID').dropdown('get value');
		totalFinalAcumulado = 0;
		for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
			totalFinalAcumulado += parseFloat(totalFinal);
			if (frecuencia == 1) { // MENSUAL
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinal).trigger('change');
				f = f + 0;
			} else if (frecuencia == 2) { // BIMENSUAL
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinal).trigger('change');
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 1)).val('0.00').trigger('change');
				f = f + 1;
			} else if (frecuencia == 3) { // SEMESTRAL
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinal).trigger('change');
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 1)).val('0.00').trigger('change');
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 2)).val('0.00').trigger('change');
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 3)).val('0.00').trigger('change');
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + (f + 4)).val('0.00').trigger('change');
				f = f + 5;
			} else if (frecuencia == 4) { // ANUAL
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + f).val(totalFinal).trigger('change');
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
				f = f + 11;
			} else if (frecuencia == 5) { // UNICO
				$('#montoLDS_' + detalle + '_' + detalleSub + '_' + 0).val(totalFinal).trigger('change');
				f = 9999999;
			}
		}
		// totalFinalAcumulado
		$('#totalLineaDS_' + detalle + '_' + detalleSub).val(totalFinalAcumulado.toFixed(2)).trigger('change');

	},
	calcularTotalColumna: function (t) {
		let control = $(t).closest('table').find('tbody').find('tr');
		// let columna = $(t).data('columna');
		let detalle = $(t).data('detalle');
		for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
			let cn = 0;
			for (let i = 0; i < control.length; i++) {
				cn += parseFloat($('#montoLDS_' + detalle + '_' + i + '_' + f).val());
			}
			$('#totalColumna_' + detalle + '_' + f).val(cn.toFixed(2));
		}
	},
	calcularTotalColumnaSueldo: function (t) {
		let control = $(t).closest('table').find('tbody').find('tr');
		// let columna = $(t).data('columna');
		let detalle = $(t).data('detalle');
		for (let f = 0; f < OrdenServicio.arrayFechas.length; f++) {
			let cn = 0;
			for (let i = 0; i < control.length; i++) {
				let td = $(control[i]).find('td')[f + 1];
				cn += parseFloat($(td).find('input').val())
			}
			$('#totalColumna_' + detalle + '_' + f).val(cn.toFixed(2));
		}
	},
	calcularTablaSueldo: function () {
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
			// let cl = $('#rowPorCL_Sueldo' + row).val();

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
		for (let i = 0; i < montoPersonal.length; i++) {
			calc = 0;
			for (let n = 0; n < montoPersonal[i].length; n++) {
				calc += parseFloat(montoPersonal[i][n]);
			}
			$('#sTotalSueldo_' + i).val(calc);
		}


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
					nuevoCalc = (calc * parseFloat(cl) / 100).toFixed(2);
					$('#rowMontoBeneficio_Sueldo' + fRow + '_' + i).val(nuevoCalc);
					acumulado += parseFloat($('#rowMontoBeneficio_Sueldo' + fRow + '_' + i).val());
				}
			}
			totalTotal = (parseFloat($('#sTotalSueldo_' + i).val()) + acumulado).toFixed(2);
			$('#totalSueldo_' + i).val(totalTotal);
		}

		let totalIncentivo = 0;
		let totalTotalSueldo = 0;
		let totalTotalIncentivo = 0;
		for (let i = 0; i < montoIncentivo.length; i++) {
			calc = 0;
			for (let n = 0; n < montoIncentivo[i].length; n++) {
				calc += parseFloat(montoIncentivo[i][n]);
			}
			incentivo = (calc * (parseFloat($('#totalPorcentaje').html()) + 100) / 100).toFixed(2);
			$('#txtIncentivo_' + i).val(incentivo);

			sueldo = ($('#totalSueldo_' + i).val() - parseFloat(incentivo)).toFixed(2);
			$('#txtSueldo_' + i).val(sueldo);

			incentivoPorCantidad = (parseFloat(incentivo) * parseFloat($('#cantidadIncentivo_' + i).html())).toFixed(2);
			$('#txtIncentivoCantidad_' + i).val(incentivoPorCantidad);
			totalIncentivo += parseFloat(incentivoPorCantidad);

			sueldoPorCantidad = (parseFloat(sueldo) * parseFloat($('#cantidadSueldo_' + i).html())).toFixed(2);
			$('#txtSueldoCantidad_' + i).val(sueldoPorCantidad);
			totalTotalSueldo = 0;
			totalTotalIncentivo = 0;
			for (let f = 0; f < (OrdenServicio.arrayFechas).length; f++) {
				$("#montoSueldo_" + i + "_" + f).val(sueldoPorCantidad).trigger('change');
				$('#montoIncentivo_' + f).val(totalIncentivo.toFixed(2)).trigger('change');
				totalTotalSueldo += parseFloat(sueldoPorCantidad);
				totalTotalIncentivo += parseFloat(totalIncentivo);
			}
			$('#totalLineaSueldo_' + i).val(totalTotalSueldo.toFixed(2));
			$('#totalLineaIncentivo').val(totalTotalIncentivo.toFixed(2)).trigger('change');
		}

	}
}

OrdenServicio.load();
