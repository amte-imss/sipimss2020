<a onclick="show_modal_campos_dependientes()" >Gestionar campos dependientes</a>
<?php
echo $this->form_complete->create_element(
    array(
      'id'=>'campos_dependientes',
      'type' => 'hidden',
      'value' => isset($datos['campos_dependientes'])? $datos['campos_dependientes']:''
    )
  );
?>
