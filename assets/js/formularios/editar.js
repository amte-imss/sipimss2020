$(document).on('submit','#form_editar',function (event)
{
    var id_form = $('input[name="id_formulario"]').val();
    event.preventDefault();
    event.stopImmediatePropagation();
    $(".alert-danger").remove();

    var destino = site_url + "/formulario/editar_formulario/" + id_form ;

    data_ajax(destino,'#form_editar','#form_mensaje',mensaje_nuevo,true,[]);
});

function mensaje_nuevo(parametros){
    console.log(parametros.object);
    var json = parametros.object;
    var base = json.base;
    if(base==0){
        $('#form_mensaje').html('');
        $('#editar_formulario').html(json.html);
    }
}


function regresar() {
    var id_form = $('input[name="id_formulario"]').val();                                 
    window.location = site_url + '/formulario/editar_formulario/' + id_form;
}