<form class="ui form" role="form" id="formRegistrarFactura" method="post" autocomplete="off">
	<div class="row">
		<div class="col-md-12 child-divcenter">
			<fieldset class="scheduler-border">
				<input class="d-none" id="idProveedorServicioGenerado" name="idProveedorServicioGenerado" value="<?= $pagosGenerados[0]['idProveedorServicioGenerado'] ?>">
				<legend class="scheduler-border">Datos Facturas</legend>
				<div class="">
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:55%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Razón Social :</label>
                            <input class="form-control col-md-9" id="razonSocial" name="razonSocial" patron="requerido" value="<?= $pagosGenerados[0]['datosProveedor'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:45%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-4" for="descripcionServicio" style="border:0px;">Descripcion Servicio :</label>
                            <input class="form-control col-md-8" id="descripcionServicio" name="descripcionServicio"  value="<?= $pagosGenerados[0]['descripcionServicio'] ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="control-group child-divcenter row" style="width:35%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="ruc" style="border:0px;">Tipo de Documento :</label>
                            <input class="form-control col-md-6" id="ruc" name="ruc" patron="requerido" value="<?= $pagosGenerados[0]['breve'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:35%;margin-left: 0px;margin-right: 0px;">
                            <label class="form-control col-md-6" for="nombreContacto" style="border:0px;">Numero de Documento :</label>
                            <input class="form-control col-md-6" id="nombreContacto" name="nombreContacto"  value="<?= $pagosGenerados[0]['numDocumento'] ?>">
                        </div>
                        <div class="control-group child-divcenter row" style="width:15%;margin-left: 0px;margin-right: 0px;">
                            <button class="ui button" type="button" name="new-factura" id="new-factura">Agregar Factura</button>
                        </div>
                    </div>
				</div>
			</fieldset>
            <fieldset class="scheduler-border">
           
				<legend class="scheduler-border">Datos Comprobante</legend>
				<div id="agregar-factura">

                </div>
			</fieldset>
		</div>
	</div>
</form>