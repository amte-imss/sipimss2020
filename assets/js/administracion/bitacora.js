$(function(){

    var grid=$('#lista_registros').jsGrid({
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
              mostrar_loader();
              //console.log(filter);
              var d = $.Deferred();
              //var result = null;

              $.ajax({
                  type: "GET",
                  url: site_url + "/administracion/bitacora/lista/",
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
          insertItem: $.noop,
          updateItem: $.noop,
          deleteItem: $.noop
        },
        fields: [
                    {name: 'id_bitacora', title: "#", visible: false},
                    {name: 'id_usuario', title: "#", visible: false},
                    {name: 'fecha', title: 'Fecha', type: 'date'},
                    {name: 'ip', title: 'IP', type: 'text'},
                    {name: 'url', title: 'URL', type: 'text'},
                    {type: "control", editButton: false, deleteButton: false, width: "10%"}
                ]
            }
        );
        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#lista_registros").jsGrid("option", "pageSize", page);
        });


});
