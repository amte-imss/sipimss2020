$(function(){

    var grid=$('#lista_registros').jsGrid({
        width: "100%",
        height: "500px",
        // deleteButton: true,
        filtering: true,
        inserting: true,
        editing: true,
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
        onItemUpdating: function (args) {
          grid._lastPrevItemUpdate = args.previousItem;
        },
        controller: {
          loadData: function (filter) {
              mostrar_loader();
              //console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "GET",
                  url: site_url + "/catalogo/regla_dependencia/lista/",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                          d.resolve({
                              data: result.data,
                              itemsCount: result.length,
                          });
                      });
                      ocultar_loader();
              return d.promise();
          },
          insertItem: function (item){
              delete item.gestion;
              mostrar_loader();
                var di = $.Deferred();
                $.ajax({
                    type: "POST",
                    url: site_url + "/catalogo/regla_dependencia/agregar",
                    data: item
                }).done(function (json) {
                    alert(json.message);
                    grid.insertSuccess = json.success;
                    di.resolve(json.data);
                }).fail(function (jqXHR, error, errorThrown) {
                    console.log("error");
                    console.log(jqXHR);
                    console.log(error);
                    console.log(errorThrown);
                });
                ocultar_loader();
                return di.promise();
          },
          updateItem: function(item){
              delete item.gestion;
              var de = $.Deferred();
               $.ajax({
                   type: "POST",
                   url: site_url + "/catalogo/regla_dependencia/editar",
                   data: item
               }).done(function (json) {
                   console.log('success');
                   alert(json.message);
                   if (json.success) {
                       json.data.id_grupo_categoria = parseInt(json.data.id_grupo_categoria);
                       de.resolve(json.data);
                   } else {
                       de.resolve(grid._lastPrevItemUpdate);
                   }
               }).fail(function (jqXHR, error, errorThrown) {
                   console.log("error");
                   console.log(jqXHR);
                   console.log(error);
                   console.log(errorThrown);
               });
               return de.promise();
          },
          deleteItem: function(item){
              var de = $.Deferred();
               $.ajax({
                   type: "POST",
                   url: site_url + "/catalogo/regla_dependencia/eliminar",
                   data: item
               }).done(function (json) {
                   console.log('success');
                   alert(json.message);
                   de.resolve(json.data);
               }).fail(function (jqXHR, error, errorThrown) {
                   console.log("error");
                   console.log(jqXHR);
                   console.log(error);
                   console.log(errorThrown);
               });
               return de.promise();
          }
        },
        fields: [
                    {name: 'clave_regla_dependecia_catalogo', title: "Clave", type:'text'},
                    {name: 'nombre', title: 'Nombre', type: 'text'},
                    {name: 'descripcion', title: 'Descripción', type: 'text'},
                    {name: 'id_catalogo_padre', title: 'Catálogo padre', type: 'select', items: json_categorias, valueField: "id_catalogo", textField: "nombre"},
                    {name: 'id_catalogo_hijo', title: 'Catálogo hijo', type: 'select', items: json_categorias, valueField: "id_catalogo", textField: "nombre"},
                    {name: 'gestion', title:'Gestión', itemTemplate:function(value, item){
                        return '<a href="'+site_url+'/catalogo/detalle_reglas_dependencia/'+item.clave_regla_dependecia_catalogo+'">Gestionar relaciones</a>';
                    }},
                    {type: "control", editButton: false, width: "10%"}
                ]
            }
        ).data("JSGrid");

        var origFinishInsert = jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert;
        jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert = function (insertedItem) {
            if (!this._grid.insertSuccess) { // define insertFailed on done of delete ajax request in insertFailed of controller
                return;
            }
            origFinishInsert.apply(this, arguments);
        };

        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#lista_registros").jsGrid("option", "pageSize", page);
        });
});
