var Oc = {

	frm: 'frm-oc',
	contentDetalle: 'idContentOC',
	url: 'OrdenCompra/',
	tipo: '',
	divItemData: '',
	itemsData: [],
	modalId: 0,

	load: function () {
		$(document).on('dblclick', '.card-body > ul > li > a', function (e) {
			$('#btn-filtrarOC').click();
		});
		$(document).on('click', '.card-body > ul > li > a', function (e) {
			Oc.tipo = $(this).data('tipo');
			Oc.contentDetalle = Oc.contentDetalle + Oc.tipo;
		});

		$(document).ready(function () {
			$('.card-body > ul > li > a.active').click();
			$('#btn-filtrarOC').click();
		});

		$(document).on('click', '#btn-filtrarOC', function () {
			var ruta = 'reporte' + Oc.tipo;
			var config = {
				'idFrm': Oc.frm
				, 'url': Oc.url + ruta
				, 'contentDetalle': Oc.contentDetalle
			};

			Fn.loadReporte_new(config);
		});
		$(document).on('focusout', '.items', function () {
			let control = $(this);
			let val = control.val();
			if (val != '' && val != undefined && val != null) {
				control.attr('readonly', 'readonly');
			}
			id = control.closest('.divItem').find('.codItems').val();
			if (id == '' || id == undefined || id == null) {
				control.closest('.divItem').find('.codItems').val('0');
			}
		});
		$(document).on('click', '.btn-editar', function () {
			let id = $(this).parents('tr:first').data('id');
			let idProveedor = $(this).parents('tr:first').data('idproveedor');

			++modalId;
			let jsonString = { 'id': id, 'idproveedor': idProveedor };
			
			let config = { 'url': Oc.url + 'formularioEditarOC' + Oc.tipo, 'data': jsonString };
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Oc.agregarItem();';
				btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
				fn[2] = 'Fn.showConfirm({ idForm: "formEditarOC", fn: "Oc.editarOC()", content: "¿Esta seguro de realizar cambios en OC?" });';
				btn[2] = { title: 'Guardar', fn: fn[2] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '90%' });
				Oc.divItemData = '<div class="row itemData">' + $('#divItemData').html() + '</div>';
				$('#divItemData').html('');
				Oc.itemsData = $.parseJSON($('#itemsData').val());
				Oc.modalId = modalId;
				Oc.itemInputComplete('all');
			});

		});

		$(document).on('click', '#btn-operSinCotizar', function () {
			Oc.agregarOperDat();
		});

		$(document).on('click', '#btn-registrarOC', function () {
			++modalId;
			let jsonString = { 'data': '' };
			let config = { 'url': Oc.url + 'formularioRegistroOC' + Oc.tipo, 'data': jsonString };
			
			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Oc.agregarItem();';
				btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
				fn[2] = 'Fn.showConfirm({ idForm: "formRegistroOC", fn: "Oc.registrarOC()", content: "¿Esta seguro de registrar OC?" });';
				btn[2] = { title: 'Registrar', fn: fn[2] };
				// fn[3] = 'Oc.agregarOperDat();';
				// btn[3] = { title: 'Oper', fn: fn[3], class: 'btn-danger' };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '90%' });
				Oc.divItemData = '<div class="row itemData">' + $('#divItemData').html() + '</div>';
				Oc.itemsData = $.parseJSON($('#itemsData').val());
				Oc.modalId = modalId;
				Oc.itemInputComplete(0);
			});
		});
		$(document).on('click', '.btn-descargarOC', function () {
			let idOC = $(this).closest('tr').data('id');
			let data = { idOC };
			let jsonString = { 'data': JSON.stringify(data) };
			Fn.download(site_url + Oc.url + 'descargarOC' + Oc.tipo, jsonString);
		});

		$(document).on('change', '.tipoServicio', function () {
			let control = $(this);
			let parent = control.closest('.subItemSpace');
			let costo = control.find('option:selected').data('costo');
			let unidadMedida = control.find('option:selected').data('unidadmedida');
			let idUnidadMedida = control.find('option:selected').data('idunidadmedida');
			

			let costoForm = parent.find('.costoSubItem');
			let unidadMedidaForm = parent.find('.umSubItem');
			let idUnidadMedidaForm = parent.find('.idUmSubItem');

			costoForm.val(costo).trigger('change');
			unidadMedidaForm.val(unidadMedida);
			idUnidadMedidaForm.val(idUnidadMedida);
		});

		$(document).on('click', '.btn-removeSubItem', function () {
			let div = $(this).closest('div.subItemSpace');
			let divItem = $(this).closest('div.divItem');
			let espacio = $(this).closest('div.itemData');
			div.remove();
			let cantidadSubItems = $(divItem).find('.subItemSpace').length;
			$(espacio).find('input.cantidadSubItem').val(cantidadSubItems);


		});
		$(document).on('change', '.clearSubItem', function () {
			t = this; v = this.value;
			$(this).closest('div.divItem').find('div.subItem').html('');
			$(this).closest('div.itemData').find('input.cantidadSubItem').val('0');
			Oc.generarSubItem(t, v);
		});
		
		$(document).on('change', '#proveedor', function () {
			$("#metodoPago").empty();

			var idProveedor = $('#proveedor').val();
			
			var obj = {
				id: idProveedor
			}
			var jsonString = {
				'data': JSON.stringify(obj)
			};
			
			var config = {
				url: Oc.url + "metodoPago",
				data: jsonString
			};

			$.when(Fn.ajax(config)).then(function (a) {
				// Verifica si hay datos en a.data.metodo
				if (a.data.metodo && a.data.metodo.length > 0) {
					// Obtén la referencia al elemento select
					var selectElement = $('#metodoPago');
			
					// Limpiar opciones anteriores si es necesario
					selectElement.empty();
			
					// Itera sobre los datos y agrega opciones al select
					$.each(a.data.metodo, function (i, m) {
						// Agrega una opción al select por cada elemento en a.data.metodo
						selectElement.append($('<option>', {
							value: m.id, // Cambia 'valor' por el nombre del campo que contiene el valor deseado
							text: m.value // Cambia 'texto' por el nombre del campo que contiene el texto deseado
						}));
					});
				}
			});
		});
	},

	registrarOC: function () {
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroOC')) };
		let url = Oc.url + "registrarOC" + Oc.tipo;
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOC").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},



	editarOC: function(){
		let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formEditarOC')) };
		let url = Oc.url + "editarOC" + Oc.tipo;
		let config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (b) {
			++modalId;
			var btn = [];
			let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

			if (b.result == 1) {
				fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarOC").click();';
			}

			btn[0] = { title: 'Continuar', fn: fn };
			Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
		});
	},
	agregarItem: function (t) {
		$('.extraItem').append(Oc.divItemData);
		console.log(Oc.divItemData);
		tot = $('.items').length - 1;
		Oc.itemInputComplete(tot);
	},

	agregarOperDat: function (t) {
		++modalId;
			let jsonString = { 'data': '' };
			let config = { 'url': Oc.url + 'modalOperSinCotizar', 'data': jsonString };

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '90%' });
			
			});
	},
	
	agregarOpersinCotizar: function (t) {
		console.log(t);
			let id = t;
			++modalId;
			let jsonString = { 'data': id };
			let config = { 'url': Oc.url + 'formularioOperSinCotizarCarga', 'data': jsonString };
			console.log(config);

			$.when(Fn.ajax(config)).then((a) => {
				let btn = [];
				let fn = [];

				fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
				btn[0] = { title: 'Cerrar', fn: fn[0] };
				fn[1] = 'Oc.agregarItem();';
				btn[1] = { title: 'Agregar', fn: fn[1], class: 'btn-warning' };
				fn[2] = 'Fn.showConfirm({ idForm: "formRegistroOC", fn: "Oc.registrarOC()", content: "¿Esta seguro de registrar Oper?" });';
				btn[2] = { title: 'Guardar', fn: fn[2] };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '90%' });
				Oc.divItemData = '<div class="row itemData">' + $('#divItemData').html() + '</div>';
				$('#divItemData').html('');
				Oc.itemsData = $.parseJSON($('#itemsData').val());
				Oc.modalId = modalId;
				Oc.itemInputComplete('all');
			});
	},
	
	quitarItem: function (t, v) {
		div = t.closest('div.itemData');
		$(div).remove();
	},
	generarSubItem: function (t, v) {
		div = t.closest('div.divItem');
		espacio = t.closest('div.itemData');

		tipo = $(espacio).find('select.tipo').val();
		let htmlAdd = '';
		let iL = $('#divItemLogistica');
		let tS = $('#divTipoServicio');
		btnAd = $(t).closest('.divItem').find('.btnAdicionar');
		btnAd.hide();
		if (tipo == '2') {
			htmlAdd = `
				<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Descripción Serv.:</label>
						<input class="form-control" name="subItem_nombre" patron="requerido">
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Cantidad:</label>
						<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="Oc.cantidadServicio(this);" onkeyup="Oc.cantidadServicio(this);">
					</div>
					<div class="d-none">
						<input type="hidden" name="subItem_tipoServ" value="">
						<input type="hidden" name="subItem_idUm" value="">
						<input type="hidden" name="subItem_itemLog" value="">
						<input type="hidden" name="subItem_talla" value="">
						<input type="hidden" name="subItem_tela" value="">
						<input type="hidden" name="subItem_color" value="">
						<input type="hidden" name="subItem_costo" value="">
						<input type="hidden" name="subItem_cantidadPdv" value="">
						<input type="hidden" name="subItem_monto" value="">
						<input type="hidden" name="subItem_genero" value="">
					</div>
				</div>
			`;
			btnAd.show();
		}
		if (tipo == '7') {
			htmlAdd = `
				<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Item Logistica:</label>
						${$(iL).html()}
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Peso:</label>
						<input class="form-control cantidadSI" name="subItem_cantidad" patron="requerido"
									onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
									onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
						>
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Cantidad PDV:</label>
						<input class="form-control cantidadPDV" name="subItem_cantidadPdv" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
					</div>
					<div class="form-group col-md-6">
						<label class="font-weight-bold">Tipo Servicio:</label>
						${$(tS).html()}
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Unidad Medida:</label>
						<input class="form-control umSubItem" name="subItem_um" patron="requerido" readonly>
						<input type="hidden" class="form-control idUmSubItem" name="subItem_idUm" patron="requerido">
					</div>
					<div class="form-group col-md-3">
						<label class="font-weight-bold">Costo:</label>
						<input class="form-control costoSubItem" name="subItem_costo" patron="requerido" readonly
									onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
									onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
						>
					</div>
					<div class="d-none">
						<input type="hidden" name="subItem_nombre" value="">
						<input type="hidden" name="subItem_talla" value="">
						<input type="hidden" name="subItem_tela" value="">
						<input type="hidden" name="subItem_color" value="">
						<input type="hidden" name="subItem_monto" value="">
						<input type="hidden" name="subItem_genero" value="">
					</div>
				</div>
			`;
		}
		if (tipo == '9') {
			htmlAdd = `
				<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
					<div class="form-group col-md-1">
						<label class="font-weight-bold">Talla:</label>
						<input class="form-control" name="subItem_talla" patron="requerido">
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold">Genero:</label>
						<select class="form-control" name="subItem_genero">
							<option class="item" value="">SELECCIONE</option>
							<option class="item" value="1">VARON</option>
							<option class="item" value="2">DAMA</option>
							<option class="item" value="3">UNISEX</option>
						</select>
					</div>
					<div class=" col-md-3" style="display: flex;">
						<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
							<label class="font-weight-bold">Tela:</label>
							<input class="form-control" name="subItem_tela" patron="requerido">
						</div>
						<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
							<label class="font-weight-bold">Color:</label>
							<input class="form-control" name="subItem_color" patron="requerido">
						</div>
					</div>
					<div class=" col-md-3" style="display: flex;">
						<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
							<label class="font-weight-bold">Cantidad:</label>
							<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido"
										onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
										onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
							>
						</div>
						<div class="form-group col-md-6" style="padding-right: 3px;padding-left: 3px;">
						<label class="font-weight-bold">Costo:</label>
						<input class="form-control SbItCosto" name="subItem_costo" patron="requerido"
									 onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
									 onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
						>
						</div>
					</div>
					<div class="form-group col-md-2">
						<label class="font-weight-bold">Sb Tot:</label>
						<input class="form-control SbItSubTotal" name="subItem_st" patron="requerido" readonly onchange="Oc.calcularTextilPrecio(this);">
					</div>
					<div class="form-group col-md-1">
						<label class="font-weight-bold" style="color: white;">:</label>
						<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
					</div>
					<div class="d-none">
						<input type="hidden" name="subItem_tipoServ" value="">
						<input type="hidden" name="subItem_idUm" value="">
						<input type="hidden" name="subItem_itemLog" value="">
						<input type="hidden" name="subItem_nombre" value="">
						<input type="hidden" name="subItem_cantidadPdv" value="">
						<input type="hidden" name="subItem_monto" value="">
					</div>
				</div>
			`;
			btnAd.show();
		}
		if (tipo == '10') {
			htmlAdd = `
				<div class="form-row subItemSpace col-md-12 border-bottom pt-2">
					<div class="form-group col-md-12">
						<label class="font-weight-bold">Monto:</label>
						<input class="form-control" name="subItem_monto" patron="requerido">
					</div>
					<div class="d-none">
						<input type="hidden" name="subItem_tipoServ" value="">
						<input type="hidden" name="subItem_idUm" value="">
						<input type="hidden" name="subItem_itemLog" value="">
						<input type="hidden" name="subItem_nombre" value="">
						<input type="hidden" name="subItem_talla" value="">
						<input type="hidden" name="subItem_tela" value="">
						<input type="hidden" name="subItem_color" value="">
						<input type="hidden" name="subItem_costo" value="">
						<input type="hidden" name="subItem_cantidad" value="">
						<input type="hidden" name="subItem_cantidadPdv" value="">
						<input type="hidden" name="subItem_genero" value="">
					</div>
				</div>
			`;
			btnAd.show();
		}
		$(div).find('div.subItem').append(htmlAdd);
		let cantidadSubItems = $(div).find('.subItemSpace').length;
		$(espacio).find('input.cantidadSubItem').val(cantidadSubItems);
	},
	cantidadPorItem: function (t) {
		div = $(t).closest('.itemData').find('div.itemValor');
		cantidad = parseFloat($(div).find('input.item_cantidad').val() || '0');
		costo = parseFloat($(div).find('input.item_costo').val() || '0');
		gap = parseFloat($(div).find('input.item_GAP').val() || '0');
		cantPDV = 0;
		if ($(t).closest('.itemData').find('input.cantidadPDV').length > 0) {
			cantPDV = parseFloat($(t).closest('.itemData').find('input.cantidadPDV').val() || '0') * parseFloat($(div).find('input.item_cantidad').val() || '0');
		}
		let precio = (cantidad * costo) + (cantidad * costo * gap / 100) + cantPDV;
		$(div).find('input.item_precio').val(precio.toFixed(2));

		Oc.cantidadTotal();
	},
	cantidadTotal: function () {
		let dd = $('input.item_precio');
		let xd = $('.item_tipo');
		let total = 0;
		let totalNoFee = 0;
		for (var i = 0; i < dd.length; i++) {
			if (xd[i].value == '7') {
				totalNoFee += parseFloat(dd[i].value);
			} else {
				total += parseFloat(dd[i].value);
			}
		};
		totalTotal = total + totalNoFee;
		$('#total').val(totalTotal.toFixed(2));
		fee = 0; //parseFloat($('#fee').val()||'0');
		// $('#totalFee').val((totalNoFee + total + (total * fee / 100)).toFixed(2));
		igv = parseFloat($('#valorIGV').val()) / 100;
		totalFinal = (totalNoFee + total) * igv + (total * igv * fee / 100);
		$('#totalFinal').val(totalFinal.toFixed(2));
	},
	itemInputComplete: function (ord) {
		let tipo = 1;
		let items = [];
		let nro = 0;
		$.each(Oc.itemsData, function (index, value) {
			items[nro] = value;
			nro++;
		});

		if (ord == 'all') {
			i = 0;
			limit = $('.items').length;
		} else {
			i = ord;
			limit = ord + 1;
		}

		for (i; i < limit; i++) {
			let input = $(".items")[i];
			$(input).autocomplete({
				source: items,
				select: function (event, ui) {
					event.preventDefault();
					let control = $(this).parents(".itemData");
					//Llenamos los items con el nombre
					$(this).val(ui.item.label);
					//Llenamos una caja de texto invisible que contiene el ID del Artículo
					control.find(".codItems").val(ui.item.value);
					//Tipo Item
					control.find(".tipo").val(ui.item.tipo).trigger('change');

					$(this).focusout();
				},
				appendTo: "#modal-page-" + Oc.modalId,
				max: 5,
				minLength: 3,
			});
		}


	},
	cleanDetalle: function (parent) {
		parent.find('.codItems').val('');
	},
	editItemValue: function (t) {
		control = $(t);
		control.closest('.divItem').find('.items').attr('readonly', false);
		control.closest('.divItem').find('.codItems').val('');
	},
	calcularTextilPrecio: function (t) {
		control = t.closest('.divItem');
		total = 0;
		cantidad = 0;
		st = $(control).find('.SbItSubTotal');
		ct = $(control).find('.SbItCantidad');
		for (var i = 0; i < st.length; i++) {
			total += parseFloat($(st[i]).val() || 0);
			cantidad += parseFloat($(ct[i]).val() || 0);
		}
		$(control).closest('.itemData').find('.item_cantidad').val(cantidad);
		$(control).closest('.itemData').find('.item_costo').val(total / cantidad).trigger('change');
	},
	cantidadServicio: function (t) {
		control = t.closest('.divItem');
		cantidad = 0;
		ct = $(control).find('.SbItCantidad');
		for (var i = 0; i < ct.length; i++) {
			cantidad += parseFloat($(ct[i]).val() || 0);
		}
		$(control).closest('.itemData').find('.item_cantidad').val(cantidad).trigger('change');

	}
}

Oc.load();
