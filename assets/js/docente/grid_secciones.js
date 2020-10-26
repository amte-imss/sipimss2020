var ruta_secciones;
var data_actividades;
var columnas;
var catalogo_secciones_actividad_docente;
var token_seccion = '';
    $(document).ready(
        function () {            
            ruta_secciones = $("#js_grid_registros_seccion").data("ruta");
            
            control_registros(1);
           
        }
    );
function control_registros(recargar) {
    if (typeof this.data_actividades === 'undefined' || (typeof recargar !== 'undefined' || recargar === 1)) {
        recarga_secciones_dropdown();
                
    } else {
    
                /**
                 * resultado catalogo_secciones_actividad_docente, campos_mostrar_datatable,
                 *  textos_extra y datos_actividad_docente
                 */
                //        $("#js_grid_registros_seccion").jsGrid("refresh");
                
                $("#js_grid_registros_seccion").jsGrid("reset");
                carga_grid_actividad_docente(this.data_actividades, this.token_seccion);                  
                
   }
    
}



function recarga_secciones_dropdown(){
    $.ajax({
        type: "GET",
        url: site_url + ruta_secciones + "/get_registros_seccion",
//                        data: filter,
        dataType: "json"
    })
            .done(function (result) {
                /**
                 * resultado catalogo_secciones_actividad_docente, campos_mostrar_datatable,
                 *  textos_extra y datos_actividad_docente
                 */
                data_actividades = result;
                    //console.log(data_actividades);

                //console.log('selecciona key select ' + token_seccion);
                recarga_catalogo_secciones_actividad_docente(result.catalogo_secciones_actividad_docente, token_seccion);
//                    carga_grid_actividad_docente(result, this.token_seccion);
                //console.log("Aquipasa algo ?");
            });
}

function carga_grid_actividad_docente(datos, seccion) {
//    var name_fields = obtener_cabeceras_implementaciones();
    //console.log(datos);
    var gfields = genera_filds(datos, seccion);
    var grid = $('#js_grid_registros_seccion').jsGrid({
        height: "500px",
        width: "100%",
//        deleteConfirm: "¿Deseas eliminar este registro?",
        filtering: true,
        inserting: false,
        editing: false,
        sorting: false,
        selecting: false,
        paging: true,
        autoload: true,
        pageSize: 5,
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
                //console.log(filter);
                var d = $.Deferred();
                //var result = null;
               //console.log(datos.datos_actividad_docente);
               
                var res = $.grep(datos.datos_actividad_docente, function (registro) {
                    var result = true;
                    var namec;
                   
                    
                    if (seccion !== '') {
                        result = (seccion == registro.id_elemento_seccion);
                    }
                    if (result && typeof columnas !== 'undefined' && columnas.length > 0) {
                        for (var i = 0; i < columnas.length; i++) {
                            namec = columnas[i];
                            if (registro[namec] == 'NULL') {
                                registro[namec] = '';
                            }                           
                            if (typeof namec !== "undefined" && !(!filter[namec] || (registro[namec] !== null && registro[namec].toString().toLowerCase().indexOf(filter[namec].toString().toLowerCase()) > -1))) {
                                
                                result = false;
                                break;
                            }
                        }
                    }
                
                    return result;
                });
                d.resolve(res);
                return d.promise();
            },
            updateItem: function (item) {
            }
        },
        fields: gfields,
        rowRenderer: function (item) {
            var result = $('<tr>');
            switch (item.id_validacion_registro.toString()) {
                case '2':
                    result = $('<tr class="estado_valido">');
                    break;
                case '3':
                    result = $('<tr class="estado_normal">');
                    break;
                case '3':
                    result = $('<tr class="estado_no_valido">');
                    break;
                default :
            }

            this._renderCells(result, item);
            return result;
        }
        ,
    });
}

