<?php
echo js('secciones/editar_elemento.js');
//pr($secciones);
?>
<div id='editar_elemento_seccion'>

  <div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Editar</h3>
        </div>
        <div class="panel-body"><br>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <button onclick="regresar()" class="btn btn-primary">Regresar</button>
            </div>
          </div> <!--row-->
          <div class="row">
            <div id="form_mensaje"></div>
          </div>

          <div class="row">
            <?php
            echo form_open('secciones/editar_elemento_seccion', array('id'=>'form_editar_elemento_seccion'));
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="row" style="margin:5px;">
                <div class="row">
                  <div id="id-input">
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_elemento_seccion',
                        'name' => 'id_elemento_seccion',
                        'type' => 'hidden',
                        'value' => $elemento_seccion['id_elemento_seccion']
                        )
                      );
                    ?>
                  </div>
                </div>
                <table class="table table-container-fluid panel">
                  <tr>
                    <td>Nombre*:</td>
                    <td>
                      <?php
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'nombre',
                          'type'=>'text',
                          'value'=>$elemento_seccion['nombre'],
                          'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '512'
                            )
                          )
                        );
                      echo form_error_format('nombre');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Seccion*:</td>
                    <td>
                      <?php
                      $id_seccion = $elemento_seccion['id_seccion'];
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'id_seccion',
                          'name'=>'id_seccion',
                          'type'=>'dropdown',
                          'options'=>$secciones,
                          'first' => array( $id_seccion => $secciones[$id_seccion]),
                          'attributes' => array(
                            'class'=>'form-control'
                            )
                          )
                        );
                      echo form_error_format('id_seccion');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Subsección padre:</td>
                    <td>
                      <?php
                      $first_sub = array(''=>'Seleccione una opción');
                      $subsec = $elemento_seccion['id_catalogo_elemento_padre'];
                      if($subsec !=''){
                        $first_sub = array( $subsec => $subsecciones[$subsec]);
                      }
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'id_catalogo_elemento_padre',
                          'name'=>'id_catalogo_elemento_padre',
                          'type'=>'dropdown',
                          'options'=>$subsecciones,
                          'first' => $first_sub,
                          'attributes' => array(
                            'class'=>'form-control'
                            )
                          )
                        );
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Etiqueta*:</td>
                    <td>
                      <?php
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'label',
                          'type'=>'text',
                          'value'=>$elemento_seccion['label'],
                          'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '50'
                            )
                          )
                        );
                      echo form_error_format('label');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Descripcion:</td>
                    <td>
                      <?php
                      $value = $elemento_seccion['descripcion'];
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'descripcion',
                          'type'=>'textarea',
                          'value'=> $value,
                          'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '512'
                            )
                          )
                        );
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Activo*:</td>
                    <td>
                      <?php
                      $no = array(
                        0 => "no",
                        1 =>"si"
                        );
                      $si = array(
                        1 =>"si",
                        0 => "no"
                        );
                      $activo = ($elemento_seccion['activo'])?$si:$no;
                      echo $this->form_complete->create_element(
                        array(
                          'id'=>'activo',
                          'type'=>'dropdown',
                          'options'=> $activo
                          )
                        );
                      echo form_error_format('activo');
                      ?>
                    </td>
                  </tr>
                </table>
                <div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-3 col-sm-3">
                  <center>
                    <input type="submit" class="ui-input-button" value="Guardar">
                  </center>
                </div>
                <?php
                echo form_close();
                ?>
                <div class="col-lg-3 col-md-3 col-sm-3">
                  <center>
                    <button onclick="regresar()" class="ui-input-button">Cancelar</button>
                  </center>
                </div>
              </div><!--row-->
            </div><!--col-->
          </div><!--row-->
        </div><!-- panel body-->
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
<?php if(!is_null($id_seccion)){?>
    $('#id_seccion').val(<?php echo $id_seccion;?>);
    $(function(){
        var parametros = {id_catalogo_elemento_padre:<?php echo $id_seccion;?>};
        $( "#id_seccion" ).trigger( "change", [parametros] );
    });
<?php } ?>
</script>
