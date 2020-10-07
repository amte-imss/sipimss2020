var monitor_formulario = false;
var elementos = new Object();
var formulariosStaticos = {};

var cargoFormulario = 0;


$(document).ready(function () {
    if(typeof properties !== 'undefined'){
        
        //console.log(properties);
        if(typeof properties.staticForm !== 'undefined' && properties.staticForm==1 || properties.staticForm=='1'){            
            $("#btn_agregar").trigger("click");
                     
        }
        

    }
});
/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function mostrar_vista_formulario(element) {
    //alert('Hola valedores');
    // data_ajax(site_url + '/formacion_docente/mostrar_secciones', null, '#seccion_seccion');
    data_ajax(site_url + '/formacion_docente/mostrar_formulario/8', null, '#seccion_formulario');
    //seccion_seccion
}

function reinicia_monitor() {
    monitor_formulario = false;//Reinicia el monitor
}
function activa_monitor() {
    monitor_formulario = true;//Reinicia el monitor
}
function status_monitos() {
    return monitor_formulario;//Reinicia el monitor
}

function ocultar_vista_formulario(element) {
//    alert('Adios valedores');
    if (status_monitos()) {//Pregunta por el estado del monitor, si es activo, pregunta si quiere cntinuar
        apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
            if (btnClick) {//Si confurma con true, limpia y cierra el div
                $('#seccion_seccion').html('');
                $('#seccion_formulario').html('');
                reinicia_monitor();//Reinicia el monitor
            }
        });
    } else {
        $('#seccion_seccion').html('');
        $('#seccion_formulario').html('');
    }
}

function cargar_actividad(element) {
    var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
    var ruta = button_obj.data('rutaeditar');//Obtiene nombre del formulario
    var censo = button_obj.data('censo');//Obtiene nombre del formulario
//    alert(ruta);
    if (status_monitos()) {//Pregunta por el estado del monitor, si es activo, pregunta si quiere cntinuar
        apprise('Se perderán los cambios efectuados, seguro que desea cotinuar', {verify: true}, function (btnClick) {
            if (btnClick) {//Continua con el guardado de las secciones
//                alert(site_url + ruta + '/' + censo);
                data_ajax(site_url + ruta + '/' + censo, null, '#seccion_formulario');
                $('#seccion_seccion').html('');
                reinicia_monitor();
            }
        });
    } else {
        $('#seccion_seccion').html('');
        data_ajax(site_url + ruta + '/' + censo, null, '#seccion_formulario');
    }
}

