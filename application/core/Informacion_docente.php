<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 30052017
 * @author      : LEAS
 * */
class Informacion_docente extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        date_default_timezone_set('America/Mexico_City');
    }

    public function index() {
        
    }

    /**
     * @author LEAS
     * @fecha 26/05/2017
     * @param type $id_docente $identificador del docente en la base de datos
     * @return array de todos los registros de secciones de censo del
     */
    protected function get_detalle_registros_censo($id_docente) {
        $this->load->model('Formulario_model', 'fm');
        $datos_registro_tmp = $this->fm->get_cross_datos_actividad_docente_completo($id_docente);
        return $datos_registro_tmp;
    }

    /**
     * @author LEAS
     * @fecha 31/08/2017
     * @param type $id_docente
     * @return type
     */
    protected function get_files_js_formularios_c($id_docente) {
        $this->load->model('Formulario_model', 'fm');
        $datos_files_js = $this->fm->get_files_js_formularios($id_docente);
        return $datos_files_js;
    }

    /**
     * @author LEAS
     * @Fecha 23/05/2017
     * @param type $id_elemento_seccion id del elemento sección a buscar
     * @param type $top_tree tope de la sima, o profundidad, es decir,
     * 0 = el elemento raíz; 1= el segundo elemento después del inicio,
     * 2=tercer elemento y así, es preciso señalar que dependera del tamaño
     * de la ramá lo que indica los niveles de profundidad
     *
     */
    protected function get_elemento_seccion_ramas_c($id_elemento_seccion, $top_tree = -1) {
        if ($top_tree == -1 and ! is_null($this->seccion)) {//Tomá el id de la sección actual
            $configuracion_seccion = $this->config->item('config_secciones');
            if (isset($configuracion_seccion['$this->seccion']['nivel_profundidad'])) {//Solo aplica para actividad docente que el primer nivel solo muestra información hasta el nivel segundo
                $top_tree = $configuracion_seccion['$this->seccion']['nivel_profundidad'];
            } else {//Para todas las demás secciones muestra todo el árbol sin excepciones
                $top_tree = 0; //Cero es el nivel más bajo
            }
        }
        $this->load->model("Secciones_model", "csm");
        return $this->csm->get_elemento_seccion_ramas($id_elemento_seccion, $top_tree);
    }

    /**
     * @fecha 20/04/2017
     * @author LEAS
     * @param type $id_docente identificador del usuario docente
     * @param type $id_seccion Identificación de la seccion, es decir,
     * formación docente, actividad docente, investigación, etc.
     * @param type $id_censo Identificador del registro de censo, puede ser null
     * @param type $id_elemento_seccion elementos de una subsección o formulario, datos con dicha estructura
     * @return type Array con todos los campos del formulario solicitado en un cross en vertical de los registros
     */
    protected function get_datos_actividad_docente_c($id_docente = null, $id_seccion = null, $id_censo = null, $id_elemento_seccion = null) {
        if ((!is_null($id_docente) and ( !is_null($id_seccion) || !is_null($id_censo)))) {
            $this->load->model('Formulario_model', 'fm');
            $datos_docente_actividad = $this->fm->get_cross_datos_actividad_docente($id_docente, $id_seccion, $id_censo, $id_elemento_seccion);
            // pr($datos_docente_actividad);
            return $datos_docente_actividad;
        }
        return NULL;
    }

    /**
     * @author LEAS
     * @fecha 02/05/2017
     * @param type $id_censo id del registro censo
     */
    protected function get_detalle_censo_c($id_censo) {//Carga información de la actividad
        $this->load->model('Formulario_model', 'fm');
        $datos_detalle_censo = $this->fm->get_detalle_censo($id_censo);
        if (!is_null($datos_detalle_censo and ! empty($datos_detalle_censo))) {//Valida que exita el registro
            return $datos_detalle_censo[0];
        }
        return NULL; //Retorna null
    }

    protected function get_estados_validacion_censo_c() {
        $this->load->model('Catalogo_model', 'cat');
        return $this->cat->get_estados_validacion_censo();
    }

}
