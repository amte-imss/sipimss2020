<?php ?>

<?php
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
$name_formulario = 'form_imagen_perfil';
$div_respuesta = 'div_result_imagen';
//Imagen del usuario
$this->load->config('general');
$ruta_imagen_perfil = $this->config->item('img_perfil_default');
$file_hidden = '<input type="hidden" id="file_cve" name="file_cve" value="">';
if (isset($elementos_imagen['id_file'])) {//Valida que existe la imagen del usuario
    $file_hidden = '<input type="hidden" id="file_cve" name="file_cve" value="' . base64_encode($elementos_imagen['id_file']) . '">';
    if (!is_null($elementos_imagen['nombre_fisico']) and ! is_null($elementos_imagen['ruta'])) {
        //Carga imagen del usuario docete
        $ruta_imagen_perfil = base_url($elementos_imagen['ruta'] . $elementos_imagen['nombre_fisico'] . '.' . $elementos_imagen['extencion']);
        if (file_exists($ruta_imagen_perfil)) {//Valida que exista la imagen del perfil solicitada, si no existe, muestra imagen default
            $ruta_imagen_perfil = $this->config->item('img_perfil_default');
        } else {

        }
    } else {
        $ruta_imagen_perfil = $this->config->item('img_perfil_default');
    }
}
?>

<?php echo css('template_sipimss/carga_imagen/vendor/jquery.Jcrop.css'); ?>
<?php echo css('template_sipimss/carga_imagen/demo.css'); ?>

<?php echo form_open('', array('id' => $name_formulario)); ?>
<div id="<?php echo $div_respuesta; ?>" >
</div>

<label><?php echo $string_value['lbl_cargar_imagen']; ?> </label><p><input type="file" id="file-input" accept="image/jpg, image/png"></p>

<?php echo $file_hidden; //Componente que indica que existe un archivo imagen cargado ?>
<!--    <canvas>
    </canvas>-->
<!--    <canvas id="canvas_img" width="300"></canvas>
    <script>
        var canvas = document.getElementById('canvas_img');
        var context = canvas.getContext('2d');
        var imageObj = new Image();

        imageObj.onload = function () {
            context.drawImage(imageObj, 0, 0, 300,300);
        };
        imageObj.src = '';
    </script>-->
<div id="result" class="result" style="width: 200px; ">
    <img src="<?php echo $ruta_imagen_perfil; ?>"  width="200">
</div>

<p id="actions" style="display:none;">
<!--    <button class="info" type="button" id="edit">Editar</button>
    <button class="info" type="button" id="crop">Cortar</button>-->
    <button class="btn btn-tpl btn-label-right" type="button"
            id="guarda_cambios_imagen" >
        <?php echo $string_value['btn_guardar_cambios']; ?>
    </button>
</p>


<br>
<?php echo form_close(); ?>
<!--<div id="exif" class="exif" style="display:none;">
    <h2>Exif meta data</h2>
    <p id="thumbnail" class="thumbnail" style="display:none;"></p>
    <table></table>
</div>-->


<?php echo js('template_sipimss/carga_imagen/load-image.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-scale.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-meta.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-fetch.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-exif.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-exif-map.js'); ?>
<?php echo js('template_sipimss/carga_imagen/load-image-orientation.js'); ?>
<?php echo js('template_sipimss/carga_imagen/vendor/jquery.Jcrop.js'); ?>
<?php echo js('template_sipimss/carga_imagen/demo/demo.js'); ?>
