<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo css('template_sipimss/campos_obligatorios.css'); //Asterisco en color rojito
echo js('docente/informacion_docente/informacion_docente.js');
?>

<div id="page-wrapper" class="">
  <div id="main-content">
    <div id="page-inner">
      <div class="row">

          <div class="col-md-12 col-sm-12 col-xs-12 " id='div_error' style='display:none'>
              <div id='mensaje_error_div' class='alert alert-info'>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <span id='mensaje_error'></span>
              </div>
          </div>
          <?php if (!is_null($titulo_seccion)) {//Pinta sección ?>
              <h1 class="page-head-line">
                  <?php echo $titulo_seccion; ?>
              </h1>
          <?php } ?>



          <br>
          <div class="form-inline" role="form" id="informacion_general">
              <?php
              if (isset($formulario_general)) {
                  echo $formulario_general;
              }
              ?>
          </div>
          <!--div class="panel panel-success">
            <div class="panel-heading">
               <h3 class="panel-title">Información IMSS</h3>
            </div>
            <div class="panel-body">
              <?php
              /*if (isset($formulario_imss)) {
                  echo $formulario_imss;
              }*/
              ?>
            </div>
          </div>

          <div class="col-md-12">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <br>
                  <div class="">
                    <?php
                    /*if (isset($imagen_docente)) {
                        echo $imagen_docente;
                    }*/
                    ?>
                  </div>
                  <div class="card-profile_user-infos">
                    <span class="infos_name"></span>
                    <a href="#"></a>
                  </div>
              </div>
              <div class="col-md-3"></div>
          </div-->
        </div>

    </div>
  </div>
</div>


<script>
</script>
