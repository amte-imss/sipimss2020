/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

function editar_convocatoria(convocatoria){
    var destino = site_url + '/convocatoria/get_convocatorias/'+convocatoria+'/update';
    data_ajax(destino, null, '#my_modal_content');
}
