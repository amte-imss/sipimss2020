
$(document).ready(function () {
    carga_datos_validacion_seccion();
});

function carga_datos_validacion_seccion(){
    if(typeof datos_validacion_seccion !== 'undefined'){
        Object.keys(datos_validacion_seccion).forEach(function (index) {
            var val_secciones = datos_validacion_seccion[index];
            //comentario
            $("#coment_seccion_"+index).val(val_secciones.comentario);
            //validacion secciones
            Object.keys(val_secciones.elementos_censo).forEach(function (indexc) {
                var val_c = val_secciones.elementos_censo[indexc];
                var nombre_rad = "radio_" + val_c + "_" + indexc;
                //console.log(val_censo);
                $("#"+nombre_rad).prop("checked", true);
            });
        });
    }
}

function selecciona_todo(elemento){
    var data  = $(elemento);
    var seccion = data.data("seccion");
    var value = data.val();
    
    $(".ctrselect_"+value+"_"+seccion).each(function (index) {
        //console.log(this.id);
        //console.log(this.name);
        $("#"+this.id).prop("checked", true);
    });
    
}

function guarda_val_seccion(elemento){
    var data_element = $(elemento);
    var seccion = data_element.data("seccion");
    var path = site_url + data_element.data("path");
    var formulario = "form_val_seccion_" + seccion;
    var dataSend = new FormData($('#' + formulario)[0]);    
    //var dataSend = $('#' +formulario).serialize();
    var div_respuesta = '';
    console.log(formulario);
    console.log(path);
    console.log(dataSend);
    //break;
    $.ajax({

    url: path,
    data: dataSend,
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
                //console.log(resp);                    
                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                    if (resp.tp_msg === 'danger') {
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 10000, 'div_error_'+ seccion,'alerta_seccion_'+seccion,'msg_'+seccion);                       
                    }else{
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 5000, 'div_error_'+ seccion,'alerta_seccion_'+seccion,'msg_'+seccion);                       
                    }
                }
                
            } catch (e) {
                console.log("Error");
                //$(div_respuesta).html(data);
            }

        })
        .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
            get_mensaje_general_validacion('Ocurrió un error durante el proceso, inténtelo más tarde.', 'danger', 5000, 'div_error_'+seccion,'alerta_seccion_'+seccion,'msg_'+seccion);
        })
        .always(function () {
            ocultar_loader();
        });

}

function guarda_validacion(elemento){
    var data_element = $(elemento);
    var doc = data_element.data("doc");
    var path = site_url + data_element.data("path");
    var formulario = "form_validacion_" + doc;
    var dataSend = new FormData($('#' + formulario)[0]);    
    //var dataSend = $('#' +formulario).serialize();
    var div_respuesta = '';
    console.log(formulario);
    console.log(path);
    console.log(dataSend);
    //break;
    $.ajax({

    url: path,
    data: dataSend,
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
                //console.log(resp);                    
                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                    if (resp.tp_msg === 'danger') {
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 10000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
                    }else{
                        $('#item_finaliza_validacion').hide();
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 5000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
                        setTimeout("location.reload()", 5000);
                    }
                }
                
            } catch (e) {
                console.log("Error" + e);
                //$(div_respuesta).html(data);
            }

        })
        .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
            get_mensaje_general_validacion('Ocurrió un error durante el proceso, por favor, inténtelo más tarde.', 'danger', 5000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
        })
        .always(function () {
            ocultar_loader();
        });
}


function guarda_ratificacion(elemento){
    var data_element = $(elemento);
    var doc = data_element.data("doc");
    var path = site_url + data_element.data("path");
    var formulario = "form_ratificacion_" + doc;
    var dataSend = new FormData($('#' + formulario)[0]);    
    //var dataSend = $('#' +formulario).serialize();
    var div_respuesta = '';
    console.log(formulario);
    console.log(path);
    console.log(dataSend);
    //break;
    $.ajax({

    url: path,
    data: dataSend,
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
                //console.log(resp);                    
                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                    if (resp.tp_msg === 'danger') {
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 10000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
                    }else{
                        $('#item_finaliza_ratificacion').hide();
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 5000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
                        setTimeout("location.reload()", 5000);
                    }
                }
                
            } catch (e) {
                console.log("Error" + e);
                //$(div_respuesta).html(data);
            }

        })
        .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
            get_mensaje_general_validacion('Ocurrió un error durante el proceso, por favor, inténtelo más tarde.', 'danger', 5000, 'div_error_'+ doc,'alerta_'+doc,'msg_'+doc);
        })
        .always(function () {
            ocultar_loader();
        });
}

function get_mensaje_general_validacion(mensaje, tipo_mensaje, timeout, div_padre, div_tipo_msg, div_msg) {   
    div_padre = "#"+div_padre;
    $('#'+div_tipo_msg).removeClass('alert-danger').removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning');
    $('#'+div_tipo_msg).addClass('alert-' + tipo_mensaje);
    $('#'+div_msg).html(mensaje);
    $(div_padre).show();
    setTimeout("$('"+div_padre+"').hide()", timeout);
}


