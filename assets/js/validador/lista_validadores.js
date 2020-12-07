
$(document).ready(function () {
    lista_validadores();
});

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
              console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "POST",
                  url: site_url + url_ctr + "/validadores/",
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
            {name: 'clave_rol', type: "text", title: "Rol", type: "select", items:nivel_acceso, valueField: "clave_rol", textField: "descripcion", visible:true},
            {name: 'umae', type: "text", title: "UMAE", visible:true},
            {name: 'total', type: "number", title: "# docentes registrados", visible:true, filtering:false},            
            {name: 'docentes_designados', type: "text", title: "Docentes designados", visible:true},
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
                    var rutas = '<a href="'+site_url+'/usuario/get_usuarios/'+item.id_usuario+'/1">Editar</a> | <a href="'+site_url + url_ctr+'/detalle_censo_docente/'+item.id_docente+'/2">Ver detalle</a>';
                    if(cambiar_validadorN1 == item.clave_rol && item.total>0){
                        rutas += '| <a href="'+site_url+url_ctr+'/registros_validador/'+item.id_docente+'">Cambiar validador N1</a>' 
                    }
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
