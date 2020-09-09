<div id="div2" class="targetDiv" style="display:block;">
    <br>
    <div class="task-content">
        <div class="row">
            <div class="col-md-12 goleft">
                 <p>
                    <label class="bold-label">
                    <?php echo $string_value['lbl_matricula']; ?>:
                    </label>
                    <?php echo $elementos_seccion['matricula']; ?>
                 </p>
             </div>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_categoria']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['categoria'])) ? $elementos_seccion['categoria'] : ''; ?>
                </p>
            </div>
            <?php if(isset($elementos_seccion['region'])){ ?>
                <div class="col-md-12 goleft">
                    <p>
                        <label class="bold-label">
                            <?php echo $string_value['lbl_region']; ?>:
                        </label>
                        <?php echo $elementos_seccion['region']; ?>
                    </p>
                </div>
            <?php } else { ?>
                <div class="col-md-12 goleft">
                    <p>
                        <label class="bold-label">
                            <?php echo $string_value['lbl_nivel_central']; ?>
                        </label>
                    </p>
                </div>
            <?php } ?>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_delegacional']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['delegacion'])) ? $elementos_seccion['delegacion'] : ''; ?>
                </p>
            </div>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_departamental']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['departamento'])) ? $elementos_seccion['departamento'] : ''; ?>
                </p>
            </div>
            <?php if(isset($elementos_seccion['nom_unidad'])){ ?>
                <div class="col-md-12 goleft">
                    <p>
                        <label class="bold-label">
                            <?php echo $string_value['lbl_unidad']; ?>:
                        </label>
                        <?php echo (isset($elementos_seccion['nom_unidad'])) ? $elementos_seccion['nom_unidad'].' ('.$elementos_seccion['clave_unidad'].')' : ''; ?>
                    </p>
                </div>
            <?php } ?>



            <!-- <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        Estado :
                    </label>
                    Activo
                </p>
            </div> -->
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label"><br><br>
                    </label>
                </p>
            </div>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <i>Información proporcionada por SIAP</i>
                    </label>
                </p>
            </div>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['fecha_ultima_actualizacion']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['echa_ultima_actualizacion'])) ? get_date_formato($elementos_seccion['echa_ultima_actualizacion'], 'd-m-Y') : ''; ?>
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
