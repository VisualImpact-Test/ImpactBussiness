<form  class="form" id="formUpdate">
    <input class="d-none" type="text" name="idElemento" value="<?= $data['idComprobante'] ?>">
        <div class="row">
            <div class="col-md-10 child-divcenter">
                <div class="control-group child-divcenter row w-100">
                    <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                    <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido" value="<?= $data['nombre'] ?>">
                </div>
            </div>
        </div>
</form>

<script>
    $('.my_select2').select2({
        dropdownParent: $("div.modal-content"),
        width: '100%'
    });
</script>