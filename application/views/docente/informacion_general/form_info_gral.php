<?php
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL, En_catalogo_textos::GENERAL));
?>
<form class="form-horizontal" id="form_datos_generales" method="post" accept-charset="utf-8">


<div class="row">
  <div class="col-md-1">
  </div>
    <div class="col-md-5">
      <div class="row">
        <div class="col-md-4">
              <label for="nombre" class="pull-right control-label">
                  <b class="rojo">*</b>
                  Nombre (s):</label>
          </div>
          <div class="col-md-8">
              <div class="input-group">
                  <span class="input-group-addon ">
                      <span class="fa fa-user"> </span>
                  </span>
                  <?php
                  echo $this->form_complete->create_element(
                          array('id' => 'nombre',
                              'type' => 'textbox',
                              'value' => $docente['nombre'],
                              'attributes' => array(
                              'class' => 'form-control',
                              'title' => 'Nombre',)
                          )
                  );
                  ?>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-5">
      <div class="row">
          <div class="col-md-4">
              <label for="apellido_p" class="pull-right control-label">
                  <b class="rojo">*</b>
                  <?php echo $string_value['lbl_ap']; ?> </label>
          </div>
          <div class="col-md-8">
              <div class="input-group">
                  <span class="input-group-addon">
                      <span class="fa fa-male"> </span>
                  </span>
                  <?php
                  echo $this->form_complete->create_element(
                          array('id' => 'apellido_p',
                              'type' => 'textbox',
                              'value' => $docente['apellido_p'],
                              'attributes' => array(
                              'class' => 'form-control',
                              'title' => $string_value['lbl_ap'],
                              )
                          )
                  );
                  ?>
              </div>
          </div>
      </div>
    </div>


</div>

<br>
  <div class="row">
    <div class="col-md-1">
      </div>
        <div class="col-md-5">
          <div class="row">
          <div class="col-md-4">
              <label for="materno" class="pull-right control-label">
                  <b class="rojo">*</b>
                  Apellido materno:</label>
          </div>
          <div class="col-md-8">
              <div class="input-group">
                  <span class="input-group-addon">
                      <span class="fa fa-female"> </span>
                  </span>
                  <?php
                  echo $this->form_complete->create_element(
                          array('id' => 'apellido_m',
                              'type' => 'textbox',
                              'value' => $docente['apellido_m'],
                              'attributes' => array(
                                  'class' => 'form-control',
                                  'title' => 'Apellido Materno',)
                          )
                  );
                  ?>
                </div>
          </div>
      </div>
    </div>
    <div class="col-md-6">
    </div>
      <div class="col-md-5" id="div_rfc">
          <div class="row">
              <div class="col-md-4">
                  <label for="rfc" class="pull-right control-label">
                      <b class="rojo">*</b>
                      <?php echo $string_value['lbl_rfc'] ?> </label>
              </div>
              <div class="col-md-8">
                  <div class="input-group">
                      <span class="input-group-addon">
                          <span class="fa fa-key"> </span>
                      </span>
                      <?php
                      echo $this->form_complete->create_element(
                              array('id' => 'rfc',
                                  'type' => 'textbox',
                                  'value' => (isset($docente['rfc'])) ? $docente['rfc'] : '',
                                  'attributes' => array(
                                      'class' => 'form-control ',
                                      'title' => $string_value['lbl_rfc'],
                                      'readonly' => 'readonly',
                                  )
                              )
                      );
                      ?>
                  </div>
              </div>
          </div>
        </div>
    </div><br>
<div class="row">
  <div class="col-md-1">
  </div>
  <div class="col-md-5" id="div_curp">
    <div class="row">
      <div class="col-md-4">
        <label for="curp" class="pull-right control-label">
          <b class="rojo">*</b>
          <?php echo $string_value['lbl_curp'] ?> </label>
      </div>
      <div class="col-md-8">
        <div class="input-group">
          <span class="input-group-addon">
            <span class="fa fa-chain"> </span>
          </span>
            <?php
              echo $this->form_complete->create_element(
              array('id' => 'curp',
              'type' => 'textbox',
              'value' => (isset($docente['curp'])) ? $docente['curp'] : '',
              'attributes' => array(
              'class' => 'form-control',
              'title' => $string_value['lbl_curp'],
              'readonly' => 'readonly',
                )
               )
              );
            ?>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-5" id="div_edad">
      <div class="row">
          <div class="col-md-4">
              <label for="edad" class="pull-right control-label">
                  <b class="rojo">*</b>
                  Edad:</label>
          </div>
          <div class="col-md-8" >
              <div class="input-group">
                  <span class="input-group-addon">
                      <span class="fa fa-th-large"> </span>
                  </span>
                  <?php
                  echo $this->form_complete->create_element(
                          array('id' => 'edad',
                              'type' => 'textbox',
                              'value' => (isset($docente['edad'])) ? $docente['edad'] : '',
                              'attributes' => array(
                                  'class' => 'form-control',
                                  'title' => 'Apellido Paterno',
                                  'readonly' => 'readonly',
                              )
                          )
                  );
                  ?>
              </div>
          </div>
      </div>
    </div>

