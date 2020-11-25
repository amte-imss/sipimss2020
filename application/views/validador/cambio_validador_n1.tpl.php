<?php 
$controlador = '/' . $this->uri->rsegment(1);
//pr($catalogos['result_delegacional']);
//pr($cambiar_validador1);
//
?>

<?php echo css("jsgrid-1.5.3/jsgrid.min.css"); ?>
<?php echo css("jsgrid-1.5.3/jsgrid-theme.min.css"); ?>
<?php echo js("js_export_grid/jsgrid-1.5.3/jsgrid.js"); ?>

<?php echo js("js_export_grid/export/canvas-datagrid.js"); ?>
<?php echo js("js_export_grid/export/Blob.js"); ?>
<?php echo js("js_export_grid/export/FileSaver.js"); ?>
<?php echo js("js_export_grid/export/xlsx.full.min.js"); ?>
<?php echo js("js_export_grid/complemento_jsgrid.js"); ?>

    <script>
  
    var url_ctr = "<?php echo $controlador; ?>";
    var validador =  "<?php echo $datos_docente_validador_actual['id_docente']; ?>";
    var delegaciones =  <?php echo json_encode($catalogos['result_delegacional']); ?>;
    </script>

<div id="main_content" class="">
    <div id="page-inner">
        <div class="col-md-12" id="div_error" style='display:none'>
        <div id="alerta"  class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <span id="msg"></span>
        </div>
        </div>
        <h2>Validador nivel 1: <?php echo $datos_docente_validador_actual['nombre_docente'] . ' (' . $datos_docente_validador_actual['matricula'] . ')'; ?></h2>
        <div class="text-right">
            <button class="btn btn-success" id="btn_guardar_cambio_val" style=" background-color:#008EAD"                 
                onclick="guarda_cambio_validador(this);"
            >
                Guardar<span class=""></span>
            </button>
        </div>
        <?php echo form_open('', array('id' => 'form_cambio_validador')); ?>
        <input id="validador_actual" type="hidden" name="validador_actual" value="<?php echo $datos_docente_validador_actual['id_usuario'];?>">
        <div><?php echo $listado_docentes; ?></div>
        <h4>Listado validadores nivel 1</h4>
        <div id="js_grid_lista_validadores"><div>
        <?php echo form_close()?>

 
    <div>
<div>
<?php echo js("validador/lista_validadores_n1.js");?>
