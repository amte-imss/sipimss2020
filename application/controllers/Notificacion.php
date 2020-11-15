<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Notificacion
 *
 * @author chrigarc
 */
class Notificacion extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();        
        $this->template->setTitle('Notificación');
    }
    
    public function index(){
        pr('En construcción');
    }
    
    public function catalogo()
    {
        try
        {
            $this->db->schema = 'sistema';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('notificaciones');
            $crud->set_primary_key('id_workflow'); //Definir llaves primarias, asegurar correcta relación 

            $crud->unset_delete();
            $crud->columns('clave', 'nombre', 'descripcion', 'activa');
            $crud->fields('nombre', 'descripcion', 'id_plantilla', 'clave_formato', 'tipo', 'activa');

            $crud->set_relation('id_plantilla', 'ui.plantillas', 'nombre');
            $crud->set_relation('clave_formato', 'ui.formateadores', 'clave');

            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));            
            $crud->change_field_type('tipo', 'enum', array('email'=>'Email', 'web'=>'Web'));
            
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    public function formatos(){
        try
        {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('formateadores');
            $crud->set_primary_key('clave'); //Definir llaves primarias, asegurar correcta relación 

            $crud->unset_delete();
            $crud->columns('clave', 'nombre', 'descripcion', 'activa');
            $crud->fields('clave', 'nombre', 'descripcion', 'activa');
            
            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));
            
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
   
    public function gestion(){
        try
        {
            $this->db->schema = 'ui';
            $crud = $this->new_crud();
            $crud->set_table('notificaciones_estaticas');
            $crud->set_primary_key('clave'); //Definir llaves primarias, asegurar correcta relación 
            $crud->set_primary_key('clave_rol'); //Definir llaves primarias, asegurar correcta relación 

            //$crud->unset_delete();
            $crud->columns('clave', 'nombre', 'descripcion', 'activa', 'fecha_inicio', 'fecha_fin', 'clave_rol','id_normativo');
            $crud->fields('clave', 'nombre', 'descripcion', 'activa','fecha_inicio', 'fecha_fin');
            $crud->add_fields('clave', 'nombre', 'descripcion', 'activa', 'fecha_inicio', 'fecha_fin', 'clave_rol'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion', 'activa', 'fecha_inicio', 'fecha_fin','clave_rol', 'id_normativo'); //Definir campos que se van a editar y su orden
            $cat_rol = $this->sesion->get_niveles_acceso_cat(true);
            $crud->change_field_type('clave_rol', 'dropdown', $cat_rol );
            
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
