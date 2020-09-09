<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<?php
foreach ($ayudas as $value)
{
    ?>
    <p>
        <strong><?php echo $value['nombre'] . ':'; ?></strong> 
        <?php echo $value['descripcion']; ?>
    </p>

<?php } ?>