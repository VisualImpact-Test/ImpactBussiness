var Login={

	load: function(){

		$("#btn-login").on("click",function(e){
			e.preventDefault();

			
			$.when( Fn.validateForm({ id:"frm-login" }) ).then(function(a){
				if( a===true ){

					$usuario = $('#user').val();
					$pwd = $("#password").val();
					if($('#exampleCheck').is(':checked')){
						$recordar = 1;
					}else{
						$recordar = 0;
					}

					var data={};
					data=Fn.formSerializeObject("frm-login");
					var jsonString={ 'data':JSON.stringify(data) };
					var url="login/acceder";
					var config={ url:url,data:jsonString };

					$.when( Fn.ajax(config) ).then(function(b){
						if( b.result!=2 ){
							++modalId;

							var btn = [];
							var fn = [];

							if(b.status==1){
								
								if($recordar == 1){
									document.cookie = "uid=" + encodeURIComponent( $usuario );
									document.cookie = "pwd=" + encodeURIComponent( $pwd );
								}else{
									document.cookie = "uid=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
									document.cookie = "uid=; max-age=0";
									document.cookie = "pwd=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
									document.cookie = "pwd=; max-age=0";
								}

								fn[0] = 'Fn.showModal({ id:'+modalId+',show:false });Fn.loadPage("'+b.url+'");';
								localStorage.setItem('flag_anuncio_visto',b.data.flag_anuncio_visto);
								localStorage.setItem('modalCuentaProyecto', 0);
								localStorage.setItem('vi_tv_filtros_ww7',b.data.filtros);
								
							}else{
								fn[0] = 'Fn.showModal({ id:'+modalId+',show:false });grecaptcha.reset();';

								if(b.status == 3){
									fn[1] = 'Login.enviarCorreo("'+b.correo+'");';
									btn[1] = { title:'Enviar Correo', fn:fn[1] };
								}
							}

							if(b.status == 2){
								Login.modalCuentaProyecto();
							}else{
								btn[0] = { title:'Continuar', fn:fn[0] };
								Fn.showModal({ id:modalId,show:true,title:b.msg.title,content:b.msg.content,btn:btn });
							}
						}
					});
				}
			});
			
			
		});

		$(document).on("click", "#btn_cambiar_clave", function(e){
			e.preventDefault();
			var data={};
			var jsonString={ 'data':JSON.stringify(data) };
			var url="recover/frm_email_changepwd";
			var config={ url:url,data:jsonString };

			$.when( Fn.ajax(config) ).then(function(b){
				if( b.result!=2 ){
					++modalId;

					var fn='Fn.showModal({ id:'+modalId+',show:false });';
					var btn=new Array();
					btn[0]={title:'Cancelar',fn:fn};
					Fn.showModal({ id:modalId,show:true,title:b.msg.title,content:b.data.content,btn:btn });
				}
			});
		});

		$(document).on("click", ".btn-changepwd", function(e){
			e.preventDefault();
			var data={};
			data=Fn.formSerializeObject("frm_mail_recover");
			var jsonString={ 'data':JSON.stringify(data) };
			var url="recover/mail";
			var config={ url:url,data:jsonString };

			$.when( Fn.ajax(config) ).then(function(b){
				if( b.result!=2 ){
					++modalId;

					var fn='Fn.showModal({ id:'+modalId+',show:false });';
					var btn=new Array();
					btn[0]={title:'Aceptar',fn:fn};
					Fn.showModal({ id:modalId,show:true,title:b.msg.title,content:b.msg.content,btn:btn });
				}
			});
		});
		
	},

	enviarCorreo: function(correo){
		var data = { 'email':correo };
		var jsonString = {
			'data': JSON.stringify(data)
		};
		var url = "recover/mail";
		var config = {
			url: url,
			data: jsonString
		};

		$.when(Fn.ajax(config)).then(function (b) {

			var idModal = ++modalId;
			var url = 'index';
			var message = Fn.message({
				'type': 1,
				'message': 'Se ha enviado un mensaje con el link de recuperación al correo especificado. El enlace solo durará 5 minutos'
			});
			var btn = [{
				'title': 'Cerrar',
				'fn': 'Fn.showModal({ id: ' + idModal + ', show: false });Fn.loadPage("login");'
			}];

			Fn.showModal({ id:idModal,show:true,title:b.msg.title,content:b.msg.content,btn:btn,width:'800px' });

			return false;
		});
	},
	modalCuentaProyecto: function(){
		$.when( Fn.ajax({ url: 'control/get_cuenta' }) ).then(function(a){
			if( a.result == 2 ) return false;

			var html = '';
				html += '<form id="frm-cambiarcuenta" class="py-3 px-4">';
					html += '<div class="row">';
						html += '<div class="col-md-8 offset-md-2">';
							html += '<div class="form-group row">';
								html += '<label class="col-md-3 col-form-label pt-0 text-left">Cuenta:</label>';
								html += '<div class="col-md-9">';
									$.each(a.data.cuenta, function(icuenta, vcuenta){
										var checked = '';
										if( a.data.cuenta.length == 1 ||
											vcuenta['id'] == a.data.idCuenta ) checked = 'checked';

										html += '<div class="form-check mb-1">';
											html += '<input type="radio" id="idCuenta' + vcuenta['id'] + '" class="form-check-input rd-cambiarcuenta-cuenta" name="idCuenta" value="' + vcuenta['id'] + '" ' + checked + ' />';
											html += '<label class="form-check-label cursor-pointer" for="idCuenta' + vcuenta['id'] + '">';
												html += vcuenta['nombre'];
											html += '</label>';
										html += '</div>';
									});
								html += '</div>';
							html += '</div>';
							html += '<div class="form-group row">';
								html += '<label class="col-md-3 col-form-label pt-0 text-left">Proyecto:</label>';
								html += '<div id="dv-cambiarcuenta-proyecto" class="col-md-9">';
									if( typeof(a.data.proyecto) != 'undefined' ){
										$.each(a.data.proyecto, function(iproyecto, vproyecto){
											var checked = '';
											if( vproyecto['id'] == a.data.idProyecto ) checked = 'checked';

											html += '<div class="form-check mb-1">';
												html += '<input class="form-check-input" type="radio" name="idProyecto" id="idProyecto-' + vproyecto['id'] + '" value="' + vproyecto['id'] + '" ' + checked + ' />';
												html += '<label class="form-check-label cursor-pointer" for="idProyecto-' + vproyecto['id'] + '">';
													html += vproyecto['nombre'];
												html += '</label>';
											html += '</div>';
										});
									}
									else{
										html += '<small class="text-muted">* Selecciona una Cuenta</small>';
									}
								html += '</div>';
							html += '</div>';
						html += '</div>';
					html += '</div>';
				html += '</form>';

				++modalId;
				var btn = [{ title: 'Cambiar', id: 'btn-cambiarcuenta-confirm', class: 'btn-trade-visual' }];
				if(
					a.data.idCuenta != null &&
					a.data.idProyecto != null &&
					String(a.data.idCuenta).length > 0 &&
					String(a.data.idProyecto).length > 0
				){
					btn.unshift({ title: 'Cerrar', fn: 'Fn.showModal({ id: ' + modalId + ', show: false });' });
				}

				Fn.showModal({
					id: modalId,
					show: true,
					title: 'Cambio de Cuenta / Proyecto',
					frm: html,
					btn: btn
				});

				View.idModal = modalId;
		});
	}

}
Login.load();

if(typeof document.cookie.split(';')[0].split('=')[1] != 'undefined'){

	let pwd = '';
	let uid = '';
	
	if(document.cookie.split(';')[0].split('=')[0] == "pwd"){
		 pwd = document.cookie.split(';')[0].split('=')[1];
		 uid = document.cookie.split(';')[1].split('=')[1];
	}else if(document.cookie.split(';')[0].split('=')[0] == "uid"){
		uid = document.cookie.split(';')[0].split('=')[1];
		pwd = document.cookie.split(';')[1].split('=')[1];
	}
	
	$('#user').val(uid) ;
	$("#password").val(pwd) ;
	$('#exampleCheck').prop('checked',true);
	
}else{
	$('#user').val('');
	$('#password').val('');
}