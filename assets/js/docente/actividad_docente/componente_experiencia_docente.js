var name_campo_curso_experiencia = "curso_experiencia_docente";
$(document).ready(function () {
    grid_cursos_experiencia_doc();
});
function grid_cursos_experiencia_doc() {
    var nameGrid = '#div_secundario_'+ name_campo_curso_experiencia;
    
    $(function() {
 
        $(nameGrid).jsGrid({
            height: "200px",
            width: "100%",
            filtering: true,
            inserting: true,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete client?",
            controller: {
                loadData: function(filter) {
                    mostrar_loader();
                    var d = $.Deferred();
                    var valor_pos_curso_exp = document.getElementById(name_campo_curso_experiencia).value;
                    try {
                        
                        valor_pos_curso_exp = $.parseJSON(decode64(valor_pos_curso_exp));
                    } catch (error) {
                        
                    }
                    return valor_pos_curso_exp;
                },
                insertItem: function(item) {
                    console.log(item);
                    //var data = $(nameGrid).jsGrid("option", "data");                    
                    //var id = (isNaN(data)) ? -1 : data.length * (-1);
                    var valor_pos_curso_exp = document.getElementById(name_campo_curso_experiencia).value;
                    
                    if(valor_pos_curso_exp.length>0){
                        valor_pos_curso_exp = $.parseJSON(decode64(valor_pos_curso_exp));
                    }else{
                        valor_pos_curso_exp = [];                        
                    }
                    var id = (valor_pos_curso_exp.length == 0) ? -1 : (valor_pos_curso_exp.length + 1) * (-1);
                    item['id_curso_exp_docente'] = id;
                     
                    valor_pos_curso_exp.push(item);  
                    //console.log(valor_pos_curso_exp);
                    document.getElementById(name_campo_curso_experiencia).value = encode64(JSON.stringify(valor_pos_curso_exp));
                    console.log( decode64(document.getElementById(name_campo_curso_experiencia).value));
                    return item;
                },
                updateItem: function(item) {
                    var valor_pos_curso_exp = document.getElementById(name_campo_curso_experiencia).value;
                    valor_pos_curso_exp = $.parseJSON(decode64(valor_pos_curso_exp));
                    for (const property in valor_pos_curso_exp) {
                        if(valor_pos_curso_exp[property]['id_curso_exp_docente'] == item['id_curso_exp_docente'] ){
                            valor_pos_curso_exp[property].nombre_curso = item.nombre_curso;
                            valor_pos_curso_exp[property].anio = item.anio;
                            break;
                        }
                        
                    }
                    document.getElementById(name_campo_curso_experiencia).value = encode64(JSON.stringify(valor_pos_curso_exp));
                    //console.log( decode64(document.getElementById(name_campo_curso_experiencia).value));
                    return item;
                },
                deleteItem: function(item) {
                    var valor_pos_curso_exp = document.getElementById(name_campo_curso_experiencia).value;
                    valor_pos_curso_exp = $.parseJSON(decode64(valor_pos_curso_exp));
                    valor_pos_curso_exp_aux = [];
                    for (const property in valor_pos_curso_exp) {
                        if(valor_pos_curso_exp[property]['id_curso_exp_docente'] != item['id_curso_exp_docente'] ){
                            //delete valor_pos_curso_exp[property]; 
                            valor_pos_curso_exp_aux.push(valor_pos_curso_exp[property]);
                            //console.log(valor_pos_curso_exp_aux);                           
                            break;
                        }
                        
                    }
                    if(valor_pos_curso_exp_aux.length>0){
                    
                        document.getElementById(name_campo_curso_experiencia).value = encode64(JSON.stringify(valor_pos_curso_exp_aux));
                    }else{
                        document.getElementById(name_campo_curso_experiencia).value = "";
                    }
                    console.log( decode64(document.getElementById(name_campo_curso_experiencia).value));
                    return item
                }
            },
            fields: [
                {name: "id_curso_exp_docente", title:"identificador", type: "number", visible: false },
                {name: "nombre_curso", title:"Nombre del curso", type: "text", width: 150, visible: true },
                {name: "anio", title:"Numero de años", type: "number", width: 50, visible: true }, 
                { type: "control" }
            ]
        });

    });

}