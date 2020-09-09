<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase que contiene la gestion de formularios
 * @version     : 1.0.0
 * @fecha   : 09052017
 * @author      : Lchrigarc
 * @modificacion: HPTZ
 * @modificacion: CPMS 07/2017
 * */
class Formulario extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $this->load->library('form_complete');
        $this->load->model('secciones_model', 'sm');

        $this->load->model('catalogo_model', 'cm');
        $this->load->model('formulario_model', 'fm');
        $this->load->library('Core_auxiliares', array(
            'db' => $this->db, 'formulario' => $this->fm, 'catalogo' => $this->cm
        ));
    }

    public function index() {
        try {
            $output = array();

            $secciones = $this->sm->get_seccion(null,'label');
            $secciones_lista = array();
            foreach ($secciones as $key => $value) {
                $secciones_lista[$value['id_seccion']] = $value['label'];
            }
            $output['secciones'] = $secciones_lista;

            $vista = $this->load->view('formulario/rama_subsecciones.tpl.php', $output, true);
            $this->template->setTitle("Formularios");
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function ajax_subseccion_padre()
    {
        $get = $this->input->get(null,true);

        $padre = null;
        $seccion = null;
        $nivel = 0;

        if(isset($get['padre'])){
            if($get['padre'] != '' && $get['padre'] != 'null'){
                $padre = $get['padre'];
            }
        }

        if(isset($get['seccion'])){
            if($get['seccion'] != '' && $get['seccion'] != 'null'){
                $seccion = $get['seccion'];
            }
        }

        if(isset($get['nivel'])){
            if($get['nivel'] != '' && $get['nivel'] != 'null'){
                $nivel = $get['nivel'];
            }
        }

        $lista = $this->sm->get_elementos_seccion($padre,$seccion);
        $output = array(
                'opciones' => $lista,
                'nivel' => $nivel
            );

        $resultado['length'] = count($lista);
        if($resultado['length'] > 0){
            $resultado['view'] = $this->load->view('formulario/select_subseccion.tpl.php',$output,true);
        }else{
            $resultado['view'] = '';
        }

        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($resultado);
    }


    public function tipo_campo() {
        try {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('tipo_campo');
            $crud->set_subject('Tipos de campos');
            $crud->set_primary_key('id_tipo_campo');

            $crud->columns('id_tipo_campo', 'nombre', 'descripcion', 'activo');
            $crud->fields('nombre', 'descripcion', 'activo');

            $crud->required_fields('activo','nombre');
            //$crud->set_rules('nombre','nombre','required|not_space');

            $crud->display_as('id_tipo_campo', 'id');

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $crud->unset_texteditor('descripcion', 'full_text');

            $crud->unset_delete();
            $crud->unset_read();
            $crud->unset_export();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_dato_campo() {
        try {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('tipo_dato_campos');
            $crud->set_subject('Tipos de datos de los campos');
            $crud->set_primary_key('id_tipo_dato');

            $crud->columns('id_tipo_dato', 'nombre', 'descripcion');
            $crud->fields('nombre', 'descripcion');

            $crud->required_fields('nombre');
            //$crud->set_rules('nombre','Nombre','required|not_space');

            $crud->display_as('id_tipo_dato', 'id');

            $crud->unset_texteditor('descripcion', 'full_text');

            $crud->unset_delete();
            $crud->unset_read();
            $crud->unset_export();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function formulario($id_elemento_seccion=null) {

        $id_elemento_seccion = !is_numeric($id_elemento_seccion)?null:$id_elemento_seccion;

        try {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            if(!is_null($id_elemento_seccion)){
                $crud->where('id_elemento_seccion',$id_elemento_seccion);
            }
            $crud->set_table('formulario');
            $crud->set_subject('Formularios');
            $crud->set_primary_key('id_formulario');

            $crud->columns('id_formulario', 'nombre', 'label', 'id_elemento_seccion', 'descripcion', 'activo');
            $crud->add_fields('nombre', 'label', 'id_elemento_seccion', 'descripcion', 'css', 'ruta_file_js');
            $crud->edit_fields('nombre', 'label', 'id_elemento_seccion', 'descripcion', 'css', 'ruta_file_js', 'activo');

            //$crud->set_rules('nombre','nombre','required|not_space');
            $crud->required_fields('nombre','label','id_elemento_seccion','activo');

            $data_elementos_seccion = $this->sm->get_sub_secciones_c(null, null, true);
            $crud->field_type('id_elemento_seccion', 'dropdown', $data_elementos_seccion);


            $crud->display_as('id_formulario', 'id');
            $crud->display_as('label', 'Etiqueta');
            $crud->display_as('id_elemento_seccion', 'Subseccion');
            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));


            $crud->add_action('Campos', '', '', ' ui-icon-grip-solid-horizontal', array($this, 'campos_formulario_link'));
            $crud->add_action('Editar', '', '', 'ui-icon-pencil', array($this, 'editar_formulario_link'));

            $crud->unset_texteditor('descripcion', 'full_text');
            $crud->unset_texteditor('css', 'full_text');
            $crud->unset_texteditor('ruta_file_js', 'full_text');

            $crud->callback_before_insert(array($this,'desactivar_formularios'));

            // $crud->unset_delete();
            $crud->unset_read();
            $crud->unset_export();
            $crud->unset_edit();

            $crud->callback_delete(array($this->core_auxiliares,'delete_formulario'));
            $crud->set_lang_string('delete_error_message', 'No se puede borrar el elemento seleccionado porque cuenta con dependencias registradas');

            $output = $crud->render();

            $main_content = $this->load->view('formulario/formulario.tpl.php', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function desactivar_formularios($post_array){
        $seccion = $post_array['id_elemento_seccion'];
        $forms_asociados = $this->fm->get_formularios(array('id_elemento_seccion'=>$seccion));
        $forms = array();
        foreach ($forms_asociados as $key => $value) {
            array_push($forms, $key);
        }
        $this->fm->desactivar_formularios($seccion,$forms);
        $post_array['activo'] = true;
        return $post_array;
    }

    public function editar_formulario_link($primary_key,$row){
        return site_url('formulario/editar_formulario/' . $row->id_formulario);
    }

    public function editar_formulario($id_formulario){
        try{
            $output = array();
            $output['id_formulario'] = $id_formulario;

            $output['subsecciones'] = $this->sm->get_sub_secciones_c();

            $datos = $this->fm->get_formularios(array('id_formulario'=>$id_formulario));
            $datos = $datos[$id_formulario];
            $datos_post = $this->input->post(null, true);

            //pr($datos);

            if($datos_post){
                $output['datos'] = $datos_post;

                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('formulario');
                $this->form_validation->set_rules($validations);


                if($this->form_validation->run() == TRUE){
                    //pr($datos_post);
                    if($datos_post['activo']){
                        $temp = $this->desactivar_formularios(array( 'id_elemento_seccion' => $datos_post['id_elemento_seccion']));
                    }
                    if($this->fm->update_formulario($id_formulario,$datos_post)){

                        echo json_encode(array(
                                "created"=>1,
                                "base" => 1,
                                "html"=>html_message("El formulario fue actualizado exitosamente","success")
                            )
                        );
                    }
                    else
                    {
                        echo json_encode(array(
                                "created"=>0,
                                "base" => 1,
                                "html"=>html_message("No se pudo actualizar el formulario","danger")
                            )
                        );
                    }
                }else{
                    $main_content = $this->load->view('formulario/editar.tpl.php', $output, true);
                    echo json_encode(array(
                            "created"=> 0,
                            "base" => 0,
                            "html" => $main_content
                        )
                    );
                }

            }else{
                $output['datos'] = $datos;
                $main_content = $this->load->view('formulario/editar.tpl.php', $output, true);
                $this->template->setMainContent($main_content);
                $this->template->getTemplate();
            }
        } catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    public function campos_formulario_link($primary_key, $row) {
        return site_url('formulario/campos_formulario/' . $row->id_formulario);
    }

    public function campos_formulario($id_formulario=0){
        $this->load->config('catalogos');
        if($this->input->post('copiar'))
        {
            $post = $this->input->post(null, true);
            $status = $this->fm->copiar_formulario($id_formulario, $post['copiar']);
            $output['status'] = $status;
        }
        $f_query = $this->config->item('formularios_activos');
        $formularios_activos = dropdown_options($this->cm->get_registros('ui.formulario', $f_query), 'id_formulario', 'arbol');
        $output['formularios_activos'] = $formularios_activos;
        try{
            $this->db->schema = 'ui';

            $data = array();
            $data['id_formulario'] = $id_formulario;

            $crud = $this->new_crud();
            $formulario = $this->fm->get_formulario($id_formulario); //buscamos el formulario

            $crud->set_table('campos_formulario');
            $crud->set_subject('Campos formulario');
            $crud->set_primary_key('id_campos_formulario');
            $crud->set_relation('id_campo', 'ui.campo', 'label');
            $crud->set_relation('id_catalogo', 'catalogo.catalogo', 'descripcion');
            $crud->where('id_formulario', $id_formulario);

            $crud->columns('id_campos_formulario', 'id_campo', 'id_catalogo', 'activo', 'tooltip');

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $crud->add_action('Editar', '', '', 'ui-icon-pencil', array($this, 'editar_campo_formulario_link'));
            $crud->add_action('Ver', '', '', 'ui-icon-info', array($this, 'ver_campo_formulario_link'));

            // $crud->unset_delete();
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_read();
            $crud->unset_export();
            $crud->callback_delete(array($this->core_auxiliares,'delete_campos_formularios'));
            $crud->set_lang_string('delete_error_message', 'No se puede borrar el elemento seleccionado porque cuenta con dependencias registradas');

            $output['crud'] = $crud->render();
            $output['id_formulario'] = $id_formulario;

            $main_content = $this->load->view('formulario/campos_form.tpl.php', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function ver_campo_formulario_link($value, $row) {
        return site_url('formulario/ver_campo_formulario/' . $row->id_campos_formulario);
    }

    public function ver_campo_formulario($id_campos_formulario){
        try{
            $output = array();

            $registro = $this->fm->get_campos_formulario_info(array($id_campos_formulario));
            $registro = $registro[$id_campos_formulario];

            $output = $this->get_listas_campo($registro['id_formulario']);
            $output['id_campos_formulario'] = $id_campos_formulario;
            $output['datos'] = $registro;

            $id_catalogo = $registro['id_catalogo'];
            if(!is_null($id_catalogo) && !is_null($registro['reglas_catalogos'])){
                if(!is_null($registro['excepciones_opciones'])){
                    // pr($id_catalogo);
                    $opciones_catalogo = $this->cm->get_listado_excepciones_opciones(array('id_catalogo'=>$id_catalogo));
                    // pr($id_catalogo);
                    // pr($registro);
                    // pr($opciones_catalogo);

                    $ids_excepciones = explode(',',$registro['excepciones_opciones']);
                    $arr = array();
                    foreach ($ids_excepciones as $key => $value) {
                        if(isset($opciones_catalogo[$value]))
                        {
                            $arr[$key] = $opciones_catalogo[$value];
                        }
                    }
                    $output['excepciones_opciones'] = $arr;
                }
            }

            $main_content = $this->load->view('formulario/ver_campo_form.tpl.php', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    public function nuevo_campo_formulario($id_formulario=0){
        try {
            $output = $this->get_listas_campo($id_formulario, true);
            $output['id_formulario'] = $id_formulario;
            $datos = $this->input->post(null, true);
            //pr($datos);

            $output['datos'] = $datos;

            if($datos){
                if($datos['id_catalogo']!=''){
                    $output['excepciones_opciones_lista'] = $this->cm->get_listado_excepciones_opciones(array('id_catalogo'=>$datos['id_catalogo']));
                }

                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('campos_formulario');
                $this->form_validation->set_rules($validations);
                //pr($datos);

                if($this->form_validation->run() == TRUE)
                {
                    $campo = $datos['id_campo'];
                    $campos_lista = $output['campos_dependientes_lista'];
                    $campos_existentes = array();
                    foreach ($campos_lista as $key => $value) {
                        array_push($campos_existentes, $value['nombre']);
                    }

                    if(!in_array($campo,$campos_existentes)){

                        if(isset($datos['rules'])){
                            $reglas = $datos['rules'];
                            sort($reglas);
                            if($reglas[0]==''){
                                unset($reglas[0]);
                            }
                            if(count($reglas)>0){
                                $reglas_arr = $this->fm->set_rule_json($campo,$reglas);
                                $datos['rules'] = json_encode($reglas_arr);
                            }else{
                                $datos['rules'] = null;
                            }
                        }

                        if(isset($datos['excepciones_opciones'])){
                            $datos['excepciones_opciones'] = implode(',', $datos['excepciones_opciones']);
                        }
                        if(isset($datos['campos_dependientes']) & !$datos['campos_dependientes']){
                            //$datos['campos_dependientes'] = implode(',', $datos['campos_dependientes']);
                            $datos['campos_dependientes'] = null;
                        }
                        if(!$datos['mostrar_datatable']){
                            $datos['mostrar_datatable'] = null;
                        }
                        if(!$datos['id_callback']){
                            $datos['id_callback'] = null;
                        }
                        if(!$datos['id_catalogo']){
                            $datos['id_catalogo'] = null;
                        }
                        if(!$datos['is_precarga']){
                            $datos['is_precarga'] = null;
                        }
                        if(isset($datos['reglas_catalogos']) && !$datos['reglas_catalogos']){
                            $datos['reglas_catalogos'] = null;
                        }
                        if(isset($datos['clave_regla_dependencia_catalogo']) && !$datos['clave_regla_dependencia_catalogo']){
                            $datos['clave_regla_dependencia_catalogo'] = null;
                        }
                        if(isset($datos['label_personalizado']) && !$datos['label_personalizado']){
                            $datos['label_personalizado'] = null;
                        }

                        //pr($datos);
                        if($this->fm->create_campo($datos)){
                            echo json_encode(array(
                                    "created"=>1,
                                    "base" => 1,
                                    "html"=>html_message("El campo fue creado exitosamente","success")
                                )
                            );
                        }
                        else
                        {
                            echo json_encode(array(
                                    "created"=>0,
                                    "base" => 1,
                                    "html"=>html_message("No se pudo crear el campo","danger")
                                )
                            );
                        }
                    }else{
                        echo json_encode(array(
                                "created"=>0,
                                "base" => 1,
                                "html"=>html_message("El campo ya existe en este formulario","danger")
                            )
                        );
                    }
                }
                else
                {
                    $main_content = $this->load->view('formulario/nuevo_campo_form.tpl.php', $output, true);
                    echo json_encode(array(
                            "created"=> 0,
                            "base" => 0,
                            "html" => $main_content
                        )
                    );
                }
            }else{
                $main_content = $this->load->view('formulario/nuevo_campo_form.tpl.php', $output, true);
                $this->template->setMainContent($main_content);
                $this->template->getTemplate();
            }
        } catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    public function editar_campo_formulario_link($value, $row) {
        return site_url('formulario/editar_campo_formulario/' . $row->id_campos_formulario);
    }

    public function editar_campo_formulario($id_campos_formulario){
        try {
            $output = array();

            $registro = $this->fm->get_campos_formulario_info(array($id_campos_formulario));
            //pr($registro);

            $registro = $registro[$id_campos_formulario];
            //pr($registro);

            $output = $this->get_listas_campo($registro['id_formulario'], true);
            // pr($output);

            $output['id_campos_formulario'] = $id_campos_formulario;

            $datos = $this->input->post(null, true);
            //pr($datos);
            if($datos){
                if($datos['id_catalogo']!=''){
                    $output['excepciones_opciones_lista'] = $this->cm->get_listado_excepciones_opciones(array('id_catalogo'=>$datos['id_catalogo']));
                    $filtros = array(
                        'select' => array('clave_regla_dependecia_catalogo', 'nombre'),
                        'where' => array('id_catalogo_hijo' => $datos['id_catalogo'])
                    );
                    $output['reglas_dependencia_catalogo'] = dropdown_options($this->cm->get_registros('catalogo.reglas_dependencia_catalogos', $filtros), 'clave_regla_dependecia_catalogo', 'nombre');
                }
                //pr($output);
                //pr($registro);

                $output['datos'] = $datos;

                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('campos_formulario');
                $this->form_validation->set_rules($validations);
                //pr($datos);

                if($this->form_validation->run() == TRUE)
                {
                //pr($datos);
                    $campo = $datos['id_campo'];
                    $campos_lista = $output['campos_dependientes_lista'];

                    $campos_existentes = array();
                    foreach ($campos_lista as $key => $value) {
                        array_push($campos_existentes, $value['nombre']);
                    }

                    $key = array_search($campo,$campos_existentes);
                    unset($campos_existentes[$key]);

                    if(!in_array($campo,$campos_existentes)){

                        if(isset($datos['rules'])){
                            $reglas = $datos['rules'];
                            sort($reglas);
                            if($reglas[0]==''){
                                unset($reglas[0]);
                            }
                            if(count($reglas)>0){
                                $reglas_arr = $this->fm->set_rule_json($campo,$reglas);
                                $datos['rules'] = json_encode($reglas_arr);
                            }else{
                                $datos['rules'] = null;
                            }
                        }

                        if(isset($datos['excepciones_opciones'])){
                            $datos['excepciones_opciones'] = implode(',', $datos['excepciones_opciones']);
                        }
                        if(isset($datos['campos_dependientes']) & !$datos['campos_dependientes']){
                            //$datos['campos_dependientes'] = implode(',', $datos['campos_dependientes']);
                            $datos['campos_dependientes'] = null;
                        }
                        if(strlen(trim($datos['mostrar_datatable'])) < 1){
                            $datos['mostrar_datatable'] = null;
                        }
                        if(!$datos['id_callback']){
                            $datos['id_callback'] = null;
                        }
                        if(strlen(trim($datos['id_catalogo']))< 1){
                            $datos['id_catalogo'] = null;
                        }
                        if(strlen(trim($datos['is_precarga']))< 1){
                            $datos['is_precarga'] = null;
                        }
                        if(isset($datos['reglas_catalogos']) && strlen(trim($datos['reglas_catalogos'])) < 1){
                            $datos['reglas_catalogos'] = null;
                        }
                        if(isset($datos['clave_regla_dependencia_catalogo']) && !$datos['clave_regla_dependencia_catalogo']){
                          $datos['clave_regla_dependencia_catalogo'] = null;
                        }
                        if(isset($datos['label_personalizado']) && !$datos['label_personalizado']){
                          $datos['label_personalizado'] = null;
                        }

                        //pr($datos);

                        if($this->fm->update_campo($datos)){
                            echo json_encode(array(
                                    "created"=>1,
                                    "base" => 1,
                                    "html"=>html_message("El campo fue actualizado exitosamente","success")
                                )
                            );
                        }
                        else
                        {
                            echo json_encode(array(
                                    "created"=>0,
                                    "base" => 1,
                                    "html"=>html_message("No se pudo actualizar el campo","danger")
                                )
                            );
                        }

                    }else{
                        echo json_encode(array(
                                "created"=>0,
                                "base" => 1,
                                "html"=>html_message("El campo ya existe en el formulario","danger")
                            )
                        );
                    }
                }
                else
                {
                    $main_content = $this->load->view('formulario/editar_campo_form.tpl.php', $output, true);
                    echo json_encode(array(
                            "created"=> 0,
                            "base" => 0,
                            "html" => $main_content
                        )
                    );
                }
            }else{

                if(!is_null($registro['excepciones_opciones'])){
                    $output['excepciones_opciones_lista'] = $this->cm->get_listado_excepciones_opciones(array('id_catalogo'=>$registro['id_catalogo']));
                }
                $filtros = array(
                    'select' => array('clave_regla_dependecia_catalogo', 'nombre'),
                    'where' => array('id_catalogo_hijo' => $registro['id_catalogo'])
                );
                $output['reglas_dependencia_catalogo'] =dropdown_options($this->cm->get_registros('catalogo.reglas_dependencia_catalogos', $filtros), 'clave_regla_dependecia_catalogo', 'nombre');
                //pr($output);

                $registro['rules'] = $this->fm->from_rule_json($id_campos_formulario);
                 /*if(strlen(trim($registro['id_catalogo'])) > 1){
                            $datos['id_catalogo'] = null;
                $registro['id_catalogo'] = $this->ajax_excepcion_catalogo();
                    }*/
                $output['datos'] = $registro;
                $main_content = $this->load->view('formulario/editar_campo_form.tpl.php', $output, true);
                $this->template->setMainContent($main_content);
                $this->template->getTemplate();
            }
        } catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    private function get_listas_campo($id_formulario, $con_nombre = false){
        $output = array();

        // Obtenemos la lista de campos
        $data_campos = $this->fm->get_campos(null,1, true);
        //pr($data_campos);
        $campos_lista = array();
        foreach ($data_campos as $key => $value) {
            $campos_lista[$key] = $value['label'];
        }
        $output['campos_lista']= $campos_lista;

        //Obtenemos la lista de catalogos
        $data_catalogos = $this->cm->get_catalogos();
        $catalogo_lista = array();
        foreach ($data_catalogos as $key => $value) {
            $catalogo_lista[$value['id_catalogo']] = $value['label'];
        }
        $output['catalogo_lista'] = $catalogo_lista;

        //Obtenemos la lista de reglas
        $data_rules = $this->cm->get_listado_reglas();
        $output['rules_lista'] = $data_rules;

        //Obtenemos la lista de callback
        $data_callback = $this->cm->get_listado_callback_opciones();
        $output['callback_lista'] = $data_callback;

        //Obtenemos la lista de reglas aplicables a los elementos de catalogo
        $data_reglas_catalogo = array(
                '0' => 'Todos',
                '1' => 'Aparecen solo las opciones del listado',
                '2' => 'Se excluyen las opciones del listado',
                '3' => 'Aparecen las opciones dependientes del listado',
                '4' => 'Ninguno'
                );
        $output['reglas_catalogo_lista'] = $data_reglas_catalogo;

        //Obtenemos la lista de campos del formulario
        $data_campos_dependientes = $this->fm->get_label_campos_asociados($id_formulario);
        $output['campos_dependientes_lista'] = $data_campos_dependientes;

        return $output;
    }


    public function ajax_excepcion_catalogo($id_catalogo){
        $opciones = $this->cm->get_listado_excepciones_opciones(array('id_catalogo'=>$id_catalogo));
        echo json_encode($opciones);
    }

    public function ajax_reglas_dependencia_catalogos($id_catalogo)
    {
        $filtros = array(
            'select' => array('clave_regla_dependecia_catalogo', 'nombre'),
            'where' => array('id_catalogo_hijo' => $id_catalogo)
        );
        $registros = $this->cm->get_registros('catalogo.reglas_dependencia_catalogos', $filtros);
        $opciones = [];
        foreach ($registros as $index => $value)
        {
            $opciones[$value['clave_regla_dependecia_catalogo']] = $value['nombre'];
        }
        echo json_encode($opciones);
    }

    public function callback() {
        try {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('callback');
            $crud->set_subject('Callbacks');
            $crud->set_primary_key('id_callback');

            $crud->columns('id_callback', 'label','tipo_evento','ruta_js','funcion_js','activo');
            $crud->fields('label', 'descripcion', 'tipo_evento','ruta_js','funcion_js','activo');
            $crud->required_fields('label','funcion_js','ruta_js','activo');

            $crud->display_as('label', 'Etiqueta');
            $crud->display_as('id_callback','id');
            $crud->display_as('descripcion','Descripción');
            $crud->unset_texteditor('descripcion', 'full_text');
            $crud->unset_texteditor('funcion_js', 'full_text');
            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $crud->unset_delete();
            $crud->unset_export();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function lista_reglas_validacion() {
        try {
            $this->db->schema = 'catalogo';
            $crud = $this->new_crud();
            $crud->set_table('lista_rules_validaciones');
            $crud->set_subject('Reglas de validación');
            $crud->set_primary_key('id_rules_validaciones');

            $crud->columns('id_rules_validaciones', 'nombre', 'label', 'descripcion', 'activo');
            $crud->fields('nombre', 'label', 'rule','descripcion', 'activo', 'orden');

            $crud->required_fields('nombre','label','rule','activo');
            //$crud->set_rules('nombre','nombre','required|not_space');

            $crud->display_as('id_rules_validaciones', 'id');
            $crud->display_as('label', 'Etiqueta');
            $crud->display_as('rule', 'Regla');

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $crud->unset_texteditor('rule', 'full_text');
            $crud->unset_texteditor('descripcion','full_text');

            $crud->unset_delete();
            $crud->unset_export();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function campo() {
        try {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('campo');
            $crud->set_subject('Campo');
            $crud->set_primary_key('id_campo');

            $crud->set_relation('id_tipo_campo', 'tipo_campo', 'nombre');
            $crud->set_relation('id_tipo_dato', 'tipo_dato_campos', 'nombre');
            $crud->set_relation('id_callback','callback','label');

            $crud->columns('id_campo', 'nombre', 'label', 'id_tipo_campo', 'id_tipo_dato', 'activo');
            $crud->fields('nombre', 'label', 'descripcion', 'id_tipo_campo', 'id_tipo_dato', 'id_callback', 'icono','activo');

            $crud->required_fields('nombre','id_tipo_campo','label','activo','id_tipo_dato');
            //$crud->set_rules('nombre','nombre','required|not_space');

            $crud->display_as('id_campo','id');
            $crud->display_as('label','Etiqueta');
            $crud->display_as('id_tipo_dato','Tipo de dato');
            $crud->display_as('id_tipo_campo','Tipo de campo');
            $crud->display_as('id_callback','Callback');

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $data_callback_opciones = $this->cm->get_listado_callback_opciones();
            $crud->field_type('id_callback','dropdown',$data_callback_opciones);

            $crud->unset_texteditor('descripcion', 'full_text');

            $crud->unset_delete();
            $crud->unset_export();
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function ajax_configuracion_campos_formulario()
    {
        $params = $this->input->post(null, true);
        $salida = [];
        $salida['campos'] = $this->fm->get_config_campos_formulario($params);
        $filtros['where']['id_catalogo'] = $params['id_catalogo'];
        $filtros['select'] = array('id_elemento_catalogo', 'label nombre');
        $salida['elementos'] = $this->cm->get_registros('catalogo.elementos_catalogos A', $filtros);
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($salida);
    }

}
