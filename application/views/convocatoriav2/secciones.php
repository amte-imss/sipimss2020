<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<script src="<?php echo base_url(); ?>assets/third-party/vakata-jstree/jstree.js"></script>
<link href="<?php echo base_url('assets/third-party/vakata-jstree/css/style.css'); ?>" rel="stylesheet" />
<?php
echo js('convocatoria/secciones.js');
?>
<div id="page-inner">
    <div class="col-sm-12">
        <h1 class="page-head-line">
            Edición de secciones de la convocatoria
        </h1>
        <div id="treeNTest_"></div>
    </div>

    <div class="col-md-12">      
        <a href="<?php echo site_url('workflow/index/' . $linea_tiempo['id_linea_tiempo']); ?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12">
                <br><br>
            </div>
            <br>            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">Secciones para N1</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php
                                    echo form_open('convocatoriaV2/get_secciones/' . $linea_tiempo['id_linea_tiempo'], array('id' => 'form_convocatoria_n1'));
                                    ?>
                                    <input type="hidden" name="tipo" value="N1">
                                    <input type="hidden" id="arbol" name="arbol">
                                    <div id="treeN1"></div>
                                    <br>
                                    <input type="submit" value="Guardar" class="btn btn-tpl º">
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse2">Secciones para N2</a>
                                </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php
                                    echo form_open('convocatoriaV2/get_secciones/' . $linea_tiempo['id_linea_tiempo'], array('id' => 'form_convocatoria_n2'));
                                    ?>
                                    <input type="hidden" name="tipo" value="N2">
                                    <input type="hidden" id="arbol2" name="arbol">
                                    <div id="treeN2"></div>
                                    <br>
                                    <input type="submit" value="Guardar" class="btn btn-tpl">
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<script type="text/javascript">
    var datos = {};
    datos = <?php echo json_encode($secciones['N1']); ?>;
    render_tree('treeN1', datos);
    datos = <?php echo json_encode($secciones['N2']); ?>;
    render_tree('treeN2', datos);
</script>
