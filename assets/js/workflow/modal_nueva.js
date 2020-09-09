/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

$(function () {
    $('.fecha').datepicker();

});



function get_contenido(item){
    if (item.value != "") {
        var destino = site_url + '/workflow/auxiliares/' + item.value;
        data_ajax(destino, null, '#workflow_contenido', function(){
            $('#workflow_boton_save').css('display', 'initial');
        });        
    }else{
        $('#workflow_contenido').html('');
        $('#workflow_boton_save').css('display', 'none');
    }
}

function workflow_submit(){
    $('#form_workflow').submit();
}