<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);

?>
<div id="myCarousel<?php echo $id_seccion; ?>">
    <?php echo form_open('', array('id' => 'form_val_seccion_' . $id_seccion)); ?>    
    <div>
        <?php
        if (isset($elementos_seccion)) {
            ?>
            <?php echo $elementos_seccion; ?>
        <?php } ?>
    </div>

    <!-- <a class="left carousel-control" href="#myCarousel<?php // echo $id_seccion; ?>" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="right carousel-control" href="#myCarousel<?php // echo $id_seccion; ?>" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Siguiente</span>
    </a>
    -->
    
    <?php echo $conf_validacion[1]['view'];?>
    <?php echo form_close()?>
    <?php echo $conf_validacion[1]['view_btn_guardar'];?>
</div>
