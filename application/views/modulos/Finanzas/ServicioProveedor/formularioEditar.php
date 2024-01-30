
<form class="form" role="form" id="formActualizarProveedorServicio" method="post" autocomplete="off">
    <div class="row">
        <div class="col-md-12 child-divcenter">
            <fieldset class="scheduler-border">
                    <input class="d-none" id="idProveedorServicio" name="idProveedorServicio" value="<?= $ServicioProveedor[0]['idProveedorServicio'] ?>">
                    <legend class="scheduler-border">Datos Proveedor Servicio</legend>
                    <div class="">
                        <div class="row">
                            <div class="control-group child-divcenter row" style="width:48%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-5" for="tipoComprobante" style="border:0px;">Tipo Documento</label>
                                <select class="form-control col-md-5 semantic-dropdown" id="tipoComprobante" name="tipoComprobante"   patron="requerido">
                                    <?= htmlSelectOptionArray2(['query' => $tipoDocumento,'selected' =>  $ServicioProveedor[0]['idTipoDocumento']  , 'class' => 'text-titlecase', 'title' => 'Seleccione' ]); ?>
                                </select>
                            </div>
                            <div class="control-group child-divcenter row" style="width:52%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-5" for="numDocumento" style="border:0px;">Numero Documento</label>
                                <input class="form-control col-md-7" id="numDocumento" name="numDocumento" patron="requerido,ruc" value="<?= $ServicioProveedor[0]['numDocumento'] ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="control-group child-divcenter row" style="width:99%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-3" for="datProveedor" style="border:0px;">Datos Proveedor</label>
                                <input class="form-control col-md-9" id="datProveedor" name="datProveedor" patron="requerido" value="<?= $ServicioProveedor[0]['datosProveedor'] ?>">
                            </div>
                          
                        </div>
                        <br>
                        <div class="row">
                            <div class="control-group child-divcenter row" style="width:50%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Region</label>
                                <select class="form-control col-md-8 semantic-dropdown" id="cboRegion" name="departamento" >
                                    <option value="">Seleccione</option>
                                    <?php foreach ($departamento as $k => $v) : ?>
                                        <option value="<?= $v['id'] ?>" <?= !empty($ServicioProveedor) ? ($v['id'] == $ServicioProveedor[0]['cod_departamento'] ? 'selected' : '') : ''; ?>><?= $v['nombre'] ?></option>;
                                    <?php endforeach; ?>
                                </select>

                            </div>
                          
                     
                            <div class="control-group child-divcenter row" style="width:50%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Provincia</label>
                                <select class="form-control col-md-8 semantic-dropdown" id="cboProvincia" name="provincia" >
                                    <option value="">Seleccione</option>
                                    <?php if (!empty($ServicioProveedor[0]['cod_provincia'])) : ?>
								<option value="<?= $ServicioProveedor[0]['cod_provincia']; ?>" selected><?= $ServicioProveedor[0]['provincia']; ?></option>
							<?php endif; ?>
                                </select>

                            </div>
                          
                        </div>
                        <br>
                        <div class="row">
                            <div class="control-group child-divcenter row" style="width:40%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-3" for="razonSocial" style="border:0px;">Distrito</label>
                                <select class="form-control col-md-8 semantic-dropdown" id="cboDistrito" name="distrito" >
                                    <option value="">Seleccione</option>
                                    <?php if (!empty($ServicioProveedor[0]['cod_distrito'])) : ?>
                                        <option value="<?= $ServicioProveedor[0]['cod_distrito']; ?>" selected><?= $ServicioProveedor[0]['distrito']; ?></option>
                                    <?php endif; ?>
                                </select>

                            </div>
                            <div class="control-group child-divcenter row" style="width:60%;margin-left: 0px;margin-right: 0px;">
                                <label class="form-control col-md-3" for="direccion" style="border:0px;">Direccion</label>
                                <input class="form-control col-md-8" id="direccion" name="direccion" patron="requerido" value="<?= $ServicioProveedor[0]['direccion'] ?>">

                            </div>
                          
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                  
                    <legend class="scheduler-border">Datos Contactos</legend>
                    <div class="">
                        <div class="row">
                            <div class="col-12" >
                                <div class="row">
                                    <div class="control-group child-divcenter row" style="width:59%;margin-left: 0px;margin-right: 0px;">
                                        <label class="form-control col-md-3" for="nomContacto" style="border:0px;">Nombre</label>
                                        <input class="form-control col-md-9" id="nomContacto" name="nomContacto" value="">
                                    </div>
                                    <div class="control-group child-divcenter row" style="width:39%;margin-left: 0px;margin-right: 0px;">
                                        <label class="form-control col-md-4" for="telContacto" style="border:0px;">Telefono</label>
                                        <input class="form-control col-md-8 onlyNumbers" id="telContacto" name="telContacto"  value="">
                                    </div>
                                <div>    
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-10" >
                               
                                    <div class="control-group child-divcenter row" style="width:98%;margin-left: 0px;margin-right: 0px;">
                                        <label class="form-control col-md-2" for="correoContacto" style="border:0px;">Correo</label>
                                        <input class="form-control col-md-10" id="correoContacto" name="correoContacto"  value="">
                                    </div>
                                    
                                  
                            </div>   
                            <div class="col-2" >
                              
                                    <div class="control-group child-divcenter row" style="width:99%;margin-left: 0px;margin-right: 0px;">
                                        <button class="form-control " type="button" id="btn-añadir-proveedor" name="btn-añadir-proveedor">Añadir</button>
                                    </div>
                            
                            </div>  
                        </div>
                        <div>
                    </div>
                    
                    <div class="card-datatable">
	                    <table id="tb-contacProveedores" class="ui celled table" width="100%">
                       
                        <thead>
                            <tr>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Opcion</th>
                            </tr>
                        </thead>
                        <tbody>
                       <?php foreach ($Contactos as $key => $row) { ?>
                        <tr data-id="<?= $row['idContacto']; ?>">
                            <td>
                                <input class="form-control" name="nomContacto" value="<?= $row['nomContacto']; ?>" readonly>
                            </td>
                            <td>
                                <input class="form-control" name="telContacto" value="<?= $row['telContacto']; ?>" readonly>
                            </td>
                            <td>
                                <input class="form-control" name="correoContacto" value="<?= $row['correoContacto']; ?>" readonly>
                            </td>
                            <td>
                             <a class="ui red  label elimnaRegistro"><i class="trash icon m-0"></i></a>                        
                            </td>
                        </tr>
                        <?php  } ?>
                       
                        </tbody>
                        </table>
                    </div>
                </fieldset>

        </div>
    </div>



</form>

