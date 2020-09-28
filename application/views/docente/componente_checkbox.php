<div class="input-group">
    
    <?php
    $class_estatica = '';
//                                $class_estatica = classe_adicional_tipo_dato($value['nom_tipo_dato'], $class_estatica);
    //Atributos del elemento html
    $atributos = array('name' => $value['nom_campo'],
        'class' => $class_estatica,
        'placeholder' => $value['placeholder'],
        'data-toggle' => $value['lb_campo'],
        'data-placement' => 'top',
        "data-displaycomponente" => $value['display'],
        'title' => $value['lb_campo'],
//        'data-catalogo' => base64_encode($value['id_catalogo']),
        'data-catalogo' => $value['id_catalogo'],
        'data-ruta' => $controlador,
        'data-reglacatalogo' => $value['clave_regla_dependencia_catalogo'],
    );
//    pr($value);
    if ($bloquear) {//Bloquea campo por precarga
        $atributos['disabled'] = null;
    }

    //pr($optiones);
    //Valida que existan atrributos extra del elemento, de ser así se agregan
    if (!empty($key_callback)) {//Valida atributos extras
        // pr($key_callback);
        try {//Cacha error en alguna configuración de atrributos en la base de datos en la tabla "ui.campos_formulario" campo "funcion_js"
            $json_decode_atrrib = (array) json_decode($value[$funcion_callback]); //Decodifica json y convierte a un array
//            pr($json_decode_atrrib);
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
    /**
     * Aplica reglas de dependencia a campos
     */
//    $json = json_decode($value['campos_dependientes']); //campos_dependientes
    $json = json_decode($value['campos_dependientes'], true);
    if (!empty($json['campos'])) {//Valida que campos dependientes sea diferente de vacio
//            $atributos['data-dependientes'] = $campos_dependientes; //Forma un array con los campos dependientes
        $atributos['onchange'] = "actualiza_campos_dependientes(this);"; //Forma un array con los campos dependientes
        $atributos['class'] = $atributos['class'] . ' ctr_dependientes';
        //Agraga en la variabe hasttable "array_padres_dependientes" el nombre del campo padre como llave y como valor los campos que dependen, es decir, que se van a recargar dependiendo de lo que contenga
//            pr($value['campos_dependientes']);
        ?>
        <script>
            array_padres_dependientes["<?php echo $value['nom_campo']; ?>"] = <?php echo $value['campos_dependientes']; ?>;
        </script>
        <?php
    }
    //    pr($value_result);
    $optiones = ''; //Opciones, (diseño actual, no lo maneja)
    $llave_catalogo = $value['id_campo'] . $value['id_catalogo']; //Llave para obtener el catálogo disponible

    $create_element = array('id' => $value['nom_campo'], 'type' => $value['nom_tipo_campo'],       
        'value' => $value_result,
        //                'value' => (isset($value['valor'])) ? $value['valor'] : '',
        'attributes' => $atributos,
    );
    if (isset($catalogos[$llave_catalogo])) {//Valida que exista el catálogo en el formulario
        $atributos_catalogo = array(
            'id' => 'id_elemento_catalogo', //Identificador del catalogo a mostrar
            'label' => 'label', //Texto a mostrar del catálogo
            'llave_valido' => 'is_validado', //Texto a mostrar del catálogo
            
        );
        $create_element['atributos_catalogo'] = $atributos_catalogo; //Agrega atributos extra para obtener opciones de catálogo
//        $optiones = dropdown_options($catalogos[$llave_catalogo], 'id_elemento_catalogo', 'label');
    }
//    pr($create_element);
    echo $this->form_complete->create_element($create_element);
    if (!empty($value['tooltip'])) {
        ?>
        <span class="input-group-addon cambia_manos" style="cursor: help"
              data-toggle="popover"
              data-trigger="hover"
              title="Ayuda"
              data-content="<?php echo $value['tooltip']; ?>">
            <span class="fa fa-question-circle"> </span>
        </span>
        <!--
                                            <a href="#" data-toggle="popover" title="Ayuda"
                                               data-content="<?php // echo $value['tooltip'];                                                  ?>">
                                                <span class="fa fa-question-circle-o"></span>
                                            </a>-->
        <?php
    }
    ?>
</div>
