/*
* Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
* Ahora, sólo Dios sabe.
* Lo siento.
*/

function render_timeline(datos, grupos) {
    console.log(datos);
    console.log(grupos);

    var container = document.getElementById('timeline');

    // Create a DataSet (allows two way data-binding)
    var items = new vis.DataSet(datos);
    var groups = new vis.DataSet(grupos);

    // Configuration for the Timeline
    var options = {locale: 'es'};

    // Create a Timeline
    var timeline = new vis.Timeline(container, items, groups, options);
}

function render_grid() {
    $("#jsGrid").jsGrid({
        height: "300px",
        width: "100%",
        filtering: true,
        inserting: false,
        editing: true,
        sorting: false,
        selecting: false,
        deleteItem: function(item){
            var confirmacion = confirm("Desea eliminar esta convocatoria "+item.nombre+ " ?");
            if(confirmacion)
            {
                $.ajax({
                    type: 'GET',
                    url: site_url + '/workflow/index/borrar/'+item.id_linea_tiempo,
                    data: '',
                    dataType: 'json'
                }).done(function(result){
                    console.log("Resultado de eliminar: ", result);
                    location.reload();
                }).fail(function(xhr) {
                    console.log('error', xhr);
                });
                console.log("Convocatoria: ", item);
            }
            else
            {

            }
        },
        editItem: function(item){
            window.location.href = site_url + "/workflow/index/" + item.id_linea_tiempo;
        },
        paging: true,
        autoload: true,
        pageSize: 10,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No se encontraron datos",
        rowDoubleClick: function (value) {},
        rowClick: function(args) { },
        data: lineas_tiempo,
        fields: [
            {name: 'id_linea_tiempo', title: "#", visible:false},
            {name: 'nombre', title: 'Nombre', type: 'text'},
            {name: 'clave', title: 'Clave', type: 'text'},
            {name: 'tipo', title: 'Tipo', type: 'text'},
            {name: 'fecha_inicio', title: 'Fecha de inicio', type: 'text'},
            {name: 'fecha_fin', title: 'Fecha de fin', type: 'text'},
            {name: 'etapa_actual', title: 'Etapa actual', type: 'text'},
            {name: 'activa', title: 'Estado', type: 'checkbox'},
            { type: "control" ,editButton: false, itemTemplate:function(value, item){
                var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
                var $myButton = $('<a href="'+site_url+'/workflow/index/'+item.id_linea_tiempo+'">Detalle</a>');
                return $myButton.add($('<span> | </span>')).add($result);
            }}
        ]
    });
}
