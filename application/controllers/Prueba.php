<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prueba extends CI_Unit_test {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        // $menu = $this->load->view('tc_template/menu.tpl.php');
//        $this->template->setNav(array());
//        $this->template->getTemplate();
//        $this->get_elemento_seccion_ramas_c(111,0);
//          $this->load->model('Catalogo_model', 'cm');
//          pr($this->cm->get_busca_opciones_catalogo(5, 'tí', TRUE));

        

        $main_content = $this->load->view('prueba_', NULL, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

}
