<form class="form" role="form" id="formFechaSincerado" method="post" autocomplete="off">
    <div class="fields">
        <div class="six wide field">
            <input type="hidden" name ="idPresupuestoValido" value="<?php echo $idPresupuestoValido ?>">
            <select class="ui dropdown parentDependiente centro-visible" id="fechaSincerado" name="fechaSincerado" patron="requerido">
                <?= htmlSelectOptionArray2(['title' => 'Seleccione', 'query' => $fechaSincerado, 'id' => 'value' , 'simple' => true, 'class' => 'text-titlecase']); ?>
            </select>
        </div>
    </div>
</form>