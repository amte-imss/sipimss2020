/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
/* Ya no se ocupa esta funcion **/

$(function () {
    $('#form_registro').on('submit', function (event) {
        event.preventDefault();
        var destino = site_url + '/welcome/registro';
        data_ajax(destino, '#form_registro', '#form_registro');
    });
});
