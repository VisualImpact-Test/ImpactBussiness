var gestionConfiguracion = {
    idModalPrincipal : 1,
    url : 'Configuracion/comprobante/',
    salesforce: {
        lista : 'getLista',
        nuevoFormulario : 'getFormNew',
        registrar : 'store',
        editarFormulario : 'getFormUpdate',
        actualizar : 'update',
        cambiarEstado : 'cambiarEstado',
        cargarMasivo : 'getFormCargaMasiva',
        formExcel : 'formExcel',
        importExcel :'importExcel',
        historial : 'historial',
        eliminarMasivo : 'eliminarMasivo',
    },
    detalleContent: 'content',
    $dataTable: [],
    columnaOrdenDT: '',
    seccionActivo: '',
    idFormSeccionActivo : '',
    Objects : [],
    Nombres : [],
    ObjectsFeatures : [],

    load: function(){
        $(document).ready(function (e) {
            gestionConfiguracion.eventos();
            $('.btn-Consultar').click();
        });
    },
    // validarRegistro : function (){},
    actualizar: function () {
        var data = Fn.formSerializeObject('formUpdate');
        var jsonString = { 'data': JSON.stringify(data) };
        var config = { url: gestionConfiguracion.url + gestionConfiguracion.salesforce.actualizar, 'data': jsonString };
        $.when(Fn.ajax(config)).then(function (a) {
            if (a.result === 2) return false;
            if (typeof a.data.validaciones !== null) $.mostrarValidaciones('formUpdate', a.data.validaciones);
            ++modalId;
            var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
            if (a.result == 1) fn += 'Fn.showModal({ id:' + gestionConfiguracion.idModalPrincipal + ',show:false });$(".btn-Consultar").click();';
            var btn = [];
            btn[0] = { title: 'Cerrar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
        });
    },
    registrar: function () {
        var data = Fn.formSerializeObject('formNew');
        var jsonString = { 'data': JSON.stringify(data) };
        var config = { url: gestionConfiguracion.url + gestionConfiguracion.salesforce.registrar, 'data': jsonString };
        $.when(Fn.ajax(config)).then(function (a) {
            if (a.result === 2) return false;
            if (typeof a.data.validaciones !== null) $.mostrarValidaciones('formNew', a.data.validaciones);
            ++modalId;
            var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
            if (a.result == 1) fn += 'Fn.showModal({ id:' + gestionConfiguracion.idModalPrincipal + ',show:false });$(".btn-Consultar").click();';
            var btn = [];
            btn[0] = { title: 'Cerrar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
        });
    },
    cambiarEstado: function (data) {
        var jsonString = { 'data': JSON.stringify(data) };
        var config = { url: gestionConfiguracion.url + gestionConfiguracion.salesforce.cambiarEstado, data: jsonString };

        $.when(Fn.ajax(config)).then(function (a) {

            if (a.result === 2) return false;
            ++modalId;
            var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
            if (a.result == 1) fn += 'Fn.showModal({ id:' + gestionConfiguracion.idModalPrincipal + ',show:false });$(".btn-Consultar").click();';
            var btn = [];
            btn[0] = { title: 'Cerrar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
        });
    },
    subirExcel: function (){
        $('#mensajeExcel').html('');
        $('#mensajeInicio').html('');
        $('#mensajeFin').html('');
        if ($('#excel').val().length == 0 || $('#inicio').val() == '' || $('#fin').val() == ''){
            if ($('#excel').val().length == 0 ){
                $('#mensajeExcel').html('Debe subir un archivo.');
            }
            if ($('#inicio').val() == '' ){
                $('#mensajeInicio').html('Debe seleccionar una fecha.');
            }
            if ($('#fin').val() == '' ){
                $('#mensajeFin').html('Debe seleccionar una fecha.');
            }
        }else {
            let fechaInicio = $('#inicio').val();
            let fechaFin = $('#fin').val();
            let inicio = fechaInicio.split('-');
            let fin = fechaFin.split('-');
            let fecha1 =inicio[1]+'-'+inicio[2]+'-'+inicio[0];
            let fecha2 =fin[1]+'-'+fin[2]+'-'+fin[0];
            if (Date.parse(fechaInicio)>Date.parse(fecha2)){
                $('#mensajeInicio').html('Esta fecha no puede ser mayor que el final.');
                $('#mensajeFin').html('Esta fecha no puede ser menor que el inicio.');
            }else {
                let file = $('#excel')[0].files[0];
                let nameFile = file.name;
                let extension = Fn.getExtension(nameFile);
                if (extension != 'xlsx'){
                    $('#mensajeExcel').html('Debe subir un archivo excel válido.')
                }else {
                    $('#mensajeExcel').html('')
                    var formData = new FormData();
                    formData.append('file', $('#excel')[0].files[0]);
                    formData.append('fechaInicio',fechaInicio );
                    formData.append('fechaFin', fechaFin);
                    var config = { url: gestionConfiguracion.url + gestionConfiguracion.salesforce.importExcel, 'data': formData };
                    $.when(Fn.ajaxFormData(config)).then(function (a) {
                        if (a.result === 2) return false;
                        ++modalId;
                        var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
                        if (a.result == 1) fn += 'Fn.showModal({ id:' + gestionConfiguracion.idModalPrincipal + ',show:false });$(".btn-Consultar").click();';
                        var btn = [];
                        btn[0] = { title: 'Cerrar', fn: fn };
                        Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
                    });}
            }

        }

    },
    eliminarMasivo : function (data){
        var jsonString = { 'data': JSON.stringify(data) };
        var config = { url: gestionConfiguracion.url + gestionConfiguracion.salesforce.eliminarMasivo, data: jsonString };
        $.when(Fn.ajax(config)).then(function (a) {
            if (a.result === 2) return false;
            ++modalId;
            var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

            if (a.result == 1) fn += 'Fn.showModal({ id:' + gestionConfiguracion.idModalPrincipal + ',show:false });$(".btn-HistorialExcel").click();';

            var btn = [];
            btn[0] = { title: 'Cerrar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: a.msg.title, btn: btn, frm: a.msg.content });
        });
    },
    eventos: function(){
        $(document).on('click','.btn-Consultar', function(e){
            e.preventDefault();
            e.stopPropagation();

            var config = {
                'url': gestionConfiguracion.url + gestionConfiguracion.salesforce.lista
            };
            $.when(Fn.ajax(config)).then( (res) => {
                $('#' + gestionConfiguracion.detalleContent).html(res.data.html);

                gestionConfiguracion.$dataTable[gestionConfiguracion.detalleContent] = $('#' + gestionConfiguracion.detalleContent + ' table').DataTable({
                    columnDefs: [
                        { targets: [0], visible: false, className: 'select-checkbox text-center' },
                        { targets: [ 1],  className: 'text-center' },
                        { targets: [ 2], searchable: false, orderable: false, className: 'text-center' },
                        { targets: [-1, -2, -3], className: 'text-center' },
                        { targets: 'colNumerica', className: 'text-center' },
                    ],
                    select: { style: 'os', selector: 'td:first-child' },
                    exportOptions: {
                        columns: ':not(.excel-borrar)'
                    }


                });
                gestionConfiguracion.columnaOrdenDT =1;
                gestionConfiguracion.$dataTable[gestionConfiguracion.detalleContent].on('order.dt search.dt', function () {
                    gestionConfiguracion.$dataTable[gestionConfiguracion.detalleContent].column(gestionConfiguracion.columnaOrdenDT, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {

                    });
                });
                gestionConfiguracion.$dataTable[gestionConfiguracion.detalleContent].draw();
            });

        });
        $(document).on('click','.btn-New',function (e){
            e.preventDefault();
            e.stopPropagation();
            var config = { 'url': gestionConfiguracion.url + gestionConfiguracion.salesforce.nuevoFormulario};
            $.when(Fn.ajax(config)).then(function (a) {
                if (a.result === 2) return false;
                ++modalId;
                gestionConfiguracion.idModalPrincipal = modalId;
                var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
                var fn1 = 'gestionConfiguracion.validarRegistro();';


                var btn = [];
                btn[0] = { title: 'Cerrar', fn: fn };
                btn[1] = { title: 'Registrar', fn: fn1 };
                Fn.showModal({ id: modalId, show: true, class: 'modalNew', title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });

            });
        });
        $(document).on("click", '.btn-Editar', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var data = { 'id': $(this).closest('tr').data('id')};
            Object.assign(data);

            var jsonString = { 'data': JSON.stringify(data) };

            var config = { 'url': gestionConfiguracion.url + gestionConfiguracion.salesforce.editarFormulario, 'data': jsonString };

            $.when(Fn.ajax(config)).then(function (a) {

                if (a.result === 2) return false;

                ++modalId;
                gestionConfiguracion.idModalPrincipal = modalId;
                var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
                var fn1 = 'gestionConfiguracion.validarActualizacion()';

                var btn = [];
                btn[0] = { title: 'Cerrar', fn: fn };
                btn[1] = { title: 'Actualizar', fn: fn1 };
                Fn.showModal({ id: modalId, show: true, class: 'modalUpdate', title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
            });
        });
        $(document).on('click', '.btn-CambiarEstado', function (e) {
            e.preventDefault();
            e.stopPropagation();
            ++modalId;
            gestionConfiguracion.idModalPrincipal = modalId;

            //  var seccionActivo = Gestion.seccionActivo;
            var id = $(this).closest('tr').data('id');
            var estado = $(this).closest('tr').data('estado');
            let nuevoEstado = estado === 0 ? 1 :0;

            var btn = [];
            var data = { id: id, estado: nuevoEstado };
            var mensajeEstado = (estado == 0) ? 'Activar' : 'Desactivar';

            var fn0 = 'Fn.showModal({ id:' + modalId + ',show:false });';
            var fn1 = 'gestionConfiguracion.cambiarEstado(' + JSON.stringify(data) + ');';
            btn[0] = { title: 'No', fn: fn0 };
            btn[1] = { title: 'Sí, '+mensajeEstado, fn: fn1 };

            Fn.showModal({ id: modalId, show: true, title: 'Activar/Desactivar', frm: '¿Desea <strong>' + mensajeEstado + '</strong> el registro seleccionado?', btn: btn });
        });
        $(document).on('click','.btn-Excel', function (e){
            e.preventDefault();
            e.stopPropagation();
            ++modalId;
            gestionConfiguracion.idModalPrincipal = modalId;
            var config = { 'url': gestionConfiguracion.url + gestionConfiguracion.salesforce.formExcel};
            $.when(Fn.ajax(config)).then(function (a) {

                if (a.result === 2) return false;
                ++modalId;
                gestionConfiguracion.idModalPrincipal = modalId;
                var fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
                var fn1='gestionConfiguracion.subirExcel();';

                var btn = [];
                btn[0] = { title: 'Cerrar', fn: fn };
                btn[1] = { title: 'Importar', fn: fn1 };
                Fn.showModal({ id: modalId, show: true, class: 'modalNew', title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
            });
        });
        $(document).on('click','.btn-HistorialExcel',function (e){
            e.preventDefault();
            e.stopPropagation();
            var config = { 'url': gestionConfiguracion.url + gestionConfiguracion.salesforce.historial};
            $.when(Fn.ajax(config)).then(function (a) {

                if (a.result === 2) return false;
                ++modalId;
                gestionConfiguracion.idModalPrincipal = modalId;
                var fn = 'Fn.showModal({ id:' + modalId + ',show:false });$(".btn-Consultar").click();';

                var btn = [];
                btn[0] = { title: 'Cerrar', fn: fn };
                Fn.showModal({ id: modalId, show: true, class: 'modalNew', title: a.msg.title, frm: a.data.html, btn: btn, width: a.data.width });
            });
        });
        $(document).on("click", '.btnEliminar', function (e) {
            e.preventDefault();
            e.stopPropagation();
            data = $(this).data('id');
            Fn.showConfirm({ fn:"gestionConfiguracion.eliminarMasivo("+data+")",content:"¿Esta seguro de eliminar este conjunto de datos?" });

        });
    }

};

gestionConfiguracion.load();
