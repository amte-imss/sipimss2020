
$(document).ready(function() {
    var reglas_catalogos = $('select[name="reglas_catalogos"]');
    var catalogo = $('select[name="id_catalogo"]');
    var excepciones_opciones = $('select[name="excepciones_opciones[]"');

    if(!catalogo.val()){
        reglas_catalogos.attr('disabled', 'true');
    }
    if(!reglas_catalogos.val()){
        excepciones_opciones.attr('disabled', 'true');
    }else{
        casos_reglas_catalogo(reglas_catalogos.val());
    }

    // Activamos las reglas para catalogos

    catalogo.on('change',function(){
        $('select[name="reglas_catalogos"]').val('');
        $('.form_item_catalogo_handler').css('display', 'none');
        if(catalogo.val()){
            reglas_catalogos.attr('disabled', false);
            $('#form_campos_reglas_excepcion').toggle("slow");//Evento, forma de salida
            // $('#form_campos_opciones_excepcion').toggle("slow");//Evento, forma de salida
            $('#form_campos_dependientes').toggle("slow");//Evento, forma de salida
            get_elementos_catalogo(catalogo.val());
        }else{
            reglas_catalogos.attr('disabled', true);
            excepciones_opciones.attr('disabled', true);
            //excepciones_opciones.empty();
        }
        $('select[name="reglas_catalogos"]').trigger( "change" );
    });

    // Opciones para excepcion
    reglas_catalogos.on('change',function(){
        casos_reglas_catalogo(reglas_catalogos.val());
    });
});

function casos_reglas_catalogo(regla){
    $('#form_campos_opciones_excepcion').css('display', 'none');
    $('#form_regla_catalogo_dependiente').css('display', 'none');
    $('#clave_regla_dependencia_catalogo').attr('required', false);
    switch(regla){
        case '0':
        case '4':
            $('select[name="excepciones_opciones[]"').attr('disabled', true);
            break;
        case '1':
        case '2':
            $('#form_campos_opciones_excepcion').toggle("slow");//Evento, forma de salida
            $('select[name="excepciones_opciones[]"').attr('disabled', false);
            break;
        case '3':
            $('#clave_regla_dependencia_catalogo').attr('required', true);
            $('#form_regla_catalogo_dependiente').toggle("slow");//Evento, forma de salida
        break;
        default:
            $('select[name="excepciones_opciones[]"').attr('disabled', true);
    }
}

function get_elementos_catalogo(id_catalogo){
    $.ajax({
        url : site_url + '/formulario/ajax_excepcion_catalogo/' + id_catalogo,
        type: "GET",
        dataType: "json",
        success:function(data) {
            $('select[name="excepciones_opciones[]"]').empty();
            $.each(data, function(key, value) {
                $('select[name="excepciones_opciones[]"]').append('<option value="'+ key +'">'+ value +'</option>');
            });
            get_reglas_dependencia_catalogos(id_catalogo);
        }
    });
}


function get_reglas_dependencia_catalogos(id_catalogo){
    $.ajax({
        url : site_url + '/formulario/ajax_reglas_dependencia_catalogos/' + id_catalogo,
        type: "GET",
        dataType: "json",
        success:function(data) {
            $('#clave_regla_dependencia_catalogo').empty();
            $('#clave_regla_dependencia_catalogo').append('<option value="">Seleccione una opción</option>');
            $.each(data, function(key, value) {
                $('#clave_regla_dependencia_catalogo').append('<option value="'+ key +'">'+ value +'</option>');
            });
        }
    });
}

$(document).on('submit','#form_editar_campo_formulario',function (event)
{
    var id_form = $('input[name="id_campos_formulario"]').val();
    event.preventDefault();
    event.stopImmediatePropagation();
    $(".alert-danger").remove();
    console.log(json_campos_dependientes);
    var reglas_catalogos = $('select[name="reglas_catalogos"]');
    if(reglas_catalogos.val()!=3){
        $('#clave_regla_dependencia_catalogo').val('');
    }
    document.getElementById('campos_dependientes').value = JSON.stringify(json_campos_dependientes);
    var destino = site_url + "/formulario/editar_campo_formulario/" + id_form ;

    data_ajax(destino,'#form_editar_campo_formulario','#form_mensaje',mensaje_nuevo,true,[]);
});

function mensaje_nuevo(parametros){
    console.log(parametros.object);
    var json = parametros.object;
    var base = json.base;
    if(base==0){
        $('#form_mensaje').html('');
        $('#editar_campo_formulario').html(json.html);
    }
}


function regresar() {
    var id_form = $('input[name="id_formulario"]').val();
    window.location = site_url + '/formulario/campos_formulario/' + id_form;
}


function show_modal_campos_dependientes(){
    $('#modal_campos_dependientes').modal('show');
    mostrar_loader();
    var id_formulario = $('#id_formulario').val();
    var id_catalogo =$('#id_catalogo').val();
    $.ajax({
        url : site_url + '/formulario/ajax_configuracion_campos_formulario',
        type: "POST",
        data: {id_formulario:id_formulario, id_catalogo:id_catalogo},
        dataType: "json"
    }).done(function(data){
        var db_campos_formulario ={campos:[
            {id_campos_formulario:1, id_campo:2, id_formulario:3, campo:'Otro campo'}
        ]};
        // muestra_grid_reglas_dependencias(db_campos_formulario);
        muestra_grid_reglas_dependencias(data);
        ocultar_loader();
    });

    $("#pager").on("change", function() {
        var page = parseInt($(this).val(), 10);
        $("#grid_campos_dependientes").jsGrid("option", "pageSize", page);
    });
}

