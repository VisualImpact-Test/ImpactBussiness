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
		$(document).on('click', '.ver-lista', function (e) {
			let table = $("#dv-lista-solicitudes > table");
			let estado = $(this).data("estado");

			table.find("tbody tr").addClass("d-none");
			$.each(table.find("tbody tr"),function(i,x){

				if($(this).data("estado") == estado){
					$(this).removeClass("d-none");
				}
			});
		})
		
		$(document).on('click', '.ver-lista-pasados', function (e) {
			let table = $("#dv-lista-solicitudes > table");
			let estado = $(this).data("estado");

			table.find("tbody tr").addClass("d-none");
			$.each(table.find("tbody tr"),function(i,x){

				if($(this).data("actual") == 1){
					$(this).removeClass("d-none");
				}
			});
		})
		
		$(document).on('click', '.ver-lista-actuales', function (e) {
			let table = $("#dv-lista-solicitudes > table");
			let estado = $(this).data("estado");

			table.find("tbody tr").addClass("d-none");
			$.each(table.find("tbody tr"),function(i,x){

				if($(this).data("actual") == 0){
					$(this).removeClass("d-none");
				}
			});
		})
		
		$(document).on('click', '.ver-lista-todo', function (e) {
			let table = $("#dv-lista-solicitudes > table");
			let estado = $(this).data("estado");

			table.find("tbody tr").removeClass("d-none");
		})
		
		$(document).on('click', '.ver-segmentos', function (e) {
			$("#dv-segmentos").removeClass("d-none");
		})
		
		
		$(document).on('click', '.ver-coti', function (e) {
			let id = $(this).data("id");
			let div = $("#dv-cotizacion-detalle");
			//
			var data = {idCotizacion:id};
			var jsonString = { 'data': JSON.stringify(data) };
			var config = { 'url': Home.url + 'get_cotizacion', 'data': jsonString };

			$.when(Fn.ajaxNoLoad(config)).then(function (a) {
				div.removeClass('d-none');
				div.html(a.data.html);
				$('html, body').animate({ scrollTop: div.offset().top }, 500);
			});
		})

		$('#tableHome').on('click','.btnEtapaActual',function (){
			let tipo = $('#key').val();
			let id = $(this).data('id');
			let datos = {
				'tipo' : tipo,
				'id' : id
			}
			$.ajax({
				dataType: "json",
				url: site_url + 'index.php/' + 'Servicio/consulta',
				data: datos,
				type: 'post',
				beforeSend: function () { Fn.showLoading(true) },
				success: function (response) {
					if (response.estado){
						$(location).attr('href',response.url);
					}
				},
				error: function (){
					Fn.showLoading(false)
				},
				complete: function (){
					Fn.showLoading(false)
				}
			});

		});

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