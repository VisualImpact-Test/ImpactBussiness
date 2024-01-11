<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>

<? if ($hojas[0] === 'Tarifario Actualizar') { ?>

<button type="button" class="btn btn-outline-trade-visual border-0" onclick="iniciarExportacionAExcel()" title="Excell">
    <i class="far fa-file-excel"></i>
</button>

<? } ?>

<form id="formCargaMasiva" role="form">
    <div class="row">
        <div class="col-md-12" id="divTablaCargaMasiva">

            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($hojas as $key => $row) { ?>
                    <li class="nav-item">
                        <a class="tabCargaMasiva nav-link <?= ($key == 0) ? 'active' : '' ?>" id="hoja<?= $key ?>-tab" data-nrohoja="<?= $key ?>" data-toggle="tab" href="#hoja<?= $key ?>" role="tab" aria-controls="hoja<?= $key ?>" aria-selected="true"><?= $row ?></a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <!-- <a href="<?= base_url() ?>item/descargar_formato_excel" target="_blank" class="btn btn-trade-visual">DESCARGAR FORMATO</a> -->
                </li>
                <!-- Botón de Exportar a Excel -->
            </ul>

            <div class="tab-content mt-4 text-white">
                <?php foreach ($hojas as $key => $row) { ?>


                    <div class="tab-pane <?= ($key == 0) ? 'show active' : '' ?>" id="hoja<?= $key ?>" role="tabpanel" aria-labelledby="hoja<?= $key ?>-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="divHT<?= $key ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</form>

<script>
    function datos(callback) {
        $.ajax({
            url: '/impactBussiness/Tarifario/Item/getFormActualizarMasivoTarifario',
            type: 'GET',
            dataType: 'json',
            success: function(respuesta) {

                var info = respuesta.data.ht[0].data;

                let arrayDeArrays = info.map(objeto => {
                    return [objeto.item, objeto.proveedor, objeto.costo, objeto.fecha];
                });

                let filaEstatica = ['ITEM', 'PROVEEDOR', 'COSTO', 'FECHA'];

                arrayDeArrays.unshift(filaEstatica);

                // console.log(arrayDeArrays);

                // exportToExcel('tableData.xlsx', arrayDeArrays);
                if (typeof callback === 'function') {
                    callback(arrayDeArrays);
                }

            },
            error: function(xhr, status, error) {
                // Manejar el error
                console.error(error);
            }
        });
    }

    // setInterval(datos, 15000); // Verifica cada 15 segundos

    function iniciarExportacionAExcel() {
        datos(function(data) {
            exportToExcel('tableData.xlsx', data);
        });
    }


    function getAllTextFromTable(tableID) {
        var form = document.getElementById(tableID);
        var tabla = form.querySelector('.htCore');
        var rows = tabla.getElementsByTagName('tr');
        var data = [];

        // Iterar sobre cada fila
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var rowData = [];

            // Iterar sobre cada celda de la fila
            for (var j = 0; j < row.cells.length; j++) {
                // Obtener el texto de cada celda y quitar espacios en blanco
                rowData.push(row.cells[j].textContent.trim());
            }

            // Añadir los datos de la fila al array de datos
            data.push(rowData);
        }

        return data;

    }

    // Esta función crea y descarga el archivo Excel
    function exportToExcel(filename, data) {


        var allData = getAllTextFromTable('formCargaMasiva');

        console.log(allData);
        console.log(data);
        let workbook = new ExcelJS.Workbook();
        let worksheet = workbook.addWorksheet('My Sheet');

        // Estilos para el encabezado
        const headerFill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: 'FFF9FAFB'
            } // Un color verde claro, cambia según tus preferencias
        };

        const headerFont = {
            name: 'Calibri',
            color: {
                argb: 'FF000000'
            }, // Texto negro
            bold: true,
            size: 12
        };

        const border = {
            top: {
                style: 'thin'
            },
            left: {
                style: 'thin'
            },
            bottom: {
                style: 'thin'
            },
            right: {
                style: 'thin'
            }
        };

        // Aplica los estilos al encabezado
        const headerRow = worksheet.getRow(1);
        headerRow.values = data[0]; // Asume que la primera fila de allData contiene los encabezados

        headerRow.eachCell((cell) => {
            cell.fill = headerFill;
            cell.font = headerFont;
            cell.border = border;
            cell.alignment = {
                vertical: 'middle',
                horizontal: 'center'
            };
        });

        // Estilos para las celdas de datos
        const dataFill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: 'FFFFFFFF'
            } // Blanco
        };

        const dataFont = {
            name: 'Calibri',
            color: {
                argb: 'FF000000'
            }, // Texto negro
            size: 11
        };

        // Aplica los estilos a las celdas de datos
        data.slice(1).forEach((rowData, index) => { // Empieza desde la segunda fila de datos
            const row = worksheet.addRow(rowData);
            row.eachCell((cell) => {
                cell.fill = dataFill;
                cell.font = dataFont;
                cell.border = border;
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'left',
                    wrapText: true
                };
            });
        });

        // Ajustar el ancho de las columnas basado en el contenido
        worksheet.columns.forEach(column => {
            let maxColumnLength = 0;
            column.eachCell({
                includeEmpty: true
            }, cell => {
                let cellLength = cell.value ? cell.value.toString().length : 0;
                maxColumnLength = Math.max(maxColumnLength, cellLength);
            });
            column.width = maxColumnLength < 10 ? 10 : maxColumnLength + 2;
        });

        // Guardar y descargar el archivo Excel
        workbook.xlsx.writeBuffer().then(buffer => {
            let blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(error => console.error(error));
    }
</script>