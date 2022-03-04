<? if ($idArticulosServicios == 23) { ?>
    <form class="form" role="form" id="formRegistroCotizacion" method="post">
        <div class="row">
            <div class="col-md-5 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control form-control-sm col-md-5" for="proveedorCotizacion" style="border:0px;">Proveedor :</label>
                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                        <option value="1" selected>ROCKEFELLER S.A.C.</option>
                        <option value="1">Santiago Resources S.A.C.</option>
                        <option value="1">Nueva Cuenta SAC</option>
                        <option value="1">Lip S.A.C</option>
                        <option value="1">San Antonio S.A</option>
                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                        <option value="1">Proveedores San Marcos S.A.C</option>
                        <option value="1">Proveedores Mariangel S.A</option>
                    </select>
                </div>
                <div class="control-group child-divcenter row w-100">
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-11 child-divcenter">
                <div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
                    <table class="mb-0 table table-bordered text-nowrap" id="listaItemsCotizacion">
                        <thead class="thead-default">
                            <tr>
                                <th style="width: 5%;" class="text-center">#</th>
                                <th style="width: 5%;">Seleccionar</th>
                                <th style="width: 50%;">Item</th>
                                <th style="width: 35%;">Costo</th>
                                <th class="text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="default">
                                <td>
                                    1
                                </td>
                                <td>
                                    <input class="form-control" type="checkbox" name="" value="0" patron="requerido">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1">MUEBLE PG 4x4</option>
                                        <option value="1">CARPINTERO</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="0" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <i style="font-size:25px" class="btn  fa fa-plus-circle btn-add-row-cotizacion"></i>
                </div>
            </div>
        </div>
    </form>
<? } ?>
<? if ($idArticulosServicios == 24) { ?>
    <form class="form" role="form" id="formRegistroCotizacion" method="post">
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-11 child-divcenter">
                <div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
                    <table class="mb-0 table table-bordered text-nowrap" id="listaItemsCotizacion">
                        <thead class="thead-default">
                            <tr>
                                <th style="width: 5%;" class="text-center">#</th>
                                <th style="width: 5%;">Seleccionar</th>
                                <th style="width: 25%;">Proveedor</th>
                                <th style="width: 50%;">Item</th>
                                <th style="width: 35%;">Precio del proveedor</th>
                                <th class="text-center">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="default">
                                <td>1</td>
                                <td>
                                    <input class="form-control form-control-sm" type="checkbox" name="" value="0" patron="requerido" checked>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                                        <option value="1">ROCKEFELLER S.A.C.</option>
                                        <option value="1" selected>Santiago Resources S.A.C.</option>
                                        <option value="1">Nueva Cuenta SAC</option>
                                        <option value="1">Lip S.A.C</option>
                                        <option value="1">San Antonio S.A</option>
                                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                                        <option value="1">Proveedores San Marcos S.A.C</option>
                                        <option value="1">Proveedores Mariangel S.A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1">CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA L</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="25" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="default">
                                <td>2</td>
                                <td>
                                    <input class="form-control form-control-sm" type="checkbox" name="" value="0" patron="requerido">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                                        <option value="1" selected>ROCKEFELLER S.A.C.</option>
                                        <option value="1">Santiago Resources S.A.C.</option>
                                        <option value="1">Nueva Cuenta SAC</option>
                                        <option value="1">Lip S.A.C</option>
                                        <option value="1">San Antonio S.A</option>
                                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                                        <option value="1">Proveedores San Marcos S.A.C</option>
                                        <option value="1">Proveedores Mariangel S.A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1">CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA L</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="28" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="default">
                                <td>3</td>
                                <td>
                                    <input class="form-control form-control-sm" type="checkbox" name="" value="0" patron="requerido">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                                        <option value="1">ROCKEFELLER S.A.C.</option>
                                        <option value="1">Santiago Resources S.A.C.</option>
                                        <option value="1">Nueva Cuenta SAC</option>
                                        <option value="1">Lip S.A.C</option>
                                        <option value="1" selected>San Antonio S.A</option>
                                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                                        <option value="1">Proveedores San Marcos S.A.C</option>
                                        <option value="1">Proveedores Mariangel S.A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1">CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA L</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="24" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="default">
                                <td>4</td>
                                <td>
                                    <input class="form-control form-control-sm" type="checkbox" name="" value="0" patron="requerido">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                                        <option value="1">ROCKEFELLER S.A.C.</option>
                                        <option value="1">Santiago Resources S.A.C.</option>
                                        <option value="1">Nueva Cuenta SAC</option>
                                        <option value="1">Lip S.A.C</option>
                                        <option value="1">San Antonio S.A</option>
                                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                                        <option value="1">Proveedores San Marcos S.A.C</option>
                                        <option value="1" selected>Proveedores Mariangel S.A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1">CAMISA VISUAL MANGA CORTA L</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="24" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="default">
                                <td>5</td>
                                <td>
                                    <input class="form-control form-control-sm" type="checkbox" name="" value="0" patron="requerido">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                                        <option value="1" selected>ROCKEFELLER S.A.C.</option>
                                        <option value="1">Santiago Resources S.A.C.</option>
                                        <option value="1">Nueva Cuenta SAC</option>
                                        <option value="1">Lip S.A.C</option>
                                        <option value="1">San Antonio S.A</option>
                                        <option value="1">Jorge Mendoza Proveedores S.A.C</option>
                                        <option value="1">Proveedores San Marcos S.A.C</option>
                                        <option value="1">Proveedores Mariangel S.A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                        <option value="1" selected>CAMISA VISUAL MANGA CORTA M</option>
                                        <option value="1">CAMISA VISUAL MANGA CORTA L</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control costoCotizacion" type="number" name="costoCotizacion" value="25" patron="requerido,numerico" min="1" max="10000">
                                </td>
                                <td class="text-center">
                                    <button class="btn btneliminarfilaCotizacion"><i class="fa fa-minus-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <i style="font-size:25px" class="btn  fa fa-plus-circle btn-add-row-cotizacion"></i>
                </div>
            </div>
        </div>
    </form>
<? } ?>