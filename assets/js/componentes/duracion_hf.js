$(document).ready(function () {//Ejecuta al final de la página
    var element = document.getElementById('duracion');
//    var prop_elemento = $(element);
    var display = $(document.getElementById('horas')).data("displaycomponente");
    var value_select_duracion = parseInt(element.value);
    
    switch (value_select_duracion) {
        
        case 254: //hrs
            $("#div_horas").css("display", "none");//Visualiza
            $('#div_horas').toggle("slow");//Evento, forma de salida
               /*Fecha de Inicio Termino*/
            $("#div_fecha_inicio").css("display", "block");//Visualiza
            $('#div_fecha_inicio').toggle("slow");//Evento, forma de salida   
            $("#div_fecha_termino").css("display", "block");//Visualiza
            $('#div_fecha_termino').toggle("slow");//Evento, forma de salida         
            break;

        case 551: //Fechas
            $("#div_horas").css("display", "block");//Oculta
            $('#div_horas').toggle("slow");//Evento, forma de salida
               /*Fecha de Inicio Termino*/
            $("#div_fecha_inicio").css("display", "none");//Visualiza
            $('#div_fecha_inicio').toggle("slow");//Evento, forma de salida
            $("#div_fecha_termino").css("display", "none");//Visualiza
            $('#div_fecha_termino').toggle("slow");//Evento, forma de salida
            break;

        default :
            $("#div_horas").css("display","block");//Oculta
            $('#div_horas').toggle("slow");//Evento, forma de salida
            $("#div_fecha_inicio").css("display","block");
            $('#div_fecha_inicio').toggle("slow");
            $("#div_fecha_termino").css("display","block");
            $('#div_fecha_termino').toggle("slow");
            
    }
});

function visualizar_fechash(element) {
//    var prop_elemento = $(element);
    var display = $(document.getElementById('horas')).data("displaycomponente");
    switch (parseInt(element.value)) {
        
        case 254: //hrs
            $("#div_horas").css("display", "none");//Visualiza
            $('#div_horas').toggle("slow");//Evento, forma de salida
               /*Fecha de Inicio*/
            $("#div_fecha_inicio").css("display", "block");//Visualiza
            $('#div_fecha_inicio').toggle("slow");//Evento, forma de salida   
            $("#div_fecha_termino").css("display", "block");//Visualiza
            $('#div_fecha_termino').toggle("slow");//Evento, forma de salida         
            break;

        case 551: //Fechas
            $("#div_horas").css("display", "block");//Oculta
            $('#div_horas').toggle("slow");//Evento, forma de salida
               /*Fecha de Inicio Termino*/
            $("#div_fecha_inicio").css("display", "none");//Visualiza
            $('#div_fecha_inicio').toggle("slow");//Evento, forma de salida
            $("#div_fecha_termino").css("display", "none");//Visualiza
            $('#div_fecha_termino').toggle("slow");//Evento, forma de salida
            break;
        default :
            $("#div_horas").css("display","block");//Oculta
            $('#div_horas').toggle("slow");//Evento, forma de salida
            $("#div_fecha_inicio").css("display","block");
            $('#div_fecha_inicio').toggle("slow");
            $("#div_fecha_termino").css("display","block");
            $('#div_fecha_termino').toggle("slow");
    }
}