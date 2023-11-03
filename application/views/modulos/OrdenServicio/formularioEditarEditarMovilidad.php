
<table class="ui celled table">
    <thead>
        <tr>
        <th>ORIGEN</th>
        <th>DESTINO</th>
        <th>SPLIT</th>
        <th>PREC. BUS</th>
        <th>PREC. HOSPEDAJE</th>
        <th>PREC. VIATICOS</th>
        <th>PREC. MOV. INTER</th>
        <th>PREC. TAXI</th>
        <th>ESTADO</th>
        <th>GUARDAR</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($detalleMovilidad as $key => $v) : ?>
    <tr>

      <td><input type="text" class="form-control" id="up_origen_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['origen']; ?>"></td>
      <td><input type="text" class="form-control" id="up_destino_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['destino']; ?>"></td>
      <td>
        <select class="form-control" name="up_split_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>">
            <option <?php if ($v['split'] == 1): ?> selected <?php endif; ?> value="1">1 vez por mes</option>
            <option <?php if ($v['split'] == 2): ?> selected <?php endif; ?> value="2">1 vez cada 2 meses</option>
            <option <?php if ($v['split'] == 3): ?> selected <?php endif; ?> value="3">1 vez cada 3 meses</option>
        </select>
      </td>
      <td><input type="number" class="form-control" id="up_preBus_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['precioBus']; ?>"></td>
      <td><input type="number" class="form-control" id="up_preHosp_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['precioHospedaje']; ?>"></td>
      <td><input type="number" class="form-control" id="up_preVia_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['precioViaticos']; ?>"></td>
      <td><input type="number" class="form-control" id="up_preMov_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['precioMovilidadInterna']; ?>"></td>
      <td><input type="number" class="form-control" id="up_preTaxi_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>" value="<?php echo $v['precioTaxi']; ?>"></td>
      <td> 
        <div id='est_movili_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>'>
            <?php if ($v['estado'] == 1): ?>
                <span class="badge badge-success">Activo</span>
            <?php else: ?>
                <span class="badge badge-danger">Inactivo</span>
            <?php endif; ?>
        </div>
      </td>
      <td style="display:flex">
        <div id='upt_mov_<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>'>
        <a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.uptEstado_movilidad('<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>', '<?php echo $v['estado']; ?>');">
        <?php if ($v['estado'] == 1): ?>
            <i class="fal fa-lg fa-toggle-on"></i>
        <?php else: ?>
            <i class="fal fa-lg fa-toggle-off"></i>
        <?php endif; ?>
        </a>
        </div>
        <a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.save_udtMovilidadDetalle('<?php echo $v['idTipoPresupuestoDetalleMovilidad']; ?>');">
            <i class="fa fa-lg fa-save"></i>
        </a>
    </td>
    </tr>
   
    <?php endforeach; ?>   
  
    </tbody>
</table>


