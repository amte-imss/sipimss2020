
/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_guardar_actividad(element) {

    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var formulario = button_obj.data('formularioid');//Obtiene nombre del formulario
//            var formData = $('#' + formulario).serialize();//Obtiene los datos del formulario
            var formData = new FormData($('#' + formulario)[0]);
//            alert(formulario);
            var div_respuesta = '#seccion_formulario';
            $.ajax({
                url: site_url + '/perfil_usuario/datos_perfil',
                data: formData,
                type: 'POST',
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
//                dataType: 'JSON',
                beforeSend: function (xhr) {
//            $('#tabla_actividades_docente').html(create_loader());
                }
            })
                    .done(function (data) {
                        try {//Cacha el error
                            $(div_respuesta).empty();
                            var resp = $.parseJSON(data);
                            if (typeof resp.html !== 'undefined') {
                                $(div_respuesta).append(resp.html);
                                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                                    get_mensaje_general(resp.mensaje, resp.tipo_msg, 5000);
                                }
                            }
                        } catch (e) {
                            get_mensaje_general('Algo malo ocurrio hoy.', 'info', 5000);
                            $(div_respuesta).html(data);
                        }

                    })
                    .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
                        get_mensaje_general('Ocurrió un error durante el proceso, inténtelo más tarde.', 'warning', 5000);
                    })
                    .always(function () {
                        remove_loader();
                    });

        } else {
            return false;
        }
    });
//    if (is_curso_principal === 1) {
//        apprise('Es un curso principal, no es posible eliminar');
//    } else {
//
//    }
}
