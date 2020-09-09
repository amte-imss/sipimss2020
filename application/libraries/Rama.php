<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

class Rama
{

    const UNIDAD = 'unidad', TIPO_UNIDAD = 'tipo_unidad';

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->db = $this->CI->load->database('default', true);
    }

    public function get_niveles_atencion($todas = true)
    {
        $niveles = array(
            1 => 'Primer nivel',
            2 => 'Segundo nivel',
        );
        if($todas){
            $niveles[3] = 'Tercer nivel';
        }
        return $niveles;
    }

    public function get_niveles_servicios()
    {
        $servicios = array(
            1 => 'Delegacional',
            2 => 'UMAE',
            3 => 'CIEFD',
            4 => 'ESCUELAS DE ENFERMERIA',
            5 => 'OFICINAS CENTRALES',
            6 => 'CENTROS VACACIONALES'
        );
        return $servicios;
    }

    public function get_tipos_unidades($filtros = []){
        $tipos_unidades = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
          'id_tipo_unidad id', 'nombre'
        );
        $this->db->select($select);
        if(isset($filtros['nivel']))
        {
            $this->db->where('nivel', $filtros['nivel']);
        }
        $this->db->where('activa', true);
        $tipos_unidades = $this->db->get('catalogo.tipos_unidades')->result_array();
        return $tipos_unidades;
    }


    public function get_delegaciones($filtros = [])
    {
        $delegaciones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'clave_delegacional id', 'nombre'
        );
        $this->db->select($select);
        $this->db->order_by('nombre');
        $delegaciones = $this->db->get('catalogo.delegaciones A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        return $delegaciones;
    }

    public function get_regiones($filtros = [])
    {

    }

    public function get_unidades($filtros = [])
    {
       // pr($filtros);
        $unidades = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_unidad_instituto', 'A.clave_unidad', 'A.nombre unidad',
            'A.clave_presupuestal', 'A.nivel_atencion', 'A.latitud', 'A.longitud',
            'B.clave_delegacional', 'B.nombre delegacion',
            'C.id_region', 'C.nombre region', 'A.nombre_unidad_principal unidad_principal' , 'A.clave_unidad_principal clave_unidad_principal'
        );
        if(isset($filtros['select']))
        {
            $select = $filtros['select'];
        }
        $this->db->select($select);
        $this->db->join('catalogo.delegaciones B', 'B.id_delegacion = A.id_delegacion', 'inner');
        $this->db->join('catalogo.regiones C', 'C.id_region = B.id_region', 'inner');
        if (isset($filtros['delegacion']) && $filtros['delegacion'] != '')
        {
            $this->db->where('B.clave_delegacional', $filtros['delegacion']);
        }
        if (isset($filtros['nivel']) && $filtros['nivel'] != '')
        {
            $this->db->where('A.nivel_atencion', $filtros['nivel']);
        }
        if(isset($filtros['nivel_servicio']) && $filtros['nivel_servicio'] == 2)
        {
            $this->db->where("(A.grupo_tipo_unidad = 'UMAE' or A.grupo_tipo_unidad = 'CUMAE')");
        }
        if(isset($filtros['nivel_servicio']) && $filtros['nivel_servicio'] == 3)
        {
            $this->db->where("A.grupo_tipo_unidad",'CIEFD');
        }
        if(isset($filtros['nivel_servicio']) && $filtros['nivel_servicio'] == 4)
        {
            $this->db->where("A.grupo_tipo_unidad",'ESC_ENF');
        }
        if(isset($filtros['nivel_servicio']) && $filtros['nivel_servicio'] == 5)
        {
            $this->db->where("A.grupo_tipo_unidad",'OFI_CEN');
        }
        if(isset($filtros['nivel_servicio']) && $filtros['nivel_servicio'] == 6)
        {
            $this->db->where("A.grupo_tipo_unidad",'CEN_VAC');
        }
        if(isset($filtros['tipo_unidad']) && $filtros['tipo_unidad']!= '')
        {
            $this->db->where('id_tipo_unidad', $filtros['tipo_unidad']);
        }
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $this->db->where('A.anio', $filtros['periodo']);
        }
        if(isset($filtros['clave_unidad']) && $filtros['clave_unidad'] != '')
        {
            $this->db->where('clave_unidad', $filtros['clave_unidad']);
        }
        if(isset($filtros['umae']) && $filtros['umae'])
        {
            $this->db->where("A.grupo_tipo_unidad", 'UMAE');
        }
        if(isset($filtros['clave_unidad_principal']) && $filtros['clave_unidad_principal'] != '')
        {
            $this->db->where("A.clave_unidad_principal", $filtros['clave_unidad_principal']);
        }
        if(isset($filtros['group_by']))
        {
            $this->db->group_by($filtros['group_by']);
        }
//        $this->db->limit(10);
        $unidades = $this->db->get('catalogo.unidades_instituto A')->result_array();
        // pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $unidades;
    }

}