</div><br>
<div class="row">
  <div class="col-md-1">
  </div>
      <div class="col-md-5" id="div_correo">
        <div class="row">
          <div class="col-md-4">
            <label for="paterno" class="pull-right control-label">
              <b class="rojo">*</b>
              Correo electrónico:</label>
          </div>
          <div class="col-md-8">
            <div class="input-group">
              <span class="input-group-addon">
                <span class="fa fa-user-o"> </span>
              </span>
              <?php
              echo $this->form_complete->create_element(
                      array('id' => 'email',
                          'type' => 'textbox',
                          'value' => $docente['email'],
                          'attributes' => array(
                          'class' => 'form-control',
                          'title' => 'Correo electrónico',)
                      )
              );
              ?>

              </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
          <div class="row">
              <div class="col-md-4">
                  <label for="" class="pull-right control-label">
                      <b class="rojo">*</b>
                      Sexo:</label>
              </div>
              <div class="col-md-8">
                  <div class="input-group">
                      <span class="input-group-addon">
                          <span class="fa fa-users"> </span>
                      </span>
                      <?php
                      echo $this->form_complete->create_element(
                              array('id' => 'sexo',
                                  'type' => 'text',
                                  'value' => ($docente['sexo'] == En_sexo::MASCULINO) ? $string_value['text_sexo_h'] : $string_value['text_sexo_m'],
                                  'attributes' => array(
                                      'class' => 'form-control',
                                      'title' => 'Sexo',
                                      'readonly' => 'readonly',
                                  )
                              )
                      );
                      ?>
                  </div>
              </div>
          </div>
        </div>
</div><br>
<div class="row">
<div class="col-md-1">
</div>

              <div class="col-md-5">
                  <div class="row">
                      <div class="col-md-4">
                          <label for="paterno" class="pull-right control-label">
                              <b class="rojo">*</b>
                              Teléfono laboral:</label>
                      </div>
                      <div class="col-md-8">
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <span class="fa fa-phone"> </span>
                              </span>
                              <?php
                              echo $this->form_complete->create_element(
                                      array('id' => 'telefono_laboral',
                                          'type' => 'textbox',
                                          'value' => $docente['telefono_laboral'],
                                          'attributes' => array(
                                              'class' => 'form-control',
                                              'title' => ' ',)
                                      )
                              );
                              ?>
                          </div>
                      </div>
                  </div>
                </div>
                  <?php echo form_error_format('telefono_laboral'); ?>
                  <div class="col-md-5">
                      <div class="row">
                          <div class="col-md-4">
                              <label for="" class="pull-right control-label">
                                  <b class="rojo">*</b>
                                  Estado Civil:</label>
                          </div>
                          <div class="col-md-8">
                              <div class="input-group">
                                  <span class="input-group-addon">
                                      <span class="fa fa-hand"> </span>
                                  </span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'estado_civil',
                                              'type' => 'dropdown',
                                              'value' => $docente['id_estado_civil'],
                                              'first' => array('' => 'Selecciona opción'),
                                              'options' => $estado_civil,
                                              'attributes' => array(
                                                  'class' => 'form-control',
                                                  'title' => 'Estado civil',)
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div><br>
<div class="row">
<div class="col-md-1">
</div>

              <div class="col-md-5">
                  <div class="row">
                      <div class="col-md-4">
                          <label for="paterno" class="pull-right control-label">
                              <b class="rojo">*</b>
                              Teléfono particular:</label>
                      </div>
                      <div class="col-md-8">
                          <div class="input-group">
                              <span class="input-group-addon">
                                  <span class="fa fa-mobile"> </span>
                              </span>
                              <?php
                              echo $this->form_complete->create_element(
                                      array('id' => 'telefono_particular',
                                          'type' => 'textbox',
                                          'value' => $docente['telefono_particular'],
                                          'attributes' => array(
                                              'class' => 'form-control',
                                              'title' => '',)
                                      )
                              );
                              ?>
                          </div>
                      </div>
                  </div>
                          </div>

</div><br>


<br><br>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button name="actualiza_general" type="button" class="btn btn-tpl"
              <?php echo $string_value['btn_actualiza_datos_imss']; ?>
                      onclick="funcion_actualizar_datos_generales_docente(this);"
                      data-url="<?php echo '/' . $this->uri->rsegment(1); ?>"
                      >
                      <?php echo $string_value['actualizar_general']; ?>
          </button>
  </div>
      </div>

  </form>
<br>
