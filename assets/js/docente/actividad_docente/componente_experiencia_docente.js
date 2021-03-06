var name_campo_curso_experiencia = "curso_experiencia_docente";
$(document).ready(function () {
    grid_cursos_experiencia_doc();
});
function grid_cursos_experiencia_doc() {
    var nameGrid = '#div_secundario_'+ name_campo_curso_experiencia;
    
    $(function() {
 
        $(nameGrid).jsGrid({
            height: "300px",
            width: "100%",
            filtering: false,
            inserting: true,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            deleteConfirm: "¿Confirme que desea eliminar el registro?",
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
                                            
                        var data = $(nameGrid).jsGrid("option", "data");                    
                    
                        var valor_pos_curso_exp = document.getElementById(name_campo_curso_experiencia).value;
                        var id = 1;
                        
                        if(valor_pos_curso_exp.length>0){
                            valor_pos_curso_exp = $.parseJSON(decode64(valor_pos_curso_exp));
                            id = getId(valor_pos_curso_exp); 
                        }else{
                            valor_pos_curso_exp = [];                        
                        }
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
                    //console.log(valor_pos_curso_exp);                           
                    valor_pos_curso_exp_aux = [];
                    for (const property in valor_pos_curso_exp) {
                        //console.log(property);                           
                        //console.log(valor_pos_curso_exp[property]);                           
                        if(valor_pos_curso_exp[property]['id_curso_exp_docente'] != item['id_curso_exp_docente'] ){
                            //delete valor_pos_curso_exp[property]; 
                            valor_pos_curso_exp_aux.push(valor_pos_curso_exp[property]);
                            //console.log(valor_pos_curso_exp_aux);                           
                            //break;
                        }
                        
                    }
                    if(valor_pos_curso_exp_aux.length>0){
                    
                        document.getElementById(name_campo_curso_experiencia).value = encode64(JSON.stringify(valor_pos_curso_exp_aux));
                    }else{
                        document.getElementById(name_campo_curso_experiencia).value = "";
                    }
                    //console.log( decode64(document.getElementById(name_campo_curso_experiencia).value));
                    return item
                }
            },
            fields: [
                {name: "id_curso_exp_docente", title:"identificador", type: "number", visible: false },
                {name: "nombre_curso", title:"Nombre del curso", type: "text", width: 150, visible: true , validate: validation_cursos("nombre_curso")},
                //{name: "anio", title:"Número de años", type: "number", width: 50, visible: true, validate: validation_cursos("anio")}, 
                {name: "anio", title:"Número de años", type: "select", items: {0:"Selecciona opción",1:1,2:2,3:3,4:4,5:5}  , valueType: "number|string", width: 50, visible: true, validate: validation_cursos("anio")}, 
                { type: "control" }
            ]
        });

    });

}

function validaDatos(item){
    console.log(item);
    if(typeof item['anio'] !== 'undefined'  && item['anio']!=='' && item['anio'].length > 0 && item['anio'] > 0 && /^\s+$/.test(item['anio'])){
        return false;
    }                    
    if(typeof item['nombre_curso'] !== 'undefined' && item['nombre_curso'].length > 0 ){
        return false;
    }

}
function getId(data){
    var id = data.length + 1;
    var is_ocupado = true;
    while(is_ocupado){
        is_ocupado = false;
        for (const property in data) {
            if(data[property]['id_curso_exp_docente'] == id ){
                id++;
                is_ocupado = true;
                break;
            }
        }
    }
    return id;

}

function validation_cursos(name){
    var validaciones = new Object;
    validaciones['anio'] =  [
        {validator:"required" ,message: "Por favor, agrege un número de años valido"},
        { message: "El rango de años es entre 1 y 5",
            validator: "range", param: [1, 5] 
        },
        function(value, item) {
            return item.IsRetired ? value > 5 : true;
        }
    ];

    validaciones['nombre_curso'] =  [{validator:"required" ,message: "Por favor, agrege un nombre de curso"}];
    return validaciones[name];
}