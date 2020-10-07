
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$string_values = get_elementos_lenguaje(array(En_catalogo_textos::COMPROBANTE));
////pr($catalogos);
//pr($formulario_campos);
//pr($rutas_generales_js);
//pr($propiedades_formulario);
//exit();
//Abre formulario
//pr($boton_submit);
$_array_js_formularios = array();
$controlador = '/' . $this->uri->rsegment(1);
?>

<?php
echo js("docente/control_campo_otro.js");
if (isset($rutas_generales_js) and ! is_null($rutas_generales_js)) {//Inserta las rutas js para usasr el formulario
    foreach ($rutas_generales_js as $ruta_js_valor) {
        echo js($ruta_js_valor);
    }
}

echo js('date/locales/bootstrap-datepicker.es.js', array("charset" => "UTF-8"));
echo css('date/datepicker.css');
echo js('docente/campo_date.js');
echo js('docente/campo_year.js');
?>
<script>
    var array_padres_dependientes = new Object();
    var memoria_values = new Object();
</script>

<div class="list-group">
    <div class="list-group-item">
        <div id="id_panel_formulario" class="panel-body" onmousedown="elemento(event);">
            <div id="notificaciones_id" class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <?php if (isset($arbol_secciones)) { ?>
                <div class="row" id="div_arbol_seccion">
                    <div class="col-md-12 ">
                        <label class="text-left" style="font-size:16px" for="lbl_arbol_seccion" class="">
                            <?php echo $arbol_secciones; ?>
                        </label>
                    </div>
                </div>
            <?php } ?>
            <?php
