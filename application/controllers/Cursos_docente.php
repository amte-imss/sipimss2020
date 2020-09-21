<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version     : 1.0.0
 * @author      : LEAS
 * @Date        : 12/09/2020
 * */
class Cursos_docente extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('docente/cursos_docente/cursos_docente.js');
        
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/cursos_docente/carga_actividad';
        
        $this->seccion = En_seccion_actividad_docente::CURSOS_DOCENTE; //La sección general que manejará el controlador, indispensable para cargar datos
    }
    
    
    //    public function index() {
//        $this->load->model('Catalogo_model', 'cmcat');
//        $res = $this->cmcat->get_busca_opciones_catalogo('1', 'nuevo');
//        pr($res);
//    }

}
