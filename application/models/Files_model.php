<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: LEAS
 * @version: 1.0
 * @desc: Clase modelo de consultas para de obtener archivos
 * */
class Files_model extends MY_Model {

    public function __construct() {
        // Call the padre constructor
        parent::__construct();
    }

    /**
     * @author LEAS
     * @param type $id_file identificador del archivo en la base de datos
     * @return type array vacio si no existe el id del archivo, si no retorna un array 
     * con un registro de el archivo, con los siguientes identificadores
     * 'id_file', 'nombre_fisico', 'ruta'
      , 'id_extencion_file', 'nombre nombre_extencion',
     */
    public function get_file($id_file) {
        $select = array(
            'f.id_file', 'f.nombre_fisico', 'f.ruta'
            , 'ef.id_extencion_file', 'ef.nombre nombre_extencion',
        );
        $this->db->select($select);
        $this->db->where('f.id_file', $id_file);

        $this->db->join('censo.extencion_file ef', 'ef.id_extencion_file = f.id_extencion_file');
        $resultado = $this->db->get('censo.files f');
//        pr($this->db->last_query());
        return $resultado->result_array();
    }

    public function update_file($id_file, $nombre_fisico, $ruta, $extencion_texto) {

        $datos_extencion = $this->get_id_extencion_file($extencion_texto);
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GUARDAR_ACTUALIZAR));
        if (!empty($datos_extencion)) {//Se encontro la extención del archivo
            $this->db->trans_begin();
            $array_update = array(
                'nombre_fisico' => $nombre_fisico,
                'ruta' => $ruta,
                'id_extencion_file' => $datos_extencion[0]['id_extencion_file'],
            );
            $this->db->where('id_file', $id_file);
            $this->db->update('censo.files', $array_update);
            if ($this->db->trans_status()) {
                $this->db->trans_commit();
                $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['success_imagen']);
            } else {
                $this->db->trans_rollback();
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['error_imagen']);
            }
        } else {
            $respuesta = array('tp_msg' => En_tpmsg::WARNING, 'mensaje' => $string_value['no_extencion_archivo']);
        }
        return $respuesta;
    }

    public function get_id_extencion_file($extencion_nombre) {
        $this->db->where('nombre', $extencion_nombre);
        $this->db->where('activo', TRUE);
        $resultado = $this->db->get('censo.extencion_file');
        return $resultado->result_array();
    }

    private function get_existe_usuario($id_usuario) {
        $this->db->where('id_usuario', $id_usuario);
        $resultado = $this->db->get('sistema.usuarios');
        return $resultado->num_rows();
    }

    public function insert_file($id_usuario, $nombre_fisico, $ruta, $extencion_texto) {
        $datos_extencion = $this->get_id_extencion_file($extencion_texto);
        $count_user = $this->get_existe_usuario($id_usuario);
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GUARDAR_ACTUALIZAR));
        if ($count_user > 0) {


            if (!empty($datos_extencion)) {//Se encontro la extención del archivo
                $this->db->trans_begin();
                $array_insert = array(
                    'nombre_fisico' => $nombre_fisico,
                    'ruta' => $ruta,
                    'id_extencion_file' => $datos_extencion[0]['id_extencion_file'],
                );

                $this->db->insert('censo.files', $array_insert);
                $id_file = $this->db->insert_id();
                if ($this->db->trans_status() AND $id_file > 0) {//Inserto el archivo correctamente
                    $array_update = array(//Agreaga el id del archivo
                        'id_file' => $id_file,
                    );

//                $this->db->where('id_docente', $id_docente);
                    $this->db->where('id_usuario', $id_usuario);
                    $this->db->update('sistema.usuarios', $array_update);

                    if ($this->db->trans_status() === FALSE) {//ocurrio un error 
                        $this->db->trans_rollback();
                        $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['error_imagen']);
                    } else {
                        $this->db->trans_commit();
                        $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['success_imagen']);
                    }
                } else {
                    $this->db->trans_rollback();
                    $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['error_imagen']);
                }
            } else {
                $respuesta = array('tp_msg' => En_tpmsg::WARNING, 'mensaje' => $string_value['no_extencion_archivo']);
            }
        } else {
            $respuesta = array('tp_msg' => En_tpmsg::WARNING, 'mensaje' => $string_value['error_encontrar_usuario']);
        }
        return $respuesta;
    }

    /**
     * @author LEAS
     * @fecha 05/07/2017
     * @param type $id_file identificador del archivo 
     * @return type array 'tp_msg' success si tuvo exito la transacción; danger 
     * si ocurrio un rollback o un error
     */
    public function delete_file($id_file) {
        $this->db->trans_begin();
        $this->db->where('id_file', $id_file);
        $this->db->delete('censo.files');
        if ($this->db->trans_status() === FALSE) {//ocurrio un error 
            $this->db->trans_rollback();
            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => '');
        } else {
            $this->db->trans_commit();
            $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => '');
        }
        return $respuesta;
    }

}
