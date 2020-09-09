<?php
echo $this->form_complete->create_element(
    array(
      'id'=>'clave_regla_dependencia_catalogo',
      'type'=>'dropdown',
      'options'=> isset($reglas_dependencia_catalogo)?$reglas_dependencia_catalogo:'',
      'first' => array( '' => 'Seleccione una opción'),
      'value' => isset($datos['clave_regla_dependencia_catalogo'])?$datos['clave_regla_dependencia_catalogo']:''
    )
  );
echo form_error_format('clave_regla_dependencia_catalogo');
