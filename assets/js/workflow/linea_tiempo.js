/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

function editar_linea_tiempo(lt){
    var destino = site_url + workflow_url_editar + '/' + lt;    
    data_ajax(destino, null, '#my_modal_content');
}