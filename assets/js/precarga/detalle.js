$(function(){

    render_grid_precarga_detalle();
    procesa_pendientes_detalle();
});

function procesa_pendientes_detalle(){
    var destino = site_url + '/precarga/cron/'+data_id_precarga;
    $.ajax({
        url: destino,
        method: 'GET',
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#mensajes_sincronización').html('Espera realizando precargas pendientes');
        }
    }).done(function(data){
        $('#mensajes_sincronización').html('');
        $('#mensajes_sincronización').html('Pendientes: ' + data.length);
        render_grid_precarga_detalle();
        if(data.length>0){
            setTimeout(procesa_pendientes_detalle, 2000);
        }
    //    setTimeout(procesa_pendientes, 2000);
        //procesa_pendientes();
    });
}

function render_grid_precarga_detalle(){
    var grid=$('#lista_registros').jsGrid({
        width: "100%",
        height: "500px",
        // deleteButton: true,
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
                  url: site_url + "/precarga/detalle/"+data_id_precarga+"/lista/",
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
        },
        fields: [
                    {name: 'id_detalle_precarga', title: "#", visible: false},
                    {name: 'id_precarga', title: "#", visible: false},
                    {name: 'detalle_registro', title: 'Contenido',width: "40%",
                        itemTemplate: function(value, item){
                            $.elemento = $('<p>'+JSON.stringify(item.detalle_registro)+'</p>');
                            return $.elemento;
                        }
                    },
                    {name: 'status', title: 'Estado', type: 'text'},
                    {name: 'tabla_destino', title: 'Tabla', type: 'text', width: "30%"},
                    {name: 'id_tabla_destino', title: 'ID aux', type: 'text', width: "10%"},
                    {name: 'descripcion_status', title: 'Detalle de la carga', type: 'text', width: "40%"},
                    {type: "control", editButton: false, deleteButton: false, width: "10%"}
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
}

function precarga_mostrar_json(){
    //$('#precarga_modal_content').html(JSON.stringify(detalle_seleccionado));
    $('#precarga_modal').modal('show');
}
