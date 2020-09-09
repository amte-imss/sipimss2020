/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


const KEY_OTHER = "keyother_";
const OTHER_AUXILIAR = "_auxiliar";

function  add_another_data(element) {
    var data = $(element);
    var catalogo = data.data('catalogo');
//    anterior = element.value;
    if (element.value == "otro") {//Valida que selecciono otro
        var previo = '';
        $("#" + element.name).on('focus', function () {
            previo = this.value;
//        alert(this.value);
        });
//        alert(previo);
        var label = "Agregar otro(a)" + $('#lbl_' + element.name).text().toLowerCase();
        var cuerpo = crear_elemento_cuerpo(element.name, label);
        $('#my_modal_content').html(this.crear_estructura_modal());
        $('#modal_titulo').html(label);
        $('#modal_cuerpo').html(cuerpo);
        $('#modal_pie').html(this.crear_elemento_pie(element.name, catalogo, previo));
        $('#my_modal').modal({show: true});
//        $('#other_drop_' + catalogo).focus();//Ensiende el foco
    } else {//Valida que no exista el dato agregado otro del catálogo correspondiente, se forma con "other_dropidcat"
//        alert(element.selectedIndex);
        var key_option = KEY_OTHER + catalogo;
//        alert(element.value);
        if (/^([0-9])*$/.test(element.value)) {//Valida digito
            for (var i = 0; i < element.options.length; i++)
            {
//                alert(element.options[i].value);
                if (key_option === element.options[i].value) {
                    key_option = element.options[i].value;
                    $("#" + element.name + " option[value='" + key_option + "']").remove();
                    $("#" + element.name + OTHER_AUXILIAR).val("");
                    break;
                }
            }
        }
    }
}

/**
 * @author LEAS
 * @param {type} element
 * @returns {undefined}
 */
function agregar_otro(element) {
    var data = $(element);
    var componente = data.data('componente');
    var catalogo = data.data('catalogo');
    var text = document.getElementById("other_drop_" + componente);
    if (text.value.toLowerCase() !== 'otro') {
        var key_otro = catalogo;
        add_select_item_value(componente, key_otro, text.value);
        $('#my_modal').modal('toggle');
    } else {
        get_mensaje_general_modal("La opción no es valida", "danger", 7000);
    }
}

function add_select_item_value(componente, value, text) {
    var elmnt = document.getElementById(componente);
    var txt_no_spacios = text.replace(/\s/g, "_");
//    var json = '{"key_other":"' + value + '","value_other":"' + txt_no_spacios + '"}';
    var key_values = KEY_OTHER + value;
    var nuevo = true;
    var i = 0;
    for (i = 0; i < elmnt.options.length; i++)
    {
        var value_select = elmnt.options[i].value;
        if (value_select === key_values) {
            elmnt.selectedIndex = i;
            elmnt.options[i].text = text;
            $("#" + componente + OTHER_AUXILIAR).val(text);
            nuevo = false;
//            break;
        }
    }
    if (nuevo) {
        $("#" + componente).append('<option value=' + key_values + ' >' + text + '</option>');
        $("#" + componente + OTHER_AUXILIAR).val(text);
        elmnt.selectedIndex = i;
    }
}
function retornar_seleccion_cancelar(element) {
    var data = $(element);
    var value_previo = data.data('previo');
    var componente = data.data('previo');
//    document.getElementById(componente).value = value_previo;

}

function  crear_elemento_cuerpo(catalogo, label_text) {
    var cuerpo = '<div id="row_' + catalogo + '" class="row">' +
            '<div class="input-group">' +
            '<span class="input-group-addon">' +
            '<span class="fa fa-keyboard-o"> </span>' +
            '</span>' +
            '<input name="other_drop_' + catalogo + '" value="" id="other_drop_' + catalogo + '" class="form-control" placeholder="" data-toggle="Otro :" data-displaycomponente="none" data-placement="top" title="otro:" type="text">' +
            '</div>' +
            '</div>' +
            '</div>';
    return cuerpo;
}

function  crear_elemento_pie(componente_name, catalogo, option_previa) {
    var pie = '<button type="button" class="btn btn-primary" data-componente="' + componente_name + '" data-catalogo="' + catalogo + '"  onclick="agregar_otro(this);">Aceptar</button>'
            + '<button type="button" class="btn btn-primary" data-componente="' + componente_name + '" data-previo="' + option_previa + '" onclick="retornar_seleccion_cancelar(this);" data-dismiss="modal">Cerrar</button>';
    return pie;
}
