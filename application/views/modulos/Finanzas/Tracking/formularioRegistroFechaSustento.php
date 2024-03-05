<form class="ui form" role="form" id="formRegistroTrackingFechaSustento" method="post" autoComplete="off">
	<div class="ui form attached fluid segment p-4">
		<h4 class="ui dividing header text-uppercase">Datos Sustentos</h4>
        <div class="field">
            <input type="hidden" class="ui" name="idSinceradoGr" value="<?= $idSinceradoGr ?>">
            <input type="hidden" class="ui" name="idCotizacion" value="<?= $idOrdenServicio ?>">
            <div class="field sixteen">
                <label>Estado Tracking:</label>
                <select class="ui selection simpleDropdown" id="estadoSustento" name="estadoSustento">
                <option value="1">Sustento</option>
                <option value="0">Eliminar</option>
                </select>
            </div>
			
		</div>
    
        <div class="field" id="fechSusten">
              
            <div class="field sixteen">
                <label>Fecha de Sustento:</label>
                <div class="ui calendar date-semantic ">
                    <div class="ui input left icon fluid">
                        <i class="calendar icon"></i>
                        <input type="text" value="" >
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" id="fechaSustento" name="fechaSustento" value="" patron="requerido">
            </div>
		</div>

        <div class="field" id="elimSusten">
              
              <!-- <div class="field sixteen">
                  <label>:</label>
                    <div class="ui checkbox">
                        <input type="checkbox" name="example" patron="requerido">
                        <label>Eliminar</label>
                    </div>
              </div> -->
              <div class="field sixteen">
                  <label>Detalle Eliminacion:</label>
                  <textarea name="comentarioForm" id="comentarioForm"  rows="6" patron="requerido"></textarea>
              </div>

        </div>


	</div>
</form>
