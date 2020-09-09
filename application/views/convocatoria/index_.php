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
<style>
    .datepicker {z-index: 1151 !important;}
</style>
<div>
    <div id="page-inner">
        <div class="row">
            <div class="row">
              <div class="col col-sm-12">
                <h1 class="page-head-line">
                  <br>
                   Convocatoria

                </h1>
              </div>
              <div class="col-sm-9">

              </div>

                <div class="col col-sm-3">
                    <button onclick="convocatoria_nueva()" class="btn btn-primary" data-toggle="modal" data-target="#my_modal">Nueva convocatoria</button>
                    <br>
                    <br>
                </div>

            <div class="row">
                <div class="form-group">
                  <br>
                  <br>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Tipo de convocatoria:</span>
                            <select class="form-control" onchange="tipo_convocatoria(this)">
                                <option value="">Seleccionar</option>
                                <option value="1">Censo</option>
                                <option value="2">Evaluación curricular docente</option>
                            </select>
                        </div>
                    </div>
                    <div id="nivel_geografico" class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Segmento</span>
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
                    <div id="regiones" class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span id="sipimss_entidad" class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'segmento',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
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
            <br />
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon">Año:</span>
                        <select class="form-control">
                            <option>Seleccionar</option>
                            <option>2016</option>
                            <option>2017</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"></span>
                        <select class="form-control">
                            <option>Seleccionar</option>
                            <option>Activos</option>
                            <option>Inactivos</option>
                        </select>
                    </div>
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
                    <table class="table table-striped">
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
