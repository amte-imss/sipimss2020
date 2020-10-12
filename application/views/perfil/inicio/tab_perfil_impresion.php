
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
        }
    ?>
</ul>
<br>

<?php
//pr($files_js_render_formularios);
if (!empty($files_js_render_formularios)) {
    foreach ($files_js_render_formularios as $v) {
        $aux = str_replace("\"", "", $v['elementos']);
        echo js($aux); //Agrega archivos JS del formulario
    }
}
?>
