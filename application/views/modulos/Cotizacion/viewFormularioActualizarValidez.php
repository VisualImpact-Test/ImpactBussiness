<div class="container">
    <form class="form" role="form" id="formActualizarValidez" method="post" autocomplete="off">
        <div class="row">
            <!-- Dias de Validez -->
            <div class="col-md-12">
                <div class="form-group">
                    <label for="diasValidez">Días de Validez</label>
                    <?php 
                    $fechaObjeto = new DateTime($diasValidez[0]['fechaReg']);
                    $fechaReg = $fechaObjeto->format("m/d/Y");
                    ?>
                    <input type="number" class="form-control" id="diasValidez" name="diasValidez" value="<?= verificarEmpty($diasValidez[0]['diasValidez'], 3); ?>">
                    <input type="number" class="form-control d-none" id="idCotizacion" name="idCotizacion" value="<?= $idCotizacion; ?>">
                    <input type="text" class="form-control d-none" id="fechaReg" name="fechaReg" value="<?= verificarEmpty($fechaReg); ?>">
                </div>
            </div>

            <!-- Fecha Final -->
            <div class="col-md-12">
                <div class="form-group">
                    <label for="fechaFinal">Fecha Final</label>
                    <input type="text" class="form-control" id="fechaFinal" name="fechaFinal" readonly>
                </div>
            </div>
        </div>
    </form>
</div>
