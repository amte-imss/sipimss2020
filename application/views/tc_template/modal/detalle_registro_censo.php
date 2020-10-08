
<?php
/** Nota: es importante que se estructure cada id de div de la siguiete manera
  *"div_id_censo_nombre_campo" y que se le asigne la classe como el "nombre del campo que tiene valor" y el predecesor "c_"
  *y que tengan la estructura de
 * <div> 
 *      <p> 
 *        <label>texto: </label>
 *        text
 *      </p>
 * </div>
 *  */
defined('BASEPATH') OR exit('No direct script access allowed');
$string_value = get_elementos_lenguaje(array(En_catalogo_textos::DETALLE_CENSO, En_catalogo_textos::COMPROBANTE));
$controlador = $this->uri->rsegment(1);
//pr($formulario_campos);

?>

<div class="row">
    <div id="notificaciones_modal_id" class="alert alert-info" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="col-md-12 col-sm-12 mb">
        <div class="col-md-6 goleft inf_extra_censo">
            <p><label class="pull-left bold-label"><?php echo $string_value['estado_validacion']; ?></label>
                <?php echo $detalle_censo['nombre_validacion']; ?>
            </p>
        </div>

        <?php
//        $this->load->library('Funciones_motor_formulario'); //Carga biblioteca
        $notificaciones = '';
        $i = 1;
        $row_print = array('row_end' => '', 'row_close' => '');
        foreach ($formulario_campos as $value) {
            if ($value['respuesta_valor'] != 'NULL') {
                if (!is_null($value['regla_notificacion']) and ! empty($value['regla_notificacion'])) {
                    $notificacion = (array) json_decode($value['regla_notificacion']);
                    //Invoca una función que es definida para ejecutar una notificacion, se define en la base de datos en la tabla ui.campos_formulario con campo  "regla_notificacion"
//                    $text_notificacion = $this->funciones_motor_formulario->{$notificacion['funcion']}($value['respuesta_valor']); //
                    $text_notificacion = NULL; //
                    $br_ = '';
                    if (!is_null($text_notificacion)) {
                        $notificaciones .= $br_ . $text_notificacion;
                        $br_ = '<br>';
                    }
                }
                //pr($value['nom_tipo_campo']);
                switch ($value['nom_tipo_campo']) {//Valida el tipo de campo
                    case 'file'://Tipo de campo file
                        ?>
                        <div  id="div_<?php echo $value['id_censo'] . '_' . $value['nombre_campo']; ?>" class="col-md-6 goleft <?php echo 'c_' . $value['nombre_campo'] ?>">
                            <p><label class="pull-left bold-label <?php echo $value['nombre_campo']; ?>"><?php echo $value['lb_campo']; ?></label>&nbsp;

                                <?php
                                $file = (isset($value['respuesta_valor'])) ? encrypt_base64($value['respuesta_valor']) : '';
                                echo '<a href="' . site_url($controlador . '/ver_archivo/' . $file) . '" target="_blank"><span class="fa fa-search"></span> ' . $string_value['ver_archivo'] . '</a><br>';
                                echo '<a href="' . site_url($controlador . '/descarga_archivo/' . $file) . '" target="_blank"><span class="fa fa-download"></span> ' . $string_value['descargar_archivo'] . '</a>';
//                echo $this->form_complete->create_element(array('id' => 'idc', 'type' => 'hidden', 'value' => $file));
                                ?>
                            </p>
                        </div>
                        <?php
                        break;
                    case 'date'://Tipo de campo file                        
                        $imprime_date = $value['respuesta_valor'];
                        if($value['nom_tipo_dato'] == 'date'){
                            $imprime_date = get_date_formato(str_replace('/', '-', $value['respuesta_valor']), 'd-m-Y');//reemplazar formato, de lo contrario regresa una fecha diferente
                        }
                        ?>                        
                        <div  id="div_<?php echo $value['id_censo'] . '_' . $value['nombre_campo']; ?>" class="col-md-6 goleft <?php echo 'c_' . $value['nombre_campo'] ?>">
                            <p><label class=" pull-left bold-label"><?php echo $value['lb_campo']; ?></label>&nbsp;
                                <?php echo  $imprime_date; ?>
                            </p>

                        </div>

                        <?php
                        break;
                        case 'custom':
                        ?>
                        <div  id="div_<?php echo $value['id_censo'] . '_' . $value['nombre_campo']; ?>" class="col-md-6 goleft <?php echo 'c_' . $value['nombre_campo'] ?>">
                            <p>
                                <label class="pull-left bold-label"><?php echo $value['lb_campo']; ?></label>&nbsp;
                                <?php $dAux = json_decode($value['respuesta_valor'],true); $print = "";?>
                                <?php foreach($dAux as $value){
                                    $print .= "<br>Nombre del curso: " . $value['nombre_curso'] . " Número de Años: " . $value['anio'] ;                                    
                                }
                                echo $print; 
                                ?>

                            </p>
                        </div>
                        <?php
                            
                        break;

                    default ://Todo lo demás diferente
                        ?>
                        <div  id="div_<?php echo $value['id_censo'] . '_' . $value['nombre_campo']; ?>" class="col-md-6 goleft <?php echo 'c_' . $value['nombre_campo'] ?>">
                            <p>
                                <label class="pull-left bold-label"><?php echo $value['lb_campo']; ?></label>&nbsp;
                                <?php echo $value['respuesta_valor']; ?>

                            </p>
                        </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>

<?php
if (!empty($propiedades_formulario) and ! is_null($propiedades_formulario[0]['ruta_file_js'])) {
    $json_js_form = (array) json_decode($propiedades_formulario[0]['ruta_file_js']);
    if ($json_js_form) {
        foreach ($json_js_form as $v) {
            echo js($v); //Agrega archivos JS del formulario
        }
    }
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        if(typeof properties.visible_textos_extras_table_seccion !== 'undefined' && properties.visible_textos_extras_table_seccion==0 || properties.visible_textos_extras_table_seccion=='0'){
            $(".inf_extra_censo").css("display", "none");
        }
        //                $('[data-toggle="popover"]').popover();
//                $('.datepicker').datepicker();
<?php if (empty($notificaciones)) { ?>
            $("#notificaciones_modal_id").remove();
<?php } else { ?>
            document.getElementById("notificaciones_modal_id").innerHTML += "<?php echo $notificaciones; ?>";

<?php } ?>

    });
</script>
