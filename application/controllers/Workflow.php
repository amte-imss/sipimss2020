<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Workflow
 *
 * @author chrigarc
 */
class Workflow extends MY_Controller implements IWorkflow
{

    const LIMIT = 10, LISTA = 'lista', UPDATE = 'update', NUEVA = 'nueva',
    BORRAR = 'borrar', FAIL_NUEVA = 3, EXPORTARDOCENTES = 'exportarDocentes',
    EXPORTARVALIDADORES = 'exportarValidadores';

    function __construct()
    {
        parent::__construct();
        $this->benchmark->mark('Constructor_start');

//        $this->output->enable_profiler(TRUE);
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Workflow_model', 'ltm');
        $this->load->model('Catalogo_model', 'catalogo');
        $this->config->load('convocatorias');

        /*
        $this->output->parse_exec_vars = TRUE;
        $this->output->append_output($this->benchmark->memory_usage());
         */
    }

    public function index($linea_tiempo = Workflow::LISTA, $limit_or_id = Workflow::LIMIT, $order_by = 'asc', $page_number = 0)
    {
        $this->benchmark->mark('Index_start');
        switch ($linea_tiempo)
        {
            case Workflow::LISTA:
                $this->get_lista($limit_or_id, $order_by, $page_number);
                break;
            case Workflow::UPDATE:
                $this->get_linea_tiempo($limit_or_id, TRUE);
                break;
            case Workflow::BORRAR:
                echo $this->delete_linea_tiempo($limit_or_id);
                break;
            case Workflow::EXPORTARDOCENTES: //exporta docentes
                $peticion = $this->input->get(null,true);
                $file_name = 'workflow_docentes_'.date('Ymd_his', time());
                $datos = $this->obtener_datos_workflow("docentes", $limit_or_id);
                $columnas = array('id_linea_tiempo','linea de tiempo','clave','fechas_inicio','fechas_fin',
                'id_etapa_activa','etapa','id_etapa', 'clave_unidad','unidad','tipo','clave_presupuestal','clave_asdcripcion',
                'adscripcion', 'delegacion','region','matricula','nombre','apellido_p','apellido_m','email','curp');
                $this->exportar($file_name, $columnas, $datos);
                break;
            case Workflow::EXPORTARVALIDADORES: //exporta validadores
                $peticion = $this->input->get(null,true);
                $file_name = 'workflow_validadores_'.date('Ymd_his', time());
                $datos = $this->obtener_datos_workflow('validadores', $limit_or_id);
                $columnas = array('id_linea_tiempo','linea de tiempo','clave','fechas_inicio','fechas_fin',
                'id_etapa_activa','etapa','id_etapa', 'clave_unidad','unidad','tipo','clave_presupuestal','clave_asdcripcion',
                'adscripcion', 'delegacion','region','matricula','nombre','apellido_p','apellido_m','email','curp','tipo');
                $this->exportar($file_name, $columnas, $datos);
                break;
            default:
                $this->get_linea_tiempo($linea_tiempo);
                break;
        }
    }

    /**
     * Funcion que obtiene los datos del wokflow
     * dependiendo el tipo (VALIDADORES o DOCENTES)
     * @author Cheko
     * @param type $tipo tipo de informacion
     *
     */
     private function obtener_datos_workflow($tipo, $id)
     {
         switch ($tipo) {
             case 'docentes':
                 $filtros = $this->config->item('workflow.lineas_tiempo');
                 $filtros['where']['L.id_linea_tiempo'] = $id;
                 return $this->catalogo->get_registros('workflow.lineas_tiempo L', $filtros);
                 break;
             case 'validadores':
                 $filtros = $this->config->item('workflow.validadores_censo');
                 $filtros['where']['L.id_linea_tiempo'] = $id;
                 return $this->catalogo->get_registros('workflow.validadores_censo VC', $filtros);
                 break;
             default:
                 return [];
                 break;
         }
     }

