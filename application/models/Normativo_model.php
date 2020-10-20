<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Normativo_model
 *
 * @author jzdp
 */
class Normativo_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_delegacional($filtros = [])
    {
        $this->db->select(array('count(distinct d.id_docente) total', 'u.id_delegacion'));
        $this->db->join('censo.docente d', 'd.id_docente = c.id_docente');
        $this->db->join('censo.historico_datos_docente hd', 'hd.id_docente = d.id_docente');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = hd.id_departamento_instituto');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad');
        $this->db->where("u.umae <> true and (u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)");
        if(isset($filtros['where'])) {
            foreach ($filtros['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->group_by('u.id_delegacion');        
        $this->db->from('censo.censo c');
        $subquery = $this->db->get_compiled_select();

        $this->db->select(array('count(distinct d.id_docente) total', 'u.id_delegacion'));
        $this->db->join('censo.docente d', 'd.id_usuario = us.id_usuario');
        $this->db->join('censo.historico_datos_docente hd', 'hd.id_docente = d.id_docente');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = hd.id_departamento_instituto');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad');
        $this->db->where("u.umae <> true and (u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)");
        if(isset($filtros['where'])) {
            foreach ($filtros['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->group_by('u.id_delegacion'); 
        $this->db->from('sistema.usuarios us');
        $subquery2 = $this->db->get_compiled_select();
        
        $this->db->select(array('del.clave_delegacional', 'del.nombre', 'coalesce(t.total, 0) total', 'coalesce(t2.total, 0) total2'));
        $this->db->from('catalogo.delegaciones del');
        $this->db->join('('.$subquery.') as t', 'del.id_delegacion = t.id_delegacion', 'left');
        $this->db->join('('.$subquery2.') as t2', 'del.id_delegacion = t2.id_delegacion', 'left');
        $this->db->order_by('del.nombre');
        $result = $this->db->get()->result_array();
        //pr($this->db->last_query());
        
        return $result;
    }

    public function get_umae($filtros = [])
    {
        $this->db->select(array('count(distinct d.id_docente) total', 'u.clave_unidad'));
        $this->db->from('censo.censo c');
        $this->db->join('censo.docente d', 'd.id_docente = c.id_docente');
        $this->db->join('censo.historico_datos_docente hd', 'hd.id_docente = d.id_docente');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = hd.id_departamento_instituto');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad');
        $this->db->where("(u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE')) and anio = 2020");
        if(isset($filtros['where'])) {
            foreach ($filtros['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->group_by('u.clave_unidad');
        $subquery = $this->db->get_compiled_select();

        $this->db->select(array('count(distinct d.id_docente) total', 'u.clave_unidad'));
        $this->db->from('sistema.usuarios us');
        $this->db->join('censo.docente d', 'd.id_usuario = us.id_usuario');
        $this->db->join('censo.historico_datos_docente hd', 'hd.id_docente = d.id_docente');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = hd.id_departamento_instituto');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad');
        $this->db->where("(u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE')) and anio = 2020");
        if(isset($filtros['where'])) {
            foreach ($filtros['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->group_by('u.clave_unidad');
        $subquery2 = $this->db->get_compiled_select();

        $this->db->select(array('u2.unidad_principal', 'u2.nombre_unidad_principal', 'coalesce(sum(t.total), 0) total', 'coalesce(sum(t2.total), 0) total2'));
        $this->db->from('catalogo.unidades_instituto u2');
        $this->db->join('('.$subquery.') as t', 't.clave_unidad = u2.clave_unidad', 'left');
        $this->db->join('('.$subquery2.') as t2', 't2.clave_unidad = u2.clave_unidad', 'left');
        $this->db->where("(u2.umae = true or u2.grupo_tipo_unidad in ('UMAE','CUMAE')) and anio = 2020");
        $this->db->group_by('u2.unidad_principal, u2.nombre_unidad_principal');
        $this->db->order_by('u2.nombre_unidad_principal');
        $result = $this->db->get()->result_array();
        //pr($this->db->last_query());
        
        return $result;
    }
}
