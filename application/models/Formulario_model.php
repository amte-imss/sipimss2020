<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: LEAS
 * @version: 1.0
 * @desc: Clase modelo de consultas para de formulario general
 * */
class Formulario_model extends MY_Model {

    const __defaul = 0,
            TIPO_CAMPO_TEXT = 1,
            TIPO_CAMPO_NUMERIC = 2,
            TIPO_CAMPO_DROPDOWN = 3,
            TIPO_CAMPO_DROPDOWN_OTRO = 4,
            TIPO_CAMPO_FILE = 5,
            TIPO_CAMPO_RADIO_BUTTON = 6,
            TIPO_CAMPO_HIDDEN = 7,
            TIPO_CAMPO_CHECKBOX = 8,
            TIPO_CAMPO_DATE = 9,
            TIPO_CUSTOM = 11

    ;
    const KEY_OTHER_CAMPO = "keyother_";
    const KEY_OTHER_CAMPO_AUXILIAR = "_auxiliar";

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * @fecha 20/04/20717
     * @author HILDA, LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * ejemp2:  array('f.id_elemento_seccion' => $elemento_seccion)
     * @return type
     */
    public function get_campos_formulario($params = null, $id_censo = null) {
        if (!is_null($params) and isset($params['f.id_elemento_seccion'])) {
            if (!is_numeric($params['f.id_elemento_seccion'])) {
                return array();
            }
        }
        /* select * from ui.formulario
          inner join ui.campos_formulario
          on ui.campos_formulario.id_formulario=ui.formulario.id_formulario
          where ui.formulario.id_formulario=1 */
        $select = array(
            //formularioo campo
            'cf.id_campos_formulario', 'cf.tooltip', 'cf.placeholder', 'cf.display', 'cf.nueva_linea', 'cf.attributes_extra'
            , 'cf.regla_notificacion', 'cf.excepciones_opciones', 'cf.campos_dependientes', 'cf.is_linea_completa'
            //formulario
            , 'f.id_formulario', 'f.id_elemento_seccion', 'f.label lbl_formulario', 'f.nombre nom_formulario', 'f.ruta_file_js'
            //campos
            , 'c.id_campo',
            '(case when cf.label_personalizado is not null then cf.label_personalizado else c.label end) lb_campo',
            'c.nombre nom_campo', 'c.icono'
            //tipo campo
            , 'tc.id_tipo_campo', 'tc.nombre nom_tipo_campo'
            //tipo dato
            , 'tdc.id_tipo_dato', 'tdc.nombre nom_tipo_dato'
            //propiedades
            , 'cf.orden', 'cf.rules', 'cf.clave_regla_dependencia_catalogo'
            //collback campo_formulario
            , 'cbf.id_callback id_callback_form', 'cbf.ruta_js ruta_js_form', 'cbf.funcion_js funcion_js_form', 'cbf.tipo_evento tipo_evento_form'
            //collback campo
            , 'c.id_callback', 'cb.ruta_js', 'cb.funcion_js', 'cb.tipo_evento'
            //catálogo
            , 'cat.id_catalogo', 'cat.nombre nom_catalogo', 'cat.editable', 'cf.is_precarga',
                //Respuesta de formulario
//            , 'CIF.valor'
        );
//        if (isset($params['condicion'])) {
//            foreach ($params['condicion'] as $scape => $datos) {
//                foreach ($datos as $llave => $valor) {
//                    $this->db->{$scape}($llave, $valor);
//                }
//            }
//        }

        $array_where = array('cf.activo' => true, 'f.activo' => TRUE, 'c.activo' => TRUE, 'tc.activo' => TRUE);
        if (!is_null($id_censo) and ! empty($id_censo)) {
            $select[] = 'CIF.valor'; //Agrega que obtenga el valor de censo info o respuesta del formulario
            $select[] = 'CIF.is_carga_sistema is_precarga_registro_sistema'; //Agrega que obtenga el valor de censo info o respuesta del formulario
            $select[] = 'cc.id_censo'; //Agrega que obtenga el valor de censo info o respuesta del formulario
//            $select[] = 'cc.formulario_registros'; //Agrega que obtenga el valor de censo info o respuesta del formulario
//            $select[] = '(select nombre_fisico from censo.files where censo.files.id_file::text= "CIF"."valor") nombre_fisico'; //Agrega que obtenga el valor de censo info o respuesta del formulario
//            $select[] = '(select ruta from censo.files where censo.files.id_file::text= "CIF"."valor") ruta_file'; //Agrega que obtenga el valor de censo info o respuesta del formulario
//            pr($select);
            //Agrega un join para obtener información concreta del censo
            $this->db->join('censo.censo_info CIF', 'CIF.id_campos_formulario = cf.id_campos_formulario', 'left');
            $this->db->join('censo.censo cc', 'cc.id_censo = CIF.id_censo', 'left');
            $array_where['CIF.id_censo'] = $id_censo; //Agrga la condición del censo
        }
        $this->db->select($select); //Agrega select
//        pr($array_where);
        //Agrega implicitamente si son activos los campos de formulario
        if (!is_null($params) and ! empty($params)) {
            $array_where = array_merge($array_where, $params);
        }
        foreach ($array_where as $key => $value) {
            $this->db->where($key, $value);
        }


        $this->db->join('ui.formulario f ', 'f.id_formulario = cf.id_formulario');
        $this->db->join('ui.campo c', 'c.id_campo = cf.id_campo');
        $this->db->join('ui.tipo_dato_campos tdc', 'tdc.id_tipo_dato = c.id_tipo_dato', 'left');
        $this->db->join('ui.tipo_campo tc', 'tc.id_tipo_campo = c.id_tipo_campo');
        $this->db->join('ui.callback cbf', 'cbf.id_callback = cf.id_callback', 'left');
        $this->db->join('ui.callback cb', 'cb.id_callback = c.id_callback', 'left');
        $this->db->join('catalogo.catalogo cat', 'cat.id_catalogo = cf.id_catalogo', 'left');
        $this->db->order_by('cf.orden', 'asc');
        $resultado = $this->db->get('ui.campos_formulario cf');

       // pr($this->db->last_query());
        return $resultado->result_array();
        return null;
    }

    /**
     * @fecha 21/04/20717
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de los
     * catálogos implementados en el formulario de una sección en específico
     * ejemplo: array('condicion' => $valor);
     * @return type
     */
    public function get_catalogos_formulario($params = null) {
        if (!is_null($params) AND isset($params['f.id_elemento_seccion']) and ! is_numeric($params['f.id_elemento_seccion'])) {
            return array();
        }

        $select = array(
            //formularioo campo
            'cf.id_campos_formulario', 'cf.id_formulario', 'cf.id_campo'
            //tipo campo
            , 'tc.id_tipo_campo', 'tc.nombre nom_tipo_campo', 'cf.excepciones_opciones', 'cf.reglas_catalogos'//, 'campos_dependientes'
            //catálogo
            , 'cat.id_catalogo', 'cat.nombre nom_catalogo', 'cat.editable', 'c.nombre nombre_campo'
        );
        if (!empty($params)) {
            $this->db->select($select);
            //Agrega implicitamente si son activos los campos de formulario
            $params = array_merge($params, array('cf.activo' => true, 'f.activo' => TRUE, 'c.activo' => TRUE,
                'tc.activo' => TRUE,
                //Indica que obtenga los valores de catálogo que no es null
                'cat.id_catalogo IS NOT NULL' => null
            ));
            foreach ($params as $key => $value) {
                $this->db->where($key, $value);
            }

            $this->db->join('ui.formulario f ', 'f.id_formulario = cf.id_formulario');
            $this->db->join('ui.campo c', 'c.id_campo = cf.id_campo');
            $this->db->join('ui.tipo_campo tc', 'tc.id_tipo_campo = c.id_tipo_campo');
            $this->db->join('catalogo.catalogo cat', 'cat.id_catalogo = cf.id_catalogo', 'left');
            $this->db->order_by('cf.orden', 'asc');
            $resultado = $this->db->get('ui.campos_formulario cf');
            //            pr($this->db->last_query());
            return $resultado->result_array();
        }
        return null;
    }

//    public function get_datos_usuario_actividad_registro($params = null) {
//        /* select * from ui.formulario
//          inner join ui.campos_formulario
//          on ui.campos_formulario.id_formulario=ui.formulario.id_formulario
//          where ui.formulario.id_formulario=1 */
//
//        $resultado = array();
//
//        if (isset($params['id_censo']) && !empty($params['id_censo'])) {
//            $this->db->where('ui.formulario.id_formulario', $params['id_formulario']);
//            $this->db->join('ui.campos_formulario', 'ui.campos_formulario.id_formulario=ui.formulario.id_formulario');
//            $query = $this->db->get(' ui.formulario');
//        }
//
//        return $resultado->result_array();
//    }

