<form id="formExcel" enctype="multipart/form-data">
    <div class="" role="alert" style="color:black !important;">
        <i class="fa fa-check-circle"></i> <label class=""><strong>Esto es una modalidad de carga masiva por archivo excel </strong></label>.
        <br>
        <i class=""><i class="fa fa-check-circle"></i> El archivo excel debe contar con los siguientes campos y en el siguiente orden:
            <br><strong> NOMBRE CUENTA, BANNER, CLUSTER, CATEGORIA, SEGMENTO, MARCA, FECHA INICIO, FECHA FIN,VALOR META, ESTADO</strong></i> </label>.<br>
        <br><label>Nota: <ul>
                <li>Solo se permite archivo con extensión .xlsx</li>
                <li>Solo se admiten archivos con 1000 filas de información como máximo.</li>
            </ul></label> <br>
        <br><br><strong>Este es un ejemplo del excel a subir <a href="<?=base_url() ;?>salesforce/dm/catman/descargarModelo">modeloExcel.xlsm</a></strong>
        <br> <small class="">* Este modelo contiene 2 filas de ejemplo</small>
        <br>
        <hr>
    </div>
     <div class='form-row'>
         <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 mb-2 p-3'>
             <label for='excel'>Fecha Inicio</label><br>
             <input id='inicio' name='inicio' type='date' class=''>
             <div class="invalid-feedback d-block" id="mensajeInicio"></div>
         </div>
         <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 mb-2 p-3'>
             <label for='excel'>Fecha Fin</label><br>
             <input id='fin' name='fin' type='date' class='' >
             <div class="invalid-feedback d-block" id="mensajeFin"></div>
         </div>
        <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 mb-2 p-3'>
            <label for='excel'>Suba su archivo excel</label><br>
            <input id='excel' name='excel' type='file' class='' placeholder='Subir archivo excel' >
            <div class="invalid-feedback d-block" id="mensajeExcel"></div>
        </div>
    </div>
</form>

<script>
    $('.my_select2').select2({
        dropdownParent: $("div.modal-content"),
        width: '100%'
    });
</script>