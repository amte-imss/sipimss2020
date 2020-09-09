
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
//'lbl_seccion'
//id_seccion
//carrucel
//string_value
$datos = (isset($datos)) ? $datos : [];
?>
<!-- <div class="panel-heading">
    Formación
</div> -->
<ul class="nav nav-pills">
    <?php
    $active = 'active';
    foreach ($datos as $key => $value) {
        ?>
        <li class="<?php echo $active; ?>"><a href="#name_seccion_<?php echo $value['id_seccion']; ?>-pills" data-toggle="tab" > <?php echo $value['lbl_seccion']; ?></a></li>
        <?php
        $active = '';
    }
    ?>
</ul>
<br>
<div class="tab-content">
    <?php
    $active = 'tab-pane fade active in';
    foreach ($datos as $key => $value) {
        ?>
        <div class="<?php echo $active; ?>" id="name_seccion_<?php echo $value['id_seccion']; ?>-pills">
            <?php echo $value['carrusel']; ?>
        </div>
        <?php
        $active = 'tab-pane fade';
    }
    ?>
</div>


<?php
//pr($files_js_render_formularios);
if (!empty($files_js_render_formularios)) {
    foreach ($files_js_render_formularios as $v) {
        $aux = str_replace("\"", "", $v['elementos']);
        echo js($aux); //Agrega archivos JS del formulario
    }
}
?>