function guardar_dependencias_catalogos(ocultar){
    if(typeof json_campos_dependientes.campos === 'undefined'){
        json_campos_dependientes.campos = [];
    }
    if(typeof json_campos_dependientes.elementos === 'undefined'){
        json_campos_dependientes.elementos = {};
    }
    var campos = $('.campos_dependientes_info');
    campos.each(function(){
        // console.log($(this).attr('name'));
        var campo = $(this).attr('name').replace('ch_', '');
        var select = 's_' + campo;
        if ($(this).is(":checked")) {
            if(json_campos_dependientes.campos.indexOf(campo)<0){
                json_campos_dependientes.campos.push(campo);
            }
            var item = {};
            if($('#' + select).val()!=null){
                if(json_campos_dependientes.campos.indexOf(campo)>-1){
                    json_campos_dependientes.elementos[campo] = $('#' + select).val();
                }
            }
        }
    });
    if(ocultar == null){
        $('#modal_campos_dependientes').modal('hide');
    }
}

function muestra_grid_reglas_dependencias(db_campos_formulario)
{
    var grid=$('#grid_campos_dependientes').jsGrid({
        width: "100%",
        height: "500px",
        // deleteButton: true,
        filtering: true,
        editing: false,
        sorting: true,
        selecting: false,
        paging: true,
        autoload: true,
        pageSize: 10,
        // pageSize: 1,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No se encontraron datos",
        data: db_campos_formulario.campos,
        controller: {
                loadData: function(filter) {
                    //console.log(filter);
                var d = $.Deferred();
                //var result = null;
                
                var res = $.grep(db_campos_formulario.campos, function (registro) {
                    //console.log(filter.campo_dependiente);
                    var result = true;
                    var select_campo_dependiente = false;
                    var aplicaFiltro_dependiente = false;
                     
                    if (typeof filter.campo_dependiente === 'undefined'){
                        aplicaFiltro_dependiente = true;                            
                    }else{
                        if(json_campos_dependientes.campos.indexOf(registro.nombre)>-1){
                            var select_campo_dependiente = true;
                        }
                    }
                   result = (!filter.campo || (registro.campo !== null && registro.campo.toLowerCase().indexOf(filter.campo.toString().toLowerCase()) > -1)) 
                   && (aplicaFiltro_dependiente || (select_campo_dependiente == filter.campo_dependiente))
                    return result;
                });
                d.resolve(res);
                return d.promise();
                },
            },
        fields: [
                    {name: 'id_campos_formulario', title: "#", visible: false},
                    {name: 'id_campo', title: "#", visible: false},
                    {name: 'id_formulario', title: "#", visible: false},
                    {name: 'nombre', title: "#", visible: false},
                    {name: 'campo', title: "Campo", type:'text'},
                    {name: 'campo_dependiente', title:'Es campo dependiente', type:"checkbox", itemTemplate:function(value, item){
                        var elemento = $('<input type="checkbox" class="campos_dependientes_info" name="ch_'+item.nombre+'">');
                        if(json_campos_dependientes.campos.indexOf(item.nombre)>-1){
                            elemento.attr('checked',true);
                        }
                        elemento.click(function(event){
                            var select = 's_' + $(this).attr('name').replace('ch_', '');
                            if ($(this).is(":checked")) {
                                $('#' + select).css('display', 'initial');
                            }
                            else {
                                $('#' + select).css('display', 'none');
                                var i_index = json_campos_dependientes.campos.indexOf(item.nombre);
                                if(i_index>-1){
                                    json_campos_dependientes.campos.splice(i_index, 1);
                                    delete json_campos_dependientes.elementos[item.nombre];
                                }
                            }
                            guardar_dependencias_catalogos(false);
                        });
                        return elemento;
                    }, width:'15%'},
                    {title:'Elementos de los que depende', itemTemplate:function(value, item){
                        var style = '';
                        style = json_campos_dependientes.campos.indexOf(item.nombre)>-1?'':'style="display:none;"';
                        var elemento = $('<select multiple name="s_'+item.nombre+'" id="s_'+item.nombre+'" class="elementos_dependientes_info" '+style+'>');
                        // elemento.append('<option value="">Seleccione una opción</option>');
                        var selected = '';
                        for (var i = 0; i < db_campos_formulario.elementos.length; i++) {
                            selected =  elemento_asignado(item.nombre, db_campos_formulario.elementos[i].id_elemento_catalogo)?'selected':'';
                            elemento.append('<option value="'+db_campos_formulario.elementos[i].id_elemento_catalogo+'" '+selected+'>'+db_campos_formulario.elementos[i].nombre+'</option>');
                        }
                        elemento.on('change', function(){
                            guardar_dependencias_catalogos(false);
                        });
                        return elemento;
                    }},
                ]
            }
    );
}

function elemento_asignado(campo, id_elemento){
    // console.log('buscando para:' + campo + ", elemento: " + id_elemento);
    var status = false;
    if(typeof json_campos_dependientes.elementos[campo] !== 'undefined' && json_campos_dependientes.elementos[campo].indexOf(""+id_elemento)>-1){
        status = true;
    }
    return status;
}
