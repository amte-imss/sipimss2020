<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<?php
echo js("rama_organica/rama_organica.js");
echo js("convocatoriav2/nueva.js");
?>

<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo $fechas;
?>

<br>
<!-- <div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon">Porcentaje de muestra</span>
            <?php
            // echo $this->form_complete->create_element(
            //         array('id' => 'porcentaje',
            //             'type' => 'text',
            //             'attributes' => array(
            //                 'class' => 'form-control',
            //                 'data-toggle' => 'tooltip',
            //                 'title' => 'Porcentaje de muestra')
            //         )
            // );
            ?>
        </div>
    </div>
</div> -->
<div style="display:none;" id="div-error-unidades" class="alert alert-danger convocatoria-error" role="alert"></div>
<p>Unidades/UMAES participantes</p>
<div id="sede1"></div>

<div id="unidades_seleccionadas"></div>

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
        });
    });

</script>
