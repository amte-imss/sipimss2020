$(function(){

    var grid=$('#lista_registros').jsGrid({
        width: "100%",
        height: "500px",
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
        controller: {
          loadData: function (filter) {
              mostrar_loader();
              //console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "GET",
                  url: site_url + "/catalogo/departamento/lista/",
                  data: filter,
                  dataType: "json"
              }).done(function (result) {
                          console.log(result);
                          console.log(result.data);
                          d.resolve({
                              data: result.data,
                              itemsCount: result.length,
                          });
                      });
                      ocultar_loader();
              return d.promise();
          },
          insertItem: function (item){
              mostrar_loader();
                var di = $.Deferred();
                $.ajax({
                    type: "POST",
                    url: site_url + "/catalogo/departamento/agregar",
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
              var de = $.Deferred();
               $.ajax({
                   type: "POST",
                   url: site_url + "/catalogo/departamento/editar",
                   data: item
               }).done(function (json) {
                   console.log('success');
                   alert(json.message);
                   if (json.success) {
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
                   url: site_url + "/catalogo/departamento/eliminar",
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
                    {name: 'id_departamento_instituto', title: "#", visible: false},
                    {name: 'clave_unidad', title: 'Clave unidad', type: 'text'},
                    {name: 'departamento', title: 'Adscripción', type: 'text'},
                    {name: 'clave_departamental', title: 'Clave adscripción', type: 'text'},                    
                    {type: "control",  width: "10%"}
                ]
            }
        );
        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#lista_registros").jsGrid("option", "pageSize", page);
        });
});
