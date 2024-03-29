<div class="row itemData d-none" id="divItemData">
  <div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
    <div class="form-group col-md-6">
      <label class="font-weight-bold">Item:</label>
      <div class="input-group mb-3">
        <input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" onclick="Oc.editItemValue(this);"><i class="fa fa-edit"></i></button>
        </div>
      </div>
      <input class="codItems d-none" type='text' name='idItemForm'>
    </div>
    <div class="form-group col-md-6">
      <label class="font-weight-bold">Tipo:</label>
      <select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
        <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'tipo']); ?>
      </select>
    </div>
    <div class="form-row col-md-12 subItem"></div>
    <div class="form-row">
      <div class="form-group" onclick="Oc.generarSubItem(this, this.value);">
        <a class="form-control btn btn-info btnAdicionar"style="display:none;"><i class="fa fa-plus"></i> Adicionar</a>
      </div>
      <div class="form-group" onclick="Oc.quitarItem(this, this.value);">
        <a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
      </div>
    </div>
    <!-- <div class="form-group col-md-4">
      <label class="font-weight-bold">Caract. para Cliente:</label>
      <input class="form-control" name="caracteristica" patron="requerido">
    </div> -->

  </div>
  <div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
    <div class="form-group">
      <label class="font-weight-bold">Cantidad:</label>
      <input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
    </div>
    <div class="form-group d-none">
      <label class="font-weight-bold">CantidadSubItem:</label>
      <input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="0">
    </div>
    <div class="form-group">
      <label class="font-weight-bold">Costo:</label>
      <input class="form-control item_costo" name="costo" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="0">
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label class="font-weight-bold">GAP:</label>
        <input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="15">
      </div>
      <div class="form-group col-md-6">
        <label class="font-weight-bold">Sub Total:</label>
        <input class="form-control item_precio" name="precio" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);">
      </div>
    </div>
  </div>
