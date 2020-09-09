<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Clase para agregar todos los metodos adicionales que neceistan los
* callback o links de grocery crud
* @author Christian Garcia
* @date Febrero 2018
* @version 1.0
*/

class Core_auxiliares
{

    const CAMPOS_FORMULARIO = 'campos_formulario', FORMULARIOS = 'formularios',
     ELEMENTOS_SECCION = 'elementos_seccion', SECCIONES = 'secciones';

    public function __construct($params = [])
    {
        foreach ($params as $key => $value)
        {
            $this->$key = $value;
        }
    }

    public function formulario_asociado_link($primary_key,$row)
    {
        return site_url('formulario/formulario/' . $row->id_elemento_seccion);
    }

    public function subsecciones_link($primary_key,$row)
    {
        return site_url('secciones/elementos_seccion/?id_seccion=' . $row->id_seccion);
    }

    public function subsecciones_hijas_link($primary_key,$row)
    {
        return site_url('secciones/elementos_seccion/?id_seccion=' . $row->id_seccion.'&id_elemento_seccion='.$row->id_elemento_seccion);
    }

    public function delete_formulario($primary_key)
    {
        $status = false;
        if(isset($this->db) && !is_null($this->db) && isset($this->formulario) && !is_null($this->formulario)){
            $registros = $this->tiene_registros(Core_auxiliares::FORMULARIOS, $primary_key);
            if(!$registros)
            {
                $this->db->flush_cache();
                $this->db->reset_query();
                $this->db->delete('ui.campos_formulario', array('id_campos_formulario' => $primary_key));
                $status = true;
            }
        }
        return $status;
    }

    public function delete_campos_formularios($primary_key)
    {
        $status = false;
        if(isset($this->db) && !is_null($this->db) && isset($this->formulario) && !is_null($this->formulario) && isset($this->catalogo) && !is_null($this->catalogo)){
            $registros = $this->tiene_registros(Core_auxiliares::CAMPOS_FORMULARIO, $primary_key);
            if(!$registros)
            {
                $this->db->flush_cache();
                $this->db->reset_query();
                $this->db->delete('ui.campos_formulario', array('id_campos_formulario' => $primary_key));
                $status = true;
            }
        }else{
            $status = true;
        }
        return $status;
    }

    public function delete_elementos_seccion($primary_key)
    {
        $status = false;
        if(isset($this->db) && !is_null($this->db) && isset($this->catalogo) && !is_null($this->catalogo)){
            $registros = $this->tiene_registros(Core_auxiliares::ELEMENTOS_SECCION, $primary_key);
            if(!$registros)
            {
                $this->db->flush_cache();
                $this->db->reset_query();
                $this->db->delete('catalogo.elementos_seccion', array('id_elemento_seccion' => $primary_key));
                $status = true;
            }
        }
        return $status;
    }

    public function delete_secciones($primary_key)
    {
        $status = false;
        if(isset($this->db) && !is_null($this->db) && isset($this->catalogo) && !is_null($this->catalogo))
        {
            $registros = $this->tiene_registros(Core_auxiliares::SECCIONES, $primary_key);
            if(!$registros)
            {
                $this->db->flush_cache();
                $this->db->reset_query();
                $this->db->delete('catalogo.secciones', array('id_seccion' => $primary_key));
                $status = true;
            }
        }else
        {

        }
        return $status;
    }

    private function tiene_registros($tabla = '', $primary_key){

        $status = false;
        switch($tabla)
        {
            case Core_auxiliares::FORMULARIOS:
                $status = count($this->formulario->get_config_campos_formulario(array('id_formulario' => $primary_key)))>0;
                break;
            case Core_auxiliares::ELEMENTOS_SECCION:
                $filtros['where'] = array('id_elemento_seccion' => $primary_key);
                $forms = $this->catalogo->get_registros('ui.formulario', $filtros);
                $filtros['where'] = array('id_catalogo_elemento_padre' => $primary_key);
                $hijos = $this->catalogo->get_registros('catalogo.elementos_seccion', $filtros);
                $status = count($forms) > 0 || count($hijos) > 0;
                // $status = false;
                break;
            case Core_auxiliares::SECCIONES:
                $filtros['where'] = array('id_seccion' => $primary_key);
                $dependencias = $this->catalogo->get_registros('catalogo.elementos_seccion', $filtros);
                $status = count($dependencias) > 0;
                break;
            case Core_auxiliares::CAMPOS_FORMULARIO:
                $filtros['where'] = array('id_campos_formulario' => $primary_key);
                $regs = $this->catalogo->get_registros('censo.censo_info', $filtros);
                $filtros = array('id_campos_formulario' => $primary_key);
                $json = $this->get_registros_json_by_id_campos_formulario($primary_key);
                $status = count($regs) > 0 || count($json) > 0;
                break;
            default:
                break;
        }
        return $status;
    }

    private function get_registros_json_by_id_campos_formulario($id_campos_formulario)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->join('ui.campo B ',' B.id_campo = A.id_campo', 'inner');
        $this->db->join('censo.censo C',' C.id_formulario = A.id_formulario', 'inner');
        $this->db->where('"C".formulario_registros::text ilike concat($$%$$,"B".nombre,$$%$$)', null, false);
        $this->db->where('A.id_campos_formulario', $id_campos_formulario);
        $json = $this->db->get('ui.campos_formulario A')->result_array();
        return $json;
    }
}
