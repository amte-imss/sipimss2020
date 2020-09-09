/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */


$(function () {
    validadores_unidades();
    validadores_umae();
    validadores_delegaciones();
});

function modal_validadores(linea, tipo, entidad, validacion) {
    var destino = site_url + '/convocatoriaV2/get_validadores/' + linea + '/' + tipo + '/' + validacion + '/' + entidad;
    data_ajax(destino, null, '#my_modal_content');
}

function validadores_unidades() {
    $("#validadores_unidades").jsGrid({
        width: "100%",
        height: "500px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
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
        data: unidades_participantes,
        fields: [
            {name: 'id_linea_tiempo', title: "#", visible: false},
            {name: 'id_unidad_instituto', title: "#", visible: false},
            {name: 'region', title: 'Región', type: 'text'},
            {name: 'delegacion', title: 'Delegación', type: 'text'},
            {name: 'clave_unidad', title: 'Clave unidad', type: 'text'},
            {name: 'unidad', title: 'Nombre', type: 'text'},
            {name: 'matricula_n1', title: 'Matrícula', type: 'text'},
            {name: 'nombre_validador_n1', title: 'Validador', type: 'text'},
            {type: "control", align: "center",
                itemTemplate: function (value, item) {
                    return '<a data-toggle="modal" data-target="#my_modal" onclick="modal_validadores(' + item['id_linea_tiempo'] + ', \'unidades\', ' + item['id_unidad_instituto'] + ')">Seleccionar validador N1</a>';
                }
            },
        ]
    });
}
function validadores_umae() {
    $("#validadores_umae").jsGrid({
        width: "100%",
        height: "500px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
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
        data: umae_participantes,
        fields: [
            {name: 'id_linea_tiempo', title: "#", visible: false},
            {name: 'id_unidad_instituto', title: "#", visible: false},
            {name: 'clave_unidad', title: 'Clave UMAE', type: 'text'},
            {name: 'unidad', title: 'Nombre', type: 'text'},
            {name: 'matricula_n1', title: 'Matrícula', type: 'text'},
            {name: 'nombre_validador_n1', title: 'Validador N1', type: 'text'},
            {name: 'matricula_n2', title: 'Matrícula', type: 'text'},
            {name: 'nombre_validador_n2', title: 'Validador N2', type: 'text'},
            {type: "control", align: "center",
                itemTemplate: function (value, item) {
                    return '<a data-toggle="modal" data-target="#my_modal" onclick="modal_validadores(' + item['id_linea_tiempo'] + ', \'unidades\', ' + item['id_unidad_instituto'] + ', \'N1\')">Seleccionar validador N1</a>' +
                            '<br>' +
                            '<a data-toggle="modal" data-target="#my_modal" onclick="modal_validadores(' + item['id_linea_tiempo'] + ', \'unidades\', ' + item['id_unidad_instituto'] + ',\'N2\')">Seleccionar validador N2</a>';
                }
            },
        ]
    });
}
function validadores_delegaciones() {
    $("#validadores_delegaciones").jsGrid({
        width: "100%",
        height: "500px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
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
        data: delegaciones_participantes,
        fields: [
            {name: 'id_linea_tiempo', title: "#", visible: false},
            {name: 'id_unidad_instituto', title: "#", visible: false},
            {name: 'region', title: 'Región', type: 'text'},
            {name: 'delegacion', title: 'Delegación', type: 'text'},
            {name: 'matricula', title: 'Matrícula', type: 'text'},
            {name: 'nombre_validador', title: 'Validador', type: 'text'},
            {type: "control", align: "center",
                itemTemplate: function (value, item) {
                    return '<a data-toggle="modal" data-target="#my_modal" onclick="modal_validadores(' + item['id_linea_tiempo'] + ', \'delegaciones\', ' + item['id_delegacion'] + ')">Seleccionar validador N2</a>';
                }
            },
        ]
    });
}

function render_validadores(lista) {
    $("#lista_validadores").jsGrid({
        width: "100%",
        height: "300px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
        paging: true,
        autoload: true,
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
        data: lista,
        fields: [
            {name: 'id_linea_tiempo', title: "#", visible: false},
            {name: 'id_usuario', title: "#", visible: false},
            {name: 'matricula', title: 'Matrícula', type: 'text'},
            {name: 'nombre_validador', title: 'Nombre', type: 'text'},
            {name: 'clave_categoria', title: 'Cve. categoría', type: 'text'},
            {name: 'categoria', title: 'Categoría', type: 'text'},
            {type: "control", align: "center",
                itemTemplate: function (value, item) {
                    return $("<input>").attr("type", "checkbox")
                            .attr("checked", item.Checked)
                            .attr('data-cve', value)
                            .on("change", function () {
                                item.Checked = $(this).is(":checked");
                                upsert_validador(this, item);
                            });
                }
            },
        ]
    });
}

function upsert_validador(object, item){
    
}