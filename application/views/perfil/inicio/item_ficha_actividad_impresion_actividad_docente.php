<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
//pr($campos);
?>
<!--Content-->
<div class="item <?php echo $activo; ?>">
    <!-- <div class="text-center">
        <h3 class="panel-title">
            <?php
            /*if (isset($nom_elemento_seccion)) {
                echo $nom_elemento_seccion;
            }*/
            ?>
        </h3>
    </div> -->
    <div class="col-lg-12 panel" style="background-color:#f1f1f1;">
        <div class="panel-body">
                        
            <?php
            
            foreach ($campos as $key_c => $value) {
                /* $value contiene las sigiente estructura
                    [lb_campo] => Formación profesional del profesor
                    [respuesta_valor] => Carrera técnica
                    [nueva_linea] => 1
                    [nom_tipo_campo] => dropdown
                 */
                if ($value['respuesta_valor'] != 'NULL') {//Submit lo considerará el
                    switch ($value['nom_tipo_campo']) {
                        /*case 'file':
                            ?>

                            <div class="col-md-6 goleft">
                                <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label>
                                    <?php
                                    $file = (isset($id_file)) ? encrypt_base64($value['respuesta_valor']) : '';
                                    echo '<a href="' . site_url($controlador . '/ver_archivo/' . $file) . '" target="_blank"><span class="fa fa-search"></span> ' . $string_value['ver_archivo'] . '</a><br>';
                                    echo '<a href="' . site_url($controlador . '/descarga_archivo/' . $file) . '" target="_blank"><span class="fa fa-download"></span> ' . $string_value['descargar_archivo'] . '</a>';
                                    ?>
                                </p>
                            </div>
                            <?php
                            break;*/
                            case 'checkbox':
                                if($value['respuesta_valor']=="1" || $value['respuesta_valor']==1){
                                ?>
                                    <div  id="div_<?php echo $id_censo . '_' . $key_c; ?>" class="col-md-6 goleft <?php echo 'c_' . $key_c; ?>">
                                    <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label></p>
                                    </div>
                                <?php
                                }
                            break;
                                case 'custom':
                                    if((strlen($value['respuesta_valor']))>0 ){
                                        $decode  = json_decode($value['respuesta_valor'], true);
                                        if(!empty($decode) ){

                                    ?>
                                            <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label></p>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo "Nombre curso ";//Asociar con textos de lenguaje ?></th>
                                                        <th><?php echo "Años ";//Asociar con textos de lenguaje ?></th></tr>
                                                </thead>
                                                <tbody>                                                
                                                    <?php                                                
                                                    foreach ($decode as $key_cust => $value_custom){
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $value_custom['nombre_curso']; ?></td>
                                                            <td><?php echo $value_custom['anio']; ?></td>
                                                        </tr>                                                
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    
                                        <?php
                                        }
                                    }
                                
                            break;

                        default :
                            ?>
                            <div  id="div_<?php echo $id_censo . '_' . $key_c; ?>" class="col-md-6 goleft <?php echo 'c_' . $key_c; ?>">
                                <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label>
                                    <?php echo $value['respuesta_valor']; ?>
                                </p>
                            </div>
                        <?php
                    }
                }
            }
            ?>
            <a class="btn btn-lg btn-outline-white waves-effect waves-light"><i class="left"></i></a>
            <br>
        </div>
    </div>
</div>
