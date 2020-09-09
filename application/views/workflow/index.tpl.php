<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('workflow/index.js');
?>

<script src="<?php echo base_url(); ?>assets/third-party/moment-with-locales.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/vis-4.19.1/dist/vis.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<link href="<?php echo base_url('assets/third-party/vis-4.19.1/dist/vis.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<style>
    .datepicker {z-index: 1151 !important;}
</style>

<div id="page-inner">
    <div class="col-sm-12">
        <h1 class="page-head-line">
            <br>
            Linea de tiempo

        </h1>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-8">

        </div>

        <div class="col col-sm-4">
            <a href="<?php echo site_url('workflow/nueva'); ?>"><button class="btn btn-tpl" >Nueva</button></a>
            <br>
            <br>
        </div>

    </div>


    <div class="" role="form" id="informacion_general">
        <div class="row">
            <div class="col col-sm-12">
                <div id="timeline"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col col-sm-12">
                <?php include('grid.tpl.php'); ?>
            </div>
        </div>
    </div>
</div>

<?php
$to_json = [];
//pr($convocatorias);
foreach ($lineas_tiempo as $row)
{
    $fecha_inicial = transform_date($row['fechas_inicio'])[0];
    $fecha_final = transform_date($row['fechas_fin']);
    $fecha_final = $fecha_final[count($fecha_final) - 1];
    $to_json[] = array(
        'id' => $row['id_linea_tiempo'],
        'content' => $row['clave'],
        'start' => $fecha_inicial,
        'end' => $fecha_final,
        'group' => $row['id_workflow']
    );
}
//pr($to_json);
?>

<script type="text/javascript">
    var datos = {};
    datos = <?php echo json_encode($to_json); ?>;
    var grupos = {};
    grupos = <?php echo json_encode($tipos_lineas_tiempo); ?>;
    render_timeline(datos, grupos);

    function docentes(){
      console.log("Docentes");
    }

    function validadores(){
      console.log("Validadores");
    }
</script>
