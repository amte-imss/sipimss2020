<?php
// pr($usuario);
$is_docente = false;
$have_convocatorias = isset($usuario['workflow']) && !empty($usuario['workflow']);
// pr($usuario['workflow']);
foreach ($usuario['niveles_acceso'] as $row)
{
    if($row['clave_rol'] == 'DOCENTE')
    {
        $is_docente = true;
    }
}
if($is_docente && $have_convocatorias)
{
    ?>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-tasks">
            <?php
            if($notificaciones > 0){
                foreach ($notificaciones as $row)
                {
                    $f = transform_date($usuario['workflow'][0]['fechas_fin']);
                    $f = $f[0];
                    $date1=date_create(date('Y-m-d'));
                    $date2=date_create($f);
                    $dias = date_diff($date1, $date2)->days;
                    $texto = str_replace('{dias_restantes_convocatoria}', $dias, $row['descripcion']);
                    ?>
                    <li>
                        <a href="#">
                            <div>
                                <strong><?php echo $row['nombre']; ?></strong>
                                <h5><?php echo $texto; ?></h5>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <?php
                }
            }
            ?>
        </ul>
    </li>
    <?php
}
?>
