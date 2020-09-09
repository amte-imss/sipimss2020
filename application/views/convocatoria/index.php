<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<?php
echo js('convocatoria/index.js');
$tipos = array('N' => 'Censo', 'ECD' => 'Evaluación curricular docente');
?>
<script src="<?php echo base_url(); ?>assets/third-party/moment-with-locales.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/vis-4.19.1/dist/vis.js"></script>
<link href="<?php echo base_url('assets/third-party/vis-4.19.1/dist/vis.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/checkbox.css'); ?>" rel="stylesheet" />


<style>
    .datepicker {z-index: 1151 !important;}
</style>
    <div id="page-inner">
            <div class="col-sm-12">
                <h1 class="page-head-line">
                  <br>
                   Convocatoria
                </h1>
              </div>
              <div class="col-sm-12">
                <div class="col-sm-9">
                </div>
                <div class="col col-sm-2">
                  <button onclick="convocatoria_nueva()" class="btn btn-tpl" data-toggle="modal" data-target="#my_modal">Nueva convocatoria</button>
                  <br><br>
                </div>
              </div>


  <div class="" role="form" id="informacion_general">

    <form class="form-horizontal" id="form_datos_generales" method="post" accept-charset="utf-8">

        <br>

        <div class="row">
          <div class="col-md-1">
          </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="paterno" class="righthoralign control-label">
                            <b class="rojo">*</b>
                          Tipo de convocatoria: </label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-male"> </span>
                            </span>
                            <select class="form-control input-sm" onchange="tipo_convocatoria(this)">
                                <option value="">Seleccionar</option>
                                <option value="1">Censo</option>
                                <option value="2">Evaluación curricular docente</option>
                            </select>
                        </div>
                    </div>
                </div>
             </div>
            <div id="nivel_geografico" class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <label for="materno" class="control-label">
                            <b class="rojo">*</b>
                            Segmento:</label>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-female"> </span>
                            </span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'segmento',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $segmentos,
                                        'attributes' => array(
                                            'class' => 'form-control',
                                            'data-toggle' => 'tooltip',
                                            'title' => '¿A cual segmento va dirigida la convocatoria?')
                                    )
                            );
                            ?>
                        </div>
                    </div>
                    <div id="sipimss_entidad" class="col-md-5">
                        <div class="input-group">

                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'segmento',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione una opción......'),
                                        'attributes' => array(
                                            'class' => 'form-control',
                                            'data-toggle' => 'tooltip',
                                            'title' => '')
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
                     <div class="col-md-4" style="display: 1">
                         <div class="row">
                             <div class="col-md-3">
                                 <label for="materno" class="control-label">
                                     <b class="rojo">*</b>
                                     Año:</label>
                             </div>
                             <div class="col-md-8">
                                 <div class="input-group">
                                     <span class="input-group-addon">
                                         <span class="fa fa-female"> </span>
                                     </span>
                                     <select class="form-control input-sm">
                                         <option>Seleccionar</option>
                                         <option>2016</option>
                                         <option>2017</option>
                                     </select>
                                 </div>
                             </div>
                             <?php echo form_error_format('email'); ?>
                         </div>
                                 </div>



                                 <div class="">

                                 </div>

                                 <div id="regiones" class="col-md-6">
                                     <div class="row">
                                         <div id="sipimss_entidad" class="col-md-3">
                                             <label for="paterno" class="righthoralign control-label">
                                                 <b class="rojo">*</b>
                                              Algo: </label>
                                         </div>
                                         <div class="col-md-8">
                                             <div class="input-group">
                                                 <span class="input-group-addon">
                                                     <span class="fa fa-male"> </span>
                                                 </span>
                                                 <select class="form-control input-sm">
                                                     <option>Seleccionar</option>
                                                     <option>Activos</option>
                                                     <option>Inactivos</option>
                                                 </select>
                                             </div>
                                         </div>
                                     </div>
                                   </div>

                 </div>
                  <br>

                  <div class="row">
                        <div class="col-md-1">
                        </div>

                      </div>
                       <br>
                       <div class="row">
                           <div class="col col-sm-12">
                               <div id="timeline"></div>
                           </div>
                       </div>
                       <br>
                       <div class="row">
                           <div class="col col-sm-12">
                               <table class="table table-striped table-bordered table-hover dataTable">
                                   <thead>
                                       <tr>
                                           <th>Nombre</th>
                                           <th>Clave</th>
                                           <th>Tipo</th>
                                           <th>Fecha de inicio</th>
                                           <th>Fecha de fin</th>
                                           <th>Activa</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <?php
                                       foreach ($convocatorias as $row)
                                       {
                                           $fecha_inicial = transform_date($row['fechas_inicio'])[0];
                                           $fecha_inicial = date_format(date_create($fecha_inicial), 'd/m/Y');
                                           if ($row['id_tipo_convocatoria'] == 'N')
                                           {
                                               $fecha_final = transform_date($row['fechas_inicio'])[2];
                                               $fecha_final = date_format(date_create($fecha_final), 'd/m/Y');
                                           } else
                                           {
                                               $fecha_final = 'undefined';
                                           }
                                           ?>
                                           <tr>
                                               <td><a href="<?php echo site_url('convocatoria/get_convocatorias/' . $row['id_convocatoria']); ?>"><?php echo $row['nombre']; ?></a></td>
                                               <td><a href="<?php echo site_url('convocatoria/get_convocatorias/' . $row['id_convocatoria']); ?>"><?php echo $row['clave']; ?></a></td>
                                               <td><?php echo $tipos[$row['id_tipo_convocatoria']]; ?></td>
                                               <td><?php echo $fecha_inicial; ?></td>
                                               <td><?php echo $fecha_final; ?></td>
                                               <td><?php echo ($row['activa'] ? 'Si' : 'No'); ?></td>
                                           </tr>
                                           <?php
                                       }
                                       ?>
                                   </tbody>
                               </table>
                           </div>
                       </div>
          </div>
      </div>



<?php
$to_json = [];
//pr($convocatorias);
foreach ($convocatorias as $row)
{
    $fecha_inicial = transform_date($row['fechas_inicio'])[0];
    if ($row['id_tipo_convocatoria'] == 'N')
    {
        $fecha_final = transform_date($row['fechas_inicio'])[2];
    } else
    {
        $fecha_final = 'undefined';
    }
    $to_json[] = array(
        'id' => $row['id_convocatoria'],
        'content' => $row['clave'],
        'start' => $fecha_inicial,
        'end' => $fecha_final
    );
}
//pr($to_json);
?>

<script type="text/javascript">
    var datos = {};
    datos = <?php echo json_encode($to_json); ?>;
    render_timeline(datos);
</script>