    /**
     * Función para exportar en excel de una tabla
     * @author Cheko
     * @param type $nombre_archivo Nombre del archivo
     * @param Array $columnas Los encabezados del excel
     * @param Array $datos Los datos de las columnas(renglones)
     *
     */
    private function exportar($nombre_archivo=NULL, $columnas=[], $datos=[])
    {
        $registros['data'] = $datos;
        $registros['columnas'] = $columnas;
        return $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $nombre_archivo);
    }

    /**
     * Funcion que elimina una linea del tiempo
     * @author Cheko
     * @param type $id identificador de la linea del tiempo
     * @return type Mensaje de respuesta
     *
     */
    private function delete_linea_tiempo($id)
    {
        $resultadoRegistro = $this->ltm->obtener_registro_linea_tiempo('workflow.registros',$id);
        if($resultadoRegistro)
        {
            return $this->restfull_respuesta("ok", 'No se puede borrar ya que existe un registro', []);
        }
        else
        {
            $resultadoEliminar = $this->ltm->eliminar_linea_tiempo($id);
            if($resultadoEliminar['success'])
            {
                return $this->restfull_respuesta('ok','Se elimino correctamente la convocatoria',$resultadoEliminar);
            }
            else
            {
                return $this->restfull_respuesta('error','No se elimino correctamente la convocatoria',$resultadoEliminar);
            }
        }
    }


    /**
     * Funcion para manejar las respuestas hacia el cliente
     * @author Cheko
     * @param String estatus de la respuesta
     * @param String mensaje de la respuesta
     * @param Array arreglos de datos de la respuesta
     *
     */
    private function restfull_respuesta($status, $msj, $datos)
    {
        $respuesta = [];
        $respuesta['estatus'] = $status;
        $respuesta['msj'] = $msj;
        $respuesta['datos'] = $datos;
        return json_encode($respuesta);
    }

    private function get_lista($limit, $order_by, $page_number)
    {
        $filtros = [];
        $output['tipos_lineas_tiempo'] = $this->ltm->get_tipos_lineas_tiempo($filtros);
        $output['lineas_tiempo'] = $this->ltm->get_lineas_tiempo($filtros);
//        pr($output);
        $vista = $this->load->view('workflow/index.tpl.php', $output, true);
        $this->template->setTitle("Administración de lineas de tiempo");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    private function get_linea_tiempo($linea_tiempo, $is_modal = false)
    {
        $filtros['id_linea_tiempo'] = $linea_tiempo;
        $output['linea_tiempo'] = $this->ltm->get_lineas_tiempo($filtros)[0];
        $filtros['id_workflow'] = $output['linea_tiempo']['id_workflow'];
        $output['workflow'] = $this->ltm->get_workflows($filtros)[0];
        $output['modulos_administracion'] = $this->ltm->get_workflows_administracion($filtros);
//        pr($output);
        if (!$is_modal)
        {
            $vista = $this->load->view('workflow/linea_tiempo.tpl.php', $output, true);
            $this->template->setTitle("Administración de " . $output['linea_tiempo']['nombre']);
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        } else
        {
            if ($this->input->post())
            {
                $params['post'] = $this->input->post(null, true);
                $params['output'] = $output;
                $params['id_linea_tiempo'] = $linea_tiempo;
                $output['status'] = $this->update_linea_tiempo($params);
            }
            $output['workflow']['linea_tiempo'] = $output['linea_tiempo'];
            $output['fechas'] = $this->load->view('workflow/fechas.tpl.php', $output['workflow'], true);
            $vista = $this->load->view('workflow/editar_linea_tiempo.tpl.php', $output, true);
            $this->template->set_titulo_modal('Editar ' . $output['linea_tiempo']['nombre']);
            $this->template->set_cuerpo_modal($vista);
            $this->template->get_modal();
        }
    }

    private function update_linea_tiempo(&$params = [])
    {
        //pr($params);
        $a = $this->input->post('activo', true);
        $params['post']['activa_boolean'] = $a != null && $a == 1 ? true : false;
        $status = $this->ltm->update($params['post']);
        $this->cron();
        return $status;
    }

    public function nueva()
    {
        $output = [];
        $output['workflows'] = $this->ltm->get_workflows();
        $output['tipos_lineas'] = dropdown_options($output['workflows'], 'id_workflow', 'nombre');
        if ($this->input->post())
        {
            pr('hola');
            $filtros['id_wordflow'] = $this->input->post('id_workflow', true);
            $params = $this->input->post(null, true);
            $params['workflow'] = $this->ltm->get_workflows($filtros);
            if ($this->workflow_valido($params))
            {
                $salida = $this->ltm->insert($params);
                pr($salida);
            } else
            {
                pr('que hago aqui');
            }
        } else
        {
            $vista = $this->load->view('workflow/nueva.tpl.php', $output, true);
            $this->template->setTitle("NUEVA");
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        }
    }

    private function workflow_valido($params = [])
    {
        $status = true;
        return $status;
    }

    public function view_new($id_workflow = 0)
    {
        $filtros['id_workflow'] = $id_workflow;
        $filtros['select'] = array('labels_fechas');
        $output = $this->ltm->get_workflows($filtros)[0];
        $output['fechas'] = $this->load->view('workflow/fechas.tpl.php', $output, true);
        $this->load->view('workflow/nueva_default.tpl.php', $output);
    }

    public function auxiliares($id_workflow = 0)
    {
        $filtros['id_workflow'] = $id_workflow;
        $filtros['select'] = array('id_workflow', 'B.url controlador_new');
        $workflow = $this->ltm->get_workflows($filtros)[0];
//        pr($workflow);
        redirect(site_url($workflow['controlador_new'] . '/' . $id_workflow));
    }

    public function etapas($id_workflow)
    {
        try
        {
            $this->db->schema = 'workflow';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('etapas');
            $crud->set_primary_key('id_etapa'); //Definir llaves primarias, asegurar correcta relación

            $crud->unset_delete();
            $crud->columns('nombre', 'id_workflow', 'descripcion', 'orden', 'activa');
            $crud->fields('nombre', 'id_workflow', 'descripcion', 'orden', 'activa');

            $crud->set_relation('id_workflow', 'workflows', 'nombre');

            $crud->where('etapas.id_workflow', $id_workflow);

            $crud->add_action('Accesos', 'ui-icon-image', 'workflow/etapas_accesos');
            $crud->add_action('Notificaciones', 'ui-icon-image', 'workflow/etapas_notificaciones');

            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function etapas_accesos($id_etapa)
    {
        try
        {
            $this->db->schema = 'workflow';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('etapas_accesos');
            $crud->set_primary_key('id_etapas_accesos'); //Definir llaves primarias, asegurar correcta relación

            $crud->unset_delete();
            $crud->columns('id_etapa', 'clave_modulo', 'clave_rol', 'activa');
            $crud->fields('id_etapa', 'clave_modulo', 'clave_rol', 'activa');

            $crud->set_relation('id_etapa', 'etapas', 'nombre');
            $crud->set_relation('clave_modulo', 'sistema.modulos', 'nombre');
            $crud->set_relation('clave_rol', 'sistema.roles', 'nombre');

            $crud->where('etapas_accesos.id_etapa', $id_etapa);
            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function etapas_notificaciones($id_etapa)
    {
        try
        {
            $this->db->schema = 'workflow';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('etapas_notificaciones');
            $crud->set_primary_key('id_etapa_notificacion'); //Definir llaves primarias, asegurar correcta relación

            $crud->unset_delete();
            $crud->columns('id_etapa', 'clave_notificacion', 'activa');
            $crud->fields('id_etapa', 'clave_notificacion', 'activa');

            $crud->set_relation('id_etapa', 'etapas', 'nombre');
            $crud->set_relation('clave_notificacion', 'sistema.notificaciones', 'clave');

            $crud->where('etapas_notificaciones.id_etapa', $id_etapa);
            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function configuracion()
    {
        try
        {
            $this->db->schema = 'workflow';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('workflows');
            $crud->set_primary_key('id_workflow'); //Definir llaves primarias, asegurar correcta relación

            $crud->unset_delete();
            $crud->columns('nombre', 'descripcion', 'activo');
            $crud->fields('nombre', 'descripcion', 'labels_fechas', 'clave_controlador_insert', 'clave_controlador_update', 'activo');

            $crud->set_relation('clave_controlador_insert', 'sistema.modulos', 'url');
            $crud->set_relation('clave_controlador_update', 'sistema.modulos', 'url');

            $crud->add_action('Etapas', '', 'workflow/etapas');
            $crud->add_action('Modulos de administración', '', 'workflow/modulos');

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

    public function modulos($id_workflow)
    {
        try
        {
            $this->db->schema = 'workflow';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('modulos_administracion');
            $crud->set_primary_key('id_modulo_administracion'); //Definir llaves primarias, asegurar correcta relación

            $crud->unset_delete();
            $crud->columns('clave_modulo', 'orden', 'activo');
            $crud->fields('id_workflow', 'clave_modulo', 'label', 'orden', 'activo');

            $crud->set_relation('id_workflow', 'workflows', 'nombre');
            $crud->set_relation('clave_modulo', 'sistema.modulos', 'url');

            $crud->where('modulos_administracion.id_workflow', $id_workflow);
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

    public function cron()
    {
        $this->load->helper('date');
        $ltm = $this->ltm->procesa_lineas_tiempo();
        //$this->correo($ltm); //activar en productivo
    }

    private function correo($l = [])
    {
        $this->load->config('email');
        $this->load->library('My_phpmailer');
        $this->load->config('email');
        $mailStatus = $this->my_phpmailer->phpmailerclass();
        //$mailStatus->addAddress($address);
        $mailStatus->addAddress('zurgcom@gmail.com');
        $subject = 'Sistema de automatización de convocatorias SIPIMSS';
        $subject = ENVIRONMENT=='development'?'[Pruebas] '.$subject:$subject;
        $mailStatus->Subject = utf8_decode($subject);
        $mailStatus->msgHTML(utf8_decode('Estas son pruebas muchachos'));
        $salida['status'] = $mailStatus->send();
    }

}
