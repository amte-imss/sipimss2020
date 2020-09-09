<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Usuario
 *
 * @author chrigarc
 */
class Usuario extends MY_Controller
{

    const LIMIT = 10, LISTA = 'lista', BASICOS = 'basico', PASSWORD = 'password',
            NIVELES_ACCESO = 'niveles', NO_SIAP = 'no_siap', SIAP = 'siap', NO_IMSS = 'no_imss',
            STATUS_ACTIVIDAD = 'actividad';

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('seguridad');
        $this->load->library('empleados_siap');
        $this->load->library('form_validation');
        $this->load->model('Usuario_model', 'usuario');
        $this->template->setTitle('Usuarios');
    }

    public function index()
    {
        redirect('usuario/get_usuarios/');
    }

    public function get_usuarios($usuario = '')
    {
        $output = [];
        switch($usuario)
        {
            case Usuario::LISTA:
                $get = $this->input->get(null, true);
                $this->lista_usuarios($get);
                break;
            case '':
                $view = $this->load->view('usuario/get_usuarios_v2.tpl.php', $output, true);
                $this->template->setMainContent($view);
                $this->template->getTemplate();
                break;
            default:
                $this->muestra_usuario($usuario);
                break;
        }
    }

    private function muestra_usuario($usuario = 0)
    {
        $params['where'] = array(
           'usuarios.id_usuario' => $usuario
        );
        $params['select'] = array(
           'usuarios.id_usuario', 'docentes.matricula', 'docentes.nombre',
           'docentes.apellido_p', 'docentes.apellido_m', 'sexo',
           'docentes.fecha_nacimiento', 'D.id_departamento_instituto', 'D.nombre departamento',
           'F.id_categoria', 'F.nombre categoria', 'C.cve_tipo_contratacion',
           'docentes.curp', 'docentes.rfc', 'docentes.telefono_particular',
           'docentes.telefono_laboral', 'docentes.email', 'usuarios.activo usuario_activo',
           'D.clave_departamental', 'F.clave_categoria'
        );
        $resultado = $this->usuario->get_usuarios($params);
        if (count($resultado) == 1)
        {
           $output['usuario'] = $resultado[0];
        }
        $output['datos_basicos'] = $this->load->view('usuario/datos_basicos.tpl.php', $output, true);
        $output['grupos_usuario'] = $this->usuario->get_niveles_acceso($output['usuario']['id_usuario']);
        $output['campo_password'] = $this->load->view('usuario/campo_password.tpl.php', $output, true);
        $output['view_grupos_usuario'] = $this->load->view('usuario/tabla_niveles.tpl.php', $output, true);
        $output['campo_niveles_acceso'] = $this->load->view('usuario/niveles_acceso.tpl.php', $output, true);
        $view = $this->load->view('usuario/usuario.tpl.php', $output, true);
        $this->template->setMainContent($view);
        $this->template->getTemplate();
    }

    private function lista_usuarios(&$params = [])
    {

        $filtros['limit'] = isset($params['pageSize'])? $params['pageSize']:Usuario::LIMIT;
        $filtros['offset'] = isset($params['pageIndex'])?  ($filtros['limit']*($params['pageIndex']-1)):0;

        $filtros['select'] = array(
            'usuarios.id_usuario', 'coalesce(docentes.matricula, usuarios.username) matricula',
            'concat(docentes.nombre, $$ $$, docentes.apellido_p, $$ $$, apellido_m) nombre',
            'G.nombre delegacion', 'E.nombre unidad'
        );
        $filtros['like'] = $this->genera_filtros($params);
        $usuarios['data'] = $this->usuario->get_usuarios($filtros);
        $filtros['total'] = true;
        $total = $this->usuario->get_usuarios($filtros)[0]['total'];
        $usuarios['length'] = $total;

        header('Content-Type: application/json; charset=utf-8;');
        echo json_encode($usuarios);
    }

    private function genera_filtros($params)
    {
        $filtros = [];
        foreach ($params as $key => $value)
        {
            if($value != '')
            {
                switch ($key)
                {
                    case 'matricula':
                        $filtros['usuarios.username'] = $value;
                        break;
                    case 'delegacion':
                        $filtros['G.nombre'] = $value;
                        break;
                    case 'unidad':
                        $filtros['E.nombre'] = $value;
                        break;
                    case 'nombre':
                        $filtros['nombre'] = $value;
                        break;
                    default:
                        # code...
                        break;
                }
            }

        }
        return $filtros;
    }

    public function editar($id_usuario = 0, $tipo = Usuario::BASICOS)
    {
        $salida = [];
        $view = '';
        if ($this->input->post() && $this->input->is_ajax_request())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            switch ($tipo)
            {
                case Usuario::BASICOS:
                    $view = $this->get_datos_basicos($id_usuario);
                    $validations = $this->config->item('form_actualizar'); //Obtener validaciones de archivo general
                    break;
                case Usuario::PASSWORD:
                    $view = $this->load->view('usuario/campo_password.tpl.php', array(), true);
                    $validations = $this->config->item('form_user_update_password'); //Obtener validaciones de archivo general
                    break;
                case Usuario::NIVELES_ACCESO:
                    $validations = $this->config->item('form_niveles_acceso_usuario');
                    $view = $this->get_niveles($id_usuario);
                    break;
                case Usuario::STATUS_ACTIVIDAD:
                   $validations = $this->config->item('form_status_actividad_usuario');
                   break;
            }
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $params = $this->input->post(null, true);
                $params['id_usuario'] = $id_usuario;
                $salida['tp_msg'] = $this->usuario->update($tipo, $params);
                $output['status'] = $salida;
                $salida['status'] = $salida['tp_msg'];
            } else
            {
                $salida['tp_msg'] = En_tpmsg::DANGER;
                $salida['msg'] = validation_errors();
                $output['status'] = false;
            }
            switch ($tipo)
            {
                case Usuario::BASICOS:
                    $view = $this->get_datos_basicos($id_usuario, $output);
                    break;
                case Usuario::PASSWORD:
                    $view = $this->load->view('usuario/campo_password.tpl.php', $output, true);
                    break;
                case Usuario::NIVELES_ACCESO:
                    $view = $this->get_niveles($id_usuario);
                    break;
            }
        }
        $salida['html'] = $view;
        echo json_encode($salida);
    }

    private function get_datos_basicos($id_usuario = 0, &$output = [])
    {
        $params['where'] = array(
            'usuarios.id_usuario' => $id_usuario
        );
        $params['select'] = array(
            'usuarios.id_usuario', 'docentes.matricula', 'docentes.nombre',
            'docentes.apellido_p', 'docentes.apellido_m', 'sexo',
            'docentes.fecha_nacimiento', 'D.id_departamento_instituto', 'D.nombre departamento',
            'F.id_categoria', 'F.nombre categoria', 'C.cve_tipo_contratacion',
            'docentes.curp', 'docentes.rfc', 'docentes.telefono_particular',
            'docentes.telefono_laboral', 'docentes.email', 'D.clave_departamental', 'F.clave_categoria'
        );
        $resultado = $this->usuario->get_usuarios($params);
        if (count($resultado) == 1)
        {
            $output['usuario'] = $resultado[0];
        }
        return $this->load->view('usuario/datos_basicos.tpl.php', $output, true);
    }

    private function get_niveles($id_usuario = 0)
    {
        $output['grupos_usuario'] = $this->usuario->get_niveles_acceso($id_usuario);
        $output['view_grupos_usuario'] = $this->load->view('usuario/tabla_niveles.tpl.php', $output, true);
        return $this->load->view('usuario/niveles_acceso.tpl.php', $output, true);
    }

    public function nuevo($tipo = Usuario::SIAP)
    {
        if ($this->input->post())
        {
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_registro_'.$tipo); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE)
            {
                $data = $this->input->post(null, true);
                $data = $this->filtra_datos_form($data);
                $output['post'] = $data;
                $output['registro_valido'] = $this->usuario->nuevo($data, $tipo);
            }else{
                //pr($this->form_validation)
                pr('no valido');
            }
        }
        $this->load->model('Catalogo_model', 'catalogo');
        $this->load->model('Administracion_model', 'administrador');
        $output['tipo_registro'] = $tipo;
        $output['delegaciones'] = dropdown_options($this->catalogo->get_delegaciones(null, array('oficinas_centrales'=>true)), 'clave_delegacional', 'nombre');
        $output['nivel_atencion'] = dropdown_options($this->administrador->get_niveles_acceso(), 'id_grupo', 'nombre');
        $main_content = $this->load->view('usuario/nuevo.tpl.php', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

    private function filtra_datos_form($datos = [])
    {
        $nuevo = [];
        foreach ($datos as $key => $value) {
            $nueva_llave = $key;
            $nueva_llave = str_replace('1', '', $nueva_llave);
            $nueva_llave = str_replace('2', '', $nueva_llave);
            $nuevo[$nueva_llave] = $value;
        }
        return $nuevo;
    }

    public function carga_usuarios()
    {
        $view = [];
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['allowed_types'] = 'csv';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            $view['status']['result'] = false;
            if ($this->upload->do_upload())
            { //Se ejecuta la validación de datos
                $this->load->library('csvimport');
                $file_data = $this->upload->data();     //BUSCAMOS LA INFORMACIÓN DEL ARCHIVO CARGADO
                $file_path = './uploads/' . $file_data['file_name'];         // CARGAMOS LA URL DEL ARCHIVO

                if ($this->csvimport->get_array($file_path))
                {              // EJECUTAMOS EL METODO get_array() DE LA LIBRERIA csvimport PARA BUSCAR SI EXISTEN DATOS EN EL ARCHIVO Y VERIFICAR SI ES UN CSV VALIDO
                    $csv_array = $this->csvimport->get_array($file_path);   //SI EXISTEN DATOS, LOS CARGAMOS EN LA VARIABLE $csv_array
                    $tipo_registro = $this->input->post('tipo_registro', true);
                    $id_usuario = $this->get_datos_sesion('id_usuario');
                    $view['status'] = $this->usuario->carga_masiva($id_usuario, $tipo_registro, $file_data, $csv_array);
                    //pr($view['status']);
                    //$this->reporte_registro($view['status']);
                } else
                {
                    $view['status']['msg'] = "Formato inválido";
                }
            } else
            {
                $view['status']['msg'] = "Formato inválido";
            }
        }
        if(isset($view['status']['result']) && $view['status']['result'])
        {
            redirect('precarga');
        }else
        {
            // pr($view);
            $main_content = $this->load->view('usuario/formulario_carga_v2.tpl.php', $view, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }
    }

    private function reporte_registro(&$datos)
   {
       $filename = "Registro_" . date("d-m-Y_H-i-s") . ".xls";
      /* header("Content-Type: application/vnd.ms-excel");
       header("Content-Disposition: attachment; filename=$filename");
       header("Pragma: no-cache");
       header("Expires: 0");*/
       echo $this->load->view('usuario/reporte_registro.tpl.php', $datos, TRUE);
       exit();
   }
}
