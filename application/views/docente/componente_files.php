
<?php
//pr($value);
$optiones = '';
$llave_catalogo = $value['id_campo'] . $value['id_catalogo'];

//                                    pr($llave_catalogo);
if (isset($catalogos[$llave_catalogo])) {//Valida que exista el catálogo en el formulario
    $optiones = dropdown_options($catalogos[$llave_catalogo], 'id_elemento_catalogo', 'label');
}
//pr($optiones);
$class_estatica = '';
$controlador = $this->uri->rsegment(1);
//                                $class_estatica = classe_adicional_tipo_dato($value['nom_tipo_dato'], $class_estatica);
//pr($value['nom_campo']);
//pr($value['display']);
//Atributos del elemento html
$atributos = array('name' => $value['nom_campo'],
    'class' => $class_estatica,
    'placeholder' => $value['placeholder'],
//                                    "aria-describedby" => "sizing-addon2",
    'data-toggle' => $value['lb_campo'],
    'data-placement' => 'top',
    'size' => '20',
    "data-displaycomponente" => $value['display'],
    'title' => $value['lb_campo'],
);
if ($bloquear) {//Bloquea campo por precarga
    $atributos['disabled'] = null;
}

//Valida que existan atrributos extra del elemento, de ser así se agregan 
if (!empty($key_callback)) {//Valida atributos extras
    try {//Cacha error en alguna configuración de atrributos en la base de datos en la tabla "ui.campos_formulario" campo "funcion_js"
        $json_decode_atrrib = (array) json_decode($value[$key_callback]); //Decodifica json y convierte a un array
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

//$valor_dato = '';
$ver_descargar_file = '';
$id_file = '';
//if (isset($value['valor']) and $value['valor'] != 'NULL' AND strlen($value['valor']) > 0) {
if (isset($value['valor']) and $value['valor'] != 'NULL') {
//    $valor_dato = $value['valor'];
    $id_file = base64_encode($value['valor']); //Codifica id de file
    $ver_descargar_file = '<a href="' . site_url($controlador . '/ver_archivo/' . $id_file) . '" target="_blank"><span class="fa fa-search"></span> ' . $string_values['ver_archivo'] . '</a><br>'
            . '<a href="' . site_url($controlador . '/descarga_archivo/' . $id_file) . '" target="_blank"><span class="fa fa-download"></span> ' . $string_values['descargar_archivo'] . '</a>';
}
//pr('aaaaaaaaaaaaaaaaaaaaaaaaaa');
//pr($id_file);
//pr('aaaaaaaaaaaaaaaaaaaaaaaaaa');
echo '<input id="' . $value['nom_campo'] . '" name="' . $value['nom_campo'] . '" value="' . $id_file . '" type="hidden">';
echo $this->form_complete->create_element(
        array('id' => $value['nom_campo'], 'type' => $value['nom_tipo_campo'],
            'options' => $optiones,
            'first' => array('' => 'Selecciona opción'),
            'value' => $id_file,
//                'value' => (isset($value['valor'])) ? $value['valor'] : '',
            'attributes' => $atributos,
        )
);
echo $ver_descargar_file;
?>
<?php
if (!empty($value['tooltip'])) {
    ?>
    <span class="input-group-addon cambia_manos" style="cursor: help" 
          data-toggle="popover" 
          data-trigger="hover"
          title="Ayuda" 
          data-content="<?php echo $value['tooltip']; ?>">
        <span class="fa fa-question-circle"> </span>
    </span>
    <?php
}
?>
