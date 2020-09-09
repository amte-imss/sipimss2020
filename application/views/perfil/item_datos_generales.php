<?php
//pr($elementos_seccion);
//exit();
?>


<div id="div1" class="targetDiv" style="display: block;">
    <br>
    <div class="task-content">
        <div class="row">

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_fecha_nacimiento']; ?>
                    </label>
                    <?php echo get_date_formato($elementos_seccion['fecha_nacimiento'], 'd-m-Y'); ?> </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        Edad:
                    </label>
                    29        </p>
            </div>

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_curp']; ?>
                    </label>
                    <?php echo $elementos_seccion['curp']; ?>
                </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_rfc']; ?>
                    </label>
                    <?php echo $elementos_seccion['rfc']; ?>
                </p>
            </div>

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_sexo']; ?>
                    </label>
                    <?php echo ($elementos_seccion['sexo'] == En_sexo::MASCULINO) ? $string_value['text_sexo_h'] : $string_value['text_sexo_m']; ?>        </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_estado_civil']; ?>
                    </label>
                    <?php echo $elementos_seccion['estado_civil']; ?>
                </p>
            </div>

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_telefono']; ?>
                    </label>
                <ul>
                    <li>Laboral :   <?php echo $elementos_seccion['telefono_laboral']; ?> </li>
                    <li>Particular : <?php echo $elementos_seccion['telefono_particular']; ?></li>
                </ul>
                </p>
            </div>
            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_correo']; ?>
                    </label>
                    <?php echo $elementos_seccion['email']; ?>      </p>
            </div>

            <div class="col-md-6 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_numero_empleos_imss']; ?>
                    </label>
                    0        </p>
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
