<div class="list-group">
  <div class="list-group-item">
    <div class="panel-body" >|
      <form id="" method="post" accept-charset="utf-8">
        <div class="row">

            <div class="row">
              <div class="col-md-1">
                <label for="paterno" class="control-label">
                  <b class="rojo">*</b>
                  Apellido paterno </label>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <span class="fa fa-male"> </span>
                    </span>
                    <?php echo $this->form_complete->create_element(
                      array('id' => 'apellido_p',
                      'type' => 'textbox',
                      'value'=> $docente['apellido_p'],
                      'attributes' => array(
                        'class' => 'form-control  form-control',
                        'title' => 'Apellido Paterno',)
                      )
                    );
                    ?>
                  </div>
                </div>
              </div>

            <div class="col-md-3" style="display: 1">
              <div class="row">
                <div class="col-md-3">
                  <label for="materno" class="control-label">
                    <b class="rojo">*</b>
                    Apellido materno</label>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="fa fa-female"> </span>
                      </span>
                      <?php echo $this->form_complete->create_element(
                        array('id' => 'apellido_m',
                        'type' => 'textbox',
                        'value'=> $docente['apellido_m'],
                        'attributes' => array(
                          'class' => 'form-control  form-control input-sm ',
                          'title' => 'Apellido Paterno',)
                        )
                      );
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div><br><div class="row">
              <div class="col-md-6"  style="display: 1">
                <div class="row">
                  <div class="col-md-4">
                    <label for="nombres" class="control-label">
                      <b class="rojo">*</b>
                      Nombre (s)
                    </label>
                  </div>
                  <div class="col-md-8">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="fa fa-user"> </span>
                      </span>
                      <?php echo $this->form_complete->create_element(
                        array('id' => 'nombre',
                        'type' => 'textbox',
                        'value'=> $docente['nombre'],
                        'attributes' => array(
                          'class' => 'form-control  form-control input-sm ',
                          'title' => 'Apellido Paterno',)
                        )
                      );
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6" id="div_edad" style="display: 1">
                <div class="row">
                  <div class="col-md-4">
                    <label for="edad" class="control-label">
                      <b class="rojo">*</b>
                      Edad                            </label>
                    </div>
                    <div class="col-md-8">
                      <div class="input-group">
                        <span class="input-group-addon">
                          <span class="fa fa-pagelines "> </span>
                        </span>
                        <?php echo $this->form_complete->create_element(
                          array('id' => 'edad',
                          'type' => 'textbox',
                          'value'=> $docente['edad'],
                          'attributes' => array(
                            'class' => 'form-control  form-control input-sm ',
                            'title' => 'Apellido Paterno',)
                          )
                        );
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div><br><div class="row">
                <div class="col-md-6" id="div_genero" style="display: 1">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="genero" class="control-label">
                        <b class="rojo">*</b>
                        Género                            </label>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <span class="fa fa-venus-mars"> </span>
                          </span>
                          <?php echo $this->form_complete->create_element(
                            array('id' => 'sexo',
                            'type' => 'textbox',
                            'value'=> $docente['sexo'],
                            'attributes' => array(
                              'class' => 'form-control  form-control input-sm ',
                              'title' => 'Apellido Paterno',)
                            )
                          );
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" id="div_estado_civil" style="display: 1">
                    <div class="row">
                      <div class="col-md-4">
                        <label for="estado_civil" class="control-label">
                          <b class="rojo">*</b>
                          Estado civíl                            </label>
                        </div>
                        <div class="col-md-8">
                          <div class="input-group">
                            <span class="input-group-addon">
                              <span class="fa fa-handshake-o"> </span>
                            </span>
                            <?php echo $this->form_complete->create_element(
                              array('id' => 'estado_:civil',
                              'type' => 'textbox',
                              'value'=> $docente['id_estado_civil'],
                              'attributes' => array(
                                'class' => 'form-control  form-control input-sm ',
                                'title' => 'Apellido Paterno',)
                              )
                            );
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><br><div class="row">
                    <div class="col-md-6" id="div_correo" style="display: 1">
                      <div class="row">
                        <div class="col-md-4">
                          <label for="correo" class="control-label">
                            <b class="rojo">*</b>
                            Correo electrónico                            </label>
                          </div>
                          <div class="col-md-8">
                            <div class="input-group">
                              <span class="input-group-addon">
                                <span class="fa fa-at"> </span>
                              </span>
                              <?php echo $this->form_complete->create_element(
                                array('id' => 'email',
                                'type' => 'textbox',
                                'value'=> $docente['email'],
                                'attributes' => array(
                                  'class' => 'form-control  form-control input-sm ',
                                  'title' => 'Apellido Paterno',)
                                )
                              );
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6" id="div_tel_particular" style="display: 1">
                        <div class="row">
                          <div class="col-md-4">
                            <label for="tel_particular" class="control-label">
                              <b class="rojo">*</b>
                              Teléfono particular                            </label>
                            </div>
                            <div class="col-md-8">
                              <div class="input-group">
                                <span class="input-group-addon">
                                  <span class="fa fa-mobile"> </span>
                                </span>
                                <?php echo $this->form_complete->create_element(
                                  array('id' => 'telefono_particular',
                                  'type' => 'textbox',
                                  'value'=> $docente['telefono_particular'],
                                  'attributes' => array(
                                    'class' => 'form-control  form-control input-sm ',
                                    'title' => 'Apellido Paterno',)
                                  )
                                );
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div><br><div class="row">
                        <div class="col-md-6" style="display: 1">
                          <div class="row">
                            <div class="col-md-4">
                              <label for="tel_trabajo" class="control-label">
                                <b class="rojo">*</b>
                                Teléfono laboral                            </label>
                              </div>
                              <div class="col-md-8">
                                <div class="input-group">
                                  <span class="input-group-addon">
                                    <span class="fa fa-volume-control-phone"> </span>
                                  </span>
                                  <?php echo $this->form_complete->create_element(
                                    array('id' => 'telefono_laboral ',
                                    'type' => 'textbox',
                                    'value'=> $docente['telefono_laboral'],
                                    'attributes' => array(
                                      'class' => 'form-control  form-control input-sm ',
                                      'title' => 'Apellido Paterno',)
                                    )
                                  );
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6" id="div_empleos_actuales" style="display: 1">
                            <div class="row">
                              <div class="col-md-4">
                                <label for="empleos_actuales" class="control-label">
                                  <b class="rojo">*</b>
                                  No de empleos actuales fuera del IMSS
                                </label>
                              </div>
                              <div class="col-md-8">
                                <div class="input-group">
                                  <span class="input-group-addon">
                                    <span class="fa fa-spinner"> </span>
                                  </span>
                                  <?php echo $this->form_complete->create_element(
                                    array('id' => 'num_empleos_fuera',
                                    'type' => 'textbox',
                                    'value'=> $docente['num_empleos_fuera'],
                                    'attributes' => array(
                                      'class' => 'form-control  form-control input-sm ',
                                      'title' => 'Apellido Paterno',)
                                    )
                                  );
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="list-group-item text-center">
                          <!--echo '<br>';-->
                          <div class="row">
                            <!--</div>-->

                            <div class="col-xs-6 col-sm-6 col-md-6 text-right">

                              <button name="cancel" type="button" id="guardar" class="btn btn-primary
                              btn" placeholder="" data-toggle="" data-placement="top" title="Cancelar" onclick="ocultar_vista_formulario(this);">
                              Cancelar</button>                    </div>

                              <div class="col-xs-6 col-sm-6 col-md-6 text-left ">
                                <input type="hidden" name="ruta_controller" value="/info_gral/datos_actividad_actualiza" id="ruta_controller">


                                <button name="guardar_actividad" type="button" id="guardar_actividad" value="Guardar" class="btn btn-primary  btn" placeholder="" data-toggle="" data-placement="top" title="Guardar" data-updatetabla="/info_gral/actualiza_tabla" data-formularioid="form_actividad_50" onclick="funcion_guardar_actividad(this);">
                                  Guardar</button>                    </div>
                                </div>
                              </div>
                            </form>


                          </div>
                        </div>
                      </div>
