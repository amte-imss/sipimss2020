$(document).ready(function () {//Ejecuta al final de la página
//        console.log(array_padres_dependientes);
    $(".ctr_dependientes").each(function (index) {
//        console.log(this);
        actualiza_campos_dependientes(this, false);
    });
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
    if (typeof array_padres_dependientes !== 'undefined' && typeof array_padres_dependientes[element.name] !== 'undefined') {
        var configuracion = array_padres_dependientes[element.name];
        //console.log(memoria_values);
        Object.keys(configuracion.campos).forEach(function (index) {
            var dependientes = configuracion.campos[index];
            //console.log("Recorre campos " + dependientes);
            //console.log("Dependientes " + dependientes);
            //console.log("Configuracion " + configuracion.elementos[dependientes]);
            //console.log("value " + objeto_this.val());
            //document.getElementById(dependientes).value = 1;

            if (document.getElementById(dependientes)) {//Valida que exista una dependencia por identificacor
                if (typeof configuracion.elementos[dependientes] !== 'undefined') {//Valida que exista una dependencia por identificacor
                    var opciones = configuracion.elementos[dependientes];
                    if (opciones.length > 0) {
                        var no_aplico_opciones = 1;
                        for (var i = 0; i < opciones.length; i++) {
                            if (objeto_this.val().toString() === opciones[i].toString()) {//
                                control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 1);//Ejecuta cambio en el control
                                no_aplico_opciones = 0;
                                break;//Sale del ciclo
                            }
                        }
                        if (no_aplico_opciones == 1) {
                            control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 2);//Ejecuta cambio en el control
                        }
                    } else {
                        control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 2);//Ejecuta cambio en el control
                    }
                } else {//No existe dependencia  y ejecuta la acción
                    control_dependientes(dependientes, objeto_this.data('catalogo'), objeto_this.val(), 0);//Ejecuta cambio en el control
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

function control_dependientes(nombre_elemento_dependiente, catalogo_padre, value_padre, tipo_aplicacion_dependencias) {
    var div_control = $('#div_' + nombre_elemento_dependiente);
    var elemento_dependiente = $("#" + nombre_elemento_dependiente);
    var catalogo_hijo = elemento_dependiente.data('catalogo');
    var regla_catalogo = elemento_dependiente.data('reglacatalogo');
    var display = elemento_dependiente.data("displaycomponente");
    if (value_padre === "") {
        div_control.css("display", display);
        div_control.toggle("slow");//Evento, forma de salida
    } else {
        switch (tipo_aplicacion_dependencias) {
            case 1://Opción unica
                div_control.css("display", "none");
                div_control.toggle("slow");//Evento, forma de salida
                break;
            case 2: //
                div_control.css("display", display);
                div_control.toggle("slow");//Evento, forma de salida
                elemento_dependiente.val("");
                break;
            default ://cualquier opción
                div_control.css("display", "none");
                div_control.toggle("slow");//Evento, forma de salida

        }
        if (typeof catalogo_hijo !== 'undefined') {//Aplica regla de catálogos, por que el elemento es un catalogo
            //console.log(catalogo_hijo);
            //console.log(regla_catalogo);
            if (regla_catalogo !== 'undefined' && regla_catalogo != null && regla_catalogo.toString() != "") {
                console.log('cargando elementos');
                carga_hijos_elemento_catalogo(catalogo_padre, value_padre, nombre_elemento_dependiente, elemento_dependiente.data("ruta"), memoria_values[nombre_elemento_dependiente], regla_catalogo, catalogo_hijo);
            }
        }
//
    }
}
