<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>

<style>
    .config-panel {
        padding: 10px;
        margin: 10px 0;
        background: #fcfcfc;
        border: 1px solid #e9e9e9;
        display: inline-block;
    }

    .config-panel label {
        margin-right: 10px;
    }
    #page-inner{
        min-height: 1250px !important;
    }
</style>

<?php
echo js("directorio/directorio.js");
?>

<div id="page-inner">
    <div class="panel-heading">
        <h1 class="page-head-line">Módulo de Registro – Directorio de Coordinadores Clínicos de Educación e Investigación en Salud (CCEIS)</h1>
        <h4 class="">Registre los Coordinadores Clínicos de Educación e Investigación en Salud (CCEIS) de cada unidad médica de su delegación o, en su caso, a los encargados de dicho puesto. Para mayor referencia consulte el tutorial que puede descargar <a href="<?php echo base_url('assets/files/manual_directorio.pdf'); ?>" download> aquí</a>.</h4>
    </div>
    <div id="form_filtro" class="col-sm-12">
        <?php
        if ($mostrar_filtros)
        {
            ?>
            <form class="form-inline">
                <div class="form-group tipo_actividad_class col-sm-4">
                    <!--<i class="fa fa-question-circle sipimss-helper" data-help="tipo_actividad"></i>-->
                    <h5><label for="tipo_nivel">Delegación/UMAE </label></h5>
                    <select name="nivel_reporte" id="nivel_reporte" class="form-control">
                        <option value=1>Delegación</option>
                        <option value=2>UMAE</option>
                    </select>
                </div>
            </form>
<?php } ?>
    </div>
    <div class="col-sm-12"><br></div>
    <div class="pinta_resumen"></div>
    <div class="col-sm-12"><br></div>
    <div class="col-sm-6">
        <p><h5 class="jsgrid">Para editar o añadir un nuevo registro, ingrese la matrícula del CCEIS o encargado y oprima la tecla "Enter" de su teclado para que se exhiba el nombre de manera automática, llene los demás datos y de clic en el icono de actualizar <input class="jsgrid-button jsgrid-update-button" title="Actualizar" type="button"></h5></p>
    </div>
    <div class="col-sm-6 text-right">
        <h4>
            <a href="<?php echo site_url('/directorio/exportar_datos/'); ?>">
            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <i class="fa fa-table" aria-hidden="true"></i>
                Exportar todos los datos registrados
            </a>
        </h4>
    </div>
    <div class="col-sm-12"><br></div>
    <div class="col-sm-12">
        <div id="jsGridDirectorio"></div>
    </div>
</div>
