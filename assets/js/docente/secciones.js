$(document).ready(function () {
    //console.log("Aqui entra ");
    if(typeof properties !== undefined && properties !== undefined){
        if(properties.visibleGridRegistrosSeccion==0 || properties.visibleGridRegistrosSeccion=='0'){
            $("#js_grid_registros_seccion").css({'display' : 'none'});    
        }       
        if(properties.visibleOptionSeccion==0 || properties.visibleOptionSeccion=='0'){            
            //console.log("que vales" + properties.visibleOptionSeccion);     
            $("#seccion_seccion").css({'display':'none'});
        }
    }
});
