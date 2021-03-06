<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 12052017
 * @author      : LEAS
 * */
class Perfil extends Informacion_docente {

    function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
    }

    public function index($id_docente = 1) {
//        $datos_elemento_seccion = $this->get_datos_actividad_docente_c($id_docente, NULL, NULL, TRUE);
        $id_docente  = $this->get_datos_sesion(En_datos_sesion::ID_DOCENTE);
        //$this->benchmark->mark('code_start');
        $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente);
//        pr($datos_elemento_seccion);
        $this->load->library('template_item_perfil');
        $this->template_item_perfil->set_registro_censo($datos_elemento_seccion); //Agrega registros
        $datos_files_js = $this->get_files_js_formularios_c($id_docente);
        $this->template_item_perfil->set_files_js_formularios($datos_files_js);
        $this->load->model("Docente_model", "dm");
        /* carga datos generales */
        $datos_generales = $this->dm->get_datos_generales($id_docente);
        if (!empty($datos_generales)) {
            $this->load->library('curp', array('curp' => $datos_generales['curp'])); //Ingresa datos del curp
            $datos_generales['edad'] = $this->curp->getEdad(); //Calcula la edad del usuario
            $this->template_item_perfil->set_datos_generales($datos_generales); //Información general del docente 
        }
        /* Cargar imagen de perfil */
        $datos_imagen_docente = $this->dm->get_imagen_perfil($id_docente); //Obtener imagen del docente
        $this->template_item_perfil->set_datos_imagen($datos_imagen_docente);
        /* carga datos imss */
        $this->template_item_perfil->set_datos_imss($this->dm->get_historico_datos_generales($id_docente)); //Información IMSS
        //Ejecutar datos de registro de perfil
        $vista = $this->template_item_perfil->get_template_registro(
                $this->template_item_perfil->get_vistas_regisatros_censo()
        );

        $this->template->setMainContent($vista);
        $this->template->getTemplate();
        $this->benchmark->mark('code_end');
        echo $this->benchmark->elapsed_time('code_start', 'code_end');

//        $this->output->set_profiler_sections($sections);
//        $this->output->enable_profiler(TRUE);
        //$this->output->parse_exec_vars = TRUE;
        //$this->output->append_output($this->benchmark->memory_usage());
    }

    public function perfil_impresion($id_docente = null) {
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
            //        pr($datos_elemento_seccion);
            $this->load->library('template_item_perfil');
            $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_PERFIL);//Tipo de vista
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
                    $this->template_item_perfil->get_vistas_regisatros_censo(null, 
                    'perfil/item_ficha_actividad_impresion.php', 
                    'perfil/item_datos_generales_impresion.php', 
                    'perfil/item_datos_imss_impresion.php', 
                    'perfil/item_carrusel_impresion.php', 
                    'perfil/tab_perfil_impresion.php'), 'perfil/perfil_impresion.tpl.php'
                    //$this->template_item_perfil->get_vistas_regisatros_censo(), 'perfil/perfil_impresion.tpl.php'
            );*/
            $vista = $this->template_item_perfil->get_template_registro(
                $this->template_item_perfil->get_vistas_regisatros_censo_inicio(null, 
                '/perfil/inicio/item_ficha_actividad_impresion', 
                '/perfil/inicio/item_datos_generales_impresion', 
                '/perfil/inicio/item_datos_imss_impresion', 
                '/perfil/inicio/item_carrusel_impresion', 
                '/perfil/inicio/tab_perfil_impresion'),                 
                'perfil/perfil_impresion.tpl.php',null, $id_docente                                
        );

            $this->template->setMainContent($vista);
            $this->template->getTemplate(true, 'tc_template/impresion.tpl.php');
        }
//        $this->output->set_profiler_sections($sections);
//        $this->output->enable_profiler(TRUE);
        //$this->benchmark->mark('code_end');
        //echo $this->benchmark->elapsed_time('code_start', 'code_end');
        //$this->output->parse_exec_vars = TRUE;
        //$this->output->append_output($this->benchmark->memory_usage());
    }


    public function docente_inicio($id_docente = null) {
        
        //pr($this->session->userdata('usuario'));
//        $datos_elemento_seccion = $this->get_datos_actividad_docente_c($id_docente, NULL, NULL, TRUE);
        //$this->benchmark->mark('code_start');
        //Sesión de usuario obtiene la sesión de la cuenta
        $datos_sesion = $this->get_datos_sesion();
        //pr($datos_sesion);
        $mostrar_datos_docente = true;
        if ($datos_sesion) {//Valida los datos de la sesión o la información de la sesíon
            if (is_null($id_docente)) {
                $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
                $is_alias_sesion = $datos_sesion[En_datos_sesion::IS_ALIAS_SESION];
                if($is_alias_sesion){                    
                    $mostrar_datos_docente = false;
                }

            }
            $datos_elemento_seccion = $this->get_detalle_registros_censo($id_docente);
            //JS para renderizar formularios e información del docente principalmente
            //pr($datos_elemento_seccion);
            $this->load->library('template_item_perfil');
            $this->template_item_perfil->set_tipoVistaDocente(Template_item_perfil::VIEW_DOCENTE);//Tipo de vista
            $this->template_item_perfil->set_registro_censo($datos_elemento_seccion); //Agrega registros
            $this->template_item_perfil->set_mostrar_datos_docente($mostrar_datos_docente); //Agrega registros
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
                null                                
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

}
