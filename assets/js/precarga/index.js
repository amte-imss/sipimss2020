$(function(){

    render_grid_precarga();
    $("#pager").on("change", function() {
        var page = parseInt($(this).val(), 10);
        $("#lista_registros").jsGrid("option", "pageSize", page);
    });

    procesa_pendientes();
});

function render_grid_precarga(){
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
                    url: site_url + "/precarga/index/lista/",
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
            {name: 'id_precarga', title: "#", visible: false},
            {name: 'fecha', title: 'Fecha', type: 'date', width:'20%'},
            {name: 'id_usuario', title: '#', type: 'text', visible:false},
            {name: 'username', title: 'Usuario', type: 'text'},
            {name: 'nombre_archivo', title: 'Archivo', type: 'text', width:'40%'},
            {name: 'peso', title: 'Peso', type: 'text'},
            {name: 'modelo', title: 'Modelo', type: 'text', width:'15%'},
            {name: 'funcion', title: 'Función', type: 'text', width:'40%'},
            {name: 'total', title: 'Total de registros', type: 'text'},
            {name: 'pendientes', title: 'Registros pendientes', type: 'text'},
            {type: "control", editButton: false, deleteButton: false, width: "10%",
                itemTemplate:function(value, item){
                    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
                    var $myButton = $('<a href="'+site_url+'/precarga/detalle/'+item.id_precarga+'">Detalle</a>');
                    return $result.add($myButton);
                }
            }
        ]}
    ).data("JSGrid");

    var origFinishInsert = jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert;
    jsGrid.loadStrategies.DirectLoadingStrategy.prototype.finishInsert = function (insertedItem) {
        if (!this._grid.insertSuccess) { // define insertFailed on done of delete ajax request in insertFailed of controller
            return;
        }
        origFinishInsert.apply(this, arguments);
    };
}

function procesa_pendientes(){
    var destino = site_url + '/precarga/cron';
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
        render_grid_precarga();
        if(data.length > 0){
            setTimeout(procesa_pendientes, 2000);
        }else{
            setTimeout(procesa_pendientes, 30000);
        }
    //    setTimeout(procesa_pendientes, 2000);
        //procesa_pendientes();
    });
}
