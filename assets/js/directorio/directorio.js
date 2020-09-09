var grid;
var monitor = true;
var delegacion_auxiliar = '';
var item_auxiliar = '';
var aux_objeto = null;
var cancel = false;
$(document).ready(function () {
//	mostrar_loader();
    $('#exportar_datos').on('click', function () {
        document.location.href = site_url + '/directorio/exportar_datos/';
    });
    if (document.getElementById("nivel_reporte")) {
        $('#nivel_reporte').on('change', function () {
//            console.log(this.value)
            grid_directorios(this.value);
        });
        $("#nivel_reporte").trigger('change');
    } else {
        grid_directorios("");
    }
});
function grid_directorios(tipo_nivel) {

//    $("#jsGridDirectorio").jsGrid({
//        onItemUpdating: function (args) {
//            console.log('cancela la edicion');
//            console.log(args);
//            // cancel update of the item with empty 'name' field
//
//            if (args.item.name === "") {
//                args.cancel = true;
//                alert("Specify the name of the item!");
//            }
//        }
//    });

    grid = $('#jsGridDirectorio').jsGrid({
        height: "800px",
        width: "100%",
        deleteConfirm: "¿Deseas eliminar este registro?",
        filtering: true,
        inserting: false,
        editing: true,
        sorting: false,
        selecting: false,
        paging: true,
        autoload: true,
        pageSize: 5,
        rowClick: function (args) {
            //console.log(args);
        },
        pageButtonCount: 3,
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
            grid._lastPrevItemUpdate = args.previousItem;
            grid._item = args.item;
            cancel = false;
        },
        onItemEditing: function (args) {
            cancel = true;

        },
        cancelEdit: function () {
            if (cancel) {
                var dec = $.Deferred();
                item_auxiliar.nombre_completo = aux_objeto.nombre_completo;
                item_auxiliar.nombre = aux_objeto.nombre;
                item_auxiliar.apellido_p = aux_objeto.ap;
                item_auxiliar.apellido_m = aux_objeto.am;
                item_auxiliar.clave_delegacional = aux_objeto.clave_delegacional;
                dec.resolve(item_auxiliar);
            }
            if (!this._editingRow) {
                return;
            }
            this._getEditRow().remove();
            this._editingRow.show();
            this._editingRow = null;
        },
        controller: {
            loadData: function (filter) {
                //console.log(filter);
                var d = $.Deferred();
                //var result = null;

                $.ajax({
                    type: "GET",
                    url: site_url + "/directorio/get_registros_directorio/" + tipo_nivel,
                    data: filter,
                    dataType: "json"
                })
                        .done(function (result) {
                            var reporte = get_reporte(result['data']);
                            var res = $.grep(result['data'], function (directorios) {
                                return (!filter.matricula || (directorios.matricula !== null && directorios.matricula.toLowerCase().indexOf(filter.matricula.toString().toLowerCase()) > -1))
                                        && (!filter.nombre_unidad || (directorios.nombre_unidad !== null && directorios.nombre_unidad.toLowerCase().indexOf(filter.nombre_unidad.toString().toLowerCase()) > -1))
                                        && (!filter.clave_unidad || (directorios.clave_unidad !== null && directorios.clave_unidad.toLowerCase().indexOf(filter.clave_unidad.toString().toLowerCase()) > -1))
                                        && (!filter.nombre_nombramiento || (directorios.nombre_nombramiento !== null && directorios.nombre_nombramiento.toLowerCase().indexOf(filter.nombre_nombramiento.toString().toLowerCase()) > -1))
                                        && (!filter.nombre || (directorios.nombre !== null && directorios.nombre.toLowerCase().indexOf(filter.nombre.toString().toLowerCase()) > -1))
                                        && (!filter.apellido_p || (directorios.apellido_p !== null && directorios.apellido_p.toLowerCase().indexOf(filter.apellido_p.toString().toLowerCase()) > -1))
                                        && (!filter.apellido_m || (directorios.apellido_m !== null && directorios.apellido_m.toLowerCase().indexOf(filter.apellido_m.toString().toLowerCase()) > -1))
                                        && (!filter.telefonos || (directorios.telefonos !== null && directorios.telefonos.toLowerCase().indexOf(filter.telefonos.toString().toLowerCase()) > -1))
                                        && (!filter.titulo || (directorios.titulo !== null && directorios.titulo.toLowerCase().indexOf(filter.titulo.toString().toLowerCase()) > -1))
                                        && (!filter.observaciones || (directorios.observaciones !== null && directorios.observaciones.toLowerCase().indexOf(filter.observaciones.toString().toLowerCase()) > -1))
                                        ;
                            });
//                            d.resolve(result['data']);
                            d.resolve(res);
                        });

                return d.promise();
            },
            updateItem: function (item) {
//                console.log(item);
                var de = $.Deferred();
                var datos_nuevos_registro = {
                    id_registro_directorio: item['id_directorio'],
                    matricula: item['matricula'],
//                    clave_delegacional: item['clave_delegacional'],
                    clave_delegacional: item['clave_delegacional'],
                    nombre: item['nombre'],
                    apellido_p: item['apellido_p'],
                    apellido_m: item['apellido_m'],
                    titulo: item['titulo'],
                    telefonos: item['telefonos'],
                    email: item['email'],
                    clave_nombramiento: item['clave_nombramiento'],
                    observaciones: item['observaciones'],
                    nombre_unidad: item['nombre_unidad'],
                    nombre_nombramiento: item['nombre_nombramiento'],
                }
                $.ajax({
                    type: "POST",
                    url: site_url + "/directorio/editar",
                    data: datos_nuevos_registro,
                    dataType: "json"
                })
                        .done(function (data) {
                            console.log(data);
//                            alert(data['message']);
                            apprise(data['message'], {verify: false}, function (btnClick) {
                                if (data['success'] === 1) {
                                    de.resolve(data['data']);
                                } else {
                                    item_auxiliar.nombre_completo = aux_objeto.nombre_completo;
                                    item_auxiliar.nombre = aux_objeto.nombre;
                                    item_auxiliar.apellido_p = aux_objeto.ap;
                                    item_auxiliar.apellido_m = aux_objeto.am;
                                    item_auxiliar.clave_delegacional = aux_objeto.clave_delegacional;
                                    de.resolve(item_auxiliar);
                                }
                            });
                        })
                        .fail(function (jqXHR, error, errorThrown) {
                            console.log("error");
                            console.log(jqXHR);
                            console.log(error);
                            console.log(errorThrown);
//                            de.resolve(grid._lastPrevItemUpdate);
                            item_auxiliar.nombre_completo = aux_objeto.nombre_completo;
                            item_auxiliar.nombre = aux_objeto.nombre;
                            item_auxiliar.apellido_p = aux_objeto.ap;
                            item_auxiliar.apellido_m = aux_objeto.am;
                            item_auxiliar.clave_delegacional = aux_objeto.clave_delegacional;
                            de.resolve(item_auxiliar);
                        });
                return de.promise();

            }
        },
        fields: [
            {name: "nombre_unidad", title: '<center><i class="fa fa-question-circle sipimss-helper" data-help="unidad"></i><br>Unidad</center>', type: "text", inserting: false, editing: false},
            {name: "clave_unidad", title: '<center><i class="fa fa-question-circle sipimss-helper" data-help="clave_unidad"></i><br>Clave de unidad</center>', type: "text", inserting: false, editing: false},
//            {name: "clave_nombramiento", type: "text", inserting: false, editing: false, visible: false},
            {name: "nombre_nombramiento", title: '<center><i class="fa fa-question-circle sipimss-helper" data-help="nombramiento"></i><br>Nombramiento</center>', type: "text", inserting: false, editing: false},
            {name: "matricula", title: '<center><br>Matrícula</center>', type: "text",
                validate: [
                    {
                        validator: "required",
                        message: function (value, item) {
                            return "El campo matrícula es obligatorio. Por favor ingreselo. ";
                        },
                    },
                ],
                editTemplate: function (value, item) {
//                    console.log(item);
                    if (!value) {
                        value = "";
                    }
                    item_auxiliar = item;
                    aux_objeto = {ap: item.apellido_p, am: item.apellido_m, nombre: item.nombre,
                        nombre_completo: item.nombre_completo, clave_delegacional: item.clave_delegacional};
                    var componente_edita_matricula = $('<input type="text" name="matricula_" id="matricula_" value= "' + value + '">');
                    var nombre_completo_fields = this._grid.fields[4];
                    var fields_completos = item;
                    $(componente_edita_matricula).on('keypress', function (event) {
//                        console.log(event);
                        actualiza_datos_matricula(event, fields_completos, nombre_completo_fields);
                    });

                    $(componente_edita_matricula).on('focusout', function (event) {
//                        console.log(event);
//                        console.log('monitor ');
                        if (monitor) {
                            actualiza_datos_matricula(event, fields_completos, nombre_completo_fields, fields_completos.clave_delegacional);
                        }
                    });

                    return componente_edita_matricula;
                },
                editValue: function () {
                    return $('#matricula_').val();
                }
            },
            {name: "nombre_completo", title: '<center><i class="fa fa-question-circle sipimss-helper" data-help="nombre"></i><br>Nombre</center>', type: "text", inserting: false, editing: true,
                editcss: "nombre_completo-editcss",
                editValue: function (value) {
                    return value;
                },
                editTemplate: function (value) {
                    return value;
                }

            },
//            {name: "apellido_p", title: "Apellido paterno", align: "center", type: "text", inserting: false, editing: true},
//            {name: "apellido_m", title: "Apellido materno", align: "center", type: "text", inserting: false, editing: true},
            {name: "email", title: '<center><br>Correo electrónico</center>', type: "text", inserting: false, editing: true},
            {name: "telefonos", title: '<center><br>Teléfonos</center>', align: "center", type: "text", inserting: false, editing: true},
            {name: "titulo", title: '<center><i class="fa fa-question-circle sipimss-helper" data-help="titulo"></i><br>Título</center>', type: "text", inserting: false, editing: true},
            {name: "observaciones", title: '<center><br>Comentarios</center>', type: "text", inserting: false, editing: true},
//            {name: "id_directorio", title: "Identificador directorio", align: "center", type: "hidden", inserting: false, editing: false},
            {type: "control", editButton: true, deleteButton: false,
                searchModeButtonTooltip: "Cambiar a modo búsqueda", // tooltip of switching filtering/inserting button in inserting mode
                editButtonTooltip: "Editar", // tooltip of edit item button
                searchButtonTooltip: "Buscar", // tooltip of search button
                clearFilterButtonTooltip: "Limpiar filtros de búsqueda", // tooltip of clear filter button
                updateButtonTooltip: "Actualizar", // tooltip of update item button
                cancelEditButtonTooltip: "Cancelar", // tooltip of cancel editing button
                itemTemplate: function(value, item) {
                    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
                    var $myButton = $('<input class="jsgrid-button jsgrid-delete-button" title="Limpiar registro" type="button">');
                    var adicional = this;
                    $myButton.click(function(){
                        limpiar_registro(item, adicional);
                    });
                    return $result.add($myButton);
                }
            }
        ]
    });
    $("#jsGridDirectorio").jsGrid("option", "filtering", false);
}

