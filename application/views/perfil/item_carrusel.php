
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
?>

<div id="myCarousel<?php echo $id_seccion; ?>" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
        if (isset($count)) {
            for ($i = 0; $i < $count; $i++) {
                ?>
                <li data-target="#myCarousel<?php echo $id_seccion; ?>" data-slide-to="<?php echo $i; ?>" class="active"></li>
                <?php
            }
        }
        ?>
    </ol>
    <div class="carousel-inner">
        <?php
        if (isset($elementos_seccion)) {
            ?>
            <?php echo $elementos_seccion; ?>
        <?php } ?>
    </div>

    <a class="left carousel-control" href="#myCarousel<?php echo $id_seccion; ?>" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="right carousel-control" href="#myCarousel<?php echo $id_seccion; ?>" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Siguiente</span>
    </a>

</div>