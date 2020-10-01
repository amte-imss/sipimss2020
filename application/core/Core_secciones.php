<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 30052017
 * @author      : LEAS
 * */
class Core_secciones extends Informacion_docente {

    protected $elementos_actividad;
    protected $seccion;

    const
            PLANTILLA_FORMULARIO_PRUEBA = "docente/actividad_docente/form_pruaba.php",
            PLANTILLA_FORMULARIO_DEFAULT = "docente/formulario_general_tpl.php";

    function __construct() {
        parent::__construct();
        $this->elementos_actividad = array(
            //Aplicando
            'formulario_view' => Core_secciones::PLANTILLA_FORMULARIO_DEFAULT,
            'cuerpo_data_table' => 'tc_template/general_data_table.php',
            'cuerpo_data_table_especifica' => 'tc_template/especifica_data_table.php',
            'tabla' => 'tc_template/data_table_tpl.php',
        );
    }

    /**
     * @author LEAS
     * @fecha 08/05/2017
     * @descripcion: Carga Boton que despliega el arbol de secciones, la información de los registros en
     * el datatable
     *
     */
    public function index() {
        $this->benchmark->mark('code_start');
        //$elementos_seccion = $this->elementos_actividad['elemento_seccion']; //Temporal
        $datos_sesion = $this->get_datos_sesion();
        //pr($this->get_datos_sesion());
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE]; //Obtiene identificador del docente en la sesión actual
        //Carga textos de comprobante  y de datatable
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::COMPROBANTE, En_catalogo_textos::DATA_TABLE_SECCIONES));
        /* Datos de tabla */
//        $tabla = $this->get_datos_actividad_docente_c($id_docente, $this->seccion);
//        $tabla['string_value'] = $string_value; //Textos del lenguaje
        $data['ruta_controler_editar_registro'] = $this->elementos_actividad['ruta_controler_editar_registro']; //Ruta que cargara los datos del formulario

//Carga el componente dropdown de secciones del datatable
        $data['catalogo_secciones_actividad_docente'] = $this->load->view('tc_template/secciones_datatable_dropdown.php', null, true);
//        pr($data_tabla_cuerpo);
//        $data_tabla_cuerpo['componente_datatable'] = $this->load->view($this->elementos_actividad['cuerpo_data_table'], $tabla, true); //carga la tabla especifica por(campo de formulario) o la general (campos en comun de todos los registros)

//        $data['tabla'] = $this->load->view($this->elementos_actividad['tabla'], $data_tabla_cuerpo, true);

        //Titulo de la sección
        $this->load->model('Secciones_model', 'csm');
        $seccion = $this->csm->get_seccion($this->seccion); //Obtiene la información de sección
        $data['config'] = array();
        $data['ruta_file_js']['callSeccion'] = "docente/secciones.js";//Agregar el control de seccion
        if (!empty($seccion)) {
            $data['titulo_seccion'] = $seccion[0]['label'];
            $data['prop_seccion'] = $seccion[0];//22092020
            if(!empty($seccion[0]['ruta_file_js'])){                
                $data['ruta_file_js'] += array_merge(json_decode($seccion[0]['ruta_file_js'],true), $data['ruta_file_js']);//22092020                
            }
            $data['is_seccion_static'] = $seccion[0]['is_seccion_static'];//22092020
            $data['config'] = json_decode($seccion[0]['config'], true);//25092020
            $parametros_boton_agregar_seccion['config'] = json_decode($seccion[0]['config'], true);//25092020
        }
        $data['seccion'] = '';
        $parametros_boton_agregar_seccion = array_merge($this->template->getParametrorBoton(),$parametros_boton_agregar_seccion); //Obtiene todos los parametros del botón            
        $parametros_boton_agregar_seccion['seccion'] = $this->seccion; //Agrega la sección a la que pertenece el modulo
        //$parametros_boton_agregar_seccion['is_seccion_static'] = $seccion[0]['is_seccion_static'];
        $this->template->setBotonAgregarGeneral($parametros_boton_agregar_seccion);
        $this->template->setFormularioSecciones($data);
        $this->template->getTemplate();

        $this->benchmark->mark('code_end');
        //echo $this->benchmark->elapsed_time('code_start', 'code_end');
