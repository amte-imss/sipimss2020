<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : Christian Garcia
 * */
class Precarga_model extends MY_Model
{

    public function get_historial($params = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($params['total']))
        {
            $select = 'count(distinct "A".id_precarga) total';
        } else if (isset($params['select']))
        {
            $select = $params['select'];
        } else
        {
            $select = array(
               'A.id_precarga',  'A.fecha', 'A.id_usuario', 'B.username',
               'A.nombre_archivo', 'A.peso', 'A.modelo', 'A.funcion',
               'sum((case when "C".status = $$SIN REALIZAR$$ then 1 else 0 end)) pendientes',
               'count("C".id_detalle_precarga) total'
            );
        }
        $this->db->select($select);
        $this->db->join('sistema.usuarios B','B.id_usuario = A.id_usuario', 'inner');
        $this->db->join('sistema.detalle_precargas C','C.id_precarga = A.id_precarga', 'inner');


        if (isset($params['where']))
        {
            foreach ($params['where'] as $key => $value)
            {
                $this->db->where($key, $value);
            }
        }
        if(isset($params['like']))
        {
            foreach ($params['like'] as $key => $value)
            {
                $this->db->like($key, $value);
            }
        }

        if (!isset($params['total']))
        {
            $this->db->group_by("1,2,3,4,5,6");
        }

//        $this->db->where('date(fecha) = current_date', null, false);
        if (isset($params['limit']) && isset($params['offset']) && !isset($params['total']))
        {
            $this->db->limit($params['limit'], $params['offset']);
        } else if (isset($params['limit']) && !isset($params['total']))
        {
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get('sistema.precargas A');
        $salida = $query->result_array();
        $query->free_result();
         // pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    // public function get

    public function get_registros_pendientes($configuracion, $filtros = [])
    {
        $registros = array();
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_precarga', 'A.modelo', 'A.funcion',
            'B.id_detalle_precarga', 'B.detalle_registro', 'B.tabla_destino'
        );
        $this->db->select($select);
        $this->db->where('B.status', 'SIN REALIZAR');
        if(isset($filtros['where']))
        {
            foreach ($filtros['where'] as $key => $value)
            {
                $this->db->where($key, $value);
            }
        }
        $this->db->join('sistema.detalle_precargas B', 'A.id_precarga = B.id_precarga', 'inner');
        $registros['data'] = $this->db->get('sistema.precargas A ')->result_array();
//         pr($this->db->last_query());
        $registros['modelos'] = [];
        foreach ($registros['data'] as $row)
        {
            if(!isset($registros['modelos'][$row['modelo']]))
            {
                $registros['modelos'][$row['modelo']] = true;
            }
        }
        // pr($registros);
        return $registros;
    }

    public function get_detalle($params = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($params['total']))
        {
            $select = 'count(*) total';
        } else if (isset($params['select']))
        {
            $select = $params['select'];
        } else
        {
            $select = '*';
        }
        $this->db->select($select);
        if (isset($params['where']))
        {
            foreach ($params['where'] as $key => $value)
            {
                $this->db->where($key, $value);
            }
        }
        if (isset($params['limit']) && isset($params['offset']) && !isset($params['total']))
        {
            $this->db->limit($params['limit'], $params['offset']);
        } else if (isset($params['limit']) && !isset($params['total']))
        {
            $this->db->limit($params['limit']);
        }
        $detalle = $this->db->get('sistema.detalle_precargas')->result_array();
        // pr($this->db->last_query());
        return $detalle;
    }
}
