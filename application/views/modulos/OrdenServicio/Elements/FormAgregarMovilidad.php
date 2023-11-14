<form class="ui form" role="form" id="form_movil_save" method="post" autoComplete="off">
   <div class="row">
      <div class="col-6">
         <div class="ui sub header">Origen</div><input class="form-control" type="text" name="origen" patron="requerido">
      </div>
      <div class="col-6">
         <div class="ui sub header">Destino</div><input class="form-control" type="text" name="destino" patron="requerido">
      </div>
   </div> <br>
   <div class="row">
      <div class="col-4">
         <div class="ui sub header">Split</div><select class="form-control" name="split">
            <option selected value="1">1 vez por mes</option>
            <option value="2">1 vez cada 2 meses</option>
            <option value="3">1 vez cada 3 meses</option>
         </select>
      </div>
      <div class="col-4">
         <div class="ui sub header">Prec. Bus</div><input class="form-control onlyNumbers" value="0" type="number" name="prec_bus" patron="requerido">
      </div>
      <div class="col-4">
         <div class="ui sub header">Prec. Hospedaje</div><input class="form-control onlyNumbers" value="0" type="number" name="prec_hospedaje" patron="requerido">
      </div>
   </div> <br>
   <div class="row">
      <div class="col-4">
         <div class="ui sub header">Prec. Viaticos</div><input class="form-control onlyNumbers" value="0" type="number" name="prec_viaticos" patron="requerido">
      </div>
      <div class="col-4">
         <div class="ui sub header">Prec. Movilidad Int.</div><input class="form-control onlyNumbers" value="0" type="number" name="prec_movilidad" patron="requerido">
      </div>
      <div class="col-4">
         <div class="ui sub header">Prec. Taxi.</div><input class="form-control onlyNumbers" value="0" type="number" name="prec_taxi" patron="requerido">
      </div>
   </div> <br>
</form>