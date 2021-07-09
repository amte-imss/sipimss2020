$(document).ready(function () {//Ejecuta al final de la página
//        console.log(array_padres_dependientes);
    $(".ctr_dependientes").each(function (index) {
//        console.log(this);
        actualiza_campos_dependientes(this, false);
    });
    if(typeof properties !== 'undefined'){
        if((typeof properties.staticForm !== 'undefined' && properties.staticForm==1 || properties.staticForm=='1') || (typeof properties.id_elementoSeccionDefault !== 'undefined' && properties.id_elementoSeccionDefault>0)){                   
            $("#div_arbol_seccion").css({'display':'none'});                 
        }
    }
});

function actualiza_campos_dependientes(element, lanzar) {
    var objeto_this = $(element);
    var componentType = element.type;
    //select-one
    if(typeof lanzar === 'undefined'){
        if(componentType === 'checkbox'){
            if (objeto_this.prop('checked') ) {
                element.value = 1;            
            }else{            
                element.value = 0;
            }
        }
    }
    //console.log(element.name);
    //console.log(componentType);
    if (typeof array_padres_dependientes !== 'undefined' && typeof array_padres_dependientes[element.name] !== 'undefined') {
        var configuracion = array_padres_dependientes[element.name];
        //console.log('memoria_values');
        //console.log(element.name +" -> " + objeto_this.val());
        //console.log(memoria_values);
        //console.log(configuracion);
        //limpiar_memoria_values(configuracion);
        Object.keys(configuracion.campos).forEach(function (index) {
            var dependientes = configuracion.campos[index];
            //console.log("dependientes");
            //console.log(dependientes);
            //console.log("Dependientes " + dependientes);
            //console.log("Configuracion " + configuracion.elementos[dependientes]);
            //console.log("value " + objeto_this.val());
            //document.getElementById(dependientes).value = 1;
            if (document.getElementById(dependientes)) {//Valida que exista una dependencia por identificacor
                if (typeof configuracion.elementos[dependientes] !== 'undefined') {//Valida que exista una dependencia por identificacor
                    var opciones = configuracion.elementos[dependientes];
                    /*console.log("opciones");
                    console.log(opciones);
                    console.log(configuracion.elementos);
                    console.log(dependientes);*/
                    if (opciones.length > 0) {
                        //console.log(document.getElementById(dependientes).name);
                        var no_aplico_opciones = 1;
                        for (var i = 0; i < opciones.length; i++) {//Recorre las opciones del combo y las compara para ver si es alguna de las que estan registradas para mover un campo
                            if (objeto_this.val().toString() === opciones[i].toString()) {//
                                //console.log(objeto_this.val().toString()  + " -> " + opciones[i].toString())
                                //console.log("Entro al recorrido por opciones:");
                                control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 1,lanzar);//Ejecuta cambio en el control
                                memoria_values[dependientes] = "";//Limpia la memoria de datos del campo despues de asignar el valor
                                no_aplico_opciones = 0;
                                break;//Sale del ciclo
                            }
                        }
                        if (no_aplico_opciones == 1) {
                            //console.log("No aplica opciones");
                            control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val().toString(), 2,lanzar);//Ejecuta cambio en el control
                        }
                    } else {
                        //console.log("No cuenta con opciones");
                        control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 2,lanzar);//Ejecuta cambio en el control
                    }
                } else {//No existe dependencia  y ejecuta la acción
                    control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 0,lanzar);//Ejecuta cambio en el control
                }
            }

        });

        if(typeof lanzar === 'undefined'){
            for(i=0;i<array_padres_dependientes[element.name].campos.length;i++){
                // console.log('hola_' + i);
                var hijo_t = '#' + array_padres_dependientes[element.name].campos[i];
                // console.log(hijo_t);
                //$(hijo_t).trigger('onchange');
                $(hijo_t).val('');
                $(hijo_t).trigger('change');
            }
        }
            //$('#'+index).trigger('change');

    }
}

function control_dependientes(nombre_elemento_dependiente, catalogo_padre, value_padre, tipo_aplicacion_dependencias, lanzar) {
    var div_control = $('#div_' + nombre_elemento_dependiente);
    var elemento_dependiente = $("#" + nombre_elemento_dependiente);
    var elemento_dependiente_ById = document.getElementById(nombre_elemento_dependiente);
    var tipo_componente = elemento_dependiente_ById.type;
    
    var catalogo_hijo = elemento_dependiente.data('catalogo');
    var regla_catalogo = elemento_dependiente.data('reglacatalogo');
    var display = elemento_dependiente.data("displaycomponente");
    if (value_padre === "") {
        div_control.css("display", display);
        div_control.toggle("slow");//Evento, forma de salida
        if(tipo_componente.toString()==="checkbox" ){
            if(typeof lanzar === 'undefined'){//Aplica solo cuando el usuario es el que selecciona
                elemento_dependiente_ById.checked = false;            
                elemento_dependiente_ById.onclick();
            
            }
            
        }
    } else {
        switch (tipo_aplicacion_dependencias) {
            case 1://Opción unica, cuando ya se considera dependiente, muestra el componente todo reseteado            
            div_control.css("display", "none");
            div_control.toggle("slow");//Evento, forma de salida
            break;
            case 2: //Es dependiente pero no aplica al valor actual
            
                div_control.css("display", display);
                div_control.toggle("slow");//Evento, forma de salida
                if(tipo_componente.toString()==="checkbox" ){//Aplica
                    if(typeof lanzar === 'undefined'){//Aplica solo cuando el usuario es el que selecciona
                        elemento_dependiente_ById.checked = false;
                        elemento_dependiente_ById.onclick();                            
                    }                
                }else{
                    elemento_dependiente.val("");
                }
            break;
            default ://cualquier opción
            div_control.css("display", "none");
            div_control.toggle("slow");//Evento, forma de salida
            
        }
        if (typeof catalogo_hijo !== 'undefined') {//Aplica regla de catálogos, por que el elemento es un catalogo
            //console.log(regla_catalogo);
            if (regla_catalogo !== 'undefined' && regla_catalogo != null && regla_catalogo.toString() != "") {
                //console.log(regla_catalogo);
                //console.log('cargando elementos');
                if(typeof lanzar !== 'undefined' && lanzar == false){//Para validar si es necesario poner ajax de modo sincrono 
                    $.ajaxSetup({async: false});  
                }
                carga_hijos_elemento_catalogo(catalogo_padre, value_padre, nombre_elemento_dependiente, elemento_dependiente.data("ruta"), memoria_values[nombre_elemento_dependiente], regla_catalogo, catalogo_hijo);
                $.ajaxSetup({async: true});  
            }
        }
//
    }
}