/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_guardar_actividad(element) {//ajaxFileUpload
    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var formulario = button_obj.data('formularioid');//Obtiene nombre del formulario
            var url_actualiza_tabla = button_obj.data('updatetabla');//Obtiene nombre del formulario
//            var formData = $('#' + formulario).serialize();//Obtiene los datos del formulario
            var formData = new FormData($('#' + formulario)[0]);
//            alert(formulario);
            var div_respuesta = '#seccion_formulario';
            var url = button_obj.data('ruta');
//            var url = $('#ruta_controller').val();
//            formData.delete('ruta_controller');//Quita el elemento, solo indica la ruta
            $.ajax({
//                url: site_url + '/actividad_docente/datos_actividad',
                url: site_url + url,
                data: formData,
                type: 'POST',
                mimeType: "multipart/form-data",
                contentType: false,
//                contentType: "charset=utf-8",
                cache: false,
                processData: false,
//                dataType: 'JSON',
                beforeSend: function (xhr) {
//            $('#tabla_actividades_docente').html(create_loader());
                    mostrar_loader();
                }
            })
                    .done(function (data) {
                        try {//Cacha el error
                            $(div_respuesta).empty();
                            var resp = $.parseJSON(data);
                            if (typeof resp.html !== 'undefined') {
                                if (resp.tp_msg === 'success') {
                                    $(div_respuesta).html('');
                                    reinicia_monitor();
                                    actaliza_data_table(url_actualiza_tabla);
                                    
                                    if(typeof properties !== 'undefined' && typeof properties.staticForm !== 'undefined' && properties.staticForm==1 || properties.staticForm=='1'){            
                                        //$("#btn_agregar").trigger("click"); 
                                        location.reload();                                                   
                                    }
                                } else {
                                    $(div_respuesta).html(resp.html);
                                }
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
                        ocultar_loader();
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

/**
 * Carga el valor de
 * @param {type} element
 * @returns {undefined}
 */
function funcion_actualizar_actividad(element) {
    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var formulario = button_obj.data('formularioid');//Obtiene nombre del formulario
            var url_actualiza_tabla = button_obj.data('updatetabla');//Obtiene nombre del formulario
//            var formData = $('#' + formulario).serialize();//Obtiene los datos del formulario
            var formData = new FormData($('#' + formulario)[0]);
//            alert(formulario);
            var div_respuesta = '#seccion_formulario';
//            var url = $('#ruta_controller').val();
            var url = button_obj.data('ruta');
//            formData.delete('ruta_controller');//Quita el elemento, solo indica la ruta

            $.ajax({
//                url: site_url + '/actividad_docente/datos_actividad',
                url: site_url + url,
                data: formData,
                type: 'POST',
                mimeType: "multipart/form-data",
                contentType: false,
//                contentType: "charset=utf-8",
                cache: false,
                processData: false,
//                dataType: 'JSON',
                beforeSend: function (xhr) {
//            $('#tabla_actividades_docente').html(create_loader());
                    mostrar_loader();
                }
            })
                    .done(function (data) {
                        console.log("Que rayos");
                        console.log(data);
                        try {//Cacha el error
                            $(div_respuesta).empty();
                            var resp = $.parseJSON(data);
                            if (typeof resp.html !== 'undefined') {
                                if (resp.tp_msg === 'success') {
                                    $(div_respuesta).html('');
                                    reinicia_monitor();//Reinicia el monitor
                                    actaliza_data_table(url_actualiza_tabla);
                                    if(typeof properties !== 'undefined' && typeof properties.staticForm !== 'undefined' && properties.staticForm==1 || properties.staticForm=='1'){       
                                        $("#btn_agregar").trigger("click");   
                                        //location.reload();       
                                    }
                                } else {
                                    $(div_respuesta).html(resp.html);
                                }
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
                        ocultar_loader();
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

/**
 *
 * @param {type} ruta
 * @returns {undefined}
 */
function actaliza_data_table(ruta) {
//    data_ajax(site_url + ruta, null, '#seccion_tabla');
    control_registros(1);
}

/**
 * @author LEAS
 * @param {type} e
 * @returns {undefined}
 */
function elemento(e) {
    if (e.srcElement)
        tag = e.srcElement.tagName;
    else if (e.target)
        tag = e.target.tagName;
//    alert(tag);
    switch (tag) {
        case 'SELECT':
            activa_monitor();//Activa el monitor como actualización del formulario
        case 'INPUT':
            activa_monitor();//Activa el monitor como actualización del formulario
            break;
        case 'BUTTON':
            if (e.target.name != 'cancel' && e.target.name != 'save') {
                activa_monitor();//Activa el monitor como actualización del formulario
            }
            break;
        default :
    }
}


function drop_censo(element) {
    apprise('Confirme que realmente desea continuar', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            var button_obj = $(element); //Convierte a objeto todos los elementos del this que llegan del componente html (button en esté caso)
            var censo = button_obj.data('censo');

            var url_actualiza_tabla = button_obj.data('updatetabla');//Obtiene nombre del formulario
//            var formData = $('#' + formulario).serialize();//Obtiene los datos del formulario
            var div_respuesta = '#seccion_formulario';
            var ruta = button_obj.data('ruta');
            //alert(url_actualiza_tabla);

            $.ajax({
//
                url: site_url + ruta + '/' + censo,
                type: 'GET',
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
//                dataType: 'JSON',
                beforeSend: function (xhr) {
//            $('#tabla_actividades_docente').html(create_loader());
                    mostrar_loader();
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
                            get_mensaje_general('Los datos se eliminaron correctamente.', 'info', 5000);
                            actaliza_data_table(url_actualiza_tabla);
                            $(div_respuesta).html(data);
                        }

                    })
                    .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
                        get_mensaje_general('Ocurrió un error durante el proceso, inténtelo más tarde.', 'warning', 5000);
                    })
                    .always(function () {
                        ocultar_loader();
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
/**
 *
 * @param {type} ruta
 * @returns {undefined}
 */
function detalle_registro(element) {
    var objeto = $(element);
    var ruta_ = objeto.data('ruta');
    var censo = objeto.data('censo');
    data_ajax(site_url + ruta_ + censo, null, '#my_modal_content');

}

/**
 *
 * @param {type} ruta
 * @returns {undefined}
 */
function carga_hijo_seccion(element) {
    var objeto = $(element);
    var ruta_ = objeto.data('ruta');
    var static_form = objeto.data('seccion_statica');
    var seccion = objeto.data('seccion');
    var elemnto_resultado = 'seccion_seccion';
    var is_static_seccion = objeto.data('is_static_seccion');
    
    if (status_monitos()) {//Pregunta por el estado del monitor, si es activo, pregunta si quiere cntinuar
                    apprise('Perdera los cambios efectuados, confirme que desea continuar', {verify: true}, function (btnClick) {
                        if (btnClick)
                        {
                            limpia_niveles(0, '#' + elemnto_resultado);
                            $('#seccion_formulario').html('');//Limpia el icono cargando
                            data_ajax(site_url + ruta_ + seccion, null, '#' + elemnto_resultado);
                            reinicia_monitor();
                        }
                    });
                } else {
            
                    limpia_niveles(0, '#' + elemnto_resultado);
                    $('#seccion_formulario').html('');//Limpia el icono cargando
                    
                    if(typeof properties !== 'undefined'){    
                        //console.log("Value: " + properties.id_elementoSeccionDefault);                        
                        if(typeof properties.id_elementoSeccionDefault !== 'undefined' && (properties.id_elementoSeccionDefault>-1 || properties.id_elementoSeccionDefault!='-1')){                            
                            data_ajax(site_url + ruta_ + seccion, null, '#' + elemnto_resultado, elementosDespuesSeccion, null, properties.id_elementoSeccionDefault); 
                        }else{
                            data_ajax(site_url + ruta_ + seccion, null, '#' + elemnto_resultado);
                        }
                    }else{
                        data_ajax(site_url + ruta_ + seccion, null, '#' + elemnto_resultado);
                        
                    }
                    
                }
            }

            
            
function elementosDespuesSeccion(param) {    
    if ($("#n_1")) {    
        document.getElementById('n_1').value = param;
        //$("#n_1").val(param);
        $("#n_1").trigger( "change" );   
        //carga_hijo_elemento_seccion(document.getElementById('n_1'));
    }
    
}



/**
 *
 * @param {type} ruta
 * @returns {undefined}
 */
function carga_hijo_elemento_seccion(element) {
    var objeto = $(element);
    var ruta_ = objeto.data('ruta');
    var nivel = objeto.data('nivel');
    var elemnto_resultado = 'seccion_seccion';
    var elemnto_formulario = 'seccion_formulario';
    var elemnto_tmp = 'div_errores_tmp';
    var dataSend = {nivel: nivel};
//    var value_select = $("#" + name_componente).val();
//    var value_select = $(element).val();
    var value_select = document.getElementById('n_' + nivel).value;
    var valores_arbol = save_seleccionados(elemnto_resultado);
    var ruta_form = '';
    if (status_monitos()) {//Pregunta por el estado del monitor, si es activo, pregunta si quiere cntinuar
        apprise('Perdera los cambios efectuados, confirme que desea continuar', {verify: true}, function (btnClick) {
            if (btnClick) {
                if (value_select.length > 0) {
                    limpia_niveles(nivel, elemnto_resultado);
                    $.ajax({
                        url: site_url + ruta_ + value_select,
                        data: dataSend,
                        method: 'POST',
                        beforeSend: function (xhr) {
                            $('#' + elemnto_tmp).html(create_loader());
//                            mostrar_loader();

                        }
                    })
                            .done(function (response) {
                                try {//Cacha el error
                                    var resp = $.parseJSON(response);
                                    if (typeof resp.ejecuta_formulario !== 'undefined') {
//                            alert(resp.ejecuta_formulario);
                                        ruta_form = resp.ruta_form;
                                        $('#' + elemnto_tmp).html('');//Limpia el icono cargando
                                        //Mostrar formulario
                                        data_ajax(site_url + '/' + ruta_form + '/mostrar_formulario/' + resp.ejecuta_formulario, null, '#seccion_formulario');
                                    }
                                } catch (e) {
                                    //Pinta la respuesta
                                    $('#' + elemnto_tmp).html('');//Limpia el icono cargando
                                    $('#' + elemnto_formulario).html('');//Limpia el icono cargando
//                        $('#' + elemnto_tmp).html(response);
                                    document.getElementById(elemnto_resultado).innerHTML += "" + response;
//                                    document.getElementById('n_' + nivel).value = value_select;
                                    selecciona_valores_arbol(valores_arbol);
                                    reinicia_monitor();
//                                    document.getElementById('n_1').value = 2;
//                                    alert();
                                }
                            })
                            .fail(function (jqXHR, response) {
                                $('#' + elemnto_tmp).html('Ocurrió un error durante el proceso, inténtelo más tarde.');
                            })
                            .always(function () {
                                ocultar_loader();
                            });
                    reinicia_monitor();
                } else {
                    limpia_niveles(nivel);
                    $('#' + elemnto_tmp).html('');//Limpia el icono cargando
                    $('#' + elemnto_formulario).html('');//Limpia el icono cargando
                }
            } else {
                $('#' + elemnto_tmp).html('');//Limpia el icono cargando
            }
        });
    } else {
        if (value_select.length > 0) {
            limpia_niveles(nivel, elemnto_resultado);
            $.ajax({
                url: site_url + ruta_ + value_select,
                data: dataSend,
                method: 'POST',
                beforeSend: function (xhr) {
                    $('#' + elemnto_tmp).html(create_loader());
//                    mostrar_loader();
                }
            })
                    .done(function (response) {
                        try {//Cacha el error
                            var resp = $.parseJSON(response);
                            if (typeof resp.ejecuta_formulario !== 'undefined') {
//                            alert(resp.ejecuta_formulario);
                                ruta_form = resp.ruta_form;
                                $('#' + elemnto_tmp).html('');//Limpia el icono cargando
                                //Mostrar formulario
                            
                                data_ajax(site_url + '/' + ruta_form + '/mostrar_formulario/' + resp.ejecuta_formulario, null, '#seccion_formulario');
                            }
                        } catch (e) {
                            //Pinta la respuesta
                            $('#' + elemnto_tmp).html('');//Limpia el icono cargando
                            $('#' + elemnto_formulario).html('');//Limpia el icono cargando
//                        $('#' + elemnto_tmp).html(response);
                            document.getElementById(elemnto_resultado).innerHTML += "" + response;
//                            document.getElementById('n_' + nivel).value = value_select;
                            selecciona_valores_arbol(valores_arbol);

//                            document.getElementById(elemnto_resultado).append(response);
                        }
                    })
                    .fail(function (jqXHR, response) {
                        $('#' + elemnto_tmp).html('Ocurrió un error durante el proceso, inténtelo más tarde.');
                    })
                    .always(function () {
                        ocultar_loader();
                    });
        } else {
            limpia_niveles(nivel);
            $('#' + elemnto_tmp).html('');//Limpia el icono cargando
            $('#' + elemnto_formulario).html('');//Limpia el icono cargando
        }
    }
}

function limpia_niveles(nivel) {

    for (var i = (nivel + 1); i < 10; i++) {
//        alert(i);
        $("#n_" + i).remove();
    }
}

function save_seleccionados(div) {
    var values = new Object();
//    var num_elementos_div = document.getElementById(div).innerHTML.length;
    var num_elementos_div = $('#' + div).children().length;//Cuenta la cantidad de componentes en el div resultado, comparado con los select tiene uno más
    var val_select = 0;
//    alert(num_elementos_div);
    for (var i = 1; i < num_elementos_div; i++) {
//        alert(i);
        val_select = document.getElementById('n_' + i).value;
        if (val_select !== '') {
            values[i] = val_select;
        }
//        alert(values[i]);
    }
    return values;
}

function selecciona_valores_arbol(values) {
    for (var k in values) {
        if (values.hasOwnProperty(k)) {
            document.getElementById('n_' + k).value = values[k];//Selecciona selects

        }
    }
}



/**
 *
 * @param {type} catalogo_padre_id
 * @param {type} elemento_catalogo_padre
 * @param {type} dropdawn_resultado
 * @param {type} ruta
 * @returns {undefined}
 */
function carga_hijos_elemento_catalogo(catalogo_padre, elemento_catalogo_padre, dropdawn_resultado, ruta, key_selecciona, regla_catalogo, catalogo_hijo) {
    if (elemento_catalogo_padre == "") {//Valida que sea vacio el elemento seleccionado
//        alert(elemento_catalogo_padre_id);
//        dropdawn_resultado
//        $("#" + dropdawn_resultado).html("");//Limpia el opciones de select
//        $("#" + dropdawn_resultado).remo("");//Limpia el opciones de select
//            $("#" + dropdawn_resultado).html("");
//        var select = document.getElementById(dropdawn_resultado);
//        var this_elemento = $(select);
//console.log(key_selecciona);
//console.log(catalogo_hijo);
//console.log(regla_catalogo);
//console.log(dropdawn_resultado);
//console.log("<<<<<>>>>");
        var select = $('#' + dropdawn_resultado + " option");
        $.each(select, function (i, v) {
            if (i > 0) {
                var value = v.value;
                $('#' + dropdawn_resultado + " option[value=" + value + "]").remove();
            }
        });

    } else {//Carga elementos al combo
        if (typeof key_selecciona === 'undefined' || key_selecciona === null) {
            key_selecciona = '';
        }
        $.post(site_url + ruta + "/llena_opciones", {
            catalogo_padre: catalogo_padre, // Valor seleccionado
            catalogo_hijo: catalogo_hijo, // Valor seleccionado
            regla_catalogo: regla_catalogo,
            elemento_catalogo_opadre: elemento_catalogo_padre,
            elemento_catalogo_hijo: key_selecciona
        }, function (data) {
            var opciones = "<option value=''>Seleccione</option>";
            var listado = JSON.parse(data);
            //alert(listado);
            for (var key in listado)
            {
                if (listado.hasOwnProperty(key)) {
                    var valor = listado[key];
                    var sel = '';
//                    alert(key_selecciona + " " + valor);
                    if (key_selecciona != 'undefined' && key == key_selecciona) {
                        sel = 'selected="selected"';
                    }
                    opciones += '<option value="' + key + '"' + sel + '>' + valor + '</option>';
                }
            }
            if ($("#" + dropdawn_resultado).hasClass('otro')) {
                opciones += '<option value="otro">Otro</option>';
            }
            $("#" + dropdawn_resultado).html(opciones);
            //$("#prueba").html(data);
            //Tomo el resultado de pagina e inserto los datos en el combo indicado
        });
    }
}

function  recarga_datatable(element) {
    var data = $(element);
    var ruta = data.data('ruta');
    var objeto = {
        sub_seccion: element.value
    }
    data_ajax_post(site_url + ruta, null, '#seccion_tabla', objeto);
}

function  get_agrega_opciones_dropdown(componente_id, opciones, key_field, value_field, value) {
    var objeto_opciones = "<option value=''>Seleccione</option>";
    for (var key in opciones)
    {
        if (opciones.hasOwnProperty(key)) {
            var item = opciones[key];
            var sel = '';
//                    alert(key_selecciona + " " + valor);
            if (value != 'undefined' && item[key_field] == value) {
                sel = 'selected="selected"';
            }
            
            objeto_opciones += '<option value="' + item[key_field] + '"' + sel + '>' + item[value_field] + '</option>';
        }
    }
    $("#" + componente_id).html(objeto_opciones);
}
