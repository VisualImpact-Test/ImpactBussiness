<form id="frmFeatures">
    <div class="ui form ">
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui sub header">Adjunte archivos</div>
                <div class="ui action input">
                    <input type="text" placeholder="Adjuntar" readonly>
                    <input type="file" accept="image/*,.pdf" multiple>
                    <div class="ui icon button">
                        <i class="attach icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="fields">
            <div class="sixteen wide field">
                <div class="ui sub header">Links</div>
                <div class="ui fluid multiple search selection dropdown dropdownLink">
                    <input name="link" type="hidden">
                    <i class="dropdown icon"></i>
                    <div class="default text">Insertar Link</div>
                    <div class="menu">

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $("input:text").click(function() {
        $(this).parent().find("input:file").click();
    });

    $('input:file')
        .on('change', function(e) {
            var name = '';
            $.each(e.target.files, function(i, x) {
                name += `${x.name}, `;
            });
            $('input:text', $(e.target).parent()).val(name);
            console.log(e.target.files);
        });

    $('.dropdownLink')
        .dropdown({
            allowAdditions: true
        });
</script>