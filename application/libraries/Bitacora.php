<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
define('TABLE_BITACORA', 'sistema.bitacora_sipimss');
define('BD_BITACORA_CONFIG', 'bitacoras');

/**
 * Description of Bitacora
 *
 * @author chrigarc
 */
class Bitacora
{

    //put your code here
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->db = $this->CI->load->database(BD_BITACORA_CONFIG, true);
    }

    public function registra_actividad()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $ip = $this->CI->input->ip_address();
        $valor = [];
        if ($this->CI->input->post())
        {
            $valor = $this->CI->input->post(null, true);
        }
        $valor = json_encode($valor);
        $uri = $this->CI->uri->uri_string();
        $usuario = null;
        if ($this->CI->session->userdata('die_sipimss') != null)
        {
            $usuario = $this->CI->session->userdata('die_sipimss')['usuario']['id_usuario'];
        }
        $insert = array(
            'id_usuario' => $usuario,
            'valor' => $valor,
            'ip' => $ip,
            'url' => $uri
        );

        $this->db->insert(TABLE_BITACORA, $insert);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
        }
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    function get_registros($params = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($params['total'])) {
            $select = 'count(*) total';
        } else if (isset($params['select']))
        {
            $select = $params['select'];
        } else
        {
            $select = array(
                'id_bitacora', 'id_usuario',
                'to_char(fecha, $$DD/mm/YYYY$$) fecha', 'ip', 'url'
            );
        }
        $this->db->select($select);
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
                $this->db->where($key, $value);
            }
        }
//        $this->db->where('date(fecha) = current_date', null, false);
        if (isset($params['limit']) && isset($params['offset']) && !isset($params['total']))
        {
            $this->db->limit($params['limit'], $params['offset']);
        } else if (isset($params['limit']) && !isset($params['total']))
        {
            $this->db->limit($params['limit']);
        }
        if(!isset($params['total']))
        {
            $this->db->order_by('id_bitacora', 'desc');
        }

        $query = $this->db->get(TABLE_BITACORA);
        $salida = $query->result_array();
        $query->free_result();
        // pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

}
