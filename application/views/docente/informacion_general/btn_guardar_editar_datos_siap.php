<?php
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
?>
<div class="row">
    <div class="col-md-12 text-center">
        <button name="actualiza_imss" type="button" class="btn btn-tpl" 
        <?php echo $string_value['btn_actualiza_datos_imss']; ?>
                onclick="funcion_actualizar_datos_imss(this);"
                data-url="<?php echo '/' . $this->uri->rsegment(1); ?>"
                >
            <span><i class="fa fa-refresh"></i></span>
                <?php echo $string_value['btn_actualiza_datos_imss']; ?>
        </button>
    </div>
</div>
