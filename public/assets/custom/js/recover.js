var Recover = {
	idForm: 'form-recover',
	idForm2: 'form-reset-clave',
	load: function () {

		if (($(".btn-MostrarOcultarClave").length > 0)){
			$(".btn-MostrarOcultarClave").on("click",function(){
				$(this).find('i').toggleClass('fa-eye-slash');
				var $input = $(this).parents('.input-group-prepend').siblings('input');
				if ($input.attr('type') == 'password'){
					$input.attr('type','text');
				}else{
					$input.attr('type','password');
				}
			});
		}

		$("#btnRecuperarContrasenna").on("click", function (e) {
			e.preventDefault();
			$.when(Fn.validateForm({ id: Recover.idForm })).then(function (a) {
				if (a === true) {
					var data = {};
					data = Fn.formSerializeObject(Recover.idForm);
					var jsonString = { 'data': JSON.stringify(data) };
					var url = "recover/mail";
					var config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {

						++modalId;

						if (b.result == 1) {
							var fn = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.loadPage("' + b.url + '");';
						}
						else var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						var btn = new Array();
						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });

					});
				}
			});

		});

		$(document).on("click", "#btnCambiarClave" , function(e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: Recover.idForm2 })).then(function (a) {
				if (a === true) {
					var data = {};
					data = Fn.formSerializeObject(Recover.idForm2);
					var jsonString = { 'data': JSON.stringify(data) };
					var url = "recover/cambiarClave";
					var config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {

						++modalId;
						
						if (b.result == 1) {
							var fn = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.loadPage("' + b.url + '");';
						}
						else var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						var btn = new Array();
						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });

					});
				}
			});

		});

		$("#btnCambiarClaveUsuario").on("click", function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: Recover.idForm2 })).then(function (a) {
				if (a === true) {
					var data = {};
					data = Fn.formSerializeObject(Recover.idForm2);
					var jsonString = { 'data': JSON.stringify(data) };
					var url = "recover/cambiarClaveUsuario";
					var config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {

						++modalId;
						
						if (b.result == 1) {
							var fn = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.loadPage("' + b.url + '");';
						}
						else var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						var btn = new Array();
						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });

					});
				}
			});

		});

		$("#btnCambiarClaveUsuarioSeguridad").on("click", function (e) {
			e.preventDefault();

			$.when(Fn.validateForm({ id: Recover.idForm2 })).then(function (a) {
				if (a === true) {
					var data = {};
					data = Fn.formSerializeObject(Recover.idForm2);
					var jsonString = { 'data': JSON.stringify(data) };
					var url = "recover/cambiarClaveUsuarioSeguridad";
					var config = { url: url, data: jsonString };

					$.when(Fn.ajax(config)).then(function (b) {

						++modalId;
						
						if (b.result == 1) {
							var fn = 'Fn.showModal({ id:' + modalId + ',show:false });Fn.logOut("home/logout");';
						}
						else var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

						var btn = new Array();
						btn[0] = { title: 'Continuar', fn: fn };
						Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn });

					});
				}
			});

		});

		$("#menuPrincipal").on("click", function (e) {
			e.preventDefault();

		});

	}

}
Recover.load();