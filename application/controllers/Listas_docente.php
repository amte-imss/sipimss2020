<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Listas_docente extends MY_Controller {

    function __construct() {
        parent::__construct();
//        $data_user = $this->session->userdata('usuario');
//        $datos_perfil['usuario'] = $data_user;
        $this->load->library('form_complete');
        $this->load->model('Catalogo_model', 'cm');
    }

    public function index() {
        $data_post = $this->input->post(null, true);


        $formulario = $this->get_campos_formulario(6); //Obtiene tosdos los campos de formulario
        $rules = $this->get_rules_formulario($formulario); //Obtiene tosdos los campos de formulario
//        pr($rules);
        $catalogos_form = $this->get_elementos_catalogos_formulario(6);
        $data['formulario_campos'] = $formulario;
        $data['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
        $data_pie['formulario'] = $data['formulario'];
        $data['catalogos'] = $catalogos_form;
//        pr($catalogos_form);
//        $data['pie_formularios'] = $this->load->view('docente/actividad_docente/actividad_pie', $data_pie, true);
        $id_seccion=6;
         $this->load->model('Secciones_model', 'csm');
        $data['copciones']=$this->csm->get_secciones_padres(array('id_seccion' => $id_seccion));
        $data['id_seccion']=$id_seccion;

        $data['formulario'] = $this->load->view('docente/actividad_docente/form_listas.php', $data, true);
        $data['tabla'] = $this->load->view('docente/actividad_docente/tabla.php', NULL, true);
        $data['titulo_seccion'] = 'Actividad docente';
        $data['titulo_form'] = 'Ciclos clínicos';
        $data['seccion'] = 'Secciones';
        $this->template->setFormularioSecciones($data);
        $this->template->getTemplate();
//        echo $main_content;
    }



    public function llena_opciones()
    {

       if($this->input->is_ajax_request() && $this->input->post())
        {

            $combo = $this->input->post('combo', true);
            $idcombo = $this->input->post('idcombo', true);
            $base = $this->input->post('base', true);


            $result =$this->cm->opciones_combos($combo,$idcombo,$base);


            echo json_encode($result);


         }


    }


}
