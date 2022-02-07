<div class="row" style="margin-top:5em">
	<div class="col-xs-6 col-sm-12 col-md-3 col-lg-3 mb-2"></div>
	<div class="col-xs-6 col-sm-12 col-md-6 col-lg-6 mb-2">
		<div class="card">
			<img class="card-img-top" src="https://ww7.visualimpact.com.pe/impactTrade/public/assets/images/logos/visual-color.png" style="width: 150px; margin-left:1.25rem;margin-top: 1.25rem;">
			<div class="card-body">
				<h5 class="card-title">ImpactTrade - Restablecer contraseña</h5>
				<div class="my-container">
					<div class="row">
						<div class="col-md-12">
							<form class="form" role="form" id="form-reset-clave">
								<label for="nuevaClave" >Ingrese su nueva contraseña.</label>
								<div class="pg-input">
									<input type="password" class="form-control" id="nuevaClave" name="nuevaClave" placeholder="Ingrese su nueva contraseña" patron="requerido">
								</div>
								<label for="nuevaClave2">Repita su nueva contraseña.</label>
								<div class="pg-input">
									<input type="password" class="form-control" id="nuevaClave2" name="nuevaClave2" placeholder="Repita la nueva contraseña" patron="requerido">
								</div>
								<br>
								<input type="hidden" class="form-control" id="idUsuario" name="idUsuario" value="<?= base64_encode($informacionDeToken['idUsuario']) ?>" patron="requerido">
								<div class="row col=md-12">
									<div class="col col-md-6"></div>
										<div class="pg-input text-center col col-md-6">
											<button id="btnCambiarClave" type="submit" class="form-control btn btn-success" role="button">Enviar</button>
										</div>
									<!-- <div class="col col-md-3"></div> -->
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="col-xs-6 col-sm-12 col-md-3 col-lg-3 mb-2"></div>

</div>

<!-- <div class="card" >
  <img class="card-img-top" src="..." alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div> -->