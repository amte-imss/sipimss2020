<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Rama_organica
 *
 * @author chrigarc
 */
class Rama_organica extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('rama');
        $this->config->load('general');
    }

    public function get_detalle($tipo_elemento = Rama::UNIDAD, $clave = '', $periodo = 2017)
    {
        $salida = [];
        if ($clave != '')
        {
            switch ($tipo_elemento)
            {
                case Rama::UNIDAD:
                    $filtros['periodo'] = $periodo;
                    $filtros['clave_unidad'] = $clave;

                    $salida = $this->rama->get_unidades($filtros);
                    break;
            }
        }
        echo json_encode($salida);
    }

    public function get_lista($lista = Rama::TIPO_UNIDAD, $opciones = null){
        $salida = [];
        $filtros = [];
        switch ($lista){
            case Rama::TIPO_UNIDAD:
                $filtros['nivel'] = $opciones;
                $salida = $this->rama->get_tipos_unidades($filtros);
                break;
        }
        echo json_encode($salida);
    }

    public function get_localizador()
    {
        //pr($this->input->post());
        if ($this->input->post())
        {

            if ($this->input->post('view') != null && $this->input->post('view', true) == 1)
            {
                //pr("renderrrrrrrrrr");
                //pr($this->input->post(null, true));
                $this->render_localizador();
            } else
            {
                $filtros = $this->input->post(null, true);
                
                $filtros['config'] = json_decode(base64_decode($filtros['config']), true);
                //pr("aquí es ");
                //pr($filtros);
                $filtros_unidades = $this->procesa_filtros_unidades($filtros);
                $output['datos'] = $this->rama->get_unidades($filtros_unidades);                
                $output['config'] = $filtros;
                $output['campos'] = $this->get_campos_unidad($filtros);
//                pr($output['datos']);
                $output['id_form'] = 'localizador_sede_table_';
                $this->load->view('rama_organica/paginador.tpl.php', $output);
            }
        } else
        {
            echo 'Configuración invalida';
        }
    }

    private function procesa_filtros_unidades($filtros)
    {
        $filtros_nuevos = [];
        $filtros_unidades = $this->config->item('filtros_unidades');
        //pr($filtros['config']['configuraciones']['anio']);
        
        foreach ($filtros_unidades as $kf => $fu)
        {
            foreach ($filtros as $key => $value)
            {
                //pr($key);
                //pr($kf);
                if (startsWith($key, $kf))
                {
                    $filtros_nuevos[$fu] = $value;
                }
            }
        }
        if (!isset($filtros_nuevos['periodo']))
        {
            if(isset($filtros['config']['configuraciones']['anio'])){
                $filtros_nuevos['periodo'] = $filtros['config']['configuraciones']['anio'];
            }else{
                $filtros_nuevos['periodo'] = date('Y');
            }
            
        }
        pr($filtros_nuevos);
        //pr($filtros_nuevos);
//        if($filtros['localizador_sede_id_servicio_'.$filtros['data_index']] == 2){
//            $filtros_nuevos['select'] = array('A.id_unidad_instituto', 'A.clave_unidad', 'A.nombre unidad',
//            'A.clave_presupuestal', 'A.nivel_atencion', 'A.latitud', 'A.longitud',
//            'B.clave_delegacional', 'B.nombre delegacion',
//            'C.id_region', 'C.nombre region', 'A.nombre_unidad');
//        }
        return $filtros_nuevos;
    }

    private function get_campos_unidad(&$filtros = [])
    {
        $campos_unidades = $this->config->item('columnas_unidades_grid');
        if ($filtros['localizador_sede_id_servicio_' . $filtros['data_index']] == 2)
        {
            $campos_unidades = $this->config->item('columnas_umae_grid');
        }
        $campos = array();
        foreach ($filtros['config']['configuraciones']['columnas'] as $val)
        {
            if (isset($campos_unidades[$val]))
            {
                $campos[] = $campos_unidades[$val];
            }
        }
        if (empty($campos))
        {
            $campos[] = $campos_unidades['cve_unidad'];
        }
//        pr($campos);
        return $campos;
    }

    private function render_localizador()
    {   
        $config = $this->input->post(null, true);
        $output['config'] = $config;
        $tipo_sede = $config['configuraciones']['tipo_sede'];
        
        $output['servicios'] = $this->rama->get_niveles_servicios($tipo_sede);
        if($tipo_sede != 2){//Académica = 2
            //        pr($config);
            $output['niveles'] = $this->rama->get_niveles_atencion(false);
            $output['delegaciones'] = dropdown_options($this->rama->get_delegaciones(), 'id', 'nombre');
            $output['unidades_normativas'] = []; //cambiar
            $filtros = $this->get_filtros_umae($config['configuraciones']['anio']);
            $output['umaes'] = dropdown_options($this->rama->get_unidades($filtros), 'clave_unidad', 'unidad');
        }
        $this->load->view('rama_organica/localizador.tpl.php', $output);
    }

    private function get_filtros_umae($periodo = null)
    {
        $filtros['periodo'] = $periodo;            
        if(is_null($periodo)){
            $filtros['periodo'] = date('Y');                        
        }
        $filtros['select'] = array(
            'A.clave_unidad', 'A.nombre_unidad_principal unidad'
        );
        $filtros['umae'] = true;
        $filtros['group_by'] = array('A.clave_unidad', 'A.nombre_unidad_principal');
        return $filtros;
    }

    public function tests()
    {
        $vista = $this->load->view('rama_organica/tests.tpl.php', [], true);
        $this->template->setTitle("Pruebas rama organica");
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

}
