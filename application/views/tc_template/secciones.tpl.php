<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($titulo_seccion);
//echo js('docente/formacion_docente/formacion_docente.js');
$controlador = '/' . $this->uri->rsegment(1);
?>


<?php echo css("jsgrid-1.5.3/jsgrid.min.css"); ?>
<?php echo css("jsgrid-1.5.3/jsgrid-theme.min.css"); ?>
<?php echo js("js_export_grid/jsgrid-1.5.3/jsgrid.js"); ?>

<?php echo js("js_export_grid/export/canvas-datagrid.js"); ?>
<?php echo js("js_export_grid/export/Blob.js"); ?>
<?php echo js("js_export_grid/export/FileSaver.js"); ?>
<?php echo js("js_export_grid/export/xlsx.full.min.js"); ?>
<?php echo js("js_export_grid/complemento_jsgrid.js"); ?>

<?php echo css('template_sipimss/campos_obligatorios.css'); //Asterisco en color rojito ?>

<style media="screen">
    label {
        display: block;
        text-align: right;
    }
</style>

<div class="col-md-12" id='div_error' style='display:none'>
    <div id='mensaje_error_div' class='alert alert-info' >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span id='mensaje_error'></span>
    </div>
</div>
<div id="main_content" class="">
    <!-- <div id="page-wrapper" class="page-wrapper-cls"> -->
    <div id="page-inner">
        <div  class="row">

            <div class="col-md-12">
                <?php if (!is_null($titulo_seccion)) {//Pinta sección ?>
                    <h1 class="page-head-line">
                        <?php echo $titulo_seccion; ?>
                    </h1>
                <?php } ?>
            </div>


            <div class="col-md-12">

                <?php if (!is_null($boton_agregar)) {//Pinta sección ?>
                    <div class="">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2 text-right">
                            <?php echo $boton_agregar; ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!is_null($ruta_accion_boton_agregar)) { //Ruta del js que controla formulario?>
                    <script src="<?php echo base_url($ruta_accion_boton_agregar); ?>"></script>
                <?php } ?>
                <div class="col-md-12 form-inline" role="form" id="seccion_seccion">
                    <?php if (!is_null($seccion)) {//Pinta sección ?>
                        <?php echo $seccion; ?>
                    <?php } ?>
                </div>
            </div>
            <br><br>

            <div class="col-md-12">
                <div class="col-md-12 form-inline" role="form" id="seccion_formulario">
                    <?php if (!is_null($formulario)) { //Pinta formulario?>
                        <?php echo $formulario; ?>
                    <?php } ?>
                </div>
                <br><br>
                <div id="catalogo_secciones_actividad_docente" class="col-md-12 form-inline">
                    <?php
                    if (isset($catalogo_secciones_actividad_docente)) {
                        ?>
                        <?php
                        echo $catalogo_secciones_actividad_docente;
                        ?>
                        <?php
                    }
                    ?>
                </div>    

                <div class="col-md-12 form-inline" id="js_grid_registros_seccion" data-ruta="<?php echo $controlador; ?>"></div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('.tip').each(function () {
        $(this).tooltip(
                {
                    html: true,
                    title: $('#' + $(this).data('tip')).html()
                });
    });
</script>

<?php echo js("docente/grid_secciones.js"); ?>