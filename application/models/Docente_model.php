<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Docente_model extends MY_Model {

    public function __construct() {
        // Call the padre constructor
        parent::__construct();
    }

    public function get_datos_generales($id_docente) {
        $usuario = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('d.id_docente', $id_docente);
        $this->db->join('censo.historico_datos_docente hdd', 'hdd.id_docente = d.id_docente and hdd.actual = 1', 'left');
        $this->db->join('catalogo.estado_civil ec', 'ec.id_estado_civil = d.id_estado_civil', 'left');
        $resultado = $this->db->get('censo.docente d')->result_array();
        if ($resultado) {
            $usuario = $resultado[0];
        }
        //pr($usuario==null?'1':'0');
        return $usuario;
    }

    /**
     * @author LEAS
     * @fecha 11/05/2017
     * @param type $id_docente
     * @return type
     */
    public function get_historico_datos_generales($id_docente) {
        $select = array(
            "dd.id_historico_docente", "dd.fecha echa_ultima_actualizacion"
            , "dd.id_departamento_instituto", "di.clave_departamental", "concat(di.nombre,' (',di.clave_departamental,')' ) departamento"
            , "u.id_unidad_instituto", "u.clave_unidad", "u.nombre nom_unidad", "u.nivel_atencion"
            , "u.id_tipo_unidad", "tu.nombre nom_tipo_unidad"
            , "d.id_delegacion", "d.clave_delegacional", "concat(d.nombre,' (',d.clave_delegacional,')' ) delegacion"
            , "tc.cve_tipo_contratacion", "tc.tipo_contratacion",
            "r.id_region", "r.nombre region",
            "cc.id_categoria", "cc.clave_categoria", "concat(cc.nombre, ' (', cc.clave_categoria, ')') categoria"
        );
        $this->db->where('dd.id_docente', $id_docente);
        $this->db->where('dd.actual', 1);
        $this->db->select($select);
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = dd.id_departamento_instituto');
        $this->db->join('catalogo.categorias cc', 'cc.id_categoria = dd.id_categoria', 'left');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad');
        $this->db->join('catalogo.delegaciones d', 'd.id_delegacion = u.id_delegacion');
        $this->db->join('catalogo.tipos_unidades tu', 'tu.id_tipo_unidad = u.id_tipo_unidad', 'left');
        $this->db->join('catalogo.regiones r', 'r.id_region = d.id_region', 'left');
        $this->db->join('catalogo.tipo_contratacion tc', 'tc.cve_tipo_contratacion = dd.cve_tipo_contratacion', 'left');
        $result = $this->db->get('censo.historico_datos_docente dd');
        $array_result = $result->result_array();
//        pr($this->db->last_query());
        if (!empty($array_result)) {
            return $array_result[0]; //retorna un array con los datos del historico o del docente
        }
    }

    public function update_datos_imss($id_docente, $id_departamento_unidad, $clave_delegacional, $id_categoria, $datos_docente) {
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL)); //Carga textos de lenguaje
        $this->db->trans_begin(); //Inicia la transacción
        $this->db->where('censo.historico_datos_docente.id_docente', $id_docente); //Id censo
        $this->db->where('censo.historico_datos_docente.actual', 1); //Id censo
        $array_update = array(//Modifica último registro
            'actual' => 0
        );

        $this->db->update('censo.historico_datos_docente', $array_update); //Actualiza el último a false
        if ($this->db->trans_status() === FALSE) {//Valida que se actualizo correctamente el último registro
            $this->db->trans_rollback();
            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['update_ultimo_registro_historico_falling']);
        } else {
            $array_insert = array(
                'id_categoria' => $id_categoria,
                'id_docente' => $id_docente,
                'id_departamento_instituto' => $id_departamento_unidad,
                'actual' => 1,
            );

            $id_insert = $this->db->insert('censo.historico_datos_docente', $array_insert); //Se inserta el nuevo registro del historico de datos IMSS

            if ($this->db->trans_status() === FALSE and $id_insert > 0) {//Valida que se inserto el archvo success
                $this->db->trans_rollback();
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['update_ultimo_registro_historico_falling']);
            } else {
                $array_insert = array(
                    'rfc' => $datos_docente['rfc'],
                    'curp' => $datos_docente['curp'],
                    'sexo' => $datos_docente['sexo'],
                    'nombre' => $datos_docente['nombre'],
                    'apellido_p' => $datos_docente['paterno'],
                    'apellido_m' => $datos_docente['materno'],
                    'fecha_nacimiento' => $datos_docente['nacimiento'],
                    'status_siap' => $datos_docente['status'],
                );
                $this->db->where('censo.docente.id_docente', $id_docente); //Id censo
                $this->db->update('censo.docente', $array_insert); //Se inserta el nuevo registro del historico de datos IMSS                
                if ($this->db->trans_status() === FALSE) {//Valida que se inserto el archvo success
                    $this->db->trans_rollback();
                    $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['update_ultimo_registro_historico_falling']);
                } else {
                    $this->db->trans_commit();
                    $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['update_ultimo_registro_historico_succes']);
                    $this->db->reset_query();
                }
            }
        }
        return $respuesta;
    }

    public function update_datos_generales($id_docente, $datos_docente) {
        $this->db->reset_query();
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL)); //Carga textos de lenguaje
        $this->db->trans_begin(); //Inicia la transacción
        $this->db->where('id_docente', $id_docente); //Id censo
        $array_update = array(//Modifica último registro
            'nombre' => $datos_docente['nombre'],
            'apellido_p' => $datos_docente['apellido_p'],
            'apellido_m' => $datos_docente['apellido_m'],
            //'num_empleos_fuera' => $datos_docente['num_empleos_fuera'],
            'telefono_particular' => $datos_docente['telefono_particular'],
            'telefono_laboral' => $datos_docente['telefono_laboral'],
            'id_estado_civil' => $datos_docente['estado_civil'],
            'email' => $datos_docente['email'],
        );

        $this->db->update('censo.docente', $array_update); //Actualiza el último a false
        if ($this->db->trans_status() === FALSE) {//Valida que se actualizo correctamente el último registro
            $this->db->trans_rollback();
            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => $string_value['update_ultimo_registro_historico_falling']);
        } else {
            $this->db->trans_commit();
            $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => $string_value['update_ultimo_registro_historico_succes']);
        }
        return $respuesta;
    }

    /**
     * @author LEAS
     * @access public
     * @fecha 22/05/2017
     * @param type $id_docente id del docente para cargar imagen
     */
    public function get_imagen_perfil($id_docente) {
        $select = array('f.id_file',
            'f.nombre_fisico', 'f.ruta', 'fext.nombre extencion'
            , "concat(d.nombre, ' ', d.apellido_p, ' ', d.apellido_m) nombre_docente"
            , "d.matricula"
        );
        $this->db->where('d.id_docente', $id_docente);
//        $this->db->where('u.activo', true);
        $this->db->where('d.activo', true);
        $this->db->select($select);
        $this->db->join('sistema.usuarios u', 'u.id_usuario = d.id_usuario', 'left');
        $this->db->join('censo.files f', 'f.id_file = u.id_file', 'left');
        $this->db->join('censo.extencion_file fext', 'fext.id_extencion_file = f.id_extencion_file', 'left');
        $result = $this->db->get('censo.docente d');
        $array_result = $result->result_array();
//        pr($this->db->last_query());
        if (!empty($array_result)) {
            return $array_result[0];
        }
        return $array_result;
    }

}
