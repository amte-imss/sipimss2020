<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ev_curricular extends MY_Controller
{

  const SOLICITUD = 'solicitud';
  function __construct() {
      parent::__construct();
  }

    public function index()
    {

      //$view = $this->load->view('tc_template/ev_curricular',null, true);
      //$view = $this->load->view('ev_curricular/ev_curricular',null, true);
      $view = $this->load->view('ev_curricular/carrera_docente',null, true);
      $this->template->setMainContent($view);
      $this->template->getTemplate();
    }

    public function evaluacion($accion=Ev_curricular::SOLICITUD)
    {
      switch ($accion) {
        case Ev_curricular::SOLICITUD:
          $view = $this->load->view('ev_curricular/ev_curricular',null, true);
          break;
      }
      $this->template->setMainContent($view);
      $this->template->getTemplate();
    }

    // function captcha()
    // {
    //     new_captcha();
    // }

//     public function datos_siap($id_docente = 1) {
//         if ($this->input->is_ajax_request()) { //Solo se accede al método a través de una petición ajax
//             $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
//             $this->load->model("Catalogo_model", "cm");
//             $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(), 'clave_delegacional', 'nombre');
//
//             $cuerpo_modal = $this->load->view('docente/informacion_general/form_info_imss.php', $data_formulario, TRUE);
//
//             $pie_modal = $this->load->view('docente/informacion_general/btn_guardar_editar_datos_siap.php', $data_formulario, TRUE);
//             $this->template->set_titulo_modal($string_value['titilo_modal']);
//             $this->template->set_cuerpo_modal($cuerpo_modal);
//             $this->template->set_pie_modal($pie_modal);
//             $this->template->get_modal();
//         } else {
//             redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
//         }
//     }
//
//     private function carga_vista_completa() {
//         $id_docente = $this->get_datos_sesion(En_datos_sesion::ID_DOCENTE); //Obtiene datos de la session id usuario especificamente
//         $this->load->model("Catalogo_model", "cm");
//         $this->load->model("Docente_model", "dm");
//         $data_formulario = $this->dm->get_historico_datos_generales($id_docente);
// //        pr($data_formulario);
//         $data_formulario['delegaciones'] = dropdown_options($this->cm->get_delegaciones(), 'clave_delegacional', 'nombre');
//         $data['formulario_imss'] = $this->load->view('docente/informacion_general/detalle_info_imss.php', $data_formulario, TRUE);
//         $output['docente'] = $this->dm->get_datos_generales($id_docente);
//         //Cálcula la edad del docente
//         $this->load->library('curp', array('curp' => $output['docente']['curp']));
//         $output['docente']['edad'] = $this->curp->getEdad();
//         $this->load->model("Catalogo_model", "cm");
//         $output['estado_civil'] = dropdown_options($this->cm->get_estado_civil(), 'id_estado_civil', 'estado_civil');
//         $data['formulario_general'] = $this->load->view('docente/informacion_general/form_info_gral.php', $output, TRUE);
//         $string_value = get_elementos_lenguaje(array(En_catalogo_textos::INFORMACION_GENERAL));
//         if (isset($output['docente']['fecha'])) {
//             $data['fecha_actualizacion'] = $output['docente']['fecha'];
//         }
//
//
// }
}
