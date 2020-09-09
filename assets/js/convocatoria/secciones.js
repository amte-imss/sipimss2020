/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

$(function () {

    $('#form_convocatoria_n1').submit(function (event) {
        document.getElementById('arbol').value = JSON.stringify($('#treeN1').jstree(true).get_json('#', {flat: false}));
    });
    $('#form_convocatoria_n2').submit(function (event) {                       
        document.getElementById('arbol2').value = JSON.stringify($('#treeN2').jstree(true).get_json('#', {flat: false}));
    });

    $('#treeNTest').jstree({
        "checkbox": {
            "keep_selected_style": false
        },
        "plugins": ["checkbox"],
        'core': {
            'data': [
                {"id": "ajson8", "parent": "ajson7", "text": "Child 6"},
                {"id": "ajson1", "parent": "#", "text": "Simple root node"},
                {"id": "ajson2", "parent": "#", "text": "Root node 2"},
                {"id": "ajson3", "parent": "ajson2", "text": "Child 1"},
                {"id": "ajson4", "parent": "ajson2", "text": "Child 2"},
                {"id": "ajson5", "parent": "ajson4", "text": "Child 3"},
                {"id": "ajson6", "parent": "ajson5", "text": "Child 4"},
                {"id": "ajson7", "parent": "ajson6", "text": "Child 5"},
            ]
        }});

})

function render_tree(item, datos) {
    $('#' + item).jstree({
        "checkbox": {
            "keep_selected_style": false
        },
        "plugins": ["checkbox"],
        'core': {
            'data': datos

        }});
}