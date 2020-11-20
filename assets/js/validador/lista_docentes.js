
$(document).ready(function () {
    console.log("saludos");
    lista_docentes();
});

function lista_docentes(){
    //console.log(site_url + url_ctr + "/docentes/");s
    $(function() {
    var grid_list=$('#js_grid_lista_docentes').jsGrid({
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
                  url: site_url + url_ctr + "/docentes",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                  //console.log(result.datos_docentes);
                  $("#docentes_registrados").text(result.datos_docentes.length + " docentes registrados");
                          /*d.resolve({
                              data: result.datos_docentes,
                              itemsCount: result.datos_docentes.length,
                          });*/
                          var res = $.grep(result.datos_docentes, function (registro) {
                            return (!filter.clave_delegacional || (registro.clave_delegacional != null && (registro.clave_delegacional == filter.clave_delegacional)))
                            && (!filter.delegacion || (registro.delegacion !== null && registro.delegacion.toLowerCase().indexOf(filter.delegacion.toString().toLowerCase()) > -1))
                            && (!filter.matricula || (registro.matricula !== null && registro.matricula.toLowerCase().indexOf(filter.matricula.toString().toLowerCase()) > -1))
                            && (!filter.nombre_docente || (registro.nombre_docente !== null && registro.nombre_docente.toLowerCase().indexOf(filter.nombre_docente.toString().toLowerCase()) > -1))
                            && (!filter.email || (registro.email !== null && registro.email.toLowerCase().indexOf(filter.email.toString().toLowerCase()) > -1))
                            && (!filter.id_docente_carrera || (registro.id_docente_carrera != null && (registro.id_docente_carrera == filter.id_docente_carrera)))
                            && (!filter.id_status_validacion || (registro.id_status_validacion != null && (registro.id_status_validacion == filter.id_status_validacion)))
                            && (!filter.total_registros_censo || (registro.total_registros_censo != null && (registro.total_registros_censo == filter.total_registros_censo)))
                            && (!filter.ratificado || (registro.ratificado != null && (registro.ratificado == filter.ratificado)))
                            && (!filter.nom_unidad || (registro.nom_unidad !== null && registro.nom_unidad.toLowerCase().replace(/ /g, "").indexOf(filter.nom_unidad.toString().toLowerCase().replace(/ /g, "")) > -1))
                            && (!filter.umae || (registro.umae !== null && registro.umae.toLowerCase().replace(/ /g, "").indexOf(filter.umae.toString().toLowerCase().replace(/ /g, "")) > -1))
                          });
                          d.resolve(res);
                      });
                      ocultar_loader();
              return d.promise();
          },         
        },
        fields: [
            {name: 'clave_delegacional', title: "OOAD", type: "select", items: delegaciones,valueField: "clave_delegacional", textField: "nombre",  visible:true, filtering:!bloquea_delegacion},
            {name: 'delegacion', title: "Delegacion", type: "text",  visible:false},
            {name: 'nom_unidad', title: "Unidad", type: "text",  visible:true},
            {name: 'umae', title: "Unidad", type: "text",  visible:false},
            {name: 'matricula', type: "text", title: "Matrícula", visible:true},
            {name: 'nombre_docente', type: "text", title: "Nombre docente", visible:true},
            {name: 'email', title:"Correos", type: "text",  visible:true},
            {name: 'id_docente_carrera', title: "Docente de carrera", type: "select", items:fase_carrera_docente, valueField: "id_docente_carrera", textField: "descripcion", visible:true},
            {name: 'id_status_validacion', title: "Estado validación", type: "select", items:estados_validacion, valueField: "id", textField: "label", visible:true},
            {name: 'ratificado', title: "Ratificado", type: "select", items:ratificado, valueField: "id", textField: "label", visible:false},
            {name: 'total_registros_censo', title: "Total registros", type: "text", visible:true},
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
                    var liga = '<a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'/2">Ver detalle</a>';
                    var liga_aux = '';
                    var control_normal = true;
                    var ambas = 0;                  
                    var name_boton = 'Ver validación';
                    if(typeof item.permite_validacion !== "undefined"){
                        if(item.permite_validacion==1){
                            if((item.id_status_validacion != 1 || item.id_status_validacion != '1') && item.total_registros_censo > 0){
                                liga_aux +='<br><a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'">'+name_boton+'</a>';                         
                                ambas += 1;
                            }
                        }
                        
                        control_normal = false;
                    }
                    if(typeof item.permite_ratificacion !== "undefined"){                        
                        
                        if(item.permite_ratificacion==1){
                            if(item.id_status_validacion == 7 || item.id_status_validacion == '7' || item.id_status_validacion == 3 || item.id_status_validacion == '3'){
                                //name_boton = 'Ver ratificación';
                                name_boton = 'Ver validación';
                                liga_aux +='<br><a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'">'+name_boton+'</a>';                         
                                ambas += 1;
                            }
                        }
                        control_normal = false;
                    }
                    if(ambas == 2){
                        //name_boton = 'Ver ratificación';
                        name_boton = 'Ver validación';
                        liga +='<br><a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'">'+name_boton+'</a>';                         
                    }else{
                        liga += liga_aux;
                    }
                    if(control_normal){
                        name_boton = 'Ver validación';
                        if((item.id_status_validacion != 1 || item.id_status_validacion != '1') && item.total_registros_censo > 0){
                            if(item.id_status_validacion == 7 || item.id_status_validacion == '7' || item.id_status_validacion == 3 || item.id_status_validacion == '3'){
                                name_boton = 'Ver ratificación';
                            }
                            liga +='<br><a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'">'+name_boton+'</a>';                         
                        }
                    }
                    if(editar_reg_doc == 1 && item.id_status_validacion == 8){
                        //liga +='<br><button data-docente="'+item.id_docente+'" onclick="permite_edicion_docente(this);" class="link">Habilitar edición de registro censo</button>';
                        liga +='<br><a  data-docente="'+item.id_docente+'" onclick="permite_edicion_docente(this);" >Habilitar edición de registro censo</a>';
                    }
                    
                    if(permiso==1){
                        //liga = '<a href="'+site_url+'/usuario/get_usuarios/'+item.id_usuario+'">Editar</a> | ' + liga;
                    }

                    return liga; 
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

function obtener_cabeceras() {
    var arr_header = {
        clave_delegacional: 'OOAD',
        delegacion: 'Delegacion',
        nom_unidad: 'Unidad',
        umae: 'UMAE',
        matricula: 'Matrícula',
        nombre_docente: 'Nombre docente',
        email: 'Correo electrónico',
        id_docente_carrera: 'Fase docente de carrera',
        id_status_validacion: 'Estatus',
        ratificado: 'Ratificado',
        total_registros_censo: 'Total de registros'
    }

    return arr_header;
}

function cabeceras_no_exportar() {
    var arr_header = {
        acciones: 'Acciones',
    }
    return arr_header;
}


function exportar_lista_docentes(element) {
    var namegrid = $(element).data('namegrid');
    var headers = remove_headers(obtener_cabeceras(), cabeceras_no_exportar());
//    var headers = obtener_cabeceras_implementaciones();
    export_xlsx_grid(namegrid, headers, 'docentes', 'docentes');
}

function permite_edicion_docente(element) {
    var docente = $(element).data('docente');
    var url = url_ctr + "/habilita_edicion";
    apprise('Confirme que realmente desea activar la edición del registro del censo', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            $.ajax({                
                    type: "POST",                
                    url: site_url + url,
                    data: {docente:docente},
                    dataType: "json",
                    beforeSend: function (xhr) {
                //            $('#tabla_actividades_docente').html(create_loader());
                                    mostrar_loader();
                                }
                            })
                                    .done(function (data) {
                                        try {//Cacha el error
                                            //var resp = $.parseJSON(data);
                                            var resp = data;
                                            //console.log(resp);
                                            if (typeof resp.success !== 'undefined' && (resp.success == 1 || resp.success == "1")) {
                                                console.log("resp");
                                                location.reload();
                                            }
                                        } catch (e) {
                                            //$(div_respuesta).html(data);
                                        }
                
                                    })
                                    .fail(function (jqXHR, response) {
                //                        $(div_respuesta).html(response);
                                        get_mensaje_general('Ocurrió un error durante el proceso, inténtelo más tarde.', 'warning', 5000);
                                    })
                                    .always(function () {
                                        ocultar_loader();
                                    });
                
                        } else {
                            return false;
                        }
                    });
   
}

