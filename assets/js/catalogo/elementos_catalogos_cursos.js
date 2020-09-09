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
          console.log("Update item eleemnto catalog", args.item);
          $.ajax({
            type: 'POST',
            url: site_url + '/catalogo/restfull_modulos/cursos/actualizar/'+ args.item.id_elemento_catalogo,
            data: args.item,
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
                  console.log("Resultado de leer un catalogo: ", catalogo.datos.data[0]);
                  $.ajax({
                      type: "GET",
                      url: site_url + "/catalogo/restfull_modulos/cursos/leer/"+catalogo.datos.data[0].id_catalogo,
                      data: filter,
                      dataType: "json"
                  }).done(function (result) {
                      console.log("Resultado de leer los elementos de un catalogo: ", result);
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
              delete datos.id_proceso_educativo;
              var fi = datos.fecha_inicio.split('-');
              var ff = datos.fecha_fin.split('-');
              //console.log(fi, ff);
              datos.fecha_inicio = fi[2]+"-"+fi[1]+"-"+fi[0];
              datos.fecha_fin = ff[2]+"-"+ff[1]+"-"+ff[0];
              console.log("Insertar Item ", datos);
              $.ajax({
                type: 'POST',
                url: site_url + '/catalogo/restfull_modulos/cursos/crear/',
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
                    {name: 'id_elemento_catalogo', title: "Id", visible:false, },
                    {name: 'label', title: 'Nombre', type: 'text'},
                    {name: 'descripcion', title: 'Descripcion', type: 'text'},
                    {name: 'tipo', title:"Tipo", type:'text'},
                    {name: 'id_catalogo', title: "Id Catalogo", type:'text'},
                    {name: 'id_catalogo_elemento_padre', title: "Id Catalogo elemento padre", type:'text'},
                    {name: 'activo', title: "Activo", type:'text'},
                    {name: 'label', title: 'Etiqueta/Label', type: 'text', visible:true},
                    {name: 'orden', title: 'Orden', type: 'text', visible:true},
                    {name: 'nivel', title: 'Nivel', type: 'text', visible:true},
                    // {name: 'sede', title: 'Sede', type: 'text', visible:true},
                    {name: 'is_valido', title: 'Es valido', type: 'text', visible:true},
                    {name: 'id_proceso_educativo', title: 'Id proceso educativo', type: 'text', visible:true},
                    {name: 'clave_ces', title: 'Clave ces', type: 'text', visible:true},
                    {name: 'clave_unidad', title: 'Clave unidad', type: 'text', visible:true},
                    {name: 'anio', title: 'Año', type: 'text', visible:true},
                    {name: 'division', title: 'Division', type: 'text', visible:true},
                    {name: 'fecha_inicio', title: 'Fecha de inicio', type: 'text', visible:true},
                    {name: 'fecha_fin', title: 'Fecha de fin', type: 'text', visible:true},
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
        });

        var formSubmitHandler = $.noop;

        var showDetailsDialog = function(dialogType, client) {
            $("#nombre").val(client.nombre);
            $("#descripcion").val(client.descripcion);
            $("#tipo").val(client.tipo);
            $("#label").val(client.label);
            $("#activo").val(client.activo);
            $("#orden").val(client.orden);
            $("#nivel").val(client.nivel);
            $("#id_proceso_educativo").val(client.id_proceso_educativo);
            $("#clave_ces").val(client.clave_ces);
            $('#clave_unidad').val(client.clave_unidad);
            $('#anio').val(client.anio);
            $('#division').val(client.division);
            $('#fecha_inicio').val(client.fecha_inicio);
            $('#fecha_fin').val(client.fecha_fin);
            $( "#detailsForm" ).prepend( "<p id='msjError'></p>" );
            //$("#detailsForm").append("<p id='msjError'></p>");
            $("#detailsDialog").dialog("option", "title", "Agregar catalogo de cursos").dialog("open");
            $("#detailsForm").children()[3].hidden = true
        };

        $( "#detailsForm" ).submit(function( event ) {
            event.preventDefault();
            var client = {
                nombre: $("#nombre").val(),
                descripcion: $("#descripcion").val(),
                tipo: $("#tipo").val(),
                label: $("#label").val(),
                activo: $("#activo").is(":checked"),
                orden: $("#orden").val(),
                nivel: $("#nivel").val(),
                id_proceso_educativo: $('#id_proceso_educativo').val(),
                clave_ces: $('#clave_ces').val(),
                clave_unidad: $('#clave_unidad').val(),
                anio: $('#anio').val(),
                division: $('#division').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val()
            };
            if(client.nombre == "" || client.descripcion == "" || client.tipo == "" ||
               client.label == "" || client.orden == "" || client.nivel == "" || client.id_proceso_educativo == "" ||
               client.clave_ces == "" || client.clave_unidad == "" || client.anio == "" || client.division == "" ||
               client.fecha_inicio == "" || client.fecha_fin == ""){
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
