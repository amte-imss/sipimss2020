$(function(){
    var grid=$('#lista_registros').jsGrid({
        width: "100%",
        height: "600px",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: true,
        sorting: true,
        selecting: true,
        paging: true,
        autoload: true,
        pageSize: 25,
        pageButtonCount: 2,
        pageLoading: true,
        onItemUpdating: function(args) {
          //console.log("Update item ", args.item);
          var datos = {
            'nombre': args.item.nombre,
            'descripcion': args.item.descripcion,
            //'label': args.item.labe,
            'nivel':args.item.nivel,
            'orden':args.item.orden,
            'tipo':args.item.tipo
          }
          $.ajax({
            type: 'POST',
            url: site_url + '/catalogo/restfull_modulos/elementos_catalogos/actualizar/'+ args.item.id_elemento_catalogo,
            data: datos,
            dataType: 'json'
          }).done(function(result){
            console.log("Resultado de actualizar: ", result);
          }).fail(function(xhr) {
            console.log('error', xhr);
          });
        },
        controller: {
          loadData: function (filter) {
              mostrar_loader();
              var d = $.Deferred();
              var catalogo = window.location.href.split("/")[window.location.href.split("/").length-1]
              console.log("FILTROS init: ", filter);
              var filtrosCatalogo = {
                nombre:"",
                descripcion: "",
                tipo:""
              }
              $.ajax({
                type:"GET",
                url: site_url + "/catalogo/restfull_modulos/catalogo/leer/"+catalogo,
                data:filtrosCatalogo,
                dataType: "json"
              }).done(function (catalogo){
                  console.log("FILTRO elementos: ", filter, catalogo);
                  if(filter.id_catalogo == "Seleccionar" || filter.id_catalogo_elemento_padre == "Seleccionar"){
                    filter.id_catalogo ="";
                    filter.id_catalogo_elemento_padre = "";
                  }
                  $.ajax({
                      type: "GET",
                      url: site_url + "/catalogo/restfull_modulos/elementos_catalogos/leer/"+catalogo.datos.data[0].id_catalogo,
                      data: filter,
                      dataType: "json"
                  }).done(function (result) {
                      console.log("Resultado de leer los elementos de un catalogo: ", result.datos.data);
                      d.resolve({
                          data: result.datos.data,
                          itemsCount: result.datos.length,
                      });
                  });
              })
              ocultar_loader();
              return d.promise();
          },
          insertItem: function(datos){
              datos.id_catalogo = window.location.href.split("/")[window.location.href.split("/").length-1];
              console.log("Insertar Item ", datos);
              $.ajax({
                type: 'POST',
                url: site_url + '/catalogo/restfull_modulos/elementos_catalogos/crear/',
                data: datos,
                dataType: 'json'
              }).done(function(result){
                console.log("Resultado de insertar: ", result);
              }).fail(function(xhr) {
                console.log('error', xhr);
              });
          },
          updateItem: $.noop,
          deleteItem: $.noop
        },
        fields: [
                    {name: 'id_elemento_catalogo', title: "Id", visible:false},
                    {name: 'nombre', title: 'Nombre', type: 'text'},
                    {name: 'descripcion', title: 'Descripcion', type: 'text'},
                    {name: 'tipo', title:"Tipo", type:'text'},
                    {name: 'id_catalogo', title: "Id Catalogo", type:'select', items: todos_catalogos, valueField: "id_catalogo", textField: "nombre"},
                    {name: 'id_catalogo_elemento_padre', title: "Id Catalogo elemento padre", type:'select', items: todos_catalogos, valueField: "id_catalogo", textField: "nombre"},
                    {name: 'activo', title: "Activo"},
                    {name: 'label', title: 'Etiqueta/Label', type: 'text', visible:false},
                    {name: 'orden', title: 'Orden', type:'text', visible:true},
                    {name: 'nivel', title: 'Nivel', type:'text', visible:true},
                    {name: 'is_valido', title: 'Es valido', visible:false},
                    {type: "control", modeSwitchButton: false, editButton: false, deleteButton: false,
                    headerTemplate: function() {
                        return $("<button>").attr("type", "button").text("Agregar")
                                .on("click", function () {
                                    showDetailsDialog("Add", {});
                                });
                    }}
                ]
            }
        );

        $("#detailsDialog").dialog({
          autoOpen: false,
          width: 400,
          close: function() {
            $("#msjError").hide();
          }
        })

        var showDetailsDialog = function(dialogType, client) {
            $( "#detailsForm button:last-child" ).hide()
            $("#nombre").val(client.nombre);
            $("#descripcion").val(client.descripcion);
            $("#tipo").val(client.tipo);
            $("#label").val(client.label);
            $("#activo").val(client.activo);
            $("#orden").val(client.orden);
            $("#nivel").val(client.nivel);
            $( "#detailsForm" ).prepend( "<p id='msjError'></p>" );
            //$("#detailsForm").append("<p id='msjError'></p>");


            $("#detailsDialog").dialog("option", "title", "Agregar elementos del catalogo").dialog("open");
        };

        $( "#detailsForm" ).submit(function( event ) {
            event.preventDefault();
            console.log("EVENT: ", event);
            var client =  {
                nombre: $("#nombre").val(),
                descripcion: $("#descripcion").val(),
                tipo: $("#tipo").val(),
                label: $("#label").val(),
                activo: $("#activo").is(":checked"),
                orden: $("#orden").val(),
                nivel: $("#nivel").val()
            };

            if(client.nombre == "" || client.descripcion == "" || client.tipo == "" ||
               client.label == "" || client.orden == "" || client.nivel == ""){
              $("#msjError").html("Error: Los campos son requeridos para agregar un elemento");
            }else{
              $("#lista_registros").jsGrid("insertItem", client);
              $("#detailsDialog").dialog("close");
            }

        });

        $("#pager").on("change", function() {
            var page = parseInt($(this).val(), 10);
            $("#lista_registros").jsGrid("option", "pageSize", page);
        });

});
