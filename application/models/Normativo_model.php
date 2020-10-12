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
        $this->db->from('censo.censo c');
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
        $subquery = $this->db->get_compiled_select();


        $this->db->select(array('del.clave_delegacional', 'del.nombre', 't.total'));
        $this->db->from('catalogo.delegaciones del');
        $this->db->join('('.$subquery.') as t', 'del.id_delegacion = t.id_delegacion', 'left');
        $this->db->order_by('del.nombre');
        $result = $this->db->get()->result_array();
        
        return $result;
    }
}
/*
--OOAD temp
select del.clave_delegacional, del.nombre, t.total
from catalogo.delegaciones del 
left join 
(select count(distinct d.id_docente) total, u.id_delegacion
from censo.censo c
inner join censo.docente d on d.id_docente = c.id_docente 
inner join censo.historico_datos_docente hd on hd.id_docente = d.id_docente 
inner join catalogo.departamentos_instituto di on di.id_departamento_instituto = hd.id_departamento_instituto 
inner join catalogo.unidades_instituto u on u.clave_unidad = di.clave_unidad 
--inner join catalogo.delegaciones del on del.id_delegacion = u.id_delegacion 
where u.umae <> true and (u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)
group by u.id_delegacion
) as t on del.id_delegacion = t.id_delegacion
--order by nombre
;

------------------------------------------------------------------------------------
--OOAD
select count(distinct d.id_docente), del.clave_delegacional, del.nombre, u.grupo_tipo_unidad 
from censo.censo c
inner join censo.docente d on d.id_docente = c.id_docente 
inner join censo.historico_datos_docente hd on hd.id_docente = d.id_docente 
inner join catalogo.departamentos_instituto di on di.id_departamento_instituto = hd.id_departamento_instituto 
inner join catalogo.unidades_instituto u on u.clave_unidad = di.clave_unidad 
inner join catalogo.delegaciones del on del.id_delegacion = u.id_delegacion 
where u.umae <> true and (u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)
group by del.clave_delegacional, del.nombre, u.grupo_tipo_unidad
order by nombre
;


--UMAE
select count(distinct d.id_docente), u.clave_unidad, u.nombre, u.unidad_principal, u.nombre_unidad_principal
from censo.censo c
inner join censo.docente d on d.id_docente = c.id_docente 
inner join censo.historico_datos_docente hd on hd.id_docente = d.id_docente 
inner join catalogo.departamentos_instituto di on di.id_departamento_instituto = hd.id_departamento_instituto 
inner join catalogo.unidades_instituto u on u.clave_unidad = di.clave_unidad 
inner join catalogo.delegaciones del on del.id_delegacion = u.id_delegacion 
where u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE')
group by u.clave_unidad, u.nombre, u.unidad_principal, u.nombre_unidad_principal
;


select * from censo.docente d
inner join censo.historico_datos_docente hd on hd.id_docente = d.id_docente 
inner join catalogo.departamentos_instituto di on di.id_departamento_instituto = hd.id_departamento_instituto 
inner join catalogo.unidades_instituto u on u.clave_unidad = di.clave_unidad 
--where u.nombre_unidad_principal ='Hidalgo'
;
*/