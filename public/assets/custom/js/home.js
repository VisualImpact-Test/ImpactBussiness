var Home = {
	url: "home/",
	carteraHoy: '',
	usuariosFaltas: [],
	load: function () {
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
					// if ($("#txtcuenta").val() == 2) {
					// 	Home.generarGraficosAsistencia(),
					// 		Home.generarGraficosEfectividadGtm()
					// 	Home.generarGraficosGtm();
					// }
				})
			).then(function () {
				$('#btn-anuncios').click();
			});

			singleDatePickerModal.autoUpdateInput = false;
			$('.txt-fecha').daterangepicker(singleDatePickerModal, function (chosen_date) {
				$(this.element[0]).val(chosen_date.format('DD/MM/YYYY'));
				$('.vista-efectividad').addClass('centrarContenidoDiv');
				$('.vista-cobertura').addClass('centrarContenidoDiv');
				$('.vista-cobertura').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$('.vista-efectividad').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>');
				$.when(
					// Home.mostrar_cartera(),
					// Home.mostrar_efectividad(),
					// Home.generarGraficosEfectividadGtm(),
					// Home.generarGraficosAsistencia()
				).then(function () {
					// Home.generarGraficosGtm();
				});
			});
		});

		$(document).on('click', 'input[name=tipoEfectividadGtm]', function (e) {
			$.when(
				$('.vista-efectividadGtm').addClass('centrarContenidoDiv'),
				$('.vista-efectividadGtm').html('<i class="fas fa-spinner-third fa-spin icon-load"></i>')
			).then(function () {
				// Home.generarGraficosEfectividadGtm();
			});
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
}
Home.load();