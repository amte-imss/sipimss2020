<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends MY_Model {

    const NO_SIAP = 'no_siap', SIAP = 'siap', NO_IMSS = 'no_imss';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function nuevo(&$parametros = null, $tipo = Usuario_model::SIAP) {
        $salida['msg'] = 'Error';
        $salida['result'] = false;
        //pr("Aqui");
        switch($tipo){
            case Usuario_model::SIAP:
                $this->nuevo_siap($parametros, $salida);
                break;
            case Usuario_model::NO_SIAP:
                $this->nuevo_no_siap($parametros, $salida);
                break;
            case Usuario_model::NO_IMSS:
                $this->nuevo_no_imss($parametros, $salida);
                break;
        }
        return $salida;
    }

    private function nuevo_no_imss(&$parametros, &$salida)
    {
        $token = $this->seguridad->folio_random(10, TRUE);
        $pass = $this->seguridad->encrypt_sha512($token . $parametros['password'] . $token);
        $params['where'] = array(
            'username' => $parametros['matricula'],
        );
        $params['informacion_docente'] = false;
        $usuario_db = count($this->get_usuarios($params)) == 0;
        if($usuario_db)
        {
            $data['usuario'] = array(
                'password' => $pass,
                'token' => $token,
                'username' => $parametros['matricula'],
                'email' => $parametros['email']
            );
            $salida = $this->insert_guardar($data, $parametros['grupo']);
            if ($salida['result'] && isset($parametros['registro_usuario']))
            {
                $this->load->model('Plantilla_model', 'plantilla');
                //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
            }
        } else if (!$usuario_db)
        {
            $salida['msg'] = 'Usuario ya registrado';
        }
    }

    private function nuevo_no_siap(&$parametros, &$salida)
    {
        $token = $this->seguridad->folio_random(10, TRUE);
        $pass = $this->seguridad->encrypt_sha512($token . $parametros['password'] . $token);
        $params['where'] = array(
            'username' => $parametros['matricula']
        );
        $usuario_db = count($this->get_usuarios($params)) == 0;
        if($usuario_db)
        {
            $unidad_instituto = $this->get_unidad($parametros['clave_departamental']);
            $categoria = $this->get_categoria($parametros['categoria']);
            if ($unidad_instituto != null)
            {
                $data['usuario'] = array(
                    'password' => $pass,
                    'token' => $token,
                    'username' => $parametros['matricula'],
                    'email' => $parametros['email']
                );
                $data['docente'] = array(
                    'email' => $parametros['email'],
                    'matricula' => $parametros['matricula'],
                    'nombre' => $parametros['nombre'],
                    'apellido_p' => $parametros['paterno'],
                    'apellido_m' => $parametros['materno'],
                    'curp' => $parametros ['curp'],
                    'sexo' => $parametros['sexo'],
                    'rfc' => $parametros['rfc'],
                    'status_siap' => 1
                );
                $data['historico'] = array(
                    'actual' => 1,
                    'id_categoria' => $categoria['id_categoria'],
                    'id_departamento_instituto' => $unidad_instituto['id_departamento_instituto']
                );
                //pr($data);
                $salida = $this->insert_guardar($data, $parametros['grupo']);
                if ($salida['result'] && isset($parametros['registro_usuario']))
                {
                    $this->load->model('Plantilla_model', 'plantilla');
                    //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
                }
                $salida['siap'] = $data;
            } else
            {
                $salida['msg'] = 'Adcripción no localizada en la base de datos';
            }
        } else if (!$usuario_db)
        {
            $salida['msg'] = 'Usuario ya registrado';
        }
    }

    private function siap_prueba(){

    }

    private function nuevo_siap(&$parametros, &$salida)
    {
        // pr($parametros);
        $token = $this->seguridad->folio_random(10, TRUE);
        $pass = $this->seguridad->encrypt_sha512($token . $parametros['password'] . $token);
        $usuario = $this->empleados_siap->buscar_usuario_siap($parametros['delegacion'], $parametros['matricula'])['empleado'];
        /***Borrar al final ******************************/
        
        /*$usuario['adscripcion'][0] = '09NC012500';
        $usuario['emp_keypue'][0] = 'Category';
        $usuario['nombre'][0] = 'Pablito';
        $usuario['paterno'][0] = 'Morales';
        $usuario['materno'][0] = 'Martinez';
        $usuario['curp'] = 'CURPTEST';
        $usuario['sexo'] = 1; 
        $usuario['rfc'][0] = 'RFCTEST';*/
        /*** ************************ ********************/
        //pr('$usuario');
        //pr($usuario);
        $params['where'] = array(
            'username' => $parametros['matricula']
        );
        //Si existe un alias 
        if(isset($parametros['username_alias']) && !is_null($parametros['username_alias'])){
            $params['or_where'] = array(
                'username_alias' => $parametros['username_alias']
            );
        }
        $getUsuarios = $this->get_usuarios($params);
        
        //pr($getUsuarios);
        //pr($parametros);
        $usuario_db = count($getUsuarios) == 0;
        $is_diff_username_alias = true;
        if(isset($parametros['username_alias']) && !is_null($parametros['username_alias'])){
            if(!$usuario_db){
               $is_diff_username_alias = (trim($getUsuarios[0]['username_alias']) != trim($parametros['username_alias']));
            }
        }
        // pr($parametros);
        // pr($this->db->last_query());
        if ($usuario && $usuario_db && $is_diff_username_alias)
        {
            $unidad_instituto = $this->get_unidad($usuario['adscripcion'][0]);
            $categoria = $this->get_categoria($usuario['emp_keypue'][0]);
            if ($unidad_instituto == null)
            {
                $unidad_instituto = $this->localiza_unidad($usuario['adscripcion'][0]);
            }
            if ($unidad_instituto != null)
            {
                $data['usuario'] = array(
                    'password' => $pass,
                    'token' => $token,
                    'username' => $parametros['matricula'],                    
                    'email' => $parametros['email']
                );
                if(isset($parametros['username_alias'])){
                    $data['usuario']['username_alias'] = $parametros['username_alias'];
                }
                $data['docente'] = array(
                    'email' => $parametros['email'],
                    'matricula' => $parametros['matricula'],
                    'nombre' => $usuario['nombre'][0],
                    'apellido_p' => $usuario['paterno'][0],
                    'apellido_m' => $usuario['materno'][0],
                    'curp' => $usuario['curp'],
                    'sexo' => $usuario['sexo'],
                    'rfc' => $usuario['rfc'][0],
                    'status_siap' => 1
                );
                $data['historico'] = array(
                    'actual' => 1,
                    'id_categoria' => $categoria['id_categoria'],
                    'id_departamento_instituto' => $unidad_instituto['id_departamento_instituto']
                );
                //pr($data); exit();
                $salida = $this->insert_guardar($data, $parametros['grupo']);
                if ($salida['result'] && isset($parametros['registro_usuario']))
                {
                    $salida['envia_correo'] = true;
                    //$this->load->model('Plantilla_model', 'plantilla');
                    //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
                    //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
                }
                $salida['siap'] = $data;
            } else
            {
                $salida['msg'] = 'Adcripción no localizada en la base de datos';
            }
        } else if (!$is_diff_username_alias){
            $salida['msg'] = 'Nombre de usuario alias no disponible';

        } else if (!$usuario_db)
        {
            $salida['msg'] = 'Usuario ya registrado';
        } else if (!$usuario)
        {
            $salida['msg'] = 'Usuario no registrado en SIAP';
        }
    }

    private function get_unidad($clave) {
        $unidad = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('clave_departamental', $clave);
        $query = $this->db->get('catalogo.departamentos_instituto');
        $resultado = $query->result_array();
        if ($resultado) {
            $unidad = $resultado[0];
        }
        $query->free_result();
        return $unidad;
    }

    private function get_categoria($clave) {
        $categoria = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('clave_categoria', $clave);
        $query = $this->db->get('catalogo.categorias');
        $resultado = $query->result_array();
        if ($resultado) {
            $categoria = $resultado[0];
        }
        $query->free_result();
        return $categoria;
    }

    private function get_departamento($clave) {
        $categoria = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('clave_departamental', $clave);
        $query = $this->db->get('catalogo.departamentos_instituto');
        $resultado = $query->result_array();
        if ($resultado) {
            $categoria = $resultado[0];
        }
        $query->free_result();
        return $categoria;
    }

    private function insert_guardar(&$datos, $id_grupo) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->insert('sistema.usuarios', $datos['usuario']); //nombre de la tabla en donde se insertaran

        $id_usuario = $this->db->insert_id();
        $docente = $this->get_docente($datos['usuario']['username']);
        // pr($docente);

        if(!is_null($docente))
        {
            if(isset($datos['docente']))
            {
                $datos['docente']['id_usuario'] = $id_usuario;
                $datos['usuario']['id_docente'] = $docente['id_docente'];
                $this->db->where('id_docente', $docente['id_docente']);
                $this->db->update('censo.docente', $datos['docente']);
                $this->db->reset_query();
            }
        }else if(isset($datos['docente']))
        {
            $datos['docente']['id_usuario'] = $id_usuario;
            $this->db->insert('censo.docente', $datos['docente']);
            $datos['usuario']['id_docente'] = $this->db->insert_id();
        }

        $data = array(
            'clave_rol' => $id_grupo,
            'id_usuario' => $id_usuario
        );
        $this->db->insert('sistema.usuario_rol', $data);
        if(isset($datos['historico']))
        {
            $this->db->reset_query();
            $this->db->where('id_docente', $datos['usuario']['id_docente']);
            $this->db->set('actual', 0);
            $this->db->update('censo.historico_datos_docente');
            $this->db->reset_query();
            $datos['historico']['id_docente'] = $datos['usuario']['id_docente'];
            $this->db->insert('censo.historico_datos_docente', $datos['historico']);
            //pr($this->db->last_query());
            //pr($datos);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
            $resultado['id_usuario'] = null;
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = 'Usuario almacenado con éxito';
            $resultado['result'] = TRUE;
            $resultado['id_usuario'] = $id_usuario;
        }
        return $resultado;
    }

    public function save_control_registro_usuarios($data, $tipo = 'insert'){
        $result = ['mensaje'=>'', 'tp_msg'=>'danger'];
        $this->db->flush_cache();
        $this->db->reset_query();
        if($tipo == 'insert'){
            $this->db->insert('sistema.control_registro_usuarios', $data); //nombre de la tabla en donde se insertaran
        }else{//update
            //pr($data);
            $this->db->trans_begin(); //Definir inicio de transacción
            $is_error = false;
            $error_guardar_limbo = false;
            if(isset($data['condicion'])){
                $this->db->where('id_usuario_registra', $data['condicion']['id_usuario_registra']); //nombre de la tabla en donde se insertaran
                $this->db->where_in('id_usuario_registrado', $data['condicion']['id_usuario_registrado']); //nombre de la tabla en donde se insertaran            
                $this->db->update('sistema.control_registro_usuarios', $data['datos']); //nombre de la tabla en donde se insertaran
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['mensaje'] = 'Error al guardar. Por favor intente más tarde';
                    $result['tp_msg'] = 'danger';
                    $is_error = true;
                }

            }
            if(isset($data['limbo'])){
                $datos['id_usuario_registra'] =$data['limbo']['id_usuario_registra'];
                $error_guardar_limbo = false;
                foreach($data['limbo']['id_usuario_registrado'] as $key => $value){
                    $datos['id_usuario_registrado'] = $value;
                    
                    $this->db->insert('sistema.control_registro_usuarios', $datos); //nombre de la tabla en donde se insertaran
                    if ($this->db->trans_status() === FALSE) {
                        $error_guardar_limbo = true;
                        break;
                    }
                } 
            }

            if(!$is_error && !$error_guardar_limbo){//
                $this->db->trans_commit();                    
                $result['mensaje'] = 'La información se guardo correctamente';
                $result['tp_msg'] = 'success';
            }else{
                $this->db->trans_rollback();
                $result['mensaje'] = 'Error al guardar. Por favor intente más tarde';
                $result['tp_msg'] = 'danger';
            }
            
            
        }
        return $result;
    }
    

    private function get_docente($matricula = '')
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('id_docente');
        $this->db->where('matricula', $matricula);
        $this->db->where('id_usuario is null');
        $docente = $this->db->get('censo.docente')->result_array();
        if(!empty($docente))
        {
            $docente = $docente[0];
        }else{
            $docente = null;
        }
        return $docente;
    }
    public function get_docente_por_user($id_usuario)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('id_docente');
        $this->db->where('id_usuario', $id_usuario);        
        $docente = $this->db->get('censo.docente')->result_array();
        if(!empty($docente))
        {
            $docente = $docente[0];
        }else{
            $docente = null;
        }
        return $docente;
    }

    private function localiza_unidad($clave) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $unidad = null;
        if (strlen($clave) > 7) {
            $busqueda = substr($clave, 0, 7);
            $this->db->like('clave_unidad', $clave, 'after');
            $query = $this->db->get('catalogo.unidades_instituto');
            $resultado  = $query->result_array();
            if ($resultado) {
                $unidad = $resultado[0];
            }
            $query->free_result();
        }
        return $unidad;
    }

    public function get_usuarios($params = []) {
        if(!isset($params['informacion_docente']))
        {
            $params['informacion_docente'] = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        $usuarios = [];
        if (isset($params['total'])) {
            $select = 'count(*) total';
        } else {
            if (isset($params['select'])) {
                $select = $params['select'];
            } else if($params['informacion_docente']){
                $select = array(
                    'usuarios.id_usuario', 'docentes.id_docente', 'coalesce(docentes.matricula, usuarios.username) matricula', 'usuarios.email'
                    , 'concat("docentes".nombre, $$ $$, "docentes".apellido_p, $$ $$, "docentes".apellido_m) nombre_completo', 'username_alias'
                );
            }else{
                $select = array(
                    'usuarios.id_usuario',  'usuarios.username', 'usuarios.username_alias'
                );
            }
        }
        $this->db->select($select);
        $this->db->from('sistema.usuarios usuarios');
        if($params['informacion_docente'])
        {
            $this->db->join('censo.docente docentes', 'docentes.id_usuario = usuarios.id_usuario', 'left');
            $this->db->join('censo.historico_datos_docente C', 'C.id_docente = docentes.id_docente and C.actual = 1', 'left');
            $this->db->join('catalogo.departamentos_instituto D', 'D.id_departamento_instituto = C.id_departamento_instituto', 'left');
            $this->db->join('catalogo.unidades_instituto E', 'E.clave_unidad = D.clave_unidad and E.anio = date_part($$year$$, CURRENT_DATE)', 'left');
            $this->db->join('catalogo.categorias F', ' F.id_categoria = C.id_categoria', 'left');
            $this->db->join('catalogo.delegaciones G', 'G.id_delegacion = E.id_delegacion', 'left');
        }

        if (isset($params['where']))
        {
//            pr($params['where']);
            foreach ($params['where'] as $key => $value)
            {
                $this->db->where($key, $value);
            }
        }

        if (isset($params['or_where']))
        {
//            pr($params['where']);
            foreach ($params['or_where'] as $key => $value)
            {
                $this->db->or_where($key, $value);
            }
        }

        if (isset($params['like'])) {
            foreach ($params['like'] as $key => $value)
            {
                $this->db->like($key, $value);
            }
        }

        if (isset($params['limit']) && isset($params['offset']) && !isset($params['total'])) {
            $this->db->limit($params['limit'], $params['offset']);
        } else if (isset($params['limit']) && !isset($params['total'])) {
            $this->db->limit($params['limit']);
        }
        if (isset($params['order_by']) && !isset($params['total'])) {
            $order = $params['order_by'] == 'desc' ? $params['order_by'] : 'asc';
            $this->db->order_by('usuarios.username', $order);
        }
        $query = $this->db->get();
        if ($query) {
            $usuarios = $query->result_array();
            $query->free_result(); //Libera la memoria
        }
        //pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $usuarios;
    }

    public function get_niveles_acceso($id_usuario, $params = []) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.clave_rol id_rol', 'A.nombre', 'B.activo'
        );
        if (isset($params['where'])) {
            $this->db->where($params['where']);
        }
        $this->db->select($select);
        $this->db->join('sistema.usuario_rol B', " B.clave_rol = A.clave_rol and B.id_usuario = {$id_usuario}", 'left');
        $query = $this->db->get('sistema.roles A');
        if ($query) {
            $niveles = $query->result_array();
            $query->free_result(); //Libera la memoria
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $niveles;
    }

    public function update($tipo = Usuario::BASICOS, $params = []) {
        $salida = false;
        switch ($tipo) {
            case Usuario::BASICOS:
                $salida = $this->update_basicos($params);
                break;
            case Usuario::PASSWORD:
                $salida = $this->update_password($params);
                break;
            case Usuario::NIVELES_ACCESO:
                $salida = $this->update_niveles_acceso($params);
                break;
            case Usuario::STATUS_ACTIVIDAD:
                $salida = $this->update_status_actividad($params);
                break;
            case Usuario::STATUS_REAPERTURA:
                $salida = $this->update_reapertura_registro($params);
                break;
        }
        return $salida;
    }

    private function update_reapertura_registro($params = [])
    {
       $this->db->flush_cache();
       $this->db->reset_query();
       $salida = false;
       try{
           $status_reapertura = $params['status_reapertura'] == 1 ? true : false;
           $this->db->set('activo_edicion', $status_reapertura);
           $this->db->where('id_docente = (select id_docente from censo.docente d where d.id_usuario='.$params['id_usuario'].')');
           $this->db->update('validacion.fin_registro_censo');
           $salida = true;
       }catch(Exception $ex)
       {
       }
       $this->db->reset_query();
       return $salida;
    }

    private function update_status_actividad($params = [])
    {
       $this->db->flush_cache();
       $this->db->reset_query();
       $salida = false;
       try{
           $status_usuario = $params['status_actividad'] == 1? true:false;
           $this->db->set('activo', $status_usuario);
           $this->db->where('id_usuario', $params['id_usuario']);
           $this->db->update('sistema.usuarios');
           $salida = true;
       }catch(Exception $ex)
       {
       }
       $this->db->reset_query();
       return $salida;
    }


    private function update_basicos($params = []) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $salida = false;
        $this->db->trans_begin();
        $params['where'] = array(
            'usuarios.id_usuario' => $params['id_usuario']
        );
        $resultado = $this->usuario->get_usuarios($params);
        if (count($resultado) == 1) {
            $usuario = $resultado[0];
            $docente = array(
                'curp' => $params['curp'],
                'sexo' => $params['sexo'],
                'rfc' => $params['rfc'],
                'email' => $params['email'],
                'nombre' => $params['nombre'],
                'apellido_p' => $params['apellido_p'],
                'apellido_m' => $params['apellido_m'],
                'telefono_particular' => $params['telefono_particular'],
                'telefono_laboral' => $params['telefono_laboral'],
                'fecha_nacimiento' => ($params['fecha_nacimiento']!='')?$params['fecha_nacimiento']:null
            );
            $this->db->start_cache();
            $this->db->where('id_docente', $usuario['id_docente']);
            $this->db->stop_cache();
            $this->db->update('censo.docente', $docente);
            $this->db->reset_query();
            $this->db->set('actual', 0);
            $this->db->update('censo.historico_datos_docente');
            //pr($this->db->last_query());
            $this->db->flush_cache();
            $this->db->reset_query();
            $categoria = $this->get_categoria($params['categoria_texto'])['id_categoria'];
            $departamento = $this->get_departamento($params['departamento_texto'])['id_departamento_instituto'];
            $historico = array(
                'id_docente' => $usuario['id_docente'],
                'actual' => 1,
                'id_categoria' => $categoria,
                'id_departamento_instituto' => $departamento
            );
            $this->db->insert('censo.historico_datos_docente', $historico);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $salida = false;
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function update_password($datos = null) {
        $salida = false;
        try {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('token');
            $this->db->where('id_usuario', $datos['id_usuario']);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($datos);
            //pr($this->db->last_query());
            if ($resultado) {
                $this->load->library('seguridad');
                $token = $resultado[0]['token'];
                $this->db->reset_query();
                $password = $this->seguridad->encrypt_sha512($token . $datos['pass'] . $token);
                $this->db->set('password', $password);
                $this->db->where('id_usuario', $datos['id_usuario']);
                $this->db->update('sistema.usuarios');
//                pr($this->db->last_query());
                $salida = true;
            } else {
                // pr('usuario no localizado');
            }
        } catch (Exception $ex) {
            //  pr($ex);
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function update_niveles_acceso($params = []) {
        //pr($params);
        $this->load->model('Administracion_model', 'admin');
        $id_usuario = $params['id_usuario'];
        $grupos = $this->admin->get_niveles_acceso();
//        pr($grupos);
        $this->db->trans_begin();
        foreach ($grupos as $grupo) {
            $id_grupo = $grupo['id_grupo'];
            $activo = (isset($params['activo' . $id_grupo])) ? true : false;
            $this->upsert_usuario_nivel_acceso($id_usuario, $id_grupo, $activo);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = false;
        } else {
            if(isset($params['entidad_asignada']) && $params['entidad_asignada']==1 ){
                $datos['id_usuario'] = $params['id_usuario'];
                $this->db->reset_query();
                $this->db->where('id_usuario', $params['id_usuario']);
                $this->db->delete('sistema.usuario_ooad');
                $this->db->reset_query();
                $this->db->where('id_usuario', $params['id_usuario']);
                $this->db->delete('sistema.usuario_umae');
                if(isset($params['activo'.LNiveles_acceso::Validador2])){
                   

                    if(isset($params['umae'])){
                        foreach($params['umae']as $key => $value){
                            $datos['umae'] = $value;
                            $this->db->reset_query();
                            
                            $this->db->insert('sistema.usuario_umae', $datos);
                            
                        }
                    }
                    unset($datos['umae']);
                    if(isset($params['ooad'])){
                        foreach($params['ooad']as $key => $value){
                            $datos['ooad'] = $value;
                            $this->db->reset_query();
                            $this->db->insert('sistema.usuario_ooad', $datos);
                            
                        }
                    }               

                }                

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = false;
                } else {
                    $this->db->trans_commit();
                    $status = true;
                }
            }else{
                $this->db->trans_commit();
                $status = true;

            }
        }
        return $status;
    }

    private function upsert_usuario_nivel_acceso($id_usuario, $id_grupo, $activo) {
        if ($id_grupo != '' && $id_usuario > 0) {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('count(*) cantidad');
            $this->db->start_cache();
            $this->db->where('clave_rol', $id_grupo);
            $this->db->where('id_usuario', $id_usuario);
            $this->db->stop_cache();
            $existe = $this->db->get('sistema.usuario_rol')->result_array()[0]['cantidad'] != 0;
            if ($existe) {
                $this->db->set('activo', $activo);
                $this->db->update('sistema.usuario_rol');
//                pr($this->db->last_query());
            } else {
                $this->db->flush_cache();
                $insert = array(
                    'id_usuario' => $id_usuario,
                    'clave_rol' => $id_grupo,
                    'activo' => $activo
                );
                $this->db->insert('sistema.usuario_rol', $insert);
            }
        }
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    public function datos_generales_docente($params) {
        $this->db->where('matricula', $params['matricula']);
        $query = $this->db->get('censo.docente');
        $this->db->flush_cache(); // limpiamos la cache
        $resultado = $query->free_result(); //Libera la memoria
        return $resultado;
    }

    public function datos_imss_docente($parametros) {

        /*
          select * from censo.docente
          left join censo.historico_datos_docente on (censo.historico_datos_docente.id_docente=censo.docente.id_docente and actual=1)
          inner join catalogo.delegaciones on catalogo.delegaciones.clave_delegacional = censo.historico_datos_docente.clave_delegacional
          inner join catalogo.categorias on catalogo.categorias.clave_categoria= historico_datos_docente.clave_categoria
          left join catalogo.departamentos_instituto on catalogo.departamentos_instituto.clave_departamental= historico_datos_docente.clave_departamental
          left join catalogo.unidades_instituto on catalogo.unidades_instituto.id_unidad_instituto=catalogo.departamentos_instituto.id_unidad_instituto
          where matricula='99095896'
         */



        $this->db->where('matricula', $parametros['matricula']);
        $this->db->join('censo.historico_datos_docente hd ', 'hd.id_docente=c.docente.id_docente', 'left');
        $this->db->join('catalogo.delegaciones cd ', 'cd.clave_delegacional = ch.clave_delegacional');
        $this->db->join('catalogo.categorias cc ', 'cc.clave_categoria= hd.clave_categoria');
        $this->db->join('catalogo.departamentos_instituto cdep ', 'cdep.clave_departamental= hd.clave_departamental', 'left');
        $this->db->join('catalogo.unidades_instituto cuni ', 'cuni.id_unidad_instituto=cdep.id_unidad_instituto and cuni.anio = date_part($$year$$, CURRENT_DATE)', 'left');

        $query = $this->db->get('censo.docente c');

        $this->db->flush_cache(); // limpiamos la cache
        $resultado = $query->free_result(); //Libera la memoria

        return $resultado;
    }

    /**
     * @author LEAS
     * @fecha 05/07/2017
     * @param type $id_user
     * @param type $id_file identificador del archivo
     * @return type array 'tp_msg' success si tuvo exito la transacción; danger
     * si ocurrio un rollback o un error
     */
    public function delete_foto_perfil($id_user, $id_file) {
        $this->db->trans_begin();

        $this->db->where('id_usuario', $id_user);
        $this->db->where('id_file', $id_file);
        $this->db->update('sistema.usuarios', array('id_file' => null));


        if ($this->db->trans_status() === FALSE) {//ocurrio un error
            $this->db->trans_rollback();
            $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => '');
        } else {
            $this->db->where('id_file', $id_file);
            $this->db->delete('censo.files');
            if ($this->db->trans_status() === FALSE) {//ocurrio un error
                $this->db->trans_rollback();
                $respuesta = array('tp_msg' => En_tpmsg::DANGER, 'mensaje' => '');
            } else {
                $this->db->trans_commit();
                $respuesta = array('tp_msg' => En_tpmsg::SUCCESS, 'mensaje' => '');
            }
        }
        return $respuesta;
    }


    public function carga_masiva($id_usuario, $tipo_registro, &$file_data, &$csv_array)
    {
        $resultado['msg'] = 'Error';
        $resultado['result'] = false;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción
        $valido = false;
        switch ($tipo_registro)
        {
            case 'registro_'.Usuario_model::SIAP:
                // pr($csv_array[0]);
                $valido = in_array('matricula', array_keys($csv_array[0]));
                $valido &= in_array('email', array_keys($csv_array[0]));
                $valido &= in_array('grupo', array_keys($csv_array[0]));
                $valido &= in_array('delegacion', array_keys($csv_array[0]));
                $resultado['msg'] = 'Las columnas para SIAP deben ser matricula, email, delegacion y grupo';
                break;
            case 'registro_'.Usuario_model::NO_SIAP:
                $valido = in_array('matricula', array_keys($csv_array[0]));
                $valido &= in_array('email', array_keys($csv_array[0]));
                $valido &= in_array('grupo', array_keys($csv_array[0]));
                $valido &= in_array('delegacion', array_keys($csv_array[0]));
                $valido &= in_array('nombre', array_keys($csv_array[0]));
                $valido &= in_array('paterno', array_keys($csv_array[0]));
                $valido &= in_array('materno', array_keys($csv_array[0]));
                $valido &= in_array('curp', array_keys($csv_array[0]));
                $valido &= in_array('sexo', array_keys($csv_array[0]));
                $valido &= in_array('rfc', array_keys($csv_array[0]));
                $valido &= in_array('clave_departamental', array_keys($csv_array[0]));
                $valido &= in_array('categoria', array_keys($csv_array[0]));
                $resultado['msg'] = 'Las columnas para NO SIAP deben ser matricula1, email1, delegacion, nombre, paterno, materno, curp, sexo, rfc, clave_departamental, categoria y grupo1';
                break;
            case 'registro_'.Usuario_model::NO_IMSS:
                $valido = in_array('matricula', array_keys($csv_array[0]));
                $valido &= in_array('email', array_keys($csv_array[0]));
                $valido &= in_array('grupo', array_keys($csv_array[0]));
                $resultado['msg'] = 'Las columnas para NO IMSS deben ser matricula, email, grupo';
                break;
            default:
                break;
        }
        if(!$valido)
        {
            return $resultado;
        }
        $precarga = array(
            'id_usuario' => $id_usuario,
            'nombre_archivo' => $file_data['file_name'],
            'peso' => $file_data['file_size'],
            'modelo' => 'Usuario_model',
            'funcion' => 'background_usuarios'
        );
        $this->db->insert('sistema.precargas', $precarga );
        $id_precarga = $this->db->insert_id();
        $registro = array(
            'id_precarga' => $id_precarga,
            'tabla_destino' => 'sistema.usuarios'
        );
        foreach ($csv_array as $row)
        {
            $pass = $this->seguridad->folio_random(10, TRUE);
            $row['tipo_registro'] = $tipo_registro;
            $row['password'] = $pass;
            $registro['detalle_registro'] = json_encode($row);
            $this->db->insert('sistema.detalle_precargas', $registro);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = 'Ëxito';
            $resultado['result'] = TRUE;
        }
        return $resultado;
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    public function background_usuarios(&$array)
    {
        $this->load->library('Seguridad');
        $this->load->library('Empleados_siap');
        $this->db->flush_cache();
        $this->db->reset_query();
        $salida = array();
        // pr($array['detalle_registro']);
        $parametros = json_decode($array['detalle_registro'], true);
        switch ($parametros['tipo_registro'])
        {
            case 'registro_'.Usuario_model::SIAP:
                $this->nuevo_siap($parametros, $salida);
                break;
            case 'registro_'.Usuario_model::NO_SIAP:
                $this->nuevo_no_siap($parametros, $salida);
                break;
            case 'registro_'.Usuario_model::NO_IMSS:
                $this->nuevo_no_imss($parametros, $salida);
                break;
            default:
                break;
        }
        if(isset($salida['result']) && $salida['result'])
        {
            $this->db->set('status', 'OK');
            $this->db->set('id_tabla_destino', $salida['id_usuario']);
        }else{
            $this->db->set('status', 'FALLA');
            $this->db->set('descripcion_status', $salida['msg']);
        }
        $this->db->where('id_detalle_precarga', $array['id_detalle_precarga']);
        $this->db->update('sistema.detalle_precargas');
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    public function test_precarga($data)
    {
        return json_decode($data['detalle_registro'], true);
    }

    
    private function tablas_relacionadas($param) {
        //pr($param);
        $delete = array(
            //'validacion.fin_registro_censo' => array('where' => '(id_docente = ' . $param['id_docente'] . ' )', 'entidad' => 'Registro del censo',),
            'censo.censo_info' => array('where' => 'id_censo in (select id_censo from censo.censo cp where cp.id_docente = '. $param['id_docente'] .')', 'entidad' => 'Censo info',),
            'censo.censo' => array('where' => '(id_docente = ' . $param['id_docente'] . ' )', 'entidad' => 'Censo',),
            'sistema.usuario_rol' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Rol de usuario',),
            'sistema.usuario_ooad' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Asignación OOAD',),
            'sistema.usuario_umae' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Asignación UMAE',),
            'censo.historico_datos_docente' => array('where' => '(id_docente = ' . $param['id_docente'] . ' )', 'entidad' => 'Historico docente',),
            'sistema.usuarios_modulos' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Modulo de usuario',),
            'censo.docente' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Docentes',),
            'sistema.control_registro_usuarios' => array('where' => '(id_usuario_registra = ' . $param['id_usuario'] . ' or id_usuario_registrado = '. $param['id_usuario'] .' )', 'entidad' => 'Docentes',),
            'sistema.usuarios' => array('where' => '(id_usuario = ' . $param['id_usuario'] . ' )', 'entidad' => 'Usuarios',),
            
        );
        $select = array(
                'validacion.validacionN1_finaliza' => array('where' => 'id_docente = ' . $param['id_docente'] . ' or id_validador = ' . $param['id_docente'] ,
                'mensaje' => 'No es posible eliminar un usuario que presente actividad en la finalización de la validacion',
                'tp_msg' => 'danger'
            ),
                'validacion.validacionN1_seccion' => array('where' => 'id_docente = ' . $param['id_docente'] . ' or id_validador = ' . $param['id_docente'] ,
                'mensaje' => 'No es posible eliminar un usuario que presente actividad en la validación de las secciones del censo',
                'tp_msg' => 'danger'
            ),
                'validacion.ratificador' => array('where' => 'id_docente = ' . $param['id_docente'] . ' or id_ratificador_validador = ' . $param['id_docente'] ,
                'mensaje' => 'No es posible eliminar un usuario que presente actividad en la ratificación',
                'tp_msg' => 'danger'
            ),
                /*'sistema.control_registro_usuarios' => array('where' => 'id_usuario_registra = ' . $param['id_usuario'] ,
                'mensaje' => 'No es posible eliminar un usuario que presente actividad en la control de registro de docentes',
                'tp_msg' => 'danger'
            ),*/
                'validacion.fin_registro_censo' => array('where' => '(id_docente = ' . $param['id_docente'] . ' )' ,
                'mensaje' => 'No es posible eliminar un usuario que presente actividad en la finalización del registro del censo',
                'tp_msg' => 'danger'
            ),
        );
        
        $conf['delete'] = $delete;
        $conf['select'] = $select;
        return $conf;
    } 

    public function delete_user($param) {
        $respuesta = ['tp_msg'=>'success', 'mensaje'=>'Eliminación exitosa'];
        $conf_borrado = $this->tablas_relacionadas($param);
        $this->db->flush_cache();
        $this->db->reset_query();
        $salida = false;
        //Validamos que se pueda borrar
        foreach($conf_borrado['select'] as $key => $value){
            $this->db->reset_query();
            $this->db->select('count(*) total');
            $this->db->where($value['where'], null);
            $query = $this->db->get($key);
            
            $total = $query->result_array()[0];
            if($total['total'] > 0){
                $respuesta['mensaje'] = $value['mensaje']; 
                $respuesta['tp_msg'] = $value['tp_msg']; 
                return $respuesta;
            }
        }
        //Inicia borrado de las tablas 
    $this->db->trans_begin();
        $is_borrado_exitoso = 1;
        foreach($conf_borrado['delete'] as $key => $value){
            $this->db->reset_query();
            
            $this->db->where($value['where'], null);
            $query = $this->db->delete($key);
            if ($this->db->trans_status() === FALSE) {
                $respuesta['mensaje'] = 'Error al borrar información de ' . $value['entidad'];
                $respuesta['tp_msg'] = 'danger' ;
                $is_borrado_exitoso = 0;
                break;
            }            
        }

        if ($is_borrado_exitoso == 0) {
            $this->db->trans_rollback();
            
        } else {
            $this->db->trans_commit();            
        }
        
        $this->db->flush_cache();
        $this->db->reset_query();
        return $respuesta;
    }

    public function actualizar_aviso_privacidad($params = [])
    {
       $salida = array('result'=>false, 'msg'=>'Debe confirmar la lectura del aviso de privacidad.');
       try {
           $this->db->set('aviso_privacidad', ($params['aviso_privacidad'] == 1 ) ? true : false);
           $this->db->where('id_docente', $params['id_docente']);
           $this->db->update('censo.docente');
           $salida = array('result'=>true, 'msg'=>'Gracias por su atención.');
       } catch(Exception $ex){ }
       $this->db->reset_query();
       return $salida;
    }    
}
