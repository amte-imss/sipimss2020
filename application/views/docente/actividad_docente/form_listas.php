
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($catalogos);
//pr($formulario_campos);
//exit();
//Abre formulario
$boton_submit = NULL; //Variable que servirá para identificar un submit
echo js('docente/formacion_docente/formacion_docente.js');
echo form_open('', array('id' => 'seccionesOpciones'));
?>

<div class="list-group">
    <div class="list-group-item">
        <div class="panel-body">
 <?php
      echo $this->form_complete->create_element(array('id' => 'cseccion', 'type' => 'dropdown', 'name' => 'cseccion', 
                            'options' => $copciones, 
                            'first' => array('0' => 'Seleccione '), 
                            'value' => '',
                            'attributes' => array('class' => 'form-control', 
                            'placeholder' => 'Secciones', 
                            'data-toggle' => 'tooltip', 
                            'data-placement' => 'top'  
                            )
                       )); 

      echo $this->form_complete->create_element(array('id' => 'c_elem_seccion', 'type' => 'dropdown', 'name' => 'c_elem_seccion',
                            'options' => '', 
                            'first' => array('0' => 'Seleccione '), 
                            'value' => '',
                            'attributes' => array('class' => 'form-control', 
                            'placeholder' => 'Sub-secciones', 
                            'data-toggle' => 'tooltip', 
                            'data-placement' => 'top'  
                            )
                       )); 



      echo $this->form_complete->create_element(array('id' => 'c_elem_subseccion', 'type' => 'dropdown', 'name' => 'c_elem_subseccion',
                            'options' => '', 
                            'first' => array('' => 'Selecciona '), 
                            'value' => '',
                            'attributes' => array('class' => 'form-control', 
                            'placeholder' => 'Sub-secciones', 
                            'data-toggle' => 'tooltip', 
                            'data-placement' => 'top',
                            'style' => 'display:none'  
                            )
                       )); 

      echo $this->form_complete->create_element(array('id' => 'c_elem_subseccions', 'type' => 'dropdown', 'name' => 'c_elem_subseccions',
                            'options' => '', 
                            'first' => array('' => 'Selecciona '), 
                            'value' => '',
                            'attributes' => array('class' => 'form-control', 
                            'placeholder' => 'Sub-secciones', 
                            'data-toggle' => 'tooltip', 
                            'data-placement' => 'top',
                            'style' => 'display:none'  
                            )
                       )); 



?>
        </div>
    </div>
    </div>
<?php echo form_close(); ?>