//            echo form_open('', array('id' => 'form_actividad_' . $formulario, 'enctype'=>'"multipart/form-data"')); //Pinta formulario
            echo form_open('', array('id' => 'form_actividad_' . $formulario)); //Pinta formulario
            echo $this->form_complete->create_element(array('id' => 'id_elementos_seccion', 'type' => 'hidden', 'value' => $id_elementos_seccion));
            echo $this->form_complete->create_element(array('id' => 'formulario', 'type' => 'hidden', 'value' => $formulario));
            echo $this->form_complete->create_element(array('id' => 'censo_regstro', 'type' => 'hidden', 'value' => (isset($censo_registro)) ? encrypt_base64($censo_registro) : ''));
            $i = 1;
            $row_print = array('row_end' => '', 'row_close' => '');
            $notificaciones = '';
            foreach ($formulario_campos as $value) {
                //pr("is_linea_completa ? " . $value['is_linea_completa']);
//                if ($value['nom_tipo_campo'] != 'submit') {//Submit lo considerará el
                $row_print = get_etiqueta_row($i, $value['nueva_linea'], $value['is_linea_completa']); //obtiene la linea de nuevo regitro
                $columm_size = ($value['is_linea_completa'])? 12 : 6;
                $i = $row_print['indice']; //Retro del indice importante cuando se genera una nueva linea y toca cerrar la fila
                echo $row_print['row_begin'];

                $value['display'] = ($value['display']) ? 'none' : 'block';
                //Obtiene datos o información de notificaciones por campo aplica la regla y la envía a library de formulario
                if (!empty($value['regla_notificacion']) AND isset($value['valor']) AND $value['valor'] != 'NULL') {
//                    pr($value['regla_notificacion']);
                    $notificacion = (array) json_decode($value['regla_notificacion']);
                    $text_notificacion = $this->funciones_motor_formulario->{$notificacion['funcion']}($value['valor']); //ejecuta función
                    $br_ = '';
                    if (!is_null($text_notificacion)) {
                        $notificaciones .= $br_ . $text_notificacion;
                        $br_ = '<br>';
                    }
//                    pr($value['regla_notificacion']);
                }
//                pr($value['ruta_js']);
                $key_callback = '';
                $funcion_callback = '';
                if (strlen($value['ruta_js_form']) > 1) {
                    $key_callback = 'ruta_js_form';
                    $funcion_callback = 'funcion_js_form';
                    //Obtiene rutas de archivos JS
                    $tmp_js =  json_decode($value[$key_callback],true); //Decodifica rutas js y las convierte en array
                    $_array_js_formularios = array_merge($_array_js_formularios, $tmp_js); //Agrega a array las rutas js
                } else if (strlen($value['ruta_js']) > 1) {
                    $key_callback = 'ruta_js';
                    $funcion_callback = 'funcion_js';
                    //Obtiene rutas de archivos JS
                    $tmp_js =  json_decode($value[$key_callback],true); //Decodifica rutas js y las convierte en array
                    $_array_js_formularios = array_merge($_array_js_formularios, $tmp_js); //Agrega a array las rutas js
                }
                ?>

                <div class="col-md-<?php echo $columm_size?>" id="<?php echo 'div_' . $value['nom_campo'] ?>">
                    <?php
                            $aux_array_componente = array('value' => $value, 'string_values' => $string_values,
                                'key_callback' => $key_callback,
                                'funcion_callback' => $funcion_callback,
//                                'bloquear' => (isset($value['id_censo']) AND $value['is_precarga_registro_sistema']) ? $value['is_precarga'] : FALSE,//VAlida precarga, para bloquear campos
                                'bloquear' => (isset($value['id_censo'])) ? $value['is_precarga_registro_sistema'] : FALSE, //VAlida precarga, para bloquear campos
                            );
                            $value['aux_array_componente'] = $aux_array_componente;
                            $value['string_values'] = $string_values;
                            $value['controlador'] = $controlador;
                            $value['_POST'] = $_POST;
                            $value['value'] = $value;
                    ?>
                    
                    <?php if($value['is_linea_completa'] && $value['nom_tipo_campo'] == 'custom'  ){?>
                            <?php $this->load->view('docente/componente_custom.php', $value, FALSE);?>
                        <?php }else if($value['nom_tipo_campo'] == 'custom'){ ?>    
                            <?php $this->load->view('docente/layout_secundario_una_linea.php', $value, FALSE);?>
                    <?php }else{ ?>
                            <?php $this->load->view('docente/layout_principal_dos_columnas.php', $value, FALSE);
                        
                          }?>
                </div>
                <?php
                echo $row_print['row_end'];
                $i ++;
            }
            echo $row_print['row_close']; //Aplica el cierre de etiqueta, si el "for" termina y no cerro alguna fila
            ?>

            <div class="list-group-item text-center">
                <!--echo '<br>';-->
                <div class="row">
                    <!--</div>-->

                    <div class="col-md-6 text-right">
                        <?php
                        if (!is_null($boton_submit)) {//Valida diferente de null
                            echo $boton_submit;
                        }
                        ?>

                    </div>

                    <div class="col-md-6 text-left ">
                        <?php
                        if (isset($boton_cancelar)) {
                            echo $boton_cancelar;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //$('input[type="date"]').attr('type', 'text');

        //                $('[data-toggle="popover"]').popover();
//                $('.datepicker').datepicker();
//            alert('Hola su magestad LEAS');
<?php if (empty($notificaciones)) { ?>
            $("#notificaciones_id").remove();
<?php } else { ?>
            document.getElementById("notificaciones_id").innerHTML += "<?php echo $notificaciones; ?>";
<?php } ?>

    });
</script>

<?php
if (!empty($propiedades_formulario) and ! is_null($propiedades_formulario[0]['ruta_file_js'])) {
    $json_js_form = (array) json_decode($propiedades_formulario[0]['ruta_file_js']);
    if ($json_js_form) {
        foreach ($json_js_form as $v) {
            echo js($v); //Agrega archivos JS del formulario
        }
    }
}
if (!empty($_array_js_formularios)) {//Valida que existan archivos js de referencia
    foreach ($_array_js_formularios as $value) {
        echo js($value); //Agrega archivos JS de campos formulario
    }
}
?>
<?php echo js("docente/formulario.js"); ?>

