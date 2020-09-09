<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Investigacion extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('assets/js/docente/investigacion/investigacion_foro.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/investigacion/carga_actividad';

        $this->seccion = En_seccion_actividad_docente::INVESTIGACION; //La sección general que manejará el controlador, indispensable para cargar datos
    }

    public function carga_formulario($elemento_seccion) {
        
    }

}
