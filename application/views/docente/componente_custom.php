<?php //pr($value);
$controlador = '/' . $this->uri->rsegment(1);
$propiedades=array('urlCarga'=>$controlador.'/getcursos'
); 
//$value['valor'] = base64_encode('[{"nombre_curso":"Curso inicial medico", "id_curso_exp_docente":1, "anio":2020}]');
//pr($_POST);
$valor_pos = (isset($_POST[$value['nom_campo']]) )? $_POST[$value['nom_campo']]: 
((isset($value['valor']) and $value['valor'] != 'NULL') ? base64_encode($value['valor']) : '');
?>
<input type="hidden" id="<?php echo $value['nom_campo']; ?>" name="<?php echo $value['nom_campo']; ?>" value= <?php echo $valor_pos;?>>
<div id="<?php echo 'div_' . $value['nom_campo']; ?>" class="componente_custom">
<input type="hidden" id="prop_<?php echo $value['nom_campo']; ?>" value="<?php echo base64_encode(json_encode($propiedades));?>" >
    <h3><?php echo $value['lb_campo']; ?></h3>
    <div id="<?php echo 'div_secundario_' . $value['nom_campo']; ?>" >

    </div>
    <?php
       echo form_error_format($value['nom_campo']); //agrega div para mostrar error
    ?>
</div>
