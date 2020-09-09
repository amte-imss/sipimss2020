<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>
<?php echo js('administracion/bitacora.js'); ?>
<div id="page-inner">
    <div class="col-sm-12">
        <h1 class="page-head-line">
            Lista de registros
        </h1>
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
                </div>
                <div class="">
                    <div id="lista_registros">
                </div>
            </div>
        </div>
    </div>
</div>
