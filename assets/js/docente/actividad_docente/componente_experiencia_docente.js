$(document).ready(function () {
    console.log("Inicia  ");
    //$("#js_grid_registros_seccion").css({ 'display' : 'none' });
    grid_clientes();
    //$(".mySpan").css({ display : inline });
});

function grid_clientes() {
    console.log("others_cursos");
    
    $(function() {
 
        $("#others_cursos").jsGrid({
            height: "30%",
            width: "100%",
     
            filtering: true,
            editing: true,
            sorting: true,
            inserting: true,
            filtering: true,
            paging: true,
            autoload: true,
     
            pageSize: 15,
            pageButtonCount: 5,
     
            deleteConfirm: "Confirme, ¿Desea eliminar el curso?",
     
            controller: {1:{nombre:"Curso 1", num_anios:5}},
     
            fields: [
                { name: "nombre", title:"Nombre del curso", type: "text", width: 150 },
                { name: "num_anios", title:"Numero de años", type: "number", width: 50 },                
                { type: "control" }
            ]
        });
     
    });

}