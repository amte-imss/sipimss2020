$(document).ready(function () {//Ejecuta al final de la página

    $('.c_duracion').each(function (index, val_duracion) {
        var clone_label = null;
        var splite_descripcion_cadena = val_duracion.id.split("_");
        var censo = splite_descripcion_cadena[1];
        if (document.getElementById("div_" + censo + "_horas")) {//Valida que exista horas
            $('#' + val_duracion.id + ' > p > label').each(function (index, value_label) {
                clone_label = value_label.cloneNode(true);//Clone de label
            });
            var clone_parrafo = null;//var para almacenar el clone de parrafo
            var proceso_satisfactorio = true;
            $('#' + val_duracion.id + ' > p').each(function (index, value_parrafo) {
                clone_parrafo = value_parrafo.cloneNode(true);//Clone de parrafo
                var horas = '';
                $('#div_' + censo + '_horas > p').each(function (index, horas_valor) {
                    try {
                        $(value_parrafo).html('');//Elimina infoirmación del parrafo
                        value_parrafo.appendChild(clone_label);//Agraga el label clonado al parrafo
                        var tmphoras = $(horas_valor).text().trim().toLowerCase();
                        var separa_cadena = tmphoras.split(":");
                        horas = " " + separa_cadena[1].toString().trim() + " " + separa_cadena[0];
                        $(value_parrafo).append(horas);//Agrega la cantidad de horas
                        //Elimina div horas 
                        $("#div_" + censo + "_horas").remove();//Remueve div horas
                    } catch (e) {
                        //No hace nada
                        proceso_satisfactorio = false;
                    }

                });

            });
            if (!proceso_satisfactorio) {//Existe un problema, se reestablece el div involucrado
                $(val_duracion).html('');//Elimina infoirmación del parrafo
                val_duracion.appendChild(clone_parrafo);//parrafo
            }
        }
    });
    $('.c_sede').each(function (index, val_sede) {
        var tmpsede = $(val_sede).text().trim();
        var separa_cadena = tmpsede.split(":");
        var clave_unidad = separa_cadena[1].toString().trim();
        var texto_sede = separa_cadena[0].toString().trim();
        if (clave_unidad.length > 0) {//Obtener sede
            var consulta = site_url + "/rama_organica/get_detalle/unidad/" + clave_unidad;
            $.getJSON(consulta, {})
                    .done(function (data, textStatus, jqXHR) {
                        if (data[0] /*&& textStatus === 'success'*/) {
                            var clone_label = null;
                            $('#' + val_sede.id + '> p >label').each(function (index, value_label) {
                                clone_label = value_label.cloneNode(true);//Clona el label
                            });

                            $('#' + val_sede.id + '> p ').each(function (index, value_parrafo) {
//                                var parrafo_ = value_parrafo.cloneNode(true);//Clona el label
                                $(value_parrafo).html('');
                                value_parrafo.appendChild(clone_label);
                                $(value_parrafo).append(data[0].unidad);
                            });

                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        return null;
                    });
        }
    });
});