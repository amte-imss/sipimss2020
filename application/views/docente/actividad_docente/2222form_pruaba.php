
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($catalogos);
//pr($formulario_campos);
//pr($rutas_generales_js);
//exit();
//Abre formulario
//pr($boton_submit);
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

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript" ></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript" ></script>-->
<link href="<?php echo base_url('assets/css/template_sipimss/jquery-ui-1.7.2.custom.css'); ?>" rel="stylesheet" />

<div class="list-group">
    <div class="list-group-item">
        <div class="panel-body" onmousedown="elemento(event);">
            <?php
            echo form_open('', array('id' => 'form_actividad_' . $formulario)); //Pinta formulario
            echo $this->form_complete->create_element(array('id' => 'id_elementos_seccion', 'type' => 'hidden', 'value' => $id_elementos_seccion));
            echo $this->form_complete->create_element(array('id' => 'formulario', 'type' => 'hidden', 'value' => $formulario));
            echo $this->form_complete->create_element(array('id' => 'censo_regstro', 'type' => 'hidden', 'value' => (isset($censo_registro)) ? encrypt_base64($censo_registro) : ''));
            $i = 1;
            $row_print = array('row_end' => '', 'row_close' => '');
            foreach ($formulario_campos as $value) {
//                if ($value['nom_tipo_campo'] != 'submit') {//Submit lo considerará el
                $row_print = get_etiqueta_row($i, $value['nueva_linea']); //obtiene la linea de nuevo regitro
                $i = $row_print['indice']; //Retro del indice importante cuando se genera una nueva linea y toca cerrar la fila
                echo $row_print['row_begin'];


                if ($value['display'] == FALSE) {
                    $value['display'] = 'none';
                }
                ?>
                <div class="col-md-6" id="<?php echo 'div_' . $value['nom_campo'] ?>" style="display: <?php echo $value['display'] ?>" >
                    <div class="row">
                        <div class="col-md-4">
                            <label for=<?php echo $value['nom_campo']; ?> class="control-label">
                                <?php if (!empty(($value['rules']))) { //$value['obligatorio'] or ?>
                                    <b class="rojo">*</b>
                                <?php } ?>
                                <?php echo $value['lb_campo']; ?>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa fa-<?php echo $value['icono'] ?>"> </span>
                                </span>
                                <?php
                                $optiones = '';
                                if (isset($catalogos[$value['id_catalogo']])) {//Valida que exista el catálogo en el formulario
                                    $optiones = dropdown_options($catalogos[$value['id_catalogo']], 'id_elemento_catalogo', 'label');
                                }
                                //pr($optiones);
                                $class_estatica = 'form-control';
                                $class = classe_adicional_tipo_dato($value['nom_tipo_dato'], $class_estatica);
                                //Atributos del elemento html
                                $atributos = array('name' => $value['nom_campo'],
                                    'class' => $class,
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

                                echo $this->form_complete->create_element(
                                        array('id' => $value['nom_campo'], 'type' => $value['nom_tipo_campo'],
                                            'options' => $optiones,
                                            'first' => array('' => 'Selecciona opción'),
                                            'value' => (isset($value['valor']) and $value['valor'] != 'NULL') ? $value['valor'] : '',
//                'value' => (isset($value['valor'])) ? $value['valor'] : '',
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
            }
            echo $row_print['row_close']; //Aplica el cierre de etiqueta, si el "for" termina y no cerro alguna fila
            ?>

            <?php if (isset($componente_comprobante) and ! is_null($componente_comprobante)) {//Valida que exista comprobante ?>
                <div id="div_comprobante"  class="list-group-item text-center">
                    <?php
                    echo '<br>';
                    echo $componente_comprobante;
                    ?>
                </div>
            <?php } ?>

            <div class="list-group-item text-center">
                <!--echo '<br>';-->
                <div class="row">
                    <!--</div>-->

                    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                        <?php
                        if (isset($boton_cancelar)) {
                            echo $boton_cancelar;
                        }
                        ?>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 text-left ">
                        <?php
                        if (!is_null($boton_submit)) {//Valida diferente de null
                            echo $boton_submit;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            echo \form_close();
            ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
//        $('[data-toggle="popover"]').popover();
    });
</script>

<script type="text/javascript">
    $(function () {
        $('.fecha').datepicker({
            dateformat: 'yy'
        });

    });
</script>
