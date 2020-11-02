<?php 
    $controlador = '/'.$this->uri->rsegment(1).'/guarda_val_seccion';
    
    //pr("->".$is_view_personalizada. " - " . $id_seccion);
?>

<div class="text-right">
    <button class="btn btn-success" id="btn_val_seccion_<?php echo $id_seccion; ?>" style=" background-color:#008EAD" 
        data-seccion="<?php echo $id_seccion; ?>"
        data-path="<?php echo $controlador; ?>"
        onclick="guarda_val_seccion(this);"
    >
        Guardar<span class=""></span>
    </button>
</div>



