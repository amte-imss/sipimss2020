<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of ConvocatoriaV2
 *
 * @author chrigarc
 */
class ConvocatoriaV2 extends MY_Controller implements IWorkflow
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Workflow_model', 'ltm');
        $this->load->model('ConvocatoriaV2_model', 'convocatoria');
    }

    public function view_new($id_workflow)
    {

        if ($this->input->is_ajax_request())
        {
            $filtros['id_workflow'] = $id_workflow;
            $filtros['select'] = array('labels_fechas');
            $this->load->model('Workflow_model', 'ltm');
            $output = $this->ltm->get_workflows($filtros)[0];
            $output['fechas'] = $this->load->view('workflow/fechas.tpl.php', $output, true);
            $vista = $this->load->view('convocatoriav2/nueva_wf.tpl.php', $output, false);
        }
    }

    public function view_update($id_linea_tiempo, $is_modal = 1)
    {
        $is_modal = $is_modal === 1?true:false;
        $filtros['id_linea_tiempo'] = $id_linea_tiempo;
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
                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $validations = $this->config->item('editar_convocatoria_censo'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations); //Añadir validaciones
                if($this->form_validation->run())
                {
                    $params['post'] = $this->input->post(null, true);
                    $params['output'] = $output;
                    $params['id_linea_tiempo'] = $id_linea_tiempo;
                    $output['status'] = $this->update_linea_tiempo($params);
                }else
                {
                   $output['status'] = array('status' => false, 'msg' => 'Campos incorrectos');
                }
            }
            $output['workflow']['linea_tiempo'] = $output['linea_tiempo'];
            $output['fechas'] = $this->load->view('workflow/fechas.tpl.php', $output['workflow'], true);
            $vista = $this->load->view('convocatoriav2/editar_linea_tiempo.tpl.php', $output, true);
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
        $status = $this->convocatoria->update($params['post']);
        return $status;
    }

    public function unidad()
    {
        $unidad = [];
        if ($this->input->post('clave_unidad') != null)
        {
            $filtros = $this->input->post(null, true);
            $unidad = $this->convocatoria->get_unidad($filtros);
        }
        echo json_encode($unidad);
    }

    public function nueva()
    {
        $salida['status'] = false;
        if ($this->input->is_ajax_request())
        {
            $filtros['id_wordflow'] = $this->input->post('id_workflow', true);
            $params = $this->input->post(null, true);
            $params['workflow'] = $this->ltm->get_workflows($filtros);
            $salida = $this->convocatoria_valida($params);
            if ($salida['status'])
            {
                $salida = $this->convocatoria->insert($params);            
            } else
            {
                // pr('que hago aqui');
            }
        }
        echo json_encode($salida);
    }

    public function get_secciones($id_linea_tiempo = 0)
    {
        if ($this->input->post())
        {
            $params = $this->input->post(null, true);
            $params['id_linea_tiempo'] = $id_linea_tiempo;
            $output['status'] = $this->convocatoria->upsert_secciones($params);
        }
        $filtros['id_linea_tiempo'] = $id_linea_tiempo;
        $output['linea_tiempo'] = $this->ltm->get_lineas_tiempo($filtros)[0];
        $filtros['id_workflow'] = $output['linea_tiempo']['id_workflow'];
        $output['workflow'] = $this->ltm->get_workflows($filtros)[0];
        $output['secciones']['N1'] = $this->convocatoria->get_secciones($id_linea_tiempo, 'N1');
//        pr($output['secciones']['N1'] );
        $output['secciones']['N2'] = $this->convocatoria->get_secciones($id_linea_tiempo, 'N2');
//        pr($output['secciones']['N2'] );
        //$vista = $this->get_linea_tiempo($id_linea_tiempo);
        $vista = $this->load->view('convocatoriav2/secciones', $output, true);
        $this->template->setTitle("Convocatorias");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    public function get_validadores($id_linea_tiempo = 0, $tipo = '', $entidad = 0, $validacion = ConvocatoriaV2_model::N1)
    {
        $post = false;
        if ($this->input->post() && is_numeric($entidad) && $entidad > 0)
        {
            $post = true;
            $params = $this->input->post(null, true);
            $params['id_linea_tiempo'] = $id_linea_tiempo;
            $params['tipo'] = $tipo;
            $params['entidad'] = $entidad;
            $params['validacion'] = $validacion;
            $output['status'] = $this->convocatoria->upsert_validadores($params);
        }
        $output['exportarValidadores'] = site_url('Workflow/index/exportarValidadores/'.$id_linea_tiempo);
        $filtros['id_linea_tiempo'] = $id_linea_tiempo;
        $output['linea_tiempo'] = $this->ltm->get_lineas_tiempo($filtros)[0];
        $filtros['id_workflow'] = $output['linea_tiempo']['id_workflow'];
        $output['workflow'] = $this->ltm->get_workflows($filtros)[0];
        $output['participantes'] = $this->convocatoria->get_participantes($id_linea_tiempo, array('delegaciones' => true));
//        pr($output['participantes']['delegaciones']);
        switch ($tipo)
        {
            default:
                $vista = $this->load->view('convocatoriav2/validadores.tpl.php', $output, true);
                $this->template->setTitle("Convocatorias");
                $this->template->setMainContent($vista);
                $this->template->getTemplate();
                break;
            case ConvocatoriaV2_model::UNIDADES:
            case ConvocatoriaV2_model::DELEGACIONES:
                if (!$post)
                {
                    $output['tipo'] = $tipo;
                    $output['entidad'] = $entidad;
                    $output['validacion'] = $validacion;
                    $filtros['tipo'] = $tipo;
                    $filtros['entidad'] = $entidad;
                    $filtros['validacion'] = $validacion;
                    $output['validadores'] = $this->convocatoria->get_validadores($filtros);
                    $vista = $this->load->view('convocatoriav2/lista_validadores.tpl.php', $output, true);
                    $this->template->set_titulo_modal('Validadores ' . $output['linea_tiempo']['nombre']);
                    $this->template->set_cuerpo_modal($vista);
                    $this->template->get_modal();
                } else
                {
                    echo json_encode($output['status']);
                }
                break;
        }
    }

    private function convocatoria_valida($params)
    {
        $salida = array('status' => true);
        $this->config->load('form_validation'); //Cargar archivo con validaciones
        $validations = $this->config->item('nueva_convocatoria_censo'); //Obtener validaciones de archivo general
        $this->form_validation->set_rules($validations); //Añadir validaciones
        $salida['status'] = $this->form_validation->run();
        $salida['errores'] = $this->form_validation->error_array();
        //pr($this->form_validation->error_array());
        return $salida;
    }

    public function get_participantes($id_linea_tiempo = 0)
    {
        $filtros['id_linea_tiempo'] = $id_linea_tiempo;
        $output['linea_tiempo'] = $this->ltm->get_lineas_tiempo($filtros)[0];
        $output['exportarDocentes'] = site_url('Workflow/index/exportarDocentes/'.$id_linea_tiempo);
        $filtros['id_workflow'] = $output['linea_tiempo']['id_workflow'];
        $output['workflow'] = $this->ltm->get_workflows($filtros)[0];
        if ($this->input->is_ajax_request() && $this->input->post())
        {
            $params = $this->input->post(null, true);
            $params['linea_tiempo'] = $output['linea_tiempo'];
            $salida['status'] = $this->convocatoria->upsert_participantes($id_linea_tiempo, $params['unidades']);
            echo json_encode($salida);
        } else
        {
            $output['participantes'] = $this->convocatoria->get_participantes($id_linea_tiempo, array('unidades_agrupadas' => false));
            $vista = $this->load->view('convocatoriav2/participantes.tpl.php', $output, true);
            $this->template->setTitle("Convocatorias");
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        }
    }

    public function notificaciones($id_linea_tiempo = 0){

    }

    /**
     * Función que crea un registro para finalizar la convocatoria
     * @author Cheko
     * @param String $finalizar Parametro para que sea correcta la petición POST
     * @modificado Christian Garcia - No entiendo la necesidad de sergio de usar una tecnologia que no aplica para el proyecto
     * es como si quisiera que nos adaptaramos a el, que por cierto esta muy mal aplicada.
     * Esta es una función mas que tuve que corregir que dejo incompleta
     * Ademas sospecho que no prueba nada de lo que hace y aplica la de "las pruebas son para los que no sabes lo que hacen"
     */
    public function guardar_registro_finaliza_convocatoria(){
        $su = $this->get_datos_sesion();
        //pr($su);
        if(isset($su['workflow']) && !empty($su['workflow']))
        {
            $linea_tiempo = $su['workflow'][0]['id_linea_tiempo'];
            $guardar = array('id_docente' => $su['id_docente'], 'id_linea_tiempo'=>$linea_tiempo); //revisar si el id_linea del tiempo viernes
            $respuesta = $this->convocatoria->registro_finaliza_convocatoria($guardar);
            if($respuesta['success'])
            {
                $ds = $this->session->userdata('die_sipimss');
                $ds['usuario']['workflow'][0]['finalizada'] = true;
                $this->session->set_userdata('die_sipimss', $ds);
            }
        }
        redirect('inicio');
    }

}
