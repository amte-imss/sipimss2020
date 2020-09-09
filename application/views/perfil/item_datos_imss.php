<?php
//pr($elementos_seccion);
?>

<div id="div2" class="targetDiv" style="display:none;">

    <div class="task-content">
        <div class="row">
            <!-- <div class="col-md-6 goleft">
                 <p>
                     <label class="bold-label">
            <?php // echo $string_value['lbl_matricula']; ?>
                     </label>
            <?php // echo $elementos_seccion['matricula']; ?>
                 </p>
             </div>-->


            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_categoria']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['categoria'])) ? $elementos_seccion['categoria'] : ''; ?>
                </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_region']; ?>
                    </label>
                    <?php echo (isset($elementos_seccion['region'])) ? $elementos_seccion['region'] : ''; ?>
                </p>
            </div>

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_departamental']; ?>
                    </label>
                    <?php echo (isset($elementos_seccion['departamento'])) ? $elementos_seccion['departamento'] : ''; ?>
                </p>
            </div>



            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_delegacional']; ?>
                    </label>
                    <?php echo (isset($elementos_seccion['delegacion'])) ? $elementos_seccion['delegacion'] : ''; ?>
                </p>
            </div>
            <!-- <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        Estado :
                    </label>
                    Activo
                </p>
            </div> -->
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                    </label>

                </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        Información proporcionada por SIAP
                    </label>

                </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['fecha_ultima_actualizacion']; ?>
                    </label>
                    <?php echo (isset($elementos_seccion['fecha_ultima_actualizacion'])) ? $elementos_seccion['fecha_ultima_actualizacion'] : ''; ?>
                </p>
            </div>
        </div>

    </div>

</div>
<!--<script type="text/javascript">
    jQuery(function () {
        //  jQuery('#showall').click(function(){
        //        jQuery('.targetDiv').show();
        // });
        jQuery('.showSingle').click(function () {
            jQuery('.targetDiv').hide();
            jQuery('#div' + $(this).attr('target')).show();
        });
    });

</script>-->
