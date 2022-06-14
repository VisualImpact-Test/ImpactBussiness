
<div class="mb-3 card">
	<div class="card-body">
		<div class="pg-row">
			<div class="my-container">
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<form class="form" role="form" id="form-reset-clave" method="post">
							<label for="claveActual">Ingrese su contraseña actual.</label>
							<div class="pg-input">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<li class="fa fa-eye" id="claveActual_view"></li>
										</div>
									</div>
									<input type="password" class="form-control" id="claveActual" name="claveActual" placeholder="Ingrese contraseña actual" patron="requerido">
								</div>
							</div>

							<label for="nuevaClave">Ingrese su nueva contraseña.</label>
							<div class="pg-input">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<li class="fa fa-eye" id="nuevaClave_view"></li>
										</div>
									</div>
									<input type="password" class="form-control" id="nuevaClave" name="nuevaClave" placeholder="Ingrese su nueva contraseña" patron="requerido">
								</div>
							</div>


							<label for="nuevaClave2">Repita su nueva contraseña.</label>
							<div class="pg-input">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<div class="input-group-text">
											<li class="fa fa-eye" id="nuevaClave2_view"></li>
										</div>
									</div>
									<input type="password" class="form-control" id="nuevaClave2" name="nuevaClave2" placeholder="Repita la nueva contraseña" patron="requerido">
								</div>
							</div>
							<br>
							<div class="row col=md-12">
								<div class="col col-md-6"></div>
								<div class="pg-input text-center col col-md-6">
									<button id="btnCambiarClaveUsuario" type="button" class="form-control btn btn-success" role="button">Enviar</button>
								</div>
								<!-- <div class="col col-md-3"></div> -->
							</div>
						</form>
					</div>

					<div class="col-md-4"></div>
				</div>
			</div>
		</div>
	</div>
</div>