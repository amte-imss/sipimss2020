<?php //Deprecado por Luis 16032018
$controlador = '/' . $this->uri->rsegment(1);

//pr($valores_mostrar_opciones);
//echo js('jquery/datatables/js/jquery.dataTables.js');

echo js('jquery.dataTables.min.js');
?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#docente_actividad').dataTable({
//            "sPaginationType": "full_numbers",
        });
    });
</script>

<div class="col-sm-6">
    <br><br>
    <div id="datatable_filter" class="dataTables_filter">
        <div class="col-md-2">
            <label>Buscar:</label>
        </div>
        <div class="col-md-6">
            <input type="search" class="form-control" placeholder="" aria-controls="datatable">
        </div>
    </div>
    <br><br>
</div>

<div class="col-md-12">
    <div class="table">
        <br>
        <div class="row">

            <?php
            if (isset($catalogo_secciones_actividad_docente)) {
                ?>
                <?php
//                echo $catalogo_secciones_actividad_docente;
                ?>
                <?php
            }
            ?>

        </div>
        <br>
        <div id="div_cuerpo_tabla" class="row">
            <!-- tabla -->
            <?php
            echo $componente_datatable;
            ?>
        </div>
    </div>
    <div class="row">

    </div>





</div>
