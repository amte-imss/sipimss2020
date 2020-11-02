<?php 
    
?>

    <div>
        <?php if( $is_view_personalizada == 1){ ?>           
        <div class="row" >
            <div class="col-md-6" >
                    <label  class="control-label text-right">Comentario:</label>        
            </div>
            <div class="col-md-6 text-right">
                <input type="radio" id="radio_si_<?php echo $id_censo;?>" class="ctrselect_si_<?php echo $id_seccion?>" name="<?php echo $id_censo;?>" value="si"><label>Si </label>
                <input type="radio" id="radio_no_<?php echo $id_censo;?>" class="ctrselect_no_<?php echo $id_seccion?>" name="<?php echo $id_censo;?>" value="no"><label>No </label>            
            </div>
        </div>
    <?php }else{?>
        <label  class="control-label text-right">Comentario:</label>        
    <?php }?>
    <input id="seccion" name="seccion_validacion" type="hidden" value="<?php echo $id_seccion;?>" >    
    <input id="docente" name="docente" type="hidden" value="<?php echo encrypt_base64($id_docente);?>" >    
    <textarea id="coment_seccion_<?php echo $id_seccion; ?>" name="comentario_seccion" class="md-textarea form-control" rows="3"></textarea><br>    
    <?php echo form_error_format("comentario_seccion");?>
    <div class="col-md-12" id="div_error_<?php echo $id_seccion;?>" style='display:none'>
        <div id="alerta_seccion_<?php echo $id_seccion;?>"  class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <span id="msg_<?php echo $id_seccion;?>"></span>
        </div>
        </div>
    </div>
        




