<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
?>
<div id="myCarousel<?php echo $id_seccion; ?>">
    
    <?php echo form_open('', array('id' => 'form_val_seccion_' . $id_seccion)); //Pinta formulario
    ?>
    <div >

            <table class="table table-striped table-bordered table-hover" id="comp_impresion<?php echo $id_seccion; ?>">
                <thead>
                    <tr>
            <?php
            if($pinta_elemento_seccion){
                ?>
                 <th> <?php echo "Formulario"; ?></th>
                <?php
            }
                    foreach ($campos_seccion as $key_campos => $value_campo) {
                    //pr($campos);
                        $campoName = $value_campo;
            ?>
                        <th><?php echo $campoName;?> </th>
            <?php
                    }
                    if($conf_validacion[1]['view_col_val_censo']){//Pinta columnas validacion de la seccion

            ?>
                        <th> <input type="radio" id="radio_si_general<?php echo $id_seccion?>" name="radio_general_<?php echo $id_seccion?>" class="tipo_radio" data-seccion="<?php echo $id_seccion?>" onclick="selecciona_todo(this);" value="si"><label>Si a todo </label></th>
                        <th> <input type="radio" id="radio_no_general<?php echo $id_seccion?>" name="radio_general_<?php echo $id_seccion?>" class="tipo_radio" data-seccion="<?php echo $id_seccion?>" onclick="selecciona_todo(this);" value="no"><label>No a todo </label></th>
                        
            <?php
            ?>
            <?php
                    }
            ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($elementos_seccion)) {
                        ?>
                        <?php echo $elementos_seccion; ?>
                    <?php } ?>
                </tbody>
                
            </table>                
        </div>
            
            <?php echo $conf_validacion[1]['view'];?>
            <?php echo form_close()?>
            <?php echo $conf_validacion[1]['view_btn_guardar'];?>
</div>

  