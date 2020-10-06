<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
                        <div class="col-md-4">
                            <label for="<?php echo $value['nom_campo']; ?>" class="control-label" id="lbl_<?php echo $value['nom_campo']; ?>" >
                                <?php
                                if (!empty(($value['rules'])) and strpos($value['rules'], 'required') > 0) { //$value['obligatorio'] or
                                    ?>
                                    <b class="rojo">*</b>
                                <?php } ?>
                                <?php echo $value['lb_campo']; ?>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <?php
                            /*$aux_array_componente = array('value' => $value, 'string_values' => $string_values,
                                'key_callback' => $key_callback,
                                'funcion_callback' => $funcion_callback,
//                                'bloquear' => (isset($value['id_censo']) AND $value['is_precarga_registro_sistema']) ? $value['is_precarga'] : FALSE,//VAlida precarga, para bloquear campos
                                'bloquear' => (isset($value['id_censo'])) ? $value['is_precarga_registro_sistema'] : FALSE, //VAlida precarga, para bloquear campos
                            );*/
                            switch ($value['nom_tipo_campo']) {
                                case 'file':
                                    $this->load->view('docente/componente_files.php', $aux_array_componente, FALSE);
                                    break;
                                case 'checkbox':
                                    //pr($aux_array_componente);
                                    $this->load->view('docente/componente_checkbox.php', $aux_array_componente, FALSE);
                                    break;
                                case 'date':
                                    if ($value['nom_tipo_dato'] == 'date') {
                                        $this->load->view('docente/componente_date.php', $aux_array_componente, FALSE);
                                    } else {
                                        $this->load->view('docente/componente_year.php', $aux_array_componente, FALSE);
                                    }
                                    break;
                                case 'dropdown':
                                    $value_result = "";
//                                    pr((isset($_POST[$value['nom_campo']])) ? $_POST[$value['nom_campo']] : "");
                                    if (isset($_POST[$value['nom_campo']]) /* and ! empty($_POST[$value['nom_campo']]) */) {//Recargar post
       // pr($value['nom_campo']. ' -> ' . $_POST[$value['nom_campo']]);
                                        ?>
                                        <script >
                                            /* Guarda los datos de configuración para el uso de ajax en javascript */
                                            memoria_values["<?php echo $value['nom_campo']; ?>"] = "<?php echo $_POST[$value['nom_campo']]; ?>";
                                        </script>
                                        <?php
                                    } else if (isset($value['valor']) and $value['valor'] != 'NULL') {
                                        $value_result = $value['valor'];
                                        ?>
                                        <script >
                                            /*Guarda los datos de configuración para el uso de ajax en javascript*/
                                            memoria_values["<?php echo $value['nom_campo']; ?>"] = "<?php echo $value_result; ?>";
                                        </script>
                                        <?php
                                    }
                                    //pr($value_result);

                                    $aux_array_componente['controlador'] = $controlador;
                                    $aux_array_componente['value_result'] = $value_result;
                                    $this->load->view('docente/componente_dropdown.php', $aux_array_componente, FALSE);
                                    break;
                                case 'dropdown_otro':
                                    $value_result = "";
//                                    pr((isset($_POST[$value['nom_campo']])) ? $_POST[$value['nom_campo']] : "");
                                    if (isset($_POST[$value['nom_campo']]) /* and ! empty($_POST[$value['nom_campo']]) */) {//Recargar post
//        pr($value['nom_campo']);
//                                    pr($_POST[$value['nom_campo']]);
                                        ?>
                                        <script >
                                            /* Guarda los datos de configuración para el uso de ajax en javascript */
                                            memoria_values["<?php echo $value['nom_campo']; ?>"] = "<?php echo $_POST[$value['nom_campo']]; ?>";
                                        </script>
                                        <?php
                                    } else if (isset($value['valor']) and $value['valor'] != 'NULL') {
                                        $value_result = $value['valor'];
                                        ?>
                                        <script >
                                            /*Guarda los datos de configuración para el uso de ajax en javascript*/
                                            memoria_values["<?php echo $value['nom_campo']; ?>"] = "<?php echo $value['valor']; ?>";
                                        </script>
                                        <?php
                                    }

                                    $aux_array_componente['controlador'] = $controlador;
                                    $aux_array_componente['value_result'] = $value_result;
                                    $aux_array_componente['opciones_extra_catalogo_otro'] = array();
                                    if (isset($opciones_extra_catalogo_otro[$value['nom_campo']])) {//Agrega datos de campo otro
                                        $aux_array_componente['opciones_extra_catalogo_otro'] = $opciones_extra_catalogo_otro[$value['nom_campo']];
                                    }
                                    $this->load->view('docente/componente_dropdown_otro.php', $aux_array_componente, FALSE);
                                    break;
                                    case 'year':
                                    $this->load->view('docente/componente_group.php', $aux_array_componente, FALSE);
                                        break;
                                    case 'hidden':
                                            $this->load->view('docente/componente_hidden.php', $aux_array_componente, FALSE);
                                        break;
                                    case 'custom':
                                            $this->load->view('docente/componente_custom.php', $aux_array_componente, FALSE);
                                        break;    
                                    case 'href':
                                            $this->load->view('docente/componente_href.php', $aux_array_componente, FALSE);
                                        break;
                                    default :
                                    $this->load->view('docente/componente_group.php', $aux_array_componente, FALSE);
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    echo form_error_format($value['nom_campo']); //agrega div para mostrar error
                    ?>
