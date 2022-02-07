var Home = {
	url: "home/",
	carteraHoy: '',
	usuariosFaltas: [],
	load: function () {
		$(document).on('click', '.lk-show-gps1', function () {
			var control = $(this);
			var latitud = control.data('latitud');
			var longitud = control.data('longitud');
			var latitud_cliente = control.data('latitud-cliente');
			var longitud_cliente = control.data('longitud-cliente');

			var cliente = control.data('cliente');
			var usuario = control.data('usuario');
			var perfil = control.data('perfil');

			var type = control.data('type');

			var data = { type: type, latitud: latitud, longitud: longitud, latitud_cliente: latitud_cliente, longitud_cliente: longitud_cliente, cliente: cliente, usuario: usuario, perfil: perfil };
			var jsonString = { 'data': JSON.stringify(data) };
			var configAjax = { 'url': Home.url + 'mostrarMapa', 'data': jsonString };

			$.when(Fn.ajax(configAjax)).then(function (a) {
				++modalId;
				var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
				var btn = new Array();
				btn[0] = { title: 'Cerrar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.data, btn: btn, width: "90%" });
			});
		});
		$(document).on('change', '.sl_filtros', function () {

			$('.vista-efectividad').addClass('centrarContenidoDiv');
			$('.vista-cobertura').addClass('centrarContenidoDiv');
			$('.vista-cobertura').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
			$('.vista-efectividad').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');

			$.when(
				Home.mostrar_cartera()
			).then(function () {
				Home.mostrar_efectividad();
			});

			if ($("#txtcuenta").val() == 2) {
				//Home.mostrar_efectividad();
			}
		});

		$(document).ready(function () {
			$('.main-cobertura').css('align-items', 'center');
			$('.main-efectividad').css('align-items', 'center');
			$('.main-fotos').css('align-items', 'center');

			$.when(
				$.when(
					// Home.mostrar_cartera(),
					// Home.mostrar_efectividad(),
				).then(function () {
					if ($("#txtcuenta").val() == 2) {
						Home.generarGraficosAsistencia(),
							Home.generarGraficosEfectividadGtm()
						Home.generarGraficosGtm();
					}
				})
			).then(function () {
				$('#btn-anuncios').click();
			});

			singleDatePickerModal.autoUpdateInput = false;
			$('.txt-fecha').daterangepicker(singleDatePickerModal, function (chosen_date) {
				$(this.element[0]).val(chosen_date.format('DD/MM/YYYY'));
				$('.vista-efectividad').addClass('centrarContenidoDiv');
				$('.vista-cobertura').addClass('centrarContenidoDiv');
				$('.vista-efectividadGtm').addClass('centrarContenidoDiv');
				$('.vista-asistencia').addClass('centrarContenidoDiv');
				$('.vista-cobertura').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$('.vista-efectividad').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$('.vista-totales').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$('.vista-efectividadGtm').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$('.vista-asistencia').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$.when(
					// Home.mostrar_cartera(),
					// Home.mostrar_efectividad(),
					// Home.generarGraficosEfectividadGtm(),
					// Home.generarGraficosAsistencia()
				).then(function () {
					Home.generarGraficosGtm();
				});
			});
		});

		$(document).on('click', 'input[name=tipoEfectividadGtm]', function (e) {
			$.when(
				$('.vista-efectividadGtm').addClass('centrarContenidoDiv'),
				$('.vista-efectividadGtm').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>')
			).then(function () {
				Home.generarGraficosEfectividadGtm();
			});
		})

		$(document).on('click', '.verFaltas', function (e) {
			var ad = $.Deferred();
			let usuariosFalta = Home.usuariosFaltas;
			var data = { usuariosFalta: usuariosFalta };
			var jsonString = { 'data': JSON.stringify(data) };
			var config = { 'url': Home.url + 'get_faltasAsistencia', 'data': jsonString };

			$.when(Fn.ajax(config)).then(function (a) {
				++modalId;
				var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
				var btn = new Array();
				btn[0] = { title: 'Aceptar', fn: fn };
				Fn.showModal({ id: modalId, show: true, title: 'USUARIOS CON FALTA', content: a.data.html, btn: btn, width: '30%' });
			});

			return ad.resolve(true);
		})

		if (localStorage.getItem('modalCuentaProyecto') == 0) {
			$('#a-cambiarcuenta').click();
		}
	},

	mostrar_cartera: function () {

		var ad = $.Deferred();
		var data = {
			fecha: $('.fechaHome').val(),
			grupoCanal: $('#grupo_filtro').val(),
			canal: $('#canal_filtro').val(),

			distribuidora_filtro: $("#distribuidora_filtro").val(),
			distribuidoraSucursal_filtro: $("#distribuidoraSucursal_filtro").val(),

			plaza_filtro: $("#plaza_filtro").val(),

			cadena_filtro: $("#cadena_filtro").val(),
			banner_filtro: $("#banner_filtro").val(),
		};
		var jsonString = { 'data': JSON.stringify(data) };
		var config = { 'url': Home.url + 'get_cobertura', 'data': jsonString };

		$.when(Fn.ajaxNoLoad(config)).then(function (a) {
			$('.vista-cobertura').html(a.data.html);
			$('.vista-cobertura').removeClass('centrarContenidoDiv');
			Home.carteraHoy = a.data.carteraHoy;

			ad.resolve(true);
		});

		return ad.promise();
	},

	mostrar_efectividad: function () {

		var ad = $.Deferred();
		var data = {
			fecha: $('.fechaHome').val(),
			grupoCanal: $('#grupo_filtro').val(),
			canal: $('#canal_filtro').val(),

			distribuidora_filtro: $("#distribuidora_filtro").val(),
			distribuidoraSucursal_filtro: $("#distribuidoraSucursal_filtro").val(),

			plaza_filtro: $("#plaza_filtro").val(),

			cadena_filtro: $("#cadena_filtro").val(),
			banner_filtro: $("#banner_filtro").val(),
		};
		var jsonString = { 'data': JSON.stringify(data) };
		var config = { 'url': Home.url + 'get_efectividad', 'data': jsonString };

		$.when(Fn.ajaxNoLoad(config)).then(function (a) {

			$('.vista-efectividad').html(a.data.html);
			$('.vista-efectividad').removeClass('centrarContenidoDiv');

			ad.resolve(true);

		});

		return ad.promise();

	},

	mostrar_fotos: function () {

		var ad = $.Deferred();
		var data = {};
		var jsonString = { 'data': JSON.stringify(data) };
		var config = { 'url': Home.url + 'get_fotos', 'data': jsonString };

		$.when(Fn.ajax(config)).then(function (a) {

			$('.vista-fotos').html(a.data.html);
			$('.vista-fotos').removeClass('centrarContenidoDiv');

			ad.resolve(true);

		});
		return ad.promise();
	},

	generarGraficosAsistencia: function () {
		let ad = $.Deferred();
		let data = {
			fecha: $('.fechaHome').val(),
		};
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Home.url + 'get_asistencia', 'data': jsonString };
		let dataAsistencia = [];

		$.when(Fn.ajaxNoLoad(config)).then(function (a) {
			dataAsistencia = a.data.asistencia;
			ad.resolve(true);

			$('.vista-asistencia').removeClass('centrarContenidoDiv');
			$(".vista-asistencia").html(a.data.html);

			Home.usuariosFaltas = a.data.usuariosFalta;
		});
	},

	generarGraficosGtm: function () {
		let ad = $.Deferred();
		let data = {
			fecha: $('.fechaHome').val(),
		};
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Home.url + 'get_cantidadGtm', 'data': jsonString };
		let dataCantidadGtm = [];

		$.when(Fn.ajaxNoLoad(config)).then(function (a) {
			dataCantidadGtm = a.data.cantidadGtm;
			ad.resolve(true);

			$.each($(".vista-gtm"), function (index, v) {
				if (dataCantidadGtm[0] == 0) {
					$(v).html(`<i class="fad fa-user-hard-hat fa-3x d-inline mr-2" style="color: brown;"></i><h1 class="mt-0 d-inline" style="color: #dbaaaa;">${dataCantidadGtm[0]} Usuarios</h1><hr>`);
				} else {
					$(v).html(`<i class="fad fa-store fa-3x d-inline mr-2" style="color: brown;"></i><h1 class="d-inline mt-0" style="color: #dbaaaa;">${Home.carteraHoy} Tiendas</h1>
					<hr>
					<i class="fad fa-user-hard-hat fa-3x d-inline mr-2" style="color: brown;"></i><h1 class="mt-0 d-inline" style="color: #dbaaaa;">${dataCantidadGtm[0]} GTM</h1>`);
				}
			});
		});
	},

	generarGraficosEfectividadGtm: function () {
		let ad = $.Deferred();
		let tipo = $('input[name=tipoEfectividadGtm]:checked').val();
		let data = {
			fecha: $('.fechaHome').val(),
			grupoCanal: $('#grupo_filtro').val(),
			canal: $('#canal_filtro').val(),
			tipo: tipo
		};
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': Home.url + 'get_efectividadPorGtm', 'data': jsonString };
		let dataGtm = [];
		let dataEfectividad = [];
		let coloR = [];
		let colorLabel = [];

		$.when(Fn.ajaxNoLoad(config)).then(function (a) {
			if (a.data.tipo == 0) {
				$('.vista-efectividadGtm').html(a.data.html);
				$('#tablaEfectividadGtm').DataTable(a.data.config);
				$('.vista-efectividadGtm').removeClass('centrarContenidoDiv');
			} else {
				dataGtm = a.data.dataGtm;
				dataEfectividad = a.data.dataEfectividad;

				for (let i in dataGtm) {
					coloR.push('#28A745');
					colorLabel.push('#ffffff9e');
				}

				ad.resolve(true);

				$.each($(".vista-efectividadGtm"), function (index, v) {
					$(v).html('<canvas class="divGraficoEfectividadGtm text-right align-middle" style="width:650px; height:450px;">');
				});
				$.each($(".divGraficoEfectividadGtm"), function (index, v) {
					let data = {
						labels: dataGtm,
						datasets: [
							{
								label: 'PDV',
								data: dataEfectividad,
								backgroundColor: coloR,
							}
						]
					};

					miCanvas = v.getContext("2d");

					let config = {
						type: 'bar',
						data: data,
						options: {
							responsive: false,
							indexAxis: 'y',
							elements: {
								bar: {
									borderWidth: 1,
								}
							},
							plugins: {
								tooltip: {
									callbacks: {
										label: data => ` ${data.formattedValue} %`
									}
								},
								legend: {
									display: false,
									position: 'left'
								},
								title: {
									display: false,
									text: 'PDV'
								}
							},
						},
					};
					var myChart = new Chart(miCanvas, config);
				});
			}
			ad.resolve(true);
		});
	},
}
Home.load();

