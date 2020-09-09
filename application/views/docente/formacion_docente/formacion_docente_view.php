<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($catalogos);
//pr($formulario_campos);
//pr($rutas_generales_js);
//exit();
//Abre formulario
$boton_submit = NULL; //Variable que servirá para identificar un submit
?>

<?php
if (isset($rutas_generales_js) and ! is_null($rutas_generales_js)) {//Inserta las rutas js para usasr el formulario
    foreach ($rutas_generales_js as $ruta_js_valor) {
        ?>
        <script type='text/javascript' src="<?php echo base_url($ruta_js_valor); ?>"></script>
        <?php
    }
}
?>

<?php
?>
<div>
        <div class="">

            <?php
            echo form_open('', array('id' => 'form_docente_inicial_' .$formulario)); //Pinta formulario
            echo $this->form_complete->create_element(array('id' => 'id_elementos_seccion', 'type' => 'hidden', 'value' => $id_elementos_seccion));
            echo $this->form_complete->create_element(array('id' => 'formulario', 'type' => 'hidden', 'value' => $formulario));
            echo $this->form_complete->create_element(array('id' => 'censo_regstro', 'type' => 'hidden', 'value' => (isset($censo_registro)) ? $censo_registro : ''));
            $i = 1;
            $row_print = array('row_end' => '', 'row_close' => '');
            foreach ($formulario_campos as $value) {
                if ($value['nom_tipo_campo'] != 'submit') {//Submit lo considerará el
                    $row_print = get_etiqueta_row($i, $value['nueva_linea']); //obtiene la linea de nuevo regitro
                    $i = $row_print['indice']; //Retro del indice importante cuando se genera una nueva linea y toca cerrar la fila
                    echo $row_print['row_begin'];
                    if($value['nom_campo'] == 'fecha_ini' || $value['nom_campo'] == 'fecha_fin' || $value['nom_campo'] == 'horasr')
                    {
                       $value['display']='none';

                    }


                    ?>

                    <div class="col-md-6" id="<?php echo $value['nom_campo']?>" style="display: <?php echo $value['display']?>" >
                        <div class="row">
                            <div class="col-md-4">
                                <label for=<?php echo $value['nom_campo']; ?> class="control-label">
                                    <?php if (!empty(($value['rules']))) { //$value['obligatorio'] or?>
                                        <b class="rojo">*</b>
                                    <?php } ?>
                                    <?php echo $value['lb_campo']; ?>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group ">
                                    <span class="input-group-addon">
                                        <span class="fa fa-<?php echo $value['icono'] ?>"> </span>
                                    </span>
                                    <?php
                                    $optiones = '';
                                    if (isset($catalogos[$value['id_catalogo']])) {//Valida que exista el catálogo en el formulario
                                        $optiones = dropdown_options($catalogos[$value['id_catalogo']], 'id_elemento_catalogo', 'nombre');
                                    }
                                    //Atributos del elemento html
                                    $atributos = array('name' => $value['nom_campo'],
                                        'class' => 'form-control',
                                        'placeholder' => $value['placeholder'],
//                                    "aria-describedby" => "sizing-addon2",
                                        'data-toggle' => $value['lb_campo'],
                                        'data-placement' => 'top',
                                        'title' => $value['lb_campo'],
                                    );
                                    //Valida que existan atrributos extra del elemento, de ser así se agregan
                                    if (!is_null($value['funcion_js']) and ! empty($value['funcion_js'])) {//Valida atributos extras
                                        try {//Cacha error en alguna configuración de atrributos en la base de datos en la tabla "ui.campos_formulario" campo "funcion_js"
                                            $json_decode_atrrib = (array) json_decode($value['funcion_js']); //Decodifica json y convierte a un array
                                            $atributos = array_merge($atributos, $json_decode_atrrib); //Agrega atributos extra del elemento por un marge
                                        } catch (Exception $exc) {
                                            pr($exc->getTraceAsString());
                                        }
                                    }

                                    echo $this->form_complete->create_element(
                                            array('id' => $value['nom_campo'], 'type' => $value['nom_tipo_campo'],
                                                'options' => $optiones,
                                                'first' => array('' => 'Selecciona opción'),
                                                'value' => '',
                                                'attributes' => $atributos,
                                            )
                                    );
                                    if (!empty($value['tooltip'])) {
                                        ?>
                                        <span class="input-group-addon" data-toggle="popover" title="Ayuda" data-content="<?php echo $value['tooltip']; ?>">
                                            <span class="fa fa-question-circle-o"> </span>
                                        </span>
                                        <?php
                                    }
                                    if ($value['nom_tipo_campo'] == 'file') {
                                        echo '<div class="input-group-btn">
                                    <button type="button" aria-expanded="false" class="btn btn-default browse">
                                        <span aria-hidden="true" class="fa fa-cloud-upload"> </span>
                                    </button>
                                  </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        echo form_error_format($value['nom_campo']); //agrega div para mostrar error
                        ?>
                    </div>
                    <?php
                    echo $row_print['row_end'];
                    $i ++;
                } else {//Genera un botón de guardado para envíar el formulario
                    //Atributos del elemento html
                    $atributos = array('name' => $value['nom_campo'],
                        'class' => 'form-control',
                        'placeholder' => $value['placeholder'],
                        'data-toggle' => $value['tooltip'],
                        'data-placement' => 'top',
//                        "data-formularioid"=>"form_actividad_1",
//                        "onclick"=>"funcion_guardar_actividad(this)",
                        'title' => $value['lb_campo'],
//                        'onclick' => $value['callback'],
//                        'onclick' => 'guardar(formulsrio)',
                    );
                    if (!is_null($value['funcion_js']) and ! empty($value['funcion_js'])) {//Valida atributos extras
                        try {//Cacha error en alguna configuración de atrributos en la base de datos en la tabla "ui.campos_formulario" campo "funcion_js"
                            $json_decode_atrrib = (array) json_decode($value['funcion_js']); //Decodifica json y convierte a un array
                            foreach ($json_decode_atrrib as $key_extra => $value_extra) {
                                $funcion = get_obtiene_cadena_pajar($value_extra); //Busca si el atributo necesita sustituir algún valor
                                if (isset(${$funcion})) {//Valida que exista un variable con el nombre solicitado
                                    $valor_variable = ${$funcion}; //Obtiene el valor de la variable solicitada
                                    $remplase = str_replace('$' . $funcion . '$', $valor_variable, $value_extra);
                                    $atributos[$key_extra] = $remplase;
                                } else {
                                    $atributos[$key_extra] = $value_extra; //Valor tal cual
                                }
                            }
                        } catch (Exception $exc) {
                            pr($exc->getTraceAsString());
                        }
                    }
//                    pr($atributos);
                    $boton_submit = $this->form_complete->create_element(
                            array('id' => $value['nom_campo'], 'type' => 'button',
                                'options' => $optiones,
                                'first' => array('' => 'Selecciona opción'),
                                'value' => $value['lb_campo'],
                                'attributes' => $atributos,
                            )
                    );
                }
            }
            echo $row_print['row_close']; //Aplica el cierre de etiqueta, si el "for" termina y no cerro alguna fila
            ?>

            <?php if (isset($componente_comprobante) and ! is_null($componente_comprobante)) {//Valida que exista comprobante ?>
                <div id="div_comprobante"  class="text-center">
                    <?php
                    echo '<br>';
                    echo $componente_comprobante;
                    ?>
                    <br><br>
                </div>
            <?php } ?>
            <hr>

            <div class="text-center">
                <?php
//                if (isset($pie_formularios)) {
//                    echo $pie_formularios;
//                }
                if (!is_null($boton_submit)) {//Valida diferente de null
                    echo '<br>';
                    echo $boton_submit;
                }
                ?>
            </div>
            <?php
            echo \form_close();
            ?>
        </div>

</div>


<script>
    $(document).ready(function () {
//        $('[data-toggle="popover"]').popover();
    });
</script>
