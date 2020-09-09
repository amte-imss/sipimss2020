/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

function get_validadores(convocatoria, tipo_entidad, entidad, validacion) {
    var destino = site_url + '/convocatoria/get_validadores/' + convocatoria + '/' + tipo_entidad + '/' + entidad + '/' + validacion;
    data_ajax(destino, null, '#my_modal_content');
}