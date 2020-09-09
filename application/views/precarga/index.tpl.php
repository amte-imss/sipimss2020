<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>


<?php
if(isset($scripts_adicionales))
{
    foreach ($scripts_adicionales as $value)
    {
        ?>
        <script type="text/javascript">
            <?php echo $value ?>
        </script>
        <?php
    }
}
?>
<?php echo $js?js($js):"Prueba"; ?>
<div id="page-inner">
    <div class="col-sm-12">
        <h2 class="page-head-line">
            <?php if(isset($title)){ echo $title;}?>
        </h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <div class="pager-panel">
                    <label>Cantidad por pagina:
                        <select id="pager">
                            <option>5</option>
                            <option>10</option>
                            <option selected>25</option>
                            <option>50</option>
                            <option>100</option>
                            <option>200</option>
                        </select>
                    </label>
                    <?php if(isset($exportar))
                    {
                        ?>
                        <div class="">
                            <a href="<?php echo $exportar;?>" class="btn btn-primary">Exportar</a>
                        </div>
                        <?php
                    } ?>

                </div>
                <div class="">
                    <div id="mensajes_sincronización" class="">

                    </div>
                    <div id="lista_registros"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="precarga_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle del registro</h4>
            </div>
            <div class="modal-body">
                <div class="" id="precarga_modal_content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
