<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Docente_model extends MY_Model {

    public function __construct() {
        // Call the padre constructor
        parent::__construct();
    }

    public function get_datos_generales($id_docente = null, $OOAD = null, $parametros_docente = []) {
        $usuario = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = "hdd.*,ec.*,dc.*, doc.*, dc.id_docente_carrera, censo.estado_validacion_docente(doc.id_docente) id_status_validacion";        
        if(!is_null($id_docente)){                
            $this->db->where('doc.id_docente', $id_docente);
        }        
        $this->db->join('censo.historico_datos_docente hdd', 'hdd.id_docente = doc.id_docente and hdd.actual = 1', 'left');
        $this->db->join('catalogo.estado_civil ec', 'ec.id_estado_civil = doc.id_estado_civil', 'left');
        $this->db->join('censo.docente_carrera  dc', 'dc.id_docente_carrera = doc.id_docente_carrera', 'left');
        //***************************************************************** */
        if(!empty($parametros_docente)){
            $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = hdd.id_departamento_instituto');
            $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad and u.anio = (select max(un.anio) from catalogo.unidades_instituto un )');
            $this->db->join('catalogo.delegaciones del', 'del.id_delegacion = u.id_delegacion');
            
            $this->db->join('sistema.usuario_rol  urol', 'urol.id_usuario = doc.id_usuario and urol.activo');
            $this->db->join('sistema.roles rol', 'rol.clave_rol = urol.clave_rol and urol.clave_rol = \''.LNiveles_acceso::Docente.'\'');     
            //pr($parametros_docente);       
            if($parametros_docente['is_entidad_designada']){

                $this->db->join('sistema.usuario_ooad ooad', 'ooad.ooad = del.clave_delegacional', 'left');            
                $this->db->join('sistema.usuario_umae umae', 'umae.umae = u.clave_unidad', 'left'); 
                $filtro_umae_ooad = array();           
                if(isset($parametros_docente['user_validadorn1'])){
                    
                    $filtro_umae_ooad[] = 'doc.id_usuario IN(' . $parametros_docente['user_validadorn1'] . ')';
                }
                if(isset($parametros_docente['ooad_usuario'])){
                    $filtro_umae_ooad[] = 'del.clave_delegacional in(' . $parametros_docente['ooad'].')'; 
                    //$this->db->where('d.clave_delegacional', $parametros_docente['ooad_usuario']);//Rol del docente
                }
                if(isset($parametros_docente['umae_usuario'])){
                    $filtro_umae_ooad[] = 'u.clave_unidad in(' . $parametros_docente['umae'].')'; 
                    //$this->db->where_in('u.clave_unidad' ,$parametros_docente['umae_usuario']);//Rol del docente                    
                }
                if(!empty($filtro_umae_ooad)){
                    $string = implode(' or ',$filtro_umae_ooad);
                    $string = '('. $string . ')';
                    $this->db->where($string, null);//Rol del docente                    
                    
                }
                if($parametros_docente['aplica_bandera_separarV1_v2'] == 1){
                    $select_aux = [];
                    if(isset($parametros_docente['user_validadorn1'])){
                        $this->db->join('sistema.control_registro_usuarios cru', 'cru.id_usuario_registrado = doc.id_usuario', 'left');
                        $select .= ",(case when cru.id_usuario_registrado is null then 0 else 1 end) permite_validacion";                         
                        $select .= ",(case when ((ooad.ooad is not null and ooad.ooad = del.clave_delegacional) or (umae.umae is not null and umae.umae = u.clave_unidad)) then 1 else 0 end) permite_ratificacion";                         
                        
                        
                    }

                }
            }
            if(isset($parametros_docente['rol_docente'])){
                if(is_array($parametros_docente['rol_docente'])){
                    $this->db->where_in('rol.clave_rol' ,$parametros_docente['rol_docente']);//Rol del docente
                } else {
                    $this->db->where('rol.clave_rol' ,$parametros_docente['rol_docente']);//Rol del docente
                }
            }
            if (isset($parametros_docente['filtros']) && !is_null($parametros_docente['filtros'])) {
                foreach ($parametros_docente['filtros'] as $key => $value) {
                    foreach($value as $keyApp => $valApp){
                        $this->db->{$key}($keyApp, $valApp);
                    }
                }
            }            
        }
        //************************************************** */


        $this->db->select($select);
        $resultado = $this->db->get('censo.docente doc')->result_array();
        if ($resultado) {
            $usuario = $resultado[0];
        }
        //pr($this->db->last_query());
        //pr($usuario==null?'1':'0');
        return $usuario;
    }

    /**
     * @author LEAS
     * @fecha 11/05/2017
     * @param type $id_docente
     * @return type
     */
    public function get_historico_datos_generales($id_docente = null, $OOAD = null , $parametros_docente =[]) {
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
        if(!empty($parametros_docente)){
            $convocatoria = $parametros_docente['convocatoria'];
            $censo_reg = "(select count(*) total_registros_censo from censo.censo cc where cc.id_docente = doc.id_docente) total_registros_censo";
            $ratificado = "(select ratificado from validacion.ratificador rat where rat.id_docente = doc.id_docente and rat.id_convocatoria = ".$convocatoria.") ratificado";
            $select_p = ["doc.id_docente", "doc.matricula", "doc.curp", "doc.email", "concat(doc.nombre, ' ', doc.apellido_p, ' ', doc.apellido_m) nombre_docente", "doc.telefono", 
            "doc.telefono_laboral", "doc.telefono_particular", "doc.id_docente_carrera", "dca.descripcion fase_carrera", "rol.clave_rol", "rol.nombre",
            "case when u.umae = true or u.grupo_tipo_unidad in ('UMAE','CUMAE') then u.nombre_unidad_principal else null end umae",
            "(select count(*) from sistema.control_registro_usuarios cru where cru.id_usuario_registra = doc.id_usuario) total, doc.id_usuario",
            "censo.estado_validacion_docente(doc.id_docente) id_status_validacion",
            $censo_reg,
            $ratificado
            ];
            $select = array_merge($select, $select_p);
        }
        if(!is_null($id_docente)){
            $this->db->where('dd.id_docente', $id_docente);
        }
        $this->db->where('dd.actual', 1);
        if(!is_null($OOAD)){
            $this->db->where('d.clave_delegacional', $OOAD);
            //$this->db->where('d.id_delegacion', $OOAD);
        }
        
        $this->db->join('catalogo.departamentos_instituto di', 'di.id_departamento_instituto = dd.id_departamento_instituto');
        $this->db->join('catalogo.categorias cc', 'cc.id_categoria = dd.id_categoria', 'left');
        $this->db->join('catalogo.unidades_instituto u', 'u.clave_unidad = di.clave_unidad and u.anio = (select max(un.anio) from catalogo.unidades_instituto un )');
        $this->db->join('catalogo.delegaciones d', 'd.id_delegacion = u.id_delegacion');
        $this->db->join('catalogo.tipos_unidades tu', 'tu.id_tipo_unidad = u.id_tipo_unidad', 'left');
        $this->db->join('catalogo.regiones r', 'r.id_region = d.id_region', 'left');
        $this->db->join('catalogo.tipo_contratacion tc', 'tc.cve_tipo_contratacion = dd.cve_tipo_contratacion', 'left');
        if(!empty($parametros_docente)){
            $this->db->join('censo.docente doc', 'doc.id_docente = dd.id_docente', 'left');
            $this->db->join('censo.docente_carrera  dca', 'dca.id_docente_carrera = doc.id_docente_carrera', 'left');
            
            $this->db->join('sistema.usuario_rol  urol', 'urol.id_usuario = doc.id_usuario and urol.activo');
            $this->db->join('sistema.roles rol', 'rol.clave_rol = urol.clave_rol');     
            //pr($parametros_docente);       
            if($parametros_docente['is_entidad_designada']){

                $this->db->join('sistema.usuario_ooad ooad', 'ooad.ooad = d.clave_delegacional', 'left');            
                $this->db->join('sistema.usuario_umae umae', 'umae.umae = u.clave_unidad', 'left'); 
                $filtro_umae_ooad = array();           
                if(isset($parametros_docente['user_validadorn1'])){
                    $filtro_umae_ooad[] = 'doc.id_usuario IN(' . $parametros_docente['user_validadorn1'] . ')';
                }
                if(isset($parametros_docente['ooad_usuario'])){
                    $filtro_umae_ooad[] = 'd.clave_delegacional in(' . $parametros_docente['ooad'].')'; 
                    //$this->db->where('d.clave_delegacional', $parametros_docente['ooad_usuario']);//Rol del docente
                }
                if(isset($parametros_docente['umae_usuario'])){
                    $filtro_umae_ooad[] = 'u.clave_unidad in(' . $parametros_docente['umae'].')'; 
                    //$this->db->where_in('u.clave_unidad' ,$parametros_docente['umae_usuario']);//Rol del docente                    
                }
                if(!empty($filtro_umae_ooad)){
                    $string = implode(' or ',$filtro_umae_ooad);
                    $string = '('. $string . ')';
                    $this->db->where($string, null);//Rol del docente                    
                    
                }
                if($parametros_docente['aplica_bandera_separarV1_v2'] == 1){
                    $select_aux = [];
                    if(isset($parametros_docente['user_validadorn1'])){
                        $this->db->join('sistema.control_registro_usuarios cru', 'cru.id_usuario_registrado = doc.id_usuario', 'left');
                        $select_aux[] = "(case when cru.id_usuario_registrado is null then 0 else 1 end) permite_validacion";                         
                        $select_aux[] = "(case when ((ooad.ooad is not null and ooad.ooad = d.clave_delegacional) or (umae.umae is not null and umae.umae = u.clave_unidad)) then 1 else 0 end) permite_ratificacion";                         
                        $select = array_merge($select, $select_aux);
                        
                    }

                }
            }
            
            if(isset($parametros_docente['rol_docente'])){
                if(is_array($parametros_docente['rol_docente'])){
                    $this->db->where_in('rol.clave_rol' ,$parametros_docente['rol_docente']);//Rol del docente
                } else {
                    $this->db->where('rol.clave_rol' ,$parametros_docente['rol_docente']);//Rol del docente
                }
            }
            if (isset($parametros_docente['filtros']) && !is_null($parametros_docente['filtros'])) {
                foreach ($parametros_docente['filtros'] as $key => $value) {
                    foreach($value as $keyApp => $valApp){
                        $this->db->{$key}($keyApp, $valApp);
                    }
                }
            }            
        }
        $this->db->select($select);
        $result = $this->db->get('censo.historico_datos_docente dd');
        $array_result = $result->result_array();
        //pr($this->db->last_query()); exit();
        if (!empty($array_result) && empty($parametros_docente)) {
            return $array_result[0]; //retorna un array con los datos del historico o del docente
        }
        return $array_result;
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
            //'id_estado_civil' => $datos_docente['estado_civil'],
            'email' => $datos_docente['email'],
            'id_docente_carrera' => $datos_docente['fase_carrera_docente'],
            'email_personal' => $datos_docente['email_personal'],
            'ext_tel_laboral' => (strlen($datos_docente['ext_tel_laboral'])> 0)? $datos_docente['ext_tel_laboral'] : null
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
