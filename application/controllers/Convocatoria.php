<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Convocatoria
 *
 * @author chrigarc
 * @deprecated Se mantiene por necesidad del cliente de ver el historico de cambios
 * pero este estilo de convocatoria no sirve para el requerimiento actual
 */
class Convocatoria extends MY_Controller implements IWorkflow
{

    const LISTA = 'lista', UPDATE = 'update', NUEVA = 'nueva', FAIL_NUEVA = 3;

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Convocatoria_model', 'convocatoria');
    }

    public function index()
    {
        $this->get_convocatorias();
    }

    public function get_convocatorias($id_convocatoria = 0, $accion = Convocatoria::LISTA)
    {
        $filtros = [];
        $output['segmentos'] = $this->convocatoria->get_segmentos();
        if ($this->input->post() && $accion == Convocatoria::LISTA)
        {
            $filtros = $this->input->post(null, true);
        }
        if ($id_convocatoria == 0)
        {
            $output['convocatorias'] = $this->convocatoria->get_convocatorias();
            $vista = $this->load->view('convocatoria/index', $output, true);
        } else
        {
            if ($accion != Convocatoria::UPDATE)
            {
                $output['is_nueva'] = true;
                $vista = $this->get_convocatoria($id_convocatoria, 'convocatoria/convocatoria', $output);
            } else
            {
                if ($this->input->post())
                {
                    $params = $this->input->post(null, true);
                    $params['id_convocatoria'] = $id_convocatoria;
                    $salida = $this->convocatoria->update($params);
                    $output['status'] = $salida;
                    redirect('convocatoria/get_convocatorias/' . $id_convocatoria);
                } else
                {
                    $vista = $this->get_convocatoria($id_convocatoria, 'convocatoria/editar', $output);
                }
            }
        }
        if ($accion == Convocatoria::LISTA)
        {
            $this->template->setTitle("Convocatorias");
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        } else
        {
            $this->template->set_titulo_modal('Editar convocatoria');
            $this->template->set_cuerpo_modal($vista);
            $this->template->get_modal();
        }
    }

    public function nueva()
    {
        $output = [];
        if ($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            if ($this->input->post('tipo') == 1)
            {
                $validations = $this->config->item('nueva_convocatoria_censo'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations);
                if ($this->form_validation->run() == TRUE)
                {
                    $nueva = $this->convocatoria->insert($this->input->post(null, true));
                    if ($nueva['status'])
                    {
                        $this->get_convocatorias($nueva['id_convocatoria'], Convocatoria::NUEVA);
                    } else
                    {
                        pr('fail');
                    }
                } else
                {
                    pr(validation_errors());
                    $this->get_convocatorias(0, Convocatoria::FAIL_NUEVA);
                }
            } else
            {
                pr('no se que hago aquí');
            }
        } else
        {
            $output['segmentos'] = $this->convocatoria->get_segmentos();
            $modal = $this->load->view('convocatoria/nueva', $output, true);
            $this->template->set_titulo_modal('Nueva convocatoria');
            $this->template->set_cuerpo_modal($modal);
            $this->template->get_modal();
        }
    }

    public function get_elementos($id_convocatoria = 0)
    {
        $output['elementos'] = $this->convocatoria->get_entidades($id_convocatoria);
        if ($this->input->post())
        {
            $params = $this->input->post(null, true);
            $params['id_convocatoria'] = $id_convocatoria;
            $params['entidades'] = $output['elementos'];
            $output['status'] = $this->convocatoria->upsert_entidades($params);
        }
        $output['segmentos'] = $this->convocatoria->get_segmentos();
        $output['elementos'] = $this->convocatoria->get_entidades($id_convocatoria);
        $vista = $this->get_convocatoria($id_convocatoria, 'convocatoria/elementos_censo', $output);
        $this->template->setTitle("Convocatorias");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    public function get_validadores($id_convocatoria = 0, $tipo_entidad = Convocatoria_model::REGION, $entidad = 0, $validacion = Convocatoria_model::N1)
    {
        $filtros = array(
            'id_convocatoria' => $id_convocatoria,
            'tipo_entidad' => $tipo_entidad,
            'id_entidad' => $entidad,
            'validacion' => $validacion
        );
        if ($this->input->post() && $this->input->post('id_docente') != null)
        {
//            pr($this->input->post());
            $params = $this->input->post(null, true);
            $filtros['id_docente'] = $this->input->post('id_docente', true);
            $filtros['activo'] = isset($params['activo'.$filtros['id_docente']]);
            pr($filtros['activo']);
            $salida = $this->convocatoria->upsert_validadores($filtros);
            echo json_encode($salida);
        } else
        {
            $output['filtros'] = $filtros;
            $output['validadores'] = $this->convocatoria->get_validadores($filtros)[$tipo_entidad];
            $modal = $this->load->view('convocatoria/validadores', $output, true);
            $this->template->set_titulo_modal('Lista de validadores ');
            $this->template->set_cuerpo_modal($modal);
            $this->template->get_modal();
        }
    }

    public function get_secciones($id_convocatoria = 0)
    {
        if($this->input->post()){
            $params = $this->input->post(null, true);
            $params['id_convocatoria'] = $id_convocatoria;
            $output['status'] = $this->convocatoria->upsert_secciones($params);
        }
        $output['segmentos'] = $this->convocatoria->get_segmentos();
        $output['secciones']['N1'] = $this->convocatoria->get_secciones($id_convocatoria, 'N1');
        $output['secciones']['N2'] = $this->convocatoria->get_secciones($id_convocatoria, 'N2');
        $vista = $this->get_convocatoria($id_convocatoria, 'convocatoria/secciones', $output);
        $this->template->setTitle("Convocatorias");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
//        $this->output->enable_profiler();
    }

    public function get_categorias($id_convocatoria = 0, $id_categoria = null, $tipo = null)
    {
        if ($this->input->post())
        {
            $params = $this->input->post(null, true);
            if ($id_categoria != null && $tipo != null)
            {
                $params['activa'] = false;
            } else
            {
                $params['activa'] = true;
            }
            $params['id_convocatoria'] = $id_convocatoria;
            $output['status'] = $this->convocatoria->upsert_categorias($params);
        } else if ($id_categoria != null && $tipo != null)
        {
            $params['activa'] = false;
            $params['id_convocatoria'] = $id_convocatoria;
            $params['tipo'] = $tipo;
            $params['id_categoria'] = $id_categoria;
            $output['status'] = $this->convocatoria->upsert_categorias($params);
        }
        $output['segmentos'] = $this->convocatoria->get_segmentos();
        $output['categorias'] = $this->convocatoria->get_categorias($id_convocatoria);
        $vista = $this->get_convocatoria($id_convocatoria, 'convocatoria/categorias', $output);
        $this->template->setTitle("Convocatorias");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    public function notificacion($id_convocatoria = 0)
    {

    }

    private function get_convocatoria($id_convocatoria, $vista_url, &$output = [])
    {
        $vista = "";
        $filtros['id_convocatoria'] = $id_convocatoria;
        $convocatoria = $this->convocatoria->get_convocatorias($filtros)[0];
        $output['convocatoria'] = $convocatoria;
        if ($convocatoria['id_tipo_convocatoria'] == 'N')
        {
            $filtros['tipo_convocatoria'] = $convocatoria['id_tipo_convocatoria'];
            $convocatoria = $this->convocatoria->get_convocatorias($filtros)[0];
            $output['convocatoria'] = $convocatoria;
//            pr($convocatoria);
            $vista = $this->load->view($vista_url, $output, true);
        }
        return $vista;
    }

    public function view_new($id_workflow = 0){
        $filtros['id_workflow'] = $id_workflow;
        $filtros['select'] = array('labels_fechas');
        $this->load->model('Workflow_model', 'ltm');
        $output = $this->ltm->get_workflows($filtros)[0];
        $output['fechas'] = $this->load->view('workflow/fechas.tpl.php', $output, true);
        $this->load->view('convocatoria/nueva_wf.tpl.php', $output);
    }

}
