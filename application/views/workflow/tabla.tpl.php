<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<table class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Clave</th>
            <th>Tipo</th>
            <th>Fecha de inicio</th>
            <th>Fecha de fin</th>
            <th>Etapa actual</th>
            <th>Activa</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($lineas_tiempo as $row)
        {
            $fecha_inicial = transform_date($row['fechas_inicio'])[0];
            $fecha_inicial = date_format(date_create($fecha_inicial), 'd/m/Y');

            $fecha_final = transform_date($row['fechas_fin']);
            $fecha_final = $fecha_final[count($fecha_final) - 1];
            $fecha_final = date_format(date_create($fecha_final), 'd/m/Y');
            ?>
            <tr>
                <td><a href="<?php echo site_url('workflow/index/' . $row['id_linea_tiempo']); ?>"><?php echo $row['nombre']; ?></a></td>
                <td><a href="<?php echo site_url('workflow/index/' . $row['id_linea_tiempo']); ?>"><?php echo $row['clave']; ?></a></td>
                <td><?php echo $row['tipo']; ?></td>
                <td><?php echo $fecha_inicial; ?></td>
                <td><?php echo $fecha_final; ?></td>
                <td><?php echo $row['etapa']; ?></td>
                <td></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>