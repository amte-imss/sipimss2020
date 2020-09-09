/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_actualizar_datos_imss(element) {
    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var url = button_obj.data('url');
            var div_respuesta = '#modal_cuerpo';
            $.ajax({
//                url: site_url + '/actividad_docente/datos_actividad',
                url: site_url + url + '/actualiza_datos_imss',
                data: $('#form_datos_imss').serialize(),
                method: 'POST',
                beforeSend: function (xhr) {
                    $(div_respuesta).html(create_loader());
                }
            })
                    .done(function (data) {
                        try {//Cacha el error
                            $(div_respuesta).empty();
                            var resp = $.parseJSON(data);
                            if (typeof resp.html !== 'undefined') {
//                                $(div_respuesta).append(resp.html);
                                if (resp.tp_msg === "success") {//Si el mensaje es de satisfaccion, cierra modal
//                                    alert('resp.tp_msg');
                                    $('#content_info_usuario').html(resp.html);//Actualiza datos html
                                    $('#my_modal').modal('hide');//Cierra modal
                                    if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                                        get_mensaje_general(resp.mensaje, resp.tp_msg, 5000);//Despliega mensaje en la plantilla
                                    }
                                    window.location = site_url+'/informacion_imss';
                                } else {//Muestra mensaje en el modal
//                                    alert('resp.todo');
                                    $(div_respuesta).html(resp.html);//Actualiza datos html
                                    if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                                        get_mensaje_general_modal(resp.mensaje, resp.tp_msg, 5000);//Despliega mensaje en el modal
                                    }
                                }
                            }
                        } catch (e) {
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
}

/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_actualizar_datos_generales_docente(element) {
    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var url = button_obj.data('url');
            var div_respuesta = '#informacion_general';
            $.ajax({
//                url: site_url + '/actividad_docente/datos_actividad',
                url: site_url + url + '/actualiza_datos_generales',
                data: $('#form_datos_generales').serialize(),
                method: 'POST',
                beforeSend: function (xhr) {
                    $(div_respuesta).html(create_loader());
                }
            })
                    .done(function (data) {
                        try {//Cacha el error
                            $(div_respuesta).empty();
                            var resp = $.parseJSON(data);
                            if (typeof resp.html !== 'undefined') {
//                                $(div_respuesta).append(resp.html);
                                $(div_respuesta).html(resp.html);//Actualiza datos html
                                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                                    get_mensaje_general(resp.mensaje, resp.tp_msg, 5000);
                                }
                            }
                        } catch (e) {
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
}

/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_ver_datos_siap(element) {
    var button_obj = $(element);
    var url = button_obj.data('url');
    data_ajax(site_url + url + '/datos_siap', null, '#my_modal_content');
}
