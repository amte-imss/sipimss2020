<?php

/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$fechas_iniciales = transform_date($linea_tiempo['fechas_inicio']);
$fechas_finales = transform_date($linea_tiempo['fechas_fin']);
$fechas = str_replace('{', '', $workflow['labels_fechas']);
$fechas = str_replace('}', '', $fechas);
$fechas = str_replace('"', '', $fechas);
$nombres_fechas = explode(',', $fechas);
?>
<div class="col-md-6">
    <label><?php echo $linea_tiempo['nombre']; ?></label>
</div>
<div class="col-md-6">
    <label><?php echo $linea_tiempo['clave']; ?></label>
</div>

<?php
for ($i = 0; $i < count($nombres_fechas) ; $i++)
{
    ?>
    <div class="col-md-6">
        <label>fecha inicio de <?php echo $nombres_fechas[$i] . ' : ' . date_format(date_create($fechas_iniciales[$i]), 'd/m/Y'); ?></label>
    </div>
    <div class="col-md-6">
        <label>fecha fin de <?php echo $nombres_fechas[$i] . ' : ' . date_format(date_create($fechas_finales[$i]), 'd/m/Y'); ?></label>
    </div>
    <?php
}
?>
