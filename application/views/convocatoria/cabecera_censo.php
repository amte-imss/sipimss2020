<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
//pr($convocatoria);
$fechas_iniciales = transform_date($convocatoria['fechas_inicio']);
$fechas_finales = transform_date($convocatoria['fechas_fin']);
$nombres_fechas = array(0 => 'registro', 1 => 'validación N1', 2 => 'validación N2');
?>







<div class="col-md-6">
    <label><?php echo $convocatoria['nombre']; ?></label>
</div>
<div class="col-md-6">
    <label><?php echo $convocatoria['clave']; ?></label>
</div>
<div class="col-md-6">
    <label>Segmento: <?php echo $segmentos[$convocatoria['target']]; ?></label>
</div>
<div class="col-md-6">
    <label>Porcentaje de muestra: <?php echo $convocatoria['porcentaje']; ?></label>
</div>

<?php
for ($i = 0; $i < 3; $i++)
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
