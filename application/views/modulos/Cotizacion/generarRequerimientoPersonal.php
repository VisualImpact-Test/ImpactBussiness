<div class="col-md-12">
    <form name="registrarRequerimiento" id="registrarRequerimiento">
        <input type="hidden" name="idCotizacionDetalle" id="idCotizacionDetall" value="<?=$idCotizacionDetalle?>">
        <!-- INICIO TIPO REQUERIMIENTO -->
    <div class="row mb-3 border p-3">
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
  
         <!-- FIN TIPO REQUERIMIENTO -->
        
         <!-- INICIO CANTIDAD REQUERIMIENTO -->

         <div class="col-md-4 columnCantidadRequerimientos">
				<div class="row">
					<div class="col-md-12">
						<label class="font-weight-bold">NUM. REQUERIMIENTOS</label>
						<hr class="mt-0">
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input type="number" class="form-control form-control-sm" name="numPersonal" id="nPersonal" min="1" step="1" value="1" patron="requerido" readonly>
						</div>
					</div>
				</div>
			</div>
         <!-- FIN CANTIDAD REQUERIMIENTO -->    

          <!-- INICIO MOTIVO REQUERIMIENTO -->

          <div class="col-md-4 columnCantidadRequerimientos">
				<div class="row">
					<div class="col-md-12">
						<label class="font-weight-bold">MOTIVO DEL REQUERIMIENTO</label>
						<hr class="mt-0">
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<select class="form-control form-control-sm" name="idTipoMotivo" patron="requerido"><option value="">SELECCIONAR</option><option data-flagreemplazo="0" value="27">DESCANSO MEDICO, MATERNIDAD</option><option data-flagreemplazo="0" value="22">INCREMENTO DE ESTRUCTURA</option><option data-flagreemplazo="0" value="21">INGRESO DE NUEVO SERVICIO</option><option data-flagreemplazo="1" value="11">VACACIONES</option></select>
						</div>
					</div>
				</div>
			</div>
         <!-- FIN MOTIVO REQUERIMIENTO -->    
    </div>

    <!---Datos requerimiento--->
    <div class="row mb-3 border p-3">
			<div class="col-md-12">
				<label class="font-weight-bold">DATOS DE REQUERIMIENTO</label>
				<hr class="mt-0">
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Título</label>
					<input name="tituloReq" class="form-control form-control-sm" maxlength="150" value="">
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label>Observaciones</label>
					<div class="input-group">
						<textarea class="form-control form-control-sm" name="observacionesReq" rows="1" maxlength="400"></textarea>
					</div>
				</div>
			</div>
		</div>
         <!------------------------->
    </form>
</div>