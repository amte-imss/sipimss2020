/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */


$(function(){
    $('.fecha').datepicker();
    $('#form_workflow').submit(function(event){
        var id_workflow = document.getElementById('id_workflow').value;
        $(this).attr('action', site_url + '/workflow/nueva/' + id_workflow);
    });
});
