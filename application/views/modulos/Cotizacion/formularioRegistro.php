<form class="form" role="form" id="formRegistroCotizacion" method="post">
    <div class="child-divcenter" style="width:90%">
        <h4 class="ui dividing header">Información de la Cotización</h4>
        <div class="ui form">
            <div class="fields">
                <div class="ten wide field">
                    <label>Titulo de la Cotización:</label>
                    <input id="nombre" name="nombre" patron="requerido" placeholder="Nombre">
                </div>
                <div class="five wide field">
                    <label>Cuenta:</label>
                    <select class="ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase']); ?>
                    </select>
                </div>
                <div class="five wide field">
                    <label>Centro de Costo:</label>
                    <select class="ui search dropdown childDependiente" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                        <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase']); ?>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Fecha de Requerimiento:</label>
                <div class="fields">
                    <div class="five wide field">
                        <div class="ui calendar date-semantic">
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" placeholder="Fecha de Requerimiento">
                            </div>
                        </div>
                        <input type="hidden" class="date-semantic-value" name="fechaRequerimiento" placeholder="Fecha de Requerimiento" value="">
                    </div>
                    <div class="five wide field">
                        <div class="inline field">
                            <div class="ui toggle checkbox">
                                <input type="checkbox" tabindex="0" class="hidden">
                                <label>Incluye IGV</label>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="two wide field d-flex align-items-center">
                        <i class="semaforoForm semaforoForm-red fad fa-lg fa-flag-alt"></i>
                        <label class="semaforoLabel">7 dias</label>
                    </div>
                    <div class="two wide field d-flex align-items-center">
                        <i class="semaforoForm semaforoForm-yellow fad fa-lg fa-flag-alt"></i>
                        <label class="semaforoLabel">8 a 15 dias</label>
                    </div>
                    <div class="two wide field d-flex align-items-center">
                        <i class="semaforoForm semaforoForm-green semaforoForm-red fad fa-lg fa-flag-alt"></i>
                        <label class="semaforoLabel">+ 15 dias</label>
                    </div> -->
                    <div class="six wide field">
                        <a class="ui teal image label">
                            <i class="fa fa-flag-alt"></i>
                            Vigencia
                            <div class="detail">7 dias</div>
                        </a>
                        <a class="ui yellow image label">
                            <i class="fa fa-flag-alt"></i>
                            Vigencia
                            <div class="detail">8 a 15 dias</div>
                        </a>
                        <a class="ui red image label">
                            <i class="fa fa-flag-alt"></i>
                            Vigencia
                            <div class="detail">+ 15 dias</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-11 child-divcenter">
            <h4 class="ui dividing header">Detalle de la Cotización</h4>
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center;max-height:400px;overflow:auto;">
                <table class="ui celled padded table" id="listaItemsCotizacion">
                    <thead class="thead-default ui">
                        <tr>
                            <th style="width: 3%;" class="text-center">#</th>
                            <th style="width: 15%;">Tipo Item</th>
                            <th style="width: 40%;">Item</th>
                            <th style="width: 20%;">Características</th>
                            <th style="width: 10%;" class="text-center">Cantidad</th>
                            <th style="width: 10%;" class="text-center">Costo</th>
                            <th style="width: 3%;" class="text-center"></th>
                            <th style="width: 10%;" class="text-center">Subtotal</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr hidden class="default">
                            <td>
                                <select class="form-control" id="tipoItemForm" name="tipoItemForm" patron="requerido">
                                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $itemTipo, 'class' => 'text-titlecase']); ?>
                                </select>
                            </td>
                            <td>
                                <div class="ui-widget">
                                    <input class="form-control items" type='text' name='nameItem' patron="requerido">
                                    <input class="codArticulos" type='hidden' name='idItemForm'>

                                    <input class="idEstadoItemForm" type='hidden' name='idEstadoItemForm' value="2">
                                    <input class="idTipoArticulo" type='hidden' name='idTipoArticulo' value="">
                                </div>
                            </td>
                            <td>
                                <div class="ui-widget">
                                    <input class="form-control" type='text' name='caracteristicasItem' patron="requerido">
                                </div>
                            </td>
                            <td>
                                <input class="form-control cantidadForm" type="number" name="cantidadForm" patron="requerido,numerico" min="1" max="10000">
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">S/.</span>
                                    </div>
                                    <input class="form-control costoForm" type="number" name="costoForm" placeholder="0" readonly>
                                </div>
                            </td>
                            <td>
                                <i class="semaforoForm fad fa-lg fa-flag-alt"></i>
                            </td>
                            <td>
                                <input class="form-control subtotalForm" type="number" name="subtotalForm" readonly>
                            </td>
                            <td class="text-center">
                                <a href="javascript:;" class="btn btn-outline-secondary border-0 btneliminarfila" title="Eliminar Fila"><i class="fad fa-lg fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="full-width">
                        <tr>
                            <th></th>
                            <th colspan="8">
                                <div class="ui right floated small primary labeled icon button btn-add-row" title="Añadir Fila">
                                    <i class="plus icon"></i> Añadir Fila
                                </div>
                                <!-- <div class="ui small button">
                                    Approve
                                </div>
                                <div class="ui small disabled button">
                                    Approve All
                                </div> -->
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- <div id="div-ajax-detalle" style="text-align:center">
                <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-add-row" title="Añadir Fila" style="margin:10px;"><i class="fad fa-lg fa-plus"></i></a>
            </div> -->
        </div>
    </div>
</form>
<script>
    $('select.dropdown').dropdown();
    $('.date-semantic').calendar({
        type: 'date',
        text: {
            days: ['S', 'L', 'M', 'MM', 'J', 'V', 'S'],
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            today: 'Hoy',
            now: 'Ahora',
            am: 'AM',
            pm: 'PM'
        },
        onChange: function(date) {
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }

            $(this).siblings('.date-semantic-value').val(year + '-' + month + '-' + day);
        }
    });
    $('.ui.checkbox').checkbox();
</script>