<div id="page-wrapper">
  <div id="main-content">
    <div id="page-inner">
      <div class="col-sm-12">
        <h1 class="page-head-line">Solicitud de evaluación curricular docente</h1>
      </div>
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-11">
        <!-- <p style="text-align: justify;"><strong>IMPORTANTE:</strong> La solicitud debe ser presentada conforme a  lo estipulado en esta Solicitud, en el órden referido y con las constancias señaladas, ello facilita la correcta evalucación.
        <br>Anexar únicamente la hoja a imprimir como carátula y fotocopias legibles de los documentos probatorios de todo lo declarado.</p>
        <br><br> -->
        </div>

        <div class="wizard col-sm-12">
          <div class="wizard-inner">
            <div class="connecting-line"></div>
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" >
                  <span class="round-tab">
                    <h5>Criterios para calificar en la carrera docente del IMSS</h5>
                    <!-- <i class="glyphicon glyphicon-folder-open">Evaluación de carrera docente institucional</i> -->
                  </span>
                </a>
              </li>

              <li role="presentation" class="disabled">
                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" >
                  <span class="round-tab">
                    <h5>Solicitud de evaluación curricular docente</h5>

                    <!-- <i class="glyphicon glyphicon-pencil"></i> -->
                  </span>
                </a>
              </li>

              <li role="presentation" class="disabled">
                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="">
                  <span class="round-tab">
                    <i class="glyphicon glyphicon-ok"></i>
                  </span>
                </a>
              </li>
            </ul>
          </div>

          <form role="form">
            <div class="tab-content">

              <div class="tab-pane active" role="tabpanel" id="step1">
                <br>
                <center><?php echo img('criterio1.png'); ?></center>
                <hr>
                <p>Si desea <strong>actualizar la información del censo</strong> por favor dé clic en el botón "Actualizar"</p>
                <ul class="list-inline pull-right">
                  <li><a href="<?php echo site_url('/ev_curricular/');?>" role="button" class="btn btn-default">Cancelar</a>
                  <li><a href="#" role="button" class="btn btn-info">Actualizar</a>
                  <li><button type="button" class="btn btn-primary next-step">Siguiente</button></li>
                </ul>
              </div>

              <div class="tab-pane" role="tabpanel" id="step2">
                <div class="col-sm-12">
                  <br>
                  <form role="form" class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-4">
                        <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/dianna.jpg" height="120px" widht="120px"/>
                        <p class="help-block"> Para cambiar tu foto da clic <a href="#">aquí</a>
                      </div>
                    </div>
                    <div class="row">
                      <br>
                      <div class="form-group col-sm-3">
                        <label for="paterno">Apellido Paterno</label>
                        <input type="text" class="form-control" id="paterno" value="Angeles" placeholder="Angeles">
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="materno">Apellido Materno</label>
                        <input type="text" class="form-control" id="materno" value="Gualito" placeholder="Gualito">
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="nombre">Nombre (s)</label>
                        <input type="text" class="form-control" id="nombre" value="Dianna Patricia" placeholder="Dianna Patricia">
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="edad">Edad</label>
                        <input type="number" class="form-control" id="edad" value="25" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-3">
                        <label for="adscripcion">Adscripción actual en el IMSS</label>
                        <input type="text" class="form-control" id="adscripcion" value="División de Innovación Educativa" readonly>
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="coordinacion">Unidad/Centro/Coordinación Normativa</label>
                        <input type="text" class="form-control" id="coordinacion" value="Coordinación de Educación en Salud" readonly>
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="clave_delegacion">Clave númerica de la Delegación</label>
                        <input type="text" class="form-control" id="clave_delegacion" value="55555555" readonly>
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="delegacion">Nombre de la Delegación</label>
                        <input type="text" class="form-control" id="delegacion" value="Oficinas Centrales" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-3">
                        <label for="numero_solicitud">Solicitud curricular docente</label>
                        <input type="text" class="form-control" id="numero_solicitud" value="Primera vez" readonly>
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="fecha_dictamen">Fecha del último dictamen</label>
                        <input type="date" class="form-control" id="fecha_dictamen" value="2018-05-15" readonly>
                      </div>
                      <div class="form-group col-sm-3">
                        <label for="puntos">Puntos obtenidos en el dictamen</label>
                        <input type="text" class="form-control" id="puntos" placeholder="20" value="20" readonly>
                      </div><div class="form-group col-sm-3">
                        <label for="etapa">Etapa</label>
                        <input type="text" class="form-control" id="etapa" value="Innovación" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <br>
                      <div class="form-group col-sm-6">
                        <label for="email">Anote un correo electrónico en el cual en caso de ser necesario se le haran llegar copias digitales del dictamen y/o de la constancia de Profesor de Carrera</label>
                        <input type="email"  class="form-control" id="email" placeholder="Correo electrónico">
                      </div>
                    </div>
                  </form>
                </div>

                <ul class="list-inline pull-right">
                  <li><button type="button" class="btn btn-default prev-step">Anterior</button></li>
                  <li><button type="button" class="btn btn-primary btn-info-full next-step">Guardar y continuar</button></li>
                </ul>
              </div> <!--panel 2-->
              <div class="tab-pane" role="tabpanel" id="complete">
                <h3>Completo</h3>
                <p>Se ha enviado correctamente la solicitud de evaluación curricular con los datos ingresados.</p>
              </div>
              <div class="clearfix"></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function () {
  //Initialize tooltips
  $('.nav-tabs > li a[title]').tooltip();

  //Wizard
  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

    var $target = $(e.target);

    if ($target.parent().hasClass('disabled')) {
      return false;
    }
  });

  $(".next-step").click(function (e) {

    var $active = $('.wizard .nav-tabs li.active');
    $active.next().removeClass('disabled');
    nextTab($active);

  });

  $(".prev-step").click(function (e) {

    var $active = $('.wizard .nav-tabs li.active');
    prevTab($active);

  });
});

function nextTab(elem) {
  $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
  $(elem).prev().find('a[data-toggle="tab"]').click();
}

</script>

