<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
//pr("Aqui entra");
//pr($campos);
//pr($campos_seccion);
//pr($campos_elemento_seccion);
//pr($id_censo);
?>


<tr  class="item <?php echo $activo; ?>">
    
    <?php
           if($pinta_elemento_seccion){
               ?>
                <td><p> <?php echo $nom_elemento_seccion; ?> </p></td>
               <?php
           }
            foreach ($campos_seccion as $key_c => $value_cs) {
                if(isset($campos[$key_c])){
                $value = $campos[$key_c];
                //$campos_seccion
                /* $value contiene las sigiente estructura
                    [lb_campo] => Formación profesional del profesor
                    [respuesta_valor] => Carrera técnica
                    [nueva_linea] => 1
                    [nom_tipo_campo] => dropdown
                 */
                //pr($value['respuesta_valor']);
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
                                    <td  id="div_<?php echo $id_censo . '_' . $key_c; ?>" >
                                    <p> <?php echo $value['respuesta_valor']; ?> </p>
                                </td>
                                <?php
                                }
                            break;
                            case 'custom':
                                ?>
                                <td  id="div_<?php echo $id_censo . '_' . $key_c; ?>">
                                <?php

                                    if((strlen($value['respuesta_valor']))>0){
                                        $decode  = json_decode($value['respuesta_valor'], true);
                                        if(!empty($decode)){
                                            foreach ($decode as $key_cust => $value_custom){
                                    ?>
                                            <div  id="divS_<?php echo $id_censo . '_' . $key_c; ?>" class="col-md-6 goleft <?php echo 'c_' . $key_cust; ?>">
                                            <p> 
                                                <label class="bold-label"><?php echo "Nombre curso: ";//Asociar con textos de lenguaje ?></label><?php echo $value_custom['nombre_curso']; ?>
                                            </p>
                                            <p> 
                                                <label class="bold-label"><?php echo "Años: ";//Asociar con textos de lenguaje ?></label><?php echo $value_custom['anio']; ?>
                                            </p>
                                            </div>  
                                    <?php
                                        }
                                    }
                                }
                                ?>
                                </td>
                                <?php
                            break;

                        default :
                            ?>
                            <td  id="div_<?php echo $id_censo . '_' . $key_c; ?>">
                                <p class="l_<?php echo $key_c; ?>"><?php echo $value['respuesta_valor']; ?></p>
                            </td>
                        <?php
                    }
                }else{
                    ?>
                        <td  id="div_<?php echo $id_censo . '_' . $key_c; ?>"></td>
                    <?php
                }
            }
                ?>
                
                <?php
            }
            //pr($conf_validacion);
            if($conf_validacion[1]['view_col_val_censo']){
                ?>
                 <th> <input type="radio" id="radio_si_<?php echo $id_censo;?>" class="ctrselect_si_<?php echo $id_seccion?>" name="<?php echo $id_censo;?>" value="si"><label>Si </label></th>
                 <th> <input type="radio" id="radio_no_<?php echo $id_censo;?>" class="ctrselect_no_<?php echo $id_seccion?>" name="<?php echo $id_censo;?>" value="no"><label>No </label></th>
                <?php
                }
                ?>
            </tr>
              
    