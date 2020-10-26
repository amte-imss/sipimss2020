<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Inicio
 *
 * @author chrigarc
 */
class Inicio extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('seguridad');
        $this->load->library('empleados_siap');
        $this->load->helper(array('secureimage'));
        $this->load->model('Sesion_model', 'sesion');
        $this->load->model('Usuario_model', 'usuario');
    }

    public function index()
    {
        //load idioma
        $data["texts"] = $this->lang->line('formulario'); //textos del formulario
        //validamos si hay datos
        if ($this->input->post())
        {
            $post = $this->input->post(null, true);

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('login'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $datos_usuario = array('matricula'=>'', 'is_alias' => false);
                $valido = $this->sesion->validar_usuario($post["usuario"], $post["password"], $datos_usuario);
                $mensajes = $this->lang->line('mensajes');
                switch ($valido)
                {
                    case 1:
                    case 4:
                        //redirect to home //load menu...etc etc
                        $params = array(
                            'where' => array('username' => $datos_usuario['matricula']),
                            'select' => array(
                                'usuarios.id_usuario', 'coalesce(docentes.matricula, usuarios.username) matricula',
                                'docentes.id_docente', 'docentes.nombre',
                                'docentes.apellido_p', 'docentes.apellido_m', 'sexo',
                                'E.id_unidad_instituto', 'E.nombre unidad', 'E.clave_unidad',
                                'docentes.fecha_nacimiento', 'D.id_departamento_instituto', 'D.nombre departamento',
                                'D.clave_departamental', 'F.clave_categoria',
                                'F.id_categoria', 'F.nombre categoria', 'C.cve_tipo_contratacion',
                                'docentes.curp', 'docentes.rfc', 'docentes.telefono_particular',
                                'docentes.telefono_laboral', 'docentes.email', 'G.clave_delegacional', 'G.nombre delegacion',
                            )
                        );
                        $die_sipimss['usuario'] = $this->usuario->get_usuarios($params)[0];
                        $die_sipimss['usuario']['is_alias_sesion'] = $datos_usuario['is_alias'];
                        $die_sipimss['usuario']['niveles_acceso'] = $this->sesion->get_niveles_acceso($die_sipimss['usuario']['id_usuario']);
                        //** Roles por clave */
                        $roles = $die_sipimss['usuario']['niveles_acceso'];
                        $roles_clave = [];
                        foreach ($roles as $key => $values) {
                            $roles_clave[$values['clave_rol']] = $values; 
                        }
                        $die_sipimss['usuario']['niveles_acceso_cves'] = $roles_clave;
                        //** fin rol por claves  */                        
                        $die_sipimss['usuario']['convocatoria'] = $this->sesion->get_info_convocatoria_censo();
                        if(!empty($die_sipimss['usuario']['convocatoria']) && isset($roles_clave[LNiveles_acceso::Docente])){
                            //pr($die_sipimss['usuario']['convocatoria']);
                            //pr($roles_clave[LNiveles_acceso::Docente]);
                            $die_sipimss['usuario']['registro_censo'] = $this->sesion->get_fin_registro_censo($die_sipimss['usuario']['id_docente'], $die_sipimss['usuario']['convocatoria']['id_convocatoria']);                            
                        }else{
                            $die_sipimss['usuario']['registro_censo'] = false;
                        }
                        //exit();
                        $this->session->set_userdata('die_sipimss', $die_sipimss);
                        // $this->valida_info_siap($die_sipimss['usuario']); //Esta linea se necesita en productivo y desarrollo en el imss
                        // $this->seguridad->token();//Genera un token
                        //pr($usuario);
                        //redirect(site_url() . '/inicio/dashboard');

                        redirect(site_url() . '/inicio/inicio');
                        /*$main_content = $this->load->view('sesion/index.tpl.php', null, true);
                        $this->template->setMainContent($main_content);
                        $this->template->getTemplate();*/
                        break;
                    case 2:
                        $data['errores'] = true;
                        $this->session->set_flashdata('flash_password', $mensajes[$valido]);
                        break;
                    case 3:
                        $data['errores'] = true;
                        $this->session->set_flashdata('flash_usuario', $mensajes[$valido]);
                        break;
                    default :
                        break;
                }
            } else
            {
//                pr(validation_errors());
                $data['errores'] = validation_errors();
            }
        }

        $die_sipimss = $this->session->userdata('die_sipimss');
        if (isset($die_sipimss['usuario']['id_usuario']))
        {

            redirect(site_url('inicio/inicio') );
            //$this->load->view("sesion/index.tpl.php", $data);
            /*$this->template->setTitle('Inicio');
            $main_content = $this->load->view('sesion/index.tpl.php', $data, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();*/
        } else
        {
            //cargamos plantilla
//            pr(validation_errors());
            $this->load->model('Catalogo_model', 'catalogo');
            $data['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
            $data['my_modal'] = $this->load->view("sesion/login_modal.tpl.php", $data, TRUE);
            $data['registro_modal'] = $this->load->view("sesion/registro_modal.tpl.php", $data, TRUE);
            $this->load->view("sesion/login.tpl.php", $data);
        }
//        $this->output->enable_profiler(true);
    }

    public function inicio(){
        //pr($this->get_roles_usuario());
        $roles = $this->get_roles_usuario();
        //Para el inicio del rol. la tabla que configura es : sistema.inicio_rol_modulo
        //pr($roles);
        if(!is_null($roles)){
            $datos_inicio_por_rol = $this->get_inicio_rol($roles);
            if(count($datos_inicio_por_rol)>0){
                $url = $datos_inicio_por_rol[0]['url'];                
                redirect($url);
                exit();
            }
        }
        
        $output = [];
        // $wf = $this->sesion->get_info_convocatoria($this->get_datos_sesion('id_docente'));
        // pr($wf);
        /* aqui es la primer vista del usuario*/
         // pr($this->get_datos_sesion());
        // pr($this->sesion->get_info_convocatoria($this->get_datos_sesion('id_docente')));
        // $this->valida_info_siap($this->get_datos_sesion()); //solo pruebas
        $u_siap = $this->session->flashdata('die_sipimss_siap');
        /// pr($u_siap);
        if(!is_null($u_siap) && $u_siap == 0)
        {
            $output['usuario'] = $this->get_datos_sesion();
            $output['modal_siap'] = $this->load->view('sesion/modal_siap.tpl.php', $output, true);
        }
        $this->template->setTitle('Inicio');
        $main_content = $this->load->view('sesion/index.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    private function get_inicio_rol($array_roles){
        $id_docente = $this->get_datos_sesion('id_docente');
        $this->load->model('Catalogo_model', 'cm');
        $join = array(
        ['table' => 'sistema.modulos mo',
        'condition' => 'mo.clave_modulo = rm.clave_modulo',
        'type' => 'join'
        ],
        ['table' =>'sistema.roles r',
        'condition' =>'r.clave_rol = rm.clave_rol',
        'type' => 'join'
        ],
        ['table' =>'sistema.inicio_rol_modulo irm',
        'condition' =>'irm.clave_modulo = rm.clave_modulo and irm.clave_rol = rm.clave_rol',
        'type' => 'join'
        ],
        
        );
        $params['select'] = array('irm.id_inicio', 'rm.clave_rol', 'rm.clave_modulo', 'mo.clave_configurador_modulo' , 
        'mo.url', 'mo.nombre modulo', 'r.nombre rol');
        $params['join'] = $join;
        $params['where'] = ['irm.activo'=>true,
        'mo.activo'=>true,
        ];
        $params['where_in'] = [
        'r.clave_rol'=>$array_roles
        ];
        $params['order'] = 'irm.orden';
        $result = $this->cm->get_registros("sistema.roles_modulos rm", $params);
        
        return $result;
    }

    function captcha()
    {
        new_captcha();
    }

    public function cerrar_sesion()
    {
        $this->session->sess_destroy();
        redirect(site_url());
    }

    public function recuperar_password($code = null)
    {
        $datos = array();
        if ($this->input->post() && $code == null)
        {
            $usuario = $this->input->post("usuario", true);
            $this->form_validation->set_rules('usuario', 'Usuario', 'required');
            if ($this->form_validation->run() == TRUE)
            {
                $this->sesion->recuperar_password($usuario);
                $datos['recovery'] = true;
            }
        } else if ($this->input->post() && $code != null)
        {
            $this->form_validation->set_rules('new_password', 'Constraseña', 'required');
            $this->form_validation->set_rules('new_password_confirm', 'Confirmar constraseña', 'required');
            if ($this->form_validation->run() == TRUE)
            {
                $new_password = $this->input->post('new_password', true);
                $datos['success'] = $this->sesion->update_password($code, $new_password);
            }
        } else if ($code != null)
        {
            $datos['code'] = $code;
            $datos['form_recovery'] = true;
        }
        $datos['my_modal'] = $this->load->view("sesion/recuperar_password.tpl.php", $datos, TRUE);
        $datos["texts"] = $this->lang->line('formulario'); //textos del formulario
        $datos['my_modal'] .= $this->load->view("sesion/login_modal.tpl.php", $datos, TRUE);
        $this->load->view("sesion/login.tpl.php", $datos);
        //$this->load->view("sesion/recuperar_password.tpl.php", $datos);
    }

    public function manteminiemto()
    {
        echo 'En mantenimiento';
    }

    public function dashboard()
    {
        $id_usuario = $this->get_datos_sesion(En_datos_sesion::ID_USUARIO);
        $this->load->model('Modulo_model', 'modulo');
        $this->load->library('LNiveles_acceso');
        $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');
        if($this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Super, LNiveles_acceso::Admin, LNiveles_acceso::Nivel_central), $niveles)){
            redirect('reporte/nivel_central');
        }else{
            redirect('reporte/n1');
        }
    }

    public function registro()
    {
        $output["texts"] = $this->lang->line('formulario'); //textos del formulario
        if($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro_usuario'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $this->load->model('Administracion_model', 'administracion');
                $data = array(
                    'matricula' => $this->input->post('reg_usuario', TRUE),
                    'delegacion' => $this->input->post('id_delegacion', TRUE),
                    'email' => $this->input->post('reg_email', true),
                    'password' => $this->input->post('reg_password', TRUE),
                    'grupo' => Administracion_model::DOCENTE,
                    'registro_usuario' => true
                );
                $output['registro_valido'] = $this->usuario->nuevo($data);
            }else{
                // pr(validation_errors());;
            }
        }
        $this->load->model('Catalogo_model', 'catalogo');
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
        $this->load->view("sesion/registro_modal.tpl.php", $output);
    }

    public function mesa_ayuda(){
        $this->template->set_titulo_modal('<h4><span class="glyphicon glyphicon-lock"></span>Mesa de ayuda</h4>');
        $view = $this->load->view('sesion/mesa_ayuda.tpl.php', [], true);
        $this->template->set_cuerpo_modal($view);
        $this->template->get_modal();
    }


    private function valida_info_siap($usuario)
    {
        $status = true;
        if(isset($usuario['id_docente']))
        {
            $status = false;
            // pr($usuario['clave_departamental'].' - '.$usuario['clave_categoria']);

            $siap = $this->empleados_siap->buscar_usuario_siap(substr($usuario['clave_unidad'], 0, 2), $usuario['matricula']);
            if($siap['tp_msg'] == En_tpmsg::SUCCESS)
            {
                $siap = $siap['empleado'];
                // pr($siap);
                // pr($siap['adscripcion'][0].' - '.$siap['emp_keypue'][0]);
                $status = ($siap['adscripcion'][0] == $usuario['clave_departamental']) && ($siap['emp_keypue'][0] == $usuario['clave_categoria']);
                // echo $status ? 'true':'false';
            }
            if(!$status)
            {
                $this->session->set_flashdata('die_sipimss_siap', 0);
            }
        }
    }

    public function p404()
    {
        $output = [];
        $this->load->view('404.tpl.php', $output);
    }
    
    public function registro_docente()
    {
    
    
    }
}
        