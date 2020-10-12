<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
?>
<div id="myCarousel<?php echo $id_seccion; ?>">
    <!-- <ol class="carousel-indicators">
        <?php
        /*if (isset($count)) {
            for ($i = 0; $i < $count; $i++) {
                ?>
                <li data-target="#myCarousel<?php echo $id_seccion; ?>" data-slide-to="<?php echo $i; ?>" class="active"></li>
                <?php
            }
        }*/
        ?>
    </ol> -->
    <div>

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
            <?php
            if($pinta_elemento_seccion){
                ?>
                 <th> <?php echo "Formulario"; ?></th>
                <?php
            }
                    foreach ($campos_seccion as $key_campos => $value_campo) {
                    //pr($campos);
                        $campoName = $value_campo;
            ?>
                        <th><?php echo $campoName;?> </th>
            <?php
                    }
            ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($elementos_seccion)) {
                        ?>
                        <?php echo $elementos_seccion; ?>
                    <?php } ?>
                </tbody>
                
            </table>                
    </div>

  