
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
//'lbl_seccion'
//id_seccion
//carrucel
//string_value
?>
<!-- <div class="panel-heading">
    Formación
</div> -->
<input id="status_validacion_docente" type="hidden" value="<?php echo $status_validacion_censo;?>">
<ul class="nav nav-pills">
    <?php
    //$active = 'active';
    //pr($datos);
    $config_secciones = $this->config->item('config_secciones');
        if(isset($datos)){

            foreach ($datos as $key => $value) {
                    ?>
                <!--li class="<?php //echo $active; ?>"><a href="#name_seccion_<?php //echo $value['id_seccion']; ?>-pills" data-toggle="tab" > <?php //echo $value['lbl_seccion']; ?></a></li-->
                <?php //echo (isset($config_secciones[$key]['s_seccion'])) ? '<h2>'.$config_secciones[$key]['s_seccion'].'</h2>' : '<h2>'.$value['lbl_seccion'].'</h2>'; ///Se  ?>
                <h4><strong><?php echo $value['lbl_seccion']; ?></strong></h4>
                <?php
                //$active = '';
                //$active = 'tab-pane fade active in';
                //if($datos['id_seccion']==){
                    
                    //}
                    //foreach ($datos[$key] as $key_1 => $val) {
                        //pr($val);
                    ?>
                    <div class="" id="name_seccion_<?php echo $value['id_seccion']; ?>-pills">
                        <?php echo $value['carrusel']; ?>
                    </div>
                    
                    <?php
                //}
            }
            if(isset($conf_validacion[2])){
                if($conf_validacion[2]['vista_ratificacion']){
                    echo $conf_validacion[2]['view'];                
                }                
                echo $conf_validacion[2]['view_btn_ratificar'];                
            }
            if(isset($conf_validacion[3])){
                echo $conf_validacion[3]['view'];                
            }
        }
    ?>
</ul>
<?php ?>
    
<?php ?>
<br>
<script>
    var bloqueo_componentes_validacion_secciones = <?php echo $conf_validacion[4]['bloqueo_componentes_validacion_secciones']; ?>
</script>
<?php
//pr($files_js_render_formularios);
if (!empty($files_js_render_formularios)) {
    foreach ($files_js_render_formularios as $v) {
        $aux = str_replace("\"", "", $v['elementos']);
        echo js($aux); //Agrega archivos JS del formulario
    }
}
if(isset($registros_validacion_seccion)){
    ?>
    <script>
        var datos_validacion_seccion = <?php echo json_encode($registros_validacion_seccion); ?>
        </script>
    <?php 
}


if(isset($files_js_validacion_censo)){
    
    echo js($files_js_validacion_censo); //Agrega archivos JS del formulario
}
?>
<script type="text/javascript">

    var consulta = '';
    $(document).ready(function () {
		//console.log("Qe ");
        $('.l_sede_academica').each(function (index, element) {
        var label = $(element);
        consulta =  site_url +"/rama_organica/get_detalle/unidad/"+label.text()+"/actual" ;
        $.getJSON(consulta, {})
        .done(function (data, textStatus, jqXHR) {
            //console.log(label.text());
            //console.log(data);
                if (data[0] /*&& textStatus === 'success'*/) {
                    label.text(data[0].unidad + "("+ data[0].clave_unidad+")");           
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                //get_mensaje_general_modal("Ocurrió un error durante el proceso, inténtelo más tarde.", textStatus, 5000);
            });

        //console.log(label.text());
    });
});
</script>