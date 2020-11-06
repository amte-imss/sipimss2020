<?php
// pr($usuario);
//pr($notificaciones);
//pr($roles_usuario);


// pr($usuario['workflow']);
    ?>
    <li class="dropdown" >
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true" style="text-decoration: none; background-color: #006a62 !important; color: #fff;">
            <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down" ></i>
        </a>
        <ul class="dropdown-menu dropdown-tasks">
            <?php
            if(count($notificaciones) > 0){
                //pr($notificaciones);
                foreach ($notificaciones as $row)
                {
                    if(isset($roles_usuario[$row['clave_rol']])){
                        //pr($roles_usuario[$row['clave_rol']]);

                        $f = transform_date($row['fecha_fin']);
                        $f = $f[0];
                        $date1=date_create(date('Y-m-d'));
                        $date2=date_create($f);
                        $dias = date_diff($date1, $date2)->days;
                        $texto = str_replace('{dias_restantes_convocatoria}', $dias, $row['descripcion']);
                    ?>
                        <li>
                            <a href="#">
                                <div >
                                    <strong><?php echo $row['nombre']; ?></strong>
                                    <h5><?php echo $texto; ?></h5>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                    <?php
                    }
                }
            }
            ?>
        </ul>
    </li>
    <?php

?>