function actualiza_datos_matricula(event, item, field_nombre) {
    //console.log(item);
    var de = $.Deferred();
//                        console.log(de.resolve);
    var ejecuta_actualizacion = false;
    if (event) {
//Valida enter
        if (event.type === 'keypress' && event.keyCode === 13) {
            ejecuta_actualizacion = true;
        } else if (event.type === 'focusout') {
            ejecuta_actualizacion = true;
        }
    }
//                            console.log(fields_completos);
    if (ejecuta_actualizacion) {
        mostrar_loader();
        monitor = false;
        var componente_matricula = $('#matricula_').val();
        $.ajax({
            type: "POST",
            url: site_url + "/directorio/get_informacion_matricula/" + componente_matricula + "/" + item.clave_delegacional,
//                                data: fields_completos,
            dataType: "json"
        })
                .done(function (data) {
//                    console.log(data);
//                                        console.log('fields_completos');
//                                        console.log(nombre_completo_fields);
//                                        console.log(fields_completos);
//                            alert(data['message']);

                    if (data['success'] === 1) {
                        var nombre_completo_actual = data['empleado'].nombre + ' ' + data['empleado'].paterno + ' ' + data['empleado'].materno;
//                                            $(".nombre_completo-editcss").empty().append(nombre_completo_fields.editTemplate(nombre_completo_actual).attr("readonly", "readonly"));
                        $(".nombre_completo-editcss").empty().append(field_nombre.editTemplate(nombre_completo_actual));
                        item.apellido_p = data['empleado'].paterno;
                        item.apellido_m = data['empleado'].materno;
                        item.nombre = data['empleado'].nombre;
                        item.nombre_completo = nombre_completo_actual;
                        de.resolve(item);
                        ocultar_loader();
                    } else {
                        var text = 'No fue posible encontrar la información del funcionario con matrícula ' + componente_matricula + '. Por favor seleccione la delegación en la que esta adscrito el ' + item.nombre_nombramiento + ' .';
                        ocultar_loader();
                        apprise(text, {input: true}, function (btnClick) {
                            if (btnClick == false) {
                                $("#jsGridDirectorio").jsGrid("cancelEdit");
                                de.resolve(grid._lastPrevItemUpdate);
                                monitor = true;
                            } else {
                                item.clave_delegacional = btnClick;
                                console.log(btnClick);
                                actualiza_datos_matricula(event, item, field_nombre, btnClick);
                            }
                        });
                    }
                })
                .fail(function (jqXHR, error, errorThrown) {
                    console.log("error");
                    console.log(jqXHR);
                    console.log(error);
                    console.log(errorThrown);
                    ocultar_loader();
                });
    }
}

