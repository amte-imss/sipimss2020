$(document).ready(function () {//Ejecuta al final de la página
    var element = document.getElementById('duracion');
//    var prop_elemento = $(element);
    var display = $(document.getElementById('horas')).data("displaycomponente");
    var value_select_duracion = parseInt(element.value);
    
    switch (value_select_duracion) {
        case 254: //hrs
            $("#div_horas").css("display", "none");//Visualiza
            $('#div_horas').toggle("slow");//Evento, forma de salida
            break;
        default :
            $("#div_horas").css("display", display);//Oculta
            $('#div_horas').toggle("slow");//Evento, forma de salida
    }
});

function visualizar_campos(element) {
//    var prop_elemento = $(element);
    var display = $(document.getElementById('horas')).data("displaycomponente");
    switch (parseInt(element.value)) {
        case 254: //horas
            $("#div_horas").css("display", "none");
            $('#div_horas').toggle("slow");//Evento, forma de salida
            break;
        default :
            $("#div_horas").css("display", display);
            $('#div_horas').toggle("slow");//Evento, forma de salida
            document.getElementById('horas').value = "";
    }
}