$(document).ready(function() {
    $('select[name="id_seccion"]').on('change', function(event, parametros) {
        mostrar_loader();
        var id_sec = $(this).val();

        if(id_sec) {
            $.ajax({
                url : site_url + '/secciones/ajax_subsecciones/' + id_sec,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('select[name="id_catalogo_elemento_padre"]').empty();
                    $('select[name="id_catalogo_elemento_padre"]').append('<option value=""> Selecciona una opción </option>');
                    $.each(data, function(key, value) {
                        $('select[name="id_catalogo_elemento_padre"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });

                    if($('#id_catalogo_elemento_padre option').length > 1){
                        $('select[name="id_catalogo_elemento_padre"]').attr('disabled', false);
                    }else{
                        $('select[name="id_catalogo_elemento_padre"]').attr('disabled', true);
                    }
                    if((typeof parametros !== 'undefined') && (typeof parametros.id_catalogo_elemento_padre !== 'undefined')){
                        $('#id_catalogo_elemento_padre').val(parametros.id_catalogo_elemento_padre);
                    }
                }
            }).done(function(){
                ocultar_loader();
            });
        }else{
            $('select[name="id_catalogo_elemento_padre"]').empty();
        }

    });
});

$(document).on('submit','#form_editar_elemento_seccion',function (event)
{
    event.preventDefault();
    event.stopImmediatePropagation();
    $(".alert-danger").remove();

    var id_es = $('input[name="id_elemento_seccion"]').val();

    var destino = site_url + "/secciones/editar_elemento_seccion/" + id_es;

    data_ajax(destino,'#form_editar_elemento_seccion','#form_mensaje',mensaje_editar,true,[]);

});

function mensaje_editar(parametros){
    console.log(parametros.object);
    var json = parametros.object;
    var base = json.base;
    if(base==0){
        $('#form_mensaje').html('');
        $('#editar_elemento_seccion').html(json.html);
    }
}


function regresar () {
      //window.location = site_url + '/secciones/elementos_seccion';
      history.back();
}
