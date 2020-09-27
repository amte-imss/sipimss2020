/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
 var localizadores_sede = [];




(function ($) {

    var index_localizadores = 0;
    $.fn.localizador_sedes = function (config, unidades_cargadas) {
        
        //console.log("configuraciones->");
        //console.log(config);
        var destino = site_url + '/rama_organica/get_localizador/';
        var configuraciones = {};
        if (typeof config !== 'undefined') {
            configuraciones = config;
        }
        this.configuracion = configuraciones;
        this.data_index = index_localizadores;
        if (typeof configuraciones.seleccion !== 'undefined') {
            switch (configuraciones.seleccion) {
                case 'checkbox':
                    this.value = {};
                    break;
                default :
                    this.value = '';
                    break;
            }
        }
        var localizador = this;
        if (typeof unidades_cargadas !== 'undefined') {
            unidades_cargadas.forEach(function (item) {
                switch (configuraciones.seleccion) {
                    case 'checkbox':
                        localizador.value[item.clave_unidad] = true;
                        break;
                    default ://radio
                        localizador.value = item.clave_unidad;
                        break;
                }
            });
        }
        this.attr('data_index', index_localizadores);
        var dataSend = {'view': 1, data_index: index_localizadores, configuraciones: configuraciones};

        if (typeof configuraciones.div_resultado !== 'undefined') {
            localizador = $(configuraciones.div_resultado);
        }

        $.ajax({
            url: destino,
            data: dataSend,
            method: 'POST',
            beforeSend: function (xhr) {
                mostrar_loader();
            }
        }).done(function (response) {
            localizador.html(response);
            ocultar_loader();
        });
        sipimss_rama_funciones(this);
        localizadores_sede[index_localizadores++] = this;
        return this;
    };

    grid_fields();
}(jQuery));

function grid_fields() {

}

function sipimss_rama_funciones(elemento) {
    
}

function localizador_sede_servicio(elemento) {
    //console.log('elemento ');
    var index = elemento.getAttribute('data-index');
    var value = elemento.value;
    
    //console.log("Quien soy ...."+elemento.value+"...");
    //console.log(elemento);
    //console.log(localizadores_sede[index].configuracion.tipo_sede);
    //console.log("Pero que rayos");
    $('#localizador_sede_id_delegacion_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_id_tipo_unidad_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_cve_umae_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_cve_unidad_normativa_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_cve_coordinacion_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_id_nivel_' + index).parent().parent().css('display', 'none');
    $('#localizador_sede_id_delegacion_' + index).val('');
    $('#localizador_sede_id_nivel_' + index).val('');
    $('#localizador_sede_id_tipo_unidad_' + index).val('');
    $('#localizador_sede_cve_umae_' + index).val('');
    $('#localizador_sede_cve_unidad_normativa_' + index).val('');
    $('#localizador_sede_cve_coordinacion_' + index).val('');
    //console.log("el elemento de servicio es: " + elemento.value);
    if((localizadores_sede[index].configuracion.tipo_sede == '2' || localizadores_sede[index].configuracion.tipo_sede == 2) && elemento.value.length == 0){
        //cuando no existe una delegacion designada
        value = 'academica';
    }
    switch (value) {
        case 1:
        case '1':
                if(localizadores_sede[index].configuracion.tipo_sede == '2' || localizadores_sede[index].configuracion.tipo_sede == 2){
                    localizador_submit(index, '#localizador_sede_table_' + index);   
                }else{
                    $('#localizador_sede_id_delegacion_' + index).parent().parent().css('display', 'block');
                    $('#localizador_sede_id_nivel_' + index).parent().parent().css('display', 'block');
                    $('#localizador_sede_id_tipo_unidad_' + index).parent().parent().css('display', 'block');
                    $('#localizador_sede_id_nivel_' + index).prop('disabled', false);
                }
            break;
        case 2:
                case '2':
                if(localizadores_sede[index].configuracion.tipo_sede == '2' || localizadores_sede[index].configuracion.tipo_sede == 2){
                    localizador_submit(index, '#localizador_sede_table_' + index);   
                }else{
                    $('#localizador_sede_cve_umae_' + index).parent().parent().css('display', 'block');
                    $('#localizador_sede_id_nivel_' + index).val('3');
                    $('#localizador_sede_id_nivel_' + index).prop('disabled', true);
                    localizador_submit(index, '#localizador_sede_table_' + index);
                }
            break;
        case 3:
        case '3':
        case 4:
        case '4':
        case 6:
        case '6':
        case '7':
        case 7:
            localizador_submit(index, '#localizador_sede_table_' + index);
        break;
        case 5:
        case '5':
            $('#localizador_sede_cve_unidad_normativa_' + index).parent().parent().css('display', 'block');
            $('#localizador_sede_cve_coordinacion_' + index).parent().parent().css('display', 'block');
            break;        
            case '':
                break;
        case 'academica':            
            if(localizadores_sede[index].configuracion.tipo_sede == '2' || localizadores_sede[index].configuracion.tipo_sede == 2){
                localizador_submit(index, '#localizador_sede_table_' + index);   
            }
            break;

        default:
            //Para sede academica
            if(localizadores_sede[index].configuracion.tipo_sede == '2' || localizadores_sede[index].configuracion.tipo_sede == 2){
                localizador_submit(index, '#localizador_sede_table_' + index);   
            }
            break;
        }
                    

}

