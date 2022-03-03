<style>
    .tabla {
        width: 100%;
        border: 1px solid #000;
    }

    .tabla th,
    td {
        /* width: 25%; */
        text-align: left;
        /* vertical-align: top; */
        /* border: 1px solid #000; */
        border-collapse: collapse;
        padding: 0.3em;
        caption-side: bottom;
    }

    caption {
        padding: 0.3em;
        color: #fff;
        background: #000;
    }

    .text-center {
        text-align: center;
    }

    .header {
        background-color: #2586da;
        color: white;
    }

    .row_data:hover {
        background-color: rgba(229, 247, 147, 0.46);
    }
</style>
<h3 style="margin: 0px;">Estimados, se le informa que se ha generado un nuevo prospecto de cotizacion con la siguiente información:</h3>
<br>
<div class="row">
    <fieldset style="margin-bottom:15px;">
        <legend>Datos Generales</legend>
        <div style="width: 70%;display: table-cell;">
            <div>
                <label for="nombre" style="border:0px;">Nombre :</label>
                <label for="nombre" style="border:0px;"><?= verificarEmpty($cabecera['cotizacion'], 3) ?></label>
            </div>
            <div>
                <label for="cuentaForm" style="border:0px;">Cuenta :</label>
                <label for="cuentaForm" style="border:0px;"><?= verificarEmpty($cabecera['cuenta'], 3) ?></label>
            </div>
        </div>
        <div style="width: 30%;display: table-cell;">
            <div>
                <label for="cuentaCentroCostoForm" style="border:0px;">Centro de Costo :</label>
                <label for="cuentaCentroCostoForm" style="border:0px;"><?= verificarEmpty($cabecera['cuentaCentroCosto'], 3) ?></label>
            </div>
        </div>
    </fieldset>
</div>
<div style="margin-top: 15px;">
    <fieldset style="margin-top:15px;margin-bottom:15px;">
        <legend>Detalle de Items</legend>
        <div>
            <div id="div-ajax-detalle" class="table-responsive" style="text-align:center">
                <table class="tabla" id="listaItemsPresupuesto" style="background:#ffffff;color:#666666" width="100%" class="tabla">
                    <thead class="thead-light">
                        <tr class="row_data">
                            <th style="width: 5%;background-color: #2586da;color: white;" class="text-center header">#</th>
                            <th style="width: 15%;background-color: #2586da;color: white;" class="text-center header">Tipo de Item</th>
                            <th style="width: 50%;background-color: #2586da;color: white;" class="text-center header">Item</th>
                            <th style="width: 15%;background-color: #2586da;color: white;" class="text-center header">Cantidad</th>
                            <th style="width: 8%;background-color: #2586da;color: white;" class="text-center header">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        foreach ($detalle as $key => $row) {
                        ?>
                            <tr class="default">
                                <td><?= $key + 1 ?></td>
                                <td><?= verificarEmpty($row['itemTipo'], 3) ?></td>
                                <td><?= verificarEmpty($row['item'], 3) ?></td>
                                <td><?= verificarEmpty($row['cantidad'], 3) ?></td>
                                <td><?= verificarEmpty($row['estadoItem'], 3) ?></td>
                            </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>
</div>
<br>
<h4 style="margin: 0px;">Se pide a los implicados rellenar los costos de cada item para completar la cotización.</h4>