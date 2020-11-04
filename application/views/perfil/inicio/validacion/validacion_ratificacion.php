<?php $aux_doc = encrypt_base64($id_docente); 
    $ratificacion_value = '';
    $comentario_value = '';

    if(isset($ratificacion) && !empty($ratificacion)){
        $ratificacion_value = $ratificacion['ratificado']; 
        $comentario_value = $ratificacion['comentario']; 
    }
?>
<?php echo form_open('', array('id' => 'form_ratificacion_'. $aux_doc)); ?>
<div class="col-md-12">
    <h1 class="page-head-line"></h1>
</div>
<input id="docente" name="docente" type="hidden" value="<?php echo encrypt_base64($id_docente); ?>"> 
<div> 
    <label  class="control-label text-right">Ratificación:</label>
    <?php
        $rat = array(["id"=>1, "label"=>"Sí"], ["id"=>2, "label"=>"No"]);
        $optiones = dropdown_options($rat, "id", 'label');
        echo $this->form_complete->create_element(array(
            'id' => 'ratificacion', 'type' => 'dropdown',
            'options' => $optiones,
            'first' => array('' => 'Selecciona opción'),
            'value' => $ratificacion_value,
            'attributes' =>array(
                'class'=> 'form-control component_ratifica',
            )
        )); 
     ?>
        
</div> 
<div> 
    <label  class="control-label text-right">Comentario:</label>
    <textarea id="coment_ratificacion_<?php echo $aux_doc; ?>" name="comentario_ratificacion" class="md-textarea form-control component_ratifica" rows="5" ><?php echo $comentario_value;?></textarea><br>    
</div> 
<?php echo form_close()?>
