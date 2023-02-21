<form id="formNew">
    <div class="row">
        <div class="col-md-10 child-divcenter">
            <div class="control-group child-divcenter row w-100">
                <label class="form-control col-md-5" for="nombre" style="border:0px;">Nombre :</label>
                <input class="form-control col-md-7" id="nombre" name="nombre" patron="requerido">
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