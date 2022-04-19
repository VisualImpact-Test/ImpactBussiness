<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="row">
        <div class="col-md-5 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control form-control-sm col-md-5" for="proveedorCotizacion" style="border:0px;">Proveedor :</label>
                <select class="form-control form-control-sm ui my_select2Full" name="proveedorCotizacion" id="proveedor">
                    <?= htmlSelectOptionArray2(['query' => $proveedor, 'class' => 'text-titlecase', 'title' => 'Seleccione']); ?>
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
                            <th style="width: 50%;">Item</th>
                            <th style="width: 35%;">Costo</th>
                            <th class="text-center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr hidden class="default">
                            <td>
                                <select class="form-control form-control-sm ui my_select2Full" name="itemCotizacion">
                                    <? foreach ($items as $key => $value) { ?>
                                        <option value="<?= $value ?>"><?= $value ?></option>
                                    <?
                                    }
                                    ?>
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