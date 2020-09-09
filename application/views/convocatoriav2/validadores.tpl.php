<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<div id="page-inner">
    <div class="col-sm-12">

    </div>
    <div class="col-md-12">
        <a href="<?php echo site_url('workflow/index/' . $linea_tiempo['id_linea_tiempo']); ?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?php echo $exportarValidadores;?>" style="float: right;"><button class="btn btn-tpl" >Exportar Validadores</button></a>
            <br><br>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse1">Unidades participantes</a>
                </div>
                <div id="collapse1" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="validadores_unidades"></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse2">UMAE participantes</a>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="validadores_umae"></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse3">Delegaciones participantes</a>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div id="validadores_delegaciones"></div>
                    </div>
                </div>
            </div>
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

<script  type="text/javascript">
    var unidades_participantes = <?php echo json_encode($participantes['unidades']['delegacional']); ?>;
    var umae_participantes = <?php echo json_encode($participantes['unidades']['umae']); ?>;
    var delegaciones_participantes = <?php echo json_encode($participantes['delegaciones']); ?>;
</script>

<?php
echo js('convocatoriav2/validadores.js');
?>
