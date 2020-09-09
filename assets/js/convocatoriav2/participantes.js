/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
var unidades_seleccionadas_censo = {};

$(function () {
    var grid_participantes = $("#unidades_seleccionadas").jsGrid({
        height: "500px",
        width: "100%",
        sorting: true,
        paging: true,
        filtering: true,
        autoload: true,
        data: unidades_participantes,
        fields: [{name: 'region', title: "Región", type: "text"},
            {name: 'delegacion', title: "Delegación", type: "text"},
            {name: 'clave_unidad', title: "Clave unidad", type: "text"},
            {name: 'unidad', title: "Unidad/UMAE", type: "text"}]
    });

    unidades_participantes.forEach(function(item){
        unidades_seleccionadas_censo[item.clave_unidad] = item;
    });

    $('#form_workflow_participantes').submit(function (event) {
        event.preventDefault();
        var destino = $(this).attr('action');
        var parametros = {};
        var unidades = [];
        Object.keys(unidades_seleccionadas_censo).forEach(function (key) {
//            console.log(key, unidades_seleccionadas_censo[key]);
            if (unidades_seleccionadas_censo[key] != null) {
                if(typeof unidades_seleccionadas_censo[key].id_unidad_instituto !== 'undefined'){
                    unidades.push(unidades_seleccionadas_censo[key].id_unidad_instituto);
                }else{
                    unidades.push(unidades_seleccionadas_censo[key].id_unidad);
                }
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
            console.log(json);
            alert(json.status.msg);
        });
    });
});

function agregar_unidad(item) {
//    console.log('Agregando unidad');
//    console.log(item);
    var clave = item.getAttribute('data-cve');
    var destino = site_url + '/convocatoriaV2/unidad/';
    var dataSend = {clave_unidad: clave};
    var inicio = document.getElementById('fecha_inicio_0').value;
    var fin = document.getElementById('fecha_fin_2').value;
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
}
