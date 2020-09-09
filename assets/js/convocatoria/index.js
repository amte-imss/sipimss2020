/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

function convocatoria_nueva() {
    var destino = site_url + '/convocatoria/nueva'
    data_ajax(destino, null, '#my_modal_content');
}

function render_timeline(datos) {
    console.log(datos);
    
    var container = document.getElementById('timeline');

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet(datos);

    // Configuration for the Timeline
    var options = {locale:'es'};

    // Create a Timeline
    var timeline = new vis.Timeline(container, items, options);
}