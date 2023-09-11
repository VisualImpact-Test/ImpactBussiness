<form name="registrarRequerimiento" id="registrarRequerimiento">
    <input type="hidden" name="idCotizacionDetalle" id="idCotizacionDetall" value="<?=$idCotizacionDetalle?>">
    <!-- INICIO TIPO REQUERIMIENTO -->
	<div class="row mb-3 border p-3">
	    <div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
				    <label class="font-weight-bold">TIPO DE REQUERIMIENTO</label>
					<hr class="mt-0">
				</div>
				<div class="col-md-12">
					<div class="row">
							
									<div class="col-md-6">
										<label class="vi-radio" class="tipoRequerimientoLabel">
											<input type="radio" name="tipoRequerimiento" id="incremento" class="forRutas" value="1" patron="requerido" checked>
											<span class="radio-tittle">Incremento</span>
										</label>
									</div>
							
						</div>
					</div>
				</div>
			</div>
</form>