    /**
     * @fecha 25/04/2007
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * @return type
     */
    public function insert_datos_formulario($datos_post_formulario = null, $deficion_formulario = null, &$datos_files = null) {
//        pr(json_encode($datos_post_formulario));
//        pr(json_encode($deficion_formulario));
//        pr(json_encode($datos_files));
//        exit();
//        return array('tp_msg' => En_tpmsg::INFO, 'mensaje' => 'Entra a censo registro');
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GUARDAR_ACTUALIZAR));
        $respuesta = array('tp_msg' => En_tpmsg::WARNING, 'mensaje' => $string_value['no_registros_guardar']);
        //Valida que los datos de post y definición del formulario lleguén o que no sean vacios
        if ((is_null($datos_post_formulario) or empty($datos_post_formulario)) and ( is_null($deficion_formulario) || empty($deficion_formulario))) {//
            return $respuesta; //Mensaje de no se puede agregar formulario, intentelo más tarde
        }
        //Valida que censo registro no exita, ya que el metodo sólo inserta un nuevo registro
//        pr($datos_post_formulario);
        if (isset($datos_post_formulario['censo_regstro']) and empty($datos_post_formulario['censo_regstro'])) {
            $this->db->trans_begin(); //Inicia la transacción
            //Guarda los archivos del formulario que llegan por post y que fueron almacenados fisicamente
            $conjunto_archivos = $this->save_files_generales($datos_post_formulario, $datos_files);
            if ($conjunto_archivos == 1) {//Valida que todos los archivos se almacenaron correctamente
                $censo_info = $this->asocia_datos_formulario($datos_post_formulario, $deficion_formulario, 0);
                $guarda_elements_otros = TRUE; //Indica que los elementos catálogo otros nuevos, se almacenaron correctamente(hasta este punto no es seguro que existan elementos)
                //***Guardar opciones de elemento catálogo nuevas
                if (!empty($censo_info['array_nuevo_elemento_catalogo'])) {//Valida que no se encuentré vacio
                    $this->load->model('Catalogo_model', 'cmcat');
                    foreach ($censo_info['array_nuevo_elemento_catalogo'] as $key_name_campo => $value_desc_catalogo) {
                        //Busca el elemento de catálogo para que coinsida con un registro
                        $busqueda_ec = $this->cmcat->get_busca_opciones_catalogo($value_desc_catalogo['catalogo_id'], $value_desc_catalogo['valor_elemento_catalogo'], TRUE);

                        if (!empty($busqueda_ec)) {//Valida que contenga alguna coincidencia
                            $censo_info['array_datos'][$key_name_campo]['valor'] = $busqueda_ec[0]['id_elemento_catalogo']; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario (censo.censo_info)
                            $censo_info['json_censo_info'][$key_name_campo] = $busqueda_ec[0]['id_elemento_catalogo']; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario en json (censo.cnso.formulario_registros)
                        } else {//No existe coinsidencia y, guardará el registro elemento catálogo nuevo
                            $array_ec = array(
                                'nombre' => '_',
                                'id_catalogo' => $value_desc_catalogo['catalogo_id'],
                                'label' => $value_desc_catalogo['valor_elemento_catalogo'],
                                'nivel' => 1,
                                'is_validado' => FALSE,
                            );
                            $this->db->insert('catalogo.elementos_catalogos', $array_ec); //Almacena usuario
                            $id_elemento_catalogo = $this->db->insert_id(); //Obtiene el identificador
                            if ($this->db->trans_status() === FALSE and $id_elemento_catalogo > 0) {//Valida que se inserto el archvo success
                                $guarda_elements_otros = FALSE; //Modifica la variable, para informar que no fue posible almacenar el elemento catálogo
                                break; //Sale de la rutina
                            }
                            $censo_info['array_datos'][$key_name_campo]['valor'] = $id_elemento_catalogo; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario (censo.censo_info)
                            $censo_info['json_censo_info'][$key_name_campo] = $id_elemento_catalogo; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario en json (censo.cnso.formulario_registros)
                        }
                    }
                }
                if ($guarda_elements_otros) {
                    $array_censo = array(
                        'id_docente' => $datos_post_formulario['id_docente'],
                        'id_validacion_registro' => $datos_post_formulario['id_validacion_registro'],
                        'is_carga_sistema' => $datos_post_formulario['is_carga_sistema'],
                        'formulario_registros' => json_encode($censo_info['json_censo_info']), //Json datos del formuario
                        'id_formulario' => $datos_post_formulario['formulario']
                    );
                    $this->db->insert('censo.censo', $array_censo); //Almacena usuario
                    $id_censo = $this->db->insert_id();
                    if ($this->db->trans_status() === FALSE and $id_censo > 0) {//Valida que se inserto el archvo success
                        $this->db->trans_rollback();
                        $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
                    } else {
                        if (empty($censo_info['array_datos'])) {
                            $this->db->trans_rollback();
                            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_registros_guardar']);
                        }
//                            $this->db->trans_rollback();
//                            pr($censo_info['array_datos']);
//                            exit();
                        foreach ($censo_info['array_datos'] as $value_censo_info) {
                            $value_censo_info['id_censo'] = $id_censo; //Asigna el valor de censo
                            $this->db->insert('censo.censo_info', $value_censo_info); //Almacena usuario
                            $id_censo_info = $this->db->insert_id();
                            if ($this->db->trans_status() === FALSE and $id_censo_info < 1) {//Valida que se inserto el archvo success
                                $this->db->trans_rollback();
                                return array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
                            }
                        }
                        $this->db->trans_commit();
                        $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['succes_insert']);
                        $respuesta['id_censo'] = $id_censo;
                    }
                } else {//No se guardaron los archivos correctamente
                    $this->db->trans_rollback();
                    $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
                }
            } else {//No se guardaron los archivos correctamente
                $this->db->trans_rollback();
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardo_file']);
            }
            return $respuesta;
        }
    }

    private function save_files_generales(&$datos_post_formulario, &$data_files_generales) {
        try {
//            exit();
            $trans_status = 1;
            if (!empty($data_files_generales)) {//Valida que los datos de archivo no esten vacios
                foreach ($data_files_generales as $key_file => &$value_files) {
                    if ($value_files['size_ff']) {//Valida que se haya cargado un archivo para el registro, o que sea valido
                        $ruta_file_tmp = str_replace('.', '', $value_files['ruta_actual']); //Elimina la ruta con punto del archivo

                        $array_datos_file_tmp = array('nombre_fisico' => $value_files['nombre_fisico_actual'], 'ruta' => $ruta_file_tmp);
                        //Valida que el archivo a guardar sea uno nuevo o una actualización, es decir
                        if (isset($value_files['id_file']) AND is_numeric($value_files['id_file'])) {//Actualiza un file
                            $this->db->where('censo.files.id_file', $value_files['id_file']); //Id censo
                            $this->db->update('censo.files', $array_datos_file_tmp);

                            if ($this->db->trans_status() === FALSE) {//valida que la transacción se haya ejecutado correctamente, de lo contrario, sale del ciclo for
                                $trans_status = 0; //Valida correcta actualiación del archivo file
                                break; //Sale del ciclo for
                            }
                        } else {//Inserta un nuevo file
                            $this->db->insert('censo.files', $array_datos_file_tmp); //Almacena archivo
                            $id_file_tmp = $this->db->insert_id(); //Obtiene id de file insertado
                            $datos_post_formulario[$key_file] = $id_file_tmp; //Le agrega el id del archivo nuevo a la variable post, para que se almacene
                            $value_files['id_file'] = $id_file_tmp;
                            if ($this->db->trans_status() === FALSE and $id_file_tmp < 1) {//Falla en la transacción para guardar archivo
                                $trans_status = 0; //Valida correcta actualiación del archivo file
                                break; //Sale del ciclo for
                            }
                        }
                    }
                }
            }
        } catch (Exception $exc) {
            $trans_status = 0;
            echo $exc->getTraceAsString();
        }
//            pr($data_files_generales);
//        pr($datos_post_formulario);
//        exit();
        return $trans_status;
    }

    /**
     *
     * @fecha 03/05/2007
     * @author LEAS
     * @param type $datos_post_formulario
     * @param type $deficion_formulario
     * @param type $nombe_file Nombre del archivo nuevo cargado, si el nombre es
     * null, no actualizará el archivo, de lo contrario
     * @return string
     */
    public function update_datos_formulario($datos_post_formulario, $datos_formulario, $elemento_seccion, $id_censo, &$datos_files = null, $detalle_censo = null) {
//        pr(json_encode($datos_post_formulario));
//        pr(json_encode($datos_formulario));
//        pr(json_encode($elemento_seccion));
//        pr(json_encode($id_censo));
//        pr(json_encode($id_file_comprobante));
//        pr(json_encode($nombe_file));
//        pr(json_encode($ruta_archivo));
//        pr(json_encode($datos_files));
//        pr(json_encode($detalle_censo));
//        exit();
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GUARDAR_ACTUALIZAR));
        if (!is_null($elemento_seccion) and ! is_numeric($elemento_seccion)) {
            return array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
        }
        if (!is_null($id_censo) and ! is_numeric($id_censo)) {
            return array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
        }

        $respuesta = array('tp_msg' => En_tpmsg::WARNING, 'mensaje' => $string_value['no_registros_guardar']);
        //Valida que los datos de post y definición del formulario lleguén o que no sean vacios
        $this->db->trans_begin(); //Inicia la transacción
        //Inicia guardado de archivos generales

        $operacion_files_generales = $this->save_files_generales($datos_post_formulario, $datos_files);

        if ($operacion_files_generales) {//Valida que los archivos generales se hayan guardado correctamente
            //Obtiene un array con las siguientes llaves   "array_datos", "array_nuevo_elemento_catalogo" y "json_censo_info"
            $result_datos = $this->asocia_datos_formulario_update($datos_post_formulario, $datos_formulario, $elemento_seccion, $id_censo, $detalle_censo);
            $guarda_elements_otros = TRUE; //Indica que los elementos catálogo otros nuevos, se almacenaron correctamente(hasta este punto no es seguro que existan elementos)
            //***Guardar opciones de elemento catálogo nuevas
            if (!empty($result_datos['array_nuevo_elemento_catalogo'])) {//Valida que no se encuentré vacio
                $this->load->model('Catalogo_model', 'cmcat');
                foreach ($result_datos['array_nuevo_elemento_catalogo'] as $key_name_campo => $value_desc_catalogo) {
                    //Busca el elemento de catálogo para que coinsida con un registro
//                    $busqueda_ec = $this->cmcat->get_busca_opciones_catalogo($value_desc_catalogo['catalogo_id'], $value_desc_catalogo['valor_elemento_catalogo'], TRUE);
                    if (!empty($busqueda_ec)) {//Valida que contenga alguna coincidencia
                        $result_datos['array_datos'][$key_name_campo]['datos']['valor'] = $busqueda_ec[0]['id_elemento_catalogo']; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario (censo.censo_info)
                        $result_datos['json_censo_info'][$key_name_campo] = $busqueda_ec[0]['id_elemento_catalogo']; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario en json (censo.cnso.formulario_registros)
                    } else {//No existe coinsidencia y, guardará el registro elemento catálogo nuevo
                        $array_ec = array(
                            'nombre' => '_',
                            'id_catalogo' => $value_desc_catalogo['catalogo_id'],
                            'label' => $value_desc_catalogo['valor_elemento_catalogo'],
                            'nivel' => 1,
                            'is_validado' => FALSE,
                        );
                        $this->db->insert('catalogo.elementos_catalogos', $array_ec); //Almacena usuario
                        $id_elemento_catalogo = $this->db->insert_id(); //Obtiene el identificador
                        if ($this->db->trans_status() === FALSE and $id_elemento_catalogo > 0) {//Valida que se inserto el archvo success
                            $guarda_elements_otros = FALSE; //Modifica la variable, para informar que no fue posible almacenar el elemento catálogo
                            break; //Sale de la rutina
                        }
                        $result_datos['array_datos'][$key_name_campo]['datos']['valor'] = $id_elemento_catalogo; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario (censo.censo_info)
                        $result_datos['json_censo_info'][$key_name_campo] = $id_elemento_catalogo; //Asocia el valor del campo o del elemento catalogo encontrado al valor del formulario en json (censo.cnso.formulario_registros)
                    }
                }
            }
            if ($guarda_elements_otros) {
                $array_datos_censo = array(//Array de actualizacion de censo para el folio
                    'formulario_registros' => json_encode($result_datos['json_censo_info']),
                );
                $this->db->where('censo.censo.id_censo', $id_censo); //Id censo
                $this->db->update('censo.censo', $array_datos_censo);
                if ($this->db->trans_status() === FALSE) {//Valida que se inserto el archvo success
                    $this->db->trans_rollback();
                    $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardo_file']);
                } else {
                    /* Obtiene los valores de los nuevos datos para actualizarlos */
//                    $this->db->where('censo.censo_info.id_censo', $id_censo); //Id censo
//                    $this->db->update('censo.censo_info', $array_datos_censo);
                    foreach ($result_datos['array_datos'] as $key_datos => $value) {//Ejecuta guardado de datos
                        if (isset($value['condicion'])) {
                            foreach ($value['condicion'] as $condicion) {
                                $this->db->where($condicion); //Id censo
                            }
                        }
                        $this->db->{$value['funcion']}('censo.censo_info', $value['datos']);
                    }
                    $this->db->trans_commit();
                    return array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['succes_insert']);
                }
            } else {//Algun elemento de catálogo otro no fue posible que se gusrdará
                $this->db->trans_rollback();
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardar']);
            }
        } else {
            $this->db->trans_rollback();
            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['no_guardo_file']);
        }
        return $respuesta;
    }

    /**
     * @fecha 25/04/2007
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * @return type
     */
    function asocia_datos_formulario($datos_post_formulario = null, $deficion_formulario = null, $id_censo) {
        $array_datos = array();
//        if (is_null($datos_post_formulario) and is_null($deficion_formulario)) {
//            return $array_datos;
//        }
//        pr($deficion_formulario);
        $catalogos_nuevos = array();
        $json_censo_info = array();
        if (!empty($deficion_formulario)) {
            foreach ($deficion_formulario as $value) {
//            if (isset($datos_post_formulario[$value['nom_campo']]) and ! empty($datos_post_formulario[$value['nom_campo']])) {
                if (isset($datos_post_formulario[$value['nom_campo']])) {
                    $dato_valor = (empty($datos_post_formulario[$value['nom_campo']])) ? 'NULL' : $datos_post_formulario[$value['nom_campo']];
                    $array_datos[$value['nom_campo']] = array(
                        'id_campos_formulario' => $value['id_campos_formulario'],
                        'valor' => $dato_valor,
                        'id_censo' => $id_censo,
                    );
                    //Valida que sea un catálogo con capacidad de agregar un nuevo registro del tipo "dropdown_otro"
                    switch($value['id_tipo_campo']){
                        case Formulario_model::TIPO_CAMPO_DROPDOWN_OTRO:
                            if (strpos($dato_valor, Formulario_model::KEY_OTHER_CAMPO) !== FALSE) {
                                $prop_post_otro = explode("_", $dato_valor);
                                if (!empty($prop_post_otro) and isset($datos_post_formulario[$value['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR])) {//Valida que el json no este vacio y que existan las llaves solicitadas
        //                            if (!empty($json_decode_catalogo_otro) and isset($json_decode_catalogo_otro[Formulario_model::KEY_OTHER_CAMPO]) and isset($json_decode_catalogo_otro['value_other'])) {//Valida que el json no este vacio y que existan las llaves solicitadas
        //                                $forma_palabra = preg_replace('/( ){2,}/u', ' ', str_replace("_", " ", $json_decode_catalogo_otro['value_other']));
                                    $forma_palabra = $datos_post_formulario[$value['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR];
                                    $catalogos_nuevos[$value['nom_campo']] = array(
                                        'catalogo_id' => $prop_post_otro[1],
                                        'valor_elemento_catalogo' => $forma_palabra,
                                    );
                                }
                            }
                        break;
                        case Formulario_model::TIPO_CUSTOM://ya que se envia en base 64
                            //pr($array_datos[$value['nom_campo']]);
                            $array_datos[$value['nom_campo']]['valor'] = base64_decode($dato_valor);
                            //pr($array_datos[$value['nom_campo']]);
                            
                        break;

                    }
                    
                    $json_censo_info[$value['nom_campo']] = $dato_valor; //Guarda el valor que tendrá el JSON del registro censo.censo
                }else{
                    switch($value['id_tipo_campo']){
                        case Formulario_model::TIPO_CAMPO_CHECKBOX:
                            $dato_valor = 0;
                            $array_datos[$value['nom_campo']] = array(
                                'id_campos_formulario' => $value['id_campos_formulario'],
                                'valor' => $dato_valor,
                                'id_censo' => $id_censo,
                            );
                            $json_censo_info[$value['nom_campo']] = $dato_valor; //Guarda el valor que tendrá el JSON del registro censo.censo
                        break;
                    }
                }
            }
            $json_censo_info['id_formulario'] = $deficion_formulario[0]['id_formulario'];
        }
        $array_result['array_datos'] = $array_datos; //Datos del formulario
        $array_result['array_nuevo_elemento_catalogo'] = $catalogos_nuevos; //Agrega los nuevos elementos para agregar a elementos de catálogo
        $array_result['json_censo_info'] = $json_censo_info; //Todos los datos del formulario guardados en un solo registro
        return $array_result;
    }

    /**
     * @fecha 25/04/2007
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * @return
     * $array_result['array_datos'] = $array_datos; //Datos del formulario con condiciones separadas por campo
     * $array_result['array_nuevo_elemento_catalogo'] = $catalogos_nuevos; //Agrega los nuevos elementos para agregar a elementos de catálogo
     * $array_result['json_censo_info']; //Todos los campos del formulario juntos valor y nombre
     */
    function asocia_datos_formulario_update($datos_post_formulario = null, $deficion_formulario = null, $elemento_seccion, $id_censo, $detalle_censo) {
        $param = array('f.id_elemento_seccion' => $elemento_seccion); //Obtiene todos los campos del formulario
        $campos_formulario = $this->get_campos_formulario($param); //Obtiene todos los campos del formulario
        $datos_guardados_deficion_formulario = array();
        $catalogos_nuevos = array();
        $json_censo_info = array();
//        pr($detalle_censo);
//        pr($deficion_formulario);
//        exit();
        foreach ($deficion_formulario as $value_df) {//Asociación de llaves
            $datos_guardados_deficion_formulario[$value_df['nom_campo']] = $value_df;
//            //Valida que sea un catalogo con capacidad de agregar un nuevo registro del tipo "dropdown_otro"
//            if (Formulario_model::TIPO_CAMPO_DROPDOWN_OTRO == $value_df['id_tipo_campo']) {
//                $str_post_data = $datos_post_formulario[$value_df['nom_campo']];
//                /**
//                 * Valida que sea un nuevo registro, tiene el formato de json
//                 * con las llaves "key_other" = id del catalogo en base64  y "value_other" = valor nuevo del elemento catálogo
//                 */
//                if (strpos($str_post_data, 'key_other') != FALSE) {
//                    $json_decode_catalogo_otro = (array) json_decode($str_post_data);
//                    if (!empty($json_decode_catalogo_otro) and isset($json_decode_catalogo_otro['key_other']) and isset($json_decode_catalogo_otro['value_other'])) {//Valida que el json no este vacio y que existan las llaves solicitadas
//                        $forma_palabra = preg_replace('/( ){2,}/u', ' ', str_replace("_", " ", $json_decode_catalogo_otro['value_other']));
//
//                        $catalogos_nuevos[$value_df['nom_campo']] = array(
//                            'catalogo_id' => base64_decode($json_decode_catalogo_otro['key_other']),
//                            'valor_elemento_catalogo' => $forma_palabra,
//                        );
//                    }
//                }
//            }
                switch($value_df['id_tipo_campo']){
                    case Formulario_model::TIPO_CAMPO_DROPDOWN_OTRO:
                        $str_post_data = $datos_post_formulario[$value_df['nom_campo']];
                                if (strpos($str_post_data, Formulario_model::KEY_OTHER_CAMPO) !== FALSE) {
                                    $prop_post_otro = explode("_", $str_post_data);
                                    //pr($prop_post_otro);
                                    if (!empty($prop_post_otro) and isset($datos_post_formulario[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR])) {//Valida que el json no este vacio y que existan las llaves solicitadas
                //                            if (!empty($json_decode_catalogo_otro) and isset($json_decode_catalogo_otro[Formulario_model::KEY_OTHER_CAMPO]) and isset($json_decode_catalogo_otro['value_other'])) {//Valida que el json no este vacio y que existan las llaves solicitadas
                //                                $forma_palabra = preg_replace('/( ){2,}/u', ' ', str_replace("_", " ", $json_decode_catalogo_otro['value_other']));
                                        $forma_palabra = $datos_post_formulario[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR];
                                        $catalogos_nuevos[$value_df['nom_campo']] = array(
                //                            'catalogo_id' => base64_decode($prop_post_otro[1]),//Antes el catalogo se codificaba, actualmente ya no
                                            'catalogo_id' => $prop_post_otro[1],
                                            'valor_elemento_catalogo' => $forma_palabra,
                                        );
                                    }
                                }
                    break;
                    case Formulario_model::TIPO_CUSTOM://ya que se envia en base 64
                        $datos_post_formulario[$value_df['nom_campo']] = base64_decode($datos_post_formulario[$value_df['nom_campo']]); 
                        
                    break;
                    case Formulario_model::TIPO_CAMPO_CHECKBOX:                    
                        if(!isset($datos_post_formulario[$value_df['nom_campo']])){
                            $datos_post_formulario[$value_df['nom_campo']] = 0;
                        }                                                                
                    break;

                }
            //Valida que sea un catálogo con capacidad de agregar un nuevo registro del tipo "dropdown_otro"
            
        }
//        unset($datos_guardados_deficion_formulario['mod']);
//        unset($datos_post_formulario['ins_avala']);
//        pr($campos_formulario);
//        pr($campos_formulario);
        $array_datos = array();
//        if (is_null($datos_post_formulario) and is_null($deficion_formulario)) {
//            return $array_datos;
//        }
//        pr($deficion_formulario);
        if (!empty($campos_formulario)) {

            foreach ($campos_formulario as $val_df) {//Recorre todos los campos del formulario
//            if (isset($datos_post_formulario[$value['nom_campo']]) and ! empty($datos_post_formulario[$value['nom_campo']])) {
                if (isset($datos_post_formulario[$val_df['nom_campo']]) and isset($datos_guardados_deficion_formulario[$val_df['nom_campo']])) {
                    $dato_valor = (strlen($datos_post_formulario[$val_df['nom_campo']]) < 1) ? 'NULL' : $datos_post_formulario[$val_df['nom_campo']];
                    //si existe en la tabla y viene de post, se actualizara el valor campo
                    $array_datos[$val_df['nom_campo']] = array(
                        'funcion' => 'update',
                        'condicion' => array(
                            'id_campos_formulario = ' . $datos_guardados_deficion_formulario[$val_df['nom_campo']]['id_campos_formulario'],
                            'id_censo =' . $id_censo),
                        'datos' => array(
                            'valor' => $dato_valor,
                        ),
                        'tabla' => 'censo.censo_info',
                    );
                } else if (isset($datos_post_formulario[$val_df['nom_campo']])) {//Solo existe en el post, inserta
                    $dato_valor = (strlen($datos_post_formulario[$val_df['nom_campo']]) < 1) ? 'NULL' : $datos_post_formulario[$val_df['nom_campo']];
                    $array_datos[$val_df['nom_campo']] = array(
                        'funcion' => 'insert',
                        'datos' => array(
                            'id_campos_formulario' => $value_df['id_campos_formulario'],
                            'valor' => $dato_valor,
                            'id_censo' => $id_censo,
                        ),
                        'tabla' => 'censo.censo_info',
                    );
                } else if (isset($datos_guardados_deficion_formulario[$val_df['nom_campo']])) {//únicamente existe en la definición del formulario, pone leyenda null el campo
                    $dato_valor = 'NULL';
                    $detalle_censo_decodifica_json = json_decode($detalle_censo['formulario_registros']); //Decódifica registros del censo

                    if ($detalle_censo['formulario_registros'] !== FALSE) {
                        $detalle_censo_decodifica_json = (array) $detalle_censo_decodifica_json;
                        if (isset($detalle_censo_decodifica_json[$val_df['nom_campo']])) {//Si existe la información del campo, la extrae para conservarla
                            $dato_valor = $detalle_censo_decodifica_json[$val_df['nom_campo']];
                        }
                    }
                    $array_datos[$val_df['nom_campo']] = array(
                        'funcion' => 'update',
                        'condicion' => array(
                            'id_campos_formulario = ' . $datos_guardados_deficion_formulario[$val_df['nom_campo']]['id_campos_formulario'],
                            'id_censo =' . $id_censo),
                        'datos' => array(
                            'valor' => $dato_valor,
                        ),
                    );
                }
                $json_censo_info[$val_df['nom_campo']] = $dato_valor; //Guarda el valor que tendrá el JSON
            }
            $json_censo_info['id_formulario'] = $campos_formulario[0]['id_formulario']; //Agrga el id del formulario
        }
//        pr($array_datos);

        $array_result['array_datos'] = $array_datos; //Datos del formulario
        $array_result['array_nuevo_elemento_catalogo'] = $catalogos_nuevos; //Agrega los nuevos elementos para agregar a elementos de catálogo
        $array_result['json_censo_info'] = $json_censo_info; //Todos los datos del formulario guardados en un solo registro
        return $array_result;
    }

    /**
     * @author LEAS
     * @fecha 27/06/2017
     * @param type $id_docente filtro de docente identficador
     * @param type $id_seccion filtro de seccion identificador
     * @param type $id_censo filtro de identificador del registro del censo
     * @param type $id_elemento_seccion filtro de elemento de una sección, trae
     * el elemento o caracteristica de la subsección
     * @return array(
     *      'id_elemento_seccion'=>'',//identificador de la sección
     *      'label'=>''//Nombre de la subsección indicando el árbol completo de subsecciones
     * );
     */
    public function get_sub_secciones($id_docente = null, $id_seccion = null, $id_censo = null, $id_elemento_seccion = null) {
//        pr($id_docente); //1
//        pr($id_seccion);
//        pr($id_censo); //2
//        pr($id_elemento_seccion);

        if (!is_null($id_elemento_seccion) and ! is_numeric($id_elemento_seccion)) {
            return array();
        }
        if (!is_null($id_docente) and ! is_numeric($id_docente)) {
            return array();
        }
        if (!is_null($id_seccion) and ! is_numeric($id_seccion)) {
            return array();
        }
        if (!is_null($id_censo) and ! is_numeric($id_censo)) {
            return array();
        }
        $select = array(
            'ES.id_elemento_seccion',
            'catalogo.get_arbol_secciones_pinta_padres("ES"."id_elemento_seccion") AS label',
        );
//            "ES.label",
        $this->db->distinct();
        $this->db->select($select);
        $this->db->where('CC.id_docente', $id_docente);
        if (!is_null($id_seccion)) {
            $this->db->where('S.id_seccion', $id_seccion);
        }
        if (!is_null($id_censo)) {
            $this->db->where('CIF.id_censo', $id_censo);
        }
        if (!is_null($id_elemento_seccion)) {
            $this->db->where('ES.id_elemento_seccion', $id_elemento_seccion);
        }
        $this->db->join('censo.censo_info CIF', 'CC.id_censo = CIF.id_censo');
        $this->db->join('ui.campos_formulario CF', 'CIF.id_campos_formulario = CF.id_campos_formulario');
        $this->db->join('ui.formulario F', 'CF.id_formulario = F.id_formulario');
        $this->db->join('catalogo.elementos_seccion ES', 'F.id_elemento_seccion = ES.id_elemento_seccion');
        $this->db->join('catalogo.secciones S', 'S.id_seccion = ES.id_seccion');
        $this->db->join('ui.campo CM', 'CM.id_campo = CF.id_campo');

        $this->db->order_by('label', 'asc');
//        $this->db->order_by('ES.id_elemento_seccion', 'asc');
        $resultado = $this->db->get('censo.censo CC');

//        pr($this->db->last_query());
//        pr($resultado->result_array());
        return $resultado->result_array();
    }

    /**
     * @fecha 27/04/2017
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * Se agregaron los campos de validacion y de carga_sistema(HPTZ)
     * @return type
     */
    public function get_datos_actividad_docente($id_docente = null, $id_seccion = null, $id_censo = null, $id_elemento_seccion = null) {
//        pr($id_docente); //1
//        pr($id_seccion);
//        pr($id_censo); //2
//        pr($id_elemento_seccion);
        if (!is_null($id_elemento_seccion) and ! is_numeric($id_elemento_seccion)) {
            return array();
        }
        if (!is_null($id_docente) and ! is_numeric($id_docente)) {
            return array();
        }
        if (!is_null($id_seccion) and ! is_numeric($id_seccion)) {
            return array();
        }
        if (!is_null($id_censo) and ! is_numeric($id_censo)) {
            return array();
        }

        $select = array(
            'CC.id_censo', 'CC.id_validacion_registro', 'UVR.nombre nombre_validacion',
            'CC.is_carga_sistema', 'CC.fecha',
            'S.id_seccion', 'S.nombre nom_seccion', 'S.label lbl_seccion',
            'catalogo.get_arbol_secciones_pinta_padres("ES"."id_elemento_seccion") arbol_secciones',
//            '(select nombre_fisico from censo.files where censo.files.id_file::text= "CIF"."valor") as nombre_fisico',
//            '(select ruta from censo.files where censo.files.id_file::text= "CIF"."valor") as ruta_file_general',
            'ES.id_elemento_seccion',
            'ES.label nom_elemento_seccion',
            'TC.nombre nom_tipo_campo',
            'TDC.nombre nom_tipo_dato',
            'F.id_formulario', 'CF.campos_dependientes',
            'F.label lb_formulario', 'F.ruta_file_js',
            'CF.orden',
            'CF.regla_notificacion',
            'CF.mostrar_datatable', 'CF.grupo_informacion_campo',
            'CM.id_campo', 'CM.nombre nombre_campo',
            'CM.label lb_campo', 'CF.nueva_linea',
            'CASE
		WHEN ("ELCAT"."id_elemento_catalogo" IS NOT NULL) THEN "ELCAT"."label"
		ELSE "CIF"."valor"
            END AS respuesta_valor'
        );

        $this->db->select($select);
        //Agrega implicitamente si son activos los campos de formulario
//            $params = array_merge($params, array('cf.activo' => true, 'f.activo' => TRUE, 'c.activo' => TRUE, 'tc.activo' => TRUE));
        $this->db->where('CC.id_docente', $id_docente);
        if (!is_null($id_seccion)) {
            $this->db->where('S.id_seccion', $id_seccion);
        }
        if (!is_null($id_censo)) {
            $this->db->where('CIF.id_censo', $id_censo);
        }
        if (!is_null($id_elemento_seccion)) {
            $this->db->where('ES.id_elemento_seccion', $id_elemento_seccion);
        }

        $this->db->join('ui.validacion_registro UVR', 'UVR.id_validacion_registro = CC.id_validacion_registro');
        $this->db->join('censo.censo_info CIF', 'CC.id_censo = CIF.id_censo', 'left');
        $this->db->join('ui.campos_formulario CF', 'CIF.id_campos_formulario = CF.id_campos_formulario', 'left');
        $this->db->join('ui.formulario F', 'CF.id_formulario = F.id_formulario', 'left');
        $this->db->join('catalogo.elementos_seccion ES', 'F.id_elemento_seccion = ES.id_elemento_seccion', 'left');
        $this->db->join('catalogo.secciones S', 'S.id_seccion = ES.id_seccion', 'left');
        $this->db->join('ui.campo CM', 'CM.id_campo = CF.id_campo', 'left');
        $this->db->join('catalogo.catalogo CT', 'CT.id_catalogo = CF.id_catalogo', 'left');
        $this->db->join('ui.tipo_dato_campos TDC', 'TDC.id_tipo_dato = CM.id_tipo_dato', 'left');
        $this->db->join('ui.tipo_campo TC', 'TC.id_tipo_campo = CM.id_tipo_campo', 'left');
        $this->db->join("catalogo.elementos_catalogos ELCAT", 'ELCAT.id_catalogo = CT.id_catalogo and CAST("ELCAT"."id_elemento_catalogo" as text) = "CIF"."valor"', 'left');

        if (is_null($id_censo)) {
            $order_by = array('S.orden', 'CC.id_censo', 'ES.id_elemento_seccion',
                'F.id_formulario', 'CF.orden');
        } else {
            $order_by = array('CF.orden');
        }
        foreach ($order_by as $value) {
            $this->db->order_by($value, 'asc');
        }
        $resultado = $this->db->get('censo.censo CC');
        return $resultado->result_array();
    }

    /**
     *
     * @author LEAS
     * @fecha 27/04/2017
     * @param type $id_docente identificador del usuario docente
     * @param type $id_seccion Identificación de la seccion, es decir,
     * formación docente, actividad docente, investigación, etc.
     * @param type $id_censo Identificador del registro de censo, puede ser null
     * @param type $id_elemento_seccion elementos de una subsección o formulario, datos con dicha estructura
     * @return type cross de cada registro del censo con el siguiente formato en un array,
     * la primera llave es
     * "datos_actividad_docente"
     * "catalogo_secciones_actividad_docente"
     * "campos_mostrar_datatable"
     * Array
      *(
      *[datos_actividad_docente] => Array
      *(
      *[0] => Array
      *(
      [id_elemento_seccion] => 32
      [nom_elemento_seccion] => Becas laborales
      [id_seccion] => 4
      [lbl_seccion] => Becas
      [lb_formulario] => Becas > Becas laborales
      [id_formulario] => 7
      [folio] => 78787878
      [id_censo] => 90
      [is_carga_sistema] =>
      [id_validacion_registro] => 1
      [nombre_validacion] => Registro usuario
      [id_file] => 90
      [nombre_fisico] => 3342165412_1494603383
      [ruta] => /assets/us/uploads/3342165412/
      [mostrar_datatable] =>
      [fecha_inicio] => 2017-05-04
      [fecha_termino] => 2017-05-05
      [clase_beca] => Con goce de salario
      [proceso_educativo] => Formacion de Directivos
      [beca_interrumpida] => No
      [causa_beca_interrumpida] => NULL
      [tipo_comprobante] => Dictamen de beca
      )

      )

      [catalogo_secciones_actividad_docente] => Array
      (
      [0] => Array
      (
      [id_elemento_seccion] => 32
      [label] => Becas laborales
      )

      )

      [campos_mostrar_datatable] => Array
      (
      [fecha_inicio] => Array
      (
      [label] => Fecha de inicio
      [nom_tipo_campo] => date
      )

      [fecha_termino] => Array
      (
      [label] => Fecha de término
      [nom_tipo_campo] => date
      )
      )

      )
     *
     */
    public function get_cross_datos_actividad_docente($id_docente = null, $id_seccion = null, $id_censo = null, $id_elemento_seccion = null) {
        $datos_docente_actividad = $this->get_datos_actividad_docente($id_docente, $id_seccion, $id_censo, $id_elemento_seccion);
        //Obtiene todos los registros elemento sección sin repetir
        $get_sub_secciones = $this->get_sub_secciones($id_docente, $id_seccion, $id_censo);
        $array_result = array();
        $array_general = array();
        $array_campos = array();
        $array_campos_mostrar_datatable = array();
        //pr($datos_docente_actividad);
        foreach ($datos_docente_actividad as $value) {
            $llave_cross = $value['id_censo'] . '-' . $value['id_formulario'] . '-' . $value['id_elemento_seccion'];
            if (!isset($array_general[$llave_cross])) {
                $array_general[$llave_cross] = array(
                    'id_elemento_seccion' => $value['id_elemento_seccion'],
                    'nom_elemento_seccion' => $value['nom_elemento_seccion'],
                    'id_seccion' => $value['id_seccion'],
                    'lbl_seccion' => $value['lbl_seccion'],
                    'lb_formulario' => $value['lb_formulario'],
                    'id_formulario' => $value['id_formulario'],
                    'id_censo' => encrypt_base64($value['id_censo']),
                    'is_carga_sistema' => $value['is_carga_sistema'],
                    'id_validacion_registro' => $value['id_validacion_registro'],
                    'nombre_validacion' => $value['nombre_validacion'],
                    'acciones' => $this->acciones($value['is_carga_sistema'], $value['id_validacion_registro']),
                );
            }
            if ($value['mostrar_datatable']) {
                //pr($value['nombre_campo'] .' -> ' . $value['id_elemento_seccion']);
                if(!isset($array_campos_mostrar_datatable[$value['nombre_campo']])){

                    $array_campos_mostrar_datatable[$value['nombre_campo']] = array(
                        'label' => $value['lb_campo'],
                        'nom_tipo_campo' => $value['nom_tipo_campo'],
                        'id_elemento_seccion' => $value['id_elemento_seccion'],
                        'nombre' => $value['nombre_campo'],
                    );
                    $array_campos_mostrar_datatable[$value['nombre_campo']]['ids_elemento_seccion'][$value['id_elemento_seccion']] = $value['id_elemento_seccion'];
                }else{
                    $array_campos_mostrar_datatable[$value['nombre_campo']]['ids_elemento_seccion'][$value['id_elemento_seccion']] = $value['id_elemento_seccion'];
                }

            }
            $array_general[$llave_cross]['mostrar_datatable'] = $value['mostrar_datatable'];
            
            switch ($value['nom_tipo_campo']) {//Formatos y casos especiales
                case 'file':
                    $array_campos[$llave_cross][$value['nombre_campo']] = encrypt_base64($value['respuesta_valor']);
                case 'date':
                    $val_aux = $value['respuesta_valor'];
                    if($value['nom_tipo_dato'] == 'date'){
                        $val_aux = get_date_formato($value['respuesta_valor']);
                    }
                    $array_campos[$llave_cross][$value['nombre_campo']] = $val_aux;
                    break;
                default :
                    $array_campos[$llave_cross][$value['nombre_campo']] = $value['respuesta_valor'];
            }
        }
        //pr($array_campos_mostrar_datatable);
        //exit();
        foreach ($array_campos as $key_c => $value_c) {//Hace merge los redsultados generales
            $array_result[] = array_merge($array_general[$key_c], $value_c);
        }
        $resultado['datos_actividad_docente'] = $array_result;
        $resultado['catalogo_secciones_actividad_docente'] = $get_sub_secciones;
        $resultado['campos_mostrar_datatable'] = $array_campos_mostrar_datatable;
        return $resultado;
    }

    private function acciones($id_validacion_registro, $is_carga_sistema) {
        $result['editar'] = false;
        $result['eliminar'] = false;
        $result['ver'] = true;
        if (!empty($is_carga_sistema) && is_bool($is_carga_sistema) === FALSE) {
            //NO SE PUEDE BORRAR
            if ($id_validacion_registro != En_estado_validacion_registro::REGISTRO_USUARIO) {
                $result['editar'] = true;
                $result['eliminar'] = true;
            }
        }
        return $result;
    }

    /**
     * @fecha 27/04/2017
     * @author LEAS
     * @param type $params parametros condiciones para obtener información de
     * definición de campos del formulario de una sección
     * ejemplo: array('condicion' => $valor);
     * @return type
     */
    public function get_cross_datos_actividad_docente_completo($id_docente) {
        if (!is_numeric($id_docente)) {
            return array();
        }
        $datos_docente_actividad = $this->get_datos_actividad_docente($id_docente);
        $array_result = array();
        $array_general = array();
        $array_campos = array();
        $config_seccion = $this->config->item('config_secciones'); //Carga configuraciones de la actividad
        $this->load->model('Secciones_model', 'csm'); //Carga configuraciones de la actividad
        $formulario_file_js = [];
        foreach ($datos_docente_actividad as $value) {
            //Obtiene ramas del árbol
            $top_tree = 0; //Indica el nivel de profundidad default 0, es decir toda la ramá
            if (isset($config_seccion[$value['id_seccion']])) {
                $top_tree = $config_seccion[$value['id_seccion']]['nivel_profundidad']; //Indica el nivel de profundidad
            }
            $llave_cross = $value['id_censo'] . $value['id_formulario'] . $value['id_elemento_seccion'];
//            $nombre_seccion = $value['nom_elemento_seccion'];
            if (!isset($array_general[$llave_cross])) {
//                $nombre_seccion = $this->csm->get_elemento_seccion_ramas($value['id_elemento_seccion'], $top_tree);
                $array_general[$llave_cross] = array(
                    'id_elemento_seccion' => $value['id_elemento_seccion'],
//                    'nom_elemento_seccion' => $nombre_seccion,
                    'nom_elemento_seccion' => $value['arbol_secciones'],
                    'id_seccion' => $value['id_seccion'],
                    'lbl_seccion' => $value['lbl_seccion'],
                    'lb_formulario' => $value['lb_formulario'],
                    'id_formulario' => $value['id_formulario'],
                    'id_censo' => $value['id_censo'],
                    'nombre_fisico' => isset($value['nombre_fisico'])?$value['nombre_fisico']:'',
                    'ruta' => isset($value['ruta'])?$value['ruta']:'',
                );
            }
            $array_campos[$llave_cross][$value['nombre_campo']] = array(
                'lb_campo' => $value['lb_campo'],
                'respuesta_valor' => $value['respuesta_valor'],
                'nueva_linea' => $value['nueva_linea'],
                'nom_tipo_campo' => $value['nom_tipo_campo'], //Agrega el tipo de campo
                'grupo_informacion_campo' => $value['grupo_informacion_campo'], //Agrega el tipo de campo
            );
        }
        foreach ($array_campos as $key_c => $value_c) {//Hace merge los redsultados generales
            $array_general[$key_c]['campos'] = $value_c; //Le asigna los datos de formulario en array independiente
            $array_general[$key_c]['formulario_file_js'] = $formulario_file_js; //Files js que aplican a todos los registros
            $array_result[] = $array_general[$key_c]; //Crea el registro con indice incremental
        }
//        pr($array_result);
//        pr($array_campos);
//        pr($array_general);
        return $array_result;
    }

    public function get_detalle_censo($id_censo = null) {
        if (is_null($id_censo)) {
            return null;
        }
        if (!is_numeric($id_censo)) {
            return null;
        }
        $select = array(
            //Datos censo
            'cc.id_censo', 'cc.is_carga_sistema', 'cc.id_formulario'
            //validacion
            , 'vr.id_validacion_registro', 'vr.nombre nombre_validacion', 'cc.formulario_registros'
        );

        $this->db->select($select);
        //Agrega implicitamente si son activos los campos de formulario
//            $params = array_merge($params, array('cf.activo' => true, 'f.activo' => TRUE, 'c.activo' => TRUE, 'tc.activo' => TRUE));
        $this->db->where('cc.id_censo', $id_censo);

        $this->db->join('ui.validacion_registro vr', 'vr.id_validacion_registro = cc.id_validacion_registro');

        $resultado = $this->db->get('censo.censo cc');

//            pr($this->db->last_query());
        return $resultado->result_array();
    }

    public function delete_censo($id_censo = null) {
        if (is_null($id_censo)) {
            return null;
        }

        $resultado = array('success' => FALSE);
        $this->db->trans_begin(); // inicio de transaccion

        /* Operaciones */
        $this->db->where('id_censo', $id_censo);
        $this->db->delete('censo.censo_info');

        $this->db->where('id_censo', $id_censo);
        $this->db->delete('censo.censo');



        $this->db->trans_complete();
        if ($this->db->trans_status() == TRUE) { // condición para ver si la transaccion se efectuara correctamente
            $this->db->trans_commit(); // si la transacción es correcta retornar TRUE
            $resultado['success'] = TRUE;
            return $resultado;
        } else {
            $this->db->trans_rollback(); // si la transacción no es correcta retornar FALSE
            $resultado['success'] = FALSE;
            return $resultado;
        }
    }

    /**
     *
     * @author LEAS
     * @fecha 04/05/2017
     * @param type $folio
     * @return boolean
     */
    public function get_valida_folio($folio) {
        if ($folio == '') {
            return FALSE;
        }
        $this->db->where('folio', $folio);
        $query = $this->db->get('censo.censo');
        $rowcount = $query->num_rows();
//            pr($rowcount);
//            pr($this->db->last_query());
        return $rowcount > 0;
    }

    /**
     *
     * @param type $folio
     * @return boolean
     */

    /**
     *
     * @author LEAS
     * @fecha 14/03/2018
     * @param type $filtros filtros
     * @param string $select datos de la consulta
     * @param type $ejecucion ejecuta "result_array" para obtener la información de la consulta;
     * ejecuta "num_rows" para contar el total de resultados
     * @return type
     */
    public function get_valida_folio_comprobante($filtros = null, $select = null, $ejecucion = "result_array") {
        if (!is_null($filtros)) {
            $this->db->where($filtros);
        }
        if (is_null($select)) {
            $select = array("c.id_censo", "ci.valor", "cm.nombre", "s.nombre", "s.label", "s.url", "c.id_docente");
        }
        $this->db->select($select);
        $this->db->join('censo.censo_info ci', 'ci.id_censo = c.id_censo');
        $this->db->join('ui.campos_formulario cf', 'cf.id_campos_formulario = ci.id_campos_formulario');
        $this->db->join('ui.campo cm', 'cm.id_campo = cf.id_campo');
        $this->db->join('ui.formulario f', 'f.id_formulario = cf.id_formulario');
        $this->db->join('catalogo.elementos_seccion es', 'es.id_elemento_seccion = f.id_elemento_seccion');
        $this->db->join('catalogo.secciones s', 's.id_seccion = es.id_seccion');


        $query = $this->db->get('censo.censo c');
        $result_array = $query->{$ejecucion}();
//            pr($rowcount);
//            pr($this->db->last_query());
        return $result_array;
    }

    /**
     *
     * @author HPTZ
     * @fecha 03/07/2017
     * @param type $id_formulario identificador del formulario a consultar
     * @param type $id_censo si es diferente de nulo, obtiene el formulario asociado a tal registro
     * para obtenere después el formulario, de esta manera el parametro $id_formulario quedará
     * inutilizable
     * @return boolean
     */
    public function get_formulario($id_formulario, $id_censo = null) {
        if (!is_null($id_censo)) {
            $this->db->where('id_censo', $id_censo);
            $query = $this->db->get('censo.censo');
            if (!empty($query->result_array())) {
                $json_decode = json_decode($query->result_array()[0]['formulario_registros']);
                if (!is_null($json_decode)) {
                    $json_decode = (array) $json_decode;
                    $id_formulario = $json_decode['id_formulario'];
                }
            }
        }
        $this->db->where('id_formulario', $id_formulario);
        $query = $this->db->get('ui.formulario');
        return $query->result_array();
    }

    /**
     * Devuelve los datos de la tabla ui.campos
     * @author CPMS
     * @date 21/07/2017
     * @param id_campo Default null. Si es nulo regresa toda la info de la tabla,
     * si no devuelve solo los datos del campo con dicho id
     * @param label Default false. Si es true regresa solo el valor del atributo label
     * de los registros, si no devuelve todos los atributos de la tabla
     * @return Devuelve los datos de la tab
     */
    public function get_campos($id_campo = null, $label = 0, $con_nombre = false) {
        if ($con_nombre) {
            $this->db->select(array(
                'id_campo', 'concat(label,$$ - $$,nombre) "label"'
            ));
        } else if ($label) {
            $this->db->select('id_campo,label');
        }
        if (!is_null($id_campo)) {
            $this->db->where('id_campo', $id_campo);
        }
        $this->db->order_by("label asc");
        $query = $this->db->get('ui.campo');
        $resultado = $query->result_array();

        //pr($resultado);

        $final = array();
        foreach ($resultado as $key => $value) {
            $id = $value['id_campo'];
            unset($value['id_campo']);
            $final[$id] = $value;
        }
        return $final;
    }

    /**
     * Devuelve el label de cada campo asociados a un formulario
     * @author CPMS
     * @date 21/07/2017
     * @param id_formulario
     * @return array - Arreglo con la info de los campos asociados
     *
     * select id_campos_formulario, c.label
     * from ui.campos_formulario as cf
     * join ui.campo as c
     * on cf.id_campo = c.id_campo
     * where id_formulario = $id_formulario;
     */
    public function get_label_campos_asociados($id_formulario) {
        $this->db->select('id_campos_formulario, c.id_campo, (case when cf.label_personalizado is not null then cf.label_personalizado else c.label end) "label",c.nombre');
        $this->db->from('ui.campos_formulario as cf');
        $this->db->join('ui.campo as c', 'c.id_campo = cf.id_campo');
        $this->db->where('id_formulario', $id_formulario);

        $query = $this->db->get();
        $resultado = $query->result_array();


        $final = array();
        foreach ($resultado as $key => $value) {
            $id_campo_form = $value['id_campos_formulario'];
            unset($value['id_campos_formulario']);
            //$final[$id_campo_form] = $value['label'];
            $final[$id_campo_form] = $value;
        }
        //pr($final);
        return $final;
    }

    /**
     * Devuelve la información de los formularios
     * @author CPMS
     * @date 25/07/2017
     * @param condiciones - array donde key es el nombre de la columna
     * y value es el valor con el que se aplicara la sentencia where en la consulta
     * @return array - Areglo con la info obtenida de la consulta
     */
    public function get_formularios($condiciones = array()) {
        if (!empty($condiciones)) {
            foreach ($condiciones as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $query = $this->db->get('ui.formulario');
        $query = $query->result_array();
//        pr($query);
        $resultado = array();
        foreach ($query as $key => $value) {
            $id = $value['id_formulario'];
            unset($value['id_formulario']);
            $resultado[$id] = $value;
        }

        return $resultado;
    }

    /**
     * Desactiva todos los formularios asociados al mismo elemento de seccion
     * @author CPMS
     * @date 25/07/2017
     * @param id_elemento_seccion
     * @param formularios - Excepcion de
     * @return boolean - True si se pudo desactivar todos los formularios
     * */
    public function desactivar_formularios($id_elemento_seccion, $formularios = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $this->db->set('activo', 'false');
        $this->db->where('id_elemento_seccion', $id_elemento_seccion);
        if (!empty($formularios)) {
            foreach ($formularios as $key => $value) {
                $this->db->where($key . '!=', $value);
            }
        }
        $this->db->update('ui.formulario');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * Inserta un registro a la tabla campos_formulario
     * @author CPMS
     * @date 21/07/2017
     * @param values - Datos en un arreglo donde
     * @return boolean - True si se pudo insertar el registro en la base
     * false en otro caso.
     */
    public function create_campo($values = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $insert = $values;

        $this->db->insert('ui.campos_formulario', $insert);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * Actualiza los datos de un registro en la tabla campos_formulario
     * @author CPMS
     * @date 27/07/2017
     * @param values - Datos en un arreglo
     * @return boolea, - True si se pudo actualizar el registro en la base
     * false en otro caso.
     */
    public function update_campo($values = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $update = $values;

        $this->db->where('id_campos_formulario', $update['id_campos_formulario']);
        $this->db->update('ui.campos_formulario', $update);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * Devuelve las reglas como arreglo de reglas de validacion para
     * codeigniter.
     * @author CPMS
     * @date 26/07/2017
     * @param id_campo - id del campo al que se le aplicaran las reglas
     * @param rules - Arreglo con los id de las reglas
     * @return array - array ('field'=> '', 'label' => '', 'rules' => '|')
     */
    public function set_rule_json($id_campo = null, $rules = array()) {
        $this->db->select('nombre,label');
        $this->db->where('id_campo', $id_campo);
        $campo = $this->db->get('ui.campo');

        $campo = $campo->result_array();
        $field = $campo[0]['nombre'];
        $label = $campo[0]['label'];

        $reglas = '';
        foreach ($rules as $key => $value) {
            $this->db->select('rule');
            $this->db->where('id_rules_validaciones', $value);
            $regla = $this->db->get('catalogo.lista_rules_validaciones');

            $regla = $regla->result_array();
            $reglas = $reglas . $regla[0]['rule'] . '|';
        }
        $reglas = substr($reglas, 0, -1);

        $resultado = array(
            'field' => $field,
            'label' => $label,
            'rules' => $reglas
        );

        return $resultado;
    }

    /**
     * Convierte el json de rule que esta guardado en la base
     * en id_rules_validaciones
     * @author CPMS
     * @date 26/07/2017
     * @param id_campo_form
     * @return array
     */
    public function from_rule_json($id_campo_form = null) {
        $this->db->select('rules');
        $this->db->where('id_campos_formulario', $id_campo_form);
        $regla = $this->db->get('ui.campos_formulario');

        $regla = $regla->result_array();
        $regla = $regla[0];

        $resultado = array();
        $arr = json_decode($regla['rules']);
        if (!is_null($arr)) {
            $resultado = explode('|', $arr->{'rules'});
        }

        //pr($resultado);

        $rules = array();

        foreach ($resultado as $key => $value) {
            $this->db->select('id_rules_validaciones');
            $this->db->where('rule', $value);
            $nombres = $this->db->get('catalogo.lista_rules_validaciones');
            $nombres = $nombres->result_array();
            if (!empty($nombres[0])) {
                array_push($rules, $nombres[0]['id_rules_validaciones']);
            }
        }


        return $rules;
    }

    /**
     * Devuelve la info guardada en la tabla campos_formulario
     * @author CPMS
     * @date 26/07/2017
     * @param array con los id de los campos_formulario
     * @return array
     */
    public function get_campos_formulario_info($arr = array()) {
        $this->db->where_in('id_campos_formulario', $arr);
        $res = $this->db->get('ui.campos_formulario')->result_array();
//        $res = $res->result_array();
//        pr($res);
        $campos = array();
        foreach ($res as $value) {
            $id_campo = $value['id_campos_formulario'];
            unset($value['id_campos_formulario']);
            $campos[$id_campo] = $value;
        }

        return $campos;
    }

    public function update_formulario($id_form, $values = array()) {
        $salida = false;

        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $update = $values;

        $this->db->where('id_formulario', $id_form);
        $this->db->update('ui.formulario', $update);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    /**
     * @author LEAS
     * @fecha 25/08/2017
     * @param type $id_docente identificador del docente
     * @return type todos los registros almacenados del docente
     */
    public function get_registros_censo($id_docente = null) {
        if (is_null($id_docente) and ! is_numeric($id_docente)) {//Si es nullo y no es numerico retorna array vacio
            return array();
        }
        $this->db->reset_query();
        $this->db->where('id_docente', $id_docente);
        $res = $this->db->get('censo.censo');
        return $res->result_array();
    }

    /**
     *
     * @param type $id_docente identificador del docente
     * @return type
     */
    public function get_files_js_formularios($id_docente = null) {
        $select = 'distinct json_array_elements(f.ruta_file_js::json)::text as elementos';
        $this->db->select($select);
        $this->db->where('c.id_docente', $id_docente, FALSE);
        $this->db->where('ruta_file_js is not null', null);
        $this->db->where('length(ruta_file_js)>0',null);
        $this->db->join('censo.censo c', 'c.formulario_registros->>\'id_formulario\' = f.id_formulario::text', 'inner', FALSE);
        $res = $this->db->get('ui.formulario f');
//        pr($this->db->last_query());
        return $res->result_array();
    }

    /**
     * Copia la configuración de campos de un formulario origen
     * @author Christian Garcia
     * @return array @mixed [result=>boolean, msg =>string]
     * */
    public function copiar_formulario($id_destino, $id_origen) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();
        $query = "insert into ui.campos_formulario(id_formulario, id_campo, id_catalogo, orden, rules, id_callback,
            tooltip, placeholder, display, activo, css, excepciones_opciones,
            nueva_linea, attributes_extra, reglas_catalogos, regla_notificacion,
            mostrar_datatable, campos_dependientes, is_precarga, label_personalizado)
            select {$id_destino}, id_campo, id_catalogo, orden, rules, id_callback,
            tooltip, placeholder, display, activo, css, excepciones_opciones,
            nueva_linea, attributes_extra, reglas_catalogos, regla_notificacion,
            mostrar_datatable, campos_dependientes, is_precarga, label_personalizado
            from ui.campos_formulario
            where id_formulario = {$id_origen} and id_campo not in (select id_campo from ui.campos_formulario where id_formulario = {$id_destino})";
        $this->db->query($query);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = 'Campos copiados con éxito';
            $resultado['result'] = TRUE;
        }
        $this->db->reset_query();
        return $resultado;
    }

    public function get_config_campos_formulario($params = []) {
        $this->db->flush_cache();
        $this->db->reset_query();

        $select = array(
            'A.id_campos_formulario', 'B.id_campo',
            //'B.label campo', 'A.id_formulario', 'B.nombre'
            "concat(\"B\".\"label\", '(', \"B\".\"nombre\", ')') campo", 'A.id_formulario', 'B.nombre'
        );
        $this->db->select($select);
        $this->db->join('ui.campo B', 'B.id_campo = A.id_campo', 'inner');
        $this->db->where('id_formulario', $params['id_formulario']);
        if (isset($params['id_campo_formulario']) && $params['id_campo_formulario'] != '') {
            $this->db->where('id_campo_formulario!=', $params['id_campo_formulario']);
        }
        $campos_formulario = $this->db->get('ui.campos_formulario A')->result_array();
        return $campos_formulario;
    }

    public function almacena_registro_workflow($id_censo, $id_linea_tiempo)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $datos = array(
            'id_censo' => $id_censo,
            'id_linea_tiempo' => $id_linea_tiempo
        );
        $this->db->insert('workflow.registros', $datos);
    }

}
