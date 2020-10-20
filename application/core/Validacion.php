<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : LEAS
 * */
class Validacion extends Informacion_docente {

    function __construct() {
        parent::__construct();
        $this->load->model('Normativo_model', 'normativo');
        $this->load->model('Docente_model', 'docente');
        $this->load->model("Catalogo_model", "cm");
    }

    public function index() {
    }

    public function detalle_censo_docente($id_docente = null){
          //pr($this->session->userdata('usuario'));
//        $datos_elemento_seccion = $this->get_datos_actividad_docente_c($id_docente, NULL, NULL, TRUE);
        //$this->benchmark->mark('code_start');
        //Sesión de usuario obtiene la sesión de la cuenta
        $datos_sesion = $this->get_datos_sesion();
        if ($datos_sesion) {//Valida los datos de la sesión o la información de la sesíon
            if (is_null($id_docente)) {
                $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            }
            $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente);
            //JS para renderizar formularios e información del docente principalmente
            //pr($datos_elemento_seccion);
            $this->load->library('template_item_perfil');
            $this->template_item_perfil->set_registro_censo($datos_elemento_seccion); //Agrega registros
            $datos_files_js = $this->get_files_js_formularios_c($id_docente);
            $this->template_item_perfil->set_files_js_formularios($datos_files_js);
            
            $this->load->model("Docente_model", "dm");
            /* carga datos generales */
            $datos_generales = $this->dm->get_datos_generales($id_docente);
            //pr($datos_generales);
            if (!empty($datos_generales)) {
                $this->load->library('curp', array('curp' => $datos_generales['curp'])); //Ingresa datos del curp
                $datos_generales['edad'] = $this->curp->getEdad(); //Calcula la edad del usuario
                 if(is_null($datos_generales['fecha_nacimiento'])){
                     //pr($datos_generales['fecha_nacimiento']);
                     $datos_generales['fecha_nacimiento'] = $this->curp->getFechaNac();
                     //pr( $datos_generales['fecha_nacimiento']);
                    }
                    $this->template_item_perfil->set_datos_generales($datos_generales); //Información general del docente 
                }
                /* Cargar imagen de perfil */
            $datos_imagen_docente = $this->dm->get_imagen_perfil($id_docente); //Obtener imagen del docente
            $this->template_item_perfil->set_datos_imagen($datos_imagen_docente);
            /* carga datos imss */
            $this->template_item_perfil->set_datos_imss(
                    $this->dm->get_historico_datos_generales($id_docente) +
                    array('matricula' => $datos_generales['matricula'])); //Información IMSS
            //Ejecutar datos de registro de perfil
            /*$vista = $this->template_item_perfil->get_template_registro(
                $this->template_item_perfil->get_vistas_regisatros_censo(null, 'perfil/item_ficha_actividad_impresion.php', 
                    '/perfil/item_datos_generales_impresion.php', 'perfil/item_datos_imss_impresion.php', 
                    '/perfil/item_carrusel_impresion.php', 'perfil/tab_perfil_impresion.php'), 
                    '/perfil/inicio/inicio_docente.tpl.php'
                    //$this->template_item_perfil->get_vistas_regisatros_censo(), 'perfil/perfil_impresion.tpl.php'
                    , null
                );*/
            $vista = $this->template_item_perfil->get_template_registro(
                $this->template_item_perfil->get_vistas_regisatros_censo_inicio(null, 
                '/perfil/inicio/item_ficha_actividad_impresion', 
                '/perfil/inicio/item_datos_generales_impresion', '/perfil/inicio/item_datos_imss_impresion', 
                '/perfil/inicio/item_carrusel_impresion', '/perfil/inicio/tab_perfil_impresion'), 
                '/perfil/inicio/inicio_docente.tpl.php',                
                null , $id_docente               
                
            );
            //pr($vista);
            $this->template->setMainContent($vista);
            //$this->template->getTemplate(true, 'tc_template/impresion.tpl.php');
            $this->template->getTemplate();
        }
        //        $this->output->set_profiler_sections($sections);
//        $this->output->enable_profiler(TRUE);
        //$this->benchmark->mark('code_end');
        //echo $this->benchmark->elapsed_time('code_start', 'code_end');
        //$this->output->parse_exec_vars = TRUE;
        //$this->output->append_output($this->benchmark->memory_usage());
    }
    
    public function listado_docentes(){
        $output['catalogos']['result_delegacional'] = $this->normativo->get_delegacional();
        array_unshift($output['catalogos']['result_delegacional'], ['clave_delegacional'=>'',"nombre"=>'Selecciona OOAD']); 
        $output['catalogos']['fase_carrera_docente'] = $this->cm->get_fase_carrera_docente();
        array_unshift($output['catalogos']['fase_carrera_docente'], ['id_docente_carrera'=>'',"descripcion"=>'Selecciona...']); 
        $this->template->setTitle('Censo de docentes');
        $main_content = $this->load->view('validador/body_lista_docentes.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }
    
    public function docentes(){
        $datos_sesion = $this->get_datos_sesion();
        //pr($datos_sesion);
        $data_post = null;
        if($this->input->post()){
            $data_post = $this->input->post(null, true);                        
        }
        $rol_aplica = $this->get_rol_aplica($datos_sesion,$data_post);
        //pr($rol_aplica);
        
        $output['datos_docentes'] = $this->docente->get_historico_datos_generales(null, null, $rol_aplica);
        //pr($output['datos_docentes']);
        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($output);
        
    }
    

    
    private function get_rol_aplica($datos_sesion, $data_post = null){
        $claves_rol = $this->get_roles_usuario(2);
        $conf=['rol_aplica'=>null, 'filtros'=>null, 'rol_docente'=>LNiveles_acceso::Docente];
        $conf['rol_docente']=LNiveles_acceso::Docente;
        if(isset($claves_rol[LNiveles_acceso::Normativo])){
            $conf['rol_aplica'] = LNiveles_acceso::Normativo;             
            if(!empty($data_post['clave_delegacional'])){
                $conf['filtros']['where']['d.clave_delegacional'] = $data_post['clave_delegacional'];
                $conf['filtros']['where']['d.clave_delegacional'] = $data_post['clave_delegacional'];
            }
        }else if(isset($claves_rol[LNiveles_acceso::Validador2])){
            $conf['rol_aplica'] = LNiveles_acceso::Validador2;      
            $conf['filtros']['where']['d.clave_delegacional'] = $datos_sesion['clave_delegacional']; 
            

        }else if(isset($claves_rol[LNiveles_acceso::Validador1])){
            $conf['rol_aplica'] = LNiveles_acceso::Validador1;
            //$where['d.clave_delegacional'] =  
            $ids_usuario_registrados = $this->get_usuarios_registro_validador($datos_sesion[En_datos_sesion::ID_USUARIO]);
            if(!empty($ids_usuario_registrados)){
                $stringIds = implode(',',$ids_usuario_registrados);
                $auxFiltro = '(u.clave_unidad=\'' . $datos_sesion['clave_unidad'].'\' or doc.id_usuario IN(' . $stringIds . '))';
                $conf['filtros']['where'][$auxFiltro] = null;                
            }else{                
                $conf['filtros']['where']['u.clave_unidad'] = $datos_sesion['clave_unidad']; 
            }
        }
        return $conf;
    }

    private function get_usuarios_registro_validador($id_user_sesion){
        $this->load->model('Catalogo_model', 'cm');        
        $params['select'] = array('id_usuario_registrado');
        $params['where'] = ['cru.id_usuario_registra'=>$id_user_sesion, 'cru.active'=>true];
        $result = $this->cm->get_registros("sistema.control_registro_usuarios cru", $params);
        $idsUserResult = [];
        if(!empty($result)){
            foreach($result as $val){
                $idsUserResult[] = $val['id_usuario_registrado'];
            }
        }
        return $idsUserResult;
    }

    public function valida_censo(){

    }

    public function registro_docente(){
        $output["texts"] = $this->lang->line('formulario'); //textos del formulario
        //pr($this->input->post());
        if($this->input->post()){
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro_usuario'); //Obtener validaciones de archivo general
            unset($validations[array_key_last($validations)]);
            $this->form_validation->set_rules($validations); //Añadir validaciones
            //pr($validations);
            
            if ($this->form_validation->run() == TRUE)
            {
                $this->load->model('Administracion_model', 'administracion');
                $is_user = $this->get_datos_sesion(En_datos_sesion::ID_USUARIO);
                $data = array(
                    'matricula' => $this->input->post('reg_usuario', TRUE),
                    'delegacion' => $this->input->post('id_delegacion', TRUE),
                    'email' => $this->input->post('reg_email', true),
                    'password' => $this->input->post('reg_password', TRUE),
                    'grupo' => Administracion_model::DOCENTE,
                    'registro_usuario' => true,
                    'id_usuario_sesion' => $is_user

                );
                //$temp = $data['password'];
                $this->load->library('empleados_siap');
                $this->load->library('seguridad');
                $this->load->model('Usuario_model', 'usuario');
                //pr($data); exit();
                $output['registro_valido'] = $this->usuario->nuevo($data, Usuario_model::SIAP);
                $data2 = array(
                    'id_usuario_registra' => $is_user,
                    'id_usuario_registrado' => $output['registro_valido']['id_usuario'],
                );
                //pr($data);
                //if(isset($output['envia_correo']) && $output['envia_correo']){
                    $this->usuario->save_control_registro_usuarios($data2);
                    //$data['password'] = $temp;
                    $this->envia_correo_electronico($data['email'], $data);
                //}
                //pr($data);
            }else{
                // pr(validation_errors());;
            }
        }
        $this->load->model('Catalogo_model', 'catalogo');
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
        $main_content = $this->load->view("docente/registro/registro_docente.tpl.php", $output, true);
       
        
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }


    /**
     *  
      *            $datos =  'email'                     'matricula'
      *              'nombre' ,                    'apellido_p',
     *               'apellido_m' ,                    'curp' ,
     *               'sexo' ,                    'rfc',
     *               'status_siap'
            
     */
    private function envia_correo_electronico($email, $datos){
        $this->enviar_correo($email, $datos, 'Censo de profesores', '/docente/email/email_docente_censo.php');
        //$this->load->model('Plantilla_model', 'plantilla');
        //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
        //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO);
    }


    public function registro(){
      $this->envia_correo_electronico('cenitluis.pumas@gmail.com', ['nombre'=>'Jesús Díaz', 'password'=>'AUSL880811BC6_NOW']);
    }


}
