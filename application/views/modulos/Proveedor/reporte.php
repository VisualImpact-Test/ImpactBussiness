<style>

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.0/exceljs.min.js"></script>
<div class="card-datatable">
    <table id="tb-proveedor" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>RAZON SOCIAL</th>
                <th>RUC</th>
                <th>RUBRO</th>
                <th>CUENTA</th>
                <th>METODO DE PAGO</th>
                <th style="display: none;">TIPO SERVICIO</th>
                <th style="display: none;">TIPO COMPROBANTE</th>
                <th>DIRECCION</th>
                <th>CONTACTO</th>
                <th>CORREO</th>
                <th>NUMERO</th>
                <th style="display: none;">BANCO</th>
                <th style="display: none;">TIPO CUENTA</th>
                <th style="display: none;">N° CUENTA</th>
                <th style="display: none;">CCI</th>

                <th class="td-center">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($datos as $key => $row) {
            ?>
                <tr data-id="<?= $key ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <?
                        if (!empty($row['estadoToggle'])) {
                        ?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Actualizar Proveedor"><i class="fa fa-lg fa-edit"></i></a>
                            <a id="hrefEstado-<?= $key; ?>" href="javascript:;" class="btn btn-outline-secondary border-0 btn-actualizar-estado" data-id="<?= $key; ?>" data-estado="<?= $row['idEstado']; ?>">
                                <i class="<?= $row['estadoToggle'] ?>"></i>
                            </a>
                        <?
                        } else {
                        ?>
                            <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-validar" title="Solicitar Aprobacion"><i class="fa fa-lg fa-file-import"></i></a>
                        <?
                        }
                        ?>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['razonSocial'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['nroDocumento'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['rubros'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['cuentas_bancos'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['metodosPago'], 3); ?></td>

                    <td class="td-left" style="display: none;"><?= verificarEmpty($row['tipoServicio'], 3); ?></td>
                    <td class="td-left" style="display: none;"><?= verificarEmpty($row['comprobante'], 3); ?></td>

                    <td class="td-left"><?= verificarEmpty($row['direccion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['nombreContacto'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['correoContacto'], 3); ?></td>
                    <td class="td-center"><?= verificarEmpty($row['numeroContacto'], 3); ?></td>

                    <td class="td-center" style="display: none;"><?= verificarEmpty($row['banco'], 3); ?></td>
                    <td class="td-center" style="display: none;"><?= verificarEmpty($row['tipoCuenta'], 3); ?></td>
                    <td class="td-center" style="display: none;"><?= verificarEmpty($row['cuenta'], 3); ?></td>
                    <td class="td-center" style="display: none;"><?= verificarEmpty($row['cci'], 3); ?></td>

                    <td class="text-center style-icons">
                        <span class="<?= $row['estadoIcono'] ?>" id="spanEstado-<?= $key; ?>"><?= $row['estado']; ?></span>
                    </td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>
<script>
    function getAllTextFromTable(tableID) {
        var table = document.getElementById(tableID);
        var rows = table.getElementsByTagName('tr'); // Esto obtiene todas las filas, incluso las no visibles.
        var data = [];

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var rowData = [];
            for (var j = 0; j < row.cells.length; j++) {
                if (j !== 1) {
                    rowData.push(row.cells[j].textContent.trim());
                }
            }
            data.push(rowData);
        }

        return data;
    }

    var allData = getAllTextFromTable('tb-proveedor');

    // Esta función crea y descarga el archivo Excel
    function exportToExcel(filename = 'tableData.xlsx') {
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
        headerRow.values = allData[0]; // Asume que la primera fila de allData contiene los encabezados
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
        allData.slice(1).forEach((rowData, index) => { // Empieza desde la segunda fila de datos
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