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
        $this->load->model('Reporte_model', 'reporte');
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

    public function reporte_formacion_docente(){
        $this->template->setTitle('Reporte de formación docente');
        
        $datos_sesion = $this->get_datos_sesion();
        $datos_rol = $this->get_rol_aplica($datos_sesion);
        $output['data_docentes'] = $this->format_data_formacion_docente($this->reporte->reporte_formacion_docente($datos_rol));

        $main_content = $this->load->view('reporte/reporte_formacion_docente.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    private function format_data_formacion_docente($data_docentes){
        $resultado = $js = array();
        foreach ($data_docentes as $data) {
            if(is_null($data['umae']) OR empty($data['umae'])) {
                //$resultado['D'][$data['delegacion']]['datos'][] = $data;
                $resultado['D'][$data['delegacion']]['total'] = (!isset($resultado['D'][$data['delegacion']]['total'])) ? 1 : $resultado['D'][$data['delegacion']]['total'] + 1;
                $resultado['D'][$data['delegacion']]['cumplen'] = (!isset($resultado['D'][$data['delegacion']]['cumplen'])) ? (($data['cumplimiento'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['cumplen'] + $data['cumplimiento'];
                $resultado['D'][$data['delegacion']]['curso_corto_educacion_cumple'] = (!isset($resultado['D'][$data['delegacion']]['curso_corto_educacion_cumple'])) ? (($data['curso_corto_educacion_cumple'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['curso_corto_educacion_cumple'] + $data['curso_corto_educacion_cumple'];
                $resultado['D'][$data['delegacion']]['diplomado_educacion_cumple'] = (!isset($resultado['D'][$data['delegacion']]['diplomado_educacion_cumple'])) ? (($data['diplomado_educacion_cumple'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['diplomado_educacion_cumple'] + $data['diplomado_educacion_cumple'];
                $resultado['D'][$data['delegacion']]['especialidad_educacion_cumple'] = (!isset($resultado['D'][$data['delegacion']]['especialidad_educacion_cumple'])) ? (($data['especialidad_educacion_cumple'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['especialidad_educacion_cumple'] + $data['especialidad_educacion_cumple'];
                $resultado['D'][$data['delegacion']]['maestria_educacion_cumple'] = (!isset($resultado['D'][$data['delegacion']]['maestria_educacion_cumple'])) ? (($data['maestria_educacion_cumple'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['maestria_educacion_cumple'] + $data['maestria_educacion_cumple'];
                $resultado['D'][$data['delegacion']]['doctorado_educacion_cumple'] = (!isset($resultado['D'][$data['delegacion']]['doctorado_educacion_cumple'])) ? (($data['doctorado_educacion_cumple'] > 0) ? 1 : 0) : $resultado['D'][$data['delegacion']]['doctorado_educacion_cumple'] + $data['doctorado_educacion_cumple'];
            } else {
                //$resultado['U'][$data['umae']]['datos'][] = $data;
                //pr($data);
                $resultado['U'][$data['umae']]['total'] = (!isset($resultado['U'][$data['umae']]['total'])) ? 1 : $resultado['U'][$data['umae']]['total'] + 1;
                $resultado['U'][$data['umae']]['cumplen'] = (!isset($resultado['U'][$data['umae']]['cumplen'])) ? (($data['cumplimiento'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['cumplen'] + $data['cumplimiento'];
                $resultado['U'][$data['umae']]['curso_corto_educacion_cumple'] = (!isset($resultado['U'][$data['umae']]['curso_corto_educacion_cumple'])) ? (($data['curso_corto_educacion_cumple'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['curso_corto_educacion_cumple'] + $data['curso_corto_educacion_cumple'];
                $resultado['U'][$data['umae']]['diplomado_educacion_cumple'] = (!isset($resultado['U'][$data['umae']]['diplomado_educacion_cumple'])) ? (($data['diplomado_educacion_cumple'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['diplomado_educacion_cumple'] + $data['diplomado_educacion_cumple'];
                $resultado['U'][$data['umae']]['especialidad_educacion_cumple'] = (!isset($resultado['U'][$data['umae']]['especialidad_educacion_cumple'])) ? (($data['especialidad_educacion_cumple'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['especialidad_educacion_cumple'] + $data['especialidad_educacion_cumple'];
                $resultado['U'][$data['umae']]['maestria_educacion_cumple'] = (!isset($resultado['U'][$data['umae']]['maestria_educacion_cumple'])) ? (($data['maestria_educacion_cumple'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['maestria_educacion_cumple'] + $data['maestria_educacion_cumple'];
                $resultado['U'][$data['umae']]['doctorado_educacion_cumple'] = (!isset($resultado['U'][$data['umae']]['doctorado_educacion_cumple'])) ? (($data['doctorado_educacion_cumple'] > 0) ? 1 : 0) : $resultado['U'][$data['umae']]['doctorado_educacion_cumple'] + $data['doctorado_educacion_cumple'];
            }
            array_push($js, array('matricula' => $data['matricula'], "nombre_docente" => $data['nombre_docente'], "curp" => $data['curp'], 
                    "email" => $data['email'], "delegacion" => $data['delegacion'], "clave_unidad" => $data['clave_unidad'],
                    "nom_unidad" => $data['nom_unidad'], "umae" => $data['umae'], "clave_departamental" => $data['clave_departamental'],
                    "departamento" => $data['departamento'], "clave_categoria" => $data['clave_categoria'], "categoria" => $data['categoria'],
                    "curso_corto_educacion" => $data['curso_corto_educacion'],"diplomado_educacion" => $data['diplomado_educacion'],
                    "especialidad_educacion" => $data['especialidad_educacion'], "maestria_educacion" => $data['maestria_educacion'],
                    "doctorado_educacion" => $data['doctorado_educacion'], "curso_corto_educacion_cumple" => $data['curso_corto_educacion_cumple'], 
                    "diplomado_educacion_cumple" => $data['diplomado_educacion_cumple'],
                    "especialidad_educacion_cumple" => $data['especialidad_educacion_cumple'], 
                    "maestria_educacion_cumple" => $data['maestria_educacion_cumple'],
                    "doctorado_educacion_cumple" => $data['doctorado_educacion_cumple'],
                    "cumplimiento" => $data["cumplimiento"])
                );
        }
        if(isset($resultado['U'])){
            ksort($resultado['U']);
        }
        if(isset($resultado['D'])){
            ksort($resultado['D']);
        }
        $resultado['js'] = $js;
        //pr($resultado);
        return $resultado;
    }

    public function reporte_pregrado(){
        //$datos_sesion = $this->get_datos_sesion();
        //$datos_rol = $this->get_rol_aplica($datos_sesion); 
        //pr($datos_rol);
        //pr($this->uri->rsegment(2));
        $this->template->setTitle('Listado de docentes pregrado');
        $output['title'] = 'Listado de docentes pregrado';
        $output['exportar_title'] = 'Exportar';
        
        $output['catalogos']['result_delegacional'] = $this->cm->get_delegaciones();
        array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
        $output['catalogos']['estados_validacion'] = $this->get_estados_validacion_censo_c();
        array_unshift($output['catalogos']['estados_validacion'], ['id'=>'',"label"=>'Selecciona...']); 
        $main_content = $this->load->view('reporte/body_reporte_pregrado_censo.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    public function datos_reporte_pregrado(){
        if ($this->input->is_ajax_request()) {
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