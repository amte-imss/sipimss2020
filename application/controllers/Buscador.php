<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Buscador
 *
 * @author chrigarc
 */
class Buscador extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');        
        $this->load->model('Buscador_model', 'buscador');
    }

    
    public function search_categoria()
    {
        if ($this->input->post() && $this->input->post('keyword')!= null)
        {
            //$keyword = 'COO';
            
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $output['categorias'] = $this->buscador->search_categoria($keyword);
            echo $this->load->view('buscador/categorias', $output, true);
        }
    }  
}
