<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : CAL
 * */
class Becas_comisiones extends Core_secciones {

    function __construct() {
        parent::__construct();

        $this->elementos_actividad['rutas_generales_js'] = array('docente/becas_comisiones/becas_comisiones.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/becas_comisiones/carga_actividad';

        $this->seccion = En_seccion_actividad_docente::BECAS_COMISIONES_LABORALES; //La sección general que manejará el controlador, indispensable para cargar datos
    }

}