var MuroMini = {
	ws: null,
	load: function () {
		if ($('.card-muro').length == 0) return false;
		if (!'WebSocket' in window) return false;



		var params = {
			idGrupo: null,
			idUsuario: $usuario['idUsuario'],
			usuario: $usuario['usuario'],
			estado: $usuario['estado'],
			device: $usuario['device']
		};

		if ($usuario['idGrupo'] != null) {
			params['idGrupo'] = $usuario['idGrupo'];
		}

		MuroMini.ws = new WebSocket('ws://190.223.68.212:5555?' + $.param(params));

		MuroMini.ws.onmessage = function (e) {
			var received = JSON.parse(e.data);

			if (received['estado'] == 'sincronizar') {
				MuroMini.publicacion();
			}
			else if (received['tabla'] == 'publicacion') {
				MuroMini.publicacionMostrar(received['data'], false);
			}
		};

		MuroMini.ws.onopen = function () {
			MuroMini.publicacion();
		};

	},
	publicacion: function () {
		var config = {
			accion: 'obtener_publicaciones',
			idGrupoCanal: $usuario['idGrupoCanal'],
			idCanal: $usuario['idCanal'],
			idProyecto: ($usuario['idProyecto'] == null) ? null : $usuario['idProyecto'],
			idCuenta: ($usuario['idCuenta'] == null) ? null : $usuario['idCuenta'],
			index_min: 0,
			index_max: 5
		};
		MuroMini.ws.send(JSON.stringify(config));
	},
	publicacionMostrar: function (data) {
		var html = '';
		data.forEach(function (v, i) {
			var fechaPub = v['fecha_p'];
			var aFechaPub = fechaPub.split('-');

			fechaPub = aFechaPub.slice().reverse().join('/');

			var hoy = new Date();
			var aFecha = [
				hoy.getFullYear(),
				hoy.getMonth() + 1,
				hoy.getDate() < 10 ? '0' + hoy.getDate() : hoy.getDate()
			];

			var fecha = aFecha.reverse().join('/');

			var hora = v['hora_p'].substring(0, 8);
			var aHora = hora.split(':');

			var d = new Date();
			d.setDate(aFechaPub[2]);
			d.setMonth(aFechaPub[1] - 1);
			d.setFullYear(aFechaPub[0]);

			d.setHours(aHora[0]);
			d.setMinutes(aHora[1]);
			d.setSeconds(aHora[2]);

			var diff = hoy.getTime() - d.getTime();
			var segundos = Math.round(diff / 1000);
			var minutos = Math.round(diff / (1000 * 60));
			var horas = Math.round(diff / (1000 * 60 * 60));
			var dias = Math.round(diff / (1000 * 60 * 60 * 24));

			var tiempo = '';
			if (fecha == fechaPub || dias < 1) {
				if (segundos < 59) tiempo = 'Hace un momento';
				else if (minutos < 59) tiempo = minutos + ' min';
				else tiempo = horas + ' h';
			}
			else if (dias < 16) tiempo = dias + ' d';
			else tiempo = fechaPub;

			html += '<li class="list-group-item">';
			html += '<div class="row">';
			html += '<div id="content-public-' + v['idPublicacion'] + '" class="col-12 text-truncate">';

			var txtContent = '';
			if (v['titulo']) txtContent = v['titulo'];
			else if (v['comentario']) txtContent = v['comentario'];

			var txtFoto = '';
			if (v['fotos']) {
				txtFoto = '<small class="font-weight-bold">' + v['fotos'] + ' ' + (v['fotos'] > 1 ? 'fotos' : 'foto') + '</small>';
			}

			if (txtContent.length == 0 && txtFoto.length > 0) html += txtFoto;
			else if (txtContent.length > 0 && txtFoto.length == 0) html += txtContent;
			else html += txtFoto + ' - ' + txtContent;

			html += '</div>';
			html += '</div>';
			html += '<small class="text-muted">';
			html += '<span><i class="far fa-user fa-lg"></i> ' + v['nombre_usuario'] + '</span>';
			html += '<span class="float-right">' + tiempo + '<span>';
			html += '</small>';
			html += '</li>';

			$('#list-muro').html(html).show('slow');
		});

	}
};
MuroMini.load();