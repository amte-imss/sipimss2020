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
//pr();
?>
<script>
    var url_ctr = "<?php echo $controlador; ?>";
    var delegaciones =  <?php echo json_encode($catalogos['result_delegacional']); ?>;
    var fase_carrera_docente =  <?php echo json_encode($catalogos['fase_carrera_docente']); ?>;
    var estados_validacion =  <?php echo json_encode($catalogos['estados_validacion']); ?>;
    var ratificado =  <?php echo json_encode($catalogos['ratificado']); ?>;
    var bloquea_delegacion =  <?php echo $bloquea_delegacion; ?>;
    var editar_reg_doc =  <?php echo $editar_reg_doc_nuevamente; ?>;
    var permiso = "<?php echo $permiso; ?>";
</script>

<div id="main_content" class="">
    <div id="page-inner">
        <h2>Censo de docentes</h2>
        <?php if ($permiso == 1){ ?>
            <div class="col-md-12 col-sm-12">
                <a href="#" type="button" class="btn btn-theme animated flipInY visible pull-right" aria-expanded="false" onclick="exportar_lista_docentes(this);" data-namegrid="js_grid_lista_docentes">
                    <span>Exportar</span>
                </a>
                <?php if(isset($btn_activar_registro_docentes_masivo) && $btn_activar_registro_docentes_masivo==1){?>
                <a type="button" class="btn btn-theme animated flipInY visible pull-right" aria-expanded="false" onclick="habilita_edicion_general(this);" data-namegrid="js_grid_lista_docentes">
                    <span>Habilitar edición registro docente</span>
                </a>
                <?php }?>
            </div><br>
        <?php } ?>
        <p>Dado el cambio en el proceso e inicio de una nueva etapa, se ha modificado el número de registros a mostrar para los CCEIS y JDES, ahora solo dispondrá de los que ha registrado para que pueda validarlos.</p>
        <h5 id="docentes_registrados"></h5>
        <div id="js_grid_lista_docentes"><div>
    <div>
<div>
<?php echo js("validador/lista_docentes.js");?>