function localizador_sede_delegacion(elemento) {
    var data_index = $(elemento).attr('data-index');
    localizador_submit(data_index, '#localizador_sede_table_' + data_index);
}

function localizador_sede_nivel(elemento) {
    var data_index = $(elemento).attr('data-index');
    var nivel = $('#localizador_sede_id_nivel_' + data_index).val();
    $('#localizador_sede_id_delegacion_' + data_index).val('');
    var destino = site_url + '/rama_organica/get_lista/tipo_unidad/' + nivel;
    var parametros = {};
    parametros.item = 'localizador_sede_id_tipo_unidad_' + data_index;
    data_ajax(destino, null, null, localizador_sede_callback_select, true, parametros);
}

function localizador_sede_callback_select(parametros) {
    //console.log(parametros);
    $('#' + parametros.item).empty();
    $('#' + parametros.item).append('<option value="">Seleccionar...</option>');
    // Use jQuery's each to iterate over the opts value
    $.each(parametros.object, function (i, d) {
        $('#' + parametros.item).append('<option value="' + d.id + '">' + d.nombre + '</option>');
    });
}

function localizador_sede_unidad_normativa(elemento) {
    var data_index = $(elemento).attr('data-index');
    var unidad_normativa = $('#localizador_sede_cve_unidad_normativa_' + data_index).val();
    var destino = site_url + '/rama_organica/get_lista/unidad/coordinacion/' + unidad_normativa;
    var parametros = {};
    parametros.item = 'localizador_sede_cve_coordinacion_' + data_index;
    data_ajax(destino, null, null, localizador_sede_callback_select, true, parametros);
}

function localizador_sede_coordinacion(elemento) {
    var index = elemento.getAttribute('data-index');
    localizador_submit(index, '#localizador_sede_table_' + index);
}

function localizador_sede_check(item) {
    switch (localizadores_sede[item.getAttribute('data-index')].configuracion.seleccion) {
        case 'checkbox':
            localizadores_sede[item.getAttribute('data-index')].value[item.getAttribute('data-cve')] = $(item).is(":checked");
            break;
        default:
            if ($(item).is(":checked")) {
                localizadores_sede[item.getAttribute('data-index')].value = item.getAttribute('data-cve');
            } else {
                localizadores_sede[item.getAttribute('data-index')].value = '';
            }
            localizadores_sede[item.getAttribute('data-index')].attr('value', localizadores_sede[item.getAttribute('data-index')].value);
            break;
    }
}
function localizador_sede_detalle(tipo_elemento, clave, periodo) {
    var consulta = site_url + "/rama_organica/get_detalle/" + tipo_elemento + "/" + clave + "/" + periodo;
    $.getJSON(consulta, {})
            .done(function (data, textStatus, jqXHR) {
                if (textStatus === 'success') {
                    return data;
                } else {
                    return null;
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                return null;
            });
}

function localizador_submit(data_index, elemento) {
    var destino = site_url + '/rama_organica/get_localizador/';
    var dataSend = {};
    dataSend.data_index = data_index;
    if (document.getElementById('localizador_sede_config_' + data_index) != null) {
        dataSend.config = document.getElementById('localizador_sede_config_' + data_index).value;
    }
    if (document.getElementById('localizador_sede_id_servicio_' + data_index) != null) {
        dataSend['localizador_sede_id_servicio_' + data_index] = document.getElementById('localizador_sede_id_servicio_' + data_index).value;
    }
    if (document.getElementById('localizador_sede_id_nivel_' + data_index) != null) {
        dataSend['localizador_sede_id_nivel_' + data_index] = document.getElementById('localizador_sede_id_nivel_' + data_index).value;
    }
    if (document.getElementById('localizador_sede_id_delegacion_' + data_index) != null) {
        dataSend['localizador_sede_id_delegacion_' + data_index] = document.getElementById('localizador_sede_id_delegacion_' + data_index).value;
    }
    if (document.getElementById('localizador_sede_id_tipo_unidad_' + data_index) != null) {
        dataSend['localizador_sede_id_tipo_unidad_' + data_index] = document.getElementById('localizador_sede_id_tipo_unidad_' + data_index).value;
    }
    if (document.getElementById('localizador_sede_cve_umae_' + data_index) != null && document.getElementById('localizador_sede_cve_umae_' + data_index).value!='') {
        dataSend['localizador_sede_cve_umae_' + data_index] = document.getElementById('localizador_sede_cve_umae_' + data_index).value;
    }
    
    $.ajax({
        url: destino,
        data: dataSend,
        method: 'POST',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $(elemento).html(response);
        ocultar_loader();
    });
}

function localizador_sede_tipo_unidad(elemento){
    var data_index = elemento.getAttribute('data-index');

    localizador_submit(data_index, '#localizador_sede_table_' + data_index);

}

function localizador_sede_umae(elemento){   
    var data_index = elemento.getAttribute('data-index');
    localizador_submit(data_index, '#localizador_sede_table_' + data_index);
}

function localizador_sede_select_all(elemento){
    var data_index = elemento.attr('data-index');
    $('.localizador_sede_field_checkbox_'+data_index).prop('checked', true);
    $('.localizador_sede_field_checkbox_'+data_index).trigger('change');
}
