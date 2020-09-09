<?php
echo js('formularios/rama_seccion.js');
?>

<div id="page-inner">
<br>
<?php
//echo form_open('formulario', array('id' => 'form_rama_seccion'));
?>
<div class="row form-group">
  <div class="col-md-3">
    <div class="input-group input-group-sm">
      <?php
      echo $this->form_complete->create_element(
          array(
            'id' => 'id_seccion',
            'type' => 'dropdown',
            'options' => $secciones,
            'first' => array( '' => 'Seleccione una sección'),
            'attributes' => array(
                    'class'=>'form-control input-sm'
                  )
          )
        );
      ?>
    </div>
  </div>
</div>

<div class="row form-group">
  <div id='subsecciones'></div>
</div>
<br>
<div class="row">
  <div class="col-md-3">
    <input id="boton_rama_seccion" onclick="cargar_formulario()" class="btn btn-primary" value="Buscar formularios">
  </div>
</div>

<?php 
//echo form_close(); 
?>