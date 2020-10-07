$(document).ready(function () {
    //console.log("Aqui entra ");
    if(typeof properties !== 'undefined' ){
        if(typeof properties.visibleGridRegistrosSeccion !== 'undefined' && properties.visibleGridRegistrosSeccion==0 || properties.visibleGridRegistrosSeccion=='0'){
            $("#js_grid_registros_seccion").css({'display' : 'none'});    
        }       
        if(typeof properties.visibleOptionSeccion !== 'undefined' && properties.visibleOptionSeccion==0 || properties.visibleOptionSeccion=='0'){            
            //console.log("que vales" + properties.visibleOptionSeccion);     
            $("#seccion_seccion").css({'display':'none'});
            $("#catalogo_secciones_actividad_docente").css({'display':'none'});
            $("#secciones_datatable").css({'display':'none'});
        }
        
    }
});