//        echo $this->benchmark->memory_usage();
//        $this->output->enable_profiler(TRUE);
        $this->output->parse_exec_vars = TRUE;
        //$this->output->append_output($this->benchmark->memory_usage());
    }

    /**
     * @author LEAS
     * @fecha 08/05/2017
     * @return type String: HTML del datatable correspondiente a la seccion a fin,
     * es decir, formación docente, Actividad docente, Becas, Comisiones, Investigación,
     * material educativo
     *
     */
    public function actualiza_tabla() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $data_post = $this->input->post(null, TRUE);
            $string_value = get_elementos_lenguaje(array(En_catalogo_textos::COMPROBANTE, En_catalogo_textos::DATA_TABLE_SECCIONES));
            $id_docente = $this->get_datos_sesion(En_datos_sesion::ID_DOCENTE); //Obtiene el id del docente
            if (isset($data_post['sub_seccion']) AND ! is_null($data_post['sub_seccion']) AND ! empty($data_post['sub_seccion'])) {
                $componente_datatable = $this->elementos_actividad['cuerpo_data_table_especifica']; //Carga el cuerpo de la tabla de datos especificos por subsección o formulario
                $id_elemento_seccion = $data_post['sub_seccion'];
            } else {
                $componente_datatable = $this->elementos_actividad['cuerpo_data_table']; //Carga el cuerpo de la tabla de datos generales
                $id_elemento_seccion = null;
            }
            /* Datos de tabla */
            //Carga el componente dropdown de secciones del datatable
            $tabla = $this->get_datos_actividad_docente_c($id_docente, $this->seccion, null, $id_elemento_seccion);
            $tabla['value_secciones'] = $id_elemento_seccion; //LEE los campos especificos que se deben mostrar del formulario
            $tabla['string_value'] = $string_value; //Textos del lenguaje
            $data_tabla_cuerpo['catalogo_secciones_actividad_docente'] = $this->load->view('tc_template/secciones_datatable_dropdown.php', $tabla, true);
            $data_tabla_cuerpo['cuerpo_data_table'] = $this->load->view($this->elementos_actividad['cuerpo_data_table'], $tabla, true);
            $data_tabla_cuerpo['ruta_controler_editar_registro'] = $this->elementos_actividad['ruta_controler_editar_registro']; //Ruta que cargara los datos del formulario
            $data_tabla_cuerpo['count_table'] = count($tabla['datos_actividad_docente']); //Cuenta la catidad de registros de la tabla

            $data_tabla_cuerpo['componente_datatable'] = $this->load->view($componente_datatable, $tabla, true); //carga la tabla especifica por(campo de formulario) o la general (campos en comun de todos los registros)

            $this->load->view($this->elementos_actividad['tabla'], $data_tabla_cuerpo, FALSE);
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function get_registros_seccion() {
//        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
        $data_post = $this->input->post(null, TRUE);
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::DATA_TABLE_SECCIONES_CONFIG));
        $id_docente = $this->get_datos_sesion(En_datos_sesion::ID_DOCENTE); //Obtiene el id del docente
        $datos_actividad = $this->get_datos_actividad_docente_c($id_docente, $this->seccion);
        $datos_actividad['id_validacion_registro'] = $this->get_estados_validacion_censo_c();
        $datos_actividad['textos_extra'] = $string_value;
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($datos_actividad);
//            return json_encode($data);
    }

    /**
     * @author HILDA
     * @param type $censo identificador del censo encryptado en base64
     * @return type returna JSON con la siguiente estructura
     * array('tp_msg' => '', 'mensaje' => '');
     * @descripcion: El método elimina el registro del censo en los siguientes casos
     * Que no se haya validado validado o evaluado
     *
     *
     */
    public function elimina_censo($censo = null) {
        if ($this->input->is_ajax_request()) {
            $id_censo = decrypt_base64($censo);
            //pr($censo);
            //pr($id_censo);
            //Verificar estatus de censo 1) No este validado-registro sistema 2) No sea carga manual
            $detalle_censo = $this->get_detalle_censo_c($id_censo);
//            pr($detalle_censo);

            if (is_bool($detalle_censo['is_carga_sistema']) === TRUE && !empty($detalle_censo['is_carga_sistema'])) {
                //NO SE PUEDE BORRAR
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => 'No se puede eliminar registro');
            } else {
                if ($detalle_censo['id_validacion_registro'] == 1) {
                    // SE PUEDE BORRAR
                    $this->load->model('Formulario_model', 'fm');
                    $result = $this->fm->delete_censo($id_censo);
                    $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => 'El registro se guardo correctamente');
                } else {
                    //NO se puede eliminar
                    $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => 'No se puede eliminar registro');
                }
            }
            return $respuesta;
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @author LEAS
     * @fecha 08/05/2017
     * @param type $id_censo identificador del registro del censo encriptado
     * en base64
     * @descripcion: Carga los datos del registro del censo para la edición
     * @post:
     * Array
     * (
     * ***Parametros que nunca cambian, son propiedades generales para todos los registros
     *   [id_elementos_seccion] => 32
     *   [formulario] => 7
     *   [censo_regstro] =>
     *   ****Variación de datos por sección y por formulario
     *   [fecha_inicio] =>
     *   [fecha_termino] =>
     *   [clase_beca] =>
     *   [proceso_educativo] =>
     *   [beca_interrumpida] =>
     *   [causa_beca_interrumpida] =>
     *   [tipo_comprobante] =>
     * ****Datos o parametros de comprobante, en su definición de negoció, todos los registros contienen esta informació
     *   [folio_comprobante] =>
     *  [extension] => pdf
     *   [comprobante] =>
     * )
     */
    public function carga_actividad($id_censo) {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $datos_sesion = $this->get_datos_sesion();
            $this->load->library('Funciones_motor_formulario'); //Carga biblioteca
//            $matricula_docente = $datos_sesion[En_datos_sesion::MATRICULA];
//            $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            $id_censo = decrypt_base64($id_censo); //Identificador del registro encriptado en base 64
            $formulario = $this->get_campos_formulario(null, $id_censo); //Obtiene tosdos los campos de formulario
            $propiedades_formulario = $this->fm->get_formulario(null, $id_censo); //Obtiene datos de formulario
//            pr($formulario );
            if (!empty($formulario)) {
                $elementos_seccion = $formulario[0]['id_elemento_seccion']; //Asigna el elemento sección para
            }
//            pr($formulario);
            $detalle_censo = $this->get_detalle_censo_c($id_censo); //Obtiene detalle del censo
//            pr($detalle_censo);
//            $datos_registro['arbol_secciones'] = $this->get_elemento_seccion_ramas_c($elemento_seccion);
//            $titulo = $datos_registro_tmp[0]['nom_elemento_seccion'];
            $catalogos_form = $this->get_elementos_catalogos_formulario($elementos_seccion, $detalle_censo);
            $data_form = $this->template->get_elements_seccion(); //Obtiene los elementos de la seccion actual
            $data_form['formulario_campos'] = $formulario;
            $data_form['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
            $data_form['id_elementos_seccion'] = $elementos_seccion;
            $data_form['catalogos'] = $catalogos_form;
            $data_form['rutas_generales_js'] = $this->elementos_actividad['rutas_generales_js'];
            $data_form['detalle_censo'] = $detalle_censo;
            $data_form['propiedades_formulario'] = $propiedades_formulario;
            $data_form['censo_registro'] = $id_censo;
            //Obtener rama del registro
            $data_form['arbol_secciones'] = $this->get_elemento_seccion_ramas_c($elementos_seccion);
//            pr($detalle_censo);
            $this->template->set_boton_guardar(array('censo_regstro' => $id_censo, 'formulario' => $data_form['formulario'])); //Genera el botón de guardar un o actualizar por default
            $data_form['boton_submit'] = $this->template->get_boton_guardar(); //Asigna comprobante
//            $this->template->set_comprobante($detalle_censo); //Envia parametros de comprobante
//            $data_form['componente_comprobante'] = $this->template->get_comprobante(); //Asigna comprobante
            // pr($this->elementos_actividad['formulario_view']);
            $this->load->view($this->elementos_actividad['formulario_view'], $data_form, FALSE);
//            $this->output->enable_profiler(TRUE);//Monitorea tiempos de ejecuación
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @descripcion Actualiza los datos de un registro de las secciones de censo
     * @post
     * @return type Description
     * @post Array
     * (
     * ********Identificadores generales, describen el registro de censo a editar
     *    [id_elementos_seccion] => 32
     *    [formulario] => 7
     *    [censo_regstro] =>
     * *****Ejemplo Datos de formulario, varian por formulario y por sección
     *    [fecha_inicio] =>
     *    [fecha_termino] =>
     *    [clase_beca] =>
     *    [proceso_educativo] =>
     *    [beca_interrumpida] =>
     *    [causa_beca_interrumpida] =>
     *    [tipo_comprobante] =>
     * ****Datos generales de comprobante y folio, por regla de negoció
     *    [folio_comprobante] =>
     *    [extension] => pdf
     *    [comprobante] =>
     * )
     */
    public function datos_actividad_actualiza() {
        if ($this->input->post()) { //Solo se accede al método a través de una petición ajax
            $datos_sesion = $this->get_datos_sesion();
            $matricula_docente = $datos_sesion[En_datos_sesion::MATRICULA];
            $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            $id_censo = null; //En algun punto debe existir la variable, aquí únicamente se define
            $data_post = $this->input->post(null, true);
//            pr($data_post);
//            exit();
            if ($this->input->post()) {
                $opciones_extra_catalogo_otro = array();
                $elemento_seccion = $data_post['id_elementos_seccion']; //Obtiene el elemento sección del formulario
                $formulario = $this->get_campos_formulario($elemento_seccion); //Obtiene tosdos los campos de formulario
                $propiedades_formulario = $this->fm->get_formulario(null, $id_censo); //Obtiene datos de formulario
                $datos_files = $this->separa_files_post($data_post); //Obtiene datos de los archivos, y agrega nombre del archivo al post
                $rules = $this->get_rules_formulario($formulario, $data_post); //Obtiene tosdos los campos de formnulario
                //pr($formulario);
                //pr($rules);
                
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
//                    $ruta_directorio = base_url($this->config->item('upload_us')); //Carga la ruta del usuario
                    $save_files_generales = $this->save_files_generales_fisicamente($datos_files, $matricula_docente, $matricula_docente); //Guarda los archivos en físico, y regresa información del archivo guardado
                    if ($save_files_generales['tp_msg'] == En_tpmsg::SUCCESS) {//Si el tipo de mensaje es satisfactorio
                        //almacenará los archivos generales del formulario
                        $post_estaticos = array(
                            'id_docente' => $id_docente, 'id_validacion_registro' => 1, 'is_carga_sistema' => FALSE,
                        );
                        $data_post = array_merge($data_post, $post_estaticos); //Hace marge de los datos faltantes
                        //Guarda archivo
                        //Guarda correctamente
                        $respuesta = $this->insert_datos_formulario_c($data_post, $formulario, $datos_files); //Obtiene tosdos los campos de formulario
                    } else {//Marca el estado de guardado y envía mensaje
                        $respuesta['tp_msg'] = $save_files_generales['tp_msg'];
                        $respuesta['mensaje'] = $save_files_generales['mensaje'];
                    }
                } else {//Recorre campos otros
                    foreach ($formulario as $value_df) {//Asociación de llaves
                        //Valida que sea un catalogo con capacidad de agregar un nuevo registro del tipo "dropdown_otro"
                        if (Formulario_model::TIPO_CAMPO_DROPDOWN_OTRO == $value_df['id_tipo_campo']) {
                            $str_post_data = $data_post[$value_df['nom_campo']];
                            /**
                             * Valida que sea un nuevo registro, tiene el formato de json
                             * con las llaves "key_other" = id del catalogo en base64  y "value_other" = valor nuevo del elemento catálogo
                             */
                            if (strpos($str_post_data, Formulario_model::KEY_OTHER_CAMPO) !== FALSE) {//Valida que post traiga un nombre de campo
//                            pr($str_post_data);
                                $prop_post_otro = explode("_", $str_post_data);
                                //Valida que el campo de catálogo se de other
                                if (!empty($prop_post_otro) and isset($data_post[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR])) {//Valida que el json no este vacio y que existan las llaves solicitadas
                                    $forma_palabra = $data_post[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR];
//                                    'ec.id_elemento_catalogo', 'ec.nombre', 'ec.id_catalogo', 'ec.id_catalogo_elemento_padre', 'ec.label', 'ec.is_validado'
                                    $opciones_extra_catalogo_otro[$value_df['nom_campo']][] = array(
                                        "id_elemento_catalogo" => $data_post[$value_df['nom_campo']],
                                        "label" => $forma_palabra,
                                        "is_validado" => TRUE,
                                        "activo" => TRUE,
                                    );
                                }
                            }
                        }
                    }
                }
//        pr($rules);
                $catalogos_form = $this->get_elementos_catalogos_formulario($elemento_seccion, null, $opciones_extra_catalogo_otro);
                $data_form = $this->template->get_elements_seccion(); //Obtiene los elementos de la seccion actual
                $data_form['formulario_campos'] = $formulario;
                $data_form['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
                $data_form['id_elementos_seccion'] = $elemento_seccion;
                $data_form['catalogos'] = $catalogos_form;
                $data_form['rutas_generales_js'] = $this->elementos_actividad['rutas_generales_js'];
                $data_form['opciones_extra_catalogo_otro'] = $opciones_extra_catalogo_otro;
                $data_form['propiedades_formulario'] = $propiedades_formulario;
//            pr($data_form);
                $this->template->set_boton_guardar(array('id_censo' => $id_censo, 'formulario' => $data_form['formulario'])); //Genera el botón de guardar un o actualizar por default
                $data_form['boton_submit'] = $this->template->get_boton_guardar(); //Asigna comprobante
//                $this->template->set_comprobante(); //Envia parametros de comprobante
//                $data_form['componente_comprobante'] = $this->template->get_comprobante(); //Asigna comprobante
                if (isset($respuesta) and ! empty($respuesta)) {
                    $respuesta['html'] = $this->load->view($this->elementos_actividad['formulario_view'], $data_form, TRUE);
                    echo json_encode($respuesta);
                    exit();
                }
                $this->load->view($this->elementos_actividad['formulario_view'], $data_form, FALSE);
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @author LEAS
     * @fecha 07/06/2017
     * @param type $files_post :Datos generales de los archivos que llegan por post y en la variable $_FILES
     * @param type $carpeta :Nombre de la carpeta o directorio donde se deberá guardar el archivo
     * @param type $nombre_sugerido :Nombre sugerido que deberá llevar el archivo
     * @return type Array
     * array(
     *      'tp_msg' => '', //success si todo salio bien o danger si por lo menos un archivo no se pudo guardar
     *      'mensaje' => '' //Si salio todo correctamente, el mensaje será vacio, si no, retorna el mensaje de por que salio mal
     * );
     */
    private function save_files_generales_fisicamente(&$files_post, $carpeta, $nombre_sugerido) {
        foreach ($files_post as $key => &$value) {
            $nombre_rchivo = $nombre_sugerido . '_' . time();
//            pr($value);
            //
            if ($value['size_ff'] > 0) {//Existe carga de archivo
                $tmp_file_save = $this->save_file($value['extencion_ff'], $carpeta, $nombre_rchivo, $key);
                if ($tmp_file_save['tp_msg'] == En_tpmsg::SUCCESS) {//Si el archivo se guardo satisfactoriamente, agrega propiedades
                    $value['nombre_fisico_actual'] = $tmp_file_save['raw_name'];
                    $value['ruta_actual'] = $tmp_file_save['upload_path'];
                } else {
                    return array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $tmp_file_save['mensaje']); //Retorna error al guardar archivo
                }
            }
        }
//        pr($files_post);

        return array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => ''); //Retorna satisfactorio guardado de archivos
    }

    private function separa_files_post(&$datos_post) {
        $array_result = array();
        if (!empty($_FILES)) {//Files debe ser diferente de vacio
//            pr($_FILES);
            $this->load->model('Files_model', 'ffm');
            foreach ($_FILES as $key => $value) {//Recorre files
                if (isset($datos_post[$key])) {//Si existe el archivo, y es post
                    $tmp_array = array(); //Reinicia variable temporal de resultados
                    if ($value['size'] > 0) {//Valida que se haya cargado un archivo
                        $_POST[$key] = $value['name']; //Cambia texto al post, para que valide el nombre y extención del archivo
                    }
//                    pr($datos_post[$key]);
                    if (strlen($datos_post[$key]) > 0) {//Valida que sea diferente de vacio el id del file
                        $tmp_array['is_nuevo_file'] = TRUE;
                        $id_file_post = base64_decode($datos_post[$key]); //Decódifica el valor id_file
//                        pr($id_file_post);
//                        pr($id_file_post);
                        $tmp_array = $this->ffm->get_file($id_file_post); //Consulta particularidades del archivo en la base de datos
                        if (!empty($tmp_array)) {
                            $tmp_array = $tmp_array[0]; //Asigna primer valor del array
                            $aux_name_file = $tmp_array['nombre_fisico'] . '.' . $tmp_array['nombre_extencion'];
                            $datos_post[$key] = $id_file_post; //Le cambia el id encriptado, por el id real para que al guardar sea el numerico y no el numerico encriptado en base64
                            if ($value['name'] != $aux_name_file) {
                                $_POST[$key] = $aux_name_file; //Cambia texto al post, para que valide el nombre y extención del archivo
                            }
                        }
                    }
                    $tmp_array['name_ff'] = $value['name'];
                    $tmp_array['size_ff'] = $value['size'];
                    $tmp_array['error_ff'] = $value['error'];
                    $tmp_array['type_ff'] = $value['type'];
                    $tmp_array['extencion_ff'] = get_extencion_archivo_de_nombre($value['name']);
                    $tmp_array['error_ff'] = $value['error'];
                    $array_result[$key] = $tmp_array;
                }
            }
        }
//        pr($datos_post);
//        pr($array_result);
        return $array_result;
    }

    private function delete_file_post_carga_general(&$datos_files, $tp_msg) {
        if ($tp_msg == En_tpmsg::SUCCESS) {//Se guardo correctamente, elimina files viejos
            foreach ($datos_files as $key => $value) {
                if ($value['size_ff']) {//Valida que se haya modificado el
                    $ruta_tmp = '.' . $value['ruta']; //Ruta de archivo old
                    $nombre_file = $value['nombre_fisico'] . '.' . $value['nombre_extencions']; //Nombre de archivo old
                    $this->delete_file($ruta_tmp, $nombre_file); //Elimina el archivo solicitado
                }
            }
        } else {//No se guardo correctamente, elimina los archivos nuevos
            //Recorre archivos viejos
            foreach ($datos_files as $key => $value) {
                if ($value['size_ff']) {
                    $ruta_tmp = '.' . $value['ruta_actual']; //Ruta de archivo old
                    $nombre_file = $value['nombre_fisico_actual'] . '.' . $value['extencion_ff']; //Nombre de archivo old
                    $this->delete_file($ruta_tmp, $nombre_file); //Elimina el archivo solicitado
                }
            }
        }
    }

    /**
     * @author LEAS
     * @fecha 05/05/2017
     * @return: Retorna codigo HTML en el caso de que no se haya guardado el registro por
     * no pasar el filtro de validaciones. En caso de que haya una falla en la
     * actualización o que se haya actualizado correctamente regresa un json con la siguiente definicion
     *  "tp_msg": tipo de mensaje, success, danger, info, warning
     *  "mensaje": = Con un mensaje de texto, que notifica lo ocurrido;
     *  "html": = codigo html del formulario;
     * @descripcion: Funcion encargada de actualizar la información de un registro del censo,
     * de cualquiera de las secciones del censo, es decir, formación docente, Actividad docente, Becas, Comisiones, Investigación,
     * material educativo
     * @post Array
     * (
     * ********Identificadores generales, describen el registro de censo a editar
     *    [id_elementos_seccion] => 32
     *    [formulario] => 7
     *    [censo_regstro] => OTA
     * *****Ejemplo Datos de formulario, varian por formulario y por sección
     *    [fecha_inicio] => 2017-05-04
     *    [fecha_termino] => 2017-05-05
     *    [clase_beca] => 194
     *    [proceso_educativo] => 197
     *    [beca_interrumpida] => 253
     *    [causa_beca_interrumpida] =>
     *    [tipo_comprobante] => 97
     * ****Datos generales de comprobante y folio, por regla de negoció
     *    [folio_comprobante] => 78787878
     *    [extension] => pdf
     *    [comprobante] => 3342165412_1494603383
     *    [id_file_comprobante] => OTA
     * )
     */
    public function editar_actividad() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            header('Content-type: text/html; charset=utf-8');
            $datos_sesion = $this->get_datos_sesion();
            $matricula_docente = $datos_sesion[En_datos_sesion::MATRICULA];
            $this->load->library('Funciones_motor_formulario'); //Carga biblioteca
            $opciones_extra_catalogo_otro = array();
            if ($this->input->post()) {
                $data_post = $this->input->post(null, true);
                $id_censo = decrypt_base64($data_post['censo_regstro']); //Desencripta censo
                $detalle_censo = $this->get_detalle_censo_c($id_censo); //Obtiene detalle del censo
                $formulario = $this->get_campos_formulario(null, $id_censo); //Obtiene tosdos los campos de formulario
                $propiedades_formulario = $this->fm->get_formulario(null, $id_censo); //Obtiene datos de formulario
                $elemento_seccion = $data_post['id_elementos_seccion']; // elemento seccion es null
                $datos_files = $this->separa_files_post($data_post);
                $rules = $this->get_rules_formulario($formulario, $data_post); //Obtiene tosdos los campos de formnulario
//                pr($rules);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    $save_files_generales = $this->save_files_generales_fisicamente($datos_files, $matricula_docente, $matricula_docente); //Inserta, actualiza
                    if ($save_files_generales['tp_msg'] == En_tpmsg::SUCCESS) {
                        //Manda actualización de archivo
                        $respuesta = $this->update_datos_formulario_c($data_post, $formulario, $elemento_seccion, $id_censo, NULL, null, null, $datos_files, $detalle_censo); //Obtiene tosdos los campos de formulario
                        if ($respuesta['tp_msg'] == En_tpmsg::SUCCESS) {//Si la actualización se efectuo correctamente, elimina archivo viejo
                            //Elimina archivos generales viejos
                        } else {
                            //Elimina archivos generales nuevos, ya que no existio un guardado de la información exitoso
                        }
                    } else {
                        //Envía mensaje de que no se puede guardar la información con la respuestad de guardar archivo
                        $respuesta['tp_msg'] = $save_files_generales['tp_msg'];
                        $respuesta['mensaje'] = $save_files_generales['mensaje'];
                    }
                } else {
                    foreach ($formulario as $value_df) {//Asociación de llaves
                        //Valida que sea un catalogo con capacidad de agregar un nuevo registro del tipo "dropdown_otro"
                        if (Formulario_model::TIPO_CAMPO_DROPDOWN_OTRO == $value_df['id_tipo_campo'] && isset($data_post[$value_df['nom_campo']])) {
                            $str_post_data = $data_post[$value_df['nom_campo']];
                            /**
                             * Valida que sea un nuevo registro, tiene el formato de json
                             * con las llaves "key_other" = id del catalogo en base64  y "value_other" = valor nuevo del elemento catálogo
                             */
                            if (strpos($str_post_data, Formulario_model::KEY_OTHER_CAMPO) !== FALSE) {//Valida que post traiga un nombre de campo
                                $prop_post_otro = explode("_", $str_post_data);
                                //Valida que el campo de catálogo se de other
                                if (!empty($prop_post_otro) and isset($data_post[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR])) {//Valida que el json no este vacio y que existan las llaves solicitadas
                                    $forma_palabra = $data_post[$value_df['nom_campo'] . Formulario_model::KEY_OTHER_CAMPO_AUXILIAR];
//                                    'ec.id_elemento_catalogo', 'ec.nombre', 'ec.id_catalogo', 'ec.id_catalogo_elemento_padre', 'ec.label', 'ec.is_validado'
                                    $opciones_extra_catalogo_otro[$value_df['nom_campo']][] = array(
                                        "id_elemento_catalogo" => $data_post[$value_df['nom_campo']],
                                        "label" => $forma_palabra,
                                        "is_validado" => TRUE,
                                        "activo" => TRUE,
                                    );
                                }
                            }
                        }
                    }
                }
            }
//        pr($rules);
//pr('$data_post');
//pr( $this->input->post(null, true));

            $catalogos_form = $this->get_elementos_catalogos_formulario($elemento_seccion, $detalle_censo, $opciones_extra_catalogo_otro);
            $data_form = $this->template->get_elements_seccion(); //Obtiene los elementos de la seccion actual
            $data_form['formulario_campos'] = $formulario;
            $data_form['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
            $data_form['id_elementos_seccion'] = $elemento_seccion;
            $data_form['catalogos'] = $catalogos_form;
            $data_form['detalle_censo'] = $detalle_censo;
//            $data_form['ruta_controller'] = '/actividad_docente/editar_actividad';
            $data_form['rutas_generales_js'] = $this->elementos_actividad['rutas_generales_js'];
            $data_form['arbol_secciones'] = $this->get_elemento_seccion_ramas_c($elemento_seccion);
            $data_form['opciones_extra_catalogo_otro'] = $opciones_extra_catalogo_otro;
            $data_form['propiedades_formulario'] = $propiedades_formulario;
//            pr($data_form);
            $this->template->set_boton_guardar(array('censo_regstro' => $id_censo, 'formulario' => $data_form['formulario'])); //Genera el botón de guardar un o actualizar por default
            $data_form['boton_submit'] = $this->template->get_boton_guardar(); //Asigna comprobante
//            $this->template->set_comprobante($detalle_censo); //Envia parametros de comprobante
//            $data_form['componente_comprobante'] = $this->template->get_comprobante(); //Asigna comprobante
            if (isset($respuesta) and ! empty($respuesta)) {//Valida una respuesta al actualizar datos
                $respuesta['html'] = $this->load->view($this->elementos_actividad['formulario_view'], $data_form, TRUE);
                echo json_encode($respuesta);
                exit();
            }
            $this->load->view($this->elementos_actividad['formulario_view'], $data_form, FALSE);
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @author LEAS
     * @fecha 08/06/2017
     * @param type $elemento_seccion identificador elemento de sección que define a un formulario
     * @return type Description Código HTML del formulario definido por alguna sección en la base de datos
     * @descripcion Lo invoca el árbol de secciones
     */
    public function mostrar_formulario($elemento_seccion) {
        if ($this->input->is_ajax_request()) {
//            $datos_sesion = $this->get_datos_sesion();
//            $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            /*             * ***Comentar de formulario**** */
            $formulario = $this->get_campos_formulario($elemento_seccion); //Obtiene tosdos los campos de formulario
//            pr($formulario);
            $label_formulario = '';
            if (!empty($formulario)) {
                $label_formulario = $formulario[0]['lbl_formulario'];
            }
            $catalogos_form = $this->get_elementos_catalogos_formulario($elemento_seccion);
            $data_form = $this->template->get_elements_seccion(); //importante colocar al inicio, ya que Obtiene los elementos de la seccion actual
            $data_form['formulario_campos'] = $formulario;
            $data_form['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
            $data_form['id_elementos_seccion'] = $elemento_seccion;
            $data_form['catalogos'] = $catalogos_form;
            $data_form['rutas_generales_js'] = $this->elementos_actividad['rutas_generales_js'];
//            $this->template->set_comprobante(); //Envia parametros de comprobante
            $this->template->set_boton_guardar(array('id_censo' => null, 'formulario' => $data_form['formulario'])); //Genera el botón de guardar un o actualizar por default
            $data_form['boton_submit'] = $this->template->get_boton_guardar(); //Asigna comprobante
//            $data_form['componente_comprobante'] = $this->template->get_comprobante(); //Asigna comprobante
            // pr($data_form);
            $this->load->view($this->elementos_actividad['formulario_view'], $data_form, FALSE);
//            $this->output->enable_profiler(TRUE);
            /*             * ***fin de formulario**** */
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @author LEAS
     * @fecha 08/05/2017
     * @param type $censo identificador del censo encriptado en base 64
     * @return type Código html con el detalle de registro incluye ver
     * y descargar comprobante
     */
    public function ver_detalle_registro_censo($censo) {
        $id_censo = decrypt_base64($censo);
        $datos_sesion = $this->get_datos_sesion();
        $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
        $this->load->library('Funciones_motor_formulario'); //Carga biblioteca
        $detalle_censo = $this->get_detalle_censo_c($id_censo);
        $this->load->model('Formulario_model', 'fm');
        $datos_registro_tmp = $this->fm->get_datos_actividad_docente($id_docente, null, $id_censo);
//        $datos_registro = $this->get_campos_formulario(null, $id_censo);
//        $datos_registro_tmp = $this->get_campos_formulario(null, $id_censo); //Obtiene tosdos los campos de formulario
//        pr($datos_registro_tmp);
//        pr($datos_registro);
//        pr($datos_registro_tmp);
        if (!empty($datos_registro_tmp)) {
            $elemento_seccion = $datos_registro_tmp[0]['id_elemento_seccion'];
//            $datos_registro['arbol_secciones'] = $this->get_elemento_seccion_ramas_c($elemento_seccion);
//            $titulo = $datos_registro_tmp[0]['nom_elemento_seccion'];
            $titulo = $this->get_elemento_seccion_ramas_c($elemento_seccion);
            $datos_registro['detalle_censo'] = $detalle_censo;
            $datos_registro['formulario_campos'] = $datos_registro_tmp;
            $datos_registro['propiedades_formulario'] = $this->fm->get_formulario(null, $id_censo);
        } else {
            //Manda mensaje de que no se encontraron datos
        }
        $this->load->library('template_detalle_censo');
        $this->template_detalle_censo->set_detalle_registro($datos_registro);
//        pr($formulario);
        $cuerpo_modal = $this->template_detalle_censo->get_template();

        $this->template->set_titulo_modal($titulo);
        $this->template->set_cuerpo_modal($cuerpo_modal);
        $this->template->set_pie_modal($this->template_detalle_censo->get_boton_cerrar_modal());
        $this->template->get_modal();
//        $this->output->enable_profiler(TRUE);
//        pr($datos_registro);
    }

    /**
     * @author LEAS
     * @fecha 08/05/2017
     * @param type $file_id
     * @param type $nombre_comprobante
     * @return type Array que indica si el comprobante del registro del censo
     * o archivo a cambiado, en el caso de que exista un archivo cargado. Devolverá
     * la siguiente estructura (propiedades o lugar de almacenamiento del archivo)
     * array(
     *      'ruta_file' => '',
     *      'nombre_file' => '',
     *      'extencion_file' => '',
     *      'equal' => 'false o true'
     * );
     * de lo contrario únicamente devuleve
     * array(
     *      'equal' => 'false'
     * );
     */
    private function is_comprobante_igual($file_id = null, $nombre_comprobante = '') {
//        pr($file_id);
        if (!is_null($file_id) and ! empty($file_id)) {
            $this->load->model("Files_model", "ffm");
            $result_file = $this->ffm->get_file($file_id); //Se valida que exista registro en base de datos
//            pr($result_file);
            if (!empty($result_file)) {
                if ($nombre_comprobante != '') {
                    $equals = $result_file[0]['nombre_fisico'] == $nombre_comprobante;
                    return array('ruta_file' => $result_file[0]['ruta'],
                        'nombre_file' => $result_file[0]['nombre_fisico'],
                        'extencion_file' => $result_file[0]['nombre_extencion'],
                        'equal' => $equals);
                }
            }
        }
        return array('equal' => FALSE);
    }

    /**
     * @fecha 20/04/2017
     * @author LEAS
     * @param type array $datos_post_formulario Información post del formulario de la sección correspondiente
     * Array
     * (
     * ****Registros generales que definen lo más basico de un formulario
     *    [id_elementos_seccion] => 32
     *    [formulario] => 7
     *    [censo_regstro] =>
     *    [folio_comprobante] => 2232fsrerterte
     *    [extension] => pdf
     *    [comprobante] => 311091488_1496275079
     *    [id_docente] => 1
     *    [id_validacion_registro] => 1
     *    [is_carga_sistema] =>
     *    [ruta_file] => ./assets/us/perfil/311091488/
     * ** Datos de los campos definidos por base de datos de los formularios
     * )
     * @param type $deficion_formulario Todos los campos que definen al formulario en cuestión (siempre varia por formulario)
     * @param type $id_docente identificador del docente actual
     * @param type $seccion sección del formulario o tipo de formuario,
     * @return type Array con la respuesta que indica que el guardado fue satisfactorio o
     * ucurrio algun error. La respuesta se define por la siguiente estructura
     * array(
     *      'tp_msg' => '', //Tipo de mensaje o respuesta de la operación, puede ser cualquiera de las registradas en la clase En_tpmsg
     *      'mensaje' => '' //Mensaje del estado de la operación
     * );
     * @descripcion: Funcion que invoca al modelo y función que insertará o almacenará
     * un nuevo registro del censo
     */
    protected function insert_datos_formulario_c($datos_post_formulario = null, $deficion_formulario = null, &$datos_files = null) {
        //pr($datos_post_formulario);
//        exit();
        $this->load->model('Formulario_model', 'fm');
        $array_result = $this->fm->insert_datos_formulario($datos_post_formulario, $deficion_formulario, $datos_files);
        $datos_s = $this->get_datos_sesion();
        /* Aquí se relaciona los registros con el workflow */
        if($array_result['tp_msg'] == En_tpmsg::SUCCESS && isset($datos_s['workflow']) && !empty($datos_s['workflow']) && isset($datos_s['workflow'][0]['id_linea_tiempo']))
        {
            $this->fm->almacena_registro_workflow($array_result['success'], $datos_s['workflow'][0]['id_linea_tiempo']);
        }
        return $array_result;
    }

    /**
     * @fecha 20/04/2017
     * @author LEAS
     * @param type $datos_post_formulario Información post del formulario de la sección correspondiente
     *  Array
     *  (
     * *****Registros generales que definen lo más basico de el formulario del registro del docente a actualizar
     *    [id_elementos_seccion] => 32
     *    [formulario] => 7
     *    [censo_regstro] => OTA
     *    [folio_comprobante] => 78787878s
     *    [extension] => pdf
     *    [comprobante] => 3342165412_1494603383
     *    [id_file_comprobante] => OTA
     *  )
     * @param type $datos_formulario Todos los campos que definen al formulario en cuestión (siempre varia por formulario)
     * @param type $elemento_seccion Identificador del elemento de la sección que define al formulario
     * @param type $id_censo Identificador del registro del censo que se va actualizar
     * @param type $id_file_comprobante Identificador del archivo comprobante del regstro de censo
     * @param type $nombe_file Nombre del nuevo archivo comprobante del registro
     * @param type $ruta_archivo Ruta de almacen del nuevo archivo comprobante del registro
     * @return type Array con la respuesta que indica que el guardado fue satisfactorio o
     * ucurrio algun error. La respuesta se define por la siguiente estructura
     * array(
     *      'tp_msg' => '', //Tipo de mensaje o respuesta de la operación, puede ser cualquiera de las registradas en la clase En_tpmsg
     *      'mensaje' => '' //Mensaje del estado de la operación
     * );
     * @descripcion: Funcion que invoca al modelo y función que actualizará un registro del censo
     *
     */
    protected function update_datos_formulario_c($datos_post_formulario, $datos_formulario, $elemento_seccion, $id_censo, $id_file_comprobante, $nombe_file = null, $ruta_archivo = null, &$datos_files = null, $detalle_censo) {
//        pr($datos_post_formulario);
        $this->load->model('Formulario_model', 'fm');
        $array_result = $this->fm->update_datos_formulario($datos_post_formulario, $datos_formulario, $elemento_seccion, $id_censo, $id_file_comprobante, $nombe_file, $ruta_archivo, $datos_files, $detalle_censo);
        return $array_result;
    }

    /**
     * @author LEAS
     * @fecha 20/04/2017
     * @param type $elemento_seccion sección del formulario o tipo de formuario,
     * @param type $id_censo identificador del censo
     * @return type Array con todos los campos del formulario solicitado para
     * el caso en que el "element_seccion" sea null. Si "id_censo" no es null,
     * obtiene unicamente los valores de el registro de censo en especifico
     */
    protected function get_campos_formulario($elemento_seccion = null, $id_censo = null) {
        $param = null;
        if (!is_null($elemento_seccion)) {
            $param = array('f.id_elemento_seccion' => $elemento_seccion);
        }
        $this->load->model('Formulario_model', 'fm');
        $formulario = $this->fm->get_campos_formulario($param, $id_censo);
        return $formulario;
    }

    /**
     * @fecha 20/04/2017
     * @author LEAS
     * @param type Array $descripcion_formulario contiene toda la definición
     * del formulario configurada en la base de datos
     * @param type Bool $is_comprobante Indica que debe o no conteplar al comprobante
     * @return type Description Retorna un array con todas las reglas que aplican
     * para un formulario del censo en especifico. Tomar en cuenta que las
     * valiaciones se definen la tabla "ui.campos_formulario" de la base de datos
     */
    protected function get_rules_formulario($descripcion_formulario = null, $datos_post = null) {
        $reglas_validacion = NULL;
        //pr('$rules-----------------');
        //pr($descripcion_formulario);
        if (!is_null($descripcion_formulario)) {
//            pr($descripcion_formulario);
            $campos_dependientes = [];
            foreach ($descripcion_formulario as $value) {//Recorre campos paderes que tengan dependencias de hijos
                $json_decode = json_decode($value['campos_dependientes'], true); //Regresa un arreglo asociativo
                if (!empty($json_decode)) {
                    if (isset($json_decode['campos'])) {
                        foreach ($json_decode['campos'] as $campos) {
                            if (isset($json_decode['elementos'][$campos])) {
                                $campos_dependientes[$campos] = array('elementos' => $json_decode['elementos'][$campos], 'padre' => $value['nom_campo']);
                            } else {
                                $campos_dependientes[$campos] = array('elementos' => [], 'padre' => $value['nom_campo']);
                            }
                        };
                    }
                }
            }
//pr($campos_dependientes);
            foreach ($descripcion_formulario as $value) {
                if (!is_null($value['rules']) and ! empty($value['rules'])) {//valida que exista una regla asociada al campo
                    $aplica_regla_validacion = TRUE;
                    if (isset($value['id_censo']) and $value['is_precarga_registro_sistema']) {//Valida que exista un registro de censo, y que el campo es no editable por ser de precarga
                        $aplica_regla_validacion = FALSE; //Indica que no se le aplique regla de validación, ya que el post por encontrarse bloqueado, no enviara nada pero existe información cargada
                        //pr("No validar");
                    }

                    if ($aplica_regla_validacion) {
                        $decode_rules = json_decode($value['rules'], true); //Decódifica las reglas de validación json to array
                        if (!empty($decode_rules)) {
                            if (isset($campos_dependientes[$value['nom_campo']])) {//Valida que exista una dependencia de campo
                                //pr($decode_rules['rules']);
                                //pr("contiene -> ".preg_match("/required/", 'numeric|rango_1_5'));
                                //if (strpos('required', $decode_rules['rules']) > -1) {//Valida que sea un campo requerido //30092020 no funciono para todo
                                if (preg_match("/required/", $decode_rules['rules']) == 1) {//30092020 se cambio por que aqui si encuentra el valo required con mas valores en la cadena
                                    //pr($value['nom_campo'] ." Is requerido");
                                    $dependiente = $campos_dependientes[$value['nom_campo']];
                                    if (isset($datos_post[$dependiente['padre']])) {//Valida que exista el padre
                                        $padre_valor = $datos_post[$dependiente['padre']];
                                        if (strlen(trim($padre_valor)) > 0) {
                                            if (!empty($dependiente['elementos'])) {//Aplica para todos
                                                //pr($padre_valor);
                                                //pr($dependiente['elementos']);
                                                $encontro_valor = in_array($padre_valor, $dependiente['elementos']);
                                                //pr($encontro_valor);
                                                if (!$encontro_valor) {//Si no encuentra el valor, quitar validación de requerido
                                                    //$decode_rules['rules'] = str_replace('required', "", $decode_rules['rules']); //Elimina requerido por que no selecciono nada el padre
                                                    $decode_rules['rules'] = "";
                                                    /*if(strlen($decode_rules['rules'])> 0 && preg_match("/\^|/", $decode_rules['rules']) == 1){
                                                        $decode_rules['rules'] ;
                                                        pr("existe");
                                                    }*/
                                                }
                                            }
                                        } else {
                                            $decode_rules['rules'] = str_replace('required', "", $decode_rules['rules']); //Elimina requerido por que no selecciono nada el padre
                                        }
                                    } else {//Elimina requerido, ya que es un campo dependiente y el padre no existe (No deberia pasar en un proceso ideal)
                                        $decode_rules['rules'] = str_replace('required', "", $decode_rules['rules']); //Elimina requerido por que no selecciono nada el padre
                                    }
                                }
                            }
                            $decode_rules['rules'] = str_replace("{field}", $value['nom_campo'], $decode_rules['rules']);
                            $decode_rules['field'] = $value['nom_campo']; //Agrega el nombre del campo, para identificar la regla
                            //Permite que se agregue un texto espécifico por campo o que tome como referencia el texto label del campo
                            if (!isset($decode_rules['label']) || empty($decode_rules['label'])) {
                                $decode_rules['label'] = $value['lb_campo'];
                            }
                            $reglas_validacion[] = $decode_rules;
                        }
                    }
                }
            }
        }

        return $reglas_validacion;
    }

    /**
     * @fecha 20/04/2017
     * @author LEAS
     * @param type $elemento_seccion identificador del elemento seccion que define al formulario
     * @return type Description obtiene todos los catálogos de un formulario
     * aplicando excepciones en el caso de que existan, es decir, que aparescan o no ciertos elementos del catálogo
     */
    protected function get_elementos_catalogos_formulario($elemento_seccion, $detalle_censo = NULL, $extras_post = array()) {
        $array_result = array();
        if (!is_null($elemento_seccion)) {
            $param = array('f.id_elemento_seccion' => $elemento_seccion);
            $this->load->model('Formulario_model', 'fm');
            $formulario = $this->fm->get_catalogos_formulario($param);
            $valida_campo_otros = FALSE;
            if (!is_null($detalle_censo)) {//Valida que no sea una actualización
                $json_campos = json_decode($detalle_censo['formulario_registros']);
                if (!empty($json_campos)) {//Valida que contenga datos
                    $json_campos = (array) json_decode($detalle_censo['formulario_registros']);
                    $valida_campo_otros = TRUE;
//                    pr($json_campos);
                }
            }
            if (!is_null($formulario) AND ! empty($formulario)) {//Valida que el formulario este definido con por lo menos un catálogo
                $parametros = array(//Parametros de select y order by
                    'select' => array('ec.id_elemento_catalogo', 'ec.nombre', 'ec.id_catalogo', 'ec.id_catalogo_elemento_padre', 'ec.label', 'ec.is_validado', 'ec.activo'),
                    'order_by' => array('ec.orden', 'ec.label'));
                $this->load->model('Secciones_model', 'cm'); //Carga módelo de catálogos
                foreach ($formulario as $catalogo) {
                    $parametros['where'] = array('ec.id_catalogo' => $catalogo['id_catalogo']);
                    //Valida que existan excepciones de catálogo, si existen, carga la condición
                    $condicionantes = '';
                    //1  Aparecen solo los de la lista 2  Excluya los de la lista 3  Para que aparezcan los hijos del padre mencionado en la listaaci
                    //ó  Vacío o null No aplica reglas y  aparecen todas las opciones del catalogo
//                    $json = json_decode($catalogo['excepciones_opciones']);
//                    if (!empty($json)) {//Es un json
////                            $values_arra = $json->this;
//                        if (isset($json->this)) {//Si existe, le agrega los valores de la excepcion
//                            $catalogo['excepciones_opciones'] = $json->this;
//                        }
//                    }
                    /**
                     * Aplica reglas de dependencia a campos
                     */
                    switch ($catalogo['reglas_catalogos']) {
                        case En_reglas_catalogo::UNICOS_ELEMENTOS://Únicamente los siguientes elementos
//                    pr($catalogo['reglas_catalogos']);
                            if (!is_null($catalogo['excepciones_opciones']) and ! empty($catalogo['excepciones_opciones'])) {
                                if ($valida_campo_otros) {//Valida que selecciono alguna opción no indicada basado en nuevos registros
                                    if (isset($json_campos[$catalogo['nombre_campo']])) {
                                        $val_catalogo = $json_campos[$catalogo['nombre_campo']];
                                        $excepciones = strpos($catalogo['excepciones_opciones'], $val_catalogo);
                                        if ($excepciones == FALSE) {//No existe, lo debe agregar
                                            $catalogo['excepciones_opciones'] .= ",$val_catalogo";
                                        }
                                    }
                                }
                                $condicionantes = 'ec.id_elemento_catalogo in' . '(' . $catalogo['excepciones_opciones'] . ')';
                            }
                            break;
                        case En_reglas_catalogo::EXCLUIR_ELEMENTOS://Excluir los siguientes elementos
                            if (!is_null($catalogo['excepciones_opciones']) and ! empty($catalogo['excepciones_opciones'])) {
                                if ($valida_campo_otros) {//Valida que selecciono alguna opción no indicada basado en nuevos registros
                                    $catalogo['excepciones_opciones'] .= ",518";
                                    if (isset($json_campos[$catalogo['nombre_campo']])) {
                                        $val_catalogo = $json_campos[$catalogo['nombre_campo']];
                                        $excepciones = strpos($catalogo['excepciones_opciones'], $val_catalogo);
                                        if ($excepciones != false) {//No existe, lo debe agregar
                                            $excepciones_opciones = str_replace($val_catalogo, " ", $catalogo['excepciones_opciones']);
                                            $excepciones_opciones = str_replace(" ,", "", $excepciones_opciones);
                                            $excepciones_opciones = str_replace(", ", "", $excepciones_opciones);
                                            $catalogo['excepciones_opciones'] = $excepciones_opciones; //Retira los excluyentes para que aparezca
                                        }
                                    }
                                }
                                $condicionantes = 'ec.id_elemento_catalogo not in' . '(' . $catalogo['excepciones_opciones'] . ')';
                            }
                            break;
                        case En_reglas_catalogo::ELEMENTOS_DE_PADRES://Únicamente los hijos de los siguientes padres
                            
                            if (!is_null($catalogo['excepciones_opciones']) and ! empty($catalogo['excepciones_opciones'])) {
                                //pr("Aqui valida los elementos de l catalogo ");
                            //pr($catalogo['excepciones_opciones']);
                                $condicionantes = 'ec.id_catalogo_elemento_padre in' . '(' . $catalogo['excepciones_opciones'] . ')';
                                if ($valida_campo_otros) {//Valida que selecciono alguna opción no indicada basado en nuevos registros
                                    if (isset($json_campos[$catalogo['nombre_campo']])) {
                                        $val_catalogo = $json_campos[$catalogo['nombre_campo']];
                                        $condicionantes .= ' or ec.id_elemento_catalogo=' . $val_catalogo;
                                    }
                                }
                            }
                            break;
                        case En_reglas_catalogo::VACIO://Elementos vacios
                            $condicionantes = '';
                            break;
                        default; //Todos los elementos, ya sea que la regla sea null o cero
                            $condicionantes = NULL;
                    }
                    //$incluir_in_not_in = ($catalogo['reglas_catalogos']) ? 'in' : 'not in';
                    if (isset($condicionantes) and empty($condicionantes)) {
//                        pr('vaciossssssss_sss_');
                        $array_result[$catalogo['id_campo'] . $catalogo['id_catalogo']] = array();
                    } else {
//                        $array_result[$catalogo['id_campo'] . $catalogo['id_catalogo']] = array();
                        $parametros['where'][$condicionantes] = NULL;
//                        $this->load->model('Secciones_model', 'cm'); //Carga módelo de catálogos
//                        pr($parametros);
                        //Envía parámetros para obtener los elementos del catálogo solicitado
                        $array_result[$catalogo['id_campo'] . $catalogo['id_catalogo']] = $this->cm->get_elementos_catalogos($parametros);
                    }
                    if (!empty($extras_post) AND isset($extras_post[$catalogo['nombre_campo']])) {//Agrega opcion otro que viene del post, es temporal
                        foreach ($extras_post[$catalogo['nombre_campo']] as $value_extras_otro) {
                            $array_result[$catalogo['id_campo'] . $catalogo['id_catalogo']][] = $value_extras_otro;
                        }
//                        pr($array_result[$catalogo['id_campo'] . $catalogo['id_catalogo']]);
                    }
                }
            }
        }
        return $array_result;
    }

    /**
     *
     * @fecha 08/05/2017
     * @author LEAS
     * @param type $seccion sección
     */
    public function carga_seccion($seccion) {
        $seccion_id = decrypt_base64($seccion);
        $this->load->model('Secciones_model', 'csm');
        $secciones_padre = $this->csm->get_elemento_seccion($seccion_id);
        $datos['opciones_select'] = dropdown_options($secciones_padre, 'id_elemento_seccion', 'label');
        $datos['seccion'] = $seccion_id;
        $this->load->view('tc_template/secciones/select_seccion.php', $datos, FALSE);
    }

    /**
     * @fecha 08/05/2017
     * @author LEAS
     * @param type $seccion_padre "elemento_seccion"
     * @return type Description: Si el elemento contiene hijos, retorna código
     * html (un componente dropdown para ser precisos) con las opciones hijos del elemento que solicita.
     * Si el elemnto_seccion es el últim nivel (no tiene hijos), entonces retorna un JSON con la siguiente estructura
     * {"ejecuta_formulario":"id_elemento_seccion","ruta_form":"Controlador_actual"} //Elementos que identifican un formulario para cargar
     * @post
     * array(
     *  nivel=>'' //Indica el nivel o numero identificador del combo que solicita la petición de llamar a los hijos o al formulario
     * );
     */
    public function carga_elemento_seccion($seccion_padre) {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                $data_post = $this->input->post(null, TRUE);
//                pr($data_post);
//                exit();
                $this->load->model('Secciones_model', 'csm');
                $secciones_padre = $this->csm->get_elemento_seccion_hijo($seccion_padre);
                $datos['opciones_select'] = dropdown_options($secciones_padre, 'id_elemento_seccion', 'label');
//                pr($seccion_padre);
                if (empty($datos['opciones_select'])) {
                    $json_data['ejecuta_formulario'] = $seccion_padre; //Sección que define un formulario
                    $json_data['ruta_form'] = $this->uri->rsegment(1); //Nombre del controlador
                    $r = json_encode($json_data);
                    echo $r;
                    exit();
                }
                $datos['nivel'] = intval($data_post['nivel']) + 1;
//                pr($datos['nivel']);
                $this->load->view('tc_template/secciones/select_seccion_hijo.php', $datos, FALSE);
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    /**
     * @fecha 08/05/2017
     * @deprecated since version number
     *
     */
    public function get_secciones_padres() {
        $this->load->model('Secciones_model', 'csm');
        $secciones_padre = $this->csm->get_secciones_padres();
        return $secciones_padre;
    }

    /**
     * @fecha 30/05/2017
     * @author HPTZ
     * @return type Array Description Array con los elementos hijos de un "elemento_seccion"
     * @post array(
     *      idcombo=>'' //Identificador de un elemento sección
     * );
     *
     */
    public function llena_opciones() {
        //pr('fasfasdf');
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $this->load->model('Secciones_model', 'csm');

            $datos_post = $this->input->post(null, true);
            // $where = array('id_catalogo_elemento_padre' => $idcombo);
            $where = array('d.id_elemento_catalogo_padre' => $datos_post['elemento_catalogo_opadre'],
                'r.id_catalogo_padre' => $datos_post['catalogo_padre'],
                'r.id_catalogo_hijo' => $datos_post['catalogo_hijo'],
                'd.clave_regla_dependecia_catalogo' => $datos_post['regla_catalogo'],
            );
            $parametros = array('select' => array(
                    'id_elemento_catalogo', 'label',
                ),
                'where' => $where,
                'where_or' => array('id_elemento_catalogo' => $datos_post['elemento_catalogo_hijo']),
            );
            $result = $this->csm->get_elementos_catalogos_reglas($parametros);
            //pr($result);

            echo json_encode($result);
        }
    }

    /**
     * Codigos especiales de validaciones
     */

    /**
     *
     * @param type $str valor o texto del elemento post
     * @param type $parametros nombre de los elementos post a validar, para el
     * caso en particular son las dos fechas de vigencia (cert_vigencia_inicial y cert_vigencia_termino)
     * para el caso de que existan
     * @return boolean Si no existen los datos post solicitados ($parametros,
     * es decir, cert_vigencia_inicial y cert_vigencia_termino) retorna true,
     * para el caso en que existan parametros y alguno sea vacio o no exista retorna true.
     * para tal caso, valida que el erchivo no sea vacio
     *
     */
    public function valida_file_certificado_concejo($str, $parametros) {
        //file_certificado, cert_vigencia_inicial, cert_vigencia_termino
        if (!empty($parametros)) {//Valida que lleguen parametros a validar
            $post_param = explode(',', $parametros);
            $is_empty_param = FALSE;
            foreach ($post_param as $value) {
                $val_post = $this->input->post($value);
                if (is_null($val_post) || empty($val_post)) {
                    $is_empty_param = TRUE;
                    break;
                }
            }
            if (!$is_empty_param) {
                //Valida carga de archivo
                return !empty($str);
            }
        }
        return TRUE;
    }

    /**
     * @author LEAS
     * @fecha 20/06/2017
     * @param type $str valor del campo en post
     * @param type $field parametros dependientes, separados por el simbolo "~"
     * el maximo de parametros es 2 y minimo 1, de no ser así retorna "true" siempre
     * Para el primer caso:
     *      primer parametro hace referencia al nombre del campo en "post"
     *      del que depende el componente actual, y el segundo es el valor que deberá
     *      que "post" debera tener para que el componente sea "required"
     * Para el segundo caso:
     *      primer parametro es la llave "post", si este existe y es diferente de vacio
     *      entonces el componente actual se volvera "required"
     * @return boolean True si pasa la validacion, false si no cumple las condiciones
     *
     */
    public function required_depends($str, $field) {
        $parte = explode('~', $field);
        switch (count($parte)) {
            case 2:
                list($post_key, $post_val) = $parte;
                if ($_POST[$post_key] == $post_val) {
                    return $str != "";
                }
                break;
            case 1:
                if (isset($_POST[$parte[0]]) and ! empty($_POST[$parte[0]])) {
                    return $str != "";
                }
                break;
        }
        return true;
    }

    /**
     * @author LEAS
     * @fecha 20/06/2017
     * @param type $str valor del campo en post
     * @param type $field parametros dependientes, separados por el simbolo "~"
     * el maximo de parametros es 2 y minimo 1, de no ser así retorna "true" siempre
     * Para el primer caso:
     *      primer parametro hace referencia al nombre del campo en "post"
     *      del que depende el componente actual, y el segundo es el valor que deberá
     *      que "post" debera tener para que el componente sea "required"
     * Para el segundo caso:
     *      primer parametro es la llave "post", si este existe y es diferente de vacio
     *      entonces el componente actual se volvera "required"
     * @return boolean True si pasa la validacion, false si no cumple las condiciones
     *
     */
    public function obliga_actualiza_certificado($str, $field) {
        if (isset($_POST[$field]) and ! empty($_POST[$field])) {
//            $is_mayor_actual = valida_fecha_mayor_actual($_POST[$field]);
            return valida_fecha_mayor_actual($_POST[$field]);
        }
        return true;
    }

}
