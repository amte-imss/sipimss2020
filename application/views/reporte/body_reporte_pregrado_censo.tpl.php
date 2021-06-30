<?php echo css("jsgrid-1.5.3/jsgrid.min.css"); ?>
<?php echo css("jsgrid-1.5.3/jsgrid-theme.min.css"); ?>
<?php echo js("js_export_grid/jsgrid-1.5.3/jsgrid.js"); ?>

<?php echo js("js_export_grid/export/canvas-datagrid.js"); ?>
<?php echo js("js_export_grid/export/Blob.js"); ?>
<?php echo js("js_export_grid/export/FileSaver.js"); ?>
<?php echo js("js_export_grid/export/xlsx.full.min.js"); ?>
<?php echo js("js_export_grid/complemento_jsgrid.js"); ?>

<?php 
$controlador = '/' . $this->uri->rsegment(1);
//pr($catalogos['result_delegacional']);
//pr($cambiar_validador1);
//
?>
<script>
  
    var url_ctr = "<?php echo $controlador; ?>";
    //var cambiar_validadorN1 =  "<?php //echo $cambiar_validador1; ?>";
    var delegaciones =  <?php echo json_encode($catalogos['result_delegacional']); ?>;
    var estados_validacion =  <?php echo json_encode($catalogos['estados_validacion']); ?>;
    //var bloquea_delegacion =  <?php //echo $bloquea_delegacion; ?>;
    //var nivel_acceso =  <?php //echo json_encode($catalogos['nivel_acceso']); ?>;
    
    //console.log(delegaciones);
    //console.log(nivel_acceso);
</script>

<div id="main_content" class="">
    <div id="page-inner">
        <h2><?php echo $title; ?></h2>
        <div class="col-md-12 col-sm-12">
                <a href="#" type="button" class="btn btn-theme animated flipInY visible pull-right" aria-expanded="false" onclick="exportar_reporte(this);" data-namegrid="js_grid_lista_censo_docentes">
                    <span><?php echo $exportar_title; ?></span>
                </a>                
            </div><br>
        <h5 id="docentes_registrados"></h5>
        <div id="js_grid_lista_censo_docentes"><div>
    <div>
<div>
<?php echo js("validador/lista_censo_docentes_pregrado.js");?>