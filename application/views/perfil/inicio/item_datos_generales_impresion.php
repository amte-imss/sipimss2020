
        <div class="row">
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_curp']; ?>:
                    </label>
                    <?php echo ($elementos_seccion['mostrar_datos'])?$elementos_seccion['curp']:$string_value['no_mostrar_datos_docente']; ?>
                </p>
            </div>

            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_rfc']; ?>:
                    </label>
                    <?php echo ($elementos_seccion['mostrar_datos'])?$elementos_seccion['rfc']:$string_value['no_mostrar_datos_docente']; ?>
                </p>
            </div>            

            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_correo']; ?>:
                    </label>
                <ul>
                    <li>Laboral :   <?php echo $elementos_seccion['email_personal']; ?> </li>                    
                    <li>Particular : <?php echo $elementos_seccion['email']; ?></li>
                </ul>
                </p>
            </div>

            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_telefono']; ?>:
                    </label>
                <ul>
                    <li>Laboral :   <?php echo $elementos_seccion['telefono_laboral']; ?> </li>
                    <li>Extensión :   <?php echo $elementos_seccion['ext_tel_laboral']; ?> </li>
                    <li>Particular : <?php echo $elementos_seccion['telefono_particular']; ?></li>
                </ul>
                </p>
            </div>

            <!--<div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php //echo $string_value['lbl_sexo']; ?>:
                    </label>
                    <?php //echo ($elementos_seccion['sexo'] == En_sexo::MASCULINO) ? $string_value['text_sexo_h'] : $string_value['text_sexo_m']; ?>        </p>
            </div>-->
       

            
           

            <!-- div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php //echo $string_value['lbl_numero_empleos_imss']; ?>:
                    </label>
                    0        </p>
            </div -->
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