function completa_campo(data){
    //console.log(data);
    Object.keys(data.datos_actividad_docente).forEach(function (key) {
        d = data.datos_actividad_docente[key];
        if(typeof d.sede_academica !== 'undefined' || typeof d.sede !== 'undefined'){
            d.sede_academica
            consulta =  site_url +"/rama_organica/get_detalle/unidad/"+d.sede_academica+"/actual" ;
            $.getJSON(consulta, {})
            .done(function (data, textStatus, jqXHR) {
                //console.log(label.text());
                //console.log(data);
                    if (data[0] /*&& textStatus === 'success'*/) {
                        d.sede_academica = data[0].unidad + "("+ data[0].clave_unidad+")";    
                        $("#js_grid_registros_seccion").jsGrid("refresh");       
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    //get_mensaje_general_modal("Ocurrió un error durante el proceso, inténtelo más tarde.", textStatus, 5000);
                });
        }     

        //console.log(d);
    });
    

    /*switch(campo){
        case 'sede':
        case 'sede_academica':

        break;
        default:

    }*/
}

function getSede(clave_sede){
    var label = $(element);
        consulta =  site_url +"/rama_organica/get_detalle/unidad/"+clave_sede+"/actual" ;
        $.getJSON(consulta, {})
        .done(function (data, textStatus, jqXHR) {
            //console.log(label.text());
            //console.log(data);
                if (data[0] /*&& textStatus === 'success'*/) {
                    var sede = data[0].unidad + "("+ data[0].clave_unidad+")";           
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                //get_mensaje_general_modal("Ocurrió un error durante el proceso, inténtelo más tarde.", textStatus, 5000);
            });

        
}


function recarga_catalogo_secciones_actividad_docente(actividades_docente, token_seccion) {
    //console.log("actividades_docente");
    //console.log(token_seccion);
    var secciones_datatable = $("#secciones_datatable");
    if (actividades_docente.length > 0) {
        get_agrega_opciones_dropdown("secciones_datatable", actividades_docente, "id_elemento_seccion", "label", token_seccion);
        
        //Recarga de secciones con una opcion
        if(typeof properties !== 'undefined'){
            if(typeof properties.id_elementoSeccionDefault !== 'undefined' && properties.id_elementoSeccionDefault>-1 || properties.id_elementoSeccionDefault!='-1'){
                var seccion_tmp_stat = properties.id_elementoSeccionDefault;
                
                document.getElementById('secciones_datatable').value = seccion_tmp_stat;
                //console.log(document.getElementById('secciones_datatable').text);
            }   
        }
        //secciones_datatable.val(token_seccion);
        secciones_datatable.css("display", "block");
        secciones_datatable.val(token_seccion);
    } else {//desaparece
        //console.log("Esto es cero");
        secciones_datatable.css("display", "none");
    }
    secciones_datatable.trigger("onchange");
}

function recarga_grid(elemento) {
    var objeto = $(elemento);
    this.token_seccion = objeto.val();
    //console.log(this.token_seccion);
    carga_grid_actividad_docente(this.data_actividades, this.token_seccion);
}

function genera_filds(data, elemento_seccion) {
    var f = new Array();
    var d_extra;
    var mostrar_extras_por_properties = true;
    columnas = new Array();
    completa_campo(data);
    //console.log(data);
    if (typeof elemento_seccion !== 'undefined' && elemento_seccion != null && elemento_seccion.toString() != '') {//Para opciones de la tabla
//        console.log(data.campos_mostrar_datatable.length);
//        if (typeof data.campos_mostrar_datatable !== 'undefined' && data.campos_mostrar_datatable.length > 0) {
    //console.log("Elemento seccion: "+elemento_seccion);
    //console.log(data.campos_mostrar_datatable);
    Object.keys(data.campos_mostrar_datatable).forEach(function (key) {
        d_extra = data.campos_mostrar_datatable[key];
        //console.log(d_extra);
        if (typeof d_extra.ids_elemento_seccion[elemento_seccion] !== "undefined") {
            columnas.push(d_extra.nombre);//Columnas del grid
            //                console.log(d_extra.nombre_tipo_campo);
            switch (d_extra.nombre_tipo_campo) {
                case "dropdown":
                    //                        f.push({name: key, type: d_extra.nombre_tipo_campo, title: d_extra.label, items: data[key], valueField: 'id', textField: 'label'});
                    f.push({name: d_extra.nombre, type: 'text', title: d_extra.label});
                    break;
                    default :
                    //                        f.push({name: key, type: d_extra.nom_tipo_campo, title: d_extra.label});
                    f.push({name: key, type: 'text', title: d_extra.label});
                }
            }
        });
    }else{//Cuando no cargo ninguna opcion 
        //Aplicar un filtro para que no fucione los registros de campos iguales
        if(typeof properties !== 'undefined'){
           
            if(typeof properties.id_elementoSeccionDefault === 'undefined' ){                                   
                f.push({name: "id_elemento_seccion", type: 'select', items:data.catalogo_secciones_actividad_docente, valueField:"id_elemento_seccion", textField:"label",title: "Formulario" , filtering:false});            
            }else{
                if(properties.id_elementoSeccionDefault.length == 0 || properties.id_elementoSeccionDefault < 1 ){    
                    f.push({name: "id_elemento_seccion", type: 'select', items:data.catalogo_secciones_actividad_docente, valueField:"id_elemento_seccion", textField:"label",title: "Formulario" , filtering:false});            
                }
            }
        }else{            
            f.push({name: "id_elemento_seccion", type: 'select', items:data.catalogo_secciones_actividad_docente, valueField:"id_elemento_seccion", textField:"label",title: "Formulario" , filtering:false});
        }
            //Carga datos del formulario
            //var cat_secciones = getElementoSeccionName(data.catalogo_secciones_actividad_docente);
        //Agrega el elemento seccion
            Object.keys(data.campos_mostrar_datatable).forEach(function (key) {
                d_extra = data.campos_mostrar_datatable[key];
                //console.log(data);                
                    columnas.push(d_extra.nombre);//Columnas del grid
                    
                    //                console.log(d_extra.nombre_tipo_campo);
                    switch (d_extra.nombre_tipo_campo) {
                        case "dropdown":
                            //                        f.push({name: key, type: d_extra.nombre_tipo_campo, title: d_extra.label, items: data[key], valueField: 'id', textField: 'label'});
                            f.push({name: d_extra.nombre, type: 'text', title: d_extra.label});
                            break;
                            default :
                            //                        f.push({name: key, type: d_extra.nom_tipo_campo, title: d_extra.label});
                            f.push({name: key, type: 'text', title: d_extra.label});
                        }
                        
                    });
                
    }


    if(typeof properties !== 'undefined'){
        if(typeof properties.visible_textos_extras_table_seccion !== 'undefined' && (properties.visible_textos_extras_table_seccion==0 || properties.visible_textos_extras_table_seccion=='0')){                   
            mostrar_extras_por_properties = false;            
        }    
    }

        if (typeof data.textos_extra !== 'undefined' && mostrar_extras_por_properties) {
            Object.keys(data.textos_extra).forEach(function (key) {
                d_extra = data.textos_extra[key];
                columnas.push(d_extra.nombre);//Columnas del grid
                switch (d_extra.nom_tipo_campo) {
                    case "select":
                        f.push({name: key, type: d_extra.nom_tipo_campo, title: d_extra.label, items: data[d_extra.nombre], valueField: 'id', textField: 'label'});
                        break;
                        default :
                        f.push({name: key, type: d_extra.nom_tipo_campo, title: d_extra.label});
                    }
                });
            }
//    f = [
//        {name: "nom_elemento_seccion", type: "text", title: "Nombre elemento sección"},
//        {name: "tipo_curso", type: "text", title: "Tipo de curso"},
//        {name: "nombre_validacion", type: "text", title: "Tipo de curso"},
//    ];
    /*f.push({name: 'id_censo', title: "Acciones", type: 'div',
        itemTemplate: function (value, item) {
            return genera_acciones(value, item);
        }
    });*/
    f.push({type: "control", editButton: false, deleteButton: false,
        searchModeButtonTooltip: "Cambiar a modo búsqueda", // tooltip of switching filtering/inserting button in inserting mode
//        editButtonTooltip: "Editar", // tooltip of edit item button
        searchButtonTooltip: "Buscar", // tooltip of search button
        clearFilterButtonTooltip: "Limpiar filtros de búsqueda", // tooltip of clear filter button
//        updateButtonTooltip: "Actualizar", // tooltip of update item button
//        cancelEditButtonTooltip: "Cancelar", // tooltip of cancel editing button
        name: 'id_censo', 
        itemTemplate: function (value, item) {
            return genera_acciones(value, item);
        }
    }
    );
    return f;
}

function getElementoSeccionName(datos_elemento_seccion){
    var cat_secciones = new Object();
    Object.keys(datos_elemento_seccion).forEach(function (key) {
        var elemento_prop = datos_elemento_seccion[key];
        cat_secciones[elemento_prop.elemento_seccion] = elemento_prop.label;
    });
    return cat_secciones;
}

function genera_acciones(value, item) {
//    console.log(item);
    var ruta = ruta_secciones;
    var link_editar = '';
    var link_detalle = '';
    var link_eliminar = '';
    if (item.acciones.editar === true) {
        link_editar = '<a class="opcion statico_update" href="#"'
                + 'onclick="cargar_actividad(this);"'
                + 'data-censo="' + value + '"'
                + 'data-rutaeditar="' + ruta + '/carga_actividad"'
                + '>'
                + '<span data-tip="tip-2" class="tip glyphicon glyphicon-pencil"></span>'
                + '</a>';
    }
    if (item.acciones.eliminar === true) {
        link_eliminar = '<a class="opcion" href="#"'
                + 'onclick="drop_censo(this);"'
                + 'data-censo="' + value + '"'
                + 'data-ruta="' + ruta + '/elimina_censo/"'
                + 'data-updatetabla="' + ruta + '/actualiza_tabla"'
                + '>'
                + '<span class="fa fa-remove" title="" placeholder="Eliminar registro"> </span>'
                + '</a>';
    }
    if (item.acciones.ver === true) {
        link_detalle = '<a class="opcion" data-toggle="modal" data-target="#my_modal"'
                + 'onclick="detalle_registro(this);"'
                + 'data-censo="' + value + '"'
                + 'data-ruta="' + ruta + '/ver_detalle_registro_censo/" >'
                + '<span class="fa fa-eye" title="" placeholder="Ver detalle"> </span>'
                + '</a>';
    }

    return link_detalle + link_editar + link_eliminar;
}
