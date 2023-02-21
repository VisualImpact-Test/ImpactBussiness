gestionConfiguracion.url = 'Configuracion/Comprobante/';

gestionConfiguracion.validarRegistro =  function (){
    Validar('registrar','formNew');
};
gestionConfiguracion.validarActualizacion =  function (){
    Validar('actualizar','formUpdate');
};


function Validar (tipo,formulario) {
    var data = Fn.formSerializeObject(formulario);
    let estado = true;
    $.each(data, function (index, value){
        $('#msj-'+index).html('');
    });

    if ( !Fn.validators.requerido.expr.test(data.nombre)){
        $('#msj-nombre').html('Debe llenar este campo');
        estado = false;
    }


    if (estado){
        let ruta = "gestionConfiguracion."+tipo+"()";

        Fn.showConfirm({ fn: ruta,content:"¿Esta seguro de "+tipo+" los datos?" });
    }
}