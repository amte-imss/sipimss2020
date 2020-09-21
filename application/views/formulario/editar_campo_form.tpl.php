<?php

$styles_items = array(
    'form_campos_reglas_excepcion'=> 'style="display:none"',
    'form_campos_opciones_excepcion'=> 'style="display:none"',
    'form_regla_catalogo_dependiente' => 'style="display:none"',
    'form_campos_dependientes' =>'style="display:none"'
);

if($datos['id_catalogo'] != '')
{
    $styles_items['form_campos_dependientes'] = '';
    $styles_items['form_campos_reglas_excepcion'] = '';
    switch($datos['reglas_catalogos']){
        case '1':
        case '2':
            $styles_items['form_campos_opciones_excepcion'] = '';
            break;
        case '3':
            $styles_items['form_regla_catalogo_dependiente'] = '';
        break;
        default:
            break;
    }
}
?>

<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>

<?php
echo js('formularios/editar_campo_form.js');
?>

<div id='editar_campo_formulario'>

<div ng-class="panelClass" class="row">
  <div class="col col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Editar campo</h3>
      </div>

      <div class="panel-body"><br>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row" style="margin:5px;">
              <button onclick="regresar()" class="btn btn-primary">Regresar</button>
            </div>
          </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div id="form_mensaje"></div>
        </div>
      </div> <!-- row -->

        <div class="row">
        <?php
        echo form_open('formulario/editar_campo_formulario', array('id'=>'form_editar_campo_formulario'));
        ?>
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row" style="margin:5px;">
              <div class="row">
                <div id="id-input">
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_campos_formulario',
                        'name' => 'id_campos_formulario',
                        'type' => 'hidden',
                        'value' => $id_campos_formulario
                      )
                    );
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_formulario',
                        'name' => 'id_formulario',
                        'type' => 'hidden',
                        'value' => $datos['id_formulario']
                      )
                    );
                  ?>
                </div>
              </div>
              <table class="table table-container-fluid panel">
                <tr>
                  <td>Campo*:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_campo',
                        'name'=>'id_campo',
                        'type'=>'dropdown',
                        'options'=>$campos_lista,
                        'value' => $datos['id_campo'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'required' => true
                          )
                        )
                      );
                    echo form_error_format('id_campo');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Label personalizado:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'label_personalizado',
                        'type'=>'text',
                        'value' => (isset($datos['label_personalizado']))? $datos['label_personalizado'] : '',
                        'attributes' => array(
                            'class'=>'form-control',
                          )
                        )
                      );
                    echo form_error_format('label_personalizado');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Tooltip:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'tooltip',
                        'type'=>'textarea',
                        'value'=>$datos['tooltip'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '512',
                            'rows' => '4'
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Rules:</td>
                  <td>
                    <?php
                    //pr($datos['rules']);
                    $value = (isset($datos['rules'])) ? $datos['rules'] : array('' => 'Ninguno');
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'rules',
                        'name'=>'rules',
                        'type'=>'multiselect',
                        'first'=> array('' => 'Ninguno'),
                        'options'=> $rules_lista,
                        'value'=> $value,
                        'attributes'=> array(
                            'class'=>'mdb-select colorful-select dropdown-primary md-form',
                            'searchable'=>'Buscar aquí...'
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Callback:</td>
                  <td>
                    <?php
                    // pr($callback_lista);                    
                    $callback = $datos['id_callback'];
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_callback',
                        'name'=>'id_callback',
                        'type'=>'dropdown',
                        'first' => array( '' => 'Seleccione una opción'),
                        'options'=> $callback_lista,
                        'value' => $callback,
                        'attributes' => array(
                            'class'=>'form-control'
                          )
                        )
                      );
                    echo form_error_format('id_callback');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Catálogo:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'id_catalogo',
                        'name'=>'id_catalogo',
                        'type'=>'dropdown',
                        'options'=>$catalogo_lista,
                        'first' => array( '' => 'Seleccione una opción'),
                        'value' => $datos['id_catalogo'],
                        'attributes' => array(
                            'class'=>'form-control'
                          )
                        )
                      );
                    ?>
                  </td>
                </tr>
                 <tr id="form_campos_reglas_excepcion"  class="form_item_catalogo_handler" <?php echo $styles_items['form_campos_reglas_excepcion']; ?>>
                  <td>Reglas de excepción para catálogos:</td>
                  <td>
                    <?php
                    $value = array();
                    if(isset($datos['reglas_catalogos'])){
                      $value = $datos['reglas_catalogos'];
                    }
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'reglas_catalogos',
                        'name'=>'reglas_catalogos',
                        'type'=>'dropdown',
                        'options'=>$reglas_catalogo_lista,
                        'first' => array( '' => 'Seleccione una opción'),
                        'value' => $value,
                        'attributes' => array(
                            'class'=>'form-control'
                          )
                        )
                      );
                    ?>
                  </td>
                </tr>
                <tr  id="form_campos_opciones_excepcion" class="form_item_catalogo_handler" <?php echo $styles_items['form_campos_opciones_excepcion']; ?>>
                  <td>Opciones para excepción:</td>
                  <td>
                    <?php
                    $opciones = array();
                    $value = array();
                    if(isset($excepciones_opciones_lista)){
                      $opciones = $excepciones_opciones_lista;
                    }
                    if(isset($datos['excepciones_opciones'])){
                      $exc_op = $datos['excepciones_opciones'];
                      if(gettype($exc_op) == 'string'){
                        $value = explode(',',$datos['excepciones_opciones']);
                      }else{
                        $value = $exc_op;
                      }
                    }

                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'excepciones_opciones',
                        'name'=>'excepciones_opciones',
                        'type'=>'multiselect',
                        'options'=> $opciones,
                        'value' => $value,
                        'attributes'=> array(
                            'class'=>'form-control'
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr id="form_regla_catalogo_dependiente" class="form_item_catalogo_handler" <?php echo $styles_items['form_regla_catalogo_dependiente']; ?>>
                    <td>Regla de dependencia de catálogos:</td>
                    <td><?php include 'view_reglas_dependencias.tpl.php'; ?></td>
                </tr>
                <tr id="form_campos_dependientes" class="form_item_catalogo_handler" <?php echo $styles_items['form_campos_dependientes']; ?>>
                  <td>Campos dependientes:</td>
                  <td>
                    <?php
                    include 'campos_dependientes.tpl.php';
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Reglas de notificaciones:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'regla_notificacion',
                        'type'=>'text',
                        'value'=> $datos['regla_notificacion'],
                        'attributes' => array(
                            'class'=>'form-control'
                          )
                        )
                      );
                    echo form_error_format('regla_notificacion');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Activo*:</td>
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
                        'value'=> $datos['activo'],
                        'attributes' => array(
                            'required' => true
                        )
                      )
                    );
                  echo form_error_format('activo');
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>Orden*:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'orden',
                        'name'=>'orden',
                        'type'=>'number',
                        'value' => $datos['orden'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'min'=>0,
                            'required' => true
                          )
                        )
                      );
                    echo form_error_format('orden');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Placeholder:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'placeholder',
                        'type'=>'text',
                        'value'=>$datos['placeholder'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'maxlength' => '512',
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Display*:</td>
                  <td>
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'display',
                        'type'=>'dropdown',
                        'value'=> $datos['display'],
                        'options'=> array(
                            1 =>"si",
                            0 => "no"
                        ),
                        'attributes' => array(
                            'required' => true
                        )
                      )
                    );
                  echo form_error_format('display');
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>CSS:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'css',
                        'type'=>'textarea',
                        'value'=> $datos['css'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'rows' => '4'
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Atributos extras:</td>
                  <td>
                    <?php
                    echo $this->form_complete->create_element(
                      array(
                        'id'=>'attributes_extra',
                        'type'=>'textarea',
                        'value'=>$datos['attributes_extra'],
                        'attributes' => array(
                            'class'=>'form-control',
                            'rows' => '4'
                          )
                      )
                    );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Nueva línea*:</td>
                  <td>
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'nueva_linea',
                        'type'=>'dropdown',
                        'value' => $datos['nueva_linea'],
                        'options'=> array(
                            1 =>"si",
                            0 => "no"
                        ),
                        'attributes' => array(
                            'required' => true
                        )
                      )
                    );
                  echo form_error_format('nueva_linea');
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>Mostrar datatable:</td>
                  <td>
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'mostrar_datatable',
                        'type'=>'dropdown',
                        'value'=>$datos['mostrar_datatable'],
                        'options'=> array(
                            1 =>"si",
                            0 => "no"
                          ),
                        'first' => array( '' => 'Seleccione una opción'),
                      )
                    );
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>Precarga:</td>
                  <td>
                  <?php
                  echo $this->form_complete->create_element(
                      array(
                        'id'=>'is_precarga',
                        'type'=>'dropdown',
                        'value'=>$datos['is_precarga'],
                        'options'=> array(
                            1 =>"si",
                            0 => "no"
                          ),
                        'first' => array( '' => 'Seleccione una opción'),
                      )
                    );
                  ?>
                  </td>
                </tr>
              </table>
            </div>
          </div> <!--columnas-->
          <br>
          <div class="row">
          <div class="col-lg-offset-3 col-lg-3 col-md-offset-3 col-md-3 col-sm-offset-3 col-sm-3">
            <center>
              <input type="submit" class="ui-input-button" value="Guardar">
            </center>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3">
            <center>
              <button onclick="regresar()" class="ui-input-button">Cancelar</button>
            </center>
          </div>
          </div>
        <?php
        echo form_close();
        ?>
        </div> <!-- row -->

      </div> <!-- panel body -->
    </div>
  </div>
</div>

</div>

<div class="modal fade" id="modal_campos_dependientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Administración de dependencias de campos y elementos de los catálogos</h4>
      </div>
      <div class="modal-body">

        <div class="" id="grid_campos_dependientes"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardar_dependencias_catalogos()">Guardar cambios</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php
if(isset($datos['campos_dependientes'])  && $datos['campos_dependientes'] != '')
{
?>
<script type="text/javascript">
    var json_campos_dependientes = <?php echo $datos['campos_dependientes']; ?>;
</script>
<?php
}else{
?>
    <script type="text/javascript">
    var json_campos_dependientes = {campos:[], elementos:{}};
    </script>
<?php
}
?>
