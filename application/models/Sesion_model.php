<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sesion_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function validar_usuario($usr, $passwd,&$datos_usuario) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->start_cache();

        $this->db->select(array('username', 'password', 'token', 'username_alias', ''));
        $this->db->from('sistema.usuarios u');
        $this->db->where('u.username', $usr);
        $num_user = $this->db->count_all_results();
        
        $usuario_alias = false;
        /* Consulta si existe el alias*/
        if ($num_user == 0) {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select(array('username', 'password', 'token', 'username_alias', ''));
            $this->db->from('sistema.usuarios u');
            $this->db->where('u.username_alias', $usr);
            $num_user = $this->db->count_all_results();        
            if ($num_user == 1) {                
                $usuario_alias = true;
                $datos_usuario['is_alias'] = true;
            }
        }
        $this->db->reset_query();
        if ($num_user == 1) {
            $usuario = $this->db->get();
            $result = $usuario->result_array();
            $datos_usuario['matricula']=$result[0]['username']; 
            // pr($passwd);
            // pr($result);
            $this->load->library('seguridad');
            $cadena = $result[0]['token'] . $passwd . $result[0]['token'];
            $clave = $this->seguridad->encrypt_sha512($cadena);
            // pr($clave);
            // pr($result[0]['password']);
            $this->db->flush_cache();
            $this->db->reset_query();
			//pr("clave: ".$clave);
			//pr("pass: ".$result[0]['password']);
            if ($clave == $result[0]['password']) {
                if($usuario_alias){
                    return 4;//Existe comop alias
                }
                return 1; //Existe
            }
            return 2; //contraseña incorrrecta
        } else {
            return 3; //Usuario no existe
        }

        //$cadena = $result[0]['token'] . $password . $result[0]['token'];
    }

    public function update_password($code = null, $new_password = null)
    {
        $salida = false;
        if ($code != null && $new_password != null)
        {
            $this->db->flush_cache();
            $this->db->reset_query();

            $this->db->select(array(
                'id_usuario', 'token'
            ));
            $this->db->where('recovery_code', $code);
            $this->db->limit(1);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($resultado);
            if ($resultado)
            {
                $this->load->library('seguridad');
                $usuario = $resultado[0];
                $this->db->reset_query();
                $pass = $this->seguridad->encrypt_sha512($usuario['token'] . $new_password . $usuario['token']);
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('password', $pass);
                $this->db->set('recovery_code', null);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
                $salida = true;
            }
        }
        return $salida;
    }


    public function recuperar_password($username) {
                $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array(
            'u.id_usuario', 'concat("D".nombre, $$ $$, "D".apellido_p, $$ $$, "D".apellido_m) nombre', 'u.email', 'recovery_code'
        ));
        $this->db->join('censo.docente D', 'D.id_usuario = u.id_usuario', 'left');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $resultado = $this->db->get('sistema.usuarios u')->result_array();

        if ($resultado)
        {
            $usuario = $resultado[0];
            if (empty($usuario['recovery_code']))
            {
                $this->load->library('seguridad');
                $usuario['recovery_code'] = $this->seguridad->crear_token();
                $this->db->reset_query();
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('recovery_code', $usuario['recovery_code']);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
            }
            $this->send_recovery_mail($usuario);
        }
    }

    private function send_recovery_mail($usuario)
    {
        $this->load->config('email');
        $this->load->library('My_phpmailer');
        $mailStatus = $this->my_phpmailer->phpmailerclass();
        $emailStatus = $this->load->view('sesion/mail_recovery_password.tpl.php', $usuario, true);
//        $mailStatus->addAddress('zurgcom@gmail.com'); //pruebas chris
        $mailStatus->addAddress($usuario['email']);
        $subject = 'Recuperación de contraseña para SIPIMSS';
        $subject = ENVIRONMENT=='development'?'[Pruebas] '.$subject:$subject;
        $mailStatus->Subject = utf8_decode($subject);
        $mailStatus->msgHTML(utf8_decode($emailStatus));
        $mailStatus->send();
    }

    public function get_info_convocatoria($id_docente)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'E.id_linea_tiempo', 'E.id_workflow', 'E.nombre', 'E.clave',
            'E.fechas_inicio', 'E.fechas_fin', 'E.id_etapa_activa',
            'F.nombre etapa_activa',
            '("cf".id_docente is not null and "cf".id_linea_tiempo is not null) finalizada'
        );
        $this->db->select($select);
        $this->db->join('catalogo.departamentos_instituto B ','B.id_departamento_instituto = A.id_departamento_instituto', 'inner');
        $this->db->join('catalogo.unidades_instituto C ',' C.clave_unidad = B.clave_unidad and C.anio = date_part($$year$$, current_date)');
        $this->db->join('workflow.unidades_censo D','D.id_unidad = C.id_unidad_instituto', 'inner');
        $this->db->join('workflow.lineas_tiempo E','E.id_linea_tiempo = D.id_linea_tiempo', 'inner');
        $this->db->join('workflow.etapas F','F.id_etapa = E.id_etapa_activa', 'inner');
        $this->db->join('workflow.convocatoria_finalizada cf','cf.id_docente = A.id_docente and cf.id_linea_tiempo = E.id_linea_tiempo', 'left');
        $this->db->where('A.id_docente', $id_docente);
        $this->db->where('E.activa', true);
        $this->db->where('E.id_etapa_activa is not null');
        $this->db->where('D.activa', true);
        $this->db->where('A.actual','1');
        // $this->db->where('E.id_workflow','1');
        $convocatoria = $this->db->get('censo.historico_datos_docente A')->result_array();
        // pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        // $convocatoria = array(array('id_linea_tiempo' => 3, 'id_workflow' => 1, 'nombre' => 'Convocatoria censo', 'clave' => 'CENSO',
        //     'fechas_inicio' =>'{2018-03-22,2018-03-25,2018-03-27}', 'fechas_fin' => '{2018-03-23,2018-03-26,2018-03-29}',
        //     'id_etapa_activa' => 1, 'etapa_activa' => 'Registro', 'finalizada' => false
        // ));
        return $convocatoria;
    }

    public function get_info_convocatoria_censo($id_convocatoria = null)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_convocatoria', 'nombre', 'clave', 'id_tipo_convocatoria', 'fechas_inicio', 'fechas_fin', 'activa', 'is_confirmado_cierre_registro_censo'
        );
        $this->db->select($select);
        if(is_null($id_convocatoria)){
            $this->db->where('C.activa', true);                
            
        }else{            
            $this->db->where('C.id_convocatoria', $id_convocatoria);                
        }
        $this->db->where('C.activa', true);                
        $convocatoria = $this->db->get('convocatoria.convocatorias C')->result_array();
        // pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        
        if(count($convocatoria)>0){
            $convocatoria=$convocatoria[0];
        }
        return $convocatoria;
    }

    public function get_fin_registro_censo($id_docente, $id_convocatoria)
    {
        //select   from  where id_convocatoria = 2 and id_docente = 78;
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'activo_edicion'
        );
        $this->db->select($select);
        $this->db->where('id_convocatoria', $id_convocatoria);                
        $this->db->where('id_docente', $id_docente);                
        $convocatoria = $this->db->get('validacion.fin_registro_censo A')->result_array();
         //pr($this->db->last_query());
         $this->db->flush_cache();
         $this->db->reset_query();
         
         if(count($convocatoria)>0){
             //pr("aqui valido");
             return $convocatoria[0]['activo_edicion'];
            }else{
                return (!is_null($id_convocatoria) && !empty($id_convocatoria) && $id_convocatoria>0);            
            }
            //exit();
    }

    public function get_niveles_acceso($id_usuario, $agrupadas = false){
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'B.clave_rol', 'B.nombre rol'
        );
        $this->db->select($select);
        $this->db->join('sistema.roles B','B.clave_rol = A.clave_rol', 'inner');
        $this->db->where('A.id_usuario', $id_usuario);
        $this->db->where('A.activo', true);
        $niveles = $this->db->get('sistema.usuario_rol A')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        if($agrupadas)
        {
            $tmp = [];
            foreach ($niveles as $row)
            {
                $tmp[$row['clave_rol']] = $row['rol'];
            }
            $niveles = $tmp;
        }
        return $niveles;
    }
    
    public function get_niveles_acceso_cat($agrupadas_catalogo){
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'B.clave_rol', 'B.nombre rol'
        );
        $this->db->select($select);        
        $this->db->where('B.activo', true);
        $niveles = $this->db->get('sistema.roles B')->result_array();
        $this->db->flush_cache();
        $this->db->reset_query();
        if($agrupadas_catalogo)
        {
            $tmp = [];
            foreach ($niveles as $row)
            {
                $tmp[$row['clave_rol']] = $row['rol'];
            }
            $niveles = $tmp;
        }
        return $niveles;
    }

    /**
     * Funcion que obtiene la notificacion estatica
     * @author Cheko
     * @return type $notificacion Numero de días restantes  de la notificacion
     */
    public function get_notificaciones_estaticas()
    {

    }

}
