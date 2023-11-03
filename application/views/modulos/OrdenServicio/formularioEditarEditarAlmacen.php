
<table class="ui celled table">
    <thead>
        <tr>
        <th>ZONA</th>
        <th>ZONA2</th>
        <th>CIUDAD</th>
        <th>ESTADO</th>
        <th>GUARDAR</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($detalleAlmacen as $key => $v) : ?>
    <tr>
      <td><input type="text" class="form-control" id="up_zona_<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>" value="<?php echo $v['zona']; ?>"></td>
      <td><input type="text" class="form-control" id="up_zona2_<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>" value="<?php echo $v['zona2']; ?>"></td>
      <td><input type="text" class="form-control" id="up_ciudad_<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>" value="<?php echo $v['ciudad']; ?>"></td>
      <td> 
        <div id='est_almacen_<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>'>
            <?php if ($v['estado'] == 1): ?>
                <span class="badge badge-success">Activo</span>
            <?php else: ?>
                <span class="badge badge-danger">Inactivo</span>
            <?php endif; ?>
        </div>
      </td>
      <td style="display:flex">
        <div id='upt_almacen_<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>'>
        <a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.uptEstado_almacenDetalle('<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>', '<?php echo $v['estado']; ?>');">
            <?php if ($v['estado'] == 1): ?>
                <i class="fal fa-lg fa-toggle-on"></i>
            <?php else: ?>
                <i class="fal fa-lg fa-toggle-off"></i>
            <?php endif; ?>
        </a>
        </div>
        <a href="javascript:;" class="btn btn-outline-secondary border-0" onclick="OrdenServicio.save_almacenDetalle('<?php echo $v['idTipoPresupuestoDetalleAlmacen']; ?>');">
            <i class="fa fa-lg fa-save"></i>
        </a>
    </td>
    </tr>
   
    <?php endforeach; ?>   
  
    </tbody>
</table>


