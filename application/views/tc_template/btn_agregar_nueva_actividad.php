<?php
$evento = '';
$censo = '';
$fin_registro_censo = ($fin_registro_censo)? 1:0;
$fin_registro = 'data-registrofinal="'.$fin_registro_censo.'"';
//if($fin_registro_censo){


if (isset($tipo_evento_js) and ! is_null($tipo_evento_js) and isset($funcion_js) and ! is_null($funcion_js)) {
    $evento = $tipo_evento_js . ' = ' . $funcion_js;
}
//pr($config);
if(isset($config['censo_static'])){
    //pr('Esta es la configuracion');
    $ruta = 'data-rutaeditar="/' . $this->uri->rsegment(1) . '/carga_actividad/"';
    $censo = 'data-censo="'.$config['censo_static'].'"';
    $evento = $tipo_evento_js . ' = ' . '"cargar_actividad(this);"';
}else{
    $ruta = 'data-ruta="/' . $this->uri->rsegment(1) . '/carga_seccion/"';
    
}
//pr($seccion);
$text_seccion = '';
if (isset($seccion)) {
    $text_seccion = 'data-seccion="' . encrypt_base64($seccion) . '"';
}
$display = '';
if (!empty($config) && isset($config['btnAgregarNuevo']) && $config['btnAgregarNuevo']==0) {
    $display = 'style="display:none"';
}

?>

<button id="<?php echo $id_btn; ?>" name="save" type="button" class="btn btn-lg btnverde" <?php echo $evento; ?>
        <?php echo $ruta; ?>        
        <?php echo $censo; ?>        
        <?php echo $display; ?>        
        <?php echo $text_seccion; ?>
        <?php echo $fin_registro; ?>
>
        <span><i class="fa fa-plus"></i></span>
        <?php echo $titulo; ?>
</button>
<?php //}?>