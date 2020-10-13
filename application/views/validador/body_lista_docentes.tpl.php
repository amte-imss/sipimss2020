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
?>
<script>
    var url_ctr = "<?php echo $controlador; ?>";
    var delegaciones =  <?php echo json_encode($catalogos['result_delegacional']); ?>;
    var fase_carrera_docente =  <?php echo json_encode($catalogos['fase_carrera_docente']); ?>;
</script>

<div id="main_content" class="">
    <div id="page-inner">
        <h2>Censo de docentes</h2>
        <h5 id="docentes_registrados"></h5>
        <div id="js_grid_lista_docentes"><div>
    <div>
<div>
<?php echo js("validador/lista_docentes.js");?>