</div>
<form class="form" role="form" id="formRegistroOC" method="post">
  <div class="row">
    <div class="col-md-12 child-divcenter">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">Datos Generales</legend>
        <div class="form-row pt-3">
          <div class="form-group col-md-4">
            <input type="hidden" name="idOper" value = "<?= $oc[0]['idOper'] ?>">
            <label class="font-weight-bold">Proveedor:</label>
            <select name="proveedor" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
              <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $proveedor, 'class' => 'text-titlecase', 'value' => 'razonSocial', 'id' => 'idProveedor']); ?>
            </select>
            <input type="hidden" name="idOc" value="">
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Cuenta:</label>
            <select class="form-control ui search dropdown parentDependiente" id="cuentaForm" name="cuentaForm" patron="requerido" data-childDependiente="cuentaCentroCostoForm">
                <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $cuenta, 'class' => 'text-titlecase', 'selected' => $oc[0]['idCuenta']]); ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Centro Costo:</label>
            <select class="form-control ui search dropdown simpleDropdown childDependiente clearable" id="cuentaCentroCostoForm" name="cuentaCentroCostoForm" patron="requerido">
                <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $centroCosto, 'class' => 'text-titlecase', 'selected' => $oc[0]['idCentroCosto']] ); ?>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Requerimiento:</label>
            <input class="form-control" name="requerimiento" patron="requerido" value="<?= $oc[0]['requerimiento'] ?>">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">PO Cliente:</label>
            <input class="form-control" name="poCliente" patron="requerido" value="<?= $oc[0]['poCliente'] ?>">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Metodo Pago:</label>
            <select name="metodoPago" patron="requerido" class="form-control ui fluid search clearable dropdown semantic-dropdown">
              <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $metodoPago, 'class' => 'text-titlecase', 'selected' => $oc[0]['idMetodoPago']]); ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Moneda:</label>
            <!-- Revisar https://fomantic-ui.com/modules/dropdown.html -->
            <div class="ui fluid search selection dropdown simpleDropdown semantic-dropdown ">
              <input type="hidden" name="moneda" value="" patron="requerido">
              <i class="dropdown icon"></i>
              <div class="default text">Moneda</div>
              <div class="menu">
                <?php foreach ($moneda as $value): ?>
                  <div class="item" data-value="<?= $value['idMoneda'] ?>">
                    <i class="<?= $value['icono'] ?>"></i><?= $value['nombre'] ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <!-- Fin Revisar -->
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Lugar de Entrega:</label>
            <input class="form-control" name="entrega" value="">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Fecha Entrega:</label>
            <input type="date" class="form-control" name="fechaEntrega" patron="requerido" value="<?= $oc[0]['fechaEntrega'] ?>">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Comentario:</label>
            <input class="form-control" name="comentario" value="">
          </div>
          <div class="form-group col-md-3">
            <label class="font-weight-bold">Concepto:</label>
            <input class="form-control" name="concepto" value="<?= $oc[0]['concepto'] ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-12">
            <label class="font-weight-bold">Observación:</label>
            <input class="form-control" name="observacion" value="<?= $oc[0]['observacion']?>">
          </div>
        </div>
      </fieldset>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 child-divcenter">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">Datos de Item<small>(s)</small></legend>
        <div class="extraItem">
          <?php foreach ($oc as $key => $value): ?>
            <div class="row itemData">
              <div class="form-row col-md-8 order-md-1 divItem pt-3 border-bottom">
                <div class="form-group col-md-6">
                  <label class="font-weight-bold">Item:</label>
                  <div class="input-group mb-3">
                    <input class="form-control items ui-autocomplete-input" type="text" name="item" patron="requerido" placeholder="Buscar item" autocomplete="off" value="<?= $value['item']?>" readonly>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" onclick="Oc.editItemValue(this);"><i class="fa fa-edit"></i></button>
                    </div>
                  </div>
                  <input class="codItems d-none" type='text' name='idItemForm' value="<?= $value['idItem']?>">
                </div>
                <div class="form-group col-md-6">
                  <label class="font-weight-bold">Tipo:</label>
                  <select class="form-control tipo clearSubItem item_tipo" name="tipo" patron="requerido" data-live-search="true">
                    <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $tipo, 'class' => 'text-titlecase', 'id' => 'idItemTipo', 'value' => 'tipo', 'selected' => $value['idTipo']]); ?>
                  </select>
                </div>
                <div class="form-row col-md-12 subItem">
                  <?php foreach ($ocSubItem[$value['idOperDetalle']] as $si_k => $si_v): ?>
                    <?php if ($value['idTipo'] == '2'): ?>
                      <div class="form-row subItemSpace col-md-12 border-bottom pt-2">
              					<div class="form-group col-md-6">
              						<label class="font-weight-bold">Descripción Serv.:</label>
              						<input class="form-control" name="subItem_nombre" patron="requerido" value="<?= $si_v['nombre']?>">
              					</div>
              					<div class="form-group col-md-6">
              						<label class="font-weight-bold">Cantidad:</label>
              						<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido" onchange="Oc.cantidadServicio(this);" onkeyup="Oc.cantidadServicio(this);" value="<?= $si_v['cantidad']?>">
              					</div>
              					<div class="d-none">
              						<input type="hidden" name="subItem_tipoServ" value="">
              						<input type="hidden" name="subItem_idUm" value="">
              						<input type="hidden" name="subItem_itemLog" value="">
              						<input type="hidden" name="subItem_talla" value="">
              						<input type="hidden" name="subItem_tela" value="">
              						<input type="hidden" name="subItem_color" value="">
              						<input type="hidden" name="subItem_costo" value="">
              						<input type="hidden" name="subItem_cantidadPdv" value="">
              						<input type="hidden" name="subItem_monto" value="">
              					</div>
              				</div>
                    <?php endif; ?>
                    <?php if ($value['idTipo'] == '7'): ?>
                      <div class="form-row subItemSpace col-md-12 border-bottom pt-2">
              					<div class="form-group col-md-6">
              						<label class="font-weight-bold">Item Logistica:</label>
                          <select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
                            <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label', 'selected' => $si_v['idItemLogistica']]); ?>
                          </select>
              					</div>
              					<div class="form-group col-md-3">
              						<label class="font-weight-bold">Peso:</label>
              						<input class="form-control cantidadSI" name="subItem_cantidad" patron="requerido"
              									onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
              									onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.costoSubItem').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
                                value="<?= $si_v['cantidad']?>"
              						>
              					</div>
              					<div class="form-group col-md-3">
              						<label class="font-weight-bold">Cantidad PDV:</label>
              						<input class="form-control cantidadPDV" name="subItem_cantidadPdv" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $si_v['cantidadPDV']?>">
              					</div>
              					<div class="form-group col-md-6">
              						<label class="font-weight-bold">Tipo Servicio:</label>
                          <select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
                            <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida'], 'selected' => $si_v['idTipoServicio']]); ?>
                          </select>
              					</div>
              					<div class="form-group col-md-3">
              						<label class="font-weight-bold">Unidad Medida:</label>
              						<input class="form-control umSubItem" name="subItem_um" patron="requerido" readonly  value="<?= $si_v['unidadMedida']?>">
              						<input type="hidden" class="form-control idUmSubItem" name="subItem_idUm" patron="requerido"  value="<?= $si_v['idUnidadMedida']?>">
              					</div>
              					<div class="form-group col-md-3">
              						<label class="font-weight-bold">Costo:</label>
              						<input class="form-control costoSubItem" name="subItem_costo" patron="requerido" readonly
              									onchange="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
              									onkeyup="$(this).closest('.itemData').find('.item_costo').val((parseFloat($(this).closest('.subItemSpace').find('.cantidadSI').val()||0) * parseFloat(this.value||0)).toFixed(2)).trigger('change')"
                                value="<?= $si_v['costo']?>"
              						>
              					</div>
              					<div class="d-none">
              						<input type="hidden" name="subItem_nombre" value="">
              						<input type="hidden" name="subItem_talla" value="">
              						<input type="hidden" name="subItem_tela" value="">
              						<input type="hidden" name="subItem_color" value="">
              						<input type="hidden" name="subItem_monto" value="">
              					</div>
              				</div>
                    <?php endif; ?>
                    <?php if ($value['idTipo'] == '9'): ?>
                      <div class="form-row subItemSpace col-md-12 border-bottom pt-2">
              					<div class="form-group col-md-1">
              						<label class="font-weight-bold">Talla:</label>
              						<input class="form-control" name="subItem_talla" patron="requerido" value="<?= $si_v['talla']?>">
              					</div>
              					<div class="form-group col-md-2">
              						<label class="font-weight-bold">Tela:</label>
              						<input class="form-control" name="subItem_tela" patron="requerido" value="<?= $si_v['tela']?>">
              					</div>
              					<div class="form-group col-md-2">
                					<label class="font-weight-bold">Color:</label>
                					<input class="form-control" name="subItem_color" patron="requerido" value="<?= $si_v['color']?>">
              					</div>
              					<div class="form-group col-md-2">
                					<label class="font-weight-bold">Cantidad:</label>
                					<input class="form-control SbItCantidad" name="subItem_cantidad" patron="requerido"
                								 onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
                								 onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCosto').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
                                 value="<?= $si_v['cantidad']?>"
                					>
              					</div>
              					<div class="form-group col-md-2">
              						<label class="font-weight-bold">Costo:</label>
              						<input class="form-control SbItCosto" name="subItem_costo" patron="requerido"
              									 onchange="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
              									 onkeyup="$(this).closest('.subItemSpace').find('.SbItSubTotal').val((parseFloat($(this).closest('.subItemSpace').find('.SbItCantidad').val() || 0) * parseFloat(this.value || 0)).toFixed(2)).trigger('change');"
                                 value="<?= $si_v['costo']?>"
              						>
              					</div>
              					<div class="form-group col-md-2">
              						<label class="font-weight-bold">Sb Tot:</label>
              						<input class="form-control SbItSubTotal" name="subItem_st" patron="requerido" readonly onchange="Oc.calcularTextilPrecio(this);"
                           value="<?= $si_v['cantidad'] * $si_v['costo']?>">
              					</div>
              					<div class="form-group col-md-1">
              						<label class="font-weight-bold" style="color: white;">:</label>
              						<a class="form-control btn btn-danger btn-removeSubItem"><i class="fa fa-trash"></i></a>
              					</div>
              					<div class="d-none">
              						<input type="hidden" name="subItem_tipoServ" value="">
              						<input type="hidden" name="subItem_idUm" value="">
              						<input type="hidden" name="subItem_itemLog" value="">
              						<input type="hidden" name="subItem_nombre" value="">
              						<input type="hidden" name="subItem_cantidadPdv" value="">
              						<input type="hidden" name="subItem_monto" value="">
              					</div>
              				</div>
                    <?php endif; ?>
                    <?php if ($value['idTipo'] == '10'): ?>
                      <div class="form-row subItemSpace col-md-12 border-bottom pt-2">
              					<div class="form-group col-md-12">
              						<label class="font-weight-bold">Monto:</label>
              						<input class="form-control" name="subItem_monto" patron="requerido" value="<?= $si_v['monto']?>">
              					</div>
              					<div class="d-none">
              						<input type="hidden" name="subItem_tipoServ" value="">
              						<input type="hidden" name="subItem_idUm" value="">
              						<input type="hidden" name="subItem_itemLog" value="">
              						<input type="hidden" name="subItem_nombre" value="">
              						<input type="hidden" name="subItem_talla" value="">
              						<input type="hidden" name="subItem_tela" value="">
              						<input type="hidden" name="subItem_color" value="">
              						<input type="hidden" name="subItem_costo" value="">
              						<input type="hidden" name="subItem_cantidad" value="">
              						<input type="hidden" name="subItem_cantidadPdv" value="">
              					</div>
              				</div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
                <div class="form-row">
                  <div class="form-group" onclick="Oc.generarSubItem(this, this.value);">
                    <?php
                      if($value['idTipo'] == '2' || $value['idTipo'] == '9' || $value['idTipo'] == '10'){
                        $display = '';
                      }else{
                        $display = 'style="display:none;"';
                      }
                    ?>
                    <a class="form-control btn btn-info btnAdicionar" <?= $display ?>><i class="fa fa-plus"></i> Adicionar</a>
                  </div>
                  <div class="form-group" onclick="Oc.quitarItem(this, this.value);">
                    <a class="form-control btn btn-danger"><i class="fa fa-trash"></i> Eliminar</a>
                  </div>
                </div>
                <!-- <div class="form-group col-md-4">
                <label class="font-weight-bold">Caract. para Cliente:</label>
                <input class="form-control" name="caracteristica" patron="requerido">
              </div> -->

            </div>
              <div class="col-md-4 order-md-2 pt-3 border-bottom itemValor">
                <div class="form-group">
                  <label class="font-weight-bold">Cantidad:</label>
                  <input class="form-control item_cantidad" name="cantidad" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['cantidad_item']?>">
                </div>
                <div class="form-group d-none">
                  <label class="font-weight-bold">CantidadSubItem:</label>
                  <input class="form-control cantidadSubItem" name="cantidadSubItem" patron="requerido" value="<?= count($ocSubItem[$value['idOperDetalle']]) ?>">
                </div>
                <div class="form-group">
                  <label class="font-weight-bold">Costo:</label>
                  <input class="form-control item_costo" name="costo" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['costo_item']?>">
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label class="font-weight-bold">GAP:</label>
                    <input class="form-control item_GAP" name="gap" patron="requerido" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['gap_item']?>">
                  </div>
                  <div class="form-group col-md-6">
                    <label class="font-weight-bold">Sub Total:</label>
                    <input class="form-control item_precio" name="precio" patron="requerido" onchange="Oc.cantidadPorItem(this);" onkeyup="Oc.cantidadPorItem(this);" value="<?= $value['csg_item']?>">
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </fieldset>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 child-divcenter">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">Datos Consolidados</legend>
        <div class="form-row pt-3">
          <div class="form-group col-md-4">
            <label class="font-weight-bold">IGV:</label>
            <select name="igvPorcentaje" patron="requerido" class="form-control" id="valorIGV" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();">
              <option <?= $value['IGVPorcentaje'] == '0' ? 'selected' : ''; ?> value="100">No incluir IGV</option>
              <option <?= $value['IGVPorcentaje'] == '18' ? 'selected' : ''; ?> value="118">Incluir IGV</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Total:</label>
            <input class="form-control" name="total" patron="requerido" id="total" onchange="Oc.cantidadTotal();" onkeyup="Oc.cantidadTotal();" value="<?= $value['total'] ?>">
          </div>
          <div class="form-group col-md-4">
            <label class="font-weight-bold">Total:</label>
            <input class="form-control" name="totalIGV" patron="requerido" id="totalFinal" readOnly value="<?= $value['totalFeeIGV'] ?>">
          </div>
        </div>
      </fieldset>
    </div>
  </div>
</form>
<input id="itemsData" type="hidden" value='<?= json_encode($item) ?>'>
<div class="d-none" id="divItemLogistica">
  <select class="form-control itemLogistica" name="subItem_itemLog" patron="requerido" data-live-search="true">
    <?= htmlSelectOptionArray2(['simple' => 1, 'query' => $itemLogistica, 'class' => 'text-titlecase', 'id' => 'value', 'value' => 'label']); ?>
  </select>
</div>
<div class="d-none" id="divTipoServicio">
  <select class="form-control tipoServicio" name="subItem_tipoServ" patron="requerido" data-live-search="true">
    <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $tipoServicios, 'class' => 'text-titlecase', 'data-option' => ['costo', 'unidadMedida', 'idUnidadMedida']]); ?>
  </select>
</div>
<script>
    setTimeout(function() {
        $('.my_select2').select2();
    }, 500);
</script>
