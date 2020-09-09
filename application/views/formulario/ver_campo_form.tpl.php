<div ng-class="panelClass" class="row">
  <div class="col col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Datos del campo</h3>
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
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="row" style="margin:5px;">
          </div>
        </div>
      </div><!--row-->

      <table class="table table-container-fluid panel">
        <tr>
          <td>Id</td>
          <td><?php echo $id_campos_formulario; ?></td>
        </tr>
        <tr>
          <td>Id Formulario</td>
          <td><?php echo $datos['id_formulario']; ?></td>
        </tr>
        <tr>
          <td>Campo</td>
          <td><?php echo $campos_lista[$datos['id_campo']]; ?></td>
        </tr>
        <tr>
          <td>Tooltip</td>
          <td><?php echo $datos['tooltip']; ?></td>
        </tr>
        <tr>
          <td>Rules</td>
          <td><?php echo $datos['rules']; ?></td>
        </tr>
        <tr>
          <td>Callback</td>
          <td>
          <?php //echo $callback_lista[$datos['id_callback']]; 
            if($datos['id_callback']!='')
              echo $callback_lista[$datos['id_callback']];
          ?>
          </td>
        </tr>
        <tr>
          <td>Campos dependientes</td>
          <td><?php echo $datos['campos_dependientes']; ?></td>
        </tr>
        <tr>
          <td>Catálogo</td>
          <td>
          <?php //echo $callback_lista[$datos['id_callback']]; 
            if(!is_null($datos['id_catalogo']))
              echo $catalogo_lista[$datos['id_catalogo']];
          ?>
          </td>
        </tr>
        <tr>
          <td>Regla para catálogo</td>
          <td>
          <?php //echo $callback_lista[$datos['id_callback']]; 
            if(!is_null($datos['reglas_catalogos']))
              echo $reglas_catalogo_lista[$datos['reglas_catalogos']];
          ?>
          </td>
        </tr>
        <tr>
          <td>Opciones para excepción</td>
          <td>
            <?php
            if(isset($excepciones_opciones)){
            echo implode(',',$excepciones_opciones);
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>Reglas de notificaciones</td>
          <td>
            <?php echo $datos['regla_notificacion']; ?>
          </td>
        </tr>
        <tr>
          <td>Activo</td>
          <td>
            <?php echo ($datos['activo'])? "si" : "no"; ?>
          </td>
        </tr>
        <tr>
          <td>Orden</td>
          <td>
            <?php echo $datos['orden']; ?>
          </td>   
        </tr>
        <tr>
          <td>Placeholder:</td>
          <td>
            <?php echo $datos['placeholder']; ?>
          </td>
        </tr>
        <tr>
          <td>Display</td>
          <td>
            <?php echo ($datos['display'])? "si" : "no"; ?>
          </td>
        </tr>
        <tr>
          <td>CSS:</td>
          <td>
            <?php echo $datos['css']; ?>
          </td>
        </tr>
        <tr>
          <td>Atributos extras</td>
          <td>
            <?php echo $datos['attributes_extra']; ?>
          </td>
        </tr>
        <tr>
          <td>Nueva línea</td>
          <td>
            <?php echo ($datos['nueva_linea'])? "si" : "no"; ?>
          </td>
        </tr>
        <tr>
          <td>Mostrar datatable:</td>
          <td>
          <?php echo ($datos['mostrar_datatable'])? "si" : "no"; ?>
          </td>
        </tr>
        <tr>
          <td>Precargar:</td>
          <td>
          <?php echo ($datos['is_precarga'])? "si" : "no"; ?>
          </td>
        </tr>
      </table>

      </div> <!-- panel body -->
    </div>
  </div>
</div>

<script>
function regresar() {
  var id_form = <?php echo $datos['id_formulario'];?>;
  window.location = site_url + '/formulario/campos_formulario/' + id_form;
}
</script>