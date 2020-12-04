
$(document).ready(function () {
    lista_validadores();
});



function selecciona_todo(element){
    var data  = $(element);
    
    var value = data.prop("checked");
    

    $(".lista_docentes").each(function (index) {
        //console.log(this);
        //console.log(index);
        //console.log(this.name);
        $(this).prop("checked", value);
    });
}
function selecciona_todo_limbo(element){
    var data  = $(element);
    
    var value = data.prop("checked");
    

    $(".lista_docentes_limbo").each(function (index) {
        //console.log(this);
        //console.log(index);
        //console.log(this.name);
        $(this).prop("checked", value);
    });
}
function lista_validadores(){
    //console.log(site_url + url_ctr + "/docentes/");
    $(function() {
    var grid_list=$('#js_grid_lista_validadores').jsGrid({
        height: "520px",
        width: "100%",
//        deleteConfirm: "¿Deseas eliminar este registro?",
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
        paging: true,
        autoload: true,
        pageSize: 10,
        rowClick: function (args) {
            //console.log(args);
        },
        pageButtonCount: 5,
        pagerFormat: "Páginas: {pageIndex} de {pageCount}    {first} {prev} {pages} {next} {last}   Total: {itemCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No se encontraron datos",
        invalidMessage: "",
        loadMessage: "Por favor espere",
        onItemUpdating: function (args) {
        },
        onItemEditing: function (args) {
        },
        cancelEdit: function () {
        },
        controller: {
          loadData: function (filter) {
              mostrar_loader();
              //console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "POST",
                  url: site_url + url_ctr + "/validadores_nivel1/",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                  //console.log(result.datos_docentes);
                  $("#validadores_registrados").text(result.datos_docentes.length + " validadores registrados");
                          /*d.resolve({
                              data: result.datos_docentes,
                              itemsCount: result.datos_docentes.length,
                          });*/
                          var res = $.grep(result.datos_docentes, function (registro) {
                            return (!filter.clave_delegacional || (registro.clave_delegacional != null && (registro.clave_delegacional == filter.clave_delegacional)))
                            && (!filter.matricula || (registro.matricula !== null && registro.matricula.toLowerCase().indexOf(filter.matricula.toString().toLowerCase()) > -1))
                            && (!filter.nombre_docente || (registro.nombre_docente !== null && registro.nombre_docente.toLowerCase().indexOf(filter.nombre_docente.toString().toLowerCase()) > -1))
                            && (!filter.email || (registro.email !== null && registro.email.toLowerCase().indexOf(filter.email.toString().toLowerCase()) > -1))
                            && (!filter.clave_rol || (registro.clave_rol != null && (registro.clave_rol == filter.clave_rol)))
                            && (!filter.umae || (registro.umae !== null && registro.umae.toLowerCase().indexOf(filter.umae.toString().toLowerCase()) > -1))
                            && (!filter.nom_unidad || (registro.nom_unidad !== null && registro.nom_unidad.toLowerCase().indexOf(filter.nom_unidad.toString().toLowerCase()) > -1))
                            && (!filter.total || (registro.total !== null && registro.total.toLowerCase().indexOf(filter.total.toString().toLowerCase()) > -1))
                          });
                          d.resolve(res);
                      });
                      ocultar_loader();
              return d.promise();
          },         
        },
        fields: [
            {name: 'id_usuario', title: "#", visible: false},
            {name: 'clave_delegacional', title: "OOAD", type: "select", items: delegaciones,valueField: "clave_delegacional", textField: "nombre",  visible:true},
            {name: 'matricula', type: "text", title: "Matrícula", visible:true},
            {name: 'nombre_docente', type: "text", title: "Nombre validador", visible:true},
            {name: 'email', title:"Correos", type: "text",  visible:true},            
            {name: 'nom_unidad', type: "text", title: "UNIDAD", visible:true},
            {name: 'umae', type: "text", title: "UMAE", visible:true},
            {name: 'total', type: "number", title: "# docentes registrados", visible:true, filtering:false},
            //{name: 'id_elemento_catalogo_padre', title: 'Elemento padre', type: 'select', items: json_elementos_catalogo_padre, valueField: "id_elemento_catalogo", textField: "label"},
            //{name: 'id_elemento_catalogo_hijo', title: 'Elemento hijo', type: 'select', items: json_elementos_catalogo_hijo, valueField: "id_elemento_catalogo", textField: "label"},
            {type: "control", editButton: false, deleteButton: false, visible:true,
                searchModeButtonTooltip: "Cambiar a modo búsqueda", // tooltip of switching filtering/inserting button in inserting mode
                editButtonTooltip: "Editar", // tooltip of edit item button
                searchButtonTooltip: "Buscar", // tooltip of search button
                clearFilterButtonTooltip: "Limpiar filtros de búsqueda", // tooltip of clear filter button
                updateButtonTooltip: "Actualizar", // tooltip of update item button
                cancelEditButtonTooltip: "Cancelar", // tooltip of cancel editing button
                itemTemplate: function (value, item) {
                    //console.log(cambiar_validadorN1);
                    var rutas = '<input class="validador_nuevo" name="validador_nuevo" type="radio" value="'+item.id_usuario+'">';
                   
                    return rutas;
                }
            }
        ]
            }
        ).data("JSGrid");
    });
        /*var origFinishInsert = jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert;
        jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert = function (insertedItem) {
            if (!this._grid.insertSuccess) { // define insertFailed on done of delete ajax request in insertFailed of controller
                return;
            }
            origFinishInsert.apply(this, arguments);
        };

        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#js_grid_lista_docentes").jsGrid("option", "pageSize", page);
        });*/
}

function guarda_cambio_validador(elemento){
    var data_element = $(elemento);
    var seccion = data_element.data("seccion");
    var path = site_url + url_ctr + "/guarda_cambio_validador";
    var formulario = "form_cambio_validador";
    var dataSend = new FormData($('#' + formulario)[0]);    
    //var dataSend = $('#' +formulario).serialize();
    var div_respuesta = '';
    //console.log(formulario);
    //console.log(path);
    //console.log(dataSend);
    //break;
    $.ajax({

    url: path,
    data: dataSend,
    type: 'POST',
    mimeType: "multipart/form-data",
    contentType: false,
//                contentType: "charset=utf-8",
    cache: false,
    processData: false,
//                dataType: 'JSON',
    beforeSend: function (xhr) {
//            $('#tabla_actividades_docente').html(create_loader());
        mostrar_loader();
    }
})
        .done(function (data) {
            try {//Cacha el error
                $(div_respuesta).empty();
                var resp = $.parseJSON(data);
                //console.log(resp);                    
                if (typeof resp.mensaje !== 'undefined') {//Muestra mensaje al usuario si este existe
                    if (resp.tp_msg === 'success') {                        
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 5000, 'div_error','alerta','msg');                                                
                        $("#btn_guardar_cambio_val").css("display", "none");   
                        setTimeout("location.reload()", 4000);        
                                           
                    }else{
                        get_mensaje_general_validacion(resp.mensaje, resp.tp_msg, 10000, 'div_error','alerta','msg');                       
                    }
                }
                
            } catch (e) {
                console.log("Error");
                //$(div_respuesta).html(data);
            }

        })
        .fail(function (jqXHR, response) {
//                        $(div_respuesta).html(response);
            get_mensaje_general_validacion('Ocurrió un error durante el proceso, inténtelo más tarde.', 'danger', 5000, 'div_error','alerta','msg');
        })
        .always(function () {
            ocultar_loader();
        });

}

function get_mensaje_general_validacion(mensaje, tipo_mensaje, timeout, div_padre, div_tipo_msg, div_msg) {   
    div_padre = "#"+div_padre;
    $('#'+div_tipo_msg).removeClass('alert-danger').removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning');
    $('#'+div_tipo_msg).addClass('alert-' + tipo_mensaje);
    $('#'+div_msg).html(mensaje);
    $(div_padre).show();
    setTimeout("$('"+div_padre+"').hide()", timeout);
}

