<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">                        
    <div class="col-md-10">
    <?php
        echo '<hidden id="' . $value['nom_campo'] . '" name="' . $value['nom_campo'] .'value="[]"' . '>';
    ?>
        <?php $this->load->view('docente/componente_custom.php', $aux_array_componente, FALSE);?>                    
    </div>
<?php echo form_error_format($value['nom_campo']); //agrega div para mostrar error ?>
</div>
