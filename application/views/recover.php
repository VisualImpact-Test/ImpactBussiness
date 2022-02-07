<div class="row mt-4">
	<div class="col-lg-2 d-flex justify-content-center align-items-center">
		<h3 class="card-title mb-3">
			<i class="<?= $icon ?>"></i>
			<?= $title ?>
		</h3>
	</div>
	<div class="col-lg-10 d-flex">
		<div class="card w-100 mb-3 p-0">
			<div class="card-body p-0">
				<ul class="nav nav-tabs nav-justified">
					<li class="nav-item btnReporte" id="" name="" url="">
						<a data-toggle="tab" href="#contentRecover" class="active nav-link " data-value="1">&nbsp;</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
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