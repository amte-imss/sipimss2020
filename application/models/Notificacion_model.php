<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Notificacion_model
 *
 * @author chrigarc
 */
class Notificacion_model extends MY_Model
{

    //put your code here
    function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
    }

    public function lanza_notificaciones($noficaciones = []){

    }

    public function get_notificaciones_usuario($params = []){
        if(isset($params['usuario'])){

        }
    }

    /**
     * Funcion que agrega una nueva notificación estatica
     * @author Cheko
     * @param type $nombre_tabla el nombre de la tabla a actualizar
     * @param Array $parametros Arreglo de parametros, los datos agregar
     *
     */
    public function agregar_notificiacion_estatica($nombre_tabla=NULL, $parametros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $status = array('success' => false, 'message' => 'Nombre de tabla incorrecto', 'data'=>[]);
        if(is_null($nombre_tabla))
        {
            return $status;
        }

        try
        {
            $this->db->insert($nombre_tabla, $parametros);
            $status['success'] = true;
            $status['message'] = 'Agregado con éxito';
            $status['data'] = array('id_elemento' => $this->db->insert_id());
        }catch(Exception $ex)
        {

        }
        return $status;
    }

    /**
     * Funcion que obtiene la notificacion
     * @author Cheko
     * @param type $nombre_tabla nombre de la tabla a actualizar
     * @param Array $parametros los parametros para actualizar la notificiacion
     * @param Array $where_ids arreglo de que notificacion(s) actualizar
     *
     */
    public function obtener_notificacion_estatica()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        try
        {
            $this->db->select('*');
            $datestring = '%Y-%m-%d';
            $hoy = time();
            $fecha_actual = mdate($datestring, $hoy);
            $this->db->where('fecha_fin >=',$fecha_actual);
            $resultado = $this->db->get('ui.notificaciones_estaticas');
            $status = $resultado->result_array();
            // pr($this->db->last_query());
            $resultado->free_result();
        }catch(Exception $ex)
        {

        }
        return $status;
    }
}
