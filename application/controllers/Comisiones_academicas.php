<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : CAL
 * */
class Comisiones_academicas extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('assets/js/docente/actividad_docente/actividad_docente.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/comisiones_academicas/carga_actividad';

        $this->seccion = En_seccion_actividad_docente::COMISIONES_ACADEMICAS; //La sección general que manejará el controlador, indispensable para cargar datos
    }

}