function limpiar_registro(item, adicional)
{
    console.log(adicional);
    console.log(item);
    var text = '¿Esta seguro que desea limpiar registro?';
    apprise(text,{verify: true}, function (btnClick) {
        if (btnClick){
            console.log('Actualizando');
            var destino = site_url + '/directorio/limpiar_registro/' + item.id_directorio;
            mostrar_loader();
            data_ajax(destino, null, recarga_grid());
        }
    });
}

function recarga_grid(){
    ocultar_loader();
    location.reload();
}

function get_reporte(datos_directorio) {
//    var d = $('#jsGridDirectorio').data("JSGrid");
//    var itemsCount = d.data.length;//Obtiene el tamaño de los datos
    var obj = new Object();//Unidades
    var total_registros = 0;
    var total_faltantes = 0;
    $.each(datos_directorio, function (index, value) {
        if (typeof obj[value.clave_unidad] === 'undefined') {
            obj[value.clave_unidad] = value.matricula;
        } else {
            obj[value.clave_unidad] = obj[value.clave_unidad] + ', ' + value.matricula;
        }
        if (value.matricula === null ) {
            total_faltantes++;
        }
        total_registros++;

    });
    var count_unidades = Object.keys(obj).length;
    $('.pinta_resumen').html(
            "<div class='col-lg-4 col-sm-4 col-md-4'><h5><strong>Número de unidades:</strong> " + count_unidades + "</h5></div>" +
            "<div class='col-lg-4 col-sm-4 col-md-4'><h5><strong>Número de registros:</strong> " + total_registros + "</h5></div>" +
            "<div class='col-lg-4 col-sm-4 col-md-4'><h5><strong>Número de registros faltantes:</strong> " + total_faltantes + "</h5></div>"
            );

//    console.log(obj);
}
//function get_reporte(datos_directorio) {
//    var obj = new Object();
//    var obj_nombrados = new Object();
//    var obj_cceis = new Object();
//    var obj_ecceis = new Object();
//    var obj_ecceis_cceis = new Object();
//    $.each(datos_directorio, function (index, value) {
//        if (typeof obj[value.clave_unidad] === 'undefined') {
//            obj[value.clave_unidad] = obj[value.matricula] + ', ' + value.matricula;
//            if (value.matricula !== null) {
//                if (typeof obj_nombrados[value.clave_unidad] === 'undefined') {
//                    if (!value.clave_nombramiento || (value.clave_nombramiento !== null && value.clave_nombramiento.toLowerCase().indexOf('cceis') > -1)) {
//                        if (!value.clave_nombramiento || (value.clave_nombramiento !== null && value.clave_nombramiento.toLowerCase().indexOf('ecceis') > -1)) {
//                            obj_ecceis[value.clave_unidad] = value.clave_nombramiento;
//                        } else {
//                            obj_cceis[value.clave_unidad] = value.clave_nombramiento;
//                        }
//                        obj_ecceis_cceis[value.clave_unidad] = value.clave_nombramiento;
//                        obj_nombrados[value.clave_unidad] = value.matricula;
//                    }
//                }
//            }
//        } else {
//            if (value.matricula !== null) {
//                if (!value.clave_nombramiento || (value.clave_nombramiento !== null && value.clave_nombramiento.toLowerCase().indexOf('cceis') > -1)) {
//                    if (!value.clave_nombramiento || (value.clave_nombramiento !== null && value.clave_nombramiento.toLowerCase().indexOf('ecceis') > -1)) {
//                        obj_ecceis[value.clave_unidad] = value.clave_nombramiento;
//                    } else {
//                        obj_cceis[value.clave_unidad] = value.clave_nombramiento;
//                    }
//                    obj_ecceis_cceis[value.clave_unidad] = value.clave_nombramiento;
//                    obj_nombrados[value.clave_unidad] = value.matricula;
//                }
//            }
//
//            obj[value.clave_unidad] = {matriculas: value.matricula};
//        }
//
//    });
//    var count_unidades = Object.keys(obj).length;
//    var count_unidades_nombrados = Object.keys(obj_nombrados).length;
//    var count_cceis = Object.keys(obj_cceis).length;
//    var count_ecceis = Object.keys(obj_ecceis).length;
//    var count_cceis_ecceis = Object.keys(obj_ecceis_cceis).length;
//
//    console.log('Cuenta unidades ' + count_unidades);
//    console.log('Cuenta unidades nombradas ' + count_unidades_nombrados);
//    console.log('Faltantes ' + (count_unidades - count_unidades_nombrados));
//    console.log('total CCEIS total = ' + count_cceis_ecceis);
//    console.log('total CCEIS = ' + count_cceis);
//    console.log('total ECCEIS = ' + count_ecceis);
//    console.log(obj_cceis);
//
//    $('.pinta_resumen').html(
//            "<div class='col-5col-sm-3 col-md-3'><h5><strong>Número de unidades:</strong> " + count_unidades + "</h5></div>" +
//            "<div class='col-sm-3 col-md-3'><h5><strong>Número de CCEIS registrados:</strong> " + count_cceis + "</h5></div>" +
//            "<div class='col-sm-3 col-md-3'><h5><strong>Número de ECCEIS registrados:</strong> " + count_ecceis + "</h5></div>" +
//            "<div class='col-sm-3 col-md-3'><h5><strong>Número de registros faltantes:</strong> " + (count_unidades - count_unidades_nombrados) + "</h5></div>"
//            );
//
////    console.log(obj);
//}
