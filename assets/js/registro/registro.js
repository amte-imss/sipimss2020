//Función que muestra los formularios de pendiendo cual ha
//sellecionado
function muestraFormularios(obj){
    if(obj.value != ""){
        console.log("Habilitado");
        $('#formulario').attr("disabled", false);
        $('#rCompleto').attr("disabled", false);
        $('#rIncompleto').attr("disabled", false);
        $('#fformulario').attr("disabled", false);
        mostrarMensajeSeccion("");
        obtener_formularios(obj.value);
    }else{
        desabilitaSeccionCarga();
    }
}

//Función que se desabilita la sección
function desabilitaSeccionCarga(){
    console.log("Desabilitado");
    mostrarMensajeSeccion("Seleccione una seccion para obtener los formulario");
    $('#formulario').attr("disabled", true);
    $('#descargarEstrutura').attr("disabled", true);
    $('#rCompleto').attr("disabled", true);
    $('#rIncompleto').attr("disabled", true);
    $('#fformulario').attr("disabled", true);
}

//Función que muestra el mensaje de la secciójn escogida
function mostrarMensajeSeccion(mensaje){
    $('#mensajeRegistro').text(mensaje);
}

//Función que muestra mensaje de los formularios
function mostrarMensajeFormulario(mensaje){
    $('#mensajeFormulario').text(mensaje);
}

//http://localhost:8080/sipimss_ii/index.php/catalogo/restfull_modulos/elementos_catalogos/exportar/
//Función que muestra el boton de exportar
function muestraBotonExportar(formulario){
    console.log("Formulario: ", formulario);
    $('#descargarEstrutura').attr("disabled", false);
    $('#descargarEstrutura').css( "display","inline");
    $('#descargarEstrutura').text("Descargar formato");
    $('#descargarEstrutura').attr("href", site_url+"/registro/exportar/formulario/"+formulario.value);
}

//Función que obtiene los tipos de formularios de una sección
function obtener_formularios(id){
    $('#formulario').find('option').remove().end();
    $.ajax({
        method: "GET",
        url: site_url + "/registro/obtener_formularios/"+id,
    }).done(function( formularios ) {
        console.log("Respuesta de formularios: ", formularios);
        if(formularios.estatus == 'ok')
        {
            $('#formulario').append($('<option>', { value: "", text: "Seleccionar"}));
            if(formularios.datos.data.length > 0)
            {
                mostrarMensajeFormulario("");
                formularios.datos.data.forEach(function(formulario) {
                    $('#formulario').append($('<option>', { value: formulario.id_formulario, text: formulario.label}));
                });
            }
            else
            {
                mostrarMensajeFormulario("Está seccion no tiene formulario");
                desabilitaSeccionCarga();
            }
        }
        else
        {
            mostrarMensajeFormulario("Hubo un error al encontrar los formularios");
            desabilitaSeccionCarga();
        }

    });
}
