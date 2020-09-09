<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>

<div id="jsGrid"></div>

<script type="text/javascript">
    var lineas_tiempo = [];
<?php
foreach ($lineas_tiempo as $row)
{
    $fecha_inicial = transform_date($row['fechas_inicio'])[0];
    $fecha_final = transform_date($row['fechas_fin']);
    $fecha_final = $fecha_final[count($fecha_final) - 1];
    $linea_t = array(
        'id_linea_tiempo' => $row['id_linea_tiempo'],
        'nombre' => $row['nombre'],
        'clave' => $row['clave'],
        'tipo' => $row['tipo'],
        'fecha_inicio' => $fecha_inicial,
        'fecha_fin' => $fecha_final,
        'activa' => $row['activa'] == 1 ? true : false,
    );
    ?>
        lineas_tiempo.push(<?php echo json_encode($linea_t); ?>);
    <?php
}
?>
    render_grid();
</script>