function habilita_edicion_general(element) {
    var docente = $(element).data('docente');
    var url = url_ctr + "/habilita_edicion_general";
    apprise('Confirme que realmente desea activar la edición del registro del censo para aquellos que tienen cero registros', {verify: true}, function (btnClick) {
        if (btnClick) {//Continua con el guardado de las secciones
            $.ajax({                
                    type: "POST",                
                    url: site_url + url,
                    data: {docente:docente},
                    dataType: "json",
                    beforeSend: function (xhr) {
                //            $('#tabla_actividades_docente').html(create_loader());
                                    mostrar_loader();
                                }
                            })
                                    .done(function (data) {
                                        try {//Cacha el error
                                            //var resp = $.parseJSON(data);
                                            var resp = data;
                                            //console.log(resp);
                                            if (typeof resp.success !== 'undefined' && (resp.success == 1 || resp.success == "1")) {
                                                //console.log("resp");
                                                location.reload();
                                            }
                                        } catch (e) {
                                            //$(div_respuesta).html(data);
                                        }
                
                                    })
                                    .fail(function (jqXHR, response) {
                //                        $(div_respuesta).html(response);
                                        get_mensaje_general('Ocurrió un error durante el proceso, inténtelo más tarde.', 'warning', 5000);
                                    })
                                    .always(function () {
                                        ocultar_loader();
                                    });
                
                        } else {
                            return false;
                        }
                    });
   
}