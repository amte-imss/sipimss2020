$(function(){

    var grid=$('#lista_usuarios').jsGrid({
        width: "100%",
        height: "500px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
        paging: true,
        autoload: true,
        pageSize: 10,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageLoading: true,
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No se encontraron datos",
        controller: {
          loadData: function (filter) {
              //console.log(filter);
              mostrar_loader();
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "GET",
                  url: site_url + "/usuario/get_usuarios/lista/",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                  ocultar_loader();
                          console.log(result);
                          console.log(result.data);
                          d.resolve({
                              data: result.data,
                              itemsCount: result.length,
                          });
                      });

              return d.promise();
          },
          insertItem: $.noop,
          updateItem: $.noop,
          deleteItem: $.noop
        },
        fields: [
                    {name: 'id_usuario', title: "#", visible: false},
                    {name: 'matricula', title: 'Matrícula', type: 'text'},
                    {name: 'nombre', title: 'Nombre completo', type: 'text'},
                    {name: 'delegacion', title: 'Delegación', type: 'text'},
                    {name: 'unidad', title: 'Unidad', type: 'text'},
                    {type: "control", editButton: false, deleteButton: false, width: "10%",
                     itemTemplate: function (value, item) {
			 var enlace_editar = '<a href="'+site_url+'/usuario/get_usuarios/'+item.id_usuario+'">Editar</a>';
             var enlace_entrar_como = '<a  data-toggle="modal" data-target="#modal_entrar_como" onclick="entrar_como('+item.id_usuario+');">Entrar como</a>';
             var eliminar_usuario = '<a  data-toggle="modal" data-target="#modal_eliminar_user" onclick="eliminar_usuario('+item.id_usuario+');">Eliminar usuario</a>'
			 var elemento = enlace_editar + ' | ' + enlace_entrar_como + ' | ' + eliminar_usuario  ;
                         return elemento;
                    }}
                ]
            }
        );
        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#lista_usuarios").jsGrid("option", "pageSize", page);
        });


});


function entrar_como(id){
    var destino = site_url + '/administracion/entrar_como/' + id;
    $('#form_entrar_como').attr('action', destino);
}

function eliminar_usuario(id){
    var destino = site_url + '/usuario/eliminar/';    
    $('#usuario_expuesto').val(id);
    $('#form_eliminar_usuario').attr('action', destino);
}

function elimina_usuario_registro(element){    
    
    var datos ={usuario_expuesto: $('#usuario_expuesto').val()};
    var url=  site_url + "/usuario/eliminar";
    $.ajax({
        //                url: site_url + '/actividad_docente/datos_actividad',
                        url: url,
                        data: datos,
                        type: 'POST',
                        //contentType: "charset=utf-8",
                        dataType: 'JSON',
                        beforeSend: function (xhr) {
                            mostrar_loader();
                        }
                    })
                            .done(function (data) {
                                //console.log(data);
                                try {//Cacha el error
                                    
                                    //var resp = $.parseJSON(data);
                                    var resp = data;
                                    if (typeof resp.tp_msg !== 'undefined') {
                                        if (resp.tp_msg === 'success') {                                                                                                                                                                             
                                            get_mensaje_expone(resp.mensaje, resp.tp_msg, 5000, 'div_error','alerta','msg');
                                            setTimeout("location.reload()", 4000);                                                                                                                            
                                        } else {
                                            $('#close_delete').click();
                                            $('#modal').modal('hide');
                                            get_mensaje_expone(resp.mensaje, resp.tp_msg, 10000, 'div_error','alerta','msg');
                                        }
                                        
                                    }
                                } catch (e) {
                                    console.log("Error conversión JSON " + e);
                                }
        
                            })
                            .fail(function (jqXHR, response) {
        //                        $(div_respuesta).html(response);
                                get_mensaje_general('Ocurrió un error durante el proceso, inténtelo más tarde.', 'warning', 5000);
                            })
                            .always(function () {
                                ocultar_loader();
                            });
        

    

}

function get_mensaje_expone(mensaje, tipo_mensaje, timeout, div_padre, div_tipo_msg, div_msg) {   
    div_padre = "#"+div_padre;
    $('#'+div_tipo_msg).removeClass('alert-danger').removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning');
    $('#'+div_tipo_msg).addClass('alert-' + tipo_mensaje);
    $('#'+div_msg).html(mensaje);
    $(div_padre).show();
    setTimeout("$('"+div_padre+"').hide()", timeout);
}
