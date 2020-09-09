/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
var unidades_seleccionadas_censo = {};

function agregar_unidad(item) {
//    console.log('Agregando unidad');
//    console.log(item);
    $('#div-error-unidades').css('display', 'none');
    var clave = item.getAttribute('data-cve');
    var destino = site_url + '/convocatoriaV2/unidad/';
    var dataSend = {clave_unidad: clave};
    var inicio = document.getElementById('fecha_inicio_0').value;
    var ffs = $('.fecha_fin');
    var fin = ffs[ffs.length-1].value;
    if(inicio != null && inicio.trim() != '' && fin != null && fin.trim() != ''){
        dataSend.fechas = {inicio: inicio, fin: fin};
        if ($(item).is(":checked")) {
            $.ajax({
                url: destino,
                data: dataSend,
                method: 'POST',
                beforeSend: function (xhr) {
                    mostrar_loader();
                }
            }).done(function (response) {
                var json = JSON.parse(response);
                ocultar_loader();
    //            console.log(json[0]);
                if (json[0].activa == null || !json[0].activa) {
                    $("#unidades_seleccionadas").jsGrid("insertItem", json[0]).done(function () {
                        //console.log("insertion completed");
                        unidades_seleccionadas_censo[json[0].clave_unidad] = json[0];
                    });
                }else{
                    alert('La unidad seleccionado se encuentra ya en otra convocatoria activa');
                    $(item).prop('checked', false);
                }
            });
        } else {
            var row = $("#unidades_seleccionadas td:contains('" + clave + "')").parent();
    //        console.log(row);
            $("#unidades_seleccionadas").jsGrid("deleteItem", row);
            unidades_seleccionadas_censo[clave] = null;
        }
        console.log(unidades_seleccionadas_censo);
    }else{
        $('#div-error-unidades').css('display', 'block');
        $('#div-error-unidades').html('Para seleccionar participantes es necesario seleccionar todas las fechas de inicio y fin');
        $('input[type=checkbox]').prop('checked', false);
    }

}

$(function () {
    var seleccionada_campos = [
        {name: 'region', title: "Región", type: "text"},
        {name: 'delegacion', title: "Delegación", type: "text"},
        {name: 'clave_unidad', title: "Clave unidad", type: "text"},
        {name: 'unidad', title: "Unidad/UMAE", type: "text"}
    ];
    $("#unidades_seleccionadas").jsGrid({
        height: "200px",
        width: "100%",
        sorting: true,
        paging: true,
        filtering: true,
        fields: seleccionada_campos
    });

    $('.fecha').datepicker();

    $('#form_workflow').submit(function (event) {
        $('#confirmModal').modal('hide');
        $('.convocatoria-requerido').css('display', 'none');
        $('.convocatoria-fecha').css('display', 'none');
        $('.convocatoria-error').css('display', 'none');
        event.preventDefault();
        var campos_validos = true;
        $('#form_workflow :input:visible[required="required"]').each(function()
        {
            if(!this.validity.valid)
            {
                $('#div-requerido-'+$(this).attr('id')).css('display', 'block');
                campos_validos = false;
            }
        });
        if(campos_validos){
            campos_validos&=valida_fechas();
        }
        // campos_validos = false;
        if(campos_validos){
            var destino = site_url + '/convocatoriaV2/nueva/';
            var parametros = {};
            parametros.id_workflow = document.getElementById('id_workflow').value;
            parametros.nombre = document.getElementById('nombre').value;
            parametros.clave = document.getElementById('clave').value;
            // parametros.porcentaje = document.getElementById('porcentaje').value;
            var fechas = $("input[name^='fecha_']");
            for (i = 0; i < fechas.length; i++) {
                parametros[fechas[i].id] = fechas[i].value;
            }
            var unidades = [];
            Object.keys(unidades_seleccionadas_censo).forEach(function (key) {
    //            console.log(key, unidades_seleccionadas_censo[key]);
                if(unidades_seleccionadas_censo[key] != null){
                    unidades.push(unidades_seleccionadas_censo[key].id_unidad);
                }

            });
            parametros.unidades = unidades;
    //        console.log(parametros);
            $.ajax({
                url: destino,
                data: parametros,
                method: 'POST',
                beforeSend: function (xhr) {
                    mostrar_loader();
                }
            }).done(function (response) {
                var json = JSON.parse(response);
                ocultar_loader();
                destino = site_url + '/workflow/cron';
                data_ajax(destino);
                if(json.status){
                    window.location = site_url + '/workflow';
                }else{
                    if(typeof json.errores !== 'undefined'){
                        Object.keys(json.errores).forEach(function(key) {
                            var key_tmp = key.replace('[', '');
                            key_tmp = key_tmp.replace(']', '');
                            $('#div-error-'+key_tmp).html(json.errores[key]);
                            $('#div-error-'+key_tmp).css('display', 'block');
                        });
                    }
                    if(typeof json.msg !== 'undefined'){
                        $('#div-error-general').html(json.msg);
                        $('#div-error-general').css('display', 'block');
                    }
                }
            });
        }
    });
});

function valida_fechas(){
    var d1 = null;
    var d2 = null;
    var f1 = null;
    var f2 = null;
    var validas = true;
    var f_inicio = $('.fecha_inicio');
    var f_fin = $('.fecha_fin');
    for(i=0;i<f_inicio.length;i++){
        if(i<f_inicio.length-1){
            d1 = f_inicio[i].value.split("-");
            d2 = f_inicio[i+1].value.split("-");
            f1 = new Date(d1[2], d1[1] - 1, d1[0]);
            f2 = new Date(d2[2], d2[1] - 1, d2[0]);
            if(f1>f2){
                validas = false;
                $('#div-'+f_inicio[i+1].id).css('display', 'block');
            }
        }
        d1 = f_inicio[i].value.split("-");
        d2 = f_fin[i].value.split("-");
        f1 = new Date(d1[2], d1[1] - 1, d1[0]);
        f2 = new Date(d2[2], d2[1] - 1, d2[0]);
        if(f1>f2){
            validas = false;
            $('#div-'+f_fin[i].id).css('display', 'block');
        }
        if(i<f_inicio.length-1){
            d1 = f_fin[i].value.split("-");
            d2 = f_inicio[i+1].value.split("-");
            f1 = new Date(d1[2], d1[1] - 1, d1[0]);
            f2 = new Date(d2[2], d2[1] - 1, d2[0]);
            if(f1>f2){
                validas = false;
                $('#div-'+f_fin[i].id).css('display', 'block');
                $('#div-'+f_inicio[i+1].id).css('display', 'block');
            }
        }
    }
    return validas;
}
