var HTCustom = {

	HTObjectsFeatures: [],
	HTObjects: [],
	HTNombres: [],
	HTHojasInvalidas: [],
	HTHooks: { 'afterChange': [], 'afterPaste': [] },
	HTHojaActiva: 0,

	HTUrlData : '../control',

	validateHojas: function () {
		HTCustom.HTHojasInvalidas = [];
		HTCustom.HTObjects.forEach(function (value, i) {
			value.validateCells((valid) => {
				if (!valid) {
					HTCustom.HTHojasInvalidas.push(HTCustom.HTNombres[i]);
				}
			})
		});
	},

	myGetDataAtCell: function (HTObjectId, row, col) {
		var valueCell = HTCustom.HTObjects[HTObjectId].getDataAtCell(row, HTCustom.HTObjects[HTObjectId].propToCol(col));
		return valueCell;
	},

	mySetDataAtCell: function (HTObjectId, row, col, value) {
		HTCustom.HTObjects[HTObjectId].setDataAtCell(row, HTCustom.HTObjects[HTObjectId].propToCol(col), value);
	},

	mySetDataAtCellSource: function (HTObjectId, row, col, value, source) {
		HTCustom.HTObjects[HTObjectId].setDataAtCell(row, HTCustom.HTObjects[HTObjectId].propToCol(col), value, source);
	},

	mySetCellSource: function (HTObjectId, row, col, newSource) {
		if (typeof newSource === "undefined") {
			HTCustom.HTObjects[HTObjectId].setCellMeta(row, col, 'source', [' ']);
		} else {
			HTCustom.HTObjects[HTObjectId].setCellMeta(row, col, 'source', newSource);
		}
	},

	load: function () {
		Handsontable.renderers.registerRenderer('myButtonRenderer', HTCustom.myButtonRenderer);
		Handsontable.renderers.registerRenderer('myDropdownRenderer', HTCustom.myDropdownRenderer);
		Handsontable.renderers.registerRenderer('myDateRenderer', HTCustom.myDateRenderer);

		Handsontable.validators.registerValidator('myUniqueValidator', HTCustom.myUniqueValidator);
		Handsontable.validators.registerValidator('myDropdownUniqueValidator', HTCustom.myDropdownUniqueValidator);

		Handsontable.cellTypes.registerCellType('myButton', {
			renderer: Handsontable.renderers.getRenderer('myButtonRenderer'),
			editor: Handsontable.editors.TextEditor,
			btnClass: 'myBtn',
			btnIcon: 'fas fa-circle',
			btnText: 'Mi botón',
			btnData: {},
		});

		Handsontable.cellTypes.registerCellType('myDate', {
			// renderer: Handsontable.renderers.DateRenderer,
			renderer: Handsontable.renderers.getRenderer('myDateRenderer'),
			editor: Handsontable.editors.DateEditor,
			validator: Handsontable.validators.DateValidator,
			dateFormat: 'DD/MM/YYYY',
			correctFormat: true,
			placeholder: moment().format('DD/MM/YYYY'),
			defaultDate: moment().toDate(),
			datePickerConfig: {
				firstDay: 1,
				showWeekNumber: true,
				numberOfMonths: 1,
				i18n: {
					previousMonth: 'Anterior',
					nextMonth: 'Siguiente',
					months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
					weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
					weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb']
				},

				// // Opcion para desactivar días
				// disableDayFn: function (date) {
				// 	return date.getDay() === 0 || date.getDay() === 6;
				// }
			}
		});

		Handsontable.cellTypes.registerCellType('myDropdown', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			//validator: Handsontable.validators.DropdownValidator,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);

				} else if (valueToValidate === '') {
					callback(false);

				} else {

					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								callback(true);
							}else{
								callback(false);
							}
						}
						
					}else{
						callback(false);
					}
					
				}
								
			}, 
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});
		Handsontable.cellTypes.registerCellType('myDropdownAlsoNew', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});

		//requiere que la siguiente columna sea los motivos
		Handsontable.cellTypes.registerCellType('myDropdown_iniciativa', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);
				} else if (valueToValidate === '') {
					callback(false);
				} else {
					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								//
								var data={};
								data['iniciativa']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_elemento_iniciativa_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;
								
								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(1,row,col+1,newSource);
								});
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								//
								var data={};
								data['iniciativa']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_elemento_iniciativa_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;

								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(1,row,col+1,newSource);
								});
								callback(true);
								
							}else{
								callback(false);
							}
						}
					}else{
						callback(false);
					}
					
				}
			}, 
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});

		//requiere que la siguiente columna sea el canal
		Handsontable.cellTypes.registerCellType('myDropdown_grupoCanal', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);
				} else if (valueToValidate === '') {
					callback(false);
				} else {
					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								//
								var data={};
								data['grupoCanal']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_canal_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;
								
								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								//
								var data={};
								data['grupoCanal']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_canal_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;

								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
								
							}else{
								callback(false);
							}
						}
					}else{
						callback(false);
					}
					
				}
			}, 
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});

		//requiere que la siguiente columna sea el cliente
		Handsontable.cellTypes.registerCellType('myDropdown_canal', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);
				} else if (valueToValidate === '') {
					callback(false);
				} else {
					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								//
								var data={};
								data['canal']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_cliente_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;
								
								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								//
								var data={};
								data['canal']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_cliente_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;

								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
								
							}else{
								callback(false);
							}
						}
					}else{
						callback(false);
					}
					
				}
			}, 
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});

		//requiere que la siguiente columna sea el banner
		Handsontable.cellTypes.registerCellType('myDropdown_cadena', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);
				} else if (valueToValidate === '') {
					callback(false);
				} else {
					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								//
								var data={};
								data['cadena']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_banner_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;
								
								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								//
								var data={};
								data['cadena']=value;
								var jsonString={'data':JSON.stringify(data)};
								var configAjax={'url':HTCustom.HTUrlData+"/get_banner_HT", 'data':jsonString};
								var col=this.col;
								var row=this.row;

								$.when(Fn.ajax2(configAjax)).then( function(a){
									var newSource = a.data;
									//HTCustom.mySetDataAtCell(0,row,col+1,"");
									HTCustom.mySetCellSource(0,row,col+1,newSource);
								});
								callback(true);
								
							}else{
								callback(false);
							}
						}
					}else{
						callback(false);
					}
					
				}
			}, 
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});

	

		Handsontable.cellTypes.registerCellType('myDropdown_cliente', {
			renderer: Handsontable.renderers.getRenderer('myDropdownRenderer'),
			editor: Handsontable.editors.DropdownEditor,
			validator: function(value, callback) {
				let valueToValidate = value;
				if (valueToValidate === null || valueToValidate === void 0) {
					valueToValidate = '';
				}
				if (this.allowEmpty && valueToValidate === '') {
					callback(true);
				} else if (valueToValidate === '') {
					callback(false);
				} else {
					if(this.source!=null){
						if( isNaN(value) ){
							if(this.source.includes(value)){
								callback(true);
							}else{
								callback(false);
							}
						}else{
							if(this.source.includes(Number(value))){
								callback(true);
							}else{
								callback(false);
							}
						}
					}else{
						callback(true);
					}
				}
			}, 
			
			source: [' '],
			dpdColumns: [],
			dpdData: [],
		});
	},

	myButtonRenderer: function (instance, td, row, col, prop, value, cellProperties) {
		var btnClass = cellProperties.btnClass;
		var btnIcon = cellProperties.btnIcon;
		var btnText = cellProperties.btnText;
		var btnData = cellProperties.btnData;
		var HTObjectId = 0;

		if (HTCustom.HTObjects.length !== 0) {
			$.each(HTCustom.HTObjects, function (i, v) {
				if (instance == v) HTObjectId = i;
			});
		}

		var boton = '<a type="submit" data-htobjectid="' + HTObjectId + '" data-row="' + row + '" data-col="' + col + '" ';

		if (Object.keys(btnData).length !== 0) {
			$.each(btnData, function (i, v) {
				boton += 'data-' + i + '="' + v + '" ';
			});
		}

		boton += 'class="btn btn-primary btn-sm btn-block rounded-0 m-0 p-0 ' + btnClass;
		boton += '"><i class="' + btnIcon + '"></i> ' + btnText + '</a>';

		td.innerHTML = boton;
		cellProperties.editor = false;
		td.className = "text-center align-middle p-0";
		return td;
	},

	myDateRenderer: function (instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.DateRenderer.apply(this, arguments);
	},

	myDropdownRenderer: function (instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.DropdownRenderer.apply(this, arguments);
	},

	myUniqueValidator: function (value, callback) {
		var data = this.instance.getDataAtCol(this.col);
		var index = data.indexOf(value);
		var uniqueValue = true;
		if (index > -1 && this.row !== index) {
			uniqueValue = false;
		}
		return callback(uniqueValue);
	},

	myDropdownUniqueValidator: function (value, callback) {

		// unique validator logic
		var data = this.instance.getDataAtCol(this.col);
		var index = data.indexOf(value);
		var uniqueValue = true;
		if (index > -1 && this.row !== index) {
			uniqueValue = false;
		}

		// dropdown validator logic
		let valueToValidate = value;

		if (valueToValidate === null || valueToValidate === void 0) {
			valueToValidate = '';
		}

		if (this.allowEmpty && valueToValidate === '') {
			callback(true);

			return;
		}

		if (this.strict && this.source) {
			if (typeof this.source === 'function') {
				this.source(valueToValidate, HTCustom.process(valueToValidate, callback, uniqueValue));
			} else {
				HTCustom.process(valueToValidate, callback, uniqueValue)(this.source);
			}
		} else {
			callback(true);
		}
	},

	process: function (value, callback, uniqueValue) {
		const originalVal = value;

		return function (source) {
			let found = false;

			for (let s = 0, slen = source.length; s < slen; s++) {
				if (originalVal === source[s]) {
					found = true; // perfect match
					break;
				}
			}
			callback(found && uniqueValue);
		};
	},

	cleanHooks: function () {
		HTCustom.HTHooks = { 'afterChange': [], 'afterPaste': [] }
	},

	llenarHTObjectsFeatures: function (ht) {

		HTCustom.HTObjects = [];
		HTCustom.HTNombres = [];
		HTCustom.HTObjectsFeatures = [];

		ht.forEach(function (value, index) {
			var features = {
				columns: value.columns,
				data: value.data,
				idDiv: 'divHT' + index,
				minSpareRows: 1,
				maxCols: Object.keys(value.data[0]).length,
				rowHeaders: true,
				colHeaders: value.headers,
				filters: false,
				dropdownMenu: false,
				allowInvalid: true,
				licenseKey: 'non-commercial-and-evaluation',
				contextMenu: ['copy', 'cut', '---------', 'row_above', 'row_below', 'remove_row', '---------', 'undo', 'alignment'],
				width: '100%',
				height: 320,
				language: 'es-MX',
				allowInvalid: true,
				manualColumnMove: false,
				manualRowResize: false,
				rowHeights: 24,
				autoRowSize: false,
				viewportRowRenderingOffset: 1,
				viewportColumnRenderingOffset: 1
			};

			if (typeof value.colWidths === 'object' || typeof value.colWidths === 'number' || typeof value.colWidths === 'string') {
				features.colWidths = value.colWidths;
			}

			HTCustom.HTObjectsFeatures[index] = features;
			HTCustom.HTNombres[index] = value.nombre;
		});
	},

	crearHTObjects: function (HTOF) {
		$.each(HTOF, function (index, value) {
			var divHT = document.getElementById(value.idDiv);
			HTCustom.cleanHTDiv(divHT);
			HTCustom.HTObjects[index] = new Handsontable(divHT, value);

			if (typeof HTCustom.HTHooks['afterChange'][index] !== 'undefined') {
				HTCustom.HTObjects[index].addHook('afterChange', HTCustom.HTHooks['afterChange'][index]);
			}

			if (typeof HTCustom.HTHooks['afterPaste'][index] !== 'undefined') {
				HTCustom.HTObjects[index].addHook('afterPaste', HTCustom.HTHooks['afterPaste'][index]);
			}

		});
	},

	cleanHTDiv: function (divHT) {
		divHT.innerHTML = "";
		divHT.removeAttribute("class");
		divHT.removeAttribute("style");
		divHT.removeAttribute("data-initialstyle");
		divHT.removeAttribute("data-originalstyle");
	}
};