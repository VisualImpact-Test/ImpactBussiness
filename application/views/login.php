<script src="https://www.google.com/recaptcha/api.js?render=6LduG8UZAAAAAOO7tX4nzdbENf-DFjmkjt0PFgAf"></script>

<div class="h-100 no-gutters row">
	<div class="d-cover-opacity bg-impactTrade-2"></div>
	<div class="col-lg-9 d-none d-sm-none d-md-none d-lg-none d-xl-block"></div>
	<div class="w-100 bg-impactTrade-1 d-flex col-lg-3 col-md-12">
		<div class="container-login">
			<div class="login-box">
				<div class="logo-white"></div>
				<div class="divider"></div>
				<form id="frm-login" class="" onsubmit="return false;">
					<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
					<input type="hidden" name="action" value="validate_captcha">
					<div class="form-row">
						<div class="col-md-6">
							<div class="position-relative form-group"><label for="user" class="text-white">Usuario</label><input name="user" id="user" placeholder="Ingrese aquí" type="text" class="form-control" patron="requerido"></div>
						</div>
						<div class="col-md-6">
							<div class="position-relative form-group"><label for="password" class="text-white">Contraseña</label><input name="password" id="password" placeholder="Ingrese aquí" type="password" class="form-control" patron="requerido"></div>
						</div>
					</div>
					<!-- <div class="form-row"> abcdefggg
						holaas
					<div class="form-row row">
						<div class="col-md-12 text-center">
							<div class="g-recaptcha" data-sitekey="6LdAotoaAAAAAEynxHe-NS_mErDdqSSw8AlERnTh" data-size="normal"></div>
						</div>
						hols


					</div> -->
					<div class="divider"></div>
					<div class="form-row">
						<div class="col-md-12">
							<label><a href="javascript:;" id="btn_cambiar_clave" class="text-white text-small">¿Has olvidado tus datos de acceso?</a></label>
						</div>
					</div>
					<div class="d-flex">
						<div class="ml-auto">
							<button id="btn-login" class="ui green button">
							<i class="icon key"></i> 
							Ingresar
							</button>
						</div>

					</div>

					<div class="d-flex">
						<small class="form-text text-white">
							<p class="text-justify"><i class="fas fa-exclamation-triangle"></i> <strong>IMPORTANTE</strong>, el acceso a este sistema es exclusivo para el cumplimiento de las labores internas de Visual Impact S.A.C y colaboradores, la cual se reserva el derecho de auditar su empleo, sancionar conforme al reglamento a las personas que hagan uso no autorizado, mal uso o uso malintencionado a ella; y/o denunciar penalmente a aquellas personas que mediante manipulación dolosa al sistema causen perjuicio a la empresa.</p>
						</small>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- <div class="bg-impactTrade-2 d-none d-lg-block col-lg-9"></div> -->
</div>
<script>
    grecaptcha.ready(function() {
    // do request for recaptcha token
    // response is promise with passed token
        grecaptcha.execute('6LduG8UZAAAAAOO7tX4nzdbENf-DFjmkjt0PFgAf', {action:'validate_captcha'})
                  .then(function(token) {
            // add token value to form
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
</script>