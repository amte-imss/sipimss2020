<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Clase que funciona como el controlador de secciones
* @author CPMS
* @date Julio 2017
* @version 1.0
*/

class Secciones extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $this->load->library('form_complete');
        $this->load->model('secciones_model', 'sm');
        //$this->output->enable_profiler(TRUE);
    }

    public function index() {
        $this->load->model('Catalogo_model', 'catalogo');
        $this->load->library('Core_auxiliares', array('db' => $this->db, 'catalogo' => $this->catalogo));
        try {
            $this->db->schema = 'catalogo';
            $crud = $this->new_crud();
            $crud->set_theme('datatables');
            $crud->set_table('secciones');
            $crud->set_subject('Secciones');
            $crud->set_primary_key('id_seccion');

            $crud->columns('id_seccion','nombre', 'label', 'descripcion', 'activo', 'orden');
            $crud->fields('nombre', 'label', 'descripcion', 'activo', 'orden', 'url');

            $crud->required_fields('nombre','activo','label');
//            $crud->set_rules('nombre','nombre','not_space');
            $crud->field_type('orden','integer');

            $crud->display_as('label', 'Etiqueta');

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));
            $crud->unset_texteditor('descripcion', 'full_text');

            $crud->unset_read();
            // $crud->unset_delete();
            $crud->unset_export();
            $crud->callback_delete(array($this->core_auxiliares,'delete_secciones'));
            $crud->set_lang_string('delete_error_message', 'No se puede borrar el elemento seleccionado porque cuenta con dependencias registradas');
            $crud->add_action('Subsecciones', '', '', 'ui-icon-grip-solid-horizontal', array($this->core_auxiliares, 'subsecciones_link'));
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function elementos_seccion() {
        $id_seccion = !is_numeric($this->input->get('id_seccion'))?null:$this->input->get('id_seccion',true);
        $id_elemento_seccion = !is_numeric($this->input->get('id_elemento_seccion'))?null:$this->input->get('id_elemento_seccion',true);
        $this->load->model('Catalogo_model', 'catalogo');
        $this->load->library('Core_auxiliares', array('db' => $this->db, 'catalogo' => $this->catalogo));
        try {
            $this->db->schema = 'catalogo';
            $crud = $this->new_crud();

            if(!is_null($id_seccion)){
                $crud->where('elementos_seccion.id_seccion',$id_seccion);
            }
            if(!is_null($id_elemento_seccion)){
                $crud->where('id_catalogo_elemento_padre',$id_elemento_seccion);
            }else{
                $crud->where('id_catalogo_elemento_padre is null');
            }

            $crud->set_table('elementos_seccion');
            $crud->set_subject('Elementos de secciones');
            $crud->set_primary_key('id_elemento_seccion');

            $crud->set_relation('id_seccion', 'secciones', 'label');

            $crud->columns('id_elemento_seccion', 'nombre', 'label', 'id_seccion', 'id_catalogo_elemento_padre', 'activo','formulario_asociado' , 'descripcion');
            //$crud->fields('nombre','label','id_seccion','id_catalogo_elemento_padre','descripcion','activo');

            $crud->display_as('label', 'Etiqueta');
            $crud->display_as('id_catalogo_elemento_padre', 'Subsección padre');
            $crud->display_as('id_seccion', 'Sección');
            $crud->display_as('id_elemento_seccion', 'id');
            $crud->display_as('formulario_asociado','Tiene formulario activo');

            $crud->callback_column('formulario_asociado',array($this,'formulario_asociado_callback'));

            $crud->change_field_type('activo', 'true_false', array(0 => 'No', 1 => 'Si'));

            $data_elementos_seccion = $this->sm->get_sub_secciones_c($id_seccion, $id_elemento_seccion);
            //pr($data_elementos_seccion);
            $crud->field_type('id_catalogo_elemento_padre', 'dropdown', $data_elementos_seccion);

            $crud->unset_read();
            // $crud->unset_delete();
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_export();

            $crud->add_action('Subsecciones', '', '', 'ui-icon-grip-solid-horizontal', array($this->core_auxiliares, 'subsecciones_hijas_link'));
            $crud->add_action('Formulario', '', '', ' ui-icon-grip-solid-horizontal', array($this->core_auxiliares, 'formulario_asociado_link'));
            $crud->add_action('Editar', '', '', 'ui-icon-pencil', array($this, 'editar_elemento_seccion_link'));
            $crud->callback_delete(array($this->core_auxiliares,'delete_elementos_seccion'));
            $crud->set_lang_string('delete_error_message', 'No se puede borrar el elemento seleccionado porque cuenta con dependencias registradas');
            $output = $crud->render();
            $output->id_seccion = $id_seccion;
            $output->id_elemento_seccion = $id_elemento_seccion;
            $main_content = $this->load->view('secciones/seccion.tpl.php', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function formulario_asociado_callback($value,$row){
        $id_formularios = $this->sm->get_formularios_asociados($row->id_elemento_seccion,true);
        return (count($id_formularios) > 0)? 'Si' : 'No';
    }

    public function editar_elemento_seccion_link($value, $row) {
        return site_url('secciones/editar_elemento_seccion/' . $row->id_elemento_seccion);
    }

    public function editar_elemento_seccion($id_elemento_seccion=null){
        $id_seccion_get = !is_numeric($this->input->get('id_seccion'))?null:$this->input->get('id_seccion',true);
        $id_elemento_seccion_get = !is_numeric($this->input->get('id_elemento_seccion'))?null:$this->input->get('id_elemento_seccion',true);
        try{

            $datos_post = $this->input->post(null, true);
            $output=array();
            $output['id_seccion'] = $id_seccion_get;
            $output['id_elemento_seccion'] = $id_elemento_seccion_get;

            $datos = $this->sm->get_info_elemento_seccion($id_elemento_seccion);
            $datos = $datos[0];

            $secciones = $this->sm->get_seccion();
            $opciones_secciones = array();
            foreach ($secciones as $key => $value) {
                $id = $secciones[$key]['id_seccion'];
                $label = $secciones[$key]['label'];
                $opciones_secciones[$id] = $label;
            }
            $output['secciones'] = $opciones_secciones;

            $seccion = $datos['id_seccion'];
            $subseccion = null;

            if($datos_post){
                //pr($datos_post);
                $output['elemento_seccion'] = $datos_post;

                $subseccion = ($datos_post['id_catalogo_elemento_padre'] != '') ? $datos_post['id_catalogo_elemento_padre'] : null;
                $subsecciones = $this->sm->get_labels($seccion,$subseccion);
                $output['subsecciones'] = $subsecciones;

                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('elemento_seccion');
                $this->form_validation->set_rules($validations);

                $distintos = true;

                if($datos_post['label']!=''){
                    if(strcasecmp(remove_accents($datos_post['label']),remove_accents($datos['label'])) != 0){
                        foreach ($subsecciones as $key => $value) {
                            if(strcasecmp(remove_accents($value),remove_accents($datos_post['label']))==0){
                                $distintos=false;
                                break;
                            }
                        }
                    }
                }

                if($distintos)
                {
                    if($this->form_validation->run() == TRUE)
                    {

                        if($this->sm->update_elemento_seccion($datos_post)){
                            echo json_encode(array(
                                    "updated"=>1,
                                    "base" => 1,
                                    "html"=>html_message("El elemento de sección fue actualizado exitosamente","success")
                                )
                            );
                        }
                        else
                        {
                            echo json_encode(array(
                                    "updated"=>0,
                                    "base" => 1,
                                    "html"=>html_message("No se pudo actualizar el elemento de sección","danger")
                                )
                            );
                        }
                    }
                    else
                    {
                        $main_content = $this->load->view('secciones/editar_elemento.tpl.php', $output, true);
                        echo json_encode(array(
                                "updated"=>0,
                                "base" => 0,
                                "html" => $main_content
                            )
                        );
                    }
                }
                else
                {
                    echo json_encode(array(
                        "updated"=>0,
                        "base"=>1,
                        "html"=>html_message("La etiqueta ya existe, ingresa una diferente","danger")
                        )
                    );
                }

            }
            else
            {
                $output['elemento_seccion']= $datos;

                if($datos['id_catalogo_elemento_padre']){
                    $subseccion = $datos['id_catalogo_elemento_padre'];
                }
                $subsecciones = $this->sm->get_labels($seccion,$subseccion);
                $output['subsecciones'] = $subsecciones;
                // pr($output);
                $main_content = $this->load->view('secciones/editar_elemento.tpl.php', $output, true);
                $this->template->setMainContent($main_content);
                $this->template->getTemplate();
            }


        }catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }

    public function nuevo_elemento_seccion(){
        $id_seccion = !is_numeric($this->input->get('id_seccion'))?null:$this->input->get('id_seccion',true);
        $id_elemento_seccion = !is_numeric($this->input->get('id_elemento_seccion'))?null:$this->input->get('id_elemento_seccion',true);
        try{
            $output=array();
            $output['id_seccion'] = $id_seccion;
            $output['id_elemento_seccion'] = $id_elemento_seccion;
            $secciones = $this->sm->get_seccion();
            $opciones_secciones = array();
            foreach ($secciones as $key => $value) {
                $id = $secciones[$key]['id_seccion'];
                $label = $secciones[$key]['label'];
                $opciones_secciones[$id] = $label;
            }
            $output['secciones'] = $opciones_secciones;
            //pr($opciones_secciones);

            $datos = $this->input->post(null, true);
            $output['datos'] = $datos;
            if ($datos){
                //pr($datos);

                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('elemento_seccion');
                $this->form_validation->set_rules($validations);

                $distintos = true;
                $texto=array();
                $opciones_subseccion=array();

                $subpadre = '';
                if(isset($datos['id_catalogo_elemento_padre'])){
                    $subpadre = $datos['id_catalogo_elemento_padre'];
                }
                $seccion = $datos['id_seccion'];
                if($seccion){
                    if($subpadre != '' && $datos['label']!=''){
                        $opciones_subseccion = $this->sm->get_sub_secciones_c($seccion,$subpadre);
                        $texto=$this->sm->get_labels($seccion,$subpadre);
                        $distintos = (strcasecmp(remove_accents($texto[$subpadre]),remove_accents($datos['label'])) == 0)?  false : true;
                    }else{
                        $opciones_subseccion = $this->sm->get_sub_secciones_c($seccion);
                        // pr($opciones_subseccion);
                        $texto=$this->sm->get_labels($seccion);
                        foreach ($texto as $key => $value) {
                            if(strcasecmp(remove_accents($value),remove_accents($datos['label']))==0){
                                $distintos=false;
                                break;
                            }
                        }
                    }
                }
                $output['subsecciones'] = $opciones_subseccion;

                if($distintos)
                {
                    if($this->form_validation->run() == TRUE)
                    {

                        if($this->sm->create_elemento_seccion($datos)){
                            echo json_encode(array(
                                    "created"=>1,
                                    "base" => 1,
                                    "html"=>html_message("El elemento de sección fue creado exitosamente","success")
                                )
                            );
                        }
                        else
                        {
                            echo json_encode(array(
                                    "created"=>0,
                                    "base" => 1,
                                    "html"=>html_message("No se pudo crear el elemento de sección","danger")
                                )
                            );
                        }
                    }
                    else
                    {
                        $main_content = $this->load->view('secciones/nuevo_elemento.tpl.php', $output, true);
                        echo json_encode(array(
                                "created"=>0,
                                "base" => 0,
                                "html" => $main_content
                            )
                        );
                    }
                }
                else
                {
                    echo json_encode(array(
                        "created"=>0,
                        "base"=>1,
                        "html"=>html_message("La etiqueta ya existe, ingresa una diferente","danger")
                        )
                    );
                }


            }else{
                $main_content = $this->load->view('secciones/nuevo_elemento.tpl.php', $output, true);
                $this->template->setMainContent($main_content);
                $this->template->getTemplate();
            }
        }catch(Exception $e){
            show_error($e->getMessage() . '---' . $e->getTraceAsString());
        }
    }


    public function ajax_subsecciones($id){
        $subsecciones = $this->sm->get_sub_secciones_c($id, null, true);
        echo json_encode($subsecciones);
    }

}
