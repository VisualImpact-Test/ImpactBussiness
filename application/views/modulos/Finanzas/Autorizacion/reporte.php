<style>

</style>
<div class="card-datatable">
    <table id="tb-autorizacion" class="ui celled table" width="100%">
        <thead>
            <tr>
                <th class="td-center">#</th>
                <th class="td-center">OPCIONES</th>
                <th>TIPO AUTORIZACION</th>
                <th>ESTADO AUTORIZACION</th>
                <th>COD COTIZACION</th>
                <th>COMENTARIO</th>
                <th>SOLICITANTE</th>
                <th>FECHA CREACION</th>
                <th>FECHA MODIFICACION</th>
            </tr>
        </thead>
        <tbody>
            <? $ix = 1; ?>
            <?
            foreach ($data as $key => $row) {
            ?>
                <tr data-id="<?= $row['idAutorizacion'] ?>">
                    <td class="td-center"><?= $ix; ?></td>
                    <td class="td-center style-icons">
                        <a href="javascript:;" class="btn btn-outline-secondary border-0 btn-editar" title="Actualizar Autorización"><i class="fa fa-lg fa-edit"></i></a>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['tipoAutorizacion'], 3); ?></td>
                    <td class="td-left"> 
                        <span></span>
                        <?= verificarEmpty($row['estadoAutorizacion'], 3); ?>
                    </td>
                    <td class="td-left"><?= verificarEmpty($row['codCotizacion'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['comentario'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['usuario'], 3); ?></td>
                    <td class="td-left"><?= verificarEmpty($row['fechaCreacion'], 3); ?></td>
                    <td class="td-center"><?= verificarEmpty($row['fechaModificacion'], 3); ?></td>
                </tr>
            <? $ix++;
            } ?>
        </tbody>
    </table>
</div>
