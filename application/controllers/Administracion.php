<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Admnistracion
 *
 * @author chrigarc
 */
class Administracion extends MY_Controller
{

    const LISTA = 'lista', LIMIT = 25;

    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->template->setTitle('Administración');
    }

    public function index()
    {
        echo "Error 404";
    }

    /**
     * Grocery crud de grupos registrados
     * @author Christian Garcia
     * @version 8 marzo 2017
     */
    public function niveles_acceso()
    {
        try
        {
            $this->db->schema = 'sistema';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('roles');


            $crud->columns('clave_rol', 'nombre', 'descripcion', 'orden', 'activo');
            $crud->fields('clave_rol','nombre', 'descripcion', 'orden', 'activo');

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function bitacora($opcion = '')
    {
        $output = [];
        switch ($opcion) {
            case Administracion::LISTA:
                $params = $this->input->get(null, true);
                $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Administracion::LIMIT;
                $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
                $bitacora['data'] = $this->bitacora->get_registros($filtros);
                $filtros['total'] = true;
                $total = $this->bitacora->get_registros($filtros)[0]['total'];
                $bitacora['length'] = $total;
                header('Content-Type: application/json; charset=utf-8;');
                echo json_encode($bitacora);
                break;
            default:
                $view = $this->load->view('administracion/bitacora.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    public function notificaciones()
    {
        $this->load->library('Core_auxiliares', array(
            'db' => $this->db
        ));
        $this->db->schema = 'ui';
        $crud = $this->new_crud();
        $crud->set_table('notificaciones_estaticas');
        $crud->set_primary_key('clave', 'notificaciones_estaticas'); //Definir llaves primarias, asegurar correcta relación
        $output = $crud->render();
        $view = $this->load->view('administracion/notificaciones.tpl.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->enable_date_picker(false);
        $this->template->getTemplate();
    }

    public function entrar_como($id = 0)
    {
        $this->load->model('Sesion_model', 'sesion');
        $this->load->model('Usuario_model', 'usuario');
        $this->realiza_acceso($id);
    }

    public function terminar_entrar_como()
    {
        $info = $this->session->userdata('die_sipimss');
        if(isset($info['anterior']))
        {
            $this->session->set_userdata('die_sipimss', $info['anterior']);
        }
        redirect('inicio');
    }

    private function realiza_acceso($id)
    {
        $params['where'] = array(
            'usuarios.id_usuario' => $id
        );
        $params['select'] = array(
            'usuarios.id_usuario', 'coalesce(docentes.matricula, usuarios.username) matricula',
            'docentes.id_docente', 'docentes.nombre',
            'docentes.apellido_p', 'docentes.apellido_m', 'sexo',
            'E.id_unidad_instituto', 'E.nombre unidad', 'E.clave_unidad',
            'docentes.fecha_nacimiento', 'D.id_departamento_instituto', 'D.nombre departamento',
            'D.clave_departamental', 'F.clave_categoria',
            'F.id_categoria', 'F.nombre categoria', 'C.cve_tipo_contratacion',
            'docentes.curp', 'docentes.rfc', 'docentes.telefono_particular',
            'docentes.telefono_laboral', 'docentes.email', 'G.clave_delegacional', 'G.nombre delegacion',
        );
        $resultado = $this->usuario->get_usuarios($params);
        if (count($resultado) == 1)
        {
            pr($resultado);
            $sesion_original = $this->session->userdata('die_sipimss');
            pr($sesion_original);
            $nueva_sesion['usuario'] = $resultado[0];
            $nueva_sesion['niveles_acceso'] = $this->sesion->get_niveles_acceso($resultado[0]['id_usuario']);
            $nueva_sesion['workflow'] =  $this->sesion->get_info_convocatoria($resultado[0]['id_docente']);
            $nueva_sesion['anterior'] = $sesion_original;
            $this->session->set_userdata('die_sipimss', $nueva_sesion);
            redirect('inicio');
        }
    }

}
