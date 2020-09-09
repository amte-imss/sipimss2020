<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : Christian Garcia
 * */
class Precarga extends MY_Controller
{

    const LISTA = 'lista', EXPORTAR = 'exportar', LIMIT=25;

    function __construct()
    {
          parent::__construct();
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->model('Precarga_model', 'precarga');
    }

    public function index($vista = '')
    {
        switch($vista)
        {
            case Precarga::LISTA:
                $this->lista_precargas();
                break;
            case Precarga::EXPORTAR:
                $file_name = 'Registro_precargas_' . date('Ymd_his', time());
                $registros['data'] = $this->precarga->get_historial();
                $registros['columnas'] = array(
                    'id_precarga', 'fecha', 'id_usuario',
                   'username','nombre_archivo','peso', 'modelo', 'funcion',
                   'pendientes','total'
                );
               $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
                break;
            default:
                $output = [];
                $output['js'] = 'precarga/index.js';
                $output['exportar'] = site_url('precarga/index/exportar');
                $view = $this->load->view('precarga/index.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
        }
    }

    private function lista_precargas()
    {
        $params = $this->input->get(null, true);
        $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Precarga::LIMIT;
        $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
        $precargas['data'] = $this->precarga->get_historial($filtros);
        $filtros['total'] = true;
        $total = $this->precarga->get_historial($filtros)[0]['total'];
        $precargas['length'] = $total;

        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($precargas);
    }

    public function detalle($id = 0, $vista = '')
    {
        switch($vista)
        {
            case Precarga::LISTA:
                $this->lista_registros($id);
                break;
            case Precarga::EXPORTAR:
                $file_name = 'Detalle_precargas_' . date('Ymd_his', time());
                $filtros['where'] = array('id_precarga' => $id);
                $registros['data'] = $this->precarga->get_detalle($filtros);
                //pr($registros);
                $registros['columnas'] = array(
                    'id_precarga', 'id_detalle_precarga','detalle_registro', 'status',
                   'tabla_destino','id_tabla_destino','descripcion_status',
                );
                $this->exportar_xls($registros['columnas'], $registros['data'], null, null, $file_name);
                break;
            default:
                $output = [];
                $output['id'] = $id;
                $output['js'] = 'precarga/detalle.js';
                $output['exportar'] = site_url('precarga/detalle/'.$id.'/exportar');
                $output['var_js'] = array(
                    array(
                        "name" => 'data_id_precarga',
                        'value' => '"'.$id.'"'
                    )
                );
                $output['scripts_adicionales'] = array($this->load->view('tc_template/var_js_view.tpl.php', $output, true));
                $view = $this->load->view('precarga/index.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
                break;
        }
    }

    private function lista_registros($id)
    {
        $params = $this->input->get(null, true);
        $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Precarga::LIMIT;
        $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;
        $filtros['where'] = array('id_precarga' => $id);
        $precargas['data'] = $this->precarga->get_detalle($filtros);
        $filtros['total'] = true;
        $total = $this->precarga->get_detalle($filtros)[0]['total'];
        $precargas['length'] = $total;

        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($precargas);
    }

    public function cron($id = null)
    {
        $this->load->config('precarga');
        $configuracion = $this->config->item('configuracion_precarga');
        $filtros = [];
        if(!is_null($id))
        {
            $filtros['where'] = array('A.id_precarga'=>$id);
        }
        $registros = $this->precarga->get_registros_pendientes($configuracion, $filtros);
        $limite = $configuracion['limite'];

        foreach ($registros['modelos'] as $key => $value)
        {
            $this->load->model($key);
        }

        if(!$this->input->is_ajax_request())
        {
            echo 'Registros a procesar:';
            pr($registros);
        }

        for($i=0 ; $i<$limite && $i < count($registros['data']); $i++)
        {
            $modelo = $registros['data'][$i]['modelo'];
            $funcion = $registros['data'][$i]['funcion'];
            $data = $registros['data'][$i];
            $this->$modelo->$funcion($data);
        }
        pr($i);
        if($this->input->is_ajax_request())
        {
            $registros = $this->precarga->get_registros_pendientes($configuracion);
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($registros['data']);
        }
    }
}
