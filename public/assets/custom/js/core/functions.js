var Fn = {

	loadPage: function (page) {
		Fn.showLoading(true, 'Redireccionando');
		setTimeout(function () { $(location).attr('href', site_url + page); }, 1000);
	},

	loadExternal: function (page) {
		Fn.showLoading(true, 'Redireccionando');
		setTimeout(function () { $(location).attr('href', page); }, 1000);
	},

	loadMessage: function (config) {
		$("#message-" + config.parent).remove();
		if (config.show) {
			var cssContent = ''
			if (typeof config.cssContent != 'undefined') {
				if (config.cssContent == 'red') cssContent = ' class="alert alert-danger" ';
				if (config.cssContent == 'green') cssContent = ' class="alert alert-success" ';
				if (config.cssContent == 'blue') cssContent = ' class="alert alert-info" ';
			}
			var msg = '<div id="message-' + config.parent + '" ' + cssContent + ' style="margin-top: 10px" >' + config.content + '</div>';
			$('.' + config.div).append(msg);
		}
	},
	mostrarNotificacion: function (cabecera, cuerpo, img = site_url + '/public/assets/images/logos/visual.png', direccion = 'https://google.com.pe', timeout = 0) {
		Push.create(cabecera, {
			body: cuerpo,
			icon: img,
			timeout: timeout,
			onClick: function () {
				location.href = direccion
			}
		});
	},
	showLoading: function (show, msg) {
		var modal = '';
		if (show) {
			modal += "<div id='modal-loading' class='modal modal-temp fade' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false'>";
			modal += "<div class='modal-dialog' style='top: 50%;transform: translateY(-50%);'>";
			modal += "<div class='modal-content' style='background-color: rgb(0 0 0 / 0%);border:0px;'>";
			modal += "<div class='modal-body'>";
			modal += "<div class='text-center' style='margin: auto;'><i class='fas fa-spinner-third fa-spin icon-load fa-8x' style='color: #ffffff;text-shadow: 0px 0px 4px #000;margin: 5px;height: 105px;'></i></div>";
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";

			$("body").append(modal);
			$("#lk-modal").attr("data-target", "#modal-loading");
			$("#lk-modal").click();

		}
		else {
			$('#modal-loading').next('.modal-backdrop').remove();
			$("#modal-loading").remove();

			$("#lk-modal").attr("data-target", "");
			Fn.modalVisible();
		}

		$(".navbar").removeAttr("style");
	},

	showModal: function (config) {
		var modal = '';
		if (typeof config.escape !== 'undefined' && config.escape) {
			var escapeKeyPressed = false;
			$(document).keydown(function (event) {
				if (event.which == 27) {
					if (escapeKeyPressed) {
						$('#modal-page-' + config.id).next('.modal-backdrop').remove();
						$('#modal-page-' + config.id).remove();
						$("#lk-modal").attr("data-target", "");
						Fn.modalVisible();
						escapeKeyPressed = false;
					} else {
						escapeKeyPressed = true;
						setTimeout(function () {
							escapeKeyPressed = false;
						}, 1000);
					}
				}
			});
		}
		if (config.show) {
			var modal_num = $("body .modal").length;
			modal += "<div id='modal-page-" + config.id + "' class='modal modal-temp fade " + (!$.isNull(config.class) ? config.class : '') + "' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false'>";
			modal += "<div class='modal-dialog " + (!$.isNull(config.large) ? 'modal-lg' : '') + "' " + (!$.isNull(config.width) ? "style='max-width: unset;width:" + config.width + "'" : '') + ">";
			modal += "<div class='modal-content " + (!$.isNull(config.class) ? 'modal-content-' + config.class : '') + "'>";
			modal += "<div class='modal-header'>";
			modal += "<h4 class='modal-title' style='margin:auto;'>" + site_name + " | " + config.title + "</h4>";
			modal += "</div>";
			modal += "<div class='modal-body'>";
			if (!$.isNull(config.content)) modal += config.content;
			else if (!$.isNull(config.frm)) modal += config.frm;
			modal += "</div>";
			modal += "<div class='modal-footer'>";
			if (!$.isNull(config.btn)) {
				for (var i = 0; i < config.btn.length; i++) {
					var $id = '';
					if (typeof (config['btn'][i]['id']) != 'undefined') {
						$id = " id='" + config['btn'][i]['id'] + "'";
					}

					var css = "btn-trade-visual";
					if (i == 0) css = "btn-outline-secondary border-0";
					if (typeof config.btn[i].class !== "undefined") css = config.btn[i].class;

					var $data = '';
					if (typeof (config['btn'][i]['data']) != 'undefined') {
						if (typeof (config['btn'][i]['data']) == 'object') {
							$.each(config['btn'][i]['data'], function (di, vi) {
								$data += " data-" + di + "='" + vi + "'";
							})
						}
					}

					var $onclick = '';
					if (typeof (config.btn[i].fn) != 'undefined') {
						$onclick = " onclick='" + config.btn[i].fn + "'";
					}

					modal += "<button type='button'" + $id + " class='btn " + css + "'" + $data + $onclick + " >" + config.btn[i].title + "</button>";
				}
			}
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";

			$("body").append(modal);

			$("#lk-modal").attr("data-target", '#modal-page-' + config.id);
			$("#lk-modal").click();
		}
		else {
			// $('#modal-page-' + config.id).modal('hide');
			$('#modal-page-' + config.id).next('.modal-backdrop').remove();
			$('#modal-page-' + config.id).remove();
			// $('.modal-backdrop.fade.in.modal-stack:last').remove();
			// $('.modal-backdrop.fade.show.modal-stack:last').remove();
			// if ($('.modal:visible').length == 0){
			// $("body").removeClass('modal-open');
			// $("body").css("padding-right", "");
			// }

			$("#lk-modal").attr("data-target", "");

			Fn.modalVisible();
		}

		$(".navbar").removeAttr("style");
	},
	diasDesdeFecha: function (fecha1, fecha2 = new Date()) {
		let fecha1utc = Date.UTC(fecha1.getFullYear(), fecha1.getMonth(), fecha1.getDate());
		let fecha2utc = Date.UTC(fecha2.getFullYear(), fecha2.getMonth(), fecha2.getDate());
		let day = 1000 * 60 * 60 * 24;
		return (fecha2utc - fecha1utc) / day;
	},
	showConfirm: function (config) {

		$.when(Fn.validateForm({ id: config.idForm })).then(function (a) {
			let fnF = '';
			if (config.fnFin) fnF = config.fnFin + ';';
			if (a === true) {
				++modalId;
				var btn = new Array();
				btn[0] = { title: 'Cerrar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + fnF };
				btn[1] = { title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + config.fn + ';' + fnF };
				Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: config.content, btn: btn });
			}
			else {
				++modalId;
				var btn = new Array();
				btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + fnF };
				var content = "<div class='alert alert-danger'>Se encontraron incidencias en la operación. <strong>Verifique el formulario.</strong></div>";
				Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: content, btn: btn });
			}
		});
	},

	showAlert: function (config) {

		$.when(Fn.validateForm({ id: config.idForm })).then(function (a) {
			let fnF = '';
			if (config.fnFin) fnF = config.fnFin + ';';
			if (a === true) {
				++modalId;
				var btn = new Array();
				var btn = new Array();
				btn[0] = { title: 'Cerrar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + fnF };
				btn[1] = { title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + config.fn + ';' + fnF };
				var content = "<div class='alert alert-warning'>" + config.content + "</strong></div>";
				Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: content, btn: btn });
			} else {
				++modalId;
				var btn = new Array();
				btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:"' + modalId + '",show:false });' + fnF };
				var content = "<div class='alert alert-danger'>Se encontraron incidencias en la operación. <strong>Verifique el formulario.</strong></div>";
				Fn.showModal({ id: modalId, show: true, title: 'Alerta', content: content, btn: btn });
			}
		});
	},

	modalVisible: function () {
		if ($('.modal:visible').length == 0) {
			$('body').removeClass('modal-open');

			if ($('body').attr('class') != undefined &&
				$('body').attr('class').length == 0
			) {
				$('body').removeAttr('class');
			}

			if ($('body').attr('style') == undefined) {
				return false;
			}

			$('body').attr('style', function (i, v) {
				$('body').removeAttr('style');

				var array = $.map(v.split(';'), function (v) { return v.replace(/ /g, ''); }).filter(function (v) { return v != null && v != ""; });
				var style = {};

				$.each(array, function (i, v) {
					var prop = v.split(':');
					if (prop[0] != 'padding-right') {
						style[prop[0]] = prop[1];
					}
				});

				if (Fn.obj_count(style) > 0) {
					$('body').css(style);
				}
			});
		}
		else {
			$('body').addClass('modal-open');
		}
	},

	download: function (url, data) {
		Fn.showLoading(true);
		$.fileDownload(url, {
			httpMethod: "POST",
			data: data,
			successCallback: function (url) {
				setTimeout(function () {
					Fn.showLoading(false);
				}, 500);
			},
			failCallback: function (responseHtml, url) {
				let result = JSON.parse(responseHtml);
				$.when(Fn.showLoading(false)).then(function () {
					var id = ++modalId;
					var btn = new Array();
					btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:' + id + ',show:false });' };
					if (result.result == 0) {
						Fn.showModal({ id: id, show: true, title: result.msg.title, content: result.msg.content, btn: btn });
					} else {
						Fn.showModal({ id: id, show: true, title: 'ERROR', content: 'Ocurrio un error inesperado en el sistema.', btn: btn });
					}
				})
			}
		});
	},

	validateForm: function (config) {
		var result = false;
		var a = $.Deferred();

		var id = config.id;

		if (id == undefined || id.length == 0) {
			a.resolve(true);
		}
		else {
			var inputs = $('#' + id).find('input, textarea, select').not(':input[type=button], :input[type=submit], :input[type=reset], input[type=checkbox]');
			$.when(Fn.formClear(id)).then(function (v) {
				if (v === true) {
					setTimeout(function () {
						$.when(
							inputs.each(function () {
								var this_ = $(this);
								var name = this_.attr('name');
								var patron = this_.attr('patron');

								if (typeof (patron) == 'string') {
									var value = this_.val();
									var type = patron.split(',');

									$.each(type, function (i, v) {
										if (v == 'requerido' || value.length > 0) {
											if (typeof (Fn.validators[v]) == 'object') {
												var validators = !Fn.validators[v]['expr'].test(value);
												
												if (validators || value == null) {
													this_.parent().addClass('has-error');
													this_.css('border-color', 'red');
													// this_.parent().append('<div class="dv-alert-error mb-1 mr-1 badge badge-danger"><small>' + Fn.validators[v]['msg'] + '</small></div>');
													return false;
												} else {
													this_.css('border-color', '#D6D8D8');
												}
											}
										}
									});
								}
							})
						).then(function () {
							var form_validado = $("#" + id).find(".has-error").length;
							if (form_validado == 0) result = true;
							else result = false;
							a.resolve(result);
						});
					}, 200);
				}
				else {
					var form_validado = $("#" + id).find(".has-error").length;
					if (form_validado == 0) result = true;
					else result = false;
					a.resolve(result);
				}
			});
		}

		return a.promise();

	},

	validateInputOneFull: function (id, input, title) {
		var a = $.Deferred();
		var msg = "";

		$.when(Fn.formClear(id)).then(function (v) {
			if (v === true) {
				setTimeout(function () {
					var total_1 = input.length;
					var n_1 = 1;

					$.each(input, function (index, value) {
						var validate = 0;
						var total_2 = value.length;
						var n_2 = 1;

						$.each(value, function (i, v) {
							if ($(v).val().length > 0) validate = 1;

							if (n_2 == total_2 && validate == 0) msg += "<p>" + title[index] + "</p>";
							else {
								$.each(value, function (ind, va) {
									$(va).parent().addClass('has-error');
								});
							}
							n_2++;
						});

						if (n_1 == total_1) a.resolve(msg);
						n_1++;
					});
				}, 200);
			}
		});

		return a.promise();
	},

	logOut: function (url) {
		//var url=$("#a-logout").attr("page");
		$.when(Fn.ajax({ url: url, data: {} })).then(function (a) {
			//if(a.result==1){
			Fn.loadPage(a.url);
			//}
		});
	},

	clave: function (modalIdOld) {
		var data = {};
		data = Fn.formSerializeObject("frm-clave");
		var jsonString = { 'data': JSON.stringify(data) };
		var url = $("#frm-clave").attr("action");
		var config = { url: url, data: jsonString };

		$.when(Fn.ajax(config)).then(function (a) {
			if (a.result != 2) {
				//var modalIdOld=modalId-1;
				if (a.result == 1) Fn.showModal({ id: modalIdOld, show: false });
				++modalId;
				var btn = new Array();
				btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:' + modalId + ',show:false });' };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn });
			}
		});
	},

	simpleAjax: function (config) {
		var a = $.Deferred();
		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			//beforeSend: function(){ Fn.showLoading(true) },
			success: function (r) {
				//Fn.showLoading( false );
				a.resolve(r);
			}
		});

		return a.promise();
	},

	ajax_arr: function (config, ix) {
		var dfd = $.Deferred();
		dfd.promise();
		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			//async:false,
			success: function (r) {
				global_masivo.push(r);
				dfd.resolve(true);
			},
			error: function () {
				dfd.resolve(false);
			},

		});
		return dfd.promise();
	},

	procesar_masivo: function (url, data, view, array) {
		var dfd = $.Deferred();
		var arr = [];
		global_masivo = [];
		Fn.showLoading(true);
		$.each(array, function (ix, value) {
			var jsonString = { 'data': JSON.stringify(data), 'grid': JSON.stringify(value), 'start': ix };
			var config = { url: url, data: jsonString };
			arr.push(Fn.ajax_arr(config, ix));
		});
		$.when.apply(this, arr).then(dfd.resolve);
		return dfd.promise();

	},

	ajax_new: function (config) {
		var a = $.Deferred();
		var result = {
			'result': 2,
			'msg': { 'title': '', 'content': '' },
			'data': '',
			'url': '',
			'tipoReporte': ''
		};

		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			beforeSend: function () { Fn.showLoading(true) },
			success: function (r) {
				$.extend(result, r);
			},
			error: function () {
				var idModal = ++modalId;
				var btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

				Fn.showModal({
					'id': idModal,
					'show': true,
					'title': 'ERROR',
					'content': 'Ocurrio un error inesperado en el sistema.',
					'btn': btn
				});
			},
			complete: function () {
				a.resolve(result);
			}
		});

		return a.promise();
	},

	ajax: function (config) {
		var a = $.Deferred();
		var result = {
			'result': 2,
			'msg': { 'title': '', 'content': '' },
			'data': '',
			'url': '',
			'tipoReporte': ''
		};

		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			beforeSend: function () { Fn.showLoading(true) },
			success: function (r) {
				$.extend(result, r);
			},
			error: function () {
				var idModal = ++modalId;
				var btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

				Fn.showModal({
					'id': idModal,
					'show': true,
					'title': 'ERROR',
					'content': 'Ocurrio un error inesperado en el sistema.',
					'btn': btn
				});
			},
			complete: function () {
				if (result.errormemoria == true) {
					let idModal = ++modalId;
					let btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

					Fn.showModal({
						'id': idModal,
						'show': true,
						'title': 'ADVERTENCIA',
						'content': 'Se está intentando cargar demasiada información. Por favor filtre los datos y vuelva a consultar.',
						'btn': btn
					});
				}
				setTimeout(
					function () {
						Fn.showLoading(false);
						a.resolve(result);
					}
					, 100);
			}
		});

		return a.promise();
	},

	ajaxFormData: function (config) {
		var a = $.Deferred();
		var result = {
			'result': 2,
			'msg': { 'title': '', 'content': '' },
			'data': '',
			'url': '',
			'tipoReporte': ''
		};

		$.ajax({
			// dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			contentType: false,
			processData: false,
			type: 'post',
			beforeSend: function () { Fn.showLoading(true) },
			success: function (r) {
				var r = JSON.parse(r)
				// Fn.showLoading(false);
				// result['result'] = r.result;
				// result['data'] = ($.isNull(r.data)) ? '' : r.data;
				// result['msg'] = ($.isNull(r.msg)) ? '' : r.msg;
				// result['url'] = ($.isNull(r.url)) ? '' : r.url;
				// a.resolve(result);
				$.extend(result, r);
			},
			error: function () {
				var idModal = ++modalId;
				var btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

				Fn.showModal({
					'id': idModal,
					'show': true,
					'title': 'ERROR',
					'content': 'Ocurrio un error inesperado en el sistema.',
					'btn': btn
				});
			},
			complete: function () {
				setTimeout(function () {
					Fn.showLoading(false);
					a.resolve(result);
				}, 100);
			}

		});

		return a.promise();
	},

	ajax2: function (config) {
		var a = $.Deferred();
		var result = [];
		result['result'] = 2;
		result['data'] = '';
		result['msg'] = '';
		result['url'] = '';

		$.ajax({
			dataType: "json",
			url: config.url,
			data: config.data,
			beforeSend: function () { Fn.showLoading(true) },
			success: function (r) {
				Fn.showLoading(false);
				if (r['result'] == 2) {
					Fn.showModal({
						'id': ++modalId,
						'show': true,
						'title': r['msg']['title'],
						'content': r['msg']['content'],
						'btn': [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + modalId + ',show:false });' }],
					});
				}

				result['result'] = r.result;
				result['data'] = ($.isNull(r.data)) ? '' : r.data;
				result['msg'] = ($.isNull(r.msg)) ? '' : r.msg;
				result['url'] = ($.isNull(r.url)) ? '' : r.url;
				result['tipoReporte'] = ($.isNull(r.tipoReporte)) ? '' : r.tipoReporte;

				a.resolve(result);
			},
			error: function (ax) {
				console.log('error en Fn.ajax2');
				// Quita el comentario a la siguiente linea para validar el error
				// console.log(ax.responseText);
				$.when(Fn.showLoading(false)).then(function () {
					var id = ++modalId;
					var btn = new Array();
					btn[0] = { title: 'Aceptar', fn: 'Fn.showModal({ id:' + id + ',show:false });' };
					Fn.showModal({ id: id, show: true, title: 'Error', content: 'Ocurrio un error inesperado en el sistema.', btn: btn, width: '300px' });
					a.resolve(result);
				})
			},
		});
		return a.promise();
	},

	formClear: function (id) {
		var a = $.Deferred();

		//if( $("#"+id).find(".has-error").removeClass("has-error") && $("#"+id).find("input, textarea, select").tooltip('destroy') ) a.resolve(true);
		if ($("#" + id).find(".dv-alert-error").remove()) a.resolve(true);
		if ($("#" + id).find(".has-error").removeClass("has-error")) a.resolve(true);
		else a.resolve(false);

		return a.promise();
	},

	formClearInput: function (id) {
		$("#" + id)[0].reset();
		if ($("#" + id + " table").length == 1) {
			$("#" + id + " table").find('input,select').each(function () {
				var this_ = $(this);
				var defaultVal = this_.data('default');
				this_.val(defaultVal);
			});
		}
	},

	formSerializeObject: function (id, skip = {}) {
		var skipDefault = { 'type': [], 'tag': [], 'class': [] };
		skip = $.extend({}, skipDefault, skip);

		var skipFind = skipDefault;
		$.each(skip, function (i, v) {
			if (v.length > 0) {
				var find = '';
				switch (i) {
					case 'type':
						find = '[type=' + v.join('], [type=') + ']';
					case 'tag':
						find = v.join(', ');
					case 'class':
						find = '.' + v.join(', .');
				};

				if ($('#' + id).find(find).length > 0) {
					skipFind[i] = $('#' + id).find(find);
					skipFind[i].prop('disabled', true);
				}
			}
		});

		var o = {};
		var a = $("#" + id).serializeArray();
		$.each(a, function () {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			}
			else {
				o[this.name] = this.value || '';
			}
		});

		$.each(skipFind, function (ii, vv) {
			$(vv).prop('disabled', false);
		});

		return o;
	},

	validators: {
		'requerido': {
			'expr': /([^\s])/,
			'msg': 'Complete'
		},
		'email': {
			'expr': /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
			'msg': ' Email invalido'
		},
		'usuario': {
			'expr': /^[A-Za-z0-9]{6,15}$/,
			'msg': ' Solo se permite letras y números. Entre 6 a 15 digitos'
		},
		'dni': {
			'expr': /^[0-9]{8}$/,
			'msg': ' DNI inválido'
		},
		'ruc': {
			'expr': /^[0-9]{11}$/,
			'msg': ' RUC inválido'
		},
		'letras': {
			'expr': /^[a-zA-Z]*$/,
			'msg': ' Ingresar solo letras'
		},
		'numeros': {
			'expr': /^-?[0-9]\d*(\.\d+)?$/,
			'msg': ' Ingresar solo números'
		},
		'enteros': {
			'expr': /^[0-9]$/,
			'msg': ' Ingresar solo enteros.'
		},
		'carnetExtranjeria' : {
			'expr': /^\d{9,12}$/,
			'msg': 'Carnet de extranjeria inválido'
		}
	},

	handleImage: function (e, callback, outputFormat) {
		var canvas = document.createElement('CANVAS');
		var ctx = canvas.getContext('2d');

		var reader = new FileReader();
		reader.onload = function (event) {
			var img = new Image();
			img.crossOrigin = 'Anonymous';
			img.onload = function () {
				canvas.width = 640;
				canvas.height = 480;
				ctx.drawImage(img, 0, 0, 640, 480);
				dataURL = canvas.toDataURL(outputFormat, 1.0)
				callback(dataURL);
				canvas = null;
			}
			img.src = event.target.result;
		}
		reader.readAsDataURL(e.target.files[0]);
	},

	handleImages: function (file, callback, outputFormat) {
		var canvas = document.createElement('CANVAS');
		var ctx = canvas.getContext('2d');
		var reader = new FileReader();
		reader.onload = function (event) {
			var img = new Image();
			img.crossOrigin = 'Anonymous';
			img.onload = function () {
				canvas.width = 640;
				canvas.height = 480;
				ctx.drawImage(img, 0, 0, 640, 480);
				dataURL = canvas.toDataURL(outputFormat, 1.0)
				callback(dataURL);
				canvas = null;
			}
			img.src = event.target.result;
		}
		reader.readAsDataURL(file);
	},

	selectpickerRefresh: function () {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) $('.selectpicker').selectpicker('mobile').selectpicker('refresh');
		else $('.selectpicker').selectpicker('refresh');
	},

	selectOption: function (name, filter = []) {
		var html = '';

		if (localStorage.getItem("vi_tv_filtros_ww7") !== null) {
			var objectLocal = JSON.parse(localStorage.getItem('vi_tv_filtros_ww7'));
			switch (name) {
				case 'zona':
					html += '<option value="" class="label label-success" >Zona (Todo)</option>';
					if (typeof (objectLocal['zona']) === 'object') {
						$.each(objectLocal['zona'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'ciudad':
					html += '<option value="" class="label label-success" >Departamento (Todo)</option>';
					if (typeof (objectLocal['ciudad'][filter[0]]) === 'object') {
						$.each(objectLocal['ciudad'][filter[0]], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'cadena':
					html += '<option value="" class="label label-success" >Cadena (Todo)</option>';
					if (typeof (objectLocal['cadena']) === 'object') {
						$.each(objectLocal['cadena'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'banner':
					html += '<option value="" class="label label-success" >Banner (Todo)</option>';
					if (typeof (objectLocal['banner'][filter[0]]) === 'object') {
						$.each(objectLocal['banner'][filter[0]], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'cluster':
					html += '<option value="" class="label label-success" >Cluster (Todo)</option>';
					if (typeof (objectLocal['cluster']) === 'object') {
						$.each(objectLocal['cluster'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'canal':
					html += '<option value="" class="label label-success" >Canal (Todo)</option>';
					if (typeof (objectLocal['canal']) === 'object') {
						$.each(objectLocal['canal'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'clienteTipo':
					html += '<option value="" class="label label-success" >Cliente Tipo (Todo)</option>';
					if (typeof (objectLocal['clienteTipo']) === 'object') {
						$.each(objectLocal['clienteTipo'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'tipoUsuario':
					html += '<option value="" class="label label-success" >Tipo Usuario (Todo)</option>';
					if (typeof (objectLocal['tipoUsuario']) === 'object') {
						$.each(objectLocal['tipoUsuario'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'distribuidora':
					html += '<option value="" class="label label-success" >Distribuidora (Todo)</option>';
					if (typeof (objectLocal['distribuidora']) === 'object') {
						$.each(objectLocal['distribuidora'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'usuarios':
					html += '<option value="" class="label label-success" >Usuario (Todos)</option>';
					if (typeof (objectLocal['usuarios'][filter[0]]) === 'object') {
						$.each(objectLocal['usuarios'][filter[0]], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'departamentos':
					//console.log(objectLocal['departamentos']);
					html += '<option value="" class="label label-success" >Departamentos (Todo)</option>';
					if (typeof (objectLocal['departamentos']) === 'object') {
						$.each(objectLocal['departamentos'], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'provincias':
					html += '<option value="" class="label label-success" >Provincias (Todo)</option>';
					if (typeof (objectLocal['provincias'][filter[0]]) === 'object') {
						$.each(objectLocal['provincias'][filter[0]], function (i, v) {
							html += '<option value=' + i + '>' + v + '</option>';
						});
					}
					break;
				case 'distritos':
					html += '<option value="" class="label label-success" >Distritos (Todo)</option>';
					if (typeof (objectLocal['distritos'][filter[0]]) !== 'undefined') {
						if (typeof (objectLocal['distritos'][filter[0]][filter[1]]) === 'object') {
							$.each(objectLocal['distritos'][filter[0]][filter[1]], function (i, v) {
								html += '<option value=' + i + '>' + v + '</option>';
							});
						}
					}
					break;
				case 'encargado':
					html += '<option value="" class="label label-success" >Encargados (Todo)</option>';
					if (typeof (objectLocal['encargados']) === 'object') {
						if (typeof (objectLocal['encargados'][filter[0]]) === 'object') {
							if (typeof (objectLocal['encargados'][filter[0]][filter[1]]) === 'object') {
								$.each(objectLocal['encargados'][filter[0]][filter[1]], function (i, v) {
									html += '<option value=' + i + '>' + v + '</option>';
								});
							}
						}
					}
					break;
			}
		}

		return html;
	},

	selectOrderOption: function (id) {
		var items = $('#' + id + ' option').get();
		items.sort(function (a, b) {
			var keyA = $(a).text();
			var keyB = $(b).text();

			if (keyA < keyB) return -1;
			if (keyA > keyB) return 1;
			return 0;
		});

		var select = $('#' + id);
		$.each(items, function (i, option) {
			select.append(option);
		});
	},

	obj_count: function (obj) {
		var count = 0;
		var i;
		for (i in obj) {
			if (obj.hasOwnProperty(i)) { count++; }
		}
		return count;
	},

	/**********FUNCIONES DE ADICIONALES*************/

	message: function (config = {}) {
		var defaults = { 'type': 0, 'message': '' };
		var config = $.extend({}, defaults, config);

		var icon = '';
		var iconSize = ' fa-2x';
		var message = '';

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
		html += '<i class="' + icon + ' mr-2 float-left"></i>';
		html += '<p class="text-left mt-1">' + message + '</p>';

		return html;
	},

	/**********FUNCIONES DE REPORTE*************/

	loadReporte: function (config = {}) {
		var data = Fn.formSerializeObject(config.idFrm);
		var jsonString = { 'data': JSON.stringify(data) };
		var configAjax = { 'url': config.url, 'data': jsonString };

		$.when(Fn.ajax(configAjax)).then(function (a) {
			if (a['status'] == null) {
				return false;
			}

			//if ( Array.isArray(a.data.view) ) {
			if (typeof a.data.views !== 'undefined') {
				$.each(a.data.views, function (id, value) {
					$('#' + id).html(value.html);
					if (typeof value.datatable !== 'undefined') {
						$('#' + value.datatable).DataTable();

					}
				});

			} else {
				$('#' + config.contentDetalle).html(a.data.html);
				if (a.result == 1) {
					if (typeof a.data.datatable !== 'undefined') {
						$('#' + a.data.datatable).DataTable();

					}

				}
			}

			Fn.showLoading(false);

		});
	},

	loadReporte_new: function (config = {}) {
		var data = Fn.formSerializeObject(config.idFrm);
		var jsonString = { 'data': JSON.stringify(data) };
		var configAjax = { 'url': config.url, 'data': jsonString };
		$.when(Fn.ajax_new(configAjax)).then(function (a) {
			if (a['status'] === null) {
				return false;
			}
			console.log(a);
			if (typeof a.data.views !== 'undefined') {
				$.each(a.data.views, function (id, value) {
					$('#' + id).html('');
					$('#' + id).html(value.html);
					if (typeof value.datatable !== 'undefined') {
						if (typeof config.responsive !== 'undefined') {
							if (typeof a.data.configTable == 'undefined') {
								a.data.configTable = {};
							}
							a.data.configTable.responsive = Fn.mobileCheck();
						}
						$('#' + value.datatable).DataTable(a.data.configTable);
					}
				});
			} else {
				$('#' + config.contentDetalle).html(a.data.html);
				if (a.result == 1) {
					if (typeof a.data.datatable !== 'undefined') {
						if (typeof config.responsive !== 'undefined') {
							if (typeof a.data.configTable == 'undefined') {
								a.data.configTable = {};
							}
							a.data.configTable.responsive = Fn.mobileCheck();
						}
						$('#' + a.data.datatable).DataTable(a.data.configTable);
					}
				}
			}

			Fn.showLoading(false);
		});
	},

	loadReporte_validado: function (config = {}) {
		var data = Fn.formSerializeObject(config.idFrm);
		var jsonString = { 'data': JSON.stringify(data) };
		var configAjax = { 'url': config.url, 'data': jsonString };

		$.when(Fn.ajax_new(configAjax)).then(function (a) {
			if (a['status'] == null) {
				return false;
			}

			if (typeof a.data.views !== 'undefined') {
				$.each(a.data.views, function (id, value) {
					$('#' + id).html('');
					$('#' + id).html(value.html);
					if (typeof value.datatable !== 'undefined') {
						$('#' + value.datatable).DataTable(a.data.configTable);
					}

					$('.filtros_gc').addClass('d-none');
					$('.filtros_gc').find('select').attr('disabled', true);

					if (typeof a.data.grupoCanal !== 'undefined') {
						$('.filtros_' + a.data.grupoCanal).removeClass('d-none');
						$('.filtros_' + a.data.grupoCanal).find('select').attr('disabled', false);
					}
				});
			} else {
				$('#' + config.contentDetalle).html(a.data.html);
				if (a.result == 1) {
					if (typeof a.data.datatable !== 'undefined') {

						$('#' + a.data.datatable).DataTable(a.data.configTable);
					}

					$('.filtros_gc').addClass('d-none');
					$('.filtros_gc').find('select').attr('disabled', true);

					if (typeof a.data.grupoCanal !== 'undefined') {
						$('.filtros_' + a.data.grupoCanal).removeClass('d-none');
						$('.filtros_' + a.data.grupoCanal).find('select').attr('disabled', false);
					}
				}

				if (a.result == 0) {
					++modalId;
					var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
					var btn = new Array();
					btn[0] = { title: 'Aceptar', fn: fn };
					Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn });
				}

			}

			Fn.showLoading(false);
		});
	},

	enviarFotos: function (arreglo) {
		var a = $.Deferred();
		if (arreglo.length > 0) {
			var jsonString = { 'arreglo': arreglo };
			// var url = 'http://movil.visualimpact.com.pe/api_impactTrade_android.php/c_control_v25/guardarFotoMultiple_v2';
			var url = site_url + 'index.php/' + 'controlFoto/enviar_fotos_api';
			var config = { 'url': url, 'data': jsonString };

			$.when(Fn.ajax2(config)).then(function (res) {
				if (res.result == 1) {
					a.resolve(res.data);
				} else {
					a.resolve(false);
				}
			});
		} else {
			a.resolve(true);
		}

		return a.promise();
	},

	enviarFotosModulos: function (arreglo) {
		var a = $.Deferred();
		var jsonString = { 'arreglo': arreglo };
		var url = 'http://movil.visualimpact.com.pe/api_impactTrade_android.php/c_control_v10/guardarFotoModulos';
		// var url = 'http://localhost/movil/api_impactTrade_android.php/c_control_v10/guardarFotoModulos';
		var config = { 'url': url, 'data': jsonString };

		$.when(Fn.ajax2(config)).then(function (res) {
			if (res.result == 1) {
				a.resolve(res.data);
			} else {
				a.resolve(false);
			}
		});

		return a.promise();
	},

	closeModals: function (cantidadDeModales = 0) {

		for (let index = 0; index < cantidadDeModales; index++) {
			$('.modal:last').modal('hide');
			$('.modal:last').remove();
			$('.modal-backdrop.fade.in.modal-stack:last').remove();
			$('.modal-backdrop.fade.show.modal-stack:last').remove();
		}

		if ($('.modal:visible').length == 0) $("body").removeClass('modal-open');
		$("body").css("padding-right", "");
		$("#lk-modal").attr("data-target", "");
	},

	replaceContent: function (id, content) {
		var node = document.getElementById(id);
		while (node.hasChildNodes()) { node.removeChild(node.firstChild); }
		$('#' + id).html(content);
	},

	rowSelected: function (idTable = '', dataIndex = '') {
		var data = [];
		var selected = {};

		if ($.fn.DataTable.isDataTable('#' + idTable)) {
			var rows = $('#' + idTable).DataTable().rows({ 'search': 'applied' }).nodes();
			selected = $('.check-row:checked', rows);
		}
		else {
			selected = $('#' + idTable).find('.check-row:checked');
		}

		$.each(selected, function (i, v) {
			if (dataIndex.length == 0) {
				data.push($(v).parents('tr').data());
			}
			else {
				data.push($(v).parents('tr').data(dataIndex));
			}
		});

		return data;
	},

	dataTableAdjust: function () {
		if ($.fn.dataTableExt) {
			if ($.fn.dataTable.tables(true).length > 0) {
				$.each($.fn.dataTable.tables(true), function (i, v) {
					$(v).DataTable().columns.adjust();
				});
			}
		}
	},

	generarCorrelativo: function ($num, $max_cifras) {
		$cifras = $max_cifras - ($num.length);
		$cadena = '';
		for ($i = 0; $i < $cifras; $i++) {
			$cadena += '0';
		}
		$cadena += $num;
		return $cadena;
	},

	replaceContentHtml: function (divId, divHtml) {
		var node = document.getElementById(divId);
		while (node.hasChildNodes()) { node.removeChild(node.firstChild); }
		$("#" + divId).html(divHtml);
	},

	fullScreen: function (elem) {
		// $('#btn-vi-expand i').toggleClass('fa-expand fa-compress-wide');
		// $('#navbarMenu').toggleClass('hide');
		$('nav').toggleClass('hide');
		// $('#content-vi-head').toggleClass('pt-4 pt-3');

		elem = elem || document.documentElement;
		if (!document.fullscreenElement && !document.mozFullScreenElement &&
			!document.webkitFullscreenElement && !document.msFullscreenElement) {
			if (elem.requestFullscreen) {
				elem.requestFullscreen();
			} else if (elem.msRequestFullscreen) {
				elem.msRequestFullscreen();
			} else if (elem.mozRequestFullScreen) {
				elem.mozRequestFullScreen();
			} else if (elem.webkitRequestFullscreen) {
				elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
			}
		} else {
			if (document.exitFullscreen) {
				document.exitFullscreen();
			} else if (document.msExitFullscreen) {
				document.msExitFullscreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitExitFullscreen) {
				document.webkitExitFullscreen();
			}
		}
	},

	exportarTablaAExcelXLSX: function (tabla, nombre, hoja) {
		// Obtener la tabla por ID
		var table = document.getElementById(tabla);
		// Convertir la tabla a una hoja de Excel
		var wb = XLSX.utils.table_to_book(table, { sheet: hoja, raw: true });
		// Pasar a Blob
		var blob = new Blob([this.s2ab(XLSX.write(wb, { bookType: 'xlsx', type: 'binary' }))], {
			type: "application/octet-stream"
		});
		// Retornar el archivo Excel para descargar con la libreria FileSaver
		return saveAs(blob, nombre + ".xlsx");
	},

	s2ab: function (s) {
		var buf = new ArrayBuffer(s.length);
		var view = new Uint8Array(buf);
		for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
		return buf;
	},

	exportarTablaAExcelXLSX_Directo: function (tabla, nombre, hoja) {
		// Obtener la tabla por ID
		var table = Fn.stringToHTML(tabla);
		// Convertir la tabla a una hoja de Excel
		var wb = XLSX.utils.table_to_book(table, { sheet: hoja, raw: true });
		// Pasar a Blob
		var blob = new Blob([this.s2ab(XLSX.write(wb, { bookType: 'xlsx', type: 'binary' }))], {
			type: "application/octet-stream"
		});
		// Retornar el archivo Excel para descargar con la libreria FileSaver
		return saveAs(blob, nombre + ".xlsx");
	},

	stringToHTML: function (str) {
		var parser = new DOMParser();
		var doc = parser.parseFromString(str, 'text/html');
		return doc.body;
	},

	ajaxNoLoad: function (config) {
		var a = $.Deferred();
		var result = {
			'result': 2,
			'msg': { 'title': '', 'content': '' },
			'data': '',
			'url': '',
			'tipoReporte': ''
		};

		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			beforeSend: function () { },
			success: function (r) {
				$.extend(result, r);
			},
			error: function () {
				var idModal = ++modalId;
				var btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

				Fn.showModal({
					'id': idModal,
					'show': true,
					'title': 'ERROR',
					'content': 'Ocurrio un error inesperado en el sistema.',
					'btn': btn
				});
			},
			complete: function () {
				setTimeout(
					function () {
						a.resolve(result);
					}
					, 100);
			}
		});

		return a.promise();
	},

	showModalOnlyBody: function (config) {
		var modal = '';
		if (config.maxwidth == null) {
			config.maxwidth = 'unset';
		}
		if (config.show) {
			var modal_num = $("body .modal").length;
			modal += "<div id='modal-page-" + config.id + "' class='modal modal-temp fade " + (!$.isNull(config.class) ? config.class : '') + "' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false' style='padding-right:0'>";
			modal += "<div class='modal-dialog " + (!$.isNull(config.large) ? 'modal-lg' : '') + "' " + (!$.isNull(config.width) ? "style='max-width: " + config.maxwidth + ";width:" + config.width + "'" : '') + ">";
			modal += "<div class='modal-content " + (!$.isNull(config.class) ? 'modal-content-' + config.class : '') + "'>";
			modal += "<div class='modal-body' style='" + (!$.isNull(config.padding) ? 'padding:' + config.padding : '') + "'>";
			if (!$.isNull(config.content)) modal += "<p>" + config.content + "</p>";
			else if (!$.isNull(config.frm)) modal += config.frm;
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";
			modal += "</div>";

			$("body").append(modal);

			$("#lk-modal").attr("data-target", '#modal-page-' + config.id);
			$("#lk-modal").click();
		}
		else {
			$('#modal-page-' + config.id).next('.modal-backdrop').remove();
			$('#modal-page-' + config.id).remove();

			$("#lk-modal").attr("data-target", "");

			Fn.modalVisible();
		}

		$(".navbar").removeAttr("style");
	},

	ajax_filtros: function (config) {
		var a = $.Deferred();
		var result = {
			'result': 2,
			'msg': { 'title': '', 'content': '' },
			'data': '',
			'url': '',
			'tipoReporte': ''
		};

		$.ajax({
			dataType: "json",
			url: site_url + 'index.php/' + config.url,
			data: config.data,
			beforeSend: function () { config.control.parents('div:first').prepend('<span class="tooltipload"><i class="fas fa-spinner-third fa-spin"></i></span>'); },
			success: function (r) {
				$.extend(result, r);
			},
			error: function () {
				var idModal = ++modalId;
				var btn = [{ 'title': 'Aceptar', 'fn': 'Fn.showModal({ id:' + idModal + ', show: false });' }];

				Fn.showModal({
					'id': idModal,
					'show': true,
					'title': 'ERROR',
					'content': 'Ocurrio un error inesperado en el sistema.',
					'btn': btn
				});
			},
			complete: function () {
				setTimeout(
					function () {
						$('.tooltipload').remove();
						a.resolve(result);
					}
					, 100);
			}
		});

		return a.promise();
	},

	randomNumber: function (min, max) {
		return Math.random() * (max - min) + min;
	},

	coloresAleatorios: function () {
		var r = Math.floor(Math.random() * 255);
		var g = Math.floor(Math.random() * 255);
		var b = Math.floor(Math.random() * 255);
		return "rgb(" + r + "," + g + "," + b + ")";
	},

	mobileCheck: function () {
		let check = false;
		(function (a) { if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true; })(navigator.userAgent || navigator.vendor || window.opera);
		return check;
	},

	obtenerHTML: function (ruta, datos = []) {
		Fn.showLoading(true);

		rpta = [];
		let post = $.post(
			site_url + 'Control/get_HTML', {
			'ruta': ruta,
			'data': datos
		});

		rpta['success'] = false;

		post.done(function (data) {
			data = jQuery.parseJSON(data);
			console.log(data);
			// bodyTable.append(data);
			// Fn.loadSemanticFunctions();
			rpta['success'] = true;
			rpta['view'] = data.msg.content;
		});

		post.always(function () {
			Fn.showLoading(false);
		});
	},

	exportarExcelDataTable: function (idWrapper) {
		let tablas = $('#' + idWrapper + '_wrapper').find('.table');
		let header = $(tablas[0]).find('thead')[0].innerHTML;
		let body = $(tablas[1]).find('tbody')[0].innerHTML;
		let table = '<table>' + header + body + '</table>';

		Fn.exportarTablaAExcelXLSX_Directo(table, Fn.camelCaseToTitleCase(idWrapper), 'HOJA');
	},

	camelCaseToTitleCase: function (text) {
		const result = text.replace(/([A-Z])/g, " $1");
		const finalResult = result.charAt(0).toUpperCase() + result.slice(1);
		return finalResult;
	},

	goToUrl: function (url) {
		window.location.replace(url);
	},

	loadSemanticFunctions: function () {
		$('.simpleDropdown').dropdown();
		$('select.semantic-dropdown').dropdown();
		$('div.semantic-dropdown').dropdown();
		$('div.boton-dropdown').dropdown({
			onChange: function (valor) {
				$(this).siblings('.date-semantic-value').val(valor).trigger('change');
			}
		});

		$('select.dropdown.parentDependiente').dropdown({
			onChange: function (noc) {
				let childDependiente = $(this).data('childdependiente');
				let idParent = $(this).val();

				if (idParent.length > 0) {
					$("#" + childDependiente).children('option').hide();
					$("#" + childDependiente).children("option[data-parentdependiente=" + idParent + "]").show();
					$('#' + childDependiente).dropdown({
						forceSelection: false
					});

					$("#" + childDependiente).val('');
					$("#" + childDependiente).siblings('div.text').text('Seleccione');
					$.each($('#' + childDependiente + ' option'), function (index, value) {
						let myoption = $('#' + childDependiente).dropdown('get item', $(value).val());
						if (myoption != false) {
							if ($(value).data('parentdependiente') == idParent) {
								myoption.removeClass('d-none');
								myoption.removeClass('active selected');
							} else {
								myoption.addClass('d-none');
								myoption.removeClass('active selected');
							}
						}
					});
				} else {
					debugger;
					$("#" + childDependiente).children('option').hide();
					$('#' + childDependiente).find('option[value=""').show();
					$("#" + childDependiente).destroyDropdown();
					$("#" + childDependiente).val('');
				}
			}
		});
		$('.date-semantic').calendar({
			minDate: new Date('2020-01-02'),
			type: 'date',
			text: {
				days: ['D', 'L', 'M', 'MM', 'J', 'V', 'S'],
				months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
				today: 'Hoy',
				now: 'Ahora',
				am: 'AM',
				pm: 'PM'
			},
			popupOptions: {
				observeChanges: false
			},
			onChange: function (date) {
				if (date != null && date != '') {
					var year = date.getFullYear();
					var month = date.getMonth() + 1;
					var day = date.getDate();
					if (month < 10) {
						month = '0' + month;
					}
					if (day < 10) {
						day = '0' + day;
					}
					$(this).siblings('.date-semantic-value').val(year + '-' + month + '-' + day).trigger('change');
					if ($(this).data('fechainicio')) $($(this).data('fechainicio')).calendar('set startDate', date);
					if ($(this).data('fechafinal')) $($(this).data('fechafinal')).calendar('set maxDate', date);
				} else {
					$(this).siblings('.date-semantic-value').val('').trigger('change');
					if ($(this).data('fechainicio')) $($(this).data('fechainicio')).calendar('set startDate', new Date('2000-01-01'));
					if ($(this).data('fechafinal')) $($(this).data('fechafinal')).calendar('set maxDate', new Date('2100-12-31'));
				}
			}
		});
		$('.ui.checkbox').checkbox();
	},

	loadDimmerHover: function () {
		$('.hover').dimmer({ on: 'hover' });
	},
	multiply: function (a, b) {
		var commonMultiplier = 1000000;

		a *= commonMultiplier;
		b *= commonMultiplier;

		return (a * b) / (commonMultiplier * commonMultiplier);
	},

	buscarParaMultiplicar: function (t, closest, multiplicar, posResultado) {
		let control = $(t);
		let div = control.closest(closest);

		total = $(t).val();
		$.each(multiplicar.split(','), function (k, v) {
			total = total * div.find(v).val();
		});

		div.find(posResultado).val(total.toFixed(2)).change();
	},

	buscarParaReemplazar: function (t, closest, data, posResultado) {
		let control = $(t);
		let div = control.closest(closest);

		if (control[0].localName == 'select') {
			$.each(data.split(','), function (k, v) {
				d = control.find('option:selected').data(v);
				div.find(posResultado.split(',')[k]).val(d).change();
			});
		}
	},
	getBase64: function (file) {
		return new Promise((resolve, reject) => {
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = () => resolve(
				{
					'base64': reader.result,
					'name': file.name,
					'type': file.type
				}
			);
			reader.onerror = error => reject(error);
		});
	}

}
