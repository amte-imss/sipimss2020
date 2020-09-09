<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<?php
$fi = transform_date($linea_tiempo['fechas_inicio']);
$ff = transform_date($linea_tiempo['fechas_fin']);
$fi = $fi[0];
$ff = $ff[count($ff)-1];
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js("rama_organica/rama_organica.js");
// pr($participantes);
?>

<script type="text/javascript">
    var unidades_participantes = <?php echo json_encode($participantes['unidades']); ?>;
</script>
<div id="page-inner">
    <div class="col-sm-12">
        <h1 class="page-head-line">
            Unidades/UMAE participantes
        </h1>
    </div>

    <div class="col-md-12">
        <a href="<?php echo site_url('workflow/index/' . $linea_tiempo['id_linea_tiempo']); ?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $exportarDocentes;?>" style="float:right;"><button class="btn btn-tpl" >Exportar Docentes</button></a>
            <p>Unidades/UMAES participantes</p>
            <div id="sede1"></div>

            <br><br>
            <h3>Unidades seleccionadas</h3>
            <div id="unidades_seleccionadas"></div>

            <br>
            <div class="col-sm-12 col-lg-12 col-md-12">
                <input type="hidden" id="fecha_inicio_0" value="<?php echo $fi; ?>">
                <input type="hidden" id="fecha_fin_2" value="<?php echo $ff; ?>">
                <?php echo form_open('/convocatoriaV2/get_participantes/' . $linea_tiempo['id_linea_tiempo'], array('id' => 'form_workflow_participantes')); ?>
                <a href="<?php echo site_url('workflow/index/'.$linea_tiempo['id_linea_tiempo']); ?>" class="btn btn-danger">Cancelar</a>
                <button id="workflow_boton_save" type="submit" class="btn btn-tpl">Guardar cambios</button>
                <?php echo form_close(); ?>
            </div>

            <script type="text/javascript">

                $(function () {
                    $('#sede1').localizador_sedes({
                        seleccion: 'checkbox',
                        agrupacion: true,
                        anio: 2017,
                        tipo_unidad: true,
                        nivel_atencion: 1,
                        columnas: ['region', 'delegacion', 'cve_unidad', 'nombre_unidad'],
                        funcion_auxiliar: 'agregar_unidad'
                    }, unidades_participantes);
                });

            </script>


            <br><br>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Convocatoria</h3>
                </div>
                <div class="panel-body">
                      <?php include 'cabecera.tpl.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo js("convocatoriav2/participantes.js"); ?>
