<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : CAL
 * */
class Normativo extends Validacion {

    function __construct() {
        parent::__construct();
        $this->load->model('Normativo_model', 'normativo');
    }

    function index()
    {        
        $output['result_delegacional'] = $this->normativo->get_delegacional();
        $output['result_umae'] = $this->normativo->get_umae();
        //pr($output);

        $this->template->setTitle('Inicio');
        $main_content = $this->load->view('normativo/index.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

}