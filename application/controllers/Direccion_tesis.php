<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Direccion_tesis extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('assets/js/docente/direccion_tesis/direccion_tesis.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/direccion_tesis/carga_actividad';
        
        $this->seccion = En_seccion_actividad_docente::DIRECCION_TESIS; //La sección general que manejará el controlador, indispensable para cargar datos
    }

}
