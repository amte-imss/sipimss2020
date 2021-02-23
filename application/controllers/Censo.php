<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : CAL
 * */
class Censo extends Validacion {

    function __construct() {
        parent::__construct();

    }
    
    public function reporte_general(){
        //$datos_sesion = $this->get_datos_sesion();
        //$datos_rol = $this->get_rol_aplica($datos_sesion); 
        //pr($datos_rol);

        $this->template->setTitle('Listado de validadores');
        
        $output['catalogos']['result_delegacional'] = $this->cm->get_delegaciones();
        array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
        $output['catalogos']['estados_validacion'] = $this->get_estados_validacion_censo_c();
        array_unshift($output['catalogos']['estados_validacion'], ['id'=>'',"label"=>'Selecciona...']); 
        $main_content = $this->load->view('reporte/body_reporte_general_censo.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function datos_reporte(){
        if ($this->input->is_ajax_request()) {
            $this->load->model('Reporte_model', 'reporte');
            $param = []; 
            
            $datos_sesion = $this->get_datos_sesion();
            $datos_rol = $this->get_rol_aplica($datos_sesion);            
            $output['docentes_reporte'] = [];
            //pr($datos_rol);
            if($datos_rol['reporte_docentes'] == 1){
                $output = $this->reporte->docentes_reporte_general_censo($datos_rol);
            }
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($output);

        }
    }



}