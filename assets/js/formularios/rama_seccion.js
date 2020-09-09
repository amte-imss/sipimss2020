var seccion = '';
var subseccion = '';

$(document).ready(function() {
    $('#boton_rama_seccion').attr('disabled', true);
	$('#id_seccion').on('change',function(){
        seccion = $(this).val();

        if(seccion){
            mostrar_loader();
            get_subseccion_padre('null');
            ocultar_loader();
        }
    });
});

function get_subseccion_padre(padre) {
    $.ajax({
        url: site_url + '/formulario/ajax_subseccion_padre',
        type: 'GET',
        dataType: 'json',
        data: {
            "padre" : padre,
            "seccion" : seccion,
            "nivel" : 1
        }
    })
    .done(function(data) {
        console.log("success");
        console.log(data);
        
        if(data['length'] > 0){
            $('#boton_rama_seccion').attr('disabled', false);
            $('#subsecciones').html(data['view']);
        }else{
            $('#boton_rama_seccion').attr('disabled', true);
            $('#subsecciones').html('<div class="col-md-6"><h3>No hay elementos para esta sección</h3></div>');
        }
        
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function get_subseccion_hijo(elemento) {
    mostrar_loader();
    var objeto = $(elemento);
    var nivel = objeto.data('nivel');
    var padre = document.getElementById('nivel_'+nivel).value;
    subseccion = padre;
    
    if(padre){
        var i = nivel+1;
        var siguiente = document.getElementById('nivel_'+ i);

        while(siguiente != null){
            $('.div_nv_'+ i).remove();
            i = i+1;
            siguiente = document.getElementById('nivel_'+ i);
        }
        
        $.ajax({
            url: site_url + '/formulario/ajax_subseccion_padre',
            type: 'GET',
            dataType: 'json',
            data: {
                "padre" : padre,
                "seccion" : seccion,
                "nivel" : (nivel + 1)
            }
        })
        .done(function(data) {
            console.log("success");
            console.log(data);
            
            if(data['length'] > 0){
                $('#subsecciones').append(data['view']);
            }else{
                $('#boton_rama_seccion').attr('disabled', false);
            }
            
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }
    ocultar_loader();
}

function cargar_formulario(argument) {
    event.preventDefault();
    event.stopImmediatePropagation();

    var destino = site_url + '/formulario/formulario/' + subseccion;
    window.location.replace(destino);
}