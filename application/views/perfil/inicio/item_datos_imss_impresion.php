<?php 
//pr($elementos_seccion); 
 //pr($string_value); 
?>
        <div class="row">
        
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <i><?php echo $string_value['info_siap']; ?>:</i>
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
                    <?php echo $string_value['lbl_nombre']; ?>:
                    </label>
                    <?php echo $elementos_seccion['nombre'] . " " . $elementos_seccion['apellido_p'] . " " . $elementos_seccion['apellido_m']; ?>
                 </p>
             </div>

             <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_fecha_nacimiento']; ?>:
                    </label>
                    <?php echo get_date_formato($elementos_seccion['fecha_nacimiento'], 'd-m-Y'); ?> </p>
            </div>
            
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_edad']; ?>:
                    </label>
                    <?php echo $elementos_seccion['edad']; ?></p>
            </div>

            <div class="col-md-12 goleft">
                 <p>
                    <label class="bold-label">
                    <?php echo $string_value['lbl_clave_delegacional']; ?>:
                    </label>
                    <?php echo $elementos_seccion['delegacion'];?>
                 </p>
             </div>
            <div class="col-md-12 goleft">
                 <p>
                    <label class="bold-label">
                    <?php echo $string_value['lbl_clave_adscripcion']; ?>:
                    </label>
                    <?php echo $elementos_seccion['departamento'];?>
                 </p>
             </div>
            <div class="col-md-12 goleft">
                <p>
                    <label class="bold-label">
                        <?php echo $string_value['lbl_clave_categoria']; ?>:
                    </label>
                    <?php echo (isset($elementos_seccion['categoria']) && $elementos_seccion['categoria'] != '()') ? $elementos_seccion['categoria'] : ''; ?>
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
            <?php //if(isset($elementos_seccion['id_docente_carrera'])){ ?>
                <div class="col-md-12 goleft">
                    <p>
                        <?php $is_carrera_docente =  (!is_null($elementos_seccion['id_docente_carrera'])); ?>
                        <label class="bold-label">
                            <?php echo $string_value['docente_carrera_docente']; ?>
                        </label>
                        <?php echo ($is_carrera_docente)?"Si":"No"; ?>
                        <?php if($is_carrera_docente){?>
                            <br>
                            <label class="bold-label">                            
                                <?php echo $string_value['fase_docente_carrera']; ?>
                            </label>
                            <?php echo $elementos_seccion['descripcion']; ?>
                        <?php }?>
                        
                    </p>
                </div>
            <?php //} ?>

          
        </div>




