<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
echo js('workflow/linea_tiempo.js');
//pr($workflow);
?>
<script type="text/javascript">
    var workflow_url_editar = '<?php echo $workflow['controlador_update']; ?>';
</script>

<div id="page-inner">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-12">
                <h1 class="page-head-line">
                    <?php echo $linea_tiempo['nombre']; ?>
                </h1>
            </div>
            <div class="col-md-3">
                <div class="col-sm-1">
                </div>
                <a href="<?php echo site_url('workflow'); ?>"><span class="glyphicon glyphicon-triangle-left"></span>Regresar</a>
            </div>

            <div class="btn-group btn-group-justified" role="group">
                <div class="btn-group" role="group">
                    <button class="btn btn-tpl" type="button" onclick="editar_linea_tiempo(<?php echo $linea_tiempo['id_linea_tiempo']; ?>)" data-toggle="modal" data-target="#my_modal">
                        <a class="btn-link">Editar</a>
                    </button>
                </div>
                <?php
                foreach ($modulos_administracion as $row)
                {
                    ?>
                    <div class="btn-group" role="group">
                        <a class="btn-link" href="<?php echo site_url($row['url'] . '/' . $linea_tiempo['id_linea_tiempo']); ?>">
                            <button class="btn btn-tpl" type="button">
                                <?php echo $row['label']; ?>
                            </button>
                        </a>
                    </div>
                    <?php
                }
                ?>               
            </div>

            <br>
            <br>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Detalle</h3>
                </div>
                <div class="panel-body">
                    <?php
                    include 'cabecera.tpl.php';
                    ?>
                </div>
            </div>
            <br>

        </div>

    </div>
</div>

