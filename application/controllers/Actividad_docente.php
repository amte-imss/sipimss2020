<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @fecha	: 09052017
 * @author      : LEAS
 * */
class Actividad_docente extends Core_secciones {
    const 
        EXPERIENCIA_DOCENTE = 6
    ;
    function __construct() {
        parent::__construct();
        $this->elementos_actividad['rutas_generales_js'] = array('docente/actividad_docente/actividad_docente.js');
        $this->elementos_actividad['ruta_controler_editar_registro'] = '/actividad_docente/carga_actividad';
        $this->seccion = En_seccion_actividad_docente::ACTIVIDAD_DOCENTE; //La sección general que manejará el controlador, indispensable para cargar datos
    }

   
    
    /**
     * @author LEAS
     * @fecha 08/06/2017
     * @param type $elemento_seccion identificador elemento de sección que define a un formulario
     * @return type Description Código HTML del formulario definido por alguna sección en la base de datos
     * @descripcion Lo invoca el árbol de secciones
     */
    public function mostrar_formulario($elemento_seccion) {
        if ($this->input->is_ajax_request()) {
            //pr("Estamos aquí" . $elemento_seccion);
            //            $datos_sesion = $this->get_datos_sesion();
            //            $id_docente = $datos_sesion[En_datos_sesion::ID_DOCENTE];
            /*             * ***Comentar de formulario**** */
            $formulario = $this->get_campos_formulario($elemento_seccion); //Obtiene tosdos los campos de formulario
            //            pr($formulario);
            $label_formulario = '';
            if (!empty($formulario)) {
                $label_formulario = $formulario[0]['lbl_formulario'];
            }
            $catalogos_form = $this->get_elementos_catalogos_formulario($elemento_seccion);
            $data_form = $this->template->get_elements_seccion(); //importante colocar al inicio, ya que Obtiene los elementos de la seccion actual
            $data_form['formulario_campos'] = $formulario;
            $data_form['formulario'] = (!empty($formulario)) ? $formulario[0]['id_formulario'] : null;
            $data_form['id_elementos_seccion'] = $elemento_seccion;
            $data_form['catalogos'] = $catalogos_form;
            $data_form['rutas_generales_js'] = $this->elementos_actividad['rutas_generales_js'];
            //            $this->template->set_comprobante(); //Envia parametros de comprobante
            $this->template->set_boton_guardar(array('id_censo' => null, 'formulario' => $data_form['formulario'])); //Genera el botón de guardar un o actualizar por default
            $datos_sesion = $this->get_datos_sesion();
            $registro_censo = (isset($datos_sesion['registro_censo']))?$datos_sesion['registro_censo']:false;
            //pr($registro_censo);
            if($registro_censo){
                $data_form['boton_submit'] = $this->template->get_boton_guardar(); //Asigna comprobante
            } else {                
                $data_form['boton_cancelar'] = ''; //Asigna comprobante
            }
            //            $data_form['componente_comprobante'] = $this->template->get_comprobante(); //Asigna comprobante
            $tabla_cursos = $this->load->view('tc_template/secciones/actividad_docente/exp_docente.php', $data_form, TRUE);
             //pr($data_form);
             //pr($this->elementos_actividad['formulario_view']);
            $this->load->view($this->elementos_actividad['formulario_view'], $data_form, FALSE);
//            $this->output->enable_profiler(TRUE);
            /*             * ***fin de formulario**** */
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

}
