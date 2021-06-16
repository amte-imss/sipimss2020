<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_model extends MY_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * Devuelve la información de los registros de la tabla catalogos
     * @author CPMS
     * @date 21/07/2017
     * @return array
     */
    public function docentes_reporte_general_censo($param) {
       $parametros = $param;
       $output = [];

       $output['docentes_reporte'] = $this->get_docentes_reporte($parametros);
       //$ids_docente = $this->get_docentes_ids($output['docentes_reporte']);
       /*if(count($output['docentes_reporte'])>0){
           $output['total_registros_censo_docente'] = $this->get_total_registros_censo_docente(null);
       }*/
       //pr($ids_docente);
       /*if(!is_null($ids_docente)){
            //$output['total_registros_censo_docente'] = [];
       }else{
            $output['total_registros_censo_docente'] = [];
       }*/
       //pr($output['total_registros_censo_docente']); 
       //$totales = $this->get_total_censo_docente(); 
       return $output;
        
    }

    /**
     *
     * @author LEAS
     * @fecha 22/02/2021
     * @return type docentes 
     */
    public function get_docentes_ids($datos) {
        $total_datos = count($datos);
        $coleccion = [];
        if($total_datos>0){
            for ($i=0; $i < $total_datos; $i++) { 
                $coleccion[] = $datos[$i]['id_docente'];
            }
        }else{
            $coleccion = null;
        }
        return $coleccion;
    }

    /**
     *
     * @author LEAS
     * @fecha 22/02/2021
     * @return type docentes 
     */
    public function get_docentes_reporte($filtros = []) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            //"(case when cru.id_usuario_registrado is null then 0 else 1 end) permite_validacion",
            //"dd.id_historico_docente", 
            "dd.fecha fecha_ultima_actualizacion", "dd.id_departamento_instituto", "di.clave_departamental", "concat(di.nombre,' (',di.clave_departamental,')' ) departamento",
            "u.id_unidad_instituto", "u.clave_unidad", "u.nombre nom_unidad", "u.nivel_atencion", "u.id_tipo_unidad", "tu.nombre nom_tipo_unidad", 
            //"d.id_delegacion", 
            "d.clave_delegacional",
            "concat(d.nombre,' (',d.clave_delegacional,')' ) delegacion",
            //"tc.cve_tipo_contratacion", "tc.tipo_contratacion", "r.id_region", "r.nombre region", "cc.id_categoria", 
            "cc.clave_categoria",
            "concat(cc.nombre, ' (', cc.clave_categoria, ')') categoria", "doc.id_docente", "doc.matricula", "doc.curp", "doc.email", "concat(doc.nombre, ' ', doc.apellido_p, ' ', doc.apellido_m) nombre_docente",
            //"doc.telefono", 
            "doc.telefono_laboral", "doc.telefono_particular", //"doc.id_docente_carrera", 
            "dca.descripcion fase_carrera", //"rol.clave_rol", "rol.nombre",
            "case when u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE') then u.nombre_unidad_principal else null end umae",
            //"(select count(*) from sistema.control_registro_usuarios cru where cru.id_usuario_registra = doc.id_usuario) total", 
            //"doc.id_usuario", 
            //"(case when trc.id_status_validacion is null then (select censo.estado_validacion_docente_complemento(doc.id_docente)) else trc.id_status_validacion end ) id_status_validacion",
            "censo.estado_validacion_docente(doc.id_docente) id_status_validacion",
            "'' status_validacion",
            "(case when trc.total_registros_censo is null then 0 else trc.total_registros_censo end) total_registros_censo",
            //"(select ratificado from validacion.ratificador rat where rat.id_docente = doc.id_docente and rat.id_convocatoria = 3) ratificado", 
            //"cru.id_usuario_registra", "doc1.matricula matricula_p", "doc1.nombre nombre_p", "doc1.apellido_p apellido_p_p", "doc1.apellido_m apellido_m_p", 
            //informacion de censo por rubro o actividad 
            /*"0 experiencia_docente_previa", "0 experiencia_actual","0 formacion_educacion","0 formacion_investigacion_temas_educacion",
            "0 formacion_investigacion_otros_campos", "0 publicacion",
            "0 material_recurso", "0 posgrado", "0 ciefd", "0 enfermeria_tecnicos", "0 educacion_continua", 
            "0 pregrado", "0 educacion_distancia", "0 diplomado_educacion", "0 especialidad_educacion", "0 maestria_educacion", "0 doctorado_educacion"*/
            "t1.*"

        );
        
        $this->db->select($select);
        
        //Join
        $this->db->join('censo.docente doc', 'doc.id_docente = dd.id_docente', 'inner');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = dd.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.categorias cc', 'cc.id_categoria = dd.id_categoria', 'left');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad and u.anio = (select max(un.anio) from catalogo.unidades_instituto un )', 'inner');
        $this->db->join('catalogo.delegaciones d', 'd.id_delegacion = u.id_delegacion', 'inner');
        $this->db->join('catalogo.tipos_unidades tu', 'tu.id_tipo_unidad = u.id_tipo_unidad', 'left');
        $this->db->join('catalogo.regiones r', 'r.id_region = d.id_region', 'left');
        $this->db->join('catalogo.tipo_contratacion tc', 'tc.cve_tipo_contratacion = dd.cve_tipo_contratacion', 'left');
        $this->db->join('censo.docente_carrera dca', 'dca.id_docente_carrera = doc.id_docente_carrera', 'left');
        $this->db->join('sistema.usuario_rol urol', 'urol.id_usuario = doc.id_usuario and urol.activo', 'inner');
        $this->db->join('sistema.roles rol', 'rol.clave_rol = urol.clave_rol', 'inner');
        //$this->db->join('sistema.control_registro_usuarios cru', 'cru.id_usuario_registrado = doc.id_usuario', 'left');
        //$this->db->join('censo.docente doc1', 'doc1.id_usuario = cru.id_usuario_registra', 'left');
        //$this->db->join('(select cc.id_docente, count(cc.id_censo) total_registros_censo, censo.estado_validacion_docente(cc.id_docente) id_status_validacion from censo.censo cc GROUP BY cc.id_docente) trc', 'trc.id_docente = doc.id_docente', 'left');
        $this->db->join('(select cc.id_docente, count(cc.id_censo) total_registros_censo from censo.censo cc GROUP BY cc.id_docente) trc', 'trc.id_docente = doc.id_docente', 'left');
        $this->db->join('total_registros_censo_docente as t1', 't1.id_docente = doc.id_docente ', 'left');
        
        $this->db->where('dd.actual', 1);
        //$this->db->where('doc.matricula', '11666706');
        $this->db->where('rol.clave_rol', $filtros['rol_docente']);
        
        if($filtros['is_entidad_designada']){
            if(isset($filtros['ooad']) && !empty($filtros['ooad']) && isset($filtros['umae']) && !empty($filtros['umae'])){
                $this->db->where("(u.unidad_principal in (select upa.unidad_principal from sistema.usuario_umae usuuma join catalogo.unidades_instituto upa on usuuma.umae = upa.clave_unidad_principal where  id_usuario =  ". $filtros['umae_usuario']. " )
                or (d.clave_delegacional in ( select ooad from sistema.usuario_ooad where id_usuario = " . $filtros['ooad_usuario'] ." ) and (u.umae <> true and u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)) )", null);
            }else if(isset($filtros['umae']) && !empty($filtros['umae'])){                
                $this->db->where('u.unidad_principal in (select upa.unidad_principal from sistema.usuario_umae usuuma join catalogo.unidades_instituto upa on usuuma.umae = upa.clave_unidad_principal where  id_usuario =  '. $filtros['umae_usuario']. ' )', null);
            }else if(isset($filtros['ooad']) && !empty($filtros['ooad'])){                            
                $this->db->where("d.clave_delegacional in (select ooad from sistema.usuario_ooad where id_usuario = ". $filtros['ooad_usuario'] . ") and (u.umae <> true and u.grupo_tipo_unidad not in ('UMAE','CUMAE') or u.grupo_tipo_unidad is null)", null);                
            }else{
                return [];//Retorna vacio el modulo
            }

        }

        if (isset($filtros['filtros']) && !is_null($filtros['filtros']) && !empty($filtros['filtros'])) {
            foreach ($filtros as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $resultado = $this->db->get('censo.historico_datos_docente dd');
        
        //pr($this->db->last_query()); exit;
        return $resultado->result_array();
    }


       /**
     *
     * @author LEAS
     * @fecha 18/05/2017
     * @return type catálogo de las delegaciones
     */
    public function get_total_registros_censo_docente($id_docentes) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array("id_docente",
            "experiencia_docente_previa", "experiencia_actual","formacion_educacion","formacion_investigacion_temas_educacion",
            "formacion_investigacion_otros_campos", "publicacion",
            "material_recurso", "posgrado", "ciefd", "enfermeria_tecnicos", "educacion_continua", 
            "pregrado", "educacion_distancia", "diplomado_educacion", "especialidad_educacion", "maestria_educacion", "doctorado_educacion"
        );
        $where = "(select dd.id_docente
        FROM censo.historico_datos_docente dd
        JOIN censo.docente doc ON doc.id_docente = dd.id_docente
        JOIN catalogo.departamentos_instituto di ON di.id_departamento_instituto = dd.id_departamento_instituto
        JOIN catalogo.unidades_instituto u ON u.clave_unidad = di.clave_unidad and u.anio = (select max(un.anio) from catalogo.unidades_instituto un )
        JOIN catalogo.delegaciones d ON d.id_delegacion = u.id_delegacion
        JOIN sistema.usuario_rol urol ON urol.id_usuario = doc.id_usuario and urol.activo
        JOIN sistema.roles rol ON rol.clave_rol = urol.clave_rol        
        WHERE dd.actual = 1
        AND rol.clave_rol = 'DOCENTE' ";
        $where .= ")";
        $this->db->select($select);
        $this->db->where_in('id_docente', $where, false);

        $resultado = $this->db->get('censo.total_registros_censo_docente');
            //pr($this->db->last_query());
        return $resultado->result_array();
    }

    /*
     * @author LEAS
     * @fecha 18/05/2021
     * @return type catálogo de las delegaciones
     */
    public function reporte_formacion_docente() {
        $this->db->select(array("dd.id_departamento_instituto", "di.clave_departamental", 
            "concat(di.nombre,' (',di.clave_departamental,')' ) departamento", "u.id_unidad_instituto", "u.clave_unidad", 
            "u.nombre nom_unidad", "u.nivel_atencion", "u.id_tipo_unidad",  
            "d.clave_delegacional", "concat(d.nombre,' (',d.clave_delegacional,')' ) delegacion", "cc.clave_categoria", 
            "concat(cc.nombre, ' (', cc.clave_categoria, ')') categoria", "doc.id_docente", "doc.matricula", "doc.curp", 
            "doc.email", "concat(doc.nombre, ' ', doc.apellido_p, ' ', doc.apellido_m) nombre_docente", "doc.telefono_laboral", "doc.telefono_particular", 
            "case when u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE') then u.nombre_unidad_principal else null end umae", 
            "censo.estado_validacion_docente(doc.id_docente) id_status_validacion", "t1.*"
            ));
        
        $this->db->join('censo.docente doc', 'doc.id_docente = dd.id_docente', 'inner');
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = dd.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.categorias cc', 'cc.id_categoria = dd.id_categoria', 'left');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad and u.anio = (select max(un.anio) from catalogo.unidades_instituto un )', 'inner');
        $this->db->join('catalogo.delegaciones d', 'd.id_delegacion = u.id_delegacion', 'inner');
        $this->db->join('sistema.usuario_rol urol', 'urol.id_usuario = doc.id_usuario and urol.activo', 'inner');
        $this->db->join('sistema.roles rol', 'rol.clave_rol = urol.clave_rol', 'inner');
        $this->db->join('censo.total_registros_censo_docente as t1', 't1.id_docente = doc.id_docente', 'left');

        $this->db->where("dd.actual = 1 AND rol.clave_rol = 'DOCENTE'");
        //$this->db->limit(150);
        
        $resultado = $this->db->get('censo.historico_datos_docente dd');
        //pr($this->db->last_query());
        return $resultado->result_array();
    }
}
