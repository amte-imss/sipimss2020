<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : LEAS
 * */
class Actividad_docente extends Core_secciones {

    function __construct() {
        parent::__construct();
        $this->elementos_actividad['rutas_generales_js'] = array('docente/actividad_docente/actividad_docente.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/actividad_docente/carga_actividad';
        $this->seccion = En_seccion_actividad_docente::ACTIVIDAD_DOCENTE; //La sección general que manejará el controlador, indispensable para cargar datos
    }

    

}
