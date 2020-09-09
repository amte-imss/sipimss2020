<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version     : 1.0.0
 * @author      : LEAS
 * */
class Publicaciones extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('docente/publicaciones/publicaciones.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/publicaciones/carga_actividad';

        $this->seccion = En_seccion_actividad_docente::PUBLICACIONES; //La sección general que manejará el controlador, indispensable para cargar datos
    }

    
//    public function index() {
//        $this->load->model('Catalogo_model', 'cmcat');
//        $res = $this->cmcat->get_busca_opciones_catalogo('1', 'nuevo');
//        pr($res);
//    }

}
