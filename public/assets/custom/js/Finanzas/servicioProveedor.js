var ServicioProveedor = {
    frm: 'frm-servicioProveedor',
    contentDetalle: 'idServicioProveedor',
    url: 'Finanzas/ServicioProveedor/',
    idEliminados :[],
    provincia: {},
	distrito: {},
    load: function () {
        $(document).on('dblclick', '.card-body > ul > li > a', function (e) {
            $('#btn-filtrarServicioProveedor').click();
        });
        $(document).ready(function () {
            $('#btn-filtrarServicioProveedor').click();
        });
        $(document).on('click', '#btn-filtrarServicioProveedor', function () {
            var ruta = 'reporte';
            var config = {
                'idFrm': ServicioProveedor.frm
                , 'url': ServicioProveedor.url + ruta
                , 'contentDetalle': ServicioProveedor.contentDetalle
            };
            // console.log(config);
            Fn.loadReporte_new(config);
            Fn.showLoading(false);
        });

        $(document).on('click', '.btn-actualizar-estado', function () {
            ++modalId;

            let idProveedorServicio = $(this).data('id');
            let estado = $(this).data('estado');
            let data = { 'idProveedorServicio': idProveedorServicio, 'estado': estado };
            let jsonString = { 'data': JSON.stringify(data) };
            let config = { 'url': ServicioProveedor.url + 'actualizarEstadoProveedorServicio', 'data': jsonString };

            $.when(Fn.ajax(config)).then((a) => {
                $("#btn-filtrarServicioProveedor").click();
            });
        });

        $(document).on('click', '#btn-proveedor', function () {
            ++modalId;

            let jsonString = { 'data': '' };
            let config = { 'url': ServicioProveedor.url + 'formularioRegistroProveedorServicio', 'data': jsonString };

            $.when(Fn.ajax(config)).then((a) => {
                let btn = [];
                let fn = [];

                fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
                btn[0] = { title: 'Cerrar', fn: fn[0] };
                fn[1] = 'Fn.showConfirm({ idForm: "formRegistroProveedorServicio", fn: "ServicioProveedor.registrarProveedorServicio()", content: "¿Esta seguro de registrar el Servicio Proveedor?" });';
                btn[1] = { title: 'Registrar', fn: fn[1] };

                Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
                Fn.loadSemanticFunctions();
                Fn.loadDimmerHover();
                ServicioProveedor.provincia = a.data.provincia;
				ServicioProveedor.distrito = a.data.distrito;
              //  console.log(ServicioProveedor.provincia);
            });
        });
        
        $(document).on('click', '#btn-añadir-proveedor', function () {
            var nomContacto = $('#nomContacto').val();
            var telContacto = $('#telContacto').val();
            var correoContacto = $('#correoContacto').val();

            html='';
            html +='<tr><td><input class="form-control" name="nomContactoinput" value="'+nomContacto+'" readonly></td><td><input class="form-control" name="telContactoinput" value="'+telContacto+'" readonly></td><td><input class="form-control" name="correoContactoimput" value="'+correoContacto+'" readonly></td>';
            html +='<td><a class="ui red  label elimnaRegistro"><i class="trash icon m-0"></i></a></td></tr>'
            console.log(html);
            $('#tb-contacProveedores tbody').append(html);
            $('#nomContacto').val("");
            $('#telContacto').val("");
            $('#correoContacto').val("");
           
        });
        $(document).on('click', '.elimnaRegistro', function () {
            let id = $(this).parents('tr:first').data('id');
            //console.log(id);
            if (id) {
                ServicioProveedor.idEliminados.push(id); 
            }
            $(this).closest('tr').remove();
            console.log(ServicioProveedor.idEliminados);
        });

        $(document).on('click', '.btn-editar', function () {
            ++modalId;
            ServicioProveedor.idEliminados = [];
            let id = $(this).parents('tr:first').data('id');
            let data = { 'idProveedorServicio': id };

            let jsonString = { 'data': JSON.stringify(data) };
            let config = { 'url': ServicioProveedor.url + 'formularioActualizacionServicioProveedor', 'data': jsonString };

            $.when(Fn.ajax(config)).then((a) => {
                let btn = [];
                let fn = [];

                fn[0] = 'Fn.showModal({ id:' + modalId + ',show:false });';
                btn[0] = { title: 'Cerrar', fn: fn[0] };
                fn[1] = 'Fn.showConfirm({ idForm: "formActualizarProveedorServicio", fn: "ServicioProveedor.actualizarServicioProveedor()", content: "¿Esta seguro de actualizar el Servicio Proveedor?" });';
                btn[1] = { title: 'Actualizar', fn: fn[1] };

                Fn.showModal({ id: modalId, show: true, title: a.msg.title, frm: a.data.html, btn: btn, width: '50%' });
               
                ServicioProveedor.provincia = a.data.provincia;
				ServicioProveedor.distrito = a.data.distrito;
				

            });

        });
        $(document).on('change', '#numDocumento', function (e) {
            var tipoDocumento = $('#tipoComprobante').val();
            var numDocumento = $(this).val();
            // console.log(numDocumento);
            // console.log(tipoDocumento);
            if (tipoDocumento == 1 && numDocumento.length === 8) {
                $.ajax({
                    url: `https://dniruc.apisperu.com/api/v1/dni/${numDocumento}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        // Asume que la API devuelve un objeto con las propiedades adecuadas
                        $('#datProveedor').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });


              } else if (tipoDocumento == 3 && numDocumento.length === 11) {
                $.ajax({
                    url: `https://dniruc.apisperu.com/api/v1/ruc/${numDocumento}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        // Asume que la API devuelve un objeto con la propiedad 'razonSocial'
                        $('#datProveedor').val(data.razonSocial);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
              } else {
                console.log('Documento inválido');
              }

              let data = { 'numDocumento': numDocumento };
              let jsonString = { 'data': JSON.stringify(data) };
              let config = { 'url': ServicioProveedor.url + 'verificarNumDocumento', 'data': jsonString };
  
              $.when(Fn.ajax(config)).then((a) => {
                ++modalId;
                var btn = [];
                let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';
              
                btn[0] = { title: 'Continuar', fn: fn };
                if (a.result != 1) {
                     Fn.showModal({ id: modalId, show: true, title: a.msg.title, content: a.msg.content, btn: btn, width: '40%' });
                    // console.log(a);
                }
               
              });
              
        });

        $(document).on('change', '#tipoComprobante', function (e) {
            var tipoComprobante = $(this).val();
           
            if (tipoComprobante == 3) {
                $('#numDocumento').attr('patron', 'requerido,ruc');
            } else if (tipoComprobante == 1){
                $('#numDocumento').attr('patron', 'requerido,dni');
            }
        });

        $(document).on('change', '#cboRegion', function (e) {
			e.preventDefault();
			var idDepartamento = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			$('#cboDistrito').html(html);

			if (typeof (ServicioProveedor.provincia[idDepartamento]) == 'object') {
				$.each(ServicioProveedor.provincia[idDepartamento], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#cboProvincia').html(html);
            Fn.loadSemanticFunctions();
            $('#cboProvincia').dropdown('clear');
            $('#cboDistrito').dropdown('clear');
			Fn.selectOrderOption('cboProvincia');
		});
        
		$(document).on('change', '#cboProvincia', function (e) {
			e.preventDefault();
			var idDepartamento = $('#cboRegion').val();
			var idProvincia = $(this).val();
			var html = '<option value="">Seleccionar</option>';

			if (typeof (ServicioProveedor.distrito[idDepartamento][idProvincia]) == 'object') {
				$.each(ServicioProveedor.distrito[idDepartamento][idProvincia], function (i, v) {
					html += '<option value="' + i + '">' + v['nombre'] + '</option>';
				});
			}

			$('#cboDistrito').html(html);
            Fn.loadSemanticFunctions();
            $('#cboDistrito').dropdown('clear');
			Fn.selectOrderOption('cboDistrito');
		});


        $(document).on('change', '#tipoDocumento', function () {
            var tipo = $(this).val();
            var numeroDocumento = $('#numeroDocumento');

            switch (tipo) {
                case 'DNI':
                    numeroDocumento.attr({
                        'placeholder': 'Ingrese su DNI',
                        'pattern': '\\d{8}',
                        'maxlength': '8',
                        'title': 'El DNI debe contener 8 dígitos numéricos.'
                    });
                    break;
                case 'RUC':
                    numeroDocumento.attr({
                        'placeholder': 'Ingrese su RUC',
                        'pattern': '\\d{11}',
                        'maxlength': '11',
                        'title': 'El RUC debe contener 11 dígitos numéricos.'
                    });
                    break;
                case 'CE':
                    numeroDocumento.attr({
                        'placeholder': 'Ingrese su Carnet de Extranjería',
                        'pattern': '\\d{9,12}',
                        'maxlength': '12',
                        'title': 'El Carnet de Extranjería debe contener entre 9 y 12 dígitos numéricos.'
                    });
                    break;
            }

            // $('#numeroDocumento').off('change'); // Remueve previos event handlers

            // if (tipo == 'DNI') {
                // Agregar handler para DNI
                // $('#numeroDocumento').on('change', function () {
                //     var dni = $(this).val();
                //     // Reemplaza 'urlApiDni' con la URL de tu API
                //     $.ajax({
                //         url: `https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
                //         type: 'GET',
                //         success: function (data) {
                //             console.log(data);
                //             // Asume que la API devuelve un objeto con las propiedades adecuadas
                //             $('#nombreContacto').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
                //         },
                //         error: function (error) {
                //             console.error('Error:', error);
                //         }
                //     });
                // });

            // } else if (tipo == 'RUC') {
            //     // Agregar handler para RUC
            //     $('#numeroDocumento').on('change', function () {
            //         var ruc = $(this).val();
            //         // Reemplaza 'urlApiRuc' con la URL de tu API
            //         $.ajax({
            //             url: `https://dniruc.apisperu.com/api/v1/ruc/${ruc}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
            //             type: 'GET',
            //             success: function (data) {
            //                 console.log(data);
            //                 // Asume que la API devuelve un objeto con la propiedad 'razonSocial'
            //                 $('#razonSocial').val(data.razonSocial);
            //             },
            //             error: function (error) {
            //                 console.error('Error:', error);
            //             }
            //         });
            //     });
            // }

        });

        var debounceTimer;
        $(document).on('keyup', '#numeroDocumento', function () {
            clearTimeout(debounceTimer);
            var $this = $(this);
            debounceTimer = setTimeout(function () {
                var numero = $this.val();
                var tipo = $('#tipoDocumento').val();

                if (tipo == 'DNI' && numero.length == 8) {
                    // Llamada a la API para DNI
                   
                    // Reemplaza 'urlApiDni' con la URL de tu API
                    $.ajax({
                        url: `https://dniruc.apisperu.com/api/v1/dni/${numero}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
                        type: 'GET',
                        success: function (data) {
                            console.log(data);
                            // Asume que la API devuelve un objeto con las propiedades adecuadas
                            $('#nombreContacto').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                } else if (tipo == 'RUC' && numero.length == 11) {
                    
                    // Reemplaza 'urlApiRuc' con la URL de tu API
                    $.ajax({
                        url: `https://dniruc.apisperu.com/api/v1/ruc/${numero}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImplYW4uYWxhcmNvbkB2aXN1YWxpbXBhY3QuY29tLnBlIn0.yuUxfSCdDVEOvZkmGM428wevwsAo8z3YZhW1qgbj56Q`,
                        type: 'GET',
                        success: function (data) {
                            console.log(data);
                            // Asume que la API devuelve un objeto con la propiedad 'razonSocial'
                            $('#razonSocial').val(data.razonSocial);
                        },
                        error: function (error) {
                            console.error('Error:', error);
                        }
                    });
                }

               
            }, 500); // Retardo de 500 milisegundos
        });

        $(document).on('change', '#region', function (e) {
            e.preventDefault();
            var idDepartamento = $(this).val();
            var html = '<option value="">Seleccionar</option>';

            $('#distrito').html(html);

            if (typeof (provincia[idDepartamento]) == 'object') {
                $.each(provincia[idDepartamento], function (i, v) {
                    html += '<option value="' + i + '">' + v['nombre'] + '</option>';
                });
            }

            $('#provincia').html(html);
            Fn.selectOrderOption('provincia');
        });

        $(document).on('change', '#provincia', function (e) {
            e.preventDefault();
            var idDepartamento = $("#region").val();
            var idProvincia = $(this).val();
            var html = '<option value="">Seleccionar</option>';

            if (typeof (distrito_ubigeo[idDepartamento]) == 'object' &&
                typeof (distrito_ubigeo[idDepartamento][idProvincia]) == 'object'
            ) {
                $.each(distrito_ubigeo[idDepartamento][idProvincia], function (i, v) {
                    html += '<option value="' + i + '">' + v['nombre'] + '</option>';
                });
            }

            $('#distrito').html(html);
            Fn.selectOrderOption('distrito');
        });

        HTCustom.load();
    },

    actualizarServicioProveedor: function () {

        let data = Fn.formSerializeObject('formActualizarProveedorServicio');
		data.archivoEliminado = ServicioProveedor.idEliminados;
		let jsonString = { 'data': JSON.stringify(data) };
		let config = { 'url': ServicioProveedor.url + 'actualizarServicioProveedor', 'data': jsonString };


        // let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formActualizarProveedorServicio')) };
        // let url = ServicioProveedor.url + "actualizarServicioProveedor";
        // let config = { url: url, data: jsonString };
        
       
        //console.log(config);

        $.when(Fn.ajax(config)).then(function (b) {
            ++modalId;
            var btn = [];
            let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

            if (b.result == 1) {
                fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarServicioProveedor").click();';
            }

            btn[0] = { title: 'Continuar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
            ServicioProveedor.idEliminados = [];
           
        });
       
        // let jsonData = JSON.parse(jsonString.data);
        // let numero = jsonData.numeroContacto;
        // let documento = jsonData.tipoDocumento;
        // let numeroDocumento_ = jsonData.numeroDocumento;
        // let correo = jsonData.correoContacto;
        // let titulo = 'Alerta!!';

        // switch (documento) {
        //     case 'DNI':

        //         if (!Fn.validators.dni.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El DNI debe contener 8 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;
        //     case 'RUC':

        //         if (!Fn.validators.ruc.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El RUC debe contener exactamente 11 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;
        //     case 'CE':

        //         if (!Fn.validators.carnetExtranjeria.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El Carnet de Extranjería debe contener entre 9 y 12 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;
        // }

        // // if (!numero.match(/^\d{9}$/)) {

        // //     var contenidoNumero = 'El número de contacto debe contener exactamente 9 dígitos numéricos.';
        // //     var btn = [];
        // //     let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        // //     btn[0] = { title: 'Continuar', fn: fn };
        // //     Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoNumero, btn: btn, width: '20%' });
        // //     return false;
        // // }

        // if (!Fn.validators.email.expr.test(correo)) {

        //     var contenidoCorreo = 'Correo inválido!!.';
        //     var btn = [];
        //     let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //     btn[0] = { title: 'Continuar', fn: fn };
        //     Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoCorreo, btn: btn, width: '20%' });
        //     return false;
        // }

 
    },

    registrarProveedorServicio: function () {

        ++modalId;
        let jsonString = { 'data': JSON.stringify(Fn.formSerializeObject('formRegistroProveedorServicio')) };
      
        let url = ServicioProveedor.url + "registrarProveedorServicio";
        let config = { url: url, data: jsonString };
        console.log(config);
        // let jsonData = JSON.parse(jsonString.data);
        // let numero = jsonData.numeroContacto;
        // let documento = jsonData.tipoDocumento;
        // let numeroDocumento_ = jsonData.numeroDocumento;
        // let correo = jsonData.correoContacto;
        // let titulo = 'Alerta!!';

        // switch (documento) {
        //     case 'DNI':

        //         if (!Fn.validators.dni.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El DNI debe contener 8 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;

        //     case 'RUC':

        //         if (!Fn.validators.ruc.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El RUC debe contener exactamente 11 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;
        //     case 'CE':

        //         if (!Fn.validators.carnetExtranjeria.expr.test(numeroDocumento_)) {

        //             var contenidoRuc = 'El Carnet de Extranjería debe contener entre 9 y 12 dígitos numéricos.';
        //             var btn = [];
        //             let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //             btn[0] = { title: 'Continuar', fn: fn };
        //             Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoRuc, btn: btn, width: '20%' });
        //             return false;
        //         }

        //         break;
        // }

        // if (!numero.match(/^\d{9}$/)) {

        //     var contenidoNumero = 'El número de contacto debe contener exactamente 9 dígitos numéricos.';
        //     var btn = [];
        //     let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //     btn[0] = { title: 'Continuar', fn: fn };
        //     Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoNumero, btn: btn, width: '20%' });
        //     return false;
        // }

        // if (!Fn.validators.email.expr.test(correo)) {

        //     var contenidoCorreo = 'Correo inválido!!.';
        //     var btn = [];
        //     let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

        //     btn[0] = { title: 'Continuar', fn: fn };
        //     Fn.showModal({ id: modalId, show: true, title: titulo, content: contenidoCorreo, btn: btn, width: '20%' });
        //     return false;
        // }

        $.when(Fn.ajax(config)).then(function (b) {
            ++modalId;
            var btn = [];
            let fn = 'Fn.showModal({ id:' + modalId + ',show:false });';

            if (b.result == 1) {
                fn = 'Fn.closeModals(' + modalId + ');$("#btn-filtrarServicioProveedor").click();';
            }

            btn[0] = { title: 'Continuar', fn: fn };
            Fn.showModal({ id: modalId, show: true, title: b.msg.title, content: b.msg.content, btn: btn, width: '40%' });
        });
        
    },


}

ServicioProveedor.load();
