<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controlador = $this->uri->rsegment(1);
//pr($campos);
?>
<!--Content-->
<div class="item <?php echo $activo; ?>">
    <div class="text-center">
        <h3 class="panel-title">
            <?php
            if (isset($nom_elemento_seccion)) {
                echo $nom_elemento_seccion;
            }
            ?>
        </h3>
    </div><br><br>
    <div class="col-md-3"></div>
    <div class="col-md-6 panel ">
        <div class="panel-body">
            <div class="col-md-6 goleft">
                <p><label class="bold-label">Folio: </label>
                    <?php echo isset($folio)?$folio:''; ?>
                </p>
            </div>
            <?php
            if (isset($id_censo) || !empty($id_censo)) { //En caso de tener asociado archivo, se muestra link
                ?>
                <div class="col-md-6 goleft">
                    <p><label class="bold-label"><?php echo $string_value['lbl_texto_comprobante']; ?></label>

                        <?php
                        $file = (isset($id_file)) ? encrypt_base64($id_file) : '';
                        echo $this->form_complete->create_element(array('id' => 'id_file_comprobante', 'type' => 'hidden', 'value' => $file));
                        echo '<a href="' . site_url($controlador . '/ver_archivo/' . $file) . '" target="_blank"><span class="fa fa-search"></span> ' . $string_value['ver_archivo'] . '</a><br>';
                        echo '<a href="' . site_url($controlador . '/descarga_archivo/' . $file) . '" target="_blank"><span class="fa fa-download"></span> ' . $string_value['descargar_archivo'] . '</a>';
//                echo $this->form_complete->create_element(array('id' => 'idc', 'type' => 'hidden', 'value' => $file));
                        ?>
                    </p>
                </div>
                <?php
            }
            foreach ($campos as $key_c =>  $value) {
                /* $value contiene las sigiente estructura
                  [lb_campo] => Formación profesional del profesor
                  [respuesta_valor] => Carrera técnica
                  [nueva_linea] => 1
                  [nom_tipo_campo] => dropdown
                 */
                if ($value['respuesta_valor'] != 'NULL') {//Submit lo considerará el
                    switch ($value['nom_tipo_campo']) {
                        case 'file':
                            ?>

                            <div  id="div_<?php echo $id_censo . '_' . $key_c; ?>" class="col-md-6 goleft <?php echo 'c_' . $key_c; ?>">
                                <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label>
                                    <?php
                                    $file = (isset($id_file)) ? encrypt_base64($value['respuesta_valor']) : '';
                                    echo '<a href="' . site_url($controlador . '/ver_archivo/' . $file) . '" target="_blank"><span class="fa fa-search"></span> ' . $string_value['ver_archivo'] . '</a><br>';
                                    echo '<a href="' . site_url($controlador . '/descarga_archivo/' . $file) . '" target="_blank"><span class="fa fa-download"></span> ' . $string_value['descargar_archivo'] . '</a>';
                                    ?>
                                </p>
                            </div>
                            <?php
                            break;
                        default :
                            ?>
                            <div  id="div_<?php echo $id_censo . '_' . $key_c; ?>" class="col-md-6 goleft <?php echo 'c_' . $key_c; ?>">
                                <p> <label class="bold-label"><?php echo $value['lb_campo']; ?></label><?php echo $value['respuesta_valor']; ?> </p>
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
    <div class="col-md-3"></div>
</div>
