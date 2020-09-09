<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Description of Ayuda_model
*
* @author chrigarc
*/
class Ayuda_model extends MY_Model
{

    const LOREM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get($clave = null)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('descripcion');
        $this->db->where('clave_ayuda', $clave);
        $ayuda = $this->db->get('ui.ayudas')->result_array();
        $this->db->reset_query();
        if(empty($ayuda)){
            $ayuda = [];
            $ayuda = Ayuda_model::LOREM;
        }else{
            $ayuda = $ayuda[0]['descripcion'];
        }
        return $ayuda;
    }
}
