/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */


function upsert_validador(formulario){
    var form = $('#form_'+formulario);
    data_ajax(form.attr('action'), form);
}