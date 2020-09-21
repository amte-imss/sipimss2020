<?php
echo js('secciones/nuevo_elemento.js');
//pr($secciones);
?>

<div id='nuevo_elemento_seccion'>

<div ng-class="panelClass" class="row">
  <div class="col col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Nuevo elemento de sección</h3>
      </div>
      <div class="panel-body"><br>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <button onclick="regresar()" class="btn btn-primary">Regresar</button>
          </div>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="form_mensaje"></div>
          </div>
        </div><!--row-->
        <div class="row">
        <?php
        echo form_open('secciones/nuevo_elemento_seccion', array('id'=>'form_nuevo_elemento_seccion'));
        ?>
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row" style="margin:5px;">
              <table class="table table-container-fluid panel">
                <tr>
                  <td>Nombre*:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'nombre',
                        'type'=>'text',
                        'value'=>(isset($datos['nombre']))? $datos['nombre'] : '',
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
                    $seccion = array();
                    //pr($secciones);
                    if(isset($datos['id_seccion']) && $datos['id_seccion']!=''){
                      $seccion = array($datos['id_seccion'] => $secciones[$datos['id_seccion']]);
                    }else{
                      $seccion = array( '' => 'Seleccione una sección');
                    }
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_seccion',
                        'name'=>'id_seccion',
                        'type'=>'dropdown',
                        'options'=> $secciones,
                        'first' => $seccion,
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
                    $opciones_subseccion = (isset($subsecciones))? $subsecciones : array();
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_catalogo_elemento_padre',
                        'name'=>'id_catalogo_elemento_padre',
                        'type'=>'dropdown',
                        'options'=> $opciones_subseccion,
                        'first' => array( '' => 'Seleccione una opción'),
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
                        'value'=>(isset($datos['label']))? $datos['label'] : '',
                        'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '100'
                          )
                        )
                      );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Descripcion:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'descripcion',
                        'type'=>'textarea',
                        'value'=> (isset($datos['descripcion']))? $datos['descripcion'] : '',
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
                  <td>Activo*</td>
                  <td>
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'activo',
                        'type'=>'dropdown',
                        'options'=> array(
                            1 =>"si",
                            0 => "no"
                          ),
                        'first' => array( '' => 'Seleccione una opción'),
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
                  <button onclick="regresar();" class="ui-input-button">Cancelar</button>
                </center>
              </div>
            </div><!-- row-->
          </div><!--col-->
        </div> <!--row-->
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
