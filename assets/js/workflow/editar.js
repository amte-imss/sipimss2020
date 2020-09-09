/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$(function () {
    var id_linea_tiempo = document.getElementById('id_linea_tiempo').value;
    $('#form_workflow').submit(function (event) {
        event.preventDefault();
        var destino = site_url + '/workflow/index/update/' + id_linea_tiempo;
        data_ajax(destino, '#form_workflow', '#my_modal_content');
    });
    $('.fecha').datepicker();
});


$("#my_modal").on("hidden.bs.modal", function () {
     location.reload(); 
});
