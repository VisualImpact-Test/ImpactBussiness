<form class="ui form" role="form" id="formDatosRutasViajeras" method="post" autocomplete="off">
	
<div class="row ui form">
    <?php foreach ($cabRutasViajeras as $k => $v) { ?>
    <div class="col-md-12 child-divcenter">
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Datos Rutas Viajeras</legend>
            <div class="fields">
            <input class="form-control"  patron="requerido" type="hidden" id="idCotizacionDetalleSub" name="idCotizacionDetalleSub" value="<?= $v['idCotizacionDetalleSub'] ?>" readOnly>

                <div class="field wide nine">
                     <label>Ruta</label>
                    <input class="form-control"  patron="requerido" type="text" id="ruta" name="ruta" value="<?= $v['nombre'] ?>" readOnly>
                </div>
             
                <div class="field wide two">
                     <label>Dias</label>
                    <input class="form-control" patron="requerido" type="text" id="dias" name="dias" value="<?= $v['dias'] ?>" readOnly>
                </div>
                <div class="field wide two">
                     <label>Total</label>
                    <input class="form-control" patron="requerido" type="text" id="total" name="total" value="<?= $v['subtotal'] ?>" readOnly>
                </div>
                <div class="field wide five">
                     <label>Responsable</label>
                    <input class="form-control" patron="requerido" type="text" id="responsable" name="responsable" value="<?= $v['responsable'] ?>">
                </div>
			</div>
            <div class="fields">
                
                <div class="field wide three">
                     <label>Cargo</label>
                    <input class="form-control" patron="requerido" type="text" id="cargo" name="cargo" value="<?= $v['cargo'] ?>">
                </div>
                <div class="field wide three">
                    <label>DNI</label>
                    <input class="form-control" patron="requerido" type="text" id="dni" name="dni" value="<?= $v['dni'] ?>" >
                </div>
                <div class="field wide three">
                    <label>Fecha Inicio</label>
                    <div class="ui calendar date-semantic">
                        <div class="ui input left icon fluid">
                            <i class="calendar icon"></i>
                            <input type="text" value="<?= $v['fechaInicio']; ?>" patron="requerido">
                        </div>
                    </div>
                    <input type="hidden" class="date-semantic-value" name="fechaInicio" value="<?= $v['fechaInicio']; ?>"  patron="requerido">
                </div>
                <div class="field wide three">
                    <label>Fecha Fin</label>
                    <div class="ui calendar date-semantic">
                        <div class="ui input left icon fluid">
                            <i class="calendar icon"></i>
                            <input type="text" value="<?= $v['fechaFin']; ?>">
                        </div>
                    </div>
                    <input type="hidden" class="date-semantic-value" name="fechaFin" value="<?= $v['fechaFin']; ?>"  patron="requerido">
                </div>
			</div>
           
            
        </fieldset>
	</div>
    <?php } ?>
</div>

</form>