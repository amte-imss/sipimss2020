<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->config('general');
$ruta_imagen_perfil = $this->config->item('img_perfil_default');
if (!is_null($elementos_seccion) and ! empty($elementos_seccion)) {
    if (!is_null($elementos_seccion['nombre_fisico']) and ! is_null($elementos_seccion['ruta'])) {
        //Carga imagen del usuario docete
        $ruta_imagen_perfil = base_url($elementos_seccion['ruta'] . $elementos_seccion['nombre_fisico'] . '.' . $elementos_seccion['extencion']);
        if (file_exists($ruta_imagen_perfil)) {//Valida que exista la imagen del perfil solicitada, si no existe, muestra imagen default 
            $ruta_imagen_perfil = $this->config->item('img_perfil_default');
        }
    } else {
        $ruta_imagen_perfil = $this->config->item('img_perfil_default');
    }
}
?>

<?php echo css('template_sipimss/style_profile.css'); //estilos de perfil  ?>
<div class="col-lg-12 col-sm-6">
    <div class="card hovercard">
        <div class="card-background">
            <img class="card-bkimg" alt="" src="http://lorempixel.com/100/100/people/9/">
        </div>
        <div class="useravatar">
            <img alt="" src="<?php echo $ruta_imagen_perfil; ?>">
        </div>
        <div class="card-info"> <span class="card-title"><?php echo (isset($nombre_docente)) ? $nombre_docente : '' ?></span>

        </div>
    </div>

    <br>
    <br>

</div>