<div class="ui form attached fluid segment p-4">
    <form class="ui form" role="form" id="formRegistroOper" method="post">
        <h4 class="ui dividing header">DATOS DEL OPER</h4>
        <input type="hidden" name="totalOper" value="<?= $totalOper?>">
        <div class="fields d-none">
            <div class="six wide field">
                <div class="ui sub header">De:</div>
                <input type="text" value="<?= $this->usuario_completo ?>" readonly>
                <input type="hidden" name="idUsuario" id="" value="<?= $this->idUsuario ?>">
            </div>
            <div class="six wide field">
                <div class="ui sub header">Dirigido a:</div>
                <select name="receptor" class="ui fluid search clearable dropdown simpleDropdown" patron="requerido">
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $usuarios, 'class' => 'text-titlecase', 'id' => 'idUsuario', 'selected' => $this->idUsuario]); ?>
                </select>
            </div>
            <div class="four wide field">
                <div class="ui sub header">N Requerimiento</div>
                <input type="text" name="requerimiento" placeholder="Num. Requerimiento" value="" patron="" readonly>
            </div>
        </div>
        <div class="fields disabled disabled-visible">
            <div class="four wide field">
                <div class="ui sub header">Dirigido a:</div>
                <input type="text" value="Coordinadora de compras" readonly>
            </div>
            <div class="six wide field">
                <div class="ui sub header">Cuenta</div>
                <select class="ui search dropdown simpleDropdown" id="cuentaForm" name="cuentaForm" patron="requerido" multiple>
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selectAll' => true]); ?>
                </select>
            </div>
            <div class="six wide field">
                <div class="ui sub header">Centro de costo</div>
                <select class="ui search dropdown simpleDropdown  clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido" multiple>
                    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuentaCentroCosto, 'class' => 'text-titlecase', 'selectAll' => true]); ?>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="eight wide field">
                <div class="ui sub header">Fecha requerimiento</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Fecha Requerimiento" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="fechaRequerida" placeholder="Fecha de requerimiento" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="eight wide field">
                <div class="ui sub header">Probable fecha de entrega</div>
                <div class="ui calendar date-semantic">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Fecha Requerida" value="">
                    </div>
                </div>
                <input type="hidden" class="date-semantic-value" name="fechaEntrega" placeholder="Fecha de entrega" value="">
            </div>
        </div>
        <h4 class="ui dividing header">
            DETALLE COTIZACIONES 
        </h4>
        <? foreach ($cotizaciones as $row) { ?>
            <input type="hidden" name="idCotizacion" value="<?=$row['idCotizacion']?>">
            <div class="default-item">
                <div class="ui segment body-item nuevo">
                    <div class="ui left floated header">
                        <span class="ui medium text "><?= $row['cotizacion'] ?> <span class="title-n-detalle"><?= !empty($row['codCotizacion']) ? $row['codCotizacion'] : '' ?></span></span>
                    </div>
                    <!-- <div class="ui right floated header">
                        <input class=" totalForm" type="hidden" name="totalForm" placeholder="0.00" value="<?= !empty($row['total']) ? $row['total'] : '' ?>" readonly>
                        <a class="item">
                            <div class="ui green horizontal label">Total </div>
                            <?= !empty($row['total']) ? moneda($row['total']) : '0.00' ?>
                        </a>
                    </div> -->
                    <div class="ui clearing divider"></div>
                    <? foreach ($detalle[$row['idCotizacion']] as $rowDetalle) { ?>
                        <div class="ui grid">
                            <div class="sixteen wide column">
                                <div class="fields">
                                    <div class="five wide field">
                                        <div class="ui sub header">Item</div>
                                        <input class="items" type='text' name='nameItem' value="<?= $rowDetalle['item'] ?>" patron="requerido" placeholder="" readonly>
                                    </div>
                                    <div class="three wide field">
                                        <div class="ui sub header">Cantidad</div>
                                        <input class="form-control " type="number" name="cantidadForm" placeholder="0" value="<?= $rowDetalle['cantidad'] ?>" patron="requerido,numerico" min="1" step="1"  readonly onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    <div class="four wide field">
                                        <div class="ui sub header">Costo</div>
                                        <div class="ui right labeled input">
                                            <label for="amount" class="ui label">S/</label>
                                            <input class="costoForm" type="text" name="costoForm" placeholder="0.00" value="<?= moneda($rowDetalle['costo']) ?>" readonly >
                                        </div>
                                    </div>
                                    <div class="four wide field">
                                        <div class="ui sub header">Subtotal</div>
                                        <div class="ui right labeled input">
                                            <label for="amount" class="ui label teal">S/</label>
                                            <input class=" subtotalFormLabel" type="text" placeholder="0.00" value="<?= moneda($rowDetalle['subTotal']) ?>" readonly>
                                            <input class=" subtotalForm" type="hidden" name="subtotalForm" value="<?= $rowDetalle['subTotal'] ?>" placeholder="0.00" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? } ?>

                </div>
            </div>
        <? } ?>
    </form>
</div>