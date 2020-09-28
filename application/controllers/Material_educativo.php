<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : CAL
 * */
class Material_educativo extends Core_secciones {

    function __construct() {
        parent::__construct();
        $this->elementos_actividad['rutas_generales_js'] = null;
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/material_educativo/carga_actividad';

        $this->seccion = En_seccion_actividad_docente::MATERIAL_EDUCATIVO; //La sección general que manejará el controlador, indispensable para cargar datos
    }

}
