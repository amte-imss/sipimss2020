
$(document).ready(function () {
    reporte_docentes();
});

function reporte_docentes(){
    //console.log(site_url + url_ctr + "/docentes/");
    $(function() {
    var grid_list=$('#js_grid_lista_censo_docentes').jsGrid({
        height: "520px",
        width: "100%",
//        deleteConfirm: "¿Deseas eliminar este registro?",
        filtering: false,
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
              console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "POST",
                  url: site_url + url_ctr + "/datos_reporte/",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                  //console.log(result);
                  $("#docentes_registrados").text(result.docentes_reporte.length + " docentes registrados");
                          /*d.resolve({
                              data: result.datos_docentes,
                              itemsCount: result.datos_docentes.length,
                          });*/
                          var res = $.grep(result.docentes_reporte, function (registro) {
                            registro.status_validacion = get_status(registro.id_status_validacion);
                            return true;
                            /*return (!filter.clave_delegacional || (registro.clave_delegacional != null && (registro.clave_delegacional == filter.clave_delegacional)))
                            && (!filter.matricula || (registro.matricula !== null && registro.matricula.toLowerCase().indexOf(filter.matricula.toString().toLowerCase()) > -1))
                            && (!filter.nombre_docente || (registro.nombre_docente !== null && registro.nombre_docente.toLowerCase().indexOf(filter.nombre_docente.toString().toLowerCase()) > -1))
                            && (!filter.email || (registro.email !== null && registro.email.toLowerCase().indexOf(filter.email.toString().toLowerCase()) > -1))
                            //&& (!filter.clave_rol || (registro.clave_rol != null && (registro.clave_rol == filter.clave_rol)))
                            && (!filter.umae || (registro.umae !== null && registro.umae.toLowerCase().indexOf(filter.umae.toString().toLowerCase()) > -1))
                            && (!filter.total || (registro.total !== null && registro.total.toLowerCase().indexOf(filter.total.toString().toLowerCase()) > -1))*/
                          });
                          d.resolve(res);
                      });
                      ocultar_loader();
              return d.promise();
          },         
        },
        fields: [
            {name: 'clave_departamental', title: "Clave departamental", type: "text", visible:true},
            {name: 'departamento', title: "Departamento", type: "text", visible:true},
            {name: 'clave_unidad', title: "Clave unidad", type: "text", visible:true},
            {name: 'nom_unidad', title: "Unidad", type: "text", visible:true},
            {name: 'nom_tipo_unidad', title: "Tipo unidad", type: "text", visible:true},            
            {name: 'clave_delegacional', title: "OOAD", type: "select", items: delegaciones,valueField: "clave_delegacional", textField: "nombre",  visible:true},
            {name: 'clave_categoria', title: "Clave categoría", type: "text", visible:true},
            {name: 'categoria', title: "Categoría", type: "text", visible:true},
            {name: 'id_docente', title: "ID docente", type: "text", visible:true},
            {name: 'matricula', title: "Matrícula", type: "text", visible:true},
            {name: 'curp', title: "CURP", type: "text", visible:true, with:"60"},
            {name: 'email', title: "Correo electrónico", type: "text", visible:true, with:"80"},
            {name: 'nombre_docente', title: "Nombre docente", type: "text", visible:true},
            {name: 'telefono_laboral', title: "Teléfono laboral", type: "text", visible:true},
            {name: 'telefono_particular', title: "Teléfono particular", type: "text", visible:true},
            {name: 'fase_carrera', title: "Fase carrera docente", type: "text", visible:true},
            {name: 'umae', title: "UMAE", type: "text", visible:true},
            {name: 'id_status_validacion', title: "Estado validación", type: "select", items:estados_validacion, valueField: "id", textField: "label", visible:true},
            
			{name:'experiencia_docente_previa', type: "text", title: "Experiencia docente previa", visible:true},
            {name:'experiencia_actual', type: "text", title: "Experiencia actual", visible:true},
            {name:'formacion_educacion', type: "text", title: "Formación educación", visible:true},
            {name:'formacion_investigacion_temas_educacion', type: "text", title: "Formación, investigación en temas de educación", visible:true},
            {name:'formacion_investigacion_otros_campos', type: "text", title: "Formación, investigación en otros campos", visible:true},
            {name:'publicacion', type: "text", title: "Publicaciones", visible:true},
            {name:'material_recurso', type: "text", title: "Material y recursos educativos", visible:true},
            {name:'total_registros_censo', type: "text", title: "Total de registros docente", visible:true},
            {name:'posgrado', type: "text", title: "Posgrado", visible:true},
            {name:'ciefd', type: "text", title: "CIEFD", visible:true},
            {name:'enfermeria_tecnicos', type: "text", title: "Enfermería y técnicos", visible:true}, 
            {name:'educacion_continua', type: "text", title: "Educación continua", visible:true},
            {name:'pregrado', type: "text", title: "Pregrado", visible:true},
            {name:'educacion_distancia', type: "text", title: "Educación a distancia", visible:true},
            {name:'diplomado_educacion', type: "text", title: "Diplomado en educación y afines", visible:true}, 
            {name:'especialidad_educacion', type: "text", title: "Especialidad en educación y afines", visible:true}, 
            {name:'maestria_educacion', type: "text", title: "Maestria en educación y afines", visible:true}, 
            {name:'doctorado_educacion', type: "text", title: "Doctorado en educación y afines", visible:true},
                    
            //{name: 'id_elemento_catalogo_padre', title: 'Elemento padre', type: 'select', items: json_elementos_catalogo_padre, valueField: "id_elemento_catalogo", textField: "label"},
            //{name: 'id_elemento_catalogo_hijo', title: 'Elemento hijo', type: 'select', items: json_elementos_catalogo_hijo, valueField: "id_elemento_catalogo", textField: "label"},
            /*{type: "control", editButton: false, deleteButton: false, visible:true,
                searchModeButtonTooltip: "Cambiar a modo búsqueda", // tooltip of switching filtering/inserting button in inserting mode
                editButtonTooltip: "Editar", // tooltip of edit item button
                searchButtonTooltip: "Buscar", // tooltip of search button
                clearFilterButtonTooltip: "Limpiar filtros de búsqueda", // tooltip of clear filter button
                updateButtonTooltip: "Actualizar", // tooltip of update item button
                cancelEditButtonTooltip: "Cancelar", // tooltip of cancel editing button
                itemTemplate: function (value, item) {
                    //console.log(cambiar_validadorN1);
                    var rutas = '<a href="'+site_url+'/usuario/get_usuarios/'+item.id_usuario+'/1">Editar</a> | <a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'/2">Ver detalle</a>';
                    if(cambiar_validadorN1 == item.clave_rol && item.total>0){
                        rutas += '| <a href="'+site_url+url_ctr+'/registros_validador/'+item.id_docente+'">Cambiar validador N1</a>' 
                    }
                    return rutas;
                }
            }*/
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

function get_status(id){
    //console.log(estados_validacion);
    //console.log(id);
    for (var i = 0; i < estados_validacion.length; ++i) {
        if (!estados_validacion[i])
            continue;
        valor = estados_validacion[i];
        if (typeof valor === 'object') {
            if (valor['id'] == id) {
                return valor['label'];
            }
        }
    }
    
}

function exportar_reporte(element) {
    var namegrid = $(element).data('namegrid');
    var headers = remove_headers(obtener_cabeceras(), cabeceras_no_exportar());
//    var headers = obtener_cabeceras_implementaciones();
    export_xlsx_grid(namegrid, headers, 'docentes', 'docentes');
}

function obtener_cabeceras() {
    var arr_header = {
        fecha_ultima_actualizacion:'Última actualización SIAP',
        clave_departamental: 'Clave departamental',
        departamento: 'Departamento',
        clave_unidad: 'Clave unidad',	
        nom_unidad: 'Unidad',	
        nom_tipo_unidad: 'Tipo unidad',
        nivel_atencion: 'Nivel de atención',
        clave_delegacional: 'OOAD',
        delegacion: 'Delegacion',
        clave_categoria: 'Clave categoría',
        categoria:'Categoría',
        id_docente:'ID docente',
        matricula: 'Matrícula',
        curp:'CURP',
        email: 'Correo electrónico',
        nombre_docente: 'Nombre docente',
        telefono_laboral: 'Teléfono laboral',
        telefono_particular: 'Teléfono particular',
        fase_carrera: 'Fase carrera docente',
        umae: 'UMAE',
        status_validacion:'Estado',
        //Registros por actividad 
        experiencia_docente_previa: 'Experiencia docente previa', 
        experiencia_actual: 'Experiencia actual',
        formacion_educacion: 'Formación educación',
        formacion_investigacion_temas_educacion: 'Formación, investigación en temas de educación',
        formacion_investigacion_otros_campos: 'Formación, investigación en otros campos', 
        publicacion: 'Publicaciones',
        material_recurso: 'Material y recursos educativos', 
        total_registros_censo:'Total de registros docente',
        posgrado: 'Posgrado', 
        ciefd: 'CIEFD', 
        enfermeria_tecnicos: 'Enfermería y técnicos', 
        educacion_continua: 'Educación continua', 
        pregrado: 'Pregrado', 
        educacion_distancia: 'Educación a distancia', 
        diplomado_educacion: 'Diplomado en educación y afines', 
        especialidad_educacion: 'Especialidad en educación y afines', 
        maestria_educacion: 'Maestría en educación y afines', 
        doctorado_educacion: 'Doctorado en educación y afines'

    }

    return arr_header;
}

function cabeceras_no_exportar() {
    var arr_header = {
        acciones: 'Acciones',
    }
    return arr_header;
}