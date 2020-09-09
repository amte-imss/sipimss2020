<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Plantilla
 *
 * @author chrigarc
 */
class Plantilla extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try
        {
            $this->db->schema = 'ui';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('plantillas');

            $crud->unset_delete();
            $crud->columns('nombre', 'descripcion', 'activa');
            $crud->fields('nombre', 'descripcion', 'contenido', 'activa');

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
