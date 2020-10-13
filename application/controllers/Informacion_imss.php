<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Informacion_imss extends Informacion_docente {

    function __construct() {
        parent::__construct();
    }

    public function index($id_docente = 1) {
        $vista = $this->carga_vista_completa($id_docente); //Genera vista inicial
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    public function datos_siap($id_docente = 1) {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
            $this->load->model("Catalogo_model", "cm");
            $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');

            $cuerpo_modal = $this->load->view('docente/informacion_general/form_info_imss.php', $data_formulario, TRUE);

            $pie_modal = $this->load->view('docente/informacion_general/btn_guardar_editar_datos_siap.php', $data_formulario, TRUE);
            $this->template->set_titulo_modal($string_value['titilo_modal']);
            $this->template->set_cuerpo_modal($cuerpo_modal);
            $this->template->set_pie_modal($pie_modal);
            $this->template->get_modal();
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function get_id_categoria_departamento($clave_departamento, $clave_categoria) {
        $this->load->model('Catalogo_model', 'cc'); //Carga model categoria
        $result_consulta['categoria'] = $this->cc->get_datos_categoria($clave_categoria);
        $result_consulta['departamento'] = $this->cc->get_datos_departamento($clave_departamento);
        if (!empty($result_consulta['categoria'])) {
            $result['id_categoria'] = $result_consulta['categoria'][0]['id_categoria'];
        }
        if (!empty($result_consulta['departamento'])) {
            $result['id_departamento_instituto'] = $result_consulta['departamento'][0]['id_departamento_instituto'];
        }
//        pr($result_consulta);
        return $result;
    }

    public function actualiza_datos_imss() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $this->load->model('Docente_model', 'dm'); //Carga model
            $datos_sesion = $this->get_datos_sesion(); //Obtiene datos de la session
            $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            $matricula = $datos_sesion[En_datos_sesion::MATRICULA];
            if ($this->input->post()) {
                $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
                $data_post = $this->input->post(null, true);
//Reglas de validación
                $rules = $this->config->load('form_validation'); //Cargar archivo con validaciones
                $rules_datos_siap = $this->config->item('datos_siap');
                $this->form_validation->set_rules($rules_datos_siap);
                if ($this->form_validation->run()) {//Valida reglas de validación
                    $this->load->library('empleados_siap');
                    $result_docente_imss = $this->empleados_siap->buscar_usuario_siap($data_post['clave_delegacional'], $matricula);
                    if ($result_docente_imss['tp_msg'] == En_tpmsg::SUCCESS) {//Existe el usuario si es satisfactoria la busqueda
                        $datos_siap = $result_docente_imss['empleado'];
                        $resultado_categoria_departamento = $this->get_id_categoria_departamento($datos_siap['adscripcion'], $datos_siap['emp_keypue']);                        
                        $result = $this->dm->update_datos_imss($id_docente, $resultado_categoria_departamento['id_departamento_instituto'], $datos_siap['delegacion'], $resultado_categoria_departamento['id_categoria'], $datos_siap);
                        //Recarga la información de la actualización
                        $result['html'] = $vista = $this->carga_vista_completa($id_docente); //Genera vista inicial
                        echo json_encode($result); //Códifica en JSON
                    } else {//No se encontraron resultados
                        $result['tp_msg'] = En_tpmsg::WARNING;
                        $result['mensaje'] = $string_value['resp_siap_no_se_encontro_info'];
                        //Recarga la información de la actualización
                        $data_formulario = $this->dm->get_historico_datos_generales($id_docente);
                        $this->load->model("Catalogo_model", "cm");
                        $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
                        $result['html'] = $this->load->view('docente/informacion_general/form_info_imss.php', $data_formulario, TRUE);
                        echo json_encode($result); //Códifica en JSON
                    }
                    exit();
                }
            }
            $data_formulario = $this->dm->get_historico_datos_generales($id_docente);
            $this->load->model("Catalogo_model", "cm");
            $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(null, array('oficinas_centrales' => true)), 'clave_delegacional', 'nombre');
            $this->load->view('docente/informacion_general/form_info_imss.php', $data_formulario, FALSE);
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function carga_vista_completa() {
        $id_docente = $this->get_datos_sesion(En_datos_sesion::ID_DOCENTE); //Obtiene datos de la session id usuario especificamente
        $this->load->model("Catalogo_model", "cm");
        $this->load->model("Docente_model", "dm");
        $data_formulario = $this->dm->get_historico_datos_generales($id_docente);
//        pr($data_formulario);
        $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(), 'clave_delegacional', 'nombre');

        $data['formulario_imss'] = $this->load->view('docente/informacion_general/detalle_info_imss.php', $data_formulario, TRUE);
        $output['docente'] = $this->dm->get_datos_generales($id_docente);
        //Cálcula la edad del docente
        $this->load->library('curp', array('curp' => $output['docente']['curp']));
        $output['docente']['edad'] = $this->curp->getEdad();
        $this->load->model("Catalogo_model", "cm");
        $output['estado_civil'] = dropdown_options($this->cm->get_estado_civil(), 'id_estado_civil', 'estado_civil');
        $output['fase_carrera_docente'] = dropdown_options($this->cm->get_fase_carrera_docente(), 'id_docente_carrera', 'descripcion');
        $output['carrera_docente'] = dropdown_options([['id_cuenta_carrera'=>1,'value'=>'Si'], ['id_cuenta_carrera'=>2,'value'=>'No']], 'id_cuenta_carrera', 'value');

        $data['formulario_general'] = $this->load->view('docente/informacion_general/form_info_gral.php', $output, TRUE);
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
        if (isset($output['docente']['fecha'])) {
            $data['fecha_actualizacion'] = $output['docente']['fecha'];
        }

//        Carga imagen del docente
        /* Cargar imagen de perfil */
        $datos_imagen['elementos_imagen'] = $this->dm->get_imagen_perfil($id_docente); //Obtener imagen del docente
        $data['imagen_docente'] = $this->load->view('docente/informacion_general/carga_imagen_docente.php', $datos_imagen, TRUE);
        // fin de imagen docente
        //*** Titulo del template
        $data['titulo_seccion'] = $string_value['title_seccion'];

        //***********************
        $vista = $this->load->view('docente/informacion_general/template_informacion_docente.tpl.php', $data, TRUE);
        return $vista;
    }

    public function actualiza_datos_generales() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $this->load->model('Docente_model', 'dm'); //Carga model
            if ($this->input->post()) {
                $datos_sesion = $this->get_datos_sesion(); //Obtiene datos de la session
                $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
                $data_post = $this->input->post(null, true);
                
                $this->load->model("Catalogo_model", "cm");
                $output['estado_civil'] = dropdown_options($this->cm->get_estado_civil(), 'id_estado_civil', 'estado_civil');
                $output['fase_carrera_docente'] = dropdown_options($this->cm->get_fase_carrera_docente(), 'id_docente_carrera', 'descripcion');
                $output['carrera_docente'] = dropdown_options([['id_cuenta_carrera'=>1,'value'=>'Si'], ['id_cuenta_carrera'=>2,'value'=>'No']], 'id_cuenta_carrera', 'value');
//Reglas de validación
                $this->config->load('form_validation'); //Cargar archivo con validaciones
                $rules_datos_generales = $this->config->item('datos_generales');
                if(isset($data_post['carrera_docente']) && $data_post['carrera_docente'] == 2){
                    //pr($rules_datos_generales[array_key_last($rules_datos_generales)]);
                    unset($rules_datos_generales[array_key_last($rules_datos_generales)]);
                    
                    $data_post['fase_carrera_docente'] = null;
                }
                
                //pr($data_post);
                //pr($rules_datos_generales);
                
                //pr($rules_datos_generales);
                $this->form_validation->set_rules($rules_datos_generales);
                if ($this->form_validation->run()) {//Valida reglas de validación
                    $result = $this->dm->update_datos_generales($id_docente, $data_post);
                    //Recarga la información de la actualización
                    $output['docente'] = $this->dm->get_datos_generales($id_docente);
                    
                    
                    $result['html'] = $this->load->view('docente/informacion_general/form_info_gral.php', $output, TRUE);
                    echo json_encode($result); //Códifica en JSON
                    exit();
                }

                $output['data_post'] = $data_post;
                $output['docente'] = $this->dm->get_datos_generales($id_docente);
                $this->load->view('docente/informacion_general/form_info_gral.php', $output, FALSE);
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function actualiza_imagen() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $data_post = $this->input->post(null, true);
            if ($this->input->post()) {
                /* Datos basicos del usuario en variable de session */
                $user_data = $this->get_datos_sesion(); //Obtiene los datos de session del usuario
                $matricula_docente = $user_data[En_datos_sesion::MATRICULA];
                $id_usuario = $user_data[En_datos_sesion::ID_USUARIO];
                /*                 * * Fin********** */
                $nombre_file = $matricula_docente . '_' . time(); //Nombre del archivo en el sistema nuevo
                $this->load->model('Files_model', 'fm'); //Carga model files
                if (!empty($data_post['file_cve'])) {//Valida si va actualizar archivo
                    //Actualiza archivo
                    $id_file = base64_decode($data_post['file_cve']); //Obtiene id del archivo
                    $attrib_file = $this->fm->get_file($id_file); //Obtiene datos del archivo solicitado por POST
                    if (!empty($attrib_file)) {//Valida que el archivo no regrese vacio, de otra forma no deberia guardar nada
                        $ruta = $attrib_file[0]['ruta'];
                        $nombre_file_actual = $attrib_file[0]['nombre_fisico'] . '.' . $attrib_file[0]['nombre_extencion'];
                        $respuesta = $this->save_file('perfil', $matricula_docente, $nombre_file); //guarda el archivo imagen
//                        pr($respuesta);
                        $save_file = $respuesta;
//                        pr($respuesta);
                        if ($respuesta['tp_msg'] == En_tpmsg::SUCCESS) {//Almacena archivo nuevo, si es correcto, actualiza el nombre en la base de datos
                            $ruta_nuevo_file = str_replace('.', '', $save_file['upload_path']); //Elimina la ruta con punto del archivo
                            $type_file = $_FILES['userfile']['name'];
                            $extencion = explode('.', $type_file)[1];
//                            pr($extencion);
                            $respuesta = $this->fm->update_file($id_file, $nombre_file, $ruta_nuevo_file, $extencion);
                            if ($respuesta['tp_msg'] == En_tpmsg::SUCCESS) {
                                $this->delete_file($attrib_file[0]['ruta'], $attrib_file[0]['nombre_fisico'], $attrib_file[0]['nombre_extencion']); //Elimina el archivo viejo
                            } else {
                                $ruta = $save_file['upload_path'];
                                $this->delete_file($ruta_nuevo_file, $nombre_file, $extencion); //Elimina archivo nuevo, ya que hubo un error al guardar en la base de datos
                            }
                        } else {
                            unset($respuesta['upload_path']); //Elimina respuesta del path  del archivo
                        }
                    } else {//Guarda una nueva imagen de perfil, considerar que esto dificilmente pasará(psará solo si directamente se borra el archivo de la base de datos)
                        $respuesta = $this->save_file('perfil', $matricula_docente, $nombre_file); //guarda el archivo imagen
                        $save_file = $respuesta;
                        if ($respuesta['tp_msg'] == En_tpmsg::SUCCESS) {//Almacena archivo nuevo, si es correcto, actualiza el nombre en la base de datos
                            $ruta_nuevo_file = str_replace('.', '', $save_file['upload_path']); //Elimina la ruta con punto del archivo
                            $type_file = $_FILES['userfile']['name'];
                            $extencion = explode('.', $type_file)[1];
//                            pr($extencion);
                            $respuesta = $this->fm->insert_file($id_usuario, $nombre_file, $ruta_nuevo_file, $extencion);
                            if ($respuesta['tp_msg'] != En_tpmsg::SUCCESS) {
                                $ruta = $save_file['upload_path'];
                                $this->delete_file($ruta_nuevo_file, $nombre_file, $extencion); //Elimina archivo nuevo, ya que hubo un error al guardar en la base de datos
                            }
                        }
                    }
                } else {////Valida guardar archivo
                    $respuesta = $this->save_file('perfil', $matricula_docente, $nombre_file); //guarda el archivo imagen
                    $save_file = $respuesta;
                    if ($save_file['tp_msg'] == En_tpmsg::SUCCESS) {//Almacena archivo nuevo, si es correcto, actualiza el nombre en la base de datos
                        $ruta_nuevo_file = str_replace('.', '', $save_file['upload_path']); //Elimina la ruta con punto del archivo
                        $type_file = $_FILES['userfile']['name'];
                        $extencion = explode('.', $type_file)[1];
//                            pr($extencion);
                        $respuesta = $this->fm->insert_file($id_usuario, $nombre_file, $ruta_nuevo_file, $extencion);
                        if ($respuesta['tp_msg'] != En_tpmsg::SUCCESS) {
                            $ruta = $save_file['upload_path'];
                            $this->delete_file($ruta_nuevo_file, $nombre_file, $extencion); //Elimina archivo nuevo, ya que hubo un error al guardar en la base de datos
                        }
                    }
                }
                $tmp = json_encode($respuesta);
                echo $tmp;
                exit();

//                pr($data_post);
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function elimina_imagen() {
        if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
            $data_post = $this->input->post(null, true);
            if ($this->input->post()) {
                /* Datos basicos del usuario en variable de session */
                $user_data = $this->get_datos_sesion(); //Obtiene los datos de session del usuario
                $id_usuario = $user_data[En_datos_sesion::ID_USUARIO];
                $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GUARDAR_ACTUALIZAR));
                if (!is_null($id_usuario) AND ! empty($data_post['file_cve'])) {//Valida si va actualizar archivo
                    $this->load->model('Files_model', 'fm'); //Carga model files
                    //Actualiza archivo
                    $id_file = base64_decode($data_post['file_cve']); //Obtiene id del archivo decodificando base64
                    $attrib_file = $this->fm->get_file($id_file); //Obtiene datos del archivo solicitado por POST
//                    pr($attrib_file);
//                    exit();
                    if (!empty($attrib_file)) {//Valida que efectivamente exista el archivo
                        $this->load->model('Usuario_model', 'um'); //Carga model files
                        $delete_file_database = $this->um->delete_foto_perfil($id_usuario, $id_file); //Elimina el archivo de la base de datos
                        if ($delete_file_database['tp_msg'] == En_tpmsg::SUCCESS) {//Valida que se elimino el registro de archivos
                            $result_delete_file = $this->delete_file($attrib_file[0]['ruta'], $attrib_file[0]['nombre_fisico'], $attrib_file[0]['nombre_extencion']);
                            if ($result_delete_file) {//Valida que se elimino el registro de archivos
                                $respuesta['tp_msg'] = En_tpmsg::SUCCESS;
                                $respuesta['mensaje'] = $string_value['success_imagen'];
                            } else {
                                $respuesta['tp_msg'] = En_tpmsg::DANGER;
                                $respuesta['mensaje'] = $string_value['error_delete_imagen'];
                            }
                        }
                        //Elimina el archivo de la imagen de perfil
                        unset($respuesta['upload_path']); //Elimina respuesta del path  del archivo
                    } else {//Guarda una nueva imagen de perfil, considerar que esto dificilmente pasará(psará solo si directamente se borra el archivo de la base de datos)
                        $respuesta['tp_msg'] = En_tpmsg::DANGER;
                        $respuesta['mensaje'] = $string_value['error_general'];
                    }
                } else {////Valida guardar archivo
                    $respuesta['tp_msg'] = En_tpmsg::DANGER;
                    $respuesta['mensaje'] = $string_value['error_general'];
                }
                $tmp = json_encode($respuesta);
                echo $tmp;
                exit();

//                pr($data_post);
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

}
