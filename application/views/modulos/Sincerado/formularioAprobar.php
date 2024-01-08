<form class="form" role="form" id="formAprobarSincerado" method="post" autocomplete="off">
    <div class="container">
        <div class="row">
            <div class="col-md-6" style="display: flex;align-items: center;justify-content: flex-end;">Total Original: </div>
            <div class="col-md-4">
                <input type="hidden" name="idSincerado" value="<?= $sincerado['idSincerado'] ?>">
                <input type="text" name="totalOriginal" class="form-control text-center" value="<?= $sincerado['totalOriginal'] ?>" disabled>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6" style="display: flex;align-items: center;justify-content: flex-end;">Total Sincerado: </div>
            <div class="col-md-4">
                <input type="text" name="totalSincerado" class="form-control text-center" value="<?= $sincerado['totalSincerado'] ?>" disabled>
            </div>
        </div>
    </div>
</form>