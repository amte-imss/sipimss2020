<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
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

    function index()
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
                $valido = $this->sesion->validar_usuario($post["usuario"], $post["password"]);
                $mensajes = $this->lang->line('mensajes');
                switch ($valido)
                {
                    case 1:
                        //redirect to home //load menu...etc etc
                        $params = array(
                            'where' => array('matricula' => $post['usuario']),
                            'select' => array(
                                'usuarios.id_usuario', 'docentes.matricula', 'docentes.id_docente', 'docentes.nombre',
                                'docentes.apellido_p', 'docentes.apellido_m', 'sexo',
                                'docentes.fecha_nacimiento', 'D.id_departamento_instituto', 'D.nombre departamento',
                                'F.id_categoria', 'F.nombre categoria', 'C.cve_tipo_contratacion',
                                'docentes.curp', 'docentes.rfc', 'docentes.telefono_particular',
                                'docentes.telefono_laboral', 'docentes.email'
                            )
                        );
                        $usuario = $this->usuario->get_usuarios($params)[0];
                        $this->session->set_userdata('usuario', $usuario);
//                        pr($usuario);
                        redirect(site_url() . '/welcome/dashboard');
                        break;
                    case 2:
                        $this->session->set_flashdata('flash_password', $mensajes[$valido]);
                        break;
                    case 3:
                        $this->session->set_flashdata('flash_usuario', $mensajes[$valido]);
                        break;
                    default :
                        break;
                }
            } else
            {
                pr(validation_errors());
                $data['errores'] = validation_errors();
            }
        }

        $usuario = $this->session->userdata('usuario');
        if (isset($usuario['id_usuario']))
        {
            redirect(site_url() . '/welcome/dashboard');
        } else
        {
            //cargamos plantilla
//            pr(validation_errors());
            $this->load->view("sesion/login.tpl.php", $data);
        }
//        $this->output->enable_profiler(true);
    }

    function captcha()
    {
        new_captcha();
    }

    function dashboard()
    {
        redirect(site_url() . '/perfil');
        /*
          $this->load->model('Menu_model', 'menu');
          $usuario = $this->session->userdata('usuario');
          if (isset($usuario['id_usuario']))
          {
          $menu = $this->menu->get_menu_usuario($usuario['id_usuario']);
          $this->template->setNav($menu);
          $perfil = $this->load->view('tc_template/perfil.tpl.php', $usuario, true);
          $this->template->setPerfilUsuario($perfil);
          $output = array();
          $main_content = $this->load->view('welcome/dashboard', $output, true);
          $this->template->setMainContent($main_content);
          $this->template->getTemplate();
          }
         */
        $this->load->library('seguridad');
        $cadena = 'token311091488token';
        echo $this->seguridad->encrypt_sha512($cadena);
        echo '<br>';
        $usuario = $this->session->userdata('usuario');
        pr($usuario);
        echo '<br>';
        echo '<a href="' . site_url('welcome/cerrar_sesion') . '">Cerrar sesion</a>';
        $this->output->enable_profiler(true);
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
        $this->load->view("sesion/recuperar_password.tpl.php", $datos);
    }

    public function registro()
    {
        $output['formulario_completo'] = true;
        $output["texts"] = $this->lang->line('form_registro'); //textos del formulario
        if ($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro_usuario'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $this->load->model('Administracion_model', 'administracion');
                $data = array(
                    'matricula' => $this->input->post('matricula', TRUE),
                    'delegacion' => $this->input->post('delegacion', TRUE),
                    'email' => $this->input->post('email', true),
                    'password' => $this->input->post('pass', TRUE),
                    'grupo' => Administracion_model::DOCENTE,
                    'registro_usuario' => true
                );
                $output['registro_valido'] = $this->usuario->nuevo($data);
            }
            $output['formulario_completo'] = false;
        }
        $this->load->model('Catalogo_model', 'catalogo');
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(), 'clave_delegacional', 'nombre');
        $vista = $this->load->view('sesion/registro.tpl.php', $output, true);
        if ($output['formulario_completo'])
        {
            $this->template->set_titulo_modal($output['texts']['titulo']);
            $this->template->set_cuerpo_modal($vista);
            $this->template->get_modal();
        } else
        {
            echo $vista;
        }
    }

    public function manteminiemto(){
        echo 'En mantenimiento';
    }

    public function mesa_ayuda(){
        $this->template->set_cuerpo_modal('Horario de mesa...');
        $this->template->get_modal();
    }
}
