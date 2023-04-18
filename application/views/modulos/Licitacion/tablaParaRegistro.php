<table class="ui definition table" id="tablaFechaPersona">
    <thead>
        <tr>
            <th></th>
            <?php for ($fechaContador = 0; $fechaContador < $nroFecha; $fechaContador++) : ?>
                <th>
                    <label>-</label>
                    <div class="ui calendar date-semantic">
                        <div class="ui button left icon w-100">
                            <i class="calendar icon"></i>
                            Fecha <?= $fechaContador + 1; ?>
                        </div>
                    </div>
                    <input type="hidden" class="date-semantic-value" name="fecha[<?= $fechaContador; ?>]" value="" patron="requerido">
                </th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <?php for ($personaContador = 0; $personaContador < $nroPersona; $personaContador++) : ?>
            <tr>
                <td>
                    <div class="ui dropdown floating labeled search icon button boton-dropdown">
                        <i class="user icon"></i>
                        <span class="text">Persona</span>
                        <div class="menu">
                            <?php foreach ($persona as $key => $value): ?>
                                <div class="item" data-value="<?= $value['id']; ?>"><?= $value['nombre']; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" onchange="Licitacion.changePersona(this, <?= $personaContador; ?>);" class="date-semantic-value" name="persona[<?= $personaContador; ?>]" value="" patron="requerido">
                </td>
                <?php for ($fechaContador = 0; $fechaContador < $nroFecha; $fechaContador++) : ?>
                    <td> <input type="text" class="form-control" patron="requerido" name="cantidad[<?= $personaContador; ?>][<?= $fechaContador; ?>]"> </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>