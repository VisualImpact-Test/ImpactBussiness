<?php $cantidadFotos = count($itemFotos); ?>

<style>
    .carousel-indicators img {
            max-width: 100px;
            height: 50px;
            overflow: hidden;
            display: block;
        }

    .carousel-indicators li {
        height: auto;
        max-width: 100px;
        border: none;

        &.active {
            border-bottom: 4px solid #fff;
        }
    }
    .img-fotos{
        width:100% !important;
    }
</style>


<?php if ($cantidadFotos>0){?>
    <div id="carouselIndicators" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php $contador = 0;
            foreach ($itemFotos as $key => $item) {
                reset($itemFotos);
                $primeraIteracion = ($key === key($itemFotos)) ? true : false;
                $link = RUTA_WASABI . "item/{$item['nombre_archivo']}";
                $claseActiva = ($primeraIteracion) ? 'class="active"' : '';
            ?>
                <?php if ($cantidadFotos < 11) { ?>
                    <li data-target="#carouselIndicators" data-slide-to="<?= $contador ?>" <?= $claseActiva ?>> <img class="d-block w-150" src="<?= $link ?>" class="img-fluid"></li>
                <?php } else { ?>
                    <li data-target="#carouselIndicators" data-slide-to="<?= $contador ?>" <?= $claseActiva ?>></li>
                <?php } ?>

            <?php $contador++;
            } ?>
        </ol>
        
        <div class="carousel-inner">
            <?php foreach ($itemFotos as $key => $item) {
                $primeraIteracion = ($key === key($itemFotos)) ? true : false;
                $link = RUTA_WASABI . "item/{$item['nombre_archivo']}";
                $claseActiva = ($primeraIteracion) ? 'active' : '';
            ?>
                <div class="carousel-item <?= $claseActiva ?>">
                    <img src="<?= $link ?>" class="d-block img-fotos" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="m-0">Nombre: <?= $item['nombre_inicial'] ?></p>
                        <br>
                        <br>
                    </div>
                </div>
            <?php } ?>
            </div>
            <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
<?php } else { ?>
    <div class="alert alert-info" role="alert">
        <p class="text-center">No se encontro fotos.</p>
    </div>
<?php } ?